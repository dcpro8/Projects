<?php
session_start();
require 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch current site settings
$sql = "SELECT * FROM home_page_settings WHERE id = 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update company name and slider images
    $company_name = $_POST['company_name'];
    $upload_dir = "../uploads/";
    $slider_images = [
        'slider_image1' => $settings['slider_image1'],
        'slider_image2' => $settings['slider_image2'],
        'slider_image3' => $settings['slider_image3'],
    ];

    foreach ($slider_images as $key => &$image) {
        if (!empty($_FILES[$key]['name'])) {
            $file_name = basename($_FILES[$key]['name']);
            $target_path = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES[$key]['tmp_name'], $target_path)) {
                $image = "uploads/" . $file_name;
            }
        }
    }

    // Update site settings in the database
    $sql_update = "UPDATE home_page_settings SET company_name=?, slider_image1=?, slider_image2=?, slider_image3=? WHERE id=1";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssss", $company_name, $slider_images['slider_image1'], $slider_images['slider_image2'], $slider_images['slider_image3']);
    $stmt->execute();

    // Redirect to prevent form resubmission
    header("Location: /chemical_website/slider_and_name");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Name and Slider Images</title>
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
            margin-top: 60px;
        }

        form {
            max-width: 700px;
            background-color: #1c1c1c;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
            margin: auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
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
            <div class="form-group">
                <label>Company Name:</label>
                <input type="text" name="company_name" value="<?= htmlspecialchars($settings['company_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Slider Image 1:</label>
                <input type="file" name="slider_image1">
            </div>
            <div class="form-group">
                <label>Slider Image 2:</label>
                <input type="file" name="slider_image2">
            </div>
            <div class="form-group">
                <label>Slider Image 3:</label>
                <input type="file" name="slider_image3">
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>