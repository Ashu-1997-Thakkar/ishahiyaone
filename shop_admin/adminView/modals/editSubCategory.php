<div class="modal fade" id="editSubCategoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSubCategoryForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Sub Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Hidden ID -->
          <input type="hidden" name="id" id="edit_sc_id">

          <div class="mb-3">
            <label for="edit_sc_name" class="form-label">Sub Category Name</label>
            <input type="text" name="sub_category_name" id="edit_sc_name" class="form-control" required autocomplete="off">
          </div>

          <div class="mb-3">
            <label for="edit_sc_slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="edit_sc_slug" class="form-control" required autocomplete="off">
          </div>

          <div class="mb-3">
            <label for="edit_sc_main_cat" class="form-label">Main Category</label>
            <select name="main_category_id" id="edit_sc_main_cat" class="form-select" required>
              <option value="">-- Select Main Category --</option>
              <?php
              include_once dirname(__DIR__, 2) . "/config/dbconnect.php";
              $cats = $conn->query("SELECT * FROM main_category ORDER BY main_category_name ASC");
              while($c = $cats->fetch_assoc()){
                echo "<option value='".$c['id']."'>".$c['main_category_name']."</option>";
              }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_sc_icon" class="form-label">Select Icon</label>
            <select class="form-control" name="icon" id="edit_sc_icon" required>
              <option value="">-- Select Icon --</option>
              <option value="fa fa-mobile">📱 Mobile</option>
              <option value="fa fa-male">👔 Men Wear</option>
              <option value="fa fa-female">👗 Women Wear</option>
              <option value="fa fa-child">🧒 Kids Wear</option>
              <option value="fa fa-laptop">💻 Laptop</option>
              <option value="fa fa-desktop">🖥️ Desktop</option>
              <option value="fa fa-hdd">💾 Storage</option>
              <option value="fa fa-print">🖨️ Printers</option>
              <option value="fa fa-clock">⌚ Wearable Tech</option>
              <option value="fa fa-bolt">⚡ Smart Energy</option>
              <option value="fa fa-lock">🔒 Home Security</option>
              <option value="fa fa-lightbulb">💡 Smart Lighting</option>
              <option value="fa fa-map-marker-alt">📍 GPS</option>
              <option value="fa fa-network-wired">🌐 Network</option>
              <option value="fa fa-wifi">📶 Routers</option>
              <option value="fa fa-server">🖧 Servers</option>
            </select>
          </div>
        </div>


        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
