# 🗄️ IshahiyaOne — Database Schema Documentation

This document describes the structure and relationships of the `ishahiyaone` database.

---

## 👥 1. User Management

### `user` table
Stores customer and admin account information.
- `id` (INT, PK, AI)
- `name` (VARCHAR)
- `email` (VARCHAR, Unique)
- `password` (VARCHAR, hashed)
- `role` (ENUM: 'user', 'admin')
- `created_at` (TIMESTAMP)

### `Customer` table
Stores mobile-verified customers (OTP flow).
- `id` (INT, PK, AI)
- `Mobile_Number` (VARCHAR)
- `is_verified` (INT: 0 or 1)
- `Date` (TIMESTAMP)

---

## 📦 2. Product Catalog

### `category` table
Top-level navigation categories.
- `category_id` (INT, PK, AI)
- `category_name` (VARCHAR)

### `all_category` table
The primary product catalog table.
- `id` (INT, PK, AI)
- `category_id` (INT) — FK to `category`
- `name` (VARCHAR) — Product title
- `brand` (VARCHAR)
- `price` (DECIMAL)
- `images1`, `images2`, `images3` (VARCHAR) — Path to images
- `description` (TEXT)
- `is_new_arrival` (INT: 0 or 1)
- `is_our_collection` (INT: 0 or 1)
- `is_best` (INT: 0 or 1)

### `subcategories` table
Detailed product variations.
- `sub_category_id` (INT, PK, AI)
- `category_id` (INT)
- `sub_category_name` (VARCHAR)
- `price` (DECIMAL)
- `image` (VARCHAR)

---

## 📏 3. Inventory & Sizes

### `sizes` table
Available size labels.
- `size_id` (INT, PK, AI)
- `size_name` (VARCHAR) — e.g., 'S', 'M', 'L', 'XL'

### `product_size_variation` table
The inventory bridge table.
- `variation_id` (INT, PK, AI)
- `product_id` (INT) — Links to `all_category` or `products`
- `size_id` (INT) — FK to `sizes`
- `quantity_in_stock` (INT) — Current inventory count

---

## 💳 4. Orders & Billing

### `billing_details` table
Order header and customer shipping info.
- `id` (INT, PK, AI)
- `user_id` (INT)
- `fullname` (VARCHAR)
- `mobile` (VARCHAR)
- `address`, `landmark`, `city`, `state`, `pincode` (VARCHAR)
- `Mode` (VARCHAR) — 'Razorpay', 'COD', 'QR Code'
- `TXNID` (VARCHAR) — Unique transaction identifier
- `total_amount` (DECIMAL)
- `payment_status` (VARCHAR) — 'PENDING', 'SUCCESS', 'FAILED'
- `created_at` (TIMESTAMP)

### `order_items` table
Individual products within an order.
- `id` (INT, PK, AI)
- `order_id` (INT) — FK to `billing_details`
- `product_name` (VARCHAR)
- `size` (VARCHAR)
- `quantity` (INT)
- `price` (DECIMAL)

---

## 🛒 5. Temporary Storage

### `cart` table
Persisted shopping cart for logged-in users.
- `id` (INT, PK, AI)
- `user_id` (INT)
- `product_id` (INT)
- `name`, `price`, `images1`, `size` (VARCHAR)
- `quantity` (INT)

---

## 🛠️ 6. Relationships & Integrity

1. **Cart → User**: `cart.user_id` references `user.id`.
2. **Order Items → Order**: `order_items.order_id` references `billing_details.id`.
3. **Stock → Size**: `product_size_variation.size_id` references `sizes.size_id`.
4. **Product → Category**: `all_category.category_id` references `category.category_id`.

---
*Schema version: 1.2 | Last Updated: May 2026*
