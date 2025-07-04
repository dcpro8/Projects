<?php
session_start();
require 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Extend session duration (prevent session timeout)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // 1800 seconds = 30 minutes inactivity
    session_unset();
    session_destroy();
    header("Location: /chemical_website/admin_login");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update activity time

// Fetch heading from settings table
$settingsQuery = "SELECT * FROM settings WHERE id = 1";
$settingsResult = mysqli_query($conn, $settingsQuery);
$settingsRow = mysqli_fetch_assoc($settingsResult);
$heading = $settingsRow['heading'];

// Fetch all products
$query = "SELECT * FROM product_display";
$result = mysqli_query($conn, $query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_all'])) {
    $newHeading = mysqli_real_escape_string($conn, $_POST['heading']);

    // Update heading in settings table
    $updateHeadingQuery = "UPDATE settings SET heading = '$newHeading' WHERE id = 1";
    mysqli_query($conn, $updateHeadingQuery);

    foreach ($_POST['id'] as $index => $id) {
        $id = intval($id);
        $title = mysqli_real_escape_string($conn, $_POST['title'][$index]);
        $description = mysqli_real_escape_string($conn, $_POST['description'][$index]);

        // Handle image upload
        if (!empty($_FILES['image']['name'][$index])) {
            $target_dir = "../uploads/";
            $file_name = basename($_FILES["image"]["name"][$index]);
            $target_file = $target_dir . $file_name;
            $image_path = "uploads/" . $file_name;

            if (move_uploaded_file($_FILES["image"]["tmp_name"][$index], $target_file)) {
                $updateQuery = "UPDATE product_display SET title='$title', description='$description', image='$image_path' WHERE id=$id";
            } else {
                echo "<p style='color: red;'>Error uploading file for product ID $id.</p>";
                continue;
            }
        } else {
            $updateQuery = "UPDATE product_display SET title='$title', description='$description' WHERE id=$id";
        }

        mysqli_query($conn, $updateQuery);
    }

    // Prevent form resubmission & reload page
    echo "<script>alert('Heading and products updated successfully.');</script>";
    echo "<script>window.location.href='/chemical_website/our_products';</script>";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Products & Heading</title>
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

        .container {
            max-width: 800px;
            margin: auto;
        }

        form {
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

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

        .image-preview {
            display: block;
            margin-top: 10px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <?php include 'side_bar.php' ?>

    <h2>Admin Panel - Edit Heading & Products</h2>

    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <!-- Edit Heading -->
            <label>Heading:</label>
            <input type="text" name="heading" value="<?php echo htmlspecialchars($heading); ?>" required>

            <hr>

            <!-- Edit Products -->
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">

                <label>Title:</label>
                <input type="text" name="title[]" value="<?php echo htmlspecialchars($row['title']); ?>" required>

                <label>Description:</label>
                <textarea name="description[]" required><?php echo htmlspecialchars($row['description']); ?></textarea>

                <label>Current Image:</label>
                <img src="../chemical_website/<?php echo htmlspecialchars($row['image']); ?>" class="image-preview" width="100">

                <label>Upload New Image:</label>
                <input type="file" name="image[]">

                <hr> <!-- Separator for each product -->
            <?php } ?>

            <button type="submit" name="update_all">Update Heading & Products</button>
        </form>
    </div>

</body>

</html>