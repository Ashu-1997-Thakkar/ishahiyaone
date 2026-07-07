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
              WHERE oi.order_id = $orderID
              GROUP BY oi.id
            ";

            // Execute the query
            $result = $conn->query($sql);
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
