<?php
/**
 * ================================================
 *  IshahiyaOne — Centralized Configuration File
 * ================================================
 *  ⚠️  Never expose this file publicly.
 *      Ensure it is NOT inside a publicly accessible path
 *      or add "deny from all" in .htaccess.
 * ================================================
 */

// ─── DATABASE ────────────────────────────────────
define('DB_HOST',     'localhost');
define('DB_USER',     'ishahiyaone');
define('DB_PASSWORD', 'BhaV@1437I');
define('DB_NAME',     'ishahiyaone');
define('DB_CHARSET',  'utf8mb4');

// ─── RAZORPAY ─────────────────────────────────────
define('RAZORPAY_KEY_ID',  'rzp_live_RvW7WZFs8WsJeG');
define('RAZORPAY_SECRET',  '266K621OO0HlREgZRbsbD24m');

// ─── SMS API ──────────────────────────────────────
define('SMS_API_URL',  'http://sms2.brinfo.in/sms-panel/api/http/index.php');
define('SMS_USERNAME', 'br');
define('SMS_APIKEY',   '5B44C-DA670');
define('SMS_SENDER',   'BRalrt');
define('ADMIN_MOBILE', '919974328904');

// ─── SITE ─────────────────────────────────────────
define('SITE_NAME',  'IshahiyaOne');
define('SITE_URL',   'https://ishahiyaone.shop');
define('SITE_EMAIL', 'support@ishahiyaone.shop');

// ─── ENVIRONMENT ──────────────────────────────────
// Set to false in production to hide errors from users
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
