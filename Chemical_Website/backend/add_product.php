<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /chemical_website/admin_login");
    exit();
}
?>

<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $appearance = $_POST['appearance'] ?? '';
    $formula = $_POST['formula'] ?? '';
    $application = $_POST['application'] ?? '';
    $packaging_details = $_POST['packaging_details'] ?? '';
    $storage_info = $_POST['storage_info'] ?? '';
    $molecular_weight = $_POST['molecular_weight'] ?? '';
    $ph = $_POST['ph'] ?? '';
    $specific_gravity = $_POST['specific_gravity'] ?? '';

    // Correct column name for "formula"
    $sql = "INSERT INTO products (product_name, category, description, molecular_weight, ph, specific_gravity, appearance, formula, application, packaging_details, storage_info)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $product_name, $category, $description, $molecular_weight, $ph, $specific_gravity, $appearance, $formula, $application, $packaging_details, $storage_info);

    if ($stmt->execute()) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Add Product</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #2a2a2a, #1f1f1f);
            color: #ffffff;
            padding: 0;
            margin: 0;
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
            margin: auto;
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
            /* box-shadow: 0px 3px 6px rgba(244, 224, 77, 0.5); */
        }
    </style>
</head>

<body>
    <?php
    include 'side_bar.php';
    ?>

    <h2>Add Product</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Product Name: <input type="text" name="product_name" required></label><br>
        <label>Chemical Formula: <input type="text" name="formula"></label><br>
        <label>Molecular Weight: <input type="text" name="molecular_weight"></label><br>
        <label>Appearance: <input type="text" name="appearance"></label><br>
        <label>pH: <input type="text" name="ph"></label><br>
        <label>Specific Gravity: <input type="text" name="specific_gravity"></label><br>
        <label>Category: <input type="text" name="category"></label><br>
        <label>Description: <textarea name="description" required></textarea></label><br>
        <label>Applications/Uses: <textarea name="application"></textarea></label><br>
        <label>Packaging Details: <textarea name="packaging_details"></textarea></label><br>
        <!-- <label>Concentration: <input type="text" name="concentration"></label><br> -->
        <label>Storage Condition: <textarea name="storage_info"></textarea></label><br>
        <!-- <label>Safety Information: <textarea name="safety_info"></textarea></label><br> -->
        <button type="submit">Add Product</button>
    </form>
</body>

</html>