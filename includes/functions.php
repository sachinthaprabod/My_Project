<?php
/**
 * Hotel S&B - Helper Functions
 * ICT 2209 Web Technologies Mini Project
 * Rajarata University of Sri Lanka
 */

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize and clean user input strings
 *
 * @param string $data
 * @return string
 */
function sanitize_input($data) {
    if (is_null($data)) return '';
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate email format
 *
 * @param string $email
 * @return bool
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Check if the user is authenticated
 *
 * @return bool
 */
function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Enforce authentication on protected pages
 *
 * @param string $redirect_to
 * @return void
 */
function require_login($redirect_to = '../auth/login.php') {
    if (!is_logged_in()) {
        set_flash_message('warning', 'Please login to access this page.');
        header("Location: $redirect_to");
        exit();
    }
}

/**
 * Set a flash message to be shown on next page load
 *
 * @param string $type 'success', 'danger', 'info', 'warning'
 * @param string $message
 * @return void
 */
function set_flash_message($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Retrieve and clear the flash message
 *
 * @return array|null
 */
function get_flash_message() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Render flash message as Bootstrap alert HTML
 *
 * @return string
 */
function display_flash_message() {
    $flash = get_flash_message();
    if ($flash) {
        $type = htmlspecialchars($flash['type']);
        $msg = htmlspecialchars($flash['message']);
        return "
        <div class='alert alert-{$type} alert-dismissible fade show my-3 shadow-sm' role='alert'>
            <i class='bi bi-info-circle-fill me-2'></i>{$msg}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
    }
    return '';
}

/**
 * Format numeric value as Sri Lankan Rupees
 *
 * @param float|int $amount
 * @return string
 */
function format_currency($amount) {
    return 'Rs. ' . number_format((float)$amount, 2);
}

/**
 * Retrieve food items from database
 *
 * @param PDO $pdo
 * @param string|null $category
 * @return array
 */
function get_food_items($pdo, $category = null) {
    try {
        if ($category && $category !== 'All') {
            $stmt = $pdo->prepare("SELECT * FROM `foods` WHERE `category` = :category AND `is_available` = 1 ORDER BY `id` ASC");
            $stmt->execute(['category' => $category]);
        } else {
            $stmt = $pdo->query("SELECT * FROM `foods` WHERE `is_available` = 1 ORDER BY `id` ASC");
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

/**
 * Fetch a single food item by ID
 *
 * @param PDO $pdo
 * @param int $id
 * @return array|false
 */
function get_food_item_by_id($pdo, $id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM `foods` WHERE `id` = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Initialize shopping cart if empty
 */
function init_cart() {
    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}

/**
 * Get total number of items in shopping cart
 *
 * @return int
 */
function get_cart_count() {
    init_cart();
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += (int)($item['qty'] ?? 1);
    }
    return $count;
}

/**
 * Calculate grand total price of items in cart
 *
 * @return float
 */
function get_cart_total() {
    init_cart();
    $total = 0.0;
    foreach ($_SESSION['cart'] as $item) {
        $qty = (int)($item['qty'] ?? 1);
        $price = (float)($item['price'] ?? 0);
        $total += ($qty * $price);
    }
    return $total;
}

/**
 * Generate a unique readable Order Number
 *
 * @return string
 */
function generate_order_number() {
    return 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}
?>
