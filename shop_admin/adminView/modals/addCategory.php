<div class="modal fade" id="addCategoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addCategoryForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label for="add_c_name" class="form-label">Category Name</label>
            <input type="text" name="category_name" id="add_c_name" class="form-control" placeholder="Category Name" required autocomplete="off">
          </div>

          <div class="mb-3">
            <label for="add_c_slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="add_c_slug" class="form-control" placeholder="Slug" required autocomplete="off">
          </div>
  
          <div class="mb-3">
            <label for="add_c_main_cat" class="form-label">Main Category</label>
            <select name="main_category_id" id="add_c_main_cat" class="form-control" required>
              <option value="">-- Select Main Category --</option>
              <?php
                include_once dirname(__DIR__, 2) . "/config/dbconnect.php";
                $sql = "SELECT * FROM main_category ORDER BY main_category_name ASC";
                $result = $conn->query($sql);
                if ($result && $result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                    echo "<option value='".$row['id']."'>".$row['main_category_name']."</option>";
                  }
                } else {
                  echo "<option value=''>❌ No Main Categories Found</option>";
                }
              ?>
            </select>
          </div>
        </div>


        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
