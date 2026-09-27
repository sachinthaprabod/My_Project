<?php
/**
 * Hotel S&B - Contact Us Page & Inquiries Handler
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$current_page = 'contact';
$page_title = 'Contact Hotel S&B - Catering & Inquiries';

$form_success = false;
$errors = [];

// Handle Form Submission (Section 3.4 & 3.5: Store in messages table)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize_input($_POST['name'] ?? '');
    $email   = sanitize_input($_POST['email'] ?? '');
    $subject = sanitize_input($_POST['subject'] ?? 'General Inquiry');
    $message = sanitize_input($_POST['message'] ?? '');

    // Server-side validation
    if (empty($name) || strlen($name) < 2) {
        $errors[] = 'Please enter your full name.';
    }

    if (empty($email) || !validate_email($email)) {
        $errors[] = 'Please provide a valid email address.';
    }

    if (empty($message) || strlen($message) < 10) {
        $errors[] = 'Your message must be at least 10 characters in length.';
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO `messages` (`name`, `email`, `subject`, `message`, `created_at`) 
                VALUES (:name, :email, :subject, :message, NOW())
            ");
            $stmt->execute([
                'name'    => $name,
                'email'   => $email,
                'subject' => $subject,
                'message' => $message
            ]);

            $form_success = true;
            set_flash_message('success', 'Thank you, ' . htmlspecialchars($name) . '! Your inquiry has been submitted. Our management team will get back to you shortly.');
            
            // Clear input variables upon success
            $name = $email = $subject = $message = '';
        } catch (PDOException $e) {
            $errors[] = 'Database error while saving message: ' . $e->getMessage();
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
  
  <div class="row text-center mb-5">
    <div class="col-lg-8 mx-auto">
      <span class="text-danger fw-bold text-uppercase small">Get In Touch</span>
      <h1 class="display-5 fw-bold font-serif">Contact Hotel S&amp;B</h1>
      <p class="lead text-muted">
        Have questions about bulk orders, party catering, dietary requirements, or delivery coverage? We are always here to help.
      </p>
    </div>
  </div>

  <div class="row g-5">
    
    <!-- Contact Info Column -->
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white h-100">
        <h4 class="fw-bold font-serif mb-4 text-dark">Hotel Details</h4>
        
        <div class="d-flex align-items-start gap-3 mb-4">
          <div class="rounded-circle bg-danger text-white p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-geo-alt-fill fs-5"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Our Location</h6>
            <p class="text-muted small mb-0">No. 88, Kandy Road, Anuradhapura, North Central Province, Sri Lanka</p>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3 mb-4">
          <div class="rounded-circle bg-warning text-dark p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-telephone-fill fs-5"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Direct Hotline</h6>
            <p class="text-muted small mb-0">+94 25 222 3456 / +94 77 123 4567<br>Hotline available 24/7 for delivery tracking</p>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3 mb-4">
          <div class="rounded-circle bg-success text-white p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-envelope-fill fs-5"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Email Inquiries</h6>
            <p class="text-muted small mb-0">orders@hotelsb.lk<br>info@hotelsb.lk</p>
          </div>
        </div>

        <div class="d-flex align-items-start gap-3 mb-4">
          <div class="rounded-circle bg-primary text-white p-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
            <i class="bi bi-clock-fill fs-5"></i>
          </div>
          <div>
            <h6 class="fw-bold mb-1">Kitchen Hours</h6>
            <p class="text-muted small mb-0">Mon – Fri: 10:00 AM – 11:00 PM<br>Sat – Sun: 09:30 AM – 11:30 PM</p>
          </div>
        </div>

        <div class="mt-auto border-top pt-4">
          <h6 class="fw-bold mb-2">Follow Hotel S&amp;B</h6>
          <div class="d-flex gap-3 fs-4 text-danger">
            <a href="#" class="text-danger"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-danger"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-danger"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>
      </div>
    </div>

    <!-- Contact Form Column -->
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <h4 class="fw-bold font-serif mb-2 text-dark">Send Us a Message</h4>
        <p class="text-muted small mb-4">Fill out the form below. We will review your message and reply promptly.</p>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
              <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form id="contactForm" action="contact.php" method="POST" novalidate>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="contact_name" class="form-label">Your Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="contact_name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required placeholder="e.g. Sunil Perera">
              <div class="invalid-feedback">Please enter your name.</div>
            </div>

            <div class="col-md-6">
              <label for="contact_email" class="form-label">Email Address <span class="text-danger">*</span></label>
              <input type="email" class="form-control" id="contact_email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required placeholder="name@example.com">
              <div class="invalid-feedback">Please provide a valid email address.</div>
            </div>

            <div class="col-12">
              <label for="contact_subject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="contact_subject" name="subject" value="<?php echo htmlspecialchars($subject ?? 'General Inquiry'); ?>" placeholder="e.g. Catering quote for 30 pax">
              <div class="invalid-feedback">Please enter a subject.</div>
            </div>

            <div class="col-12">
              <label for="contact_message" class="form-label">Your Message <span class="text-danger">*</span></label>
              <textarea class="form-control" id="contact_message" name="message" rows="5" required placeholder="Type your message, query or feedback here..."><?php echo htmlspecialchars($message ?? ''); ?></textarea>
              <div class="invalid-feedback">Message must be at least 10 characters long.</div>
            </div>

            <div class="col-12 mt-4">
              <button type="submit" class="btn btn-primary-sb btn-lg w-100">
                <i class="bi bi-send-fill me-2"></i> Submit Inquiry
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
