# 🏗️ IshahiyaOne — Project Architecture Guide

This guide provides an in-depth walkthrough of the internal logic and file organization of the IshahiyaOne eCommerce platform.

---

## 🧭 1. Global Components (Standardized UI)

To maintain the "Black and Gold" theme, the site uses a centralized header and footer. Any change to these files updates the entire website instantly.

- **Header/Nav**: `includes/header_nav.php`
  - Logic: Dynamically detects the current page to apply the `active-link` style (gold text).
  - Components: Contains the global search bar, cart counter, and account links.
- **Footer**: `includes/footer.php`
  - Layout: 4-column responsive grid (About, Support, Shop, Contact).
  - Features: WhatsApp floating button and legal policy links.
- **Master Styles**: `css/style.css`
  - Contains CSS variables for `--gold` and `--dark-bg`.
  - Defines the global search bar design and navigation hover effects.

---

## 🔄 2. Data Flow: From Browse to Order

### Step A: Product Discovery
Users browse products on `index.php`, `shop.php`, or `subshop1.php`.
- **Logic**: Queries the `all_category` or `subcategories` tables.
- **Detail View**: Clicking a product opens `drt.php`, which fetches specific details, images, and sizes based on a `GET` ID.

### Step B: The Cart System
- **Persistence**: Guest carts are stored in `$_SESSION['cart']`. Upon login (`log.php`), these are merged into the `cart` database table.
- **Stock Check**: `check_stock.php` ensures users can't add more than the available `quantity_in_stock` for a specific size.

### Step C: Checkout & Payment
- **Information Gathering**: `checkout.php` collects billing data.
- **Validation**: Ensures the cart isn't empty and all fields are valid.
- **Payment Trigger**: 
  - If **Razorpay**: AJAX calls `create_razorpay_order.php`, opens modal, and then redirects to `payment_success.php`.
  - If **COD**: Processes directly and redirects to `payment_success.php`.

### Step D: Finalization (The "Hook" System)
`payment_success.php` is the master controller for finalizing an order:
1. Validates transaction.
2. Inserts line items into `order_items`.
3. **Decrements Stock**: Updates `product_size_variation`.
4. Sends SMS via `config.php` credentials.
5. Clears the user's specific cart.

---

## 🛠️ 3. Admin Panel Internals (`shop_admin/`)

The admin panel is designed as a **Single Page Application (SPA)** to ensure fast management.

- **Dynamic Routing**: `index.php` contains a JS `loadModule` function that fetches PHP views from `adminView/` and injects them into the `#main-content` div.
- **Sidebar**: `sidebar.php` manages the navigation links that trigger these AJAX calls.
- **Controllers**: `controller/` folder contains pure logic files (no UI) that handle database `INSERT`, `UPDATE`, and `DELETE` operations.

---

## 🛡️ 4. Security Implementation

- **`.htaccess` Protection**:
  - Direct access to `config.php` and `db.php` is forbidden.
  - Directory listing is disabled (`Options -Indexes`).
- **Database Safety**:
  - `db.php` enforces `utf8mb4` encoding.
  - All modern modules utilize Prepared Statements (`$stmt->prepare`).
- **Error Handling**: `DEBUG_MODE` in `config.php` toggles between showing errors for development and hiding them for production.

---

## 🗄️ 5. Database Table Guide

- `all_category`: Master product catalog.
- `subcategories`: Detailed product variations.
- `product_size_variation`: Junction table mapping products to sizes and stock levels.
- `billing_details`: Order header information.
- `order_items`: Order line items.
- `user`: Account data (roles: `user` or `admin`).

---
*Documentation Version: 1.1 | Updated: May 2026*
