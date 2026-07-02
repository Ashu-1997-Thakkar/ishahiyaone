# IshahiyaOne Enterprise eCommerce Platform — Full API Documentation & Architecture

Welcome to the comprehensive technical API specifications for the **IshahiyaOne eCommerce Platform**. This document outlines all existing internal AJAX/PHP controller endpoints, public frontend workflows, database table mappings, authentication mechanisms, and recommended RESTful endpoints required for enterprise-grade marketplace scaling.

---

## 🏗️ System Architecture & Protocol Overview

* **Base URL:** `https://ishahiyaone.shop/` (Production) / `http://localhost/ishahiyaone/` (Local Development)
* **Architecture:** Decentralized PHP Controller & Webhook Architecture (Vanilla Javascript AJAX / jQuery / cURL)
* **Data Interchange Format:** `application/json` & `multipart/form-data` (for media assets)
* **Session Management:** Native PHP Sessions (`$_SESSION['user_id']`, `$_SESSION['cart']`, `$_SESSION['admin']`)

---

## 📦 Part 1: Public Frontend & Shopping Cart Endpoints

### 1. Add Item to Cart
* **Endpoint:** `/drt.php`
* **HTTP Method:** `POST`
* **Purpose:** Adds a selected product (with specified size and quantity) to the active user session or database cart.
* **Authentication Requirements:** Public (Session-based fallback for guest users; User ID bound if logged in).
* **Database Tables Used:** `cart`, `products`, `all_category`, `subcategories`
* **Request Parameters (`application/x-www-form-urlencoded`):**
  * `product_id` (Integer, Required): Unique ID of the product.
  * `product_quantity` (Integer, Required): Number of items to add (Default: 1).
  * `product_size` (String, Optional): Selected clothing/shoe size token.
  * `source` (String, Optional): Table origin indicator (e.g., `all_category`, `subcategories`).
* **Example Request Body:**
  ```json
  {
    "product_id": 105,
    "product_quantity": 2,
    "product_size": "L",
    "source": "all_category"
  }
  ```
* **Success Response (200 OK):**
  ```json
  {
    "status": "success",
    "message": "Product successfully added to cart",
    "cart_count": 3
  }
  ```
* **Error Response (400 Bad Request):**
  ```json
  {
    "status": "error",
    "message": "Out of stock or invalid product ID selected"
  }
  ```
* **Example cURL:**
  ```bash
  curl -X POST http://localhost/ishahiyaone/drt.php \
       -d "product_id=105&product_quantity=2&product_size=L&source=all_category"
  ```

---

### 2. Update Cart Quantity & Remove Item
* **Endpoint:** `/cart.php`
* **HTTP Method:** `POST` / `AJAX`
* **Purpose:** Updates item quantity (+/-) or removes an item entirely from the shopping cart.
* **Authentication Requirements:** Public (Session cookie required).
* **Database Tables Used:** `cart`
* **Request Parameters:**
  * `action` (String, Required): `update` or `remove`.
  * `cart_id` (Integer, Required): Unique identifier of the cart row.
  * `quantity` (Integer, Optional): New target quantity (Required if `action=update`).
* **Success Response (200 OK):**
  ```json
  {
    "status": "success",
    "subtotal": 1499.00,
    "total_cart_items": 2
  }
  ```

---

### 3. Public Order Tracking Web Hook
* **Endpoint:** `/track-order.php`
* **HTTP Method:** `POST`
* **Purpose:** Retrieves live tracking status and progress stepper index for a given customer order.
* **Authentication Requirements:** None (Public customer lookup).
* **Database Tables Used:** `orders`, `order_items`, `billing_details`, `subcategories`, `all_category`, `products`
* **Request Parameters:**
  * `order_id` (String, Required): Order reference number (e.g., `ORD-88231`).
  * `phone` (String, Required): 10-digit registered billing phone number.
* **Success Response (200 OK):**
  ```json
  {
    "status": "success",
    "order_id": "ORD-88231",
    "order_status": 2,
    "status_label": "Shipped",
    "estimated_delivery": "2026-06-28",
    "items": [
      {
        "name": "Allen Solly Polo Shirt",
        "quantity": 1,
        "price": "799.00",
        "image": "shop_admin/uploads/shirt1.png"
      }
    ]
  }
  ```

---

### 4. Newsletter Subscription Webhook
* **Endpoint:** `/subscribe_newsletter.php`
* **HTTP Method:** `POST`
* **Purpose:** Subscribes a visitor's email address to marketing campaigns.
* **Database Tables Used:** `newsletter_subscribers`
* **Request Parameters:**
  * `email` (String, Required): Valid customer email address.
* **Success Response (200 OK):**
  ```json
  {
    "status": "success",
    "message": "Thank you for subscribing to Ishahiya newsletters!"
  }
  ```

---

## ⚙️ Part 2: Admin Panel Management APIs (`/shop_admin/controller/`)

### 1. Create Product Catalog Item
* **Endpoint:** `/shop_admin/controller/addItemController.php`
* **HTTP Method:** `POST` (`multipart/form-data`)
* **Purpose:** Inserts a new product alongside category associations, pricing, stock levels, and up to 4 image uploads.
* **Authentication Requirements:** Admin Session (`$_SESSION['admin']`).
* **Database Tables Used:** `subcategories`, `all_category`, `products`
* **Request Body Form Data:**
  * `p_name`: Product Title
  * `p_price`: Selling Price
  * `p_desc`: HTML Description
  * `category`: Subcategory Reference ID
  * `file`, `file1`, `file2`, `file3`: Binary image files (.jpg, .png, .webp)
* **Success Response (302 Redirect / JSON):**
  ```json
  {
    "status": "success",
    "inserted_id": 142
  }
  ```

---

### 2. Update Order Fulfillment Status
* **Endpoint:** `/shop_admin/controller/updateOrderStatus.php`
* **HTTP Method:** `POST`
* **Purpose:** Advances an order's lifecycle stage across the tracking stepper pipeline.
* **Authentication Requirements:** Admin Session.
* **Database Tables Used:** `orders`, `billing_details`
* **Request Parameters:**
  * `order_id` (Integer, Required): Internal database order ID.
  * `status` (Integer, Required): Status code index:
    * `0` = Pending
    * `1` = Processing
    * `2` = Shipped
    * `3` = Delivered
    * `4` = Cancelled
* **Success Response (200 OK):**
  ```json
  {
    "status": "success",
    "updated_status_code": 2,
    "message": "Order marked as Shipped successfully"
  }
  ```
* **Example cURL:**
  ```bash
  curl -X POST http://localhost/ishahiyaone/shop_admin/controller/updateOrderStatus.php \
       -H "Cookie: PHPSESSID=admin_sess_token_here" \
       -d "order_id=45&status=2"
  ```

---

### 3. Manage Category & Subcategory Hierarchy
* **Endpoints:** 
  * `/shop_admin/controller/mainCategoryController.php` (`POST`)
  * `/shop_admin/controller/subCategoryController.php` (`POST`)
  * `/shop_admin/controller/delete_subcategory.php` (`POST`)
* **Purpose:** CRUD operations for multi-tier marketplace taxonomy.
* **Database Tables Used:** `main_category`, `sub_category`, `subcategories`
* **Request Parameters (Add Subcategory):**
  * `main_cat_id` (Integer): Parent Main Category ID.
  * `sub_cat_name` (String): Title of Subcategory.
  * `slug` (String): SEO URL friendly identifier.

---

### 4. Dynamic Marketing Deals & Bumper Offers
* **Endpoints:**
  * `/shop_admin/controller/bumperOfferController.php`
  * `/shop_admin/controller/offerController.php`
  * `/shop_admin/controller/toggle_best.php`
* **Purpose:** Controls homepage dynamic banner tickers, countdown clocks, and promotional card grids.
* **Database Tables Used:** `deals_offers_manager`, `hero_slider`, `subcategories`

---

## 💳 Part 3: Checkout & Payment Webhooks

### 1. Order Placement & Razorpay Initialization
* **Endpoint:** `/checkout.php`
* **HTTP Method:** `POST`
* **Purpose:** Validates cart contents, calculates shipping taxes, records customer delivery address, and generates a Razorpay Order ID.
* **Authentication Requirements:** User logged in OR Guest details provided.
* **Database Tables Used:** `billing_details`, `orders`, `order_items`
* **Request Parameters:**
  * `full_name`, `email`, `phone`, `address`, `city`, `pincode`, `state`, `payment_method` (`razorpay` or `cod`)
* **Success Response (200 OK):**
  ```json
  {
    "status": "razorpay_init",
    "razorpay_order_id": "order_EKwx8291Maks",
    "amount": 149900,
    "key_id": "rzp_live_xxx..."
  }
  ```

---

### 2. Razorpay Webhook Verification Callback
* **Endpoint:** `/payment_success.php`
* **HTTP Method:** `POST`
* **Purpose:** Receives cryptographic signature verification from Razorpay checkout modal and confirms order capture.
* **Request Parameters:**
  * `razorpay_payment_id`, `razorpay_order_id`, `razorpay_signature`

---

## 🗄️ Part 4: Core Database Schema Mappings

| Table Name | Primary Key | Key Foreign Keys | Purpose & Usage |
| :--- | :--- | :--- | :--- |
| `main_category` | `id` | None | Top-level marketplace navigation tabs (e.g., Electronics, Men Wear). |
| `sub_category` | `id` | `main_category_id` -> `main_category.id` | Secondary taxonomy categories linked to main categories. |
| `subcategories` | `id` | `category_id` -> `sub_category.id` | Primary product catalog storage table containing item attributes & images. |
| `all_category` | `id` | `main_category_id`, `sub_category_id` | Unified catalog table used for site-wide global search and sliders. |
| `products` | `product_id` | `category_id`, `sub_category_id` | Legacy/Extended product storage table. |
| `cart` | `cart_id` | `user_id`, `product_id` | Active shopping bag persistence. |
| `billing_details` | `id` | `user_id` | Source of truth for administrative order management & addresses. |
| `orders` | `order_id` | `user_id` | Customer order header records. |
| `order_items` | `id` | `order_id` -> `orders.order_id` | Line items mapped to an order. |

---

## 🚀 Part 5: Detected Missing Enterprise APIs (Recommended Specifications)

To transition IshahiyaOne into a fully decoupled, modern Headless eCommerce architecture (e.g., Next.js / React Native mobile apps), the following RESTful APIs should be developed:

### 1. RESTful Authentication API (JWT Standard)
* **Recommended Endpoint:** `POST /api/v1/auth/login`
* **Request Body:**
  ```json
  { "email": "customer@gmail.com", "password": "SecurePassword123" }
  ```
* **Recommended Response:**
  ```json
  {
    "success": true,
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 86400,
    "user": { "id": 12, "name": "Bhav Patel", "role": "customer" }
  }
  ```

### 2. Customer Wishlist Management API
* **Recommended Endpoints:**
  * `GET /api/v1/wishlist` (List user favorite items)
  * `POST /api/v1/wishlist/toggle` (Add/Remove item)
* **Purpose:** Enables cross-device persistent item bookmarking without cluttering active shopping carts.

### 3. High-Speed Auto-Complete & Semantic Search API
* **Recommended Endpoint:** `GET /api/v1/catalog/search?q={query}&limit=5`
* **Response:**
  ```json
  {
    "suggestions": [
      { "id": 102, "title": "Allen Solly Blue Polo", "category": "Men Wear", "price": 799 }
    ]
  }
  ```

### 4. Customer Address Book CRUD API
* **Recommended Endpoints:**
  * `GET /api/v1/user/addresses`
  * `POST /api/v1/user/addresses`
  * `DELETE /api/v1/user/addresses/{id}`
* **Purpose:** Allows saved addresses for 1-click rapid checkouts.

---
*Generated by Antigravity AI Engineering Assistant — IshahiyaOne Platform Specifications.*
