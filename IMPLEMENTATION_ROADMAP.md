# IshahiyaOne Phase 1 Implementation Roadmap: Search, Filtering & Reviews

**Document Status:** Approved & Ready for Development  
**Target Scope:** Backward-Compatible Architectural Extension of IshahiyaOne eCommerce Platform.

---

## 🏗️ Architectural Audit & Compatibility Analysis

### 1. Current State Assessment
* **Search Module (`includes/header_nav.php`):** Currently implements a standard GET request form (`<form action="search.php" method="get">`). Typing requires pressing enter/submit, triggering a full synchronous browser reload.
* **Category Landing (`subshop1.php`):** Renders product cards via server-side PHP loops based on URL query parameters (`?subcategory=slug` or `?category_id=id`). Changing filters requires page reloads.
* **Social Proof / Reviews:** Zero review models or database tables exist in the current MySQL schema.

### 2. Architectural Compatibility
The proposed enhancements are **100% compatible** with the existing IshahiyaOne procedural/AJAX hybrid architecture:
* We will establish a clean, modular API namespace under `/api/v1/` returning standardized JSON headers (`Content-Type: application/json`).
* Frontend scripts will use vanilla Javascript `fetch()` API, ensuring zero bundle size bloat or dependency conflicts with existing `Swiper.js` or `Bootstrap` assets.
* Existing UI components (`.product-card`, `#sh-banner`, mega-menu bar) remain entirely untouched.

---

## 📋 Feature Implementation Plans

### 🔍 Feature 1: Advanced Auto-Complete Semantic Search
* **Objective:** Deliver instant predictive search dropdowns with product thumbnails and pricing.
* **New Backend API:** `GET /api/v1/search/autocomplete.php?q={keyword}`
  * Queries `all_category`, `subcategories`, and `products` using `LIKE '%keyword%'` across `name`, `brand`, and `category` columns.
  * Limits response to top 6 highly relevant matches.
* **New Frontend Asset:** `js/search-autocomplete.js`
  * Hooks into `input[name="s"]` with a 300ms debounce timer.
  * Dynamically injects a glassmorphism `.search-dropdown-results` container positioned directly below the search input bar.

### ⚡ Feature 2: Dynamic AJAX Client-Side Filtering
* **Objective:** Enable instantaneous catalog sorting and filtering on `subshop1.php` without page reloads.
* **New Backend API:** `GET /api/v1/catalog/filter.php`
  * Accepts parameters: `category_id`, `min_price`, `max_price`, `brand`, `sort` (`price_asc`, `price_desc`, `newest`).
  * Returns JSON payload containing formatted HTML card snippets or raw product object data.
* **UI Extension in `subshop1.php`:**
  * Inject an interactive filter controls bar (Price Range Slider, Sort Dropdown) above the `.products-grid` container.
  * Update grid contents smoothly via Javascript DOM manipulation.

### ⭐ Feature 3: Customer Review & Rating Engine
* **Objective:** Collect verified customer feedback and display star ratings.
* **Database Schema Migration:** `product_reviews` Table
  ```sql
  CREATE TABLE IF NOT EXISTS product_reviews (
      id INT AUTO_INCREMENT PRIMARY KEY,
      product_id INT NOT NULL,
      user_id INT DEFAULT 0,
      user_name VARCHAR(100) NOT NULL,
      rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
      review_title VARCHAR(255) DEFAULT '',
      review_text TEXT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      INDEX (product_id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
  ```
* **New Backend APIs:**
  * `POST /api/v1/reviews/create.php` (Accepts JSON body: `product_id`, `user_name`, `rating`, `review_text`).
  * `GET /api/v1/reviews/list.php?product_id={id}` (Returns average rating and review list).

---

## 📦 Matrix of Required System Changes

| Module / Layer | File Path | Action Type | Description of Change |
| :--- | :--- | :--- | :--- |
| **Database** | `shop_admin/scratch/migrate_reviews.php` | Create | Migration script to initialize `product_reviews` table. |
| **Backend API** | `api/v1/search/autocomplete.php` | Create | JSON predictive search endpoint. |
| **Backend API** | `api/v1/catalog/filter.php` | Create | JSON dynamic product filter query engine. |
| **Backend API** | `api/v1/reviews/create.php` | Create | Ingest customer review submission. |
| **Backend API** | `api/v1/reviews/list.php` | Create | Fetch product ratings and testimonials. |
| **Frontend JS** | `js/search-autocomplete.js` | Create | Client-side predictive search UI logic. |
| **Frontend UI** | `includes/header_nav.php` | Include | Link `search-autocomplete.js` script tag. |
| **Frontend UI** | `subshop1.php` | Modify | Add filter bar UI controls & AJAX grid handler. |

---

## ⚠️ Risk & Dependency Assessment
1. **CSS Collision Risk:** The auto-complete dropdown must have `z-index: 9999` to overlay above the Swiper category navigation bar smoothly.
2. **Multi-Table Mapping Dependency:** The review API must accurately map `product_id` across decentralized catalog tables.

---
*Roadmap Approved. Proceeding to Step 1 Implementation.*
