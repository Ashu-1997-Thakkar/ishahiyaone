<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'mail.brinfo.in';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'inquiry@brinfo.in';
    $mail->Password   = 'AnoopaM@1437';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;

    $mail->setFrom('inquiry@brinfo.in', 'Ishahiya Test');
    $mail->addAddress('your-personal-email@example.com');  // Replace with your personal email for testing

    $mail->isHTML(true);
    $mail->Subject = 'Test Email from Ishahiya';
    $mail->Body    = "<p>This is a test email to verify SMTP configuration.</p>";

    $mail->send();
    echo "✅ Test email sent successfully.";

} catch (Exception $e) {
    echo "❌ Mailer Error: {$mail->ErrorInfo}";
}
?>
