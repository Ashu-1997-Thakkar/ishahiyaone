<?php
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_GET['token'], $_GET['name'], $_GET['email'], $_GET['phone'], $_GET['message'])) {
    $token   = $_GET['token'];
    $name    = htmlspecialchars($_GET['name']);
    $email   = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
    $phone   = htmlspecialchars($_GET['phone']);
    $message = htmlspecialchars($_GET['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("<h1 style='color:red;'>❌ Invalid email address.</h1>");
    }

    // ✅ Prevent duplicate
    $stmt = $conn->prepare("SELECT id FROM inquiries WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo "<h1 style='color:red;'>❌ Invalid or expired verification link.</h1>";
    } else {
        // ✅ Update existing inquiry to verified
        $stmt = $conn->prepare("UPDATE inquiries SET is_verified = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "<h1 style='color:green;'>✅ Your inquiry has been verified successfully!</h1>";

        // ✅ Send notification to Admin inbox
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'mail.brinfo.in';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'inquiry@ishahiya.com';
            $mail->Password   = 'AnoopaM@1437';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('inquiry@ishahiya.com', 'Ishahiya Website');
            $mail->addAddress('inquiry@ishahiya.com'); // ✅ Admin inbox

            $mail->isHTML(true);
            $mail->Subject = 'New Verified Inquiry';
            $mail->Body    = "
                <h2>New Inquiry Verified</h2>
                <p><b>Name:</b> {$name}</p>
                <p><b>Email:</b> {$email}</p>
                <p><b>Phone:</b> {$phone}</p>
                <p><b>Message:</b><br>{$message}</p>
            ";

            $mail->send();

            // ✅ Send Thank You to Customer
            $mail->clearAddresses();
            $mail->addAddress($email, $name);
            $mail->Subject = 'Inquiry Received - Ishahiya';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
                    <div style='text-align: center; margin-bottom: 20px;'>
                        <img src='https://ishahiya.com/image/logo/logo.png' alt='Ishahiya' style='max-width: 150px;'>
                    </div>
                    <h2 style='color: #d4af37; text-align: center;'>Thank You, {$name}!</h2>
                    <p>We have successfully verified your inquiry. Our specialized team has been notified and will review your request shortly.</p>
                    <div style='background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <p style='margin: 0;'><b>Your Message:</b></p>
                        <p style='font-style: italic; color: #666;'>\"{$message}\"</p>
                    </div>
                    <p>We typically respond within 24-48 business hours. Thank you for choosing Ishahiya.</p>
                    <hr style='border: 0; border-top: 1px solid #eee;'>
                    <p style='font-size: 12px; color: #999; text-align: center;'>This is an automated confirmation. Please do not reply to this email.</p>
                </div>
            ";
            $mail->send();

        } catch (Exception $e) {
            error_log("❌ Verification mail failed: " . $mail->ErrorInfo);
        }
    }
} else {
    echo "<h1 style='color:red;'>❌ Invalid or expired verification link.</h1>";
}
?>
