<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch feature sections
$sql_features = "SELECT * FROM feature_sections ORDER BY id ASC";
$result_features = $conn->query($sql_features);

// Update feature sections
if (isset($_POST['features']) && is_array($_POST['features'])) {
    foreach ($_POST['features'] as $id => $feature) {
        $sql_update_feature = "UPDATE feature_sections SET letter=?, icon_class=?, title1=?, description=? WHERE id=?";
        $stmt_feature = $conn->prepare($sql_update_feature);
        $stmt_feature->bind_param("ssssi", $feature['letter'], $feature['icon_class'], $feature['title1'], $feature['description'], $id);
        $stmt_feature->execute();
    }
    // Redirect to prevent form resubmission
    header("Location: /chemical_website/section_3");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section 3</title>
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
            <h3>Section 3</h3>
            <?php while ($feature = $result_features->fetch_assoc()): ?>
                <fieldset>
                    <div class="form-group">
                        <label>Letter:</label>
                        <input type="text" name="features[<?= $feature['id'] ?>][letter]" value="<?= htmlspecialchars($feature['letter']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Icon Class:</label>
                        <input type="text" name="features[<?= $feature['id'] ?>][icon_class]" value="<?= htmlspecialchars($feature['icon_class']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Title:</label>
                        <textarea name="features[<?= $feature['id'] ?>][title1]" required><?= htmlspecialchars($feature['title1']) ?></textarea>

                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="features[<?= $feature['id'] ?>][description]" required><?= htmlspecialchars($feature['description']) ?></textarea>
                    </div>
                </fieldset>
            <?php endwhile; ?>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>