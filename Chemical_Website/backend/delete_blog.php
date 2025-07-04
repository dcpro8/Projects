<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch blog ID from query string
$blog_id = $_GET['id'] ?? null;

if (!$blog_id) {
    die("Invalid blog post ID.");
}

// Handle delete confirmation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_delete'])) {
    $sql = "DELETE FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blog_id);

    if ($stmt->execute()) {
        echo "<script>alert('Blog post deleted successfully!'); window.location.href='/chemical_website/blog_page';</script>";
    } else {
        echo "<script>alert('Failed to delete the blog post. Please try again.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Blog Post</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2b2b2b;
            color: #333;
            padding: 20px;
            margin: 0;
        }
        h2 {
            color: #ffcc00;
            text-align: center;
        }
        .confirmation-box {
            max-width: 500px;
            margin: 100px auto;
            background-color: #000;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        }
        p {
            color: #ffcc00;
        }
        button {
            background-color: #ffcc00;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin: 10px;
            transition: 0.3s all ease-in;
        }
        button:hover {
            background-color: #ffd633;
        }
        a {
            text-decoration: none;
            color: #fff;
        }
    </style>
</head>
<body>
    <h2>Delete Blog Post</h2>
    <div class="confirmation-box">
        <p>Are you sure you want to delete this blog post?</p>
        <form method="POST">
            <button type="submit" name="confirm_delete">Yes, Delete</button>
            <button><a href="/chemical_website/blog_page">Cancel</a></button>
        </form>
    </div>
</body>
</html>
