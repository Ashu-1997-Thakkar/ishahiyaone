<form action="../controller/savePricing.php" method="POST">
    <h4>Add New Pricing</h4>

    <label>Main Category:</label>
    <select id="main_category" name="main_category" class="form-control" required onchange="loadSubCategories()">
        <option value="">Select Main Category</option>
        <option value="bulk-sms-services">Bulk SMS Services</option>
        <option value="cattle-feed">Cattle Feed</option>
    </select>

    <label>Subcategory:</label>
    <select id="sub_category" name="subcategory" class="form-control" required>
        <option value="">Select Subcategory</option>
    </select>

    <label>Package:</label>
    <input type="text" name="package" class="form-control" required>

    <label>Promotional Price:</label>
    <input type="number" name="promotional_price" class="form-control">

    <label>Promotional Paise:</label>
    <input type="number" name="promotional_paise" class="form-control">

    <label>Transactional Price:</label>
    <input type="number" name="transactional_price" class="form-control">

    <label>Transactional Paise:</label>
    <input type="number" name="transactional_paise" class="form-control">

    <label>Voice Price:</label>
    <input type="number" name="voice_price" class="form-control">

    <label>Voice Paise:</label>
    <input type="number" name="voice_paise" class="form-control">

    <button class="btn btn-success mt-3">Save</button>
</form>

<script>
function loadSubCategories() {
    let main = document.getElementById("main_category").value;
    let sub = document.getElementById("sub_category");
    sub.innerHTML = "";

    let options = [];

    if(main === "bulk-sms-services"){
        options = [
            "Promotional Bulk SMS Services",
            "Transactional Bulk SMS Services",
            "Long Code Services",
            "Short Code Services",
            "Missed Call Alert Services",
            "Voice Call Services"
        ];
    }

    if(main === "cattle-feed"){
        options = [
            "Dairy Cattle Feed",
            "Buffalo Feed",
            "Calf Starter",
            "Milk Booster"
        ];
    }

    options.forEach(function(val){
        let opt = document.createElement("option");
        opt.value = val;
        opt.text = val;
        sub.appendChild(opt);
    });
}
</script>
