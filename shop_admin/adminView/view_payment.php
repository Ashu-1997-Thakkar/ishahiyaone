<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

// check GET param
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Invalid Request: Missing ID");
}

$id = intval($_GET['id']); // security के लिए int में convert

// query
$sql = "SELECT * FROM billing_details WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $sql);

// query error check
if (!$result) {
    die("❌ SQL Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    die("❌ Record not found for ID = " . $id);
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payment Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 60%;
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th {
            background: #f1f1f1;
            width: 30%;
        }
    </style>
</head>
<body>

    <h2>Payment Details (ID: <?= $data['id'] ?>)</h2>

    <table>
        <tr><th>Order ID</th><td><?= htmlspecialchars($data['order_id']) ?></td></tr>
        <tr><th>User ID</th><td><?= htmlspecialchars($data['user_id']) ?></td></tr>
        <tr><th>Full Name</th><td><?= htmlspecialchars($data['fullname']) ?></td></tr>
        <tr><th>Email</th><td><?= htmlspecialchars($data['email']) ?></td></tr>
        <tr><th>Mobile</th><td><?= htmlspecialchars($data['mobile']) ?></td></tr>
        <tr><th>Alt. Mobile</th><td><?= htmlspecialchars($data['alt_mobile']) ?></td></tr>
        <tr><th>Address</th><td><?= htmlspecialchars($data['address']) ?></td></tr>
        <tr><th>Landmark</th><td><?= htmlspecialchars($data['landmark']) ?></td></tr>
        <tr><th>City</th><td><?= htmlspecialchars($data['city']) ?></td></tr>
        <tr><th>State</th><td><?= htmlspecialchars($data['state']) ?></td></tr>
        <tr><th>Pincode</th><td><?= htmlspecialchars($data['pincode']) ?></td></tr>
        <tr><th>Payment Method</th><td><?= htmlspecialchars($data['payment_status']) ?></td></tr>
        <tr><th>Total Amount</th><td>₹ <?= number_format($data['total_amount'], 2) ?></td></tr>
        <tr><th>Created At</th><td><?= htmlspecialchars($data['created_at']) ?></td></tr>
    </table>

</body>
</html>
