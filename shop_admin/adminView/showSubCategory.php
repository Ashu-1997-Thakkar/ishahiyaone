<?php
include_once dirname(__DIR__) . "/config/dbconnect.php";
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="container mt-4">
  <h3 class="mb-3">Sub Categories</h3>

  <!-- Add Button -->
  <button class="btn btn-primary mb-3 float-end" onclick="openAddSubCategory()">
    + Add Sub Category
  </button>

  <!-- Table -->
  <div class="table-responsive-custom">
    <table class="custom-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Sub Category</th>
          <th>Slug</th>
          <th>Icon</th>
          <th>Main Category</th>
          <th class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT sub_category.*, main_category.main_category_name 
            FROM sub_category 
            JOIN main_category ON sub_category.main_category_id = main_category.id
            ORDER BY sub_category.id DESC";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            ?>
            <tr>
              <td><span class="font-weight-bold">#<?= $row['id'] ?></span></td>
              <td><span class="text-dark font-weight-600"><?= htmlspecialchars($row['sub_category_name']) ?></span></td>
              <td><code><?= htmlspecialchars($row['slug']) ?></code></td>
              <td>
                <div class="icon-badge">
                  <i class="<?= htmlspecialchars($row['icon'] ?: 'fas fa-tag') ?>"></i>
                  <span><?= htmlspecialchars($row['icon'] ?: 'No Icon') ?></span>
                </div>
              </td>
              <td>
                <span class="badge badge-pill badge-warning" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">
                  <?= htmlspecialchars($row['main_category_name']) ?>
                </span>
              </td>
              <td>
                <div class="action-btn-group justify-content-center">
                  <button class="btn-action btn-action-edit" title="Edit" onclick="openEditSubCategory(
                    '<?= $row['id'] ?>',
                    '<?= addslashes($row['sub_category_name']) ?>',
                    '<?= addslashes($row['slug']) ?>',
                    '<?= $row['main_category_id'] ?>',
                    '<?= addslashes($row['icon']) ?>'
                  )">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn-action btn-action-delete" title="Delete" onclick="deleteSubCategory('<?= $row['id'] ?>')">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
            <?php
          }
        } else {
          echo "<tr><td colspan='6' class='text-center py-5 text-muted'>No Sub Categories found</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php include_once "modals/addSubCategory.php"; ?>
<?php include_once "modals/editSubCategory.php"; ?>
<script>
function openAddSubCategory() {
  $("#addSubCategoryModal").modal("show");
}

$(document).off("submit", "#addSubCategoryForm").on("submit", "#addSubCategoryForm", function(e){
  e.preventDefault();
  const btn = $(this).find('button[type="submit"]');
  btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...');

  $.post("controller/subCategoryController.php", $(this).serialize()+"&action=add", function(res){
    btn.prop('disabled', false).text('Save');
    if(res.trim()=="success"){
      showToast("Sub Category Added!", "success");
      $("#addSubCategoryModal").modal("hide");
      showSubCategory();   // ✅ refresh list
    } else if(res.trim()=="empty"){
      showToast("Please fill all fields!", "warning");
    } else {
      showToast("Error: " + res, "danger");
    }
  });
});

function openEditSubCategory(id, name, slug, mainCatId, icon){
  $("#edit_sc_id").val(id);
  $("#edit_sc_name").val(name);
  $("#edit_sc_slug").val(slug);
  $("#edit_sc_main_cat").val(mainCatId);
  $("#edit_sc_icon").val(icon);  // ✅ set icon in dropdown
  $("#editSubCategoryModal").modal("show");
}

$(document).off("submit", "#editSubCategoryForm").on("submit", "#editSubCategoryForm", function(e){
  e.preventDefault();
  $.post("controller/subCategoryController.php", $(this).serialize()+"&action=edit", function(res){
    if(res.trim()=="success"){
      showToast("Sub Category Updated!", "success");
      $("#editSubCategoryModal").modal("hide");
      showSubCategory();
    } else {
      showToast("Error: " + res, "danger");
    }
  });
});

function deleteSubCategory(id){
  showConfirm(
    "Delete Sub Category?",
    "Are you sure you want to delete this Sub Category? This action cannot be undone.",
    function() {
      $.post("controller/subCategoryController.php", {id:id, action:"delete"}, function(res){
        if(res.trim()=="success"){
          showToast("Sub Category Deleted!", "danger");
          const params = new URLSearchParams(window.location.hash.split('?')[1]);
          const page = params.get('page') || 1;
          loadModule('sub-categories', page);
        } else {
          showToast("Error: " + res, "danger");
        }
      });
    }
  );
}
</script>
