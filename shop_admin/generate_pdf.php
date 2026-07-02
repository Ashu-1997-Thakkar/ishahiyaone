<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Thanks For Shoping With Us</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
  <style>
    body { font-family: Arial, sans-serif; font-size: 14px; background: #f8f9fa; }
    .label-box {
      width: 100%;
      max-width: 800px;
      margin: auto;
      padding: 12px;
      border: 2px solid #000;
      background: #fff;
    }
    .label-row { border-bottom: 2px solid #000; }
    .section-title { font-weight: bold; font-size: 15px; margin-bottom: 5px; text-transform: uppercase; }
    .big-text { font-size: 18px; font-weight: bold; }
    .medium-text { font-size: 16px; font-weight: bold; }
    .small-text { font-size: 12px; }
    .barcode-box { text-align: center; padding: 10px; }
    hr { margin: 6px 0; border: 0; border-top: 1px solid #000; }

    /* Print scaling */
    @media print {
      body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      .label-box {
        transform: scale(0.75);       /* shrink to ~35% */
        transform-origin: top left;   /* anchor scaling */
        width: 100%;
      }
      .label-box-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 20px;
      }
      button, .btn {
        display: none !important;  /* hide buttons */
      }
    }
  </style>
</head>
<body>
<?php
include_once __DIR__ . '/config/dbconnect.php';

if (!isset($_GET['id'])) { die("No ID provided."); }
$id = $_GET['id'];  

$stmt = mysqli_prepare($conn, "SELECT * FROM billing_details WHERE id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) { die("Order not found."); }

$order = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>

<div class="label-box-wrapper">
  <div class="label-box" id="labelBox">
    <!-- Header -->
    <div class="text-center mb-2">
      <span class="big-text"><?= ($order['payment_status'] == 'completed' ? 'PREPAID' : 'CASH ON DELIVERY'); ?></span>
      <br>
      <span class="medium-text">Amount: ₹<?= number_format((float)$order['total_amount'], 2); ?></span>
    </div>

    <!-- From / To -->
    <div class="row label-row">
      <div class="col-6 p-2 border-end">
        <div class="section-title">From</div>
        <p><strong>IshaHiyaOne.Shop</strong><br>
        (Discover unbeatable deals across<br>
       your favorite products)<br>
        Mobile:+919974328904</p>
      </div>
      <div class="col-6 p-2">
        <div class="section-title">Ship To</div>
        <p class="big-text"><?= htmlspecialchars($order['fullname']); ?></p>
        <p><?= htmlspecialchars($order['address'] . ", " . $order['landmark']); ?></p>
        <p><?= htmlspecialchars($order['city'] . ", " . $order['state']); ?> - <?= htmlspecialchars($order['pincode']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($order['mobile']); ?></p>
      </div>
    </div>

    <!-- Product / Order -->
    <div class="row label-row">
      <div class="col-6 p-2 border-end">
        <div class="section-title">Product</div>
        <p><strong>SKU:</strong> <?= htmlspecialchars($order['sku_no']); ?></p>
        <p><?= htmlspecialchars($order['product_name']); ?></p>
      </div>
      <div class="col-6 p-2">
        <div class="section-title">Order Info</div>
        <p><strong>Order ID:</strong> <?= htmlspecialchars($order['id']); ?></p>
        <p><strong>Date:</strong> <?= date("d/m/Y", strtotime($order['created_at'])); ?></p>
        <p><strong>Tracking ID:</strong> <?= htmlspecialchars($order['TXNID']); ?></p>
      </div>
    </div>

    <!-- Barcode -->
    <div class="barcode-box">
      <svg id="barcode"></svg>
      <p class="small-text">Tracking ID: <?= htmlspecialchars($order['TXNID']); ?></p>
    </div>
  </div>
</div>

<div class="text-center mt-3">
  <button class="btn btn-primary" onclick="printLabel()">Print</button>
  <button class="btn btn-success" onclick="downloadPDF()">Download PDF</button>
</div>

<script>
function printLabel() { window.print(); }
function downloadPDF() {
  const element = document.getElementById('labelBox');
  const opt = {
    margin: 0.2,
    filename: 'label_<?= $order['id']; ?>.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(element).save();
}

// Generate barcode
JsBarcode("#barcode", "<?= htmlspecialchars($order['TXNID']); ?>", {
  format: "CODE128",
  width: 2,
  height: 60,
  displayValue: true
});
</script>
</body>
</html>
