<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Fetch existing footer settings
$sql_fetch = "SELECT * FROM footer_settings WHERE id=1";
$result = $conn->query($sql_fetch);
$footer_data = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_POST['company_name'];
    $address = $_POST['company_address'];
    $telephone = $_POST['company_telephone'];
    $fax = $_POST['company_fax'];
    $copyright = $_POST['company_copyright'];

    $sql_update = "UPDATE footer_settings SET company_name=?, address=?, telephone=?, fax=?, copyright=? WHERE id=1";

    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sssss", $name, $address, $telephone, $fax, $copyright);

    if ($stmt->execute()) {
        echo "<script>alert('Footer settings updated successfully!');</script>";
        echo "<script>window.location.href='/chemical_website/footer_edit';</script>";
    } else {
        echo "Error updating settings: " . $conn->error;
    }
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
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

            <h3>Footer</h3>
            <div class="form-group">
                <label>Company Name:</label>
                <textarea name="company_name" required><?= htmlspecialchars($footer_data['company_name']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Address:</label>
                <textarea name="company_address" required><?= htmlspecialchars($footer_data['address']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Telephone:</label>
                <textarea name="company_telephone" required><?= htmlspecialchars($footer_data['telephone']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Fax:</label>
                <textarea name="company_fax" required><?= htmlspecialchars($footer_data['fax']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Copyright:</label>
                <textarea name="company_copyright" required><?= htmlspecialchars($footer_data['copyright']) ?></textarea>
            </div>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>