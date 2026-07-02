<?php
include_once "shop_admin/config/dbconnect.php";
try {
    $title = 'test';
    $description = '24';
    $sub_category_id = 'NULL';
    $discount_percent = 0;
    $promo_code = '';
    $start_date = '2026-06-20';
    $end_date = '2026-07-09';
    $status = 1;
    $offer_id = 1;

    $sql = "UPDATE bumper_offers SET 
            title='$title', 
            description='$description',
            sub_category_id=$sub_category_id,
            discount_percent=$discount_percent,
            promo_code='$promo_code',
            start_date='$start_date', 
            end_date='$end_date', 
            status='$status' 
            WHERE id=$offer_id";
    echo "SQL: $sql\n";
    $conn->query($sql);
    echo "Success!";
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage();
}
?>
