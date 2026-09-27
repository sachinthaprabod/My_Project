<?php
/**
 * Hotel S&B - Customer Registration Page
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: ../dashboard.php');
    exit();
}

$errors = [];
$username = '';
$email = '';
$phone = '';
$address = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username         = sanitize_input($_POST['username'] ?? '');
    $email            = sanitize_input($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone            = sanitize_input($_POST['phone'] ?? '');
    $address          = sanitize_input($_POST['address'] ?? '');

    // Server-side validation
    if (empty($username) || strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }

    if (empty($email) || !validate_email($email)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    // Check if username or email already exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT `id` FROM `users` WHERE `username` = :username OR `email` = :email LIMIT 1");
            $stmt->execute(['username' => $username, 'email' => $email]);
            if ($stmt->fetch()) {
                $errors[] = 'A user with this username or email already exists.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Database query error: ' . $e->getMessage();
        }
    }

    // Insert user using password_hash() (Section 3.3)
    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_stmt = $pdo->prepare("
                INSERT INTO `users` (`username`, `email`, `password`, `phone`, `address`, `created_at`) 
                VALUES (:username, :email, :password, :phone, :address, NOW())
            ");
            $insert_stmt->execute([
                'username' => $username,
                'email'    => $email,
                'password' => $hashed_password,
                'phone'    => $phone,
                'address'  => $address
            ]);

            // Auto-login newly registered user
            $new_user_id = $pdo->lastInsertId();
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            $_SESSION['address'] = $address;

            set_flash_message('success', 'Welcome to Hotel S&B, ' . htmlspecialchars($username) . '! Your account has been created.');
            header('Location: ../dashboard.php');
            exit();

        } catch (PDOException $e) {
            $errors[] = 'Registration failed: ' . $e->getMessage();
        }
    }
}

$page_title = 'Create an Account - Hotel S&B';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8">
      
      <div class="card form-card border-0">
        <div class="text-center mb-4">
          <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold mb-2">Join Hotel S&amp;B</span>
          <h2 class="fw-bold font-serif mb-1">Create Customer Account</h2>
          <p class="text-muted small">Sign up to place food orders, track delivery, and enjoy quick reordering.</p>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger shadow-sm py-2">
            <ul class="mb-0 small">
              <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form id="registerForm" action="register.php" method="POST" novalidate>
          <div class="mb-3">
            <label for="reg_username" class="form-label">Username <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="reg_username" name="username" value="<?php echo htmlspecialchars($username); ?>" required placeholder="e.g. kamal_sb">
            </div>
            <div class="invalid-feedback">Username must be at least 3 characters.</div>
          </div>

          <div class="mb-3">
            <label for="reg_email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
              <input type="email" class="form-control" id="reg_email" name="email" value="<?php echo htmlspecialchars($email); ?>" required placeholder="name@example.com">
            </div>
            <div class="invalid-feedback">Please enter a valid email address.</div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-md-6">
              <label for="reg_password" class="form-label">Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                <input type="password" class="form-control" id="reg_password" name="password" required placeholder="Min 6 characters">
              </div>
              <div class="invalid-feedback">Password must be at least 6 characters.</div>
            </div>

            <div class="col-md-6">
              <label for="reg_confirm_password" class="form-label">Confirm Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                <input type="password" class="form-control" id="reg_confirm_password" name="confirm_password" required placeholder="Re-type password">
              </div>
              <div class="invalid-feedback">Passwords do not match.</div>
            </div>
          </div>

          <div class="mb-3">
            <label for="reg_phone" class="form-label">Contact Phone (Optional)</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
              <input type="tel" class="form-control" id="reg_phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="0771234567">
            </div>
            <div class="invalid-feedback">Please enter a valid Sri Lankan phone number.</div>
          </div>

          <div class="mb-4">
            <label for="reg_address" class="form-label">Default Delivery Address (Optional)</label>
            <textarea class="form-control" id="reg_address" name="address" rows="2" placeholder="House no, Street name, City"><?php echo htmlspecialchars($address); ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary-sb w-100 py-2 fs-6">
            <i class="bi bi-person-check-fill me-1"></i> Register Account
          </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top">
          <p class="text-muted small mb-0">Already have an account? <a href="login.php" class="text-danger fw-bold">Log in here</a></p>
        </div>

      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
