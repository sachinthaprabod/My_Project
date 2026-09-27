<?php
/**
 * Hotel S&B - Common Footer & Cart Drawer Component
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

$is_subfolder = (basename(dirname($_SERVER['PHP_SELF'])) === 'auth');
$base = $is_subfolder ? '../' : '';
?>

  <!-- Shopping Cart Offcanvas Drawer -->
  <div class="offcanvas offcanvas-end offcanvas-cart" tabindex="-1" id="cartDrawer" aria-labelledby="cartDrawerLabel">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="cartDrawerLabel">
        <i class="bi bi-basket3-fill text-danger"></i> Your Food Basket
      </h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    
    <div class="offcanvas-body d-flex flex-column">
      <div id="cartItemsContainer" class="flex-grow-1 overflow-y-auto mb-3">
        <!-- Rendered dynamically by js/main.js -->
      </div>

      <div id="emptyCartNotice" class="text-center py-5">
        <i class="bi bi-cart-x text-muted" style="font-size: 3.5rem;"></i>
        <h6 class="mt-3 text-secondary">Your cart is currently empty</h6>
        <p class="text-muted small">Explore our signature rice, kottu, and biryani to get started!</p>
        <a href="<?php echo $base; ?>menu.php" class="btn btn-outline-sb btn-sm mt-2">Browse Menu</a>
      </div>

      <div class="border-top pt-3 mt-auto">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <span class="fw-bold fs-6">Subtotal:</span>
          <span class="fw-bold fs-5 text-danger" id="cartDrawerTotal">Rs. 0.00</span>
        </div>
        <button type="button" id="btnCartCheckout" class="btn btn-primary-sb w-100 py-2 d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#checkoutModal" disabled>
          <i class="bi bi-check-circle-fill"></i> Proceed to Checkout
        </button>
      </div>
    </div>
  </div>

  <!-- Checkout Modal Dialog -->
  <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header bg-dark text-white">
          <h5 class="modal-title font-serif" id="checkoutModalLabel">
            <i class="bi bi-bag-check-fill text-danger me-2"></i> Complete Your Order
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form id="checkoutOrderForm" action="<?php echo $base; ?>order_process.php" method="POST">
          <div class="modal-body p-4">
            <!-- Hidden input holding JSON serialized cart items from JavaScript -->
            <input type="hidden" name="cart_data" id="checkoutCartData" value="">

            <div class="alert alert-light border d-flex justify-content-between align-items-center mb-3">
              <span class="fw-bold text-muted">Grand Total:</span>
              <span class="fw-bold fs-5 text-danger" id="checkoutModalGrandTotal">Rs. 0.00</span>
            </div>

            <div class="mb-3">
              <label for="order_customer_name" class="form-label">Full Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="order_customer_name" name="customer_name" value="<?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?>" required placeholder="e.g. Kamal Perera">
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="order_customer_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="order_customer_email" name="customer_email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required placeholder="name@example.com">
              </div>
              <div class="col-md-6">
                <label for="order_customer_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control" id="order_customer_phone" name="customer_phone" value="<?php echo htmlspecialchars($_SESSION['phone'] ?? ''); ?>" required placeholder="0771234567">
              </div>
            </div>

            <div class="mb-3">
              <label for="order_delivery_address" class="form-label">Delivery Address <span class="text-danger">*</span></label>
              <textarea class="form-control" id="order_delivery_address" name="delivery_address" rows="2" required placeholder="House number, Street name, City (e.g. No 24, Main St, Anuradhapura)"><?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
              <label for="order_payment_method" class="form-label">Payment Method</label>
              <select class="form-select" id="order_payment_method" name="payment_method">
                <option value="Cash on Delivery" selected>Cash on Delivery (COD)</option>
                <option value="Card on Delivery">Card on Delivery (POS Machine)</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="order_notes" class="form-label">Special Cooking / Delivery Notes (Optional)</label>
              <input type="text" class="form-control" id="order_notes" name="order_notes" placeholder="e.g. Extra spicy, no cutlery, call upon arrival">
            </div>
          </div>

          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary-sb">Confirm & Place Order</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast Notification Anchor -->
  <div class="sb-toast-container"></div>

  <!-- Footer Section -->
  <footer class="pt-5 pb-4">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="d-flex align-items-center gap-2 mb-3">
            <img src="<?php echo $base; ?>images/logo.svg" alt="Hotel S&B Logo" style="height: 42px; filter: brightness(0) invert(1);">
          </div>
          <p class="text-light-50 small mb-3">
            Hotel S&B is your premier culinary destination for authentic Sri Lankan comfort foods. Serving hot, griddle-chopped kottu, slow-dum basmati biryani, and wok-fired rice made with high quality farm-fresh ingredients.
          </p>
          <div class="d-flex gap-3 fs-5">
            <a href="#" class="text-white-50"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-white-50"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-white-50"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-6">
          <h6 class="footer-heading">Quick Links</h6>
          <ul class="list-unstyled small">
            <li class="mb-2"><a href="<?php echo $base; ?>index.php">Home</a></li>
            <li class="mb-2"><a href="<?php echo $base; ?>menu.php">Online Order & Menu</a></li>
            <li class="mb-2"><a href="<?php echo $base; ?>contact.php">Contact Us</a></li>
            <li class="mb-2"><a href="<?php echo $base; ?>auth/login.php">Customer Login</a></li>
            <li class="mb-2"><a href="<?php echo $base; ?>auth/register.php">Create Account</a></li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h6 class="footer-heading">Opening Hours</h6>
          <ul class="list-unstyled small text-light-50">
            <li class="mb-2 d-flex justify-content-between">
              <span>Monday – Friday:</span>
              <strong class="text-white">10:00 AM – 11:00 PM</strong>
            </li>
            <li class="mb-2 d-flex justify-content-between">
              <span>Saturday – Sunday:</span>
              <strong class="text-white">09:30 AM – 11:30 PM</strong>
            </li>
            <li class="mt-3 text-warning">
              <i class="bi bi-bicycle me-1"></i> Hot Delivery within 30-45 Mins!
            </li>
          </ul>
        </div>

        <div class="col-lg-3 col-md-6">
          <h6 class="footer-heading">Contact & Location</h6>
          <ul class="list-unstyled small text-light-50">
            <li class="mb-2 d-flex gap-2">
              <i class="bi bi-geo-alt-fill text-danger mt-1"></i>
              <span>No. 88, Kandy Road, Anuradhapura, Sri Lanka</span>
            </li>
            <li class="mb-2 d-flex gap-2">
              <i class="bi bi-telephone-fill text-danger mt-1"></i>
              <span>+94 25 222 3456 / +94 77 123 4567</span>
            </li>
            <li class="mb-2 d-flex gap-2">
              <i class="bi bi-envelope-fill text-danger mt-1"></i>
              <span>orders@hotelsb.lk</span>
            </li>
          </ul>
        </div>
      </div>

      <hr class="border-secondary my-4">

      <div class="row align-items-center small text-light-50">
        <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
          &copy; <?php echo date('Y'); ?> Hotel S&B. All rights reserved.
        </div>
        <div class="col-md-6 text-center text-md-end">
          <span class="badge bg-secondary">ICT 2209 - Web Technologies Mini Project</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap 5.3 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <!-- Project JavaScript -->
  <script src="<?php echo $base; ?>js/main.js"></script>
</body>
</html>
