// Open Add Modal
function openAddMainCategory() {
  $("#addMainCategoryModal").modal("show");
}

// Add Main Category
$("#addMainCategoryForm").submit(function(e){
  e.preventDefault();
  $.post("controller/mainCategoryController.php", $(this).serialize()+"&action=add", function(res){
    if(res=="success"){
      showToast("Main Category Added!", "success");
      $("#addMainCategoryModal").modal("hide");
      showMainCategory();
    }else{
      showToast("Error while adding!", "danger");
    }
  });
});

// Open Edit Modal
function openEditMainCategory(id, name, slug){
  $("#edit_id").val(id);
  $("#edit_name").val(name);
  $("#edit_slug").val(slug);
  $("#editMainCategoryModal").modal("show");
}

// Update Main Category
$("#editMainCategoryForm").submit(function(e){
  e.preventDefault();
  $.post("controller/mainCategoryController.php", $(this).serialize()+"&action=edit", function(res){
    if(res=="success"){
      showToast("Main Category Updated!", "success");
      $("#editMainCategoryModal").modal("hide");
      showMainCategory();
    }else{
      showToast("Error while updating!", "danger");
    }
  });
});

// Delete Main Category
function deleteMainCategory(id){
  showConfirm(
    "Delete Main Category?",
    "Are you sure you want to delete this? This action cannot be undone.",
    function() {
      $.post("controller/mainCategoryController.php", {id:id, action:"delete"}, function(res){
        if(res=="success"){
          showToast("Main Category Deleted!", "danger");
          showMainCategory();
        }else{
          showToast("Error while deleting!", "danger");
        }
      });
    }
  );
}
