<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

// Fetch Personnel Data
$sql_personnel = "SELECT * FROM personnel ORDER BY id ASC";
$result_personnel = $conn->query($sql_personnel);

// Update Personnel Data (Only When Form is Submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['personnel'])) {
    foreach ($_POST['personnel'] as $id => $data) {
        $name = $conn->real_escape_string($data['name']);
        $description = $conn->real_escape_string($data['description']);

        // Get existing image URL
        $imageUrl = $_POST["existing_image_$id"];

        // Check if a new image is uploaded
        if (!empty($_FILES["image"]["name"][$id])) {
            $upload_dir = __DIR__ . "/../uploads/"; // Correct upload path
            $fileName = basename($_FILES["image"]["name"][$id]);
            $targetFilePath = $upload_dir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            $allowTypes = ["jpg", "jpeg", "png", "gif"];

            // Validate file type
            if (in_array(strtolower($fileType), $allowTypes)) {
                // Move uploaded file
                if (move_uploaded_file($_FILES["image"]["tmp_name"][$id], $targetFilePath)) {
                    $imageUrl = "uploads/" . $fileName; // Save relative path in DB
                } else {
                    echo "<p style='color:red;'>Error uploading file.</p>";
                }
            } else {
                echo "<p style='color:red;'>Invalid file type! Only JPG, JPEG, PNG, GIF allowed.</p>";
            }
        }

        // Update query
        $sql_personnel_update = "UPDATE personnel SET name=?, description=?, image_url=? WHERE id=?";
        $stmt_personnel = $conn->prepare($sql_personnel_update);
        $stmt_personnel->bind_param("sssi", $name, $description, $imageUrl, $id);
        $stmt_personnel->execute();
    }

    header("Location: personnel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Management</title>
    <style>
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
            margin: auto;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
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

    <form method="POST" enctype="multipart/form-data">
        <h3>Highly Experienced Personnel</h3>
        <?php while ($person = $result_personnel->fetch_assoc()): ?>
            <fieldset>
                <legend>Edit Personnel: <?= htmlspecialchars($person['name']) ?></legend>

                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" name="personnel[<?= $person['id'] ?>][name]" value="<?= htmlspecialchars($person['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="personnel[<?= $person['id'] ?>][description]" required><?= htmlspecialchars($person['description']) ?></textarea>
                </div>

                <!-- Display Current Image -->
                

                <div class="form-group">
                    <label>Upload New Image:</label>
                    <input type="file" name="image[<?= $person['id'] ?>]">
                </div>

                <!-- Keep Existing Image -->
                <input type="hidden" name="existing_image_<?= $person['id'] ?>" value="<?= htmlspecialchars($person['image_url']) ?>">

            </fieldset>
        <?php endwhile; ?>

        <button type="submit">Update</button>
    </form>


</body>

</html>