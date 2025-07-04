<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch "About Us" data (already exists)
$id = 1;
$sql_about = "SELECT * FROM about_us WHERE id=?";
$stmt_about = $conn->prepare($sql_about);
$stmt_about->bind_param("i", $id);
$stmt_about->execute();
$result_about = $stmt_about->get_result();
$about = $result_about->fetch_assoc();
$stmt_about->close();

// Handle "About Us" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_about'])) {
    $title = $_POST['title'];
    $headline = $_POST['headline'];
    $description = $_POST['description'];
    $image = $_POST['existing_image'];

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetDir = __DIR__ . "../uploads/";
        $targetFilePath = $targetDir . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $image = "uploads/" . $imageName;
        }
    }

    // Update database
    $update_about_sql = "UPDATE about_us SET title=?, image=?, headline=?, description=? WHERE id=?";
    $stmt_about = $conn->prepare($update_about_sql);
    $stmt_about->bind_param("ssssi", $title, $image, $headline, $description, $id);

    if ($stmt_about->execute()) {
        header("Location: /chemical_website/about_us");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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

<?php include 'side_bar.php' ?>

    <div class="container">

        <h2>Edit About Page</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($about['image'] ?? ''); ?>">
            <input type="hidden" name="update_about" value="1">

            <div class="form-group">
                <label>Title:</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($about['title'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Current Image:</label><br>
                <?php if (!empty($about['image'])) { ?>
                    <img src="<?php echo htmlspecialchars($about['image']); ?>" alt="Current Image" width="200px">
                <?php } else {
                    echo "<p>No image found.</p>";
                } ?>
            </div>

            <div class="form-group">
                <label>Upload New Image:</label>
                <input type="file" name="image">
            </div>

            <div class="form-group">
                <label>Headline:</label>
                <input type="text" name="headline" value="<?php echo htmlspecialchars($about['headline'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Description:</label>
                <textarea name="description" required><?php echo htmlspecialchars($about['description'] ?? ''); ?></textarea>
            </div>

            <button type="submit">Update About Us</button>
        </form>
    </div>
</body>

</html>