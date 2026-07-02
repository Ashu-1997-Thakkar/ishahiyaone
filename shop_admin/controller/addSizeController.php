<?php
    include_once dirname(__DIR__) . "/config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $size = $_POST['size'];
       
         $insert = mysqli_query($conn,"INSERT INTO sizes
         (size_name)   VALUES ('$size')");
 
          if(!$insert)
          {
              echo "error: " . mysqli_error($conn);
          }
          else
          {
              echo "success";
          }
     
    }
        
?>