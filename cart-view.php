<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$subtotal = 0;
?>
<h2>SHOPPING CART</h2>

<?php if (!empty($cart)): ?>
<form method="POST" action="update-cart.php">
  <table>
    <thead>
      <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Size</th>
        <th>Price</th>
        <th>Total</th>
        <th>Remove</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cart as $key => $item): 
        $item_total = $item['price'] * $item['quantity'];
        $subtotal += $item_total;
      ?>
      <tr>
        <td><img src="images/<?= $item['image']; ?>" width="80"></td>
        <td><?= $item['name']; ?></td>
        <td>
          <input type="number" name="quantity[<?= $key ?>]" value="<?= $item['quantity'] ?>" min="1">
        </td>
        <td><?= $item['size']; ?></td>
        <td>₹<?= $item['price']; ?></td>
        <td>₹<?= $item_total ?></td>
        <td><a href="remove-item.php?id=<?= $key ?>">❌</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <br>
  <button type="submit">Update Quantity</button>
</form>

<!-- Totals -->
<div>
  <p><strong>Sub Total:</strong> ₹<?= $subtotal ?></p>
  <p><strong>GST 5%:</strong> ₹<?= $gst = round($subtotal * 0.05) ?></p>
  <p><strong>Shipping:</strong> As Per Charge</p>
  <p><strong>Total:</strong> ₹<?= $subtotal + $gst ?></p>
  <a href="checkout.php">Checkout</a>
</div>

<?php else: ?>
  <p>Your cart is empty.</p>
<?php endif; ?>
