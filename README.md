<<<<<<< HEAD
# Hotel S&B – Online Food Ordering System

**Course:** ICT 2209 – Web Technologies  
**Assignment:** Mini Project – Individual Submission  
**Institution:** Faculty of Technology, Department of ICT, Rajarata University of Sri Lanka  

---

## 1. Project Overview

**Hotel S&B** is an interactive, full-stack online food ordering web application designed for an authentic Sri Lankan restaurant. The platform enables customers to explore signature Sri Lankan dishes (such as kottu, dum biryani, fried rice, and devilled dishes), filter by category, search live, add items to an interactive shopping basket, and place online delivery orders with real-time feedback.

The application is built using **HTML5, CSS3, Bootstrap 5.3, JavaScript (ES6+), PHP, and MySQL**, strictly complying with the academic guidelines and evaluation criteria of the ICT 2209 Mini Project.

---

## 2. Featured Food Items (8 Signature Dishes)

The system features 8 handcrafted Sri Lankan culinary items:

1. **Special Mixed Fried Rice** (Rice) – Wok-tossed premium Basmati rice loaded with chicken, lagoon prawns, egg ribbons, spring onions, and sweet-chili paste (*Rs. 1,450.00*).
2. **Sri Lankan Chicken Kottu** (Kottu) – Fresh godamba roti chopped on griddle with marinated chicken, leeks, egg, and spicy curry gravy (*Rs. 1,200.00*).
3. **Seafood Cheese Kottu** (Kottu) – Godamba roti tossed with cuttlefish, prawns, and fish, blended in creamy rich melted mozzarella (*Rs. 1,850.00*).
4. **Hotel S&B Chicken Dum Biryani** (Biryani) – Slow-dum fragrant Basmati rice with whole spices, roasted spiced chicken quarter, egg, mint sambol, and raita (*Rs. 1,600.00*).
5. **Royal Mutton Biryani** (Biryani) – Tender slow-cooked mutton chunks simmered in aromatic whole spices, ghee rice, golden fried onions, and cashews (*Rs. 2,100.00*).
6. **Egg Roti with Chicken Curry Set** (Curries & Roti) – Three golden crispy egg rotis served with thick coconut dhal, lunu miris, and country chicken curry (*Rs. 850.00*).
7. **Spicy Devilled Chicken** (Sides) – Crispy chicken tossed with capsicum, tomatoes, red onions, banana peppers, and a fiery sweet-chili glaze (*Rs. 1,350.00*).
8. **Signature Royal Faluda & Iced Milo** (Beverages) – Chilled milk drink with rose syrup, basil seeds (kasakasa), grass jelly, topped with vanilla ice cream (*Rs. 550.00*).

---

## 3. Compliance with Project Requirements & Evaluation Criteria

| Evaluation Category | Weightage | Implementation in Hotel S&B |
| :--- | :---: | :--- |
| **HTML, CSS Layout** | **20%** | Semantic HTML5, Bootstrap 5.3 responsive grid, custom CSS styling (`css/style.css`), custom brand palette (warm saffron, amber, crimson), card hover zoom effects, responsive typography, and mobile-friendly offcanvas drawer. |
| **JavaScript Features** | **15%** | **1. Dynamic Content Updates:** Live category filtering (All, Rice, Kottu, Biryani, Curries, Sides, Beverages) and instant keyword search input.<br>**2. Interactive Image Slider:** Auto-playing & manual Bootstrap Carousel with slide indicators.<br>**3. Form Validation:** Real-time client-side regex email, phone, and password validation.<br>**4. Smooth Scrolling:** Smooth anchor transitions.<br>**5. Event Handling & Animations:** Interactive Cart state management with `localStorage`, toast alerts, and modal dialogs. |
| **Database Integration** | **20%** | Normalized MySQL schema (`hotel_sb`) with PDO abstraction, prepared statements to prevent SQL injection, and atomic database transactions (`beginTransaction`/`commit`) during order checkout. |
| **User Authentication** | **20%** | Secure registration (`auth/register.php`) hashing passwords with `password_hash()`, login authentication (`auth/login.php`) using `password_verify()`, session management, user profile updates, and logout (`auth/logout.php`). |
| **Contact Form & Data Handling** | **10%** | Full contact page (`contact.php`) storing inquiries into MySQL `messages` table with both client-side and server-side validation. |
| **Creativity & Originality** | **5%** | Original "Hotel S&B" branding, tailored Sri Lankan food theme, custom vector illustrations, and interactive customer dashboard (`dashboard.php`). |
| **Proper File Structure** | **5%** | Strictly follows the folder layout specified in Section 4 of the project guidelines. |
| **Documentation & Comments** | **5%** | Comprehensive `README.md`, setup walkthrough, and detailed inline PHP/JS/CSS comments. |

---

## 4. Project Folder Structure

```text
hotel-sb/
│── css/
│   └── style.css            # Project styling, animations, and responsive rules
│── js/
│   └── main.js              # Interactivity: slider, filtering, validation, cart
│── images/
│   ├── logo.svg             # Hotel S&B vector logo
│   ├── rice_mixed.svg       # Food illustration: Mixed Fried Rice
│   ├── kottu_chicken.svg    # Food illustration: Chicken Kottu
│   ├── kottu_cheese.svg     # Food illustration: Seafood Cheese Kottu
│   ├── biryani_chicken.svg  # Food illustration: Chicken Dum Biryani
│   ├── biryani_mutton.svg   # Food illustration: Royal Mutton Biryani
│   ├── egg_roti.svg         # Food illustration: Egg Roti Set
│   ├── devilled_chicken.svg # Food illustration: Devilled Chicken
│   └── faluda.svg           # Food illustration: Royal Faluda
│── includes/
│   ├── db.php               # Database connection file (PDO with error guidance)
│   ├── functions.php        # Helper functions (sanitization, auth, cart, formatters)
│   ├── header.php           # Common responsive header & navigation bar
│   └── footer.php           # Common footer, shopping cart drawer & checkout modal
│── auth/
│   ├── register.php         # User registration logic & form
│   ├── login.php            # User authentication & session start
│   └── logout.php           # Session destruction & redirect
│── contact.php              # Contact form storing queries in messages table
│── index.php                # Main landing page (hero carousel, highlights, reviews)
│── menu.php                 # Food menu page with category filter & online ordering
│── order_process.php        # Backend order processor (database transactions)
│── dashboard.php            # User order history & delivery profile management
│── database.sql             # Complete MySQL dump with tables & seed records
└── README.md                # Project documentation & setup instructions
```

---

## 5. Local Setup Instructions (XAMPP / WAMP)

Follow these steps to run the project locally on your computer:

### Step 1: Install XAMPP or WAMP
Ensure **XAMPP** (or WAMP) is installed on your computer. Download from [https://www.apachefriends.org](https://www.apachefriends.org) if not already installed.

### Step 2: Copy Project Files to Web Root
1. Copy the `hotel-sb` directory into the Apache web root:
   - For **XAMPP**: `C:\xampp\htdocs\hotel-sb`
   - For **WAMP**: `C:\wamp64\www\hotel-sb`

### Step 3: Start Apache and MySQL
1. Open the **XAMPP Control Panel**.
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

### Step 4: Import the Database
1. Open your web browser and navigate to:  
   `http://localhost/phpmyadmin`
2. Click on the **Import** tab at the top.
3. Click **Choose File** and select the `database.sql` file located in `hotel-sb/database.sql`.
4. Click **Import** (or **Go**) at the bottom.
5. The `hotel_sb` database will be created automatically, along with all tables (`users`, `foods`, `orders`, `order_items`, `messages`) and sample data.

### Step 5: Launch the Application
Open your web browser and visit:  
`http://localhost/hotel-sb/`

---

## 6. Pre-Configured Demo Credentials

For quick evaluation and testing, the database includes pre-configured customer accounts:

| Role | Username | Email | Password |
| :--- | :--- | :--- | :--- |
| **Customer** | `kamal_sb` | `kamal@example.com` | `password123` |
| **Customer** | `nimali_p` | `nimali@example.com` | `password123` |

*You can also create a new customer account at any time using the `auth/register.php` page.*

---

## 7. Database Tables Schema

- **`users`**: `id` (PK, AI), `username`, `email`, `password` (hashed with `password_hash`), `phone`, `address`, `created_at`.
- **`foods`**: `id` (PK, AI), `name`, `category`, `price`, `description`, `image`, `badge`, `is_available`, `created_at`.
- **`orders`**: `id` (PK, AI), `order_number` (unique), `user_id` (FK to `users`), `customer_name`, `customer_email`, `customer_phone`, `delivery_address`, `total_amount`, `payment_method`, `order_notes`, `status`, `created_at`.
- **`order_items`**: `id` (PK, AI), `order_id` (FK to `orders`), `food_id` (FK to `foods`), `item_name`, `price`, `quantity`, `subtotal`.
- **`messages`**: `id` (PK, AI), `name`, `email`, `subject`, `message`, `created_at`.

---

## 8. Authorship & Academic Integrity

Developed for **ICT 2209 – Web Technologies Mini Project**, Rajarata University of Sri Lanka.  
All database scripts, backend endpoints, and frontend assets are designed to run cleanly both offline and online without external API dependencies.
=======
# My_Project
Sachintha prabod Create the Hottle S&amp;B 
>>>>>>> eab6122f72fc67fac42bcdabd7e0aa4d317530eb
