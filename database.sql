-- =========================================================
-- Hotel S&B Online Food Ordering System
-- Database Name: hotel_sb
-- ICT 2209 Web Technologies Mini Project Submission
-- Rajarata University of Sri Lanka
-- =========================================================

CREATE DATABASE IF NOT EXISTS `hotel_sb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hotel_sb`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `foods`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `foods`
-- --------------------------------------------------------
CREATE TABLE `foods` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `badge` VARCHAR(50) DEFAULT NULL,
  `is_available` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(20) NOT NULL,
  `delivery_address` TEXT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT 'Cash on Delivery',
  `order_notes` TEXT DEFAULT NULL,
  `status` ENUM('Pending', 'Preparing', 'Out for Delivery', 'Delivered', 'Cancelled') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `order_items`
-- --------------------------------------------------------
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `food_id` INT NOT NULL,
  `item_name` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`food_id`) REFERENCES `foods`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `messages`
-- (Required by Mini Project Section 3.4)
-- --------------------------------------------------------
CREATE TABLE `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(150) DEFAULT 'General Inquiry',
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- Sample Data Insertion
-- =========================================================

-- Sample users (Default password for demo accounts is 'password123')
-- Bcrypt hash generated using PHP password_hash('password123', PASSWORD_BCRYPT)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 'kamal_sb', 'kamal@example.com', '$2y$10$4B9Pcrn06GzGjG01o5nOqujD.F2E0UsmMfvqj9G0FqH1vYqKx5U3m', '0771234567', 'No. 45, Temple Road, Anuradhapura', '2026-09-01 10:00:00'),
(2, 'nimali_p', 'nimali@example.com', '$2y$10$4B9Pcrn06GzGjG01o5nOqujD.F2E0UsmMfvqj9G0FqH1vYqKx5U3m', '0719876543', 'No. 12, Main Street, Mihintale', '2026-09-05 14:30:00');

-- 8 Authentic Featured Food Items for Hotel S&B
INSERT INTO `foods` (`id`, `name`, `category`, `price`, `description`, `image`, `badge`, `is_available`, `created_at`) VALUES
(1, 'Special Mixed Fried Rice', 'Rice', 1450.00, 'Wok-tossed premium Basmati rice loaded with succulent chicken, fresh lagoon prawns, fluffy egg ribbons, spring onions, and carrots. Served with our signature sweet-chili paste and fresh salad.', 'rice_mixed.svg', 'Bestseller', 1, '2026-09-01 08:00:00'),
(2, 'Sri Lankan Chicken Kottu', 'Kottu', 1200.00, 'Chopped fresh godamba roti flash-fried on the hot griddle with marinated spiced chicken, crisp leeks, carrots, egg, and our secret hotel aromatic curry gravy.', 'kottu_chicken.svg', 'Popular', 1, '2026-09-01 08:00:00'),
(3, 'Seafood Cheese Kottu', 'Kottu', 1850.00, 'Hotel S&B specialty! Godamba roti shredded with tender cuttlefish, prawns, and fish, blended in creamy rich melted mozzarella and spiced cheese sauce.', 'kottu_cheese.svg', 'Chef Special', 1, '2026-09-01 08:00:00'),
(4, 'Hotel S&B Chicken Dum Biryani', 'Biryani', 1600.00, 'Authentic slow-dum cooked fragrant long-grain Basmati infused with saffron, cardamom, and clove. Layered with roasted spiced chicken quarter, hard-boiled egg, mint sambol, and cucumber raita.', 'biryani_chicken.svg', 'House Special', 1, '2026-09-01 08:00:00'),
(5, 'Royal Mutton Biryani', 'Biryani', 2100.00, 'Tender fall-off-the-bone mutton chunks simmered in aromatic whole spices, layered with fragrant ghee rice, fried golden onions, and roasted cashews.', 'biryani_mutton.svg', 'Premium', 1, '2026-09-01 08:00:00'),
(6, 'Egg Roti with Chicken Curry Set', 'Curries', 850.00, 'Three golden crisp egg-stuffed rotis served hot with thick coconut dhal curry, fiery onion sambol (lunu miris), and rich Sri Lankan country chicken curry.', 'egg_roti.svg', 'Traditional', 1, '2026-09-01 08:00:00'),
(7, 'Spicy Devilled Chicken', 'Sides', 1350.00, 'Golden crispy battered chicken cubes tossed in a wok with capsicum, ripe tomatoes, red onions, banana peppers, and a mouthwatering sweet & fiery chili glaze.', 'devilled_chicken.svg', 'Spicy', 1, '2026-09-01 08:00:00'),
(8, 'Signature Royal Faluda & Iced Milo', 'Beverages', 550.00, 'Refreshing chilled milk infused with sweet rose syrup, basil seeds (kasakasa), grass jelly strips, topped with a scoop of vanilla ice cream, or blended iced Milo.', 'faluda.svg', 'Refreshing', 1, '2026-09-01 08:00:00');

-- Sample Initial Orders
INSERT INTO `orders` (`id`, `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `total_amount`, `payment_method`, `order_notes`, `status`, `created_at`) VALUES
(1, 'ORD-20260901-1001', 1, 'Kamal Silva', 'kamal@example.com', '0771234567', 'No. 45, Temple Road, Anuradhapura', 3050.00, 'Cash on Delivery', 'Please add extra chili paste with the rice.', 'Delivered', '2026-09-02 12:15:00'),
(2, 'ORD-20260910-1002', 1, 'Kamal Silva', 'kamal@example.com', '0771234567', 'No. 45, Temple Road, Anuradhapura', 2400.00, 'Cash on Delivery', 'Please make the kottu medium spicy.', 'Preparing', '2026-09-10 19:40:00');

-- Sample Initial Order Items
INSERT INTO `order_items` (`id`, `order_id`, `food_id`, `item_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'Special Mixed Fried Rice', 1450.00, 1, 1450.00),
(2, 1, 4, 'Hotel S&B Chicken Dum Biryani', 1600.00, 1, 1600.00),
(3, 2, 2, 'Sri Lankan Chicken Kottu', 1200.00, 2, 2400.00);

-- Sample Initial Messages
INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Saman Kumara', 'saman@example.com', 'Catering Inquiry', 'Do you undertake party orders for 25 people with custom kottu and biryani packs?', '2026-09-12 11:20:00'),
(2, 'Dilani Weerasinghe', 'dilani@example.com', 'Opening Hours', 'Can we place delivery orders after 10:00 PM on weekends?', '2026-09-18 16:45:00');
