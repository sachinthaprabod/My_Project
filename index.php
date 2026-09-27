<?php
/**
 * Hotel S&B - Main Home Page
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$current_page = 'home';
$page_title = 'Hotel S&B - Authentic Sri Lankan Kottu, Biryani & Rice | Online Ordering';

// Fetch featured food items for home highlights
try {
    $stmt = $pdo->query("SELECT * FROM `foods` WHERE `is_available` = 1 ORDER BY `id` ASC LIMIT 6");
    $featured_foods = $stmt->fetchAll();
} catch (PDOException $e) {
    $featured_foods = [];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- =========================================================================
     Hero Section with Interactive Image Slider (Carousel)
     (Fulfills Project Requirement 2.3: Interactive Image Slider)
     ========================================================================= -->
<section class="hero-slider">
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4500">
    <!-- Carousel Indicators -->
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>

    <!-- Carousel Slides -->
    <div class="carousel-inner">
      <!-- Slide 1: Kottu & Street Flavours -->
      <div class="carousel-item active hero-slide-1">
        <div class="hero-overlay">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-7">
                <span class="hero-tag"><i class="bi bi-fire me-1"></i> Hot From The Griddle</span>
                <h1 class="hero-title mb-3">Craving Authentic <br><span>Sri Lankan Kottu</span>?</h1>
                <p class="hero-desc">Chopped to perfection with spiced tender meats, fresh garden veggies, and rich curry gravy. Taste the legendary night-bite flavours right at home.</p>
                <div class="d-flex flex-wrap gap-3">
                  <a href="menu.php" class="btn btn-primary-sb btn-lg"><i class="bi bi-cart3 me-1"></i> Order Kottu Online</a>
                  <a href="#featured" class="btn btn-outline-light btn-lg rounded-pill"><i class="bi bi-arrow-down me-1"></i> View Specials</a>
                </div>
              </div>
              <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="images/kottu_chicken.svg" alt="Chicken Kottu" class="img-fluid rounded-4 shadow-lg" style="max-height: 350px;">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 2: Dum Biryani -->
      <div class="carousel-item hero-slide-1">
        <div class="hero-overlay">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-7">
                <span class="hero-tag"><i class="bi bi-stars me-1"></i> Slow-Dum Perfection</span>
                <h1 class="hero-title mb-3">Royal Basmati <br><span>Chicken &amp; Mutton Biryani</span></h1>
                <p class="hero-desc">Infused with saffron, cardamom, cloves, and tender marinated meats, served with fresh mint sambol, hard-boiled egg, and cool raita.</p>
                <div class="d-flex flex-wrap gap-3">
                  <a href="menu.php" class="btn btn-secondary-sb btn-lg"><i class="bi bi-bag-check me-1"></i> Explore Biryanis</a>
                  <a href="contact.php" class="btn btn-outline-light btn-lg rounded-pill">Party Orders</a>
                </div>
              </div>
              <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="images/biryani_chicken.svg" alt="Chicken Biryani" class="img-fluid rounded-4 shadow-lg" style="max-height: 350px;">
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Slide 3: Wok Fried Rice & Devilled Dishes -->
      <div class="carousel-item hero-slide-1">
        <div class="hero-overlay">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-7">
                <span class="hero-tag"><i class="bi bi-lightning-charge me-1"></i> Wok Fresh Specials</span>
                <h1 class="hero-title mb-3">Special Mixed <span>Fried Rice</span> &amp; Devilled</h1>
                <p class="hero-desc">Wok-fired with succulent prawns, tender chicken, crispy devilled chili sides, and sweet homemade chili paste. Ready for quick delivery.</p>
                <div class="d-flex flex-wrap gap-3">
                  <a href="menu.php" class="btn btn-primary-sb btn-lg"><i class="bi bi-basket me-1"></i> Order Fried Rice</a>
                  <a href="menu.php" class="btn btn-outline-light btn-lg rounded-pill">View All 8 Dishes</a>
                </div>
              </div>
              <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="images/rice_mixed.svg" alt="Fried Rice" class="img-fluid rounded-4 shadow-lg" style="max-height: 350px;">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Carousel Controls (Manual Next/Prev) -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>

<!-- =========================================================================
     Why Choose Hotel S&B Section
     ========================================================================= -->
<section class="py-5 bg-white border-bottom">
  <div class="container py-4">
    <div class="row text-center mb-4">
      <div class="col-lg-8 mx-auto">
        <span class="text-danger fw-bold text-uppercase small letter-spacing-2">The Hotel S&B Promise</span>
        <h2 class="display-6 fw-bold">Fresh, Authentic &amp; Delivered Fast</h2>
        <p class="text-muted">We combine generational Sri Lankan culinary tradition with high quality hygiene and express delivery to your doorstep.</p>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-3 col-sm-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-fire"></i>
          </div>
          <h5 class="fw-bold mb-2">Live Griddle &amp; Wok</h5>
          <p class="text-muted small mb-0">Every kottu and rice dish is cooked fresh upon receiving your order, never pre-packed or re-heated.</p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-shield-check"></i>
          </div>
          <h5 class="fw-bold mb-2">100% Quality Spices</h5>
          <p class="text-muted small mb-0">We use authentic Sri Lankan roasted curry powders, organic ghee, and freshly ground whole aromatic spices.</p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-truck"></i>
          </div>
          <h5 class="fw-bold mb-2">Express Hot Delivery</h5>
          <p class="text-muted small mb-0">Insulated thermal food delivery ensuring your rice, roti, and kottu arrive steaming hot within 30-45 minutes.</p>
        </div>
      </div>

      <div class="col-md-3 col-sm-6">
        <div class="feature-box">
          <div class="feature-icon-wrapper">
            <i class="bi bi-phone"></i>
          </div>
          <h5 class="fw-bold mb-2">Effortless Ordering</h5>
          <p class="text-muted small mb-0">Browse our dishes, customize your quantities, add special cooking notes, and track your order online.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================================
     Featured Dishes Section
     (Rice, Kottu, Biryani Highlights)
     ========================================================================= -->
<section id="featured" class="py-5">
  <div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4">
      <div>
        <span class="text-danger fw-bold text-uppercase small">Customer Favorites</span>
        <h2 class="display-6 fw-bold">Popular Dishes at Hotel S&amp;B</h2>
        <p class="text-muted mb-0">Handcrafted Sri Lankan delicacies prepared by our master chefs.</p>
      </div>
      <div class="mt-3 mt-md-0">
        <a href="menu.php" class="btn btn-outline-sb">
          View Complete 8 Items Menu <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>
    </div>

    <div class="row g-4">
      <?php if (!empty($featured_foods)): ?>
        <?php foreach ($featured_foods as $food): ?>
          <div class="col-lg-4 col-md-6">
            <div class="food-card">
              <div class="food-img-container">
                <img src="images/<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" loading="lazy">
                <?php if (!empty($food['badge'])): ?>
                  <span class="food-badge"><i class="bi bi-star-fill text-warning me-1"></i> <?php echo htmlspecialchars($food['badge']); ?></span>
                <?php endif; ?>
                <span class="food-category-tag"><?php echo htmlspecialchars($food['category']); ?></span>
              </div>
              <div class="food-body">
                <h4 class="food-title"><?php echo htmlspecialchars($food['name']); ?></h4>
                <p class="food-desc"><?php echo htmlspecialchars($food['description']); ?></p>
                <div class="food-footer">
                  <div class="food-price"><?php echo format_currency($food['price']); ?></div>
                  <button type="button" 
                          class="btn-add-cart btn-add-to-cart" 
                          data-id="<?php echo $food['id']; ?>"
                          data-name="<?php echo htmlspecialchars($food['name']); ?>"
                          data-price="<?php echo $food['price']; ?>">
                    <i class="bi bi-bag-plus"></i> Add to Order
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <p class="text-muted">No food items found in the database. Please make sure <code>database.sql</code> is imported.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- =========================================================================
     Promo Banner / Call to Action
     ========================================================================= -->
<section class="py-5 bg-dark text-white text-center position-relative overflow-hidden">
  <div class="container py-4 position-relative" style="z-index: 2;">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <span class="badge bg-danger px-3 py-2 text-uppercase mb-3">Dinner &amp; Lunch Special</span>
        <h2 class="display-5 fw-bold mb-3 font-serif">Planning a Family Gathering or Office Lunch?</h2>
        <p class="lead text-light-50 mb-4">
          Order our famous Chicken Dum Biryani or Seafood Cheese Kottu packs. We guarantee piping hot delivery and mouthwatering satisfaction!
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
          <a href="menu.php" class="btn btn-primary-sb btn-lg px-4"><i class="bi bi-cart4 me-2"></i> Order Online Now</a>
          <a href="contact.php" class="btn btn-outline-light btn-lg px-4 rounded-pill">Contact For Party Orders</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================================
     Customer Reviews Section
     ========================================================================= -->
<section class="py-5 bg-light">
  <div class="container py-4">
    <div class="text-center mb-5">
      <span class="text-danger fw-bold text-uppercase small">What Our Food Lovers Say</span>
      <h2 class="display-6 fw-bold">Loved Across the City</h2>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
          <div class="d-flex text-warning mb-3">
            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
          </div>
          <p class="text-muted fst-italic">"The Chicken Dum Biryani at Hotel S&B is hands down the best in the area. Fragrant rice, tender spiced chicken quarter, and the raita was delicious!"</p>
          <div class="mt-auto d-flex align-items-center gap-3">
            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">KS</div>
            <div>
              <h6 class="mb-0 fw-bold">Kamal Silva</h6>
              <small class="text-muted">Verified Customer</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
          <div class="d-flex text-warning mb-3">
            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
          </div>
          <p class="text-muted fst-italic">"The Seafood Cheese Kottu is out of this world! Loaded with real mozzarella, cuttlefish, and prawns. Fast online order system and prompt delivery."</p>
          <div class="mt-auto d-flex align-items-center gap-3">
            <div class="rounded-circle bg-warning text-dark d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">NP</div>
            <div>
              <h6 class="mb-0 fw-bold">Nimali Perera</h6>
              <small class="text-muted">Verified Customer</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 h-100">
          <div class="d-flex text-warning mb-3">
            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
          </div>
          <p class="text-muted fst-italic">"Their Special Mixed Fried Rice with the fiery sweet chili paste and spicy devilled chicken is our weekend family staple. Super easy online checkout."</p>
          <div class="mt-auto d-flex align-items-center gap-3">
            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">DW</div>
            <div>
              <h6 class="mb-0 fw-bold">Dilani Weerasinghe</h6>
              <small class="text-muted">Verified Customer</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
