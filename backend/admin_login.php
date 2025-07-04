<?php
session_start();
require 'db_connection.php'; // Include DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check credentials
    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: /chemical_website/admin");
        exit();
    } else {
        $error_message = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Body Styling */
        body {
            background-color: #2b2b2b;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            background-color: #1a1a1a;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #ffd700;
            margin-bottom: 20px;
            letter-spacing: 1.5px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            color: #ffd700;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ffd700;
            border-radius: 4px;
            margin-bottom: 15px;
            background-color: #333;
            color: white;
        }

        button[type="submit"] {
            background-color: #ffd700;
            color: #333;
            padding: 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: white;
            color: black;
        }

        p {
            color: red;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)) echo "<p style='color:red;'>$error_message</p>"; ?>
        <form method="post" action="">
            <label>Username</label>
            <input type="text" name="username" required>
            <br>
            <label>Password</label>
            <input type="password" name="password" required>
            <br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>