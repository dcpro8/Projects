<?php
session_start();
require '../backend/db_connection.php';

$sql = "SELECT * FROM about_us WHERE id = 1"; // Assuming you only have one "About Us" record
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $about = $result->fetch_assoc();
} else {
    $about = [
        'title' => 'About Us',
        'image' => 'images/page2_pic1.jpg',
        'headline' => 'Default Headline',
        'description' => 'No description available.'
    ];
}

$sql = "SELECT * FROM why_us ORDER BY id ASC";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>About</title>
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
        /* Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            justify-content: center;
            align-items: start;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* Product Card */
        .product-card1 {
            background-color: #111;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 300px;
            transition: all 0.3s ease-in-out;
        }

        .product-card1:hover {
            box-shadow: 0px 0px 20px 0px rgb(123, 123, 123);
        }

        /* Image */
        .product-image {
            width: 100px;
            /* Increase size */
            height: 100px;
            object-fit: contain;
            display: block;
            margin-bottom: 15px;
            /* Space below image */
        }

        /* Product Title */
        .product-title {
            font-size: 1rem;
            font-weight: bold;
            text-transform: uppercase;
            color: #fff;
            font-family: 'Orbitron', sans-serif;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        /* Product Description */
        .product-description {
            font-size: 0.85rem;
            color: #ccc;
            line-height: 1.4;
            font-family: 'Arial', sans-serif;
            padding: 0 10px;
        }

        .sf-menu li a {
            color: white;
        }

        .sf-menu li a:hover {
            color: rgb(164, 164, 164);
        }

        @media only screen and (max-width: 767px) {
            .block_7 figure img {
                width: 100%;
                height: 100%;
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
                            <li class="current"><a href="/chemical_website/about">About</a></li>
                            <li><a href="/chemical_website/products">Products</a></li>
                            <li><a href="/chemical_website/blog">Blog</a></li>
                            <li><a href="/chemical_website/findus">Find Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!--=======content================================-->
    <section id="content">
        <div class="container_12">
            <div class="grid_12 marg_8">
                <div class="grid_8 alpha">
                    <div class="block_5">
                        <h3><?php echo htmlspecialchars($about['title']); ?></h3>
                        <img src="../chemical_website/backend/<?php echo htmlspecialchars($about['image']); ?>" alt="" width="200px">
                        <h5 style="font-size: 17px;"><a href="#" class="link_1 animate"><?php echo htmlspecialchars($about['headline']); ?></a></h5>
                        <p style="color:rgb(225, 225, 225); font-size: 14px; line-height: 20px"><?php echo nl2br(htmlspecialchars($about['description'])); ?></p>
                    </div>
                </div>
                <div class="grid_4 omega">
                    <div class="block_6">
                        <?php


                        // Fetch heading from why_us table
                        $heading_query = "SELECT heading FROM why_us LIMIT 1";
                        $heading_result = $conn->query($heading_query);
                        $why_us_heading = "Why Us?"; // Default heading

                        if ($heading_result->num_rows > 0) {
                            $row = $heading_result->fetch_assoc();
                            $why_us_heading = $row['heading'];
                        }
                        ?>
                        <h3><?php echo htmlspecialchars($why_us_heading); ?></h3>

                        <ul class="list_1">
                            <?php
                            // Fetch Why Us data
                            $sql = "SELECT * FROM why_us ORDER BY id ASC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $counter = 1; // Numbering for items
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <li>
                                        <div class="block_num">
                                            <p><?php echo str_pad($counter, 2, "0", STR_PAD_LEFT); ?></p>
                                        </div>
                                        <div class="txt_info">
                                            <h5><a href="#" class="link_1 animate"><?php echo htmlspecialchars($row['title']); ?></a></h5>
                                            <p style="color: white; font-size: 12.3px"><?php echo htmlspecialchars($row['description']); ?></p>
                                        </div>
                                    </li>
                            <?php
                                    $counter++;
                                }
                            } else {
                                echo "<li>No data available</li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="grid_12">
                <hr class="marg_4">
            </div>
            <div class="product-section">
                <h3 style="text-align: center;" class="product-heading">
                    <?php
                    $settingsQuery = "SELECT heading FROM settings WHERE id = 1";
                    $settingsResult = mysqli_query($conn, $settingsQuery);
                    $settingsRow = mysqli_fetch_assoc($settingsResult);
                    echo htmlspecialchars($settingsRow['heading']);
                    ?>
                    <br><br>
                </h3>

                <div class="product-grid">
                    <?php
                    $query = "SELECT * FROM product_display";
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <div class="product-card1">
                            <figure class="product-image">
                                <img alt="" src="../chemical_website/<?php echo htmlspecialchars($row['image']); ?>">
                            </figure>
                            <div class="product-info">
                                <h5 style="letter-spacing: 2px;" class="product-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="product-description"><?php echo htmlspecialchars($row['description']); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>



    </section>
    <!--=======footer=================================-->

    <?php
    include 'footer.php';
    ?>
</body>

</html>
<?php $conn->close(); ?>