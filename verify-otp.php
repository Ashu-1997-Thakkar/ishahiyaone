<?php
session_start();
require_once __DIR__ . '/db.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = $_POST['otp']; // OTP entered by user
    $mobile_number = $_POST['phone']; // Customer's mobile number

    // Step 1: Validate input
    if (empty($mobile_number)) {
        echo json_encode(['status' => 'error', 'message' => 'Mobile number is required']);
        exit;
    }
    if (empty($otp)) {
        echo json_encode(['status' => 'error', 'message' => 'OTP is required']);
        exit;
    }

    // Step 2: Check session for OTP and timestamp
    if (isset($_SESSION['otp']) && isset($_SESSION['otp_time'])) {
        $current_time = time();

        // Step 3: Expiry check (5 min validity)
        if ($current_time - $_SESSION['otp_time'] > 300) {
            echo json_encode(['status' => 'expired', 'message' => 'OTP has expired']);
            exit;
        } else {
            // Step 4: Verify OTP
            if ($_SESSION['otp'] == $otp) {
                try {
                    // Step 5: Check if customer exists
                    $stmt = $conn->prepare("SELECT id, Mobile_Number FROM Customer WHERE Mobile_Number = ?
");
                    $stmt->bind_param("s", $mobile_number);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $customer = $result->fetch_assoc();

                    if ($customer === null) {
                        // New customer: Insert record
                        $stmt_insert = $conn->prepare("INSERT INTO Customer (Mobile_Number, is_verified, Date) VALUES (?, 1, NOW())");
                        $stmt_insert->bind_param("s", $mobile_number);
                        $stmt_insert->execute();

                        // Get the newly inserted customer
                        $stmt = $conn->prepare("SELECT id, Mobile_Number FROM Customer WHERE Mobile_Number = ?");
                        $stmt->bind_param("s", $mobile_number);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $customer = $result->fetch_assoc();
                    } else {
                        // Existing customer: Update verified status
                        $stmt_update = $conn->prepare("UPDATE Customer SET is_verified = 1, Date = NOW() WHERE Mobile_Number = ?");
                        $stmt_update->bind_param("s", $mobile_number);
                        $stmt_update->execute();
                    }

                    // Step 6: Store customer data in session (for both new/existing)
                    if ($customer) {
                        $_SESSION['customer_id']   = $customer['id'];
                        // $_SESSION['customer_name'] = $customer['Name'] ?? '';
                        $_SESSION['user_phone']    = $customer['Mobile_Number'];
                        $_SESSION['otp_verified']  = true;
                        $_SESSION['otp_verified_time'] = time();
                    }

                    // Step 7: Clean up OTP from session
                    unset($_SESSION['otp']);
                    unset($_SESSION['otp_time']);

                    echo json_encode(['status' => 'verified', 'message' => 'Customer verified successfully']);
                } catch (Exception $e) {
                    // Step 8: Error handling
                    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
                }
            } else {
                // Step 9: Invalid OTP
                echo json_encode(['status' => 'failed', 'message' => 'Invalid OTP']);
            }
        }
    } else {
        // Step 10: No OTP in session
        echo json_encode(['status' => 'invalid_request', 'message' => 'Session OTP not found']);
    }
}
?>
