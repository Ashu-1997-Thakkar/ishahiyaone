<?php include_once dirname(__DIR__) . "/config/dbconnect.php"; ?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Shop Collection</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .modal-content { background-color: white; border-radius: 10px; }
    .modal-backdrop { z-index: 1040 !important; }
    .modal { background: rgba(0, 0, 0, 0.6); z-index: 1050; }
    body.modal-open { overflow: hidden; }
    img.product-thumb { object-fit: cover; width: 50px; height: 50px; border-radius: 4px; }
  </style>
</head>
<body>
<div class="container my-4">
  <h4 class="mb-4">Shop Collection1</h4>
  <table class="table table-bordered align-middle">
  <thead class="text-white" style="background-color: #c59d2f;">
    <tr>
      <th>S.N.</th>
      <th>ID</th>
      <th>Image</th>
      <th>Product Name</th>
      <th>Category</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sql = "SELECT id, name, category, image1 FROM all_category";
    $result = mysqli_query($conn, $sql);
    $sn = 1;

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $img = trim($row['image1']);
            $imgTag = "";

            if (!empty($img)) {
                $imgPath = '/' . ltrim($img, '/'); // ensure no double slashes

                // Optional: check file physically exists
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . $imgPath)) {
                    $imgTag = "<img src='{$imgPath}' class='product-thumb'>";
                } else {
                    // Debug help
                    $imgTag = "<img src='/assets/no-image.png' class='product-thumb' title='Missing: {$imgPath}'>";
                }
            } else {
                $imgTag = "<img src='/assets/no-image.png' class='product-thumb'>";
            }

            $productName = htmlspecialchars($row['name']);
            $category = htmlspecialchars($row['category']);
            $id = (int)$row['id'];

            echo "<tr>
                <td>{$sn}</td>
                <td>{$id}</td>
                <td>{$imgTag}</td>
                <td>{$productName}</td>
                <td>{$category}</td>
                <td>
                    <button class='btn btn-sm btn-primary' onclick='editCollection({$id})'>Edit</button>
                    <a href='/shop_admin/controller/deleteallcat.php?id={$id}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?');\">Delete</a>
                    <button class='btn btn-success btn-sm' onclick='markAsNewArrival(<?= $id ?>)'>Mark as New Arrival</button>

                </td>
            </tr>";

            $sn++;
        }
    } else {
        echo "<tr><td colspan='6' class='text-center'>No collections found.</td></tr>";
    }
    ?>
  </tbody>
</table>

  <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
</div>
</body>
</html>


    <!-- ADD PRODUCT MODAL -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-4">
        <h5 class="modal-title mb-3" id="addProductModalLabel">Add New Product</h5>
        <form action="/shop_admin/controller/addallcat.php" method="POST" enctype="multipart/form-data">
            <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Category:</label>
                <select class="form-select" name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
                <option value="Kids">Kids</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Product Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Brand:</label>
                <input type="text" class="form-control" name="brand" required>
            </div>
            <div class="col-12 mb-3">
                <label class="form-label">Description:</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            </div>

            <div class="row">
            <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="col-md-6 mb-3">
                <label class="form-label">Product Image (<?= $i ?>):</label>
                <input type="file" class="form-control" name="image<?= $i ?>" accept="image/*" required>
                </div>
            <?php endfor; ?>
            </div>

            <div class="text-end mt-3">
            <button type="submit" name="upload" class="btn btn-primary">Add Product</button>
            </div>
        </form>
        </div>
    </div>
    </div>
    </body>
    </html>
<!-- EDIT PRODUCT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content p-4">
      <h5 class="modal-title mb-3" id="editProductModalLabel">Edit Product</h5>
      <form action="/shop_admin/controller/bg.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="edit_id" id="edit_id">

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Product Category:</label>
            <select class="form-select" name="category_name" id="edit_category" required>
              <option value="">-- Select Category --</option>
              <option value="Men">Men</option>
              <option value="Women">Women</option>
              <option value="Kids">Kids</option>
            </select>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Product Name:</label>
            <input type="text" class="form-control" name="product_name" id="edit_product_name" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Price:</label>
            <input type="number" class="form-control" name="price" id="edit_price" required>
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label">Brand:</label>
            <input type="text" class="form-control" name="brand" id="edit_brand" required>
          </div>

          <div class="col-12 mb-3">
            <label class="form-label">Description:</label>
            <textarea class="form-control" name="description" id="edit_description" rows="3" required></textarea>
          </div>
        </div>

        <div class="row">
          <?php for ($i = 1; $i <= 4; $i++): ?>
            <div class="col-md-6 mb-3">
              <label class="form-label">Product Image <?= $i ?>:</label>
              <input type="file" class="form-control" name="new_image<?= $i ?>" accept="image/*">
              <input type="hidden" name="old_image<?= $i ?>" id="old_image<?= $i ?>">
              <img id="preview_image<?= $i ?>" src="" alt="Preview" class="mt-2" style="height: 60px; display: none;">
            </div>
          <?php endfor; ?>
        </div>

        <div class="text-end mt-3">
          <button type="submit" class="btn btn-primary">Update Product</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

    <!-- Subcategory Modal -->
    <div class="modal fade" id="subcategoryModal" tabindex="-1" aria-labelledby="subcategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form action="/shop_admin/controller/Dft.php" method="POST" enctype="multipart/form-data">
            <div class="modal-header">
            <h5 class="modal-title" id="subcategoryModalLabel">Add Subcategory</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            <!-- Category Name (shown but disabled) -->
            <div class="form-group mb-3">
                <label for="modal_category_display">Category:</label>
                <input type="text" class="form-control" id="modal_category_display" disabled>
            </div>

            <!-- Actual value to submit -->
            <input type="hidden" name="category_name" id="modal_category_name_real">

            <!-- Hidden Collection ID -->
            <input type="hidden" name="collection_id" id="modal_collection_id">

            <!-- Subcategory Name -->
            <div class="mb-3">
                <label for="subcategory_name" class="form-label">Name</label>
                <input type="text" class="form-control" name="subcategory_name" required>
            </div>

            <!-- Brand -->
            <div class="mb-3">
                <label for="name" class="form-label">Brand</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <!-- Price -->
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" name="price" required>
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>

            <!-- Image Uploads -->
            <div class="mb-3">
                <label class="form-label">Image 1 (optional)</label>
                <input type="file" class="form-control" name="image1">
            </div>
            <div class="mb-3">
                <label class="form-label">Image 2 (optional)</label>
                <input type="file" class="form-control" name="image2">
            </div>
            <div class="mb-3">
                <label class="form-label">Image 3 (optional)</label>
                <input type="file" class="form-control" name="image3">
            </div>
            <div class="mb-3">
                <label class="form-label">Image 4 (optional)</label>
                <input type="file" class="form-control" name="image4">
            </div>
            </div>

            <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save Subcategory</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
    </div>
    </div>

    <!-- JS -->
 <script>
function editCollection(id) {
    fetch('/shop_admin/controller/getAllCategory.php?id=' + id)
    .then(res => {
        if (!res.ok) throw new Error("HTTP error " + res.status);
        return res.json();
    })
    .then(result => {
        if (result.success) {
            const data = result.data;
            document.getElementById("edit_id").value = data.id;
            document.getElementById("edit_product_name").value = data.name || '';
            document.getElementById("edit_description").value = data.description || '';
            document.getElementById("edit_price").value = data.price || '';
            document.getElementById("edit_brand").value = data.brand || '';
            document.getElementById("edit_category").value = data.category || '';

            document.getElementById("old_image1").value = data.image1 || '';
            document.getElementById("old_image2").value = data.image2 || '';
            document.getElementById("old_image3").value = data.image3 || '';
            document.getElementById("old_image4").value = data.image4 || '';

            // ✅ Updated: preview images directly from stored relative path
            for (let i = 1; i <= 4; i++) {
                const imgVal = data[`image${i}`];
                const preview = document.getElementById(`preview_image${i}`);
                if (imgVal) {
                   preview.src = `/ishahiyaone-image/${encodeURIComponent(imgVal)}`;

                    preview.style.display = "block";
                } else {
                    preview.style.display = "none";
                }
            }

            // Show modal (if not triggered already)
            const modal = new bootstrap.Modal(document.getElementById('editProductModal'));
            modal.show();

        } else {
            alert("Product not found");
        }
    })
    .catch(err => {
        console.error("Fetch error:", err);
        alert("Error loading product data");
    });
}
</script>




    <!-- Bootstrap NEXT -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Your scripts that use jQuery and Bootstrap -->
    <script>
    function openSubcategoryModal(productId, categoryName) {
    // Set shown category name
    document.getElementById('modal_category_display').value = categoryName;

    // Set the hidden product ID (collection_id) in the modal
    document.getElementById('modal_collection_id').value = productId;


    // Make sure the category dropdown is disabled
    document.getElementById('modal_category_name_real').value = categoryName;
    // Open the modal (using Bootstrap modal)
    $('#subcategoryModal').modal('show');
    }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    </body>
    </html>
