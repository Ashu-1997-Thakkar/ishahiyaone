<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
$ID = $_POST['record'];

$qry = mysqli_query($conn, "
SELECT 
    p.*, 
    c.category_id, 
    c.category_name
FROM products p
LEFT JOIN category c ON p.category_id = c.category_id
WHERE p.product_id='$ID'
");

if(mysqli_num_rows($qry) > 0){
  $row1 = mysqli_fetch_assoc($qry);
?>

<form onsubmit="updateItems(); return false;" class="premium-form compressed-form">
    <input type="hidden" id="product_id" value="<?= $row1['product_id'] ?>">
    <div class="text-right mb-2">
        <span class="badge badge-warning">Product ID: #<?= $row1['product_id'] ?></span>
    </div>

    <input type="hidden" id="product_id" value="<?= $row1['product_id'] ?>">

    <div class="row">
      <!-- Line 1: Name & Brand -->
      <div class="col-md-8 form-group">
        <label class="font-weight-600">Product Name</label>
        <input type="text" id="p_name" class="form-control" value="<?= htmlspecialchars($row1['name']) ?>" placeholder="Enter product title">
      </div>
      <div class="col-md-4 form-group">
        <label class="font-weight-600">Brand</label>
        <input type="text" id="brand" class="form-control" value="<?= htmlspecialchars($row1['brand'] ?? '') ?>" placeholder="Brand name">
      </div>
    </div>

    <div class="row">
      <!-- Line 2: Price, SKU, Stock -->
      <div class="col-md-4 form-group">
        <label class="font-weight-600">Price (₹)</label>
        <input type="number" id="p_price" class="form-control" value="<?= $row1['price'] ?>">
      </div>
      <div class="col-md-4 form-group">
        <label class="font-weight-600">SKU Code</label>
        <input type="text" id="sku" class="form-control" value="<?= htmlspecialchars($row1['sku'] ?? '') ?>" placeholder="SKU Code">
      </div>
      <div class="col-md-4 form-group">
        <label class="font-weight-600">Available Stock</label>
        <input type="number" id="stock" class="form-control" value="<?= $row1['stock'] ?? '0' ?>">
      </div>
    </div>

    <div class="row">
      <!-- Line 3: Category & Sub Category -->
      <div class="col-md-6 form-group">
        <label class="font-weight-600">Category</label>
        <select id="category" class="form-control custom-select-premium">
          <?php
          $cat = $conn->query("SELECT * FROM category");
          while($c = $cat->fetch_assoc()):
          ?>
          <option value="<?= $c['category_id'] ?>" <?= ($row1['category_id'] == $c['category_id']) ? 'selected' : '' ?>>
            <?= $c['category_name'] ?>
          </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-6 form-group">
        <label class="font-weight-600">Sub Category</label>
        <select id="sub_category" class="form-control custom-select-premium">
          <option value="0">General</option>
          <?php
          $sub = $conn->query("SELECT * FROM sub_category WHERE main_category_id = " . $row1['category_id']);
          while($s = $sub->fetch_assoc()):
          ?>
          <option value="<?= $s['id'] ?>" <?= ($row1['sub_category_id'] == $s['id']) ? 'selected' : '' ?>>
            <?= $s['sub_category_name'] ?>
          </option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>

    <div class="row">
      <!-- Line 4: Description -->
      <div class="col-12 form-group">
        <label class="font-weight-600">Product Description</label>
        <textarea id="p_desc" class="form-control" style="height: 60px !important;"><?= htmlspecialchars($row1['description'] ?? '') ?></textarea>
      </div>
    </div>

    <!-- Line 5: Sizes -->
    <div class="row">
      <div class="col-12">
        <?php $selectedSizes = explode(',', $row1['size_ids'] ?? ''); ?>
        <div class="form-group">
          <label class="font-weight-600 d-block">Available Sizes</label>
          <div class="size-selection-grid border rounded p-2 bg-white d-flex flex-wrap shadow-sm">
            <?php
            $sizes = $conn->query("SELECT * FROM sizes ORDER BY size_name ASC");
            while($sz = $sizes->fetch_assoc()):
              $isChecked = in_array($sz['size_id'], $selectedSizes) ? 'checked' : '';
            ?>
            <div class='size-checkbox-item mr-2 mb-1'>
              <input type='checkbox' id='esz_<?= $sz['size_id'] ?>' name='sizes[]' value='<?= $sz['size_id'] ?>' class='d-none size-check-input' <?= $isChecked ?>>
              <label for='esz_<?= $sz['size_id'] ?>' class='size-label'><?= htmlspecialchars($sz['size_name']) ?></label>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Line 6: Image -->
    <div class="row">
      <div class="col-12">
        <div class="compressed-image-card p-2 border rounded bg-light">
          <div class="row align-items-center">
            <div class="col-md-2 text-center">
              <img src="./uploads/<?= $row1['image'] ?>" class="rounded shadow-sm" style="height: 50px; width: 50px; object-fit: cover; border: 2px solid white;">
              <input type="hidden" id="existingImage" value="<?= $row1['image'] ?>">
            </div>
            <div class="col-md-10">
              <div class="form-group mb-0">
                <label class="small text-muted mb-1">Replace Image</label>
                <div class="custom-file" style="height: 30px;">
                  <input type="file" class="custom-file-input" id="newImage" style="height: 30px;">
                  <label class="custom-file-label" for="newImage" style="height: 30px; line-height: 20px; font-size: 0.8rem;">Choose file...</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-3 d-flex justify-content-end align-items-center">
      <button type="button" class="btn btn-outline-secondary px-4 mr-2 btn-sm" data-dismiss="modal">
        Cancel
      </button>
      <button type="submit" class="btn btn-success px-4 shadow-sm btn-sm font-weight-700" style="background: #c59d2f; border: none;">
        <i class="fas fa-save mr-1"></i> Update Product
      </button>
    </div>
  </form>


<?php } ?>

<style>
  .custom-select-premium {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    height: 45px;
    border-radius: 8px;
  }
  .form-control:focus {
    border-color: #c59d2f;
    box-shadow: 0 0 0 0.2rem rgba(197, 157, 47, 0.15);
  }
  .font-weight-700 { font-weight: 700; }
  .font-weight-600 { font-weight: 600; }

  /* Modern Size Selection */
  .size-label {
      display: inline-block;
      padding: 6px 14px;
      background: #f1f5f9;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      margin: 0;
  }
  .size-check-input:checked + .size-label {
      background: #c59d2f;
      color: white;
      border-color: #c59d2f;
      box-shadow: 0 3px 6px rgba(197, 157, 47, 0.3);
      transform: translateY(-1px);
  }
  .size-label:hover { border-color: #c59d2f; background: #fff; }
</style>