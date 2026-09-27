<?php
/**
 * Hotel S&B - Order Processing Backend
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: menu.php');
    exit();
}

// 1. Sanitize and retrieve POST data
$customer_name    = sanitize_input($_POST['customer_name'] ?? '');
$customer_email   = sanitize_input($_POST['customer_email'] ?? '');
$customer_phone   = sanitize_input($_POST['customer_phone'] ?? '');
$delivery_address = sanitize_input($_POST['delivery_address'] ?? '');
$payment_method   = sanitize_input($_POST['payment_method'] ?? 'Cash on Delivery');
$order_notes      = sanitize_input($_POST['order_notes'] ?? '');
$raw_cart_data    = $_POST['cart_data'] ?? '[]';

// 2. Decode cart items
$cart_items = json_decode($raw_cart_data, true);

// 3. Server-side validation
$errors = [];

if (empty($customer_name) || strlen($customer_name) < 2) {
    $errors[] = 'Please provide a valid full name.';
}

if (empty($customer_email) || !validate_email($customer_email)) {
    $errors[] = 'Please provide a valid email address.';
}

if (empty($customer_phone)) {
    $errors[] = 'Please provide a delivery contact phone number.';
}

if (empty($delivery_address) || strlen($delivery_address) < 8) {
    $errors[] = 'Please provide a detailed delivery address.';
}

if (empty($cart_items) || !is_array($cart_items)) {
    $errors[] = 'Your shopping basket is empty. Please add food items before ordering.';
}

if (!empty($errors)) {
    set_flash_message('danger', implode(' ', $errors));
    header('Location: menu.php');
    exit();
}

try {
    // 4. Begin database transaction for safe atomic order insertion
    $pdo->beginTransaction();

    // 5. Verify prices from database and calculate authoritative total
    $total_amount = 0.0;
    $verified_items = [];

    $item_query = $pdo->prepare("SELECT `id`, `name`, `price` FROM `foods` WHERE `id` = :id AND `is_available` = 1");

    foreach ($cart_items as $item) {
        $food_id = (int)($item['id'] ?? 0);
        $qty     = max(1, (int)($item['qty'] ?? 1));

        $item_query->execute(['id' => $food_id]);
        $db_food = $item_query->fetch();

        if ($db_food) {
            $price = (float)$db_food['price'];
            $subtotal = $price * $qty;
            $total_amount += $subtotal;

            $verified_items[] = [
                'food_id'   => $db_food['id'],
                'name'      => $db_food['name'],
                'price'     => $price,
                'quantity'  => $qty,
                'subtotal'  => $subtotal
            ];
        }
    }

    if (empty($verified_items)) {
        throw new Exception("None of the items in your cart are currently available.");
    }

    // 6. Generate unique order number
    $order_number = generate_order_number();
    $user_id = is_logged_in() ? (int)$_SESSION['user_id'] : null;

    // 7. Insert Order into `orders` table
    $order_stmt = $pdo->prepare("
        INSERT INTO `orders` (
            `order_number`, `user_id`, `customer_name`, `customer_email`, 
            `customer_phone`, `delivery_address`, `total_amount`, 
            `payment_method`, `order_notes`, `status`, `created_at`
        ) VALUES (
            :order_number, :user_id, :customer_name, :customer_email, 
            :customer_phone, :delivery_address, :total_amount, 
            :payment_method, :order_notes, 'Pending', NOW()
        )
    ");

    $order_stmt->execute([
        'order_number'      => $order_number,
        'user_id'           => $user_id,
        'customer_name'     => $customer_name,
        'customer_email'    => $customer_email,
        'customer_phone'    => $customer_phone,
        'delivery_address'  => $delivery_address,
        'total_amount'      => $total_amount,
        'payment_method'    => $payment_method,
        'order_notes'       => $order_notes
    ]);

    $order_id = $pdo->lastInsertId();

    // 8. Insert individual items into `order_items` table
    $item_insert_stmt = $pdo->prepare("
        INSERT INTO `order_items` (`order_id`, `food_id`, `item_name`, `price`, `quantity`, `subtotal`) 
        VALUES (:order_id, :food_id, :item_name, :price, :quantity, :subtotal)
    ");

    foreach ($verified_items as $v_item) {
        $item_insert_stmt->execute([
            'order_id'   => $order_id,
            'food_id'    => $v_item['food_id'],
            'item_name'  => $v_item['name'],
            'price'      => $v_item['price'],
            'quantity'   => $v_item['quantity'],
            'subtotal'   => $v_item['subtotal']
        ]);
    }

    // Commit transaction
    $pdo->commit();

    // Reset session cart
    $_SESSION['cart'] = [];

    // Success response
    $success_order = [
        'order_number'     => $order_number,
        'customer_name'    => $customer_name,
        'customer_phone'   => $customer_phone,
        'delivery_address' => $delivery_address,
        'total_amount'     => $total_amount,
        'payment_method'   => $payment_method,
        'items'            => $verified_items
    ];

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    set_flash_message('danger', 'Order placement failed: ' . $e->getMessage());
    header('Location: menu.php');
    exit();
}

$page_title = 'Order Confirmation - Hotel S&B';
require_once __DIR__ . '/includes/header.php';
?>

<script>
  // Signal JavaScript to clear client-side localStorage cart upon successful order placement
  window.ORDER_PLACED_SUCCESS = true;
</script>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      
      <!-- Confirmation Banner Card -->
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-4">
        <div class="bg-success text-white text-center py-4 px-3">
          <i class="bi bi-patch-check-fill" style="font-size: 3.5rem;"></i>
          <h2 class="fw-bold mt-2 font-serif">Order Successfully Placed!</h2>
          <p class="mb-0">Thank you, <strong><?php echo htmlspecialchars($success_order['customer_name']); ?></strong>. Your meal is being prepared at Hotel S&B!</p>
        </div>

        <div class="card-body p-4 p-md-5">
          <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4">
            <div>
              <span class="text-muted small text-uppercase">Order Reference</span>
              <h4 class="fw-bold text-danger mb-0"><?php echo htmlspecialchars($success_order['order_number']); ?></h4>
            </div>
            <div class="mt-2 mt-sm-0 text-sm-end">
              <span class="badge bg-warning text-dark px-3 py-2 fs-6">Status: Pending Kitchen Preparation</span>
            </div>
          </div>

          <h5 class="fw-bold mb-3"><i class="bi bi-receipt me-2 text-danger"></i> Order Summary</h5>
          <div class="table-responsive mb-4">
            <table class="table table-borderless align-middle">
              <thead class="table-light">
                <tr>
                  <th>Dish Item</th>
                  <th class="text-center">Qty</th>
                  <th class="text-end">Price</th>
                  <th class="text-end">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($success_order['items'] as $item): ?>
                  <tr class="border-bottom">
                    <td class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                    <td class="text-end"><?php echo format_currency($item['price']); ?></td>
                    <td class="text-end fw-bold"><?php echo format_currency($item['subtotal']); ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <td colspan="3" class="text-end fw-bold fs-5 pt-3">Grand Total:</td>
                  <td class="text-end fw-bold fs-5 text-danger pt-3"><?php echo format_currency($success_order['total_amount']); ?></td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Delivery Details Box -->
          <div class="bg-light p-4 rounded-3 mb-4">
            <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Delivery &amp; Contact Info</h6>
            <div class="row g-2 small">
              <div class="col-md-6">
                <strong>Recipient:</strong> <?php echo htmlspecialchars($success_order['customer_name']); ?>
              </div>
              <div class="col-md-6">
                <strong>Contact Phone:</strong> <?php echo htmlspecialchars($success_order['customer_phone']); ?>
              </div>
              <div class="col-md-6">
                <strong>Payment Method:</strong> <?php echo htmlspecialchars($success_order['payment_method']); ?>
              </div>
              <div class="col-md-6">
                <strong>Estimated Delivery:</strong> 30 - 45 Minutes
              </div>
              <div class="col-12 mt-2">
                <strong>Delivery Address:</strong> <?php echo nl2br(htmlspecialchars($success_order['delivery_address'])); ?>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
            <a href="menu.php" class="btn btn-outline-sb">
              <i class="bi bi-arrow-left me-1"></i> Back to Menu
            </a>
            
            <?php if (is_logged_in()): ?>
              <a href="dashboard.php" class="btn btn-primary-sb">
                <i class="bi bi-speedometer2 me-1"></i> View Order Dashboard
              </a>
            <?php else: ?>
              <a href="auth/register.php" class="btn btn-secondary-sb">
                <i class="bi bi-person-plus me-1"></i> Create Account to Track Orders
              </a>
            <?php endif; ?>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
