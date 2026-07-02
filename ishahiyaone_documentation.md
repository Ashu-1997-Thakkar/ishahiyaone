# 📘 IshahiyaOne — Complete Platform Documentation

> **Version:** 2.0 | **Last Updated:** June 2026 | **Live Site:** [ishahiyaone.shop](https://ishahiyaone.shop/index.php)

---

## 📋 Table of Contents
1. [Project Overview](#1-project-overview)
2. [Technology Stack](#2-technology-stack)
3. [Website — Customer Facing](#3-website--customer-facing)
4. [Admin Panel](#4-admin-panel)
5. [eCommerce Compliance Audit](#5-ecommerce-compliance-audit)
6. [Bugs & Known Issues Found](#6-bugs--known-issues-found)
7. [Recommended Next Features](#7-recommended-next-features)
8. [Database Reference](#8-database-reference)

---

## 1. Project Overview

**IshahiyaOne** is a fashion/ethnic wear eCommerce platform based in India. It is specifically focused on Navratri, festival, and premium clothing collections. The platform has:

- A **customer-facing storefront** (`index.php` and related pages)
- A **private admin panel** (`shop_admin/index.php`)
- **Razorpay** payment integration
- **OTP-based login** via mobile number (alongside email/password)
- Multi-tier product catalog with categories, subcategories, and size/stock management

---

## 2. Technology Stack

| Layer | Technology |
|---|---|
| **Backend** | PHP 8+ (procedural + PDO + MySQLi) |
| **Database** | MySQL (via WAMP server locally) |
| **Frontend** | HTML5, CSS3, Vanilla JS, jQuery |
| **CSS Framework** | Bootstrap 4 (admin), Custom CSS (frontend) |
| **Animations** | Swiper.js (sliders), CSS transitions |
| **Icons** | Font Awesome 6 |
| **Payments** | Razorpay SDK (card, UPI, net banking, QR Code) |
| **Email** | PHPMailer |
| **PDF Export** | FPDF library |
| **SEO** | Custom `seo_master.php` (meta, OG tags, JSON-LD schema) |
| **Hosting** | Live at `ishahiyaone.shop`; dev via WAMP on `localhost` |

---

## 3. Website — Customer Facing

### 3.1 Pages Available

| Page | File | Purpose |
|---|---|---|
| Homepage | `index.php` | Hero slider, new arrivals, best sellers, festival offers |
| Shop | `shop.php` | Full product listing with filtering |
| Product Detail | `drt.php` | Individual product view with sizes & add to cart |
| Cart | `cart.php` | Cart management, quantity update |
| Checkout | `checkout.php` | Address, GST calc, Razorpay/COD/QR payment |
| Wishlist | `wishlist.php` | Saved/favorite products |
| Account | `accounts.php` | User profile dashboard |
| Login / OTP | `log.php`, `send-otp.php`, `verify-otp.php` | Dual login (email or mobile OTP) |
| Contact | `contact.php` | Contact form + inquiry submission |
| About | `About.php` | Company/brand information |
| Collections | `collections.php` | Curated themed collections |
| Search | `search.php` | Product search results |
| Subcategory | `subcategory.php`, `subshop1.php` | Category browsing |
| Order Payment Success | `payment_success.php` | Confirmation after payment |
| Delivery Info | `delivery.php` | Shipping & delivery policies |
| Policies | `privacy.php`, `terms-and-conditions.php`, `Cancellation_Return_Refund_Policy.php` | Legal pages |
| SMS Services | `promotional-bulk-sms-services.php`, `transactional-bulk-sms-services.php`, etc. | Info pages for SMS features |

---

### 3.2 Frontend Features

#### ✅ Implemented & Working
- **Hero Banner Slider** — Full-width, image-based promotional slider managed via admin panel
- **Festival Offer Cards** — Black & Gold themed promotional offer cards on homepage
- **New Arrivals Section** — Dynamic product slider populated from DB (`is_new_arrival = 1`)
- **Best Sellers Section** — Highlights products flagged as best (`is_best = 1`)
- **Our Collections** — Curated themed product sliders
- **Product Cards** — Includes image, name, price, SALE badge, wishlist icon
- **Add to Cart** — Works for both logged-in users (DB cart) and guests (session cart)
- **Wishlist** — Toggle heart icon, persisted in DB for logged-in users
- **Cart Page** — Item list, quantity adjustment, price subtotal
- **Checkout** — Multi-step: address → payment method → confirmation
  - Payment Modes: **Razorpay** (card, UPI, netbanking), **COD**, **QR Code scan**
  - Dynamic GST calculation (5% or 18% based on product category)
- **Order Invoice** — FPDF-powered PDF download after order
- **Contact Form** — Customer inquiry submission stored in DB
- **OTP Login** — Mobile-number-based OTP login (via SMS)
- **Email Login** — Standard email + password authentication
- **SEO** — Title tags, meta descriptions, OG tags, JSON-LD schema, canonical URLs, robots.txt
- **Favicon** — Ishahiya branded favicon in all browser tabs
- **Responsive Design** — Mobile-first with hamburger menu
- **Size Selector** — Size (S, M, L, XL, etc.) with stock level check before add to cart
- **Promo / Coupon Codes** — Coupon entry at checkout with backend validation

---

### 3.3 Homepage Sections (Top to Bottom)

```
┌─────────────────────────────────────┐
│         Top Info Bar                │  Phone, Country, Alert Message
├─────────────────────────────────────┤
│         Navigation                  │  Logo, Menu Links, Search, Wishlist, Cart
├─────────────────────────────────────┤
│         Category Nav Bar            │  Quick category links
├─────────────────────────────────────┤
│         Hero Slider                 │  Full-width promotional banners (admin managed)
├─────────────────────────────────────┤
│         Festival Offer Cards        │  Black & Gold themed offer banners
├─────────────────────────────────────┤
│         New Arrivals Slider         │  Swiper.js horizontal product cards
├─────────────────────────────────────┤
│         Brand Promise Ticker        │  Sliding text slogan
├─────────────────────────────────────┤
│         Best Sellers Grid           │  Featured best-selling products
├─────────────────────────────────────┤
│         Our Collections             │  Themed collection sliders
├─────────────────────────────────────┤
│         GIF / Promo Banner          │  Full-width animated GIF or banner
├─────────────────────────────────────┤
│         Footer                      │  Links, social media, contact
└─────────────────────────────────────┘
```

---

## 4. Admin Panel

**URL:** `http://localhost/ishahiyaone/shop_admin/index.php`
**Authentication:** Username + Password (stored in `admin` table)

### 4.1 Architecture

The admin panel is a **Single-Page Application (SPA)** style dashboard. It uses a hash-based router (`#module-name`) and a JavaScript `loadModule()` function to dynamically load content into `#main-content` via AJAX without full page reloads. All module views are PHP files in `shop_admin/adminView/`.

### 4.2 Admin Panel Modules

#### 📊 Dashboard Overview
- **URL:** `#dashboard`
- Displays live statistics: Total Customers, Total Categories, Total Products, Total Orders
- Summary cards with color-coded gradients

---

#### 📦 Products Management
| Module | Hash | File | Features |
|---|---|---|---|
| All Products | `#all-products` | `viewAllProducts.php` | List, Add, Edit, Delete, Toggle New Arrival / Best Seller |
| New Arrivals | `#new-arrivals` | `viewAllProducts.php` | Filter by `is_new_arrival` flag |
| Our Collections | `#our-collections` | `viewOurCollections.php` | Manage curated product collections |
| Sub-Collections | `#sub-collections` | `viewOurSubCollections.php` | Sub-level collections management |
| Men Products | `#men` | `viewMen.php` | Dedicated men's catalog |
| Our Shop | `#our-shop` | `viewOurShop.php` | Shop-level product display |
| SubShop Collections | `#subshop-collections` | `showOurSubShopCollections.php` | SubShop-level collection management with AJAX |

**Product Actions Available:** Add, Edit (with image replace), Delete (with image file cleanup), Toggle "New Arrival" flag, Toggle "Best Seller" flag, Toggle "Our Collection" flag.

---

#### 🗂️ Categories & Taxonomy
| Module | Hash | File |
|---|---|---|
| Categories | `#categories` | `viewCategories.php` |
| Main Categories | `#main-categories` | `showMainCategory.php` |
| Sub-Categories | `#sub-categories` | `showSubCategory.php` |
| Sizes | `#sizes` | `viewSizes.php` |
| Stock Management | `#stock` | `showquantity.php` |

**Stock Management** (`showquantity.php`) is a powerful module that shows product-wise inventory (size variants), allows Add/Delete stock quantities via AJAX, with server-side pagination.

---

#### 👥 Customer Management
| Module | Hash | File | Features |
|---|---|---|---|
| All Customers | `#customers` | `viewCustomers.php` | View customer list, Ban/Unban users, Delete, Bulk Delete |
| User Inquiries | `#user-inquiries` | `showUserInquiries.php` | View inquiry submissions, Verify, Delete, Bulk Delete |
| Customer Inquiries | `#messages` | `viewMassages.php` | Contact form messages, Delete, Bulk Delete |

**Bulk Management Pattern:** All three modules follow the same standard — header-level "Select All" checkbox, per-row checkboxes, dynamic "Delete Selected" button with SweetAlert2 confirmation modal.

---

#### 💳 Orders & Payments
| Module | Hash | File | Features |
|---|---|---|---|
| Payment Records | `#payments` | `showPayment.php` | View all orders, Filter by status, Dispatch modal, View details, Delete |
| All Orders | `#orders` | `viewAllOrders.php` | Order list overview |
| Order Details | — | `viewEachOrder.php` | Per-order line items |
| Dispatch Order | — | `dispatch_order.php` | Mark as dispatched with tracking details |

**Payment Status Values:** `PENDING`, `SUCCESS`, `FAILED`
**Payment Modes:** Razorpay, COD, QR Code

---

#### 🎟️ Promotions & Marketing
| Module | Hash | File | Features |
|---|---|---|---|
| Promo Codes | `#offers` | `showPromoCodes.php` | Full CRUD — Create, Edit, Delete promo codes |
| Festival Dhamaka | `#festival` | `showFestivalDhamaka.php` | Festival offer vouchers (image-based) |
| Special Offers | `#special-offers` | `showSpecialOffer.php` | Special offer banners for homepage |
| GIF/Promo Banner | `#gif-banner` | `showGifBanner.php` | Upload animated GIF or static promo image |
| Hero Slider | `#hero-slider` | `viewHeroSlider.php` | Manage fullwidth homepage hero slides (Add/Edit/Delete/Reorder) |
| Pricing | `#pricing` | `pricing-status.php` | Manage display price rules/statuses |

**Promo Code Fields:** Offer Name, Coupon Code, Discount Value, Type (Percentage/Fixed), Category, Display Location, CTA Text, CTA Link, Schedule (Start/End Date), Carousel integration, Status (Active/Inactive).

---

#### 🖼️ Content Management
| Module | Hash | File |
|---|---|---|
| Hero Slider | `#hero-slider` | `viewHeroSlider.php` |
| GIF Banner | `#gif-banner` | `showGifBanner.php` |

---

### 4.3 Admin Panel Design System

- **Theme:** Black (`#111`) + Gold (`#d4af37`) — "Black & Gold" premium aesthetic
- **Font:** Inter (Google Fonts)
- **UI Library:** Bootstrap 4
- **Notifications:** SweetAlert2 (confirm dialogs + toast messages)
- **Icons:** Font Awesome 5
- **Tables:** Compressed layout (`0.78rem` font), server-side pagination (10 rows/page)
- **CSS File:** `shop_admin/assets/css/admin-modern.css`

### 4.4 Admin Authentication

- Login: `shop_admin/log.php`
- Session variable checked: `$_SESSION['role'] === 'admin'`
- Unauthorized access redirects to `log.php`

---

## 5. eCommerce Compliance Audit

### ✅ Features Present (Standard eCommerce)

| Feature | Status | Notes |
|---|---|---|
| Product Catalog | ✅ Done | Multi-category, with images, price, description |
| Product Detail Page | ✅ Done | Full product page with size selector |
| Shopping Cart | ✅ Done | DB-persisted (logged-in) + session (guest) |
| User Authentication | ✅ Done | Email/password + OTP mobile login |
| Checkout Process | ✅ Done | Address, payment selection, confirmation |
| Multiple Payment Modes | ✅ Done | Razorpay, COD, QR Code |
| Order Confirmation | ✅ Done | Post-payment success page |
| Invoice/PDF Download | ✅ Done | FPDF-based invoice |
| Wishlist | ✅ Done | Persisted per user in DB |
| Promo/Coupon Codes | ✅ Done | Discount calculation at checkout |
| GST Calculation | ✅ Done | 5% or 18% dynamic based on category |
| Search Functionality | ✅ Done | `search.php` |
| Product Filtering | ✅ Partial | Category-based; no price range filter |
| SEO Tags | ✅ Done | Title, meta, OG, JSON-LD, canonical |
| Favicon | ✅ Done | Custom Ishahiya favicon |
| Responsive Design | ✅ Done | Mobile-friendly layout |
| Legal Pages | ✅ Done | Privacy, T&C, Cancellation/Refund Policy |
| Admin Dashboard | ✅ Done | Full SPA admin panel |
| Order Management | ✅ Done | View, dispatch, delete orders |
| Customer Management | ✅ Done | View, ban, delete customers |
| Inventory/Stock Management | ✅ Done | Size-level stock tracking |
| Promotional Banners | ✅ Done | Hero slider, GIF banner, offer cards |
| Festival Offers | ✅ Done | Dedicated festival voucher system |
| Contact Form | ✅ Done | Stored as inquiries in DB |
| OTP Verification | ✅ Done | Mobile OTP via SMS gateway |

---

### ❌ Missing Features (Standard eCommerce Gaps)

| Missing Feature | Priority | Impact |
|---|---|---|
| **Order Tracking for Customers** | 🔴 High | Customers cannot track their order status |
| **Email Notifications** (Order placed, dispatched, cancelled) | 🔴 High | No automated emails after purchase |
| **Product Reviews & Ratings** | 🟠 Medium | No social proof / UGC on product pages |
| **Return / Refund Request System** | 🔴 High | Policy page exists but no system to submit/manage returns |
| **Price Range Filter** | 🟠 Medium | Shop page only filters by category |
| **Sort Options** (Price low-high, newest, popular) | 🟠 Medium | No sorting on shop/category pages |
| **Inventory Low-Stock Alerts** (Admin) | 🟠 Medium | Admin must manually check stock |
| **Abandoned Cart Recovery** | 🟡 Low | No email trigger for abandoned carts |
| **Customer Order History** | 🟠 Medium | Account page doesn't show past orders |
| **Address Book** (Multiple saved addresses) | 🟡 Low | Only one address captured per checkout |
| **Product Image Zoom** | 🟡 Low | No zoom on product detail page |
| **Related / Similar Products** | 🟠 Medium | No recommendations on product page |
| **Stock-Out / Pre-Order** | 🟠 Medium | Out-of-stock products not clearly shown |
| **Newsletter / Email Subscription** | 🟡 Low | No email capture for marketing |
| **Analytics Dashboard** (Sales charts, revenue trends) | 🟠 Medium | Admin dashboard has counts, not charts |
| **Bulk Product Import** (CSV/Excel) | 🟡 Low | Products added one by one |
| **Multi-image Gallery** on product detail | 🟠 Medium | DB supports `images1-3` but display may be limited |
| **Admin Role Permissions** (Editor vs Super Admin) | 🟡 Low | Single admin role only |

---

## 6. Bugs & Known Issues Found

> ⚠️ No code was changed. This is an observation list only.

| # | Issue | Location | Severity |
|---|---|---|---|
| 1 | Favicon tag in `index.php` head was commented out (`<!--`) | `index.php` line 58 | Medium |
| 2 | `subshop.php` was using incorrect DB path `./shop_admin/config/dbconnect.php` (works only from root) | `adminView/subshop.php` | Medium |
| 3 | Duplicate Font Awesome 6 CSS link in `index.php` (lines 61 and 69) | `index.php` | Low |
| 4 | `editUser.php` controller file is **empty** (0 bytes) | `controller/editUser.php` | High |
| 5 | `toggleFeature.php` controller is also **empty** (0 bytes) | `controller/toggleFeature.php` | High |
| 6 | `men.php.php` is a duplicate file with `.php.php` extension | `adminView/men.php.php` | Low |
| 7 | AJAX delete URL was absolute (`/ishahiyaone/...`) instead of relative — caused failures on some servers | `showquantity.php`, `viewMassages.php` (Fixed) | High |
| 8 | `cart.php` and `checkout.php` both fallback silently if `images1` path is wrong — shows broken image | `cart.php`, `checkout.php` | Medium |
| 9 | `banUser.php` exists but there's no matching "Unban" UI visible in the admin panel | `controller/banUser.php` | Medium |
| 10 | No CSRF protection on any form submission | All POST forms | High (Security) |
| 11 | Passwords stored as plain text in `admin` table (no hashing) | `shop_admin/log.php` | Critical (Security) |

---

## 7. Recommended Next Features

### 🔴 High Priority (Business Critical)

#### 1. Customer Order Tracking Page
- Add `order_status` field to `billing_details` (Pending → Packed → Dispatched → Delivered)
- Show order timeline on `accounts.php` for customer
- Admin panel dispatch action already exists — extend it to update status

#### 2. Automated Email Notifications (PHPMailer already installed)
- Trigger emails on: Order placed, Payment confirmed, Order dispatched, Order cancelled
- Use `PHPMailer` library already present at `/PHPMailer`

#### 3. Customer Order History in Accounts
- `accounts.php` should query `billing_details WHERE user_id = $_SESSION['user_id']`
- Show order list with status, amount, date, and download invoice link

#### 4. Return / Refund Request Module
- Frontend: Form in `accounts.php` where customer selects order and submits reason
- Admin: New module `#returns` in admin panel to view and manage return requests

---

### 🟠 Medium Priority (Revenue & Conversion)

#### 5. Product Reviews & Ratings System
- New table: `product_reviews (id, product_id, user_id, rating, review, created_at)`
- Display star rating on `drt.php` product page
- Admin panel moderation view

#### 6. Advanced Shop Filtering & Sorting
- Price range slider (min/max)
- Sort by: Price (Low to High / High to Low), Newest, Most Popular
- Filter by: Size availability, Category

#### 7. Analytics Dashboard in Admin
- Sales revenue chart (monthly/weekly) using Chart.js
- Top-selling products list
- Customer acquisition trend
- Payment mode distribution (Razorpay vs COD vs QR)

#### 8. Low Stock Alerts in Admin
- Flag products where `quantity_in_stock < threshold` (e.g., < 5)
- Show warning badge in Stock Management module

#### 9. Related Products on Product Page
- Query products from same category on `drt.php`
- Show in "You Might Also Like" section at bottom

---

### 🟡 Low Priority (UX Improvements)

#### 10. Product Image Zoom
- Use a lightbox or CSS zoom on hover for product images in `drt.php`

#### 11. Newsletter Subscription
- Simple email capture form in footer
- Store in `newsletter_subscribers` table

#### 12. WhatsApp Order Notification
- Integrate WhatsApp Business API to send order confirmation via WhatsApp
- Useful for Indian market (preferred over email)

#### 13. Admin Password Hashing
- Migrate admin passwords from plain text to `password_hash()` + `password_verify()`

#### 14. CSRF Token Protection
- Add CSRF tokens to all admin and frontend forms

---

## 8. Database Reference

### Core Tables

| Table | Purpose |
|---|---|
| `user` | Customer accounts (email login) |
| `admin` | Admin accounts |
| `Customer` | Mobile OTP-verified customers |
| `all_category` | Primary product catalog |
| `subcategories` | Product sub-variants |
| `category` | Top-level categories |
| `sub_category` | Mid-level category grouping |
| `main_category` | Root category grouping |
| `sizes` | Size labels (S, M, L, XL, etc.) |
| `product_size_variation` | Inventory: per product, per size stock count |
| `cart` | Persistent shopping cart (logged-in users) |
| `billing_details` | Order headers (address, payment, status) |
| `order_items` | Individual products within an order |
| `wishlist` | User's saved favorite products |
| `promo_codes` | Discount coupon codes |
| `vouchers` | Festival/promotional image vouchers |
| `hero_slider` | Homepage hero banner slides |
| `gif_banner` | Full-width promo/GIF banner |
| `inquiries` | User/customer inquiry submissions |
| `messages` | Contact form messages |
| `states` | Indian state list for address dropdown |

---

## 📌 Summary

**IshahiyaOne is a functionally complete Indian fashion eCommerce platform** with solid core features: product catalog, cart, checkout with multiple payment options, admin panel, and marketing tools. 

**The main gaps are:**
1. No post-purchase customer experience (order tracking, email confirmations)
2. No product reviews/ratings  
3. No advanced shop filtering
4. Critical security issues (plain text passwords, no CSRF protection)

**Recommended immediate focus:**
> Fix security issues → Add email notifications → Add customer order history → Add product reviews

---

*Documentation prepared based on codebase analysis of `e:\wamp64\www\ishahiyaone` | June 2026*
