<?php
/**
 * Hotel S&B - Customer Dashboard
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Enforce login for dashboard
require_login('auth/login.php');

$current_page = 'dashboard';
$page_title = 'My Orders & Account - Hotel S&B';

$user_id = (int)$_SESSION['user_id'];

// Handle Profile Information Update (Phone / Address)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $phone   = sanitize_input($_POST['phone'] ?? '');
    $address = sanitize_input($_POST['address'] ?? '');

    try {
        $upd = $pdo->prepare("UPDATE `users` SET `phone` = :phone, `address` = :address WHERE `id` = :id");
        $upd->execute([
            'phone'   => $phone,
            'address' => $address,
            'id'      => $user_id
        ]);
        $_SESSION['phone'] = $phone;
        $_SESSION['address'] = $address;
        set_flash_message('success', 'Your delivery profile was successfully updated!');
        header('Location: dashboard.php');
        exit();
    } catch (PDOException $e) {
        set_flash_message('danger', 'Failed to update profile: ' . $e->getMessage());
    }
}

// Fetch current user details
$user_stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
$user_stmt->execute(['id' => $user_id]);
$current_user = $user_stmt->fetch();

// Fetch orders placed by this user along with their items
$orders_stmt = $pdo->prepare("
    SELECT * FROM `orders` 
    WHERE `user_id` = :user_id 
    ORDER BY `created_at` DESC
");
$orders_stmt->execute(['user_id' => $user_id]);
$user_orders = $orders_stmt->fetchAll();

// Calculate user statistics
$total_orders_count = count($user_orders);
$total_spent = 0.0;
$pending_count = 0;

foreach ($user_orders as $ord) {
    $total_spent += (float)$ord['total_amount'];
    if (in_array($ord['status'], ['Pending', 'Preparing', 'Out for Delivery'])) {
        $pending_count++;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
  
  <!-- Welcome Header Banner -->
  <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 60px; height: 60px;">
          <?php echo strtoupper(substr($current_user['username'] ?? 'U', 0, 2)); ?>
        </div>
        <div>
          <h3 class="fw-bold mb-1 font-serif">Welcome back, <?php echo htmlspecialchars($current_user['username']); ?>!</h3>
          <p class="text-muted mb-0 small">
            <i class="bi bi-envelope me-1"></i> <?php echo htmlspecialchars($current_user['email']); ?> &bull; 
            <i class="bi bi-calendar3 ms-2 me-1"></i> Member since <?php echo date('M Y', strtotime($current_user['created_at'])); ?>
          </p>
        </div>
      </div>
      <div>
        <a href="menu.php" class="btn btn-primary-sb">
          <i class="bi bi-plus-circle me-1"></i> Place New Order
        </a>
      </div>
    </div>
  </div>

  <!-- Quick Statistics Cards -->
  <div class="row g-4 mb-5">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small text-uppercase fw-bold">Total Orders</span>
            <h2 class="fw-bold mt-1 mb-0 text-dark"><?php echo $total_orders_count; ?></h2>
          </div>
          <div class="rounded-circle bg-danger-subtle text-danger p-3 fs-3">
            <i class="bi bi-bag-check-fill"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small text-uppercase fw-bold">Active / In-Kitchen</span>
            <h2 class="fw-bold mt-1 mb-0 text-warning"><?php echo $pending_count; ?></h2>
          </div>
          <div class="rounded-circle bg-warning-subtle text-warning p-3 fs-3">
            <i class="bi bi-hourglass-split"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="text-muted small text-uppercase fw-bold">Total Spent</span>
            <h2 class="fw-bold mt-1 mb-0 text-success"><?php echo format_currency($total_spent); ?></h2>
          </div>
          <div class="rounded-circle bg-success-subtle text-success p-3 fs-3">
            <i class="bi bi-cash-stack"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Column: Order History -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h4 class="fw-bold mb-4 font-serif d-flex align-items-center gap-2">
          <i class="bi bi-clock-history text-danger"></i> Your Order History
        </h4>

        <?php if (!empty($user_orders)): ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light small text-uppercase">
                <tr>
                  <th>Order Reference</th>
                  <th>Date &amp; Time</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Details</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($user_orders as $order): ?>
                  <?php
                    // Fetch items for this order
                    $items_stmt = $pdo->prepare("SELECT * FROM `order_items` WHERE `order_id` = :oid");
                    $items_stmt->execute(['oid' => $order['id']]);
                    $items = $items_stmt->fetchAll();

                    // Status badge class mapping
                    $status_class = match ($order['status']) {
                        'Preparing'        => 'badge-status-preparing',
                        'Out for Delivery' => 'badge-status-out-for-delivery',
                        'Delivered'        => 'badge-status-delivered',
                        'Cancelled'        => 'badge-status-cancelled',
                        default            => 'badge-status-pending',
                    };
                  ?>
                  <tr>
                    <td>
                      <span class="fw-bold text-dark"><?php echo htmlspecialchars($order['order_number']); ?></span>
                      <div class="small text-muted"><?php echo count($items); ?> dish item(s)</div>
                    </td>
                    <td class="small">
                      <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?>
                    </td>
                    <td>
                      <strong class="text-danger"><?php echo format_currency($order['total_amount']); ?></strong>
                    </td>
                    <td>
                      <span class="badge <?php echo $status_class; ?> px-2 py-1">
                        <?php echo htmlspecialchars($order['status']); ?>
                      </span>
                    </td>
                    <td>
                      <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#orderCollapse_<?php echo $order['id']; ?>" aria-expanded="false">
                        <i class="bi bi-eye"></i> View
                      </button>
                    </td>
                  </tr>

                  <!-- Collapsible Item Details Row -->
                  <tr class="collapse" id="orderCollapse_<?php echo $order['id']; ?>">
                    <td colspan="5" class="bg-light p-3">
                      <div class="border rounded p-3 bg-white">
                        <h6 class="fw-bold mb-2">Items Ordered:</h6>
                        <ul class="list-group list-group-flush mb-3">
                          <?php foreach ($items as $it): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1 small">
                              <span><?php echo htmlspecialchars($it['item_name']); ?> &times; <strong><?php echo $it['quantity']; ?></strong></span>
                              <span><?php echo format_currency($it['subtotal']); ?></span>
                            </li>
                          <?php endforeach; ?>
                        </ul>
                        <div class="small text-muted">
                          <strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['delivery_address']); ?><br>
                          <strong>Payment:</strong> <?php echo htmlspecialchars($order['payment_method']); ?>
                          <?php if (!empty($order['order_notes'])): ?>
                            <br><strong>Cooking Notes:</strong> <?php echo htmlspecialchars($order['order_notes']); ?>
                          <?php endif; ?>
                        </div>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else: ?>
          <div class="text-center py-5">
            <i class="bi bi-receipt-cutoff text-muted" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-secondary">No Orders Placed Yet</h5>
            <p class="text-muted small">You haven't ordered any delicious Kottu or Biryani yet. Check out our menu!</p>
            <a href="menu.php" class="btn btn-primary-sb btn-sm">Explore Menu &amp; Order</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Right Column: Delivery Profile -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
        <h5 class="fw-bold mb-3 font-serif d-flex align-items-center gap-2">
          <i class="bi bi-person-gear text-danger"></i> Delivery Profile
        </h5>
        <p class="text-muted small">Keep your delivery details updated for faster checkout on future orders.</p>

        <form action="dashboard.php" method="POST">
          <input type="hidden" name="action" value="update_profile">
          
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($current_user['username']); ?>" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-control bg-light" value="<?php echo htmlspecialchars($current_user['email']); ?>" readonly>
          </div>

          <div class="mb-3">
            <label for="prof_phone" class="form-label">Contact Phone</label>
            <input type="text" class="form-control" id="prof_phone" name="phone" value="<?php echo htmlspecialchars($current_user['phone'] ?? ''); ?>" placeholder="0771234567">
          </div>

          <div class="mb-3">
            <label for="prof_address" class="form-label">Default Delivery Address</label>
            <textarea class="form-control" id="prof_address" name="address" rows="3" placeholder="No. 12, Main Street, City..."><?php echo htmlspecialchars($current_user['address'] ?? ''); ?></textarea>
          </div>

          <button type="submit" class="btn btn-outline-sb w-100">
            <i class="bi bi-floppy me-1"></i> Save Changes
          </button>
        </form>
      </div>
    </div>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
