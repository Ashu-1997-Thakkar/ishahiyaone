<div class="modal fade" id="editMainCategoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editMainCategoryForm">
        <div class="modal-header">
          <h5 class="modal-title">Edit Main Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_mc_id">
          <div class="mb-3">
            <label for="edit_mc_name" class="form-label">Main Category Name</label>
            <input type="text" name="main_category_name" id="edit_mc_name" class="form-control" required autocomplete="off">
          </div>
          <div class="mb-3">
            <label for="edit_mc_icon_class" class="form-label">Category Icon (FontAwesome)</label>
            <select name="icon_class" id="edit_mc_icon_class" class="form-select">
              <option value="">-- Select Icon --</option>
              <option value="fa-solid fa-house">🏠 Home</option>
              <option value="fa-solid fa-network-wired">🌐 Networking</option>
              <option value="fa-solid fa-shirt">👕 Fashion</option>
              <option value="fa-solid fa-tv">📺 Electronics</option>
              <option value="fa-solid fa-mobile-screen">📱 Mobile</option>
              <option value="fa-solid fa-laptop">💻 Laptop</option>
              <option value="fa-solid fa-blender">⚡ Appliances</option>
              <option value="fa-solid fa-gem">💎 Jewelry</option>
              <option value="fa-solid fa-couch">🛋 Furniture</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_mc_slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="edit_mc_slug" class="form-control" required autocomplete="off">
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
