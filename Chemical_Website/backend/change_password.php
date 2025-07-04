<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error_message = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error_message = "New password and confirm password do not match!";
    } else {
        // Verify username and current password
        $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $current_password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Update password in the database
            $update_stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $new_password, $username);
            if ($update_stmt->execute()) {
                $success_message = "Password successfully updated!";
            } else {
                $error_message = "Failed to update password. Please try again.";
            }
        } else {
            $error_message = "Invalid username or current password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2b2b2b;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .change-password-container {
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #ffd700;
            font-size: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            text-align: left;
            color: #f4e04d;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 94%;
            background-color: #333;
            color: white;
            font-size: 1rem;
        }

        button {
            background-color: #ffd700;
            color: #333;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: white;
            box-shadow: 0px 0px 12px 5px rgb(68, 68, 68);
        }

        .message {
            color: green;
            font-size: 14px;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }

        a {
            color: #f4e04d;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <?php include 'side_bar.php' ?>

    <div class="change-password-container">
        <h2>Change Password</h2>
        <?php
        if (isset($success_message)) echo "<p class='message'>$success_message</p>";
        if (isset($error_message)) echo "<p class='error-message'>$error_message</p>";
        ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>

            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit">Update Password</button>
            <a href="/chemical_website/admin">Back to Dashboard</a>
        </form>
    </div>
</body>

</html>