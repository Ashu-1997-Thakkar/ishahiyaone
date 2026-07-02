<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";

if(isset($_POST['main_category_id'])){
    $mainCatId = intval($_POST['main_category_id']);
    $stmt = $conn->prepare("SELECT id, sub_category_name FROM sub_category WHERE main_category_id=? ORDER BY sub_category_name ASC");
    $stmt->bind_param("i", $mainCatId);
    $stmt->execute();
    $res = $stmt->get_result();

    $data = [];
    while($row = $res->fetch_assoc()){
        $data[] = $row;
    }

    if(count($data) > 0){
        echo json_encode(["status"=>"success","data"=>$data]);
    } else {
        echo json_encode(["status"=>"empty","data"=>[]]);
    }
    exit;
}
?>
