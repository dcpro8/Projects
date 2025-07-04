<?php
session_start();
require 'backend/db_connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}

// ===============================
// ✅ Handle Delete Single Record
// ===============================
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $deleteQuery = "DELETE FROM feedback WHERE id=?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        header("Location: /chemical_website/admin");
        exit();
    } else {
        echo "Failed to delete the record.";
    }
    $stmt->close();
}

// ===============================
// ✅ Handle Delete All Records
// ===============================
if (isset($_GET['delete_all'])) {
    // Delete all records
    $conn->query("TRUNCATE TABLE feedback");

    // Reset Auto Increment ID to 1
    $conn->query("ALTER TABLE feedback AUTO_INCREMENT = 1");

    // Redirect after deleting all records
    header("Location: /chemical_website/admin");
    exit();
}

// ===============================
// ✅ Count Total Visitors
// ===============================
$totalVisitorsQuery = "SELECT COUNT(*) AS total_visitors FROM visitors";
$totalVisitorsResult = $conn->query($totalVisitorsQuery);
$totalVisitors = ($totalVisitorsResult->num_rows > 0) ? $totalVisitorsResult->fetch_assoc()['total_visitors'] : 0;

// ✅ Count Total Inquiries
$totalInquiriesQuery = "SELECT COUNT(*) AS total_inquiries FROM feedback";
$totalInquiriesResult = $conn->query($totalInquiriesQuery);
$totalInquiries = ($totalInquiriesResult->num_rows > 0) ? $totalInquiriesResult->fetch_assoc()['total_inquiries'] : 0;

// ✅ Count Total Admins
$totalAdminsQuery = "SELECT COUNT(*) AS total_admins FROM admin_users";
$totalAdminsResult = $conn->query($totalAdminsQuery);
$totalAdmins = ($totalAdminsResult->num_rows > 0) ? $totalAdminsResult->fetch_assoc()['total_admins'] : 0;

// ✅ Fetch Feedback Data
$sql = "SELECT * FROM feedback ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://kit.fontawesome.com/4e6807ba42.js" crossorigin="anonymous"></script>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #2b2b2b;
        }

        .dashboard-container {
            padding: 20px;
            text-align: center;
            color: #ffffff;
        }

        .metrics-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .metric-box {
            background-color: #444;
            padding: 20px;
            border-radius: 10px;
            width: 200px;
            text-align: center;
            color: #f4e04d;
            font-size: 18px;
        }

        .metric-box span {
            display: block;
            font-size: 24px;
            font-weight: bold;
            color: #ffffff;
        }

        h2 {
            text-align: center;
            font-size: 35px;
            color: #f4e04d;
        }

        .container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        table {
            width: 75%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 1em;
            text-align: left;
            color: #ffffff;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        table td {
            word-wrap: break-word;
            white-space: normal;
            max-width: 200px;
        }

        table th {
            background-color: #f4e04d;
            color: #2b2b2b;
        }

        table tr:hover {
            background-color: #444;
        }

        button {
            background-color: #ff4c4c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #ff3333;
        }

        .delete-all {
            background-color: #ff3333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        .delete-all:hover {
            background-color: #e60000;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 200px;
            height: 100vh;
            background-color: #333;
            color: #eee;
            padding: 20px 0;
            box-sizing: border-box;
            overflow-y: hidden;
            transition: left 0.3s ease;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .sidebar.closed {
            left: -200px;
        }

        #toggle-sidebar {
            position: fixed;
            top: 20px;
            left: 220px;
            padding: 10px 15px;
            background-color: #555;
            color: white;
            border: none;
            cursor: pointer;
            transition: left 0.3s ease;
        }

        .sidebar.closed+#toggle-sidebar {
            left: 20px;
        }

        .sidebar h2 {
            margin: 0;
            padding: 15px 20px;
            font-size: 20px;
            font-weight: bold;
            color: yellow;
            margin-top: 25px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            padding: 0;
            margin: 0;
        }

        .sidebar a {
            color: #eee;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            transition: background-color 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #444;
        }

        .dropdown-content {
            background-color: #444;
            padding: 0;
            border-radius: 5px;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-10px);
            transition: max-height 0.7s ease, opacity 0.3s ease, transform 0.7s ease;
        }

        .dropdown.active .dropdown-content {
            max-height: 500px;
            opacity: 1;
            transform: translateY(0);
        }

        .dropdown-content a {
            padding: 8px 20px;
        }

        .dropdown>a::after {
            content: '\25BE';
            float: right;
            margin-left: 5px;
        }

        .footer-menu {
            margin-top: auto;
            border-top: 1px solid #555;
            padding-top: 10px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                left: -100%;
            }

            .sidebar.closed {
                left: 0;
            }

            #toggle-sidebar {
                left: 20px;
                z-index: 1000;
            }

            .sidebar.closed+#toggle-sidebar {
                left: 20px;
            }
        }
    </style>
</head>

<body>
<div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li><a href="/chemical_website/admin"><span class="fa-solid fa-gauge"></span> Dashboard</a></li>

            <li class="dropdown">
                <a href="#"><span class="fa-solid fa-house"></span> Home</a>
                <div class="dropdown-content">
                    <a href="/chemical_website/slider_and_name">Slider Images</a>
                    <!-- <a href="/chemical_website/section_1">Section 1</a> -->
                    <a href="/chemical_website/section_2">Section 2</a>
                    <a href="/chemical_website/section_3">Section 3</a>
                    <a href="/chemical_website/personnel">Personnel</a>
                </div>
            </li>

            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-address-card"></i> About</a>
                <div class="dropdown-content">
                    <a href="/chemical_website/about_us">About Us</a>
                    <a href="/chemical_website/why_us">Why Us</a>
                    <a href="/chemical_website/our_products">Our Products</a>
                </div>
            </li>

            <li class="dropdown">
                <a href="#"><i class="fa-brands fa-product-hunt"></i> Products</a>
                <div class="dropdown-content">
                    <a href="/chemical_website/add_product">Add Product</a>
                    <a href="/chemical_website/edit-product">Edit Product</a>
                    <a href="/chemical_website/delete-product">Remove Product</a>
                </div>
            </li>

            <li><a href="/chemical_website/blog_page"><i class="fa-solid fa-blog"></i> Blog</a></li>
            <li><a href="/chemical_website/footer_edit"><i class="fa-solid fa-f"></i> Footer</a></li>
            <li><a href="/chemical_website/map"><i class="fa-solid fa-map-location"></i> Map</a></li>
        </ul>

        <div class="footer-menu">
            <ul>
                <li><a href="/chemical_website/change_password"><i class="fa-solid fa-key"></i> Change Password</a></li>
                <li><a href="/chemical_website/logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
            </ul>
        </div>
    </div>

    <button id="toggle-sidebar">&#9776;</button>

    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleButton = document.getElementById('toggle-sidebar');
        const dropdowns = document.querySelectorAll('.dropdown');

        // Toggle Sidebar
        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('closed');
        });

        function isMobileView() {
            return window.innerWidth <= 768;
        }

        dropdowns.forEach(dropdown => {
            if (!isMobileView()) {
                // Hover functionality for larger screens
                dropdown.addEventListener('mouseenter', () => {
                    dropdown.classList.add('active');
                });
                dropdown.addEventListener('mouseleave', () => {
                    dropdown.classList.remove('active');
                });
            } else {
                // Click functionality for smaller screens
                dropdown.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevents event bubbling
                    dropdown.classList.toggle('active');
                });
            }
        });

        // Close dropdowns when clicking outside (only for mobile)
        document.addEventListener('click', (e) => {
            if (isMobileView()) {
                dropdowns.forEach(dropdown => {
                    if (!dropdown.contains(e.target)) {
                        dropdown.classList.remove('active');
                    }
                });
            }
        });

        // Reapply event listeners when screen resizes
        window.addEventListener('resize', () => {
            dropdowns.forEach(dropdown => {
                dropdown.replaceWith(dropdown.cloneNode(true)); // Reset event listeners
            });
            location.reload(); // Reload to reapply correct hover or click behavior
        });
    </script>

    <div class="dashboard-container">
        <h1>Welcome, Admin!</h1>
        <div class="metrics-container">
            <div class="metric-box">
                Total Visitors
                <span><?php echo $totalVisitors; ?></span>
            </div>
            <div class="metric-box">
                Total Inquiries
                <span><?php echo $totalInquiries; ?></span>
            </div>
            <div class="metric-box">
                Total Admins
                <span><?php echo $totalAdmins; ?></span>
            </div>
        </div>
    </div>

    <h2>Recent Inquiries</h2>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                            <td><?php echo $row['submitted_at']; ?></td>
                            <td>
                                <a href="/chemical_website/admin?delete_id=<?php echo $row['id']; ?>">
                                    <button>Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7">No feedback available.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- ✅ Delete All Button -->
        <a href="/chemical_website/admin?delete_all=true" onclick="return confirm('⚠️ Are you sure to delete all records?')">
            <button class="delete-all">🗑 Delete All Records</button>
        </a>
    </div>
</body>

</html>