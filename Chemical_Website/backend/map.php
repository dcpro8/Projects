<?php
session_start();
require 'db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// ✅ Fetch data from email_data table
$sql_email_data = "SELECT email, telephone, fax, address FROM email_data LIMIT 1";
$result_email_data = $conn->query($sql_email_data);

$email = $telephone = $fax = $address = "";
if ($result_email_data->num_rows > 0) {
    $row = $result_email_data->fetch_assoc();
    $email = $row['email'];
    $telephone = $row['telephone'];
    $fax = $row['fax'];
    $address = $row['address'];
}

// ✅ Fetch data from iframe table
$sql_iframe_data = "SELECT iframe FROM iframe LIMIT 1";
$result_iframe_data = $conn->query($sql_iframe_data);

$iframe = "";
if ($result_iframe_data->num_rows > 0) {
    $row = $result_iframe_data->fetch_assoc();
    $iframe = $row['iframe'];
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_email = $_POST['email'];
    $new_telephone = $_POST['telephone'];
    $new_fax = $_POST['fax'];
    $new_address = $_POST['address'];
    $new_iframe = $_POST['iframe_code'];

    // ✅ Update email_data table
    $update_email_sql = "UPDATE email_data SET 
                    email = ?, 
                    telephone = ?, 
                    fax = ?, 
                    address = ? 
                    LIMIT 1";
    $stmt_email = $conn->prepare($update_email_sql);
    $stmt_email->bind_param("ssss", $new_email, $new_telephone, $new_fax, $new_address);
    $stmt_email->execute();

    // ✅ Update iframe table
    $update_iframe_sql = "UPDATE iframe SET iframe = ? LIMIT 1";
    $stmt_iframe = $conn->prepare($update_iframe_sql);
    $stmt_iframe->bind_param("s", $new_iframe);
    $stmt_iframe->execute();

    // ✅ Set success message and reload
    $_SESSION['success_message'] = "Data updated successfully!";
    header("Location: /chemical_website/map");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Contact Information & Map</title>
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

        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            background-color: #2b2b2b;
            border: 2px solid #f4e04d;
            border-radius: 8px;
            color: #ffffff;
            font-size: 14px;
            box-sizing: border-box;
            height: 200px;
        }

        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            background-color: #2b2b2b;
            border: 2px solid #f4e04d;
            border-radius: 8px;
            color: #ffffff;
            font-size: 14px;
            box-sizing: border-box;
        }

        .button2 {
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

        .button2:hover {
            background-color: white;
            box-shadow: 0px 0px 12px 5px rgb(68, 68, 68);
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .success {
            color: #4caf50;
        }

        .error {
            color: #ff3b3b;
        }
    </style>
</head>

<body>
<?php include 'side_bar.php'?>
    <div class="container">
        <form method="POST">
            <h3>Update Contact Information & Google Map Iframe</h3>

            <!-- ✅ Success Message -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <p class="message success"><?= $_SESSION['success_message'];
                                            unset($_SESSION['success_message']); ?></p>
            <?php endif; ?>

            <fieldset>
                <legend>Contact Information</legend>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" required>
                </div>

                <div class="form-group">
                    <label>Telephone Number</label>
                    <input type="text" name="telephone" value="<?= htmlspecialchars($telephone); ?>" required>
                </div>

                <div class="form-group">
                    <label>Fax Number</label>
                    <input type="text" name="fax" value="<?= htmlspecialchars($fax); ?>" required>
                </div>

                <div class="form-group">
                    <label>Company Address</label>
                    <textarea name="address" required><?= htmlspecialchars($address); ?></textarea>
                </div>
            </fieldset>

            <fieldset>
                <legend>Google Map Iframe</legend>
                <div class="form-group">
                    <label>Paste your Google Map Iframe Code</label>
                    <textarea name="iframe_code" required><?= htmlspecialchars($iframe); ?></textarea>
                </div>
            </fieldset>

            <button class="button2" type="submit">Update</button>
        </form>
    </div>

</body>

</html>