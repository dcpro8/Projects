<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: /chemical_website/admin_login");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link your external CSS -->

    <style>

        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #2a2a2a;
            color: #ffffff;
            font-family: 'Segoe UI', sans-serif;
        }

        /* Center Heading */
        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #f4e04d;
            margin-top: 20px;
        }

        /* Table Styling */
        table {
            width: 65%;
            margin: 30px auto;
            border-collapse: collapse;
            color: #ffffff;
        }

        thead {
            background-color: #f4e04d;
            color: #121212;
        }

        td,
        th {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background-color: #444;
        }

        /* Delete Buttons */
        a {
            color: #f4e04d;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Delete All Button Styling */
        .delete-all-container {
            text-align: center;
            margin-top: 30px;
        }

        button {
            background-color: #f4e04d;
            color: #121212;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: white;
            box-shadow: 0px 0px 12px 5px rgb(68, 68, 68);
        }
    </style>

</head>

<body>
    <?php
    include 'side_bar.php';
    ?>

    <h2>Delete Product</h2>

    <!-- Product Table -->
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require 'db_connection.php'; // Database connection

            $query = "SELECT id, product_name FROM products";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['product_name']) . "</td>
                            <td>
                                <a href='/chemical_website/delete-product?delete_id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No products available</td></tr>";
            }

            $stmt->close();
            ?>
        </tbody>
    </table>

    <!-- Delete All Products Button -->
    <div class="delete-all-container">
        <form method="POST">
            <button type="submit" name="delete_all" onclick="return confirm('Are you sure you want to delete all products?')">Delete All Products</button>
        </form>
    </div>

    <?php
    // Delete individual product securely
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);
        $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            echo "<script>alert('Product deleted successfully.'); window.location.href='/chemical_website/delete-product';</script>";
        } else {
            echo "<script>alert('Failed to delete product.'); window.location.href='/chemical_website/delete-product';</script>";
        }
        $delete_stmt->close();
    }

    // Delete all products securely
    if (isset($_POST['delete_all'])) {
        $delete_all_stmt = $conn->prepare("DELETE FROM products");

        if ($delete_all_stmt->execute()) {
            echo "<script>alert('All products deleted successfully.'); window.location.href='/chemical_website/delete-product';</script>";
        } else {
            echo "<script>alert('Failed to delete all products.'); window.location.href='/chemical_website/delete-product';</script>";
        }
        $delete_all_stmt->close();
    }

    $conn->close();
    ?>
</body>

</html>