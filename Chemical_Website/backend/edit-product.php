<?php
    session_start();
    require 'db_connection.php'
?> 

<?php

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch all products for the dropdown
$product_list_query = "SELECT id, product_name FROM products";
$product_list_result = $conn->query($product_list_query);

$product = null;
$product_id = $_POST['selected_product'] ?? null;

// Load product details when requested
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['load_product'])) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

// Update product details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $name = $_POST['product_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $molecular_weight = $_POST['molecular_weight'] ?? '';
    $specific_gravity = $_POST['specific_gravity'] ?? '';
    $ph = $_POST['ph'] ?? '';
    $appearance = $_POST['appearance'] ?? '';
    $formula = $_POST['formula'] ?? '';
    $application = $_POST['application'] ?? '';
    $packaging_details = $_POST['packaging_details'] ?? '';
    $storage_info = $_POST['storage_info'] ?? '';
    $product_id = $_POST['id'] ?? 0; // Ensure product_id is captured

    // Update query
    $update_query = "UPDATE products SET product_name=?, category=?, description=?, molecular_weight=?, specific_gravity=?, ph=?, appearance=?, formula=?, application=?, packaging_details=?, storage_info=? WHERE id=?";
    $stmt = $conn->prepare($update_query);

    // Check for statement preparation errors
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssi", $name, $category, $description, $molecular_weight, $specific_gravity, $ph, $appearance, $formula, $application, $packaging_details, $storage_info, $product_id);

    if ($stmt->execute()) {
        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2a2a2a;
            color: #ffffff;
            padding: 0;
            margin: 0;
        }

        select {
            width: 100%;
            padding: 10px;
            background-color: #2b2b2b;
            color: white;
            border: 2px solid #f4e04d;
            border-radius: 8px;
            font-size: 14px;
        }

        select option {
            background-color: #2b2b2b;
            color: #ffffff;
        }

        h2 {
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            color: #f4e04d;
            text-align: center;
            margin-bottom: 30px;
        }

        /* Form Container */
        form {
            max-width: 600px;
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
            margin: 20px auto 0 auto;
        }

        /* Labels and Input Fields */
        label {
            display: block;
            margin: 12px 0;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            background-color: #2b2b2b;
            border: 2px solid #f4e04d;
            border-radius: 8px;
            color: #ffffff;
            margin-top: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        button[name="load_product"] {
            display: block;
            margin: 10px auto;
            background-color: #f4e04d;
            color: #121212;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            text-transform: uppercase;
            cursor: pointer;
        }

        /* Button Styling */
        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #f4e04d;
            color: #121212;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: white;
            box-shadow: 0px 0px 12px 5px rgb(68, 68, 68);
        }
    </style>
    </style>
</head>

<body>
    <?php
    include 'side_bar.php';
    ?>

    <h2>Edit Product</h2>

    <!-- Dropdown to Select a Product -->
    <form method="POST">
        <label>Select a Chemical to Edit:<br><br>
            <select name="selected_product" required>
                <option value="">-- Select Product --</option>
                <?php
                if ($product_list_result->num_rows > 0) {
                    while ($row = $product_list_result->fetch_assoc()) {
                        $selected = ($row['id'] == $product_id) ? "selected" : "";
                        echo "<option value='{$row['id']}' $selected>{$row['product_name']}</option>";
                    }
                }
                ?>
            </select>
        </label>
        <button type="submit" name="load_product">Load Product</button>
    </form>

    <?php if ($product): ?>
        <!-- Edit Form with Pre-filled Values -->
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($product_id) ?>">

            <label>Product Name: <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required></label>
            <label>Chemical Formula: <input type="text" name="formula" value="<?= htmlspecialchars($product['formula']) ?>"></label>
            <label>Molecular Weight: <input type="text" name="molecular_weight" value="<?= htmlspecialchars($product['molecular_weight']) ?>"></label>
            <label>Appearance: <input type="text" name="appearance" value="<?= htmlspecialchars($product['appearance']) ?>"></label>
            <label>pH: <input type="text" name="ph" value="<?= htmlspecialchars($product['ph']) ?>"></label>
            <label>Specific Gravity: <input type="text" name="specific_gravity" value="<?= htmlspecialchars($product['specific_gravity']) ?>"></label>
            <label>Category: <input type="text" name="category" value="<?= htmlspecialchars($product['category']) ?>"></label>
            <label>Description: <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea></label>
            <label>Application: <textarea name="application"><?= htmlspecialchars($product['application']) ?></textarea></label>
            <label>Packaging Details: <textarea name="packaging_details"><?= htmlspecialchars($product['packaging_details']) ?></textarea></label>
            <label>Storage Info: <textarea name="storage_info"><?= htmlspecialchars($product['storage_info']) ?></textarea></label>

            <button type="submit" name="update_product">Update Product</button>
        </form>
    <?php endif; ?>


</body>

</html>