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

// Fetch blog post data
$blog_id = $_GET['id'] ?? null;
if ($blog_id) {
    $sql = "SELECT * FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();

    if (!$blog) {
        die("Blog post not found.");
    }

    $title = $blog['title'];
    $content = $blog['content'];
    $image_path = $blog['image_path'];
} else {
    die("Invalid blog post ID.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_title = $_POST['title'] ?? '';
    $new_content = $_POST['content'] ?? '';
    $new_image_path = $image_path;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "../uploads/";
        $new_image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $new_image_path);
    }

    // Update blog post
    $sql = "UPDATE blog_posts SET title = ?, content = ?, image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $new_title, $new_content, $new_image_path, $blog_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "✅ Blog post updated successfully!";
    } else {
        $_SESSION['success_message'] = "⚠️ No changes made or update failed.";
    }

    // Redirect to avoid resubmission
    header("Location: /chemical_website/edit_blog?id=" . $blog_id);
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #1a1a1a;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #2b2b2b;
            padding: 25px;
            border-radius: 12px;
            /* width: 450px; */
            width: 640px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #f4e04d;
            margin-bottom: 15px;
            text-align: center;
        }

        #success-message {
            color: limegreen;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #f4e04d;
            border-radius: 5px;
            background-color: transparent;
            color: white;
            outline: none;
            transition: 0.3s;
        }

        textarea {
            resize: none;
            height: 110px;
            overflow: hidden;
        }

        input::placeholder,
        textarea::placeholder {
            color: white;
        }

        input[type="text"]:focus,
        textarea:focus {
            border-color: yellow;
            background-color: #111;
        }

        label {
            color: #f4e04d;
            font-weight: bold;
            margin-bottom: 5px;
        }

        #blog-image {
            width: 120px;
            height: 120px;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
        }

        /* ✅ Center Aligning Everything */
        .action-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        button {
            background-color: #f4e04d;
            border: none;
            padding: 12px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
            font-weight: bold;
            color: black;
        }

        button:hover {
            background-color: black;
            color: #f4e04d;
        }

        a {
            text-decoration: none;
            color: #f4e04d;
            font-size: 15px;
            margin-top: 10px;
        }

        a:hover {
            color: white;
        }

        /* ✅ Fix Current Image Not Showing */
        img#blog-image {
            background-color: #111;
            object-fit: cover;
        }

        img#blog-image[src=""] {
            content: url('../uploads/no-image.png');
        }

        /* ✅ Responsive Design */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            #blog-image {
                width: 100px;
                height: 100px;
            }

            button {
                width: 100%;
            }

            a {
                text-align: center;
                display: block;
            }
        }

        @media (max-width: 480px) {
            .container {
                width: 100%;
                padding: 15px;
            }

            #blog-image {
                width: 90px;
                height: 90px;
            }

            textarea {
                height: 120px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Blog Post</h2>

        <div id="success-message">
            <?php
            if (isset($_SESSION['success_message'])) {
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); // Clear message after displaying
            }
            ?>
        </div>


        <!-- <div id="success-message">
            ✅ Blog post updated successfully!
        </div> -->

        <form method="POST" enctype="multipart/form-data">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Enter Blog Title">

            <label for="content">Content:</label>
            <textarea id="content" name="content" rows="5" placeholder="Write your content here..."><?php echo htmlspecialchars($content); ?></textarea>

            <div class="action-container">
                <img id="blog-image"
                    src="<?php echo $image_path ? $image_path : '../uploads/no-image.png'; ?>"
                    alt="Blog Image">

                <label for="image">Change Image:</label>
                <input type="file" id="image" name="image">

                <button type="submit">Update Blog Post</button>
                <a href="/chemical_website/blog_page">⬅️ Back to Blog</a>
            </div>
        </form>
    </div>
</body>

</html>