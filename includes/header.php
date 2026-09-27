<?php
/**
 * Hotel S&B - Common Header Component
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Automatically detect root path whether called from root or subfolder (auth/)
$is_subfolder = (basename(dirname($_SERVER['PHP_SELF'])) === 'auth');
$base = $is_subfolder ? '../' : '';

$page_title = $page_title ?? 'Hotel S&B - Authentic Sri Lankan Flavours & Online Ordering';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($page_title); ?></title>
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <!-- Custom Project CSS -->
  <link rel="stylesheet" href="<?php echo $base; ?>css/style.css">
  
  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?php echo $base; ?>images/logo.svg">
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $base; ?>index.php">
        <img src="<?php echo $base; ?>images/logo.svg" alt="Hotel S&B Logo">
      </a>
      
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#hotelSbNav" aria-controls="hotelSbNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="hotelSbNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page ?? '') === 'home' ? 'active' : ''; ?>" href="<?php echo $base; ?>index.php">
              <i class="bi bi-house-door me-1"></i> Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page ?? '') === 'menu' ? 'active' : ''; ?>" href="<?php echo $base; ?>menu.php">
              <i class="bi bi-journal-text me-1"></i> Order Online / Menu
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page ?? '') === 'contact' ? 'active' : ''; ?>" href="<?php echo $base; ?>contact.php">
              <i class="bi bi-envelope me-1"></i> Contact Us
            </a>
          </li>
        </ul>

        <div class="d-flex align-items-center gap-2">
          <!-- Shopping Cart Trigger Button -->
          <button type="button" class="btn btn-cart-nav d-inline-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer" aria-controls="cartDrawer">
            <i class="bi bi-bag-heart-fill me-1"></i>
            <span>Cart</span>
            <span class="badge-count cart-count-badge" style="display: none;">0</span>
          </button>

          <!-- User Authentication Links -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <div class="dropdown">
              <button class="btn btn-outline-sb dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-5 text-danger"></i>
                <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Account'); ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li><a class="dropdown-item py-2" href="<?php echo $base; ?>dashboard.php"><i class="bi bi-speedometer2 me-2 text-danger"></i> Order Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger" href="<?php echo $base; ?>auth/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Log Out</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="<?php echo $base; ?>auth/login.php" class="btn btn-outline-sb btn-sm px-3">
              <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </a>
            <a href="<?php echo $base; ?>auth/register.php" class="btn btn-primary-sb btn-sm px-3">
              Register
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Global Flash Messages Notification Area -->
  <div class="container">
    <?php 
    if (function_exists('display_flash_message')) {
        echo display_flash_message(); 
    }
    ?>
  </div>
