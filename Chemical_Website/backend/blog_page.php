<?php
session_start();
require 'db_connection.php';

// Check if the user is logged in as admin
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// Handle blog post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_blog'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $content = htmlspecialchars(trim($_POST['content']));
    $author = 'admin'; // Default author

    // Handle image upload
    $image_path = '';
    if (!empty($_FILES['blog_image']['name'])) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['blog_image']['tmp_name']);

        if (in_array($file_type, $allowed_types)) {
            $target_dir = "images/";
            $image_name = time() . "_" . basename($_FILES['blog_image']['name']);
            $image_path = $target_dir . $image_name;

            if (!move_uploaded_file($_FILES['blog_image']['tmp_name'], $image_path)) {
                echo "Error uploading image.";
                exit();
            }
        } else {
            echo "Invalid file type. Please upload an image.";
            exit();
        }
    }

    // Insert blog post into the database
    $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, image_path, author) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $image_path, $author);

    if ($stmt->execute()) {
        header("Location: /chemical_website/blog_page?message=Blog added successfully");
    } else {
        echo "Error: " . $stmt->error;
    }
    exit();
}

// Fetch all blog posts
$blog_posts = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Blog Panel</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2b2b2b;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            padding: 20px;
            width: 60vw;
        }

        .navbar {
            display: flex;
            /* background-color: #1a1a1a; */
            padding: 10px;
            justify-content: center;
            align-items: center;
            gap: 20px;
            background-color: #f4e04d;
        }

        .navbar div {
            display: flex;
            gap: 15px;
        }

        .navbar a {
            /* color: #f4e04d; */
            color: black;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 12px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .blog-form {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #333;
            border-radius: 10px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #444;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button {
            background-color: yellow;
            border: none;
            padding: 10px 15px;
            color: black;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: white;
            box-shadow: 0px 0px 12px 5px rgb(68, 68, 68);
        }

        .blog-item a {
            color: blue;
        }

        .blog-item a:visited {
            color: blue;
        }

        .blog-list {
            margin-top: 30px;
        }

        .blog-item {
            padding: 15px;
            background-color: #444;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .blog-item img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>

<body>

    <?php include 'side_bar.php' ?>

    <div class="container">
        <h1>Admin Blog Panel</h1>

        <!-- Blog Form -->
        <div class="blog-form">
            <h2>Add New Blog Post</h2>
            <form method="post" enctype="multipart/form-data">
                <label>Title:</label>
                <input type="text" name="title" required>
                <label>Content:</label>
                <textarea name="content" rows="5" required></textarea>
                <label>Image:</label>
                <input type="file" name="blog_image" accept="image/*">
                <button type="submit" name="add_blog">Add Blog</button>
            </form>
        </div>

        <!-- Existing Blogs -->
        <div class="blog-list">
            <h2>Existing Blog Posts</h2>
            <?php while ($row = $blog_posts->fetch_assoc()): ?>
                <div class="blog-item">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo strlen($row['content']) > 100 ? substr($row['content'], 0, 100) . "..." : $row['content']; ?></p>
                    <?php if (!empty($row['image_path'])): ?>
                        <img src="<?php echo $row['image_path']; ?>" alt="Blog Image" width="150">
                    <?php endif; ?>
                    <p><small>Posted by <?php echo $row['author']; ?> on <?php echo $row['created_at']; ?></small></p>
                    <a href="/chemical_website/edit_blog?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="/chemical_website/delete_blog?id=<?php echo $row['id']; ?>">Delete</a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>

</html>