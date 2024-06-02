<?php
include 'topnav.php';
include 'nav.php';
include_once 'includes/config.php';
include_once 'includes/process_addproduct.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch supplier brands and default categories
$suppliers_sql = "SELECT sup_brand FROM suppliers";
$suppliers_result = $conn->query($suppliers_sql);

$categories_sql = "SELECT cat_name FROM categories";
$categories_result = $conn->query($categories_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="css/dashboard.css">
<link rel="stylesheet" href="css/addproduct.css">
<title>Add Product</title>

<script>
    function validateForm() {
        var productName = document.forms["productForm"]["product_name"].value.trim();
        var size = document.forms["productForm"]["size"].value.trim();
        var quantity = document.forms["productForm"]["quantity"].value.trim();
        var price = document.forms["productForm"]["price"].value.trim();

        var productNameRegex = /^[a-zA-Z0-9\s]+$/;
        var sizeRegex = /^\d{1,3}$/;
        var quantityRegex = /^\d{1,3}$/;
        var priceRegex = /^₱?\d{1,6}(\.\d{1,2})?$/;

        if (!productNameRegex.test(productName)) {
            alert("Product Name can only contain letters and numbers.");
            return false;
        }
        if (!sizeRegex.test(size)) {
            alert("Size must be an integer with a maximum of 3 digits.");
            return false;
        }
        if (!quantityRegex.test(quantity)) {
            alert("Quantity must be an integer with a maximum of 3 digits.");
            return false;
        }
        if (!priceRegex.test(price)) {
            alert("Price must be a number with a maximum of 6 digits and up to 2 decimal places, including a pesos sign.");
            return false;
        }

        return true;
    }

    function formatPriceInput(event) {
        var input = event.target;
        var value = input.value.replace(/[^0-9.]/g, '');
        if (value) {
            input.value = '₱' + parseFloat(value).toFixed(2);
        } else {
            input.value = '';
        }
    }
</script>
</head>

<body>
<div class="container">
    <form name="productForm" action="addproduct.php" method="post" onsubmit="return validateForm()">
    <h4>Add New Product</h4>
        <div class="row1">
            <div class="input-box">
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>
            </div>
            <div class="input-box">
                <label for="size">Size</label>
                <input type="text" id="size" name="size" placeholder="Enter size" required>
            </div>
        </div>
        <div class="row2">
            <div class="input-box">
                <label for="quantity">Quantity</label>
                <input type="text" id="quantity" name="quantity" placeholder="Enter Quantity" required>
            </div>
            <div class="input-box">
                <label for="category">Category</label>
                <div class="column">
                    <div class="select-box">
                        <select id="category" name="category" required>
                            <option value="" disabled selected>Select a category</option>
                            <?php
                            if ($categories_result->num_rows > 0) {
                                while($row = $categories_result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['cat_name']) . "'>" . htmlspecialchars($row['cat_name']) . "</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No categories available</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row3">
            <div class="input-box">
                <label for="price">Price</label>
                <input type="text" id="price" name="price" placeholder="Enter Price" required oninput="formatPriceInput(event)">
            </div>
            <div class="input-box">
                <label for="brand_name">Brand Name</label>
                <div class="column">
                    <div class="select-box">
                        <select id="brand_name" name="brand_name" required>
                            <option value="" disabled selected>Select a brand</option>
                            <?php
                            if ($suppliers_result->num_rows > 0) {
                                while($row = $suppliers_result->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['sup_brand']) . "'>" . htmlspecialchars($row['sup_brand']) . "</option>";
                                }
                            } else {
                                echo "<option value='' disabled>No brands available</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>

<?php
$conn->close();
?>
