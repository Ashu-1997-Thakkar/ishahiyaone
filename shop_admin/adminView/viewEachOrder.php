<div class="container">
  <table class="table table-striped">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Unit Price</th>
        </tr>
    </thead>
    <tbody>
      <?php
        include_once dirname(__DIR__) . "/config/dbconnect.php";

        // Get the order ID from the GET request
        $orderID = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

        if ($orderID > 0) {
            $linked_order_id = $orderID;
            $b_chk = $conn->query("SELECT id, product_name, total_amount FROM billing_details WHERE id = $orderID LIMIT 1");
            $b_row_direct = ($b_chk) ? $b_chk->fetch_assoc() : null;
            if ($b_row_direct) {
                $linked_order_id = (int)$b_row_direct['id'];
            }
            
            // Query to retrieve data from the order_item table
            $sql = "
              SELECT 
                COALESCE(NULLIF(oi.image, ''), sc.Image1, ac.Image1, p.image, bo.banner_image, 'no-image.png') AS product_image, 
                oi.product_name, 
                oi.size, 
                oi.quantity, 
                oi.price 
              FROM order_items oi
              LEFT JOIN subcategories sc ON (oi.product_name COLLATE utf8mb4_general_ci = sc.name COLLATE utf8mb4_general_ci OR sc.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', sc.name COLLATE utf8mb4_general_ci, '%'))
              LEFT JOIN all_category ac ON (oi.product_name COLLATE utf8mb4_general_ci = ac.name COLLATE utf8mb4_general_ci OR ac.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', ac.name COLLATE utf8mb4_general_ci, '%'))
              LEFT JOIN products p ON (oi.product_name COLLATE utf8mb4_general_ci = p.name COLLATE utf8mb4_general_ci OR p.name COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', p.name COLLATE utf8mb4_general_ci, '%'))
              LEFT JOIN bumper_offers bo ON (oi.product_name COLLATE utf8mb4_general_ci = bo.title COLLATE utf8mb4_general_ci OR bo.title COLLATE utf8mb4_general_ci LIKE CONCAT('%', oi.product_name COLLATE utf8mb4_general_ci, '%') OR oi.product_name COLLATE utf8mb4_general_ci LIKE CONCAT('%', bo.title COLLATE utf8mb4_general_ci, '%'))
              WHERE oi.order_id IN ($linked_order_id, $orderID)
              GROUP BY oi.id
            ";

            // Execute the query
            $result = $conn->query($sql);
            
            // Self-healing fallback if order_items is empty for this order
            if ($result && $result->num_rows === 0) {
                $b_row = $b_row_direct;
                if (!$b_row) {
                    $ord_q = $conn->query("SELECT * FROM orders WHERE id = $orderID LIMIT 1");
                    if ($ord_q && $ord_row = $ord_q->fetch_assoc()) {
                        $u_id = (int)($ord_row['user_id'] ?? 0);
                        $tot = (float)($ord_row['total_price'] ?? 0);
                        $phone = $conn->real_escape_string($ord_row['Contact'] ?? '');
                        $ord_time = $conn->real_escape_string($ord_row['order_date'] ?? '');
                        $b_q = $conn->query("SELECT * FROM billing_details WHERE (user_id = $u_id OR mobile = '$phone' OR alt_mobile = '$phone') AND ABS(total_amount - $tot) < 5 ORDER BY ABS(TIMESTAMPDIFF(SECOND, created_at, '$ord_time')) ASC LIMIT 1");
                        if ($b_q) $b_row = $b_q->fetch_assoc();
                    }
                }
                
                if ($b_row && !empty($b_row['product_name'])) {
                    $prod_names = explode(',', $b_row['product_name']);
                    $tot = (float)($b_row['total_amount'] ?? 0);
                    $ins_id = $linked_order_id > 0 ? $linked_order_id : $orderID;
                    foreach ($prod_names as $p_name) {
                        $p_name = trim($p_name);
                        if (empty($p_name)) continue;
                        $p_safe = $conn->real_escape_string($p_name);
                        $l_q = $conn->query("
                            SELECT name, price, COALESCE(NULLIF(Image1, ''), 'uploads/no-image.png') AS img, size FROM subcategories WHERE name LIKE '%$p_safe%'
                            UNION ALL
                            SELECT name, price, COALESCE(NULLIF(Image1, ''), 'uploads/no-image.png') AS img, '' AS size FROM all_category WHERE name LIKE '%$p_safe%'
                            UNION ALL
                            SELECT name, price, COALESCE(NULLIF(image, ''), 'uploads/no-image.png') AS img, '' AS size FROM products WHERE name LIKE '%$p_safe%'
                            UNION ALL
                            SELECT title AS name, 0 AS price, COALESCE(NULLIF(banner_image, ''), 'uploads/no-image.png') AS img, '' AS size FROM bumper_offers WHERE title LIKE '%$p_safe%'
                            LIMIT 1
                        ");
                        $l_res = ($l_q) ? $l_q->fetch_assoc() : null;
                        $ins_name = $conn->real_escape_string($l_res['name'] ?? $p_name);
                        $ins_price = (float)($l_res['price'] ?? 0);
                        if ($ins_price == 0 && $tot > 0) {
                            $ins_price = $tot / max(1, count($prod_names));
                        }
                        $ins_img = $conn->real_escape_string($l_res['img'] ?? '');
                        $ins_size = $conn->real_escape_string($l_res['size'] ?? '');
                        
                        $conn->query("INSERT INTO order_items (order_id, product_name, size, quantity, price, image) VALUES ($ins_id, '$ins_name', '$ins_size', 1, $ins_price, '$ins_img')");
                    }
                    $result = $conn->query($sql);
                }
            }
            
            $count = 1;

            // Check if there are results
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Construct full image URL
                    $img = $row["product_image"];
                    $imageURL = "../assets/no-image.png";
                    if (!empty($img) && $img !== 'no-image.png' && $img !== 'uploads/no-image.png') {
                        if (file_exists(__DIR__ . "/../" . $img)) {
                            $imageURL = "../" . $img;
                        } elseif (file_exists(__DIR__ . "/../shop_admin/" . $img)) {
                            $imageURL = "../shop_admin/" . $img;
                        } elseif (file_exists(__DIR__ . "/../shop_admin/uploads/subshop/" . basename($img))) {
                            $imageURL = "../shop_admin/uploads/subshop/" . basename($img);
                        } elseif (file_exists(__DIR__ . "/../shop_admin/uploads/" . basename($img))) {
                            $imageURL = "../shop_admin/uploads/" . basename($img);
                        } else {
                            // Web URL fallback for Linux hosting environments
                            $imageURL = (strpos($img, 'shop_admin/') !== false || strpos($img, 'uploads/') !== false) ? "../" . $img : "../uploads/subshop/" . basename($img);
                        }
                    }
      ?>
                    <tr>
                        <td><?= $count ?></td>
                        <td><img height="80px" src="<?= htmlspecialchars($imageURL) ?>" alt="Product Image" style="object-fit:contain; border-radius:4px; background:#111;"></td>
                        <td><?= htmlspecialchars($row["product_name"]) ?></td>
                        <td><?= htmlspecialchars($row["size"]) ?></td>
                        <td><?= htmlspecialchars($row["quantity"]) ?></td>
                        <td><?= htmlspecialchars(number_format($row["price"], 2)) ?></td>
                    </tr>
      <?php
                    $count++;
                }
            } else {
                echo "<tr><td colspan='6'>No order items found for this order.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Invalid order ID.</td></tr>";
        }
      ?>
    </tbody>
  </table>
</div>
