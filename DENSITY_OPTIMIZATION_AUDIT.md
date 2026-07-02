# Storefront Product Density & Spacing Optimization Audit

**Target Module:** `subshop1.php` Product Merchandising Grid  
**Benchmark:** Flipkart & Myntra High-Density Catalog Guidelines (5 Items per desktop row).

---

## 🧐 1. Spacing & Density Bottlenecks Audited

### ❌ A. Top Header Collision (Z-Axis / Margin Overlap)
* **Audited State:** The fixed top navigation headers (`header_nav.php` + `category_nav.php`) occupy ~150px of vertical space. Because `.marketplace-container` had `margin-top: 30px`, the top row of product cards slid underneath the header bar, obscuring product images and titles.
* **Prescription:** Increase container `margin-top` to `170px` to guarantee zero visual clipping below the global mega-menu fold.

### ❌ B. Excessive Intra-Card Padding & Gaps (Low Product Density)
* **Audited State:**
  * Grid horizontal/vertical gap: `25px` (Wastes ~75px per row).
  * Card inner padding: `18px` (Forces card content to expand vertically).
  * Column minimum width: `250px` (Limits desktop grid to 3 or 4 items per row).
* **Prescription (Compact Enterprise Layout):**
  * Decrease grid gap from `25px` $\rightarrow$ `14px`.
  * Decrease card `.product-info` padding from `18px` $\rightarrow$ `10px 12px`.
  * Reduce minimum grid column width from `250px` $\rightarrow$ `205px`. This allows **5 uniform cards per row** on standard 1300px+ viewports without looking cramped.
  * Condense button height and typography (`13px` title, `16px` price, `8px` button padding).

---
*Proceeding to CSS & Presentation Optimization.*
