function showProductItems() {
  $.ajax({
    url: "./adminView/viewAllProducts.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}
function showCategory() {
  $.ajax({
    url: "./adminView/viewCategories.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}
function showSizes() {
  $.ajax({
    url: "./adminView/viewSizes.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}
function showProductSizes() {
  $.ajax({
    url: "./adminView/viewProductSizes.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}

function showCustomers() {
  $.ajax({
    url: "./adminView/viewCustomers.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}

function showOrders() {
  $.ajax({
    url: "./adminView/viewAllOrders.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}
function ShowMassage() {
  console.log("Messages link clicked!"); // Check if function is being triggered
  $.ajax({
    url: "./adminView/viewMassages.php",
    method: "POST",
    data: { record: 1 },
    success: function (data) {
      console.log("Data received:", data);
      $(".allContent-section").html(data); // Inject the response data into the section
    },
    error: function (xhr, status, error) {
      console.log("Error:", error); // Log any error encountered
    },
  });
}

function ChangeOrderStatus(id) {
  $.ajax({
    url: "./controller/updateOrderStatus.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}

function ChangePay(id) {
  $.ajax({
    url: "./controller/updatePayStatus.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      showToast("Payment Status updated successfully", "success");
      $("form").trigger("reset");
      showOrders();
    },
  });
}

//add product data
function addItems() {
  var p_name = $("#p_name").val();
  var p_desc = $("#p_desc").val();
  var p_price = $("#p_price").val();
  var category = $("#category").val();
  var sub_category = $("#sub_category").val();
  var brand = $("#brand").val();
  var sku = $("#sku").val();
  var sizes = $("#sizes").val();
  var stock = $("#stock").val();

  var file = $("#file")[0].files[0];

  var fd = new FormData();
  fd.append("p_name", p_name);
  fd.append("p_desc", p_desc);
  fd.append("p_price", p_price);
  fd.append("category", category);
  fd.append("sub_category", sub_category);
  fd.append("brand", brand);
  fd.append("sku", sku);
  fd.append("sizes", sizes);
  fd.append("stock", stock);
  fd.append("file", file);
  fd.append("upload", 1);

  $.ajax({
    url: "./controller/addItemController.php",
    method: "post",
    data: fd,
    processData: false,
    contentType: false,
    success: function (data) {
      showToast("Product Added successfully.", "success");
      $("form").trigger("reset");
      showProductItems();
    },
  });
}
//edit product data
function itemEditForm(id) {
  $.ajax({
    url: "./adminView/editItemForm.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      $("#editProductModalBody").html(data);
      $("#editProductModal").modal("show");
    },
  });
}

//update product after submit
function updateItems() {
  var product_id = $("#product_id").val();
  var p_name = $("#p_name").val();
  var p_desc = $("#p_desc").val();
  var p_price = $("#p_price").val();
  var category = $("#category").val();
  var sub_category = $("#sub_category").val();
  var brand = $("#brand").val();
  var sku = $("#sku").val();
  var sizes = $("#sizes").val();
  var stock = $("#stock").val();

  var existingImage = $("#existingImage").val();
  var newImage = $("#newImage")[0].files[0];

  var fd = new FormData();
  fd.append("product_id", product_id);
  fd.append("p_name", p_name);
  fd.append("p_desc", p_desc);
  fd.append("p_price", p_price);
  fd.append("category", category);
  fd.append("sub_category", sub_category);
  fd.append("brand", brand);
  fd.append("sku", sku);
  fd.append("sizes", sizes);
  fd.append("stock", stock);
  fd.append("existingImage", existingImage);
  fd.append("newImage", newImage);

  $.ajax({
    url: "./controller/updateItemController.php",
    method: "post",
    data: fd,
    processData: false,
    contentType: false,
    success: function (data) {
      showToast("Data Update Success.", "success");
      $("#editProductModal").modal("hide");
      // Keep on current page
      const params = new URLSearchParams(window.location.hash.split('?')[1]);
      const page = params.get('page') || 1;
      loadModule('new-arrivals', page);
    },
  });
}
//delete product data
function itemDelete(id) {
  $.ajax({
    url: "./controller/deleteItemController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      showToast("Item Successfully deleted", "danger");
      // Keep on current page
      const params = new URLSearchParams(window.location.hash.split('?')[1]);
      const page = params.get('page') || 1;
      loadModule('new-arrivals', page);
    },
  });
}

//delete cart data
function cartDelete(id) {
  $.ajax({
    url: "./controller/deleteCartController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      showToast("Cart Item Successfully deleted", "danger");
      $("form").trigger("reset");
      showMyCart();
    },
  });
}

function eachDetailsForm(id) {
  $.ajax({
    url: "./view/viewEachDetails.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}

//delete category data
function categoryDelete(id) {
  $.ajax({
    url: "./controller/catDeleteController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      showToast("Category Successfully deleted", "danger");
      // Keep on current page
      const params = new URLSearchParams(window.location.hash.split('?')[1]);
      const page = params.get('page') || 1;
      loadModule('categories', page);
    },
  });
}

//delete size data
function sizeDelete(id) {
  $.ajax({
    url: "./controller/deleteSizeController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      showToast("Size Successfully deleted", "danger");
      // Keep on current page
      const params = new URLSearchParams(window.location.hash.split('?')[1]);
      const page = params.get('page') || 1;
      loadModule('sizes', page);
    },
  });
}

//delete variation data
function variationDelete(id) {
  $.ajax({
    url: "./controller/deleteVariationController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      alert("Successfully deleted");
      $("form").trigger("reset");
      showProductSizes();
    },
  });
}

//edit variation data
function variationEditForm(id) {
  $.ajax({
    url: "./adminView/editVariationForm.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}

//update variation after submit
function updateVariations() {
  var v_id = $("#v_id").val();
  var product = $("#product").val();
  var size = $("#size").val();
  var qty = $("#qty").val();
  var fd = new FormData();
  fd.append("v_id", v_id);
  fd.append("product", product);
  fd.append("size", size);
  fd.append("qty", qty);

  $.ajax({
    url: "./controller/updateVariationController.php",
    method: "post",
    data: fd,
    processData: false,
    contentType: false,
    success: function (data) {
      alert("Update Success.");
      $("form").trigger("reset");
      showProductSizes();
    },
  });
}
function search(id) {
  $.ajax({
    url: "./controller/searchController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      $(".eachCategoryProducts").html(data);
    },
  });
}

function quantityPlus(id) {
  $.ajax({
    url: "./controller/addQuantityController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      $("form").trigger("reset");
      showMyCart();
    },
  });
}
function quantityMinus(id) {
  $.ajax({
    url: "./controller/subQuantityController.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      $("form").trigger("reset");
      showMyCart();
    },
  });
}

function checkout() {
  $.ajax({
    url: "./view/viewCheckout.php",
    method: "post",
    data: { record: 1 },
    success: function (data) {
      $(".allContent-section").html(data);
    },
  });
}

function removeFromWish(id) {
  $.ajax({
    url: "./controller/removeFromWishlist.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      alert("Removed from wishlist");
    },
  });
}

function addToWish(id) {
  $.ajax({
    url: "./controller/addToWishlist.php",
    method: "post",
    data: { record: id },
    success: function (data) {
      alert("Added to wishlist");
    },
  });
}
