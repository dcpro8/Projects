<!DOCTYPE html>
<html lang="en">

<head>
    <title>Products</title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="css/style.css"> -->
    <link rel="stylesheet" href="/chemical_website/frontendside/css/style.css">
    <link rel="stylesheet" href="css/touchTouch.css">
    <script src="js/jquery.js"></script>
    <script src="js/jquery-migrate-1.1.1.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/script.js"></script>
    <script src="js/jquery.equalheights.js"></script>
    <script src="js/superfish.js"></script>
    <script src="js/jquery.mobilemenu.js"></script>
    <script src="js/jquery.animate-colors-min.js"></script>
    <script src="js/jquery.ui.totop.js"></script>
    <script src="js/sForm.js"></script>
    <script src="js/touchTouch.jquery.js"></script>
    <!-- font-awesome font -->
    <link rel="stylesheet" href="fonts/font-awesome.css" type="text/css" media="screen">
    <!-- end font-awesome font -->

    <style>
        .sf-menu li a {
            color: white;
        }

        .sf-menu li a:hover {
            color: rgb(164, 164, 164);
        }


        .product-card {
            /* padding: 4px; */
            height: 220px;
        }

        @media only screen and (max-width: 480px) {
            .product-card {
                height: 250px;
                width: 250px;
            }

            .product-grid {
                grid-template-columns: 0fr;
            }

            .product-section h1 {
                line-height: 33px;
            }
        }
    </style>

    <script>
        jQuery
        $(window).load(function() {


            setTimeout(function() {
                $('.magnifier').touchTouch();
            }, 0);

        });
        jQuery(function() {
            $().UItoTop({
                easingType: 'easeOutQuart'
            });

            $(".top_arr").click(function() {
                $('body, html').stop().animate({
                    'scrollTop': 0
                });
            });
        });
    </script>
</head>

<body>
    <!--==============================header=================================-->
    <header>
        <div class="container_12">
            <div class="grid_12">
                <div class="menuHolder">
                    <nav>
                        <ul class="sf-menu">
                            <li><a href="/chemical_website/home">Home</a></li>
                            <li><a href="/chemical_website/about">About</a></li>
                            <li class="current"><a href="/chemical_website/products">Products</a></li>
                            <li><a href="/chemical_website/blog">Blog</a></li>
                            <li><a href="/chemical_website/findus">Find Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!--=======content================================-->
    <!-- <section class="products-section">
        <h1>Our Products</h1>
        <div class="product-grid">
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          <a href="#" class="product-card">Products Here</a>
          
        </div>
      </section> -->

    <section class="products-section">
        <h1 style="letter-spacing: 2px;">Our Products</h1>
        <?php
        require '../backend/db_connection.php'; // Include the database connection

        // Fetch product names and IDs from the database
        $query = "SELECT id, product_name FROM products";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo '<div class="product-grid">';
            while ($row = $result->fetch_assoc()) {
                // Create clickable cards linking to the description page
                echo '<a href="product-details?id=' . $row['id'] . '" class="product-card">' . htmlspecialchars($row['product_name']) . '</a>';
            }
            echo '</div>';
        } else {
            echo '<p>No products available.</p>';
        }
        ?>

    </section>




    <!--=======footer=================================-->
    <?php
    include 'footer.php';
    ?>
</body>

</html>

<?php
$conn->close();
?>