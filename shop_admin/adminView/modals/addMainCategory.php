<div class="modal fade" id="addMainCategoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="addMainCategoryForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Main Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="add_mc_name" class="form-label">Main Category Name</label>
            <input type="text" name="main_category_name" id="add_mc_name" class="form-control" placeholder="Enter Main Category Name" required autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="add_mc_icon_class" class="form-label">Category Icon</label>
            <select class="form-select" id="add_mc_icon_class" name="icon_class" required>
              <option value="" disabled selected>-- Select Icon --</option>
              <option value="fa-solid fa-tv">📺 Electronics</option>
              <option value="fa-solid fa-shirt">👕 Fashion</option>
              <option value="fa-solid fa-network-wired">🌐 Networking</option>
              <option value="fa-solid fa-house-signal">🏠 Smart Home</option>
              <option value="fa-solid fa-mobile-screen">📱 Mobile</option>
              <option value="fa-solid fa-laptop">💻 Laptop</option>
              <option value="fa-solid fa-couch">🛋 Furniture</option>
              <option value="fa-solid fa-blender">⚡ Appliances</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="add_mc_slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="add_mc_slug" class="form-control" placeholder="Enter Slug (e.g. fashion)" required autocomplete="off">
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
