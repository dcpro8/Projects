<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch section image data for section_id = 1
$sql_image = "SELECT * FROM section_images WHERE section_id = 1 LIMIT 1";
$result_image = $conn->query($sql_image);
$section_image = $result_image->fetch_assoc() ?: [
    'image_url' => 'images/default.jpg',
    'title' => 'Default Title',
    'description' => 'Default description text.'
];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = $_POST['section_title'] ?? $section_image['title'];
    $new_description = $_POST['section_description'] ?? $section_image['description'];
    $new_image_url = $section_image['image_url'];

    if (!empty($_FILES['section_image']['name'])) {
        $upload_dir = "uploads/"; // Ensure this directory exists
        $image_name = basename($_FILES['section_image']['name']);
        $target_image = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['section_image']['tmp_name'], $target_image)) {
            $new_image_url = $target_image;
        }
    }

    $sql_update_image = "UPDATE section_images SET title=?, description=?, image_url=? WHERE section_id=1";
    $stmt_image = $conn->prepare($sql_update_image);
    $stmt_image->bind_param("sss", $new_title, $new_description, $new_image_url);
    $stmt_image->execute();

    // Fetch updated data again to reflect changes in the form
    $sql_image = "SELECT * FROM section_images WHERE section_id = 1 LIMIT 1";
    $result_image = $conn->query($sql_image);
    $section_image = $result_image->fetch_assoc();

    // Redirect to prevent form resubmission
    header("Location: /chemical_website/section_2");
    exit;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section 2</title>
    <style>
        html,
        body {
            padding: 0;
            margin: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .container {
            padding: 20px;
        }

        form {
            max-width: 700px;
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
            margin: auto;
        }

        h3 {
            text-align: center;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        fieldset {
            border: 1px solid #f4e04d;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        legend {
            font-weight: bold;
            color: #f4e04d;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
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
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
            resize: none;
        }

        button[type="submit"] {
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
            transition: all 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: white;
            box-shadow: 0px 0px 12px 5px rgb(68, 68, 68);
        }
    </style>
</head>

<body>
    <?php include 'side_bar.php'; ?>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <h3>Section 2</h3>
            <div class="form-group">
                <label>New Image:</label>
                <input type="file" name="section_image">
            </div>

            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="section_title" value="<?= htmlspecialchars($section_image['title']) ?>" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="section_description" required><?= htmlspecialchars($section_image['description']) ?></textarea>
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>