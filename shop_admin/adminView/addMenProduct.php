<h4>Add Men Product</h4>
<form action="./controller/addProductController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="category" value="Men">
    
    <label>Name:</label>
    <input type="text" name="name" required><br>
    
    <label>Price:</label>
    <input type="number" name="price" required><br>
    
    <label>Image:</label>
    <input type="file" name="image" required><br><br>
    
    <button class="btn btn-success" type="submit" name="addProduct">Add Product</button>
</form>
