<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Database connection
$server   = "localhost";
$user     = "ishahiyaone";
$password = "BhaV@1437I";
$database = "ishahiyaone";

$conn = new mysqli($server, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Assume login check
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;

/**
 * Helper function to fetch real-time max stock
 *
 * @param mysqli $conn The database connection
 * @param int|string|null $prd_id The product ID
 * @param string|null $size The size of the product
 * @return int The actual stock quantity
 */
function get_actual_stock(mysqli $conn, $prd_id, $size) {
    $actual_stock = 0;
    if ($prd_id !== null) {
        $sql_base = "
            SELECT sc.Stock, sub.main_category_id 
            FROM subcategories sc
            LEFT JOIN sub_category sub ON sc.category_id = sub.id
            WHERE sc.id = ? LIMIT 1
        ";
        $stmt_base = $conn->prepare($sql_base);
        if ($stmt_base) {
            $stmt_base->bind_param("i", $prd_id);
            $stmt_base->execute();
            $row_base = $stmt_base->get_result()->fetch_assoc();
            
            if ($row_base) {
                $actual_stock = (int)$row_base['Stock'];
                $main_cat_id = (int)$row_base['main_category_id'];
                
                if ($main_cat_id === 7 && !empty($size) && $size !== '-') {
                    $sql_stock = "
                        SELECT psv.quantity_in_stock 
                        FROM product_size_variation psv
                        JOIN sizes s ON psv.size_id = s.size_id
                        WHERE psv.product_id = ? AND s.size_name = ?
                        LIMIT 1
                    ";
                    $stmt = $conn->prepare($sql_stock);
                    if ($stmt) {
                        $stmt->bind_param("is", $prd_id, $size);
                        $stmt->execute();
                        $row = $stmt->get_result()->fetch_assoc();
                        if ($row && isset($row['quantity_in_stock'])) {
                            $actual_stock = (int)$row['quantity_in_stock'];
                        }
                    }
                }
            }
        }
    }
    return max(0, $actual_stock);
}

// === [ Add to Cart ] ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $id    = $_POST['product_id'] ?? 1;
    $size  = $_POST['product_size'] ?? '';
    $qty   = max(1, (int)($_POST['product_quantity'] ?? 1)); // minimum 1
    $image = $_POST['product_image'] ?? '';
    $name  = $_POST['product_name'] ?? '';
    $price_raw = $_POST['product_price'] ?? 0;
    if (is_string($price_raw)) {
        $price_raw = preg_replace('/[^\d.]/', '', $price_raw);
    }
    $price = (float)$price_raw;
    $sku_no = $_POST['sku_no'] ?? '';

    if ($id === null) {
        die("Error: Product ID missing.");
    }

    if ($user_id > 0) {
        // Logged in → save in DB
        $stmt = $conn->prepare("
            INSERT INTO cart (user_id, product_id, size, quantity, images1, name, sku_no, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt) die("Prepare failed: " . $conn->error);
        $stmt->bind_param("iisisssd", $user_id, $id, $size, $qty, $image, $name, $sku_no, $price);
        if (!$stmt->execute()) die("Execute failed: " . $stmt->error);
        $stmt->close();
    } else {
        // Guest → save in SESSION
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $qty;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $id,
                'name' => $name,
                'size' => $size,
                'quantity' => $qty,
                'images1' => $image,
                'price' => $price,
                'sku_no' => $sku_no
            ];
        }
    }

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo "success";
        exit;
    }

    header("Location: cart.php");
    exit();
}

// === [ Update Quantity (AJAX) ] ===
if (isset($_POST['update_qty'])) {
    $cart_id = (int)$_POST['cart_id'];
    $new_qty = max(1, (int)$_POST['quantity']);

    // 1. Fetch Product ID and Size
    $prd_id = null;
    $size = '';
    
    if ($user_id > 0) {
        $stmt = $conn->prepare("SELECT product_id, size FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $prd_id = $row['product_id'];
            $size = $row['size'];
        }
        $stmt->close();
    } else {
        if (isset($_SESSION['cart'][$cart_id])) {
            $prd_id = $cart_id;
            $size = $_SESSION['cart'][$cart_id]['size'];
        }
    }

    // 2. Fetch Actual Stock using Helper
    $actual_stock = get_actual_stock($conn, $prd_id, $size);

    // 3. Validate against stock
    if ($new_qty > $actual_stock) {
        echo json_encode(["status" => "error", "message" => "limit_reached", "max_stock" => $actual_stock]);
        exit;
    }

    // 4. If valid, update quantity
    if ($user_id > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $new_qty, $cart_id, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        if (isset($_SESSION['cart'][$cart_id])) {
            $_SESSION['cart'][$cart_id]['quantity'] = $new_qty;
        }
    }
    
    echo json_encode(["status" => "success"]);
    exit;
}

// === [ Remove Item ] ===
if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    
    if ($user_id > 0) {
        // Logged-in: delete from database using the cart row ID
        $conn->query("DELETE FROM cart WHERE id = " . (int)$remove_id . " AND user_id = $user_id");
    } else {
        // Guest: remove from session using the product ID (key)
        if (isset($_SESSION['cart'][$remove_id])) {
            unset($_SESSION['cart'][$remove_id]);
        }
    }
    
    header("Location: cart.php?removed=success");
    exit();
}


// === [ Fetch Cart ] ===
$cart_items = [];
$subtotal = 0;
$total_items = 0;

if ($user_id > 0) {
    // Fetch cart items for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $actual_stock = get_actual_stock($conn, $row['product_id'], $row['size']);
        if ($row['quantity'] > $actual_stock && $actual_stock > 0) {
            $row['quantity'] = $actual_stock;
            $stmt_fix = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
            if ($stmt_fix) {
                $stmt_fix->bind_param("ii", $row['quantity'], $row['id']);
                $stmt_fix->execute();
            }
        }
        
        $row['cart_row_id'] = $row['id']; // Consistent key for the delete button
        $cart_items[] = $row;
        $subtotal += ((float)$row['price']) * ((int)$row['quantity']);
        $total_items += (int)$row['quantity'];
    }
    $stmt->close();
} else {
    // Fetch cart items for guest users (from session)
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $p_id => $item) {
            $actual_stock = get_actual_stock($conn, $item['id'], $item['size']);
            if ($item['quantity'] > $actual_stock && $actual_stock > 0) {
                $item['quantity'] = $actual_stock;
                $_SESSION['cart'][$p_id]['quantity'] = $item['quantity'];
            }
            $item['cart_row_id'] = $p_id; // For guests, the row id is the product id key
            $cart_items[] = $item;
            $subtotal += ((float)$item['price']) * ((int)$item['quantity']);
            $total_items += (int)$item['quantity'];
        }
    }
}

$cart_count = count($cart_items);  // Update the cart count badge for the header (unique products only)


// Optional: define wishlist_count if not already set
$wishlist_count = $wishlist_count ?? 0;
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart | Ishahiya</title>
  <meta name="description" content="Review your selected items, update quantities and proceed to checkout at IshahiyaOne — India's premium fashion destination.">
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="shop.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --gold: #d4af37;
      --purple: #9b59b6;
      --dark-bg: #000000;
      --card-bg: #111111;
    }
    body { background-color: var(--dark-bg); color: #fff; }
    
    /* ================= HERO SECTION ================= */
    .hero-section {
      background: #000;
      color: #fff;
      padding: 60px 20px;
      text-align: center;
      border-bottom: 1px solid rgba(212, 175, 55, 0.2);
      margin-bottom: 40px;
    }
    .hero-section h1 {
      font-size: 3rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: var(--gold);
      text-transform: uppercase;
      letter-spacing: 2px;
    }
    .hero-section p {
      font-size: 1.2rem;
      color: #ffffff;
      font-style: italic;
      opacity: 0.9;
    }

    /* ================= CART STYLES ================= */
    .cart-table { border: 1px solid #333; color: #fff !important; }
    .cart-table thead { background-color: var(--gold) !important; color: #000 !important; }
    .cart-table td { background-color: #111 !important; color: #fff !important; border-bottom: 1px solid #222; }
    .cart-image { width: 80px; height: auto; border-radius: 4px; border: 1px solid #333; }
    
    .checkout-box { background: #111; padding: 25px; border-radius: 12px; border: 1px solid #333; box-shadow: 0 4px 15px rgba(0,0,0,0.5); }
    .cart-total-box { background: #0a0a0a; padding: 20px; border-radius: 10px; border: 1px solid var(--gold); margin-bottom: 20px; }
    
    .btn-gold { background-color: var(--gold) !important; color: #000 !important; font-weight: 700; text-transform: uppercase; border: none; padding: 12px 25px; transition: 0.3s; }
    .btn-gold:hover { background-color: #fff !important; transform: translateY(-2px); }
    
    .empty-cart { text-align: center; padding: 100px 0; }
    .empty-cart h4 { color: var(--gold); margin-bottom: 20px; }
    
    .form-control, .form-select { background-color: #222 !important; border: 1px solid #333 !important; color: #fff !important; }
    .form-control:focus { border-color: var(--gold) !important; box-shadow: none !important; }

    .premium-error-toast {
        border: 1px solid rgba(255, 71, 87, 0.4) !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 25px rgba(255, 71, 87, 0.2) !important;
        font-family: 'Inter', sans-serif !important;
        padding: 12px 20px !important;
    }
  </style>
  <link rel="icon" href="image/logo/ishahiya-logo.png" type="image/png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <!-- ✅ SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
<?php include 'includes/header_nav.php'; ?>
<?php include 'includes/category_nav.php'; ?>
    <section class="hero-section">
        <div class="container">
            <h1>Shopping Cart</h1>
            <p>Review and checkout your items</p>
        </div>
    </section>

<div class="container pb-5">

    <?php if (empty($cart_items)): ?>
        <div class="empty-cart">
            <i class="fa-solid fa-shopping-basket" style="font-size: 4rem; color: var(--gold); margin-bottom: 20px; opacity: 0.5;"></i>
            <h4>Your cart is currently empty.</h4>
            <a href="index.php" class="btn btn-gold mt-3">Continue Shopping</a>
        </div>
    <?php else: ?>
        <form method="post">
            <div class="table-responsive">
                <table class="table cart-table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Image</th>
                            <th>Sku No</th>
                            <th>Product Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cart_items as $id => $product): 
                        $qty = isset($product['quantity']) ? (int)$product['quantity'] : 1;
                        $price = isset($product['price']) ? (float)$product['price'] : 0.00;
                        $name = $product['name'];
                        $image = $product['images1'];
                        $sku_no = $product['sku_no'];
                        $product_subtotal = $price * $qty;

                        // ✅ Dynamic Image Paths Logic
                        $final_image_path = 'uploads/no-image.png'; 
                        
                        if (!empty($image)) {
                            $image = trim($image);
                            // 1. Try direct path
                            if (file_exists(__DIR__ . '/' . $image)) {
                                $final_image_path = $image;
                            }
                            // 2. Try prepending shop_admin/
                            elseif (file_exists(__DIR__ . '/shop_admin/' . $image)) {
                                $final_image_path = 'shop_admin/' . $image;
                            }
                            // 3. Try legacy subshop path
                            elseif (file_exists(__DIR__ . '/shop_admin/uploads/subshop/' . basename($image))) {
                                $final_image_path = 'shop_admin/uploads/subshop/' . basename($image);
                            }
                        }
                    ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($final_image_path) ?>" class="cart-image" alt="photo">
                            </td>
                            <td><?= htmlspecialchars($sku_no) ?></td>
                            <td><?= htmlspecialchars($name) ?></td>
                            <td>
                                <div class="input-group quantity-group" style="width: 130px;">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="changeQty(this, -1, <?= $user_id > 0 ? $product['id'] : $id ?>)">-</button>
                                    <input type="number" class="form-control form-control-sm text-center qty-input" 
                                           value="<?= $qty ?>" min="1" 
                                           onchange="updateQty(this, <?= $user_id > 0 ? $product['id'] : $id ?>)">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="changeQty(this, 1, <?= $user_id > 0 ? $product['id'] : $id ?>)">+</button>
                                </div>
                            </td>
                            <td>₹<?= number_format($price, 2) ?></td>
                            <td>₹<?= number_format($product_subtotal, 2) ?></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('<?= $product['cart_row_id'] ?>')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <a href="index.php" class="btn btn-outline-warning">Continue Shopping</a>
                </div>
            </div>

            <div class="row mt-4 justify-content-end">
                <div class="col-md-6">
                    <div class="cart-total-box">
                        <h4 class="mb-3" style="color: var(--gold);">Cart Summary</h4>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Items:</span>
                            <span><?= count($cart_items) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span style="font-size: 1.2rem; font-weight: 700;">Subtotal:</span>
                            <span style="font-size: 1.2rem; font-weight: 700; color: var(--gold);">₹<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-gold w-100">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>



<?php include 'includes/footer.php'; ?>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Remove Item?',
        text: "Do you want to remove this product from your cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d4af37',
        cancelButtonColor: '#333',
        confirmButtonText: 'Yes, remove it!',
        background: '#111',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'cart.php?remove=' + id;
        }
    })
}

function changeQty(btn, delta, cartId) {
    let input = btn.parentElement.querySelector('.qty-input');
    let newVal = parseInt(input.value) + delta;
    if (newVal < 1) newVal = 1;
    input.value = newVal;
    updateQty(input, cartId);
}

function updateQty(input, cartId) {
    let qty = input.value;
    if (qty < 1) {
        input.value = 1;
        qty = 1;
    }

    // Show loading state
    Swal.fire({
        title: 'Updating...',
        didOpen: () => { Swal.showLoading() },
        allowOutsideClick: false,
        timer: 500,
        showConfirmButton: false,
        background: '#111',
        color: '#fff'
    });

    let formData = new FormData();
    formData.append('update_qty', '1');
    formData.append('cart_id', cartId);
    formData.append('quantity', qty);

    fetch('cart.php', {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        try {
            const text = await response.text();
            return JSON.parse(text);
        } catch(e) {
            return {status: 'success'}; // Fallback if plain text "success"
        }
    })
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else if (data.status === 'error' && data.message === 'limit_reached') {
            input.value = data.max_stock; // Revert to max allowed
            const itemText = data.max_stock === 1 ? ' item' : ' items';
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Stock Limit Reached! Only ' + data.max_stock + itemText + ' available.',
                showConfirmButton: false,
                showCloseButton: true,
                timerProgressBar: true,
                timer: 4000,
                background: '#1a1a1a',
                color: '#fff',
                iconColor: '#ff4757',
                customClass: {
                    popup: 'premium-error-toast'
                }
            }).then(() => {
                // We optionally reload or just let them stay on page with the corrected input value.
                // It's better to silently fix the DB to max_stock, so let's call updateQty again with max_stock!
                if (parseInt(qty) > data.max_stock) {
                    // Fast update to actual max stock without looping infinitely
                    let fixData = new FormData();
                    fixData.append('update_qty', '1');
                    fixData.append('cart_id', cartId);
                    fixData.append('quantity', data.max_stock);
                    fetch('cart.php', { method: 'POST', body: fixData }).then(() => location.reload());
                }
            });
        }
    });
}

// ✅ Show Success Toast if item removed
<?php if (isset($_GET['removed']) && $_GET['removed'] === 'success'): ?>
Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'success',
    title: 'Item removed from cart',
    showConfirmButton: false,
    timer: 3000,
    background: '#111',
    color: '#fff'
});
<?php endif; ?>
</script>
</body>
</html>
