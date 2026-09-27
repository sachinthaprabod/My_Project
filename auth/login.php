<?php
/**
 * Hotel S&B - Customer Login Page
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    header('Location: ../dashboard.php');
    exit();
}

$error = '';
$identifier = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = sanitize_input($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $error = 'Please enter both your username/email and password.';
    } else {
        try {
            // Find user by either username OR email
            $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `username` = :identifier OR `email` = :identifier LIMIT 1");
            $stmt->execute(['identifier' => $identifier]);
            $user = $stmt->fetch();

            if ($user) {
                // Verify password using password_verify() (with plain text fallback for manual db edits)
                $password_matched = password_verify($password, $user['password']) || ($password === $user['password']);

                if ($password_matched) {
                    // Set session variables (Section 3.3)
                    $_SESSION['user_id']  = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email']    = $user['email'];
                    $_SESSION['phone']    = $user['phone'] ?? '';
                    $_SESSION['address']  = $user['address'] ?? '';

                    set_flash_message('success', 'Welcome back, ' . htmlspecialchars($user['username']) . '!');
                    header('Location: ../dashboard.php');
                    exit();
                } else {
                    $error = 'Invalid credentials. Please verify your password and try again.';
                }
            } else {
                $error = 'No user account found with that username or email address.';
            }
        } catch (PDOException $e) {
            $error = 'Database authentication error: ' . $e->getMessage();
        }
    }
}

$page_title = 'Customer Login - Hotel S&B';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
      
      <div class="card form-card border-0">
        <div class="text-center mb-4">
          <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill fw-bold mb-2">Welcome Back</span>
          <h2 class="fw-bold font-serif mb-1">Customer Sign In</h2>
          <p class="text-muted small">Log in to view orders, save favorite meals, and checkout faster.</p>
        </div>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger shadow-sm py-2 small" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <form id="loginForm" action="login.php" method="POST" novalidate>
          <div class="mb-3">
            <label for="login_identifier" class="form-label">Username or Email</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
              <input type="text" class="form-control" id="login_identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" required placeholder="Enter username or email">
            </div>
            <div class="invalid-feedback">Please enter your username or email.</div>
          </div>

          <div class="mb-4">
            <label for="login_password" class="form-label">Password</label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
              <input type="password" class="form-control" id="login_password" name="password" required placeholder="Enter your password">
            </div>
            <div class="invalid-feedback">Please enter your password.</div>
          </div>

          <button type="submit" class="btn btn-primary-sb w-100 py-2 fs-6">
            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In to Account
          </button>
        </form>

        <!-- Evaluation Quick Login Helper Box -->
        <div class="mt-4 p-3 bg-light rounded-3 border">
          <div class="d-flex align-items-center gap-2 mb-2">
            <i class="bi bi-mortarboard-fill text-danger"></i>
            <strong class="small text-dark">Demo Evaluation Credentials:</strong>
          </div>
          <p class="small text-muted mb-1">
            <strong>Username:</strong> <code>kamal_sb</code> &nbsp;|&nbsp; 
            <strong>Password:</strong> <code>password123</code>
          </p>
          <small class="text-muted fst-italic">Or create a new account using the registration form.</small>
        </div>

        <div class="text-center mt-4 pt-3 border-top">
          <p class="text-muted small mb-0">Don't have an account yet? <a href="register.php" class="text-danger fw-bold">Sign up here</a></p>
        </div>

      </div>

    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
