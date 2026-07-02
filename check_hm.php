<?php
require_once __DIR__ . '/db.php';
header('Content-Type: text/plain; charset=utf-8');

$sql = "SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS Image1, CAST('all_category' AS CHAR CHARACTER SET utf8mb4) AS tbl FROM all_category WHERE name LIKE '%Denim%' OR name LIKE '%H&M%'
        UNION ALL
        SELECT id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(Image1 AS CHAR CHARACTER SET utf8mb4) AS Image1, CAST('subcategories' AS CHAR CHARACTER SET utf8mb4) AS tbl FROM subcategories WHERE name LIKE '%Denim%' OR name LIKE '%H&M%'
        UNION ALL
        SELECT product_id AS id, CAST(name AS CHAR CHARACTER SET utf8mb4) AS name, CAST(image AS CHAR CHARACTER SET utf8mb4) AS Image1, CAST('products' AS CHAR CHARACTER SET utf8mb4) AS tbl FROM products WHERE name LIKE '%Denim%' OR name LIKE '%H&M%'";

$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        echo "Found in Table: {$row['tbl']} | ID: {$row['id']}\n";
        echo "Name: {$row['name']}\n";
        echo "Image Column Value: '{$row['Image1']}'\n";
        
        $searchName = basename(trim($row['Image1']));
        echo "Searching for file matching '{$searchName}'...\n";
        
        if (!empty($searchName)) {
            $ite = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__));
            $foundFiles = [];
            foreach ($ite as $file) {
                if ($file->isFile()) {
                    $fn = $file->getFilename();
                    if (stripos($fn, 'Denim') !== false || stripos($fn, 'Jeans') !== false || stripos($fn, 'H&M') !== false || stripos($fn, $searchName) !== false) {
                        $path = $file->getPathname();
                        if (strpos($path, '.git') === false && strpos($path, '.gemini') === false && strpos($path, 'brain') === false) {
                            $foundFiles[] = str_replace(__DIR__, '', $path);
                        }
                    }
                }
            }
            echo "Matches found on disk:\n";
            print_r($foundFiles);
        }
        echo "--------------------------------------------------\n\n";
    }
} else {
    echo "No rows found or SQL Error: " . $conn->error;
}
