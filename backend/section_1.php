<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

$sql_sections = "SELECT * FROM content_sections ORDER BY id ASC";
$result_sections = $conn->query($sql_sections);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sections'])) {
    foreach ($_POST['sections'] as $id => $section) {
        $sql_update_section = "UPDATE content_sections SET title=?, subtitle=?, description=?, dropdown_text=? WHERE id=?";
        $stmt_section = $conn->prepare($sql_update_section);
        $stmt_section->bind_param("ssssi", $section['title'], $section['subtitle'], $section['description'], $section['dropdown_text'], $id);
        $stmt_section->execute();
    }
    // Redirect to prevent form resubmission
    header("Location: /chemical_website/section_1");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section 1</title>

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

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            form {
                max-width: 100%;
                padding: 20px;
            }

            fieldset {
                padding: 10px;
            }

            input[type="text"],
            textarea {
                font-size: 16px;
                padding: 12px;
            }

            button[type="submit"] {
                padding: 14px;
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            form {
                padding: 15px;
                border-radius: 8px;
            }

            fieldset {
                padding: 8px;
            }

            legend {
                font-size: 14px;
            }

            input[type="text"],
            textarea {
                font-size: 14px;
                padding: 10px;
            }

            button[type="submit"] {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>

</head>

<body>
    <?php include 'side_bar.php'; ?>
    <div class="container">
        <form method="POST" enctype="multipart/form-data">

            <h3>Section 1</h3>
            <?php while ($section = $result_sections->fetch_assoc()): ?>
                <fieldset>
                    <legend>Section <?= $section['id'] ?></legend>

                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="sections[<?= $section['id'] ?>][title]" value="<?= htmlspecialchars($section['title']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Subtitle:</label>
                        <input type="text" name="sections[<?= $section['id'] ?>][subtitle]" value="<?= htmlspecialchars($section['subtitle']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="sections[<?= $section['id'] ?>][description]" required><?= htmlspecialchars($section['description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Dropdown Text:</label>
                        <textarea name="sections[<?= $section['id'] ?>][dropdown_text]" required><?= htmlspecialchars($section['dropdown_text']) ?></textarea>
                    </div>
                </fieldset>
            <?php endwhile; ?>
            <button type="submit">Update</button>
        </form>
    </div>
</body>

</html>