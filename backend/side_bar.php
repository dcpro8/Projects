<?php
// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: /chemical_website/admin_login");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Hover Dropdown Sidebar with Toggle</title>
    <script src="https://kit.fontawesome.com/4e6807ba42.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
                    <!-- <a href="section_1.php">Section 1</a> -->
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


</body>

</html>