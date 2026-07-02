# 🛍️ IshahiyaOne — Premium eCommerce Platform

IshahiyaOne is a fully functional, high-end fashion eCommerce platform built for **BR IT Solution & BR Cattle Feed**. It features a modern "Black and Gold" aesthetic and provides a seamless shopping experience from product discovery to secure payment.

## 🚀 Key Features

- **Dynamic Catalog**: Multi-level category management (Main Categories & Sub-Categories).
- **Premium UI**: Modern dark-themed design with gold accents and responsive layouts.
- **Secure Authentication**: OTP-based mobile login and traditional Email/Password options.
- **Shopping System**: Persistence-ready cart and wishlist (session + database).
- **Payment Integration**: Integrated with **Razorpay** for secure online transactions and UPI.
- **Inventory Management**: Real-time stock deduction per size variation upon successful orders.
- **Admin Dashboard**: Full-featured SPA (Single Page Application) for managing orders, products, customers, and promotions.
- **Automated Alerts**: SMS notifications for customers and admins on order status updates.

## 🛠️ Technology Stack

- **Frontend**: HTML5, Vanilla CSS3, JavaScript (ES6+), Bootstrap 5.3.3.
- **Backend**: PHP (Procedural & Structured).
- **Database**: MySQL (optimized with MySQLi & Prepared Statements).
- **Integrations**: Razorpay (Payments), BR SMS API (OTP & Alerts), PHPMailer (Emails).
- **PDF Generation**: FPDF for automated invoices.

## 🔐 Security Architecture

- **Centralized Config**: All sensitive credentials (DB, API Keys) are stored in `config.php`.
- **Access Control**: Root `.htaccess` prevents direct web access to sensitive system files.
- **Data Protection**: User-specific session management and prepared statements to prevent SQL injection.

## 📁 Key Project Structure

- `/includes`: Source of truth for Navigation and Footer.
- `/shop_admin`: Secure dashboard for platform management.
- `/css`: Global design system and component styles.
- `config.php`: Master configuration file (requires protection).
- `db.php`: Database connection interface.

---

## 🛠️ Installation & Setup

1. **Environment**: Best run on **WAMP64** or **XAMPP** (PHP 7.4+ recommended).
2. **Database**: Import `velvetvougedb.sql` (renamed to `ishahiyaone` in production).
3. **Configuration**: 
   - Open `config.php`.
   - Update `DB_PASSWORD` and `DB_NAME` to match your local environment.
   - Enter your `RAZORPAY_KEY_ID` and `RAZORPAY_SECRET`.
4. **Permissions**: Ensure `uploads/` directory is writable for product images.

## 📜 Documentation

For detailed technical flows and API specifications, see:
- [API Documentation](API_DOCUMENTATION.md)
- [Project Architecture Walkthrough](docs/PROJECT_GUIDE.md)

---

*Developed by the Ishahiya Development Team.*
