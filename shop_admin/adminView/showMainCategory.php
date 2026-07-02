<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container mt-4">
  <h3 class="mb-3">Main Categories</h3>

  <!-- Add Button -->
  <button class="btn btn-primary mb-3 float-end" onclick="openAddMainCategory()">
    + Add Main Category
  </button>

  <!-- Table -->
  <div class="table-responsive-custom">
    <table class="custom-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Main Category</th>
          <th>Slug</th>
          <th>Icon</th>
          <th>Sub Categories</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $sql = "SELECT * FROM main_category ORDER BY id DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            $mainCatId = (int)$row['id'];

            // Fetch sub-categories count
            $subCountSql = "SELECT COUNT(*) as total FROM sub_category WHERE main_category_id = $mainCatId";
            $subCountRes = $conn->query($subCountSql);
            $subCount = $subCountRes->fetch_assoc()['total'];
            ?>
        <tr>
          <td><span class="font-weight-bold">#<?= $row['id'] ?></span></td>
          <td><span class="text-dark font-weight-600"><?= htmlspecialchars($row['main_category_name']) ?></span></td>
          <td><code><?= htmlspecialchars($row['slug']) ?></code></td>
          <td>
            <div class="icon-badge">
              <i class="<?= htmlspecialchars($row['icon_class'] ?: 'fas fa-folder') ?>"></i>
              <span><?= htmlspecialchars($row['icon_class'] ?: 'No Icon') ?></span>
            </div>
          </td>
          <td>
            <span class="badge badge-pill badge-light border" style="font-size: 0.9rem; padding: 6px 12px;">
              <i class="fas fa-sitemap mr-1 text-muted"></i> <?= $subCount ?> Sub-categories
            </span>
          </td>
          <td>
            <div class="action-btn-group justify-content-center">
              <button 
                class="btn-action btn-action-edit" 
                title="Edit"
                onclick="openEditMainCategory(
                  '<?= $row['id'] ?>', 
                  '<?= addslashes($row['main_category_name']) ?>', 
                  '<?= addslashes($row['slug']) ?>',
                  '<?= addslashes($row['icon_class']) ?>'
                )">
                <i class="fas fa-edit"></i>
              </button>
              <button 
                class="btn-action btn-action-delete" 
                title="Delete"
                onclick="deleteMainCategory('<?= $row['id'] ?>')">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </td>
        </tr>
            <?php
          }
        } else {
          echo "<tr><td colspan='6' class='text-center py-5 text-muted'>No Main Categories found</td></tr>";
        }
      ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Include Modals -->
<?php include_once "modals/addMainCategory.php"; ?>
<?php include_once "modals/editMainCategory.php"; ?>

<script>
function openAddMainCategory() {
  $("#addMainCategoryModal").modal("show");
}

$(document).off("submit", "#addMainCategoryForm").on("submit", "#addMainCategoryForm", function(e){
  e.preventDefault();
  const btn = $(this).find('button[type="submit"]');
  btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

  $.post("controller/categoryController.php", $(this).serialize()+"&action=add", function(res){
    btn.prop('disabled', false).text('Save');
    if(res.trim()=="success"){
      showToast("Main Category Added Successfully!", "success");
      $("#addMainCategoryModal").modal("hide");
      showMainCategory();
    }else{
      showToast("Error while adding! " + res, "danger");
    }
  });
});

function openEditMainCategory(id, name, slug, icon){
  $("#edit_mc_id").val(id);
  $("#edit_mc_name").val(name);
  $("#edit_mc_slug").val(slug);
  $("#edit_mc_icon_class").val(icon);
   // ✅ नया field
  $("#editMainCategoryModal").modal("show");
}


$(document).off("submit", "#editMainCategoryForm").on("submit", "#editMainCategoryForm", function(e){
  e.preventDefault();
  $.post("controller/mainCategoryController.php", $(this).serialize()+"&action=edit", function(res){
    if(res.trim()=="success"){
      showToast("Main Category Updated!", "success");
      $("#editMainCategoryModal").modal("hide");
      showMainCategory();
    }else{
      showToast("Error while updating! " + res, "danger");
    }
  });
});

function deleteMainCategory(id){
  showConfirm(
    "Delete Main Category?", 
    "This will permanently delete this category and all its associations.",
    function() {
      $.post("controller/mainCategoryController.php", {id:id, action:"delete"}, function(res){
        if(res.trim()=="success"){
          showToast("Main Category Deleted!", "danger");
          const params = new URLSearchParams(window.location.hash.split('?')[1]);
          const page = params.get('page') || 1;
          loadModule('main-categories', page);
        }else{
          showToast("Error while deleting! " + res, "danger");
        }
      });
    }
  );
}
</script>
