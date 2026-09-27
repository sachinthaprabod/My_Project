<?php
/**
 * Hotel S&B - Online Ordering & Food Menu Page
 * ICT 2209 Web Technologies Mini Project - Rajarata University of Sri Lanka
 */

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$current_page = 'menu';
$page_title = 'Online Menu & Food Ordering - Hotel S&B';

// Fetch all available food items from database
try {
    $stmt = $pdo->query("SELECT * FROM `foods` WHERE `is_available` = 1 ORDER BY `category` ASC, `id` ASC");
    $all_foods = $stmt->fetchAll();
    
    // Extract unique categories for dynamic filter navigation
    $categories = array_unique(array_column($all_foods, 'category'));
} catch (PDOException $e) {
    $all_foods = [];
    $categories = [];
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- =========================================================================
     Menu Page Header Banner
     ========================================================================= -->
<section class="py-5 bg-dark text-white text-center position-relative">
  <div class="container py-3">
    <span class="badge bg-danger px-3 py-2 text-uppercase mb-2">Live Kitchen &amp; Takeaway</span>
    <h1 class="display-5 fw-bold font-serif mb-2">Hotel S&amp;B Online Ordering Menu</h1>
    <p class="lead text-light-50 mx-auto" style="max-width: 650px;">
      Explore our signature Sri Lankan rice, hot griddle kottu, and slow-dum biryanis. Add your favorite items to your basket and enjoy fast delivery!
    </p>
  </div>
</section>

<!-- =========================================================================
     Interactive Filters & Menu Grid Section
     (Fulfills Project Requirement 2.2 & 2.3: Dynamic Content Filtering)
     ========================================================================= -->
<section class="py-5">
  <div class="container">
    
    <!-- Search Bar & Controls -->
    <div class="row justify-content-center mb-4">
      <div class="col-md-6">
        <div class="input-group shadow-sm">
          <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
          <input type="text" id="foodSearchInput" class="form-control border-start-0 py-2 ps-0" placeholder="Search dishes (e.g. Kottu, Biryani, Rice)..." aria-label="Search food">
        </div>
      </div>
    </div>

    <!-- Category Filter Buttons (Dynamic JS Filtering) -->
    <div class="category-filter-nav">
      <button type="button" class="filter-btn active" data-category="All">
        <i class="bi bi-grid-fill me-1"></i> All Items (<?php echo count($all_foods); ?>)
      </button>
      <button type="button" class="filter-btn" data-category="Rice">
        <i class="bi bi-pie-chart-fill me-1"></i> Rice Dishes
      </button>
      <button type="button" class="filter-btn" data-category="Kottu">
        <i class="bi bi-fire me-1"></i> Kottu Roti
      </button>
      <button type="button" class="filter-btn" data-category="Biryani">
        <i class="bi bi-stars me-1"></i> Dum Biryani
      </button>
      <button type="button" class="filter-btn" data-category="Curries">
        <i class="bi bi-egg-fried me-1"></i> Curries &amp; Roti
      </button>
      <button type="button" class="filter-btn" data-category="Sides">
        <i class="bi bi-shield-shaded me-1"></i> Devilled / Sides
      </button>
      <button type="button" class="filter-btn" data-category="Beverages">
        <i class="bi bi-cup-straw me-1"></i> Drinks &amp; Shakes
      </button>
    </div>

    <!-- Food Items Grid -->
    <div class="row g-4" id="foodGridContainer">
      <?php if (!empty($all_foods)): ?>
        <?php foreach ($all_foods as $food): ?>
          <div class="col-lg-4 col-md-6 food-item-col" 
               data-category="<?php echo htmlspecialchars($food['category']); ?>"
               data-name="<?php echo htmlspecialchars($food['name']); ?>"
               data-desc="<?php echo htmlspecialchars($food['description']); ?>">
            <div class="food-card">
              <div class="food-img-container">
                <img src="images/<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" loading="lazy">
                <?php if (!empty($food['badge'])): ?>
                  <span class="food-badge"><i class="bi bi-award-fill me-1"></i> <?php echo htmlspecialchars($food['badge']); ?></span>
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
                          data-price="<?php echo $food['price']; ?>"
                          title="Add <?php echo htmlspecialchars($food['name']); ?> to cart">
                    <i class="bi bi-bag-plus"></i> Add to Order
                  </button>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <div class="alert alert-warning d-inline-block">
            <i class="bi bi-database-exclamation me-2"></i>
            No food items found. Please ensure the MySQL database is running and <code>database.sql</code> is imported.
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- No Search Results Notification (Dynamically shown by JS) -->
    <div id="noMenuResults" class="text-center py-5" style="display: none;">
      <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
      <h5 class="mt-3 text-secondary">No dishes match your search</h5>
      <p class="text-muted small">Try searching for other keywords like "kottu", "biryani", or "rice".</p>
    </div>

    <!-- Sticky Bottom Bar Mobile/Desktop Cart Reminder -->
    <div class="card mt-5 border-0 shadow bg-light p-3 rounded-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-3 mb-2 mb-md-0">
        <div class="bg-danger text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
          <i class="bi bi-bag-heart fs-5"></i>
        </div>
        <div>
          <h6 class="mb-0 fw-bold">Ready to savor your meal?</h6>
          <small class="text-muted">Review your items and proceed with express checkout.</small>
        </div>
      </div>
      <div>
        <button type="button" class="btn btn-primary-sb" data-bs-toggle="offcanvas" data-bs-target="#cartDrawer">
          <i class="bi bi-cart-check me-1"></i> View Cart &amp; Checkout
        </button>
      </div>
    </div>

  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
