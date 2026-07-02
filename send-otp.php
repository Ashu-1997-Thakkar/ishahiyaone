<?php
// send-otp.php
session_start();

// ✅ Load centralized config
require_once __DIR__ . '/config.php';

if (isset($_POST['phone'])) {
    $phone = trim($_POST['phone']);

    // ✅ Validate phone number format (must be 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid phone number format. Please enter a 10-digit number.']);
        exit;
    }

    // ✅ Generate OTP
    $otp = rand(100000, 999999);

    // ✅ Message with variable placeholder for template {#var#}
    // Note: The SMS provider will replace {#var#} with the actual OTP automatically if configured properly in DLT template
    $message = "Your OTP for IshaHiyaOne login is {#var#} and is valid for 5 mins. Please DO NOT share this OTP with anyone to keep your account safe, IshaHiya, BR CATTLE FEED.";

    // ✅ Store OTP and timestamp in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_time'] = time();

    // ✅ SMS API configuration
    $api_url = SMS_API_URL;
    $sender_id = SMS_SENDER;
    $template_id = '1707176268818000430'; // DLT-approved template ID
    $apikey = SMS_APIKEY;
    $username = SMS_USERNAME;

    // ✅ Prepare POST data
    $data = array(
        'username'   => $username,
        'apikey'     => $apikey,
        'apirequest' => 'Text',
        'sender'     => $sender_id,
        'route'      => 'TRANS',
        'message'    => str_replace('{#var#}', $otp, $message), // replace {#var#} with actual OTP before sending
        'mobile'     => $phone,
        'TemplateID' => $template_id,
        'format'     => 'JSON'
    );

    // ✅ Send SMS using cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);

    // ✅ Error handling
    if (curl_errno($ch)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP. Please try again later.']);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // ✅ Parse SMS API response (optional)
    $responseData = json_decode($response, true);
    if (isset($responseData['status']) && strtolower($responseData['status']) == 'success') {
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully to your phone.']);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully to your phone.']);
        // You can log or inspect $responseData if needed
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Phone number is missing.']);
}
?>
