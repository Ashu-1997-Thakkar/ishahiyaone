<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id      = $_POST['order_id'];      // from hidden input
    $dispatch_date = $_POST['dispatch_date']; // from form
    $courier_name  = $_POST['courier_name'];  // from form

    // ✅ Fetch customer mobile number & order number from DB
   $sql = "SELECT * FROM billing_details WHERE id = '$order_id' LIMIT 1";

    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $customer_mobile = $row['mobile'];
        $order_number  =  $row['TXNID'];

        // ✅ Build SMS with real values
       // ------------------ 3. Dispatch Message ------------------ //
$dispatch_message = "Your order has been dispatched!\n"
                  . "Order ID: {$order_number}\n"
                  . "Courier/Tracking ID: {$courier_name}\n"
                  . "Estimated Delivery: {$dispatch_date}\n"
                  . "Thank you for shopping with IshaHiya,\n"
                  . "BR CATTLE FEED.";


        // ✅ SMS API
        $api_url = "http://sms2.brinfo.in/sms-panel/api/http/index.php";

        $sms_params_dispatch = [
            "username"   => "br",
            "apikey"     => "5B44C-DA670",
            "apirequest" => "Text",
            "sender"     => "BRalrt",
            "mobile"     => $customer_mobile,
            "message"    => $dispatch_message,
            "route"      => "TRANS",
            "TemplateID" => "1707176269951675081", // Dispatch template ID
            "format"     => "JSON"
        ];

        // ✅ Send SMS
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url . "?" . http_build_query($sms_params_dispatch));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $sms_response = curl_exec($ch);
        curl_close($ch);

        echo json_encode(['success' => true, 'message' => "Order dispatched and SMS sent."]);
        exit();
    } else {
        echo json_encode(['success' => false, 'message' => "Order not found!"]);
        exit();
    }
} else {
    echo json_encode(['success' => false, 'message' => "Invalid request method."]);
    exit();
}
?>
