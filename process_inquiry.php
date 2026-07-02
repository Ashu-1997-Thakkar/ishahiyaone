<?php
session_start();  // ✅ Start session for CAPTCHA check

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'db.php'; // ✅ Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ CAPTCHA verification
    if (!isset($_POST['captcha_answer']) || $_POST['captcha_answer'] != $_SESSION['captcha']) {
        $_SESSION['error_message'] = "❌ CAPTCHA incorrect. Please try again.";
        header("Location: contact.php");
        exit;
    }

    $name    = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars(strip_tags(trim($_POST['phone'])));
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "❌ Please fill in all required fields with valid information.";
        header("Location: contact.php");
        exit;
    }

    // Generate secure token
    $token = bin2hex(random_bytes(16));

    // ✅ Verification link with all data
    $verification_link = "https://ishahiya.com/verify_inquiry.php?token={$token}"
        . "&name=" . urlencode($name)
        . "&email=" . urlencode($email)
        . "&phone=" . urlencode($phone)
        . "&message=" . urlencode($message);

    $mail = new PHPMailer(true);

    try {
        // ✅ Auto-fix: Check if 'token' column exists, add if missing
        $checkCols = $conn->query("SHOW COLUMNS FROM inquiries LIKE 'token'");
        if ($checkCols->num_rows == 0) {
            $conn->query("ALTER TABLE inquiries ADD token VARCHAR(255) AFTER message");
            $conn->query("ALTER TABLE inquiries ADD is_verified TINYINT(1) DEFAULT 0 AFTER token");
        }

        // ✅ Insert into database immediately (unverified)
        $stmt = $conn->prepare("INSERT INTO inquiries (name, email, phone, message, token, is_verified) VALUES (?, ?, ?, ?, ?, 0)");
        $stmt->bind_param("sssss", $name, $email, $phone, $message, $token);
        $stmt->execute();
        $stmt->close();

        $mail->isSMTP();
        $mail->Host       = 'mail.brinfo.in';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'inquiry@ishahiya.com';  // ✅ fixed
        $mail->Password   = 'AnoopaM@1437';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('inquiry@ishahiya.com', 'Ishahiya');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Please Verify Your Inquiry Submission';
        $mail->Body    = "
            <html><body>
                <h2>Verify Your Inquiry</h2>
                <p>Hi {$name},</p>
                <p>Thank you for your inquiry! Please verify by clicking the link below:</p>
                <p><a href='{$verification_link}'>✅ Verify My Inquiry</a></p>
            </body></html>
        ";

        $mail->send();

        // ✅ Clear captcha after successful mail send
        unset($_SESSION['captcha']);
        unset($_SESSION['captcha_question']);

        echo "
            <div style='font-family: Arial; background:#000; color:#fff; text-align:center; padding:50px;'>
                <h1 style='color:#d4af37;'>✅ Success!</h1>
                <p>A verification link has been sent to your email. Please check your inbox.</p>
            </div>
        ";

    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}
?>
