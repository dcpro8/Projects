<?php
session_start();
require 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch "Why Us" data
$sql_why_us = "SELECT * FROM why_us ORDER BY id ASC";
$result_why_us = $conn->query($sql_why_us);
$why_us_data = [];
while ($row = $result_why_us->fetch_assoc()) {
    $why_us_data[] = $row;
}

// Fetch "Why Us" heading
$heading_query = "SELECT heading FROM why_us LIMIT 1";
$heading_result = $conn->query($heading_query);
$current_heading = "Why Us?"; // Default heading

if ($heading_result->num_rows > 0) {
    $row = $heading_result->fetch_assoc();
    $current_heading = $row['heading'];
}

// Handle "Why Us" form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_why_us'])) {
    for ($i = 0; $i < count($_POST['why_us_id']); $i++) {
        $id = $_POST['why_us_id'][$i];
        $title = $_POST['why_us_title'][$i];
        $description = $_POST['why_us_description'][$i];

        $update_why_us_sql = "UPDATE why_us SET title=?, description=? WHERE id=?";
        $stmt_why_us = $conn->prepare($update_why_us_sql);
        $stmt_why_us->bind_param("ssi", $title, $description, $id);
        $stmt_why_us->execute();
    }

    header("Location: /chemical_website/why_us");
    exit();
}

// Handle "Why Us" heading update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_heading'])) {
    $new_heading = trim($_POST['why_us_heading']);

    if (!empty($new_heading)) {
        $update_heading_query = "UPDATE why_us SET heading = ? LIMIT 1";
        $stmt = $conn->prepare($update_heading_query);
        $stmt->bind_param("s", $new_heading);
        $stmt->execute();
    }

    header("Location: /chemical_website/why_us");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Why Us</title>
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
        }
    </style>
</head>

<body>

    <?php include 'side_bar.php' ?>

    <div class="container">
        <h2>Edit Why Us Section</h2>

        <!-- Update Why Us Heading -->
        <form method="POST">
            <label>Why Us Heading:</label>
            <input type="text" name="why_us_heading" value="<?php echo htmlspecialchars($current_heading); ?>" required>
            <button type="submit" name="update_heading">Update Heading</button>
        </form>

        <form method="POST">
            <input type="hidden" name="update_why_us" value="1">

            <?php foreach ($why_us_data as $why) { ?>
                <input type="hidden" name="why_us_id[]" value="<?php echo $why['id']; ?>">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="why_us_title[]" value="<?php echo htmlspecialchars($why['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="why_us_description[]" required><?php echo htmlspecialchars($why['description']); ?></textarea>
                </div>
                <hr>
            <?php } ?>

            <button type="submit">Update Why Us</button>
        </form>
    </div>
</body>

</html>
