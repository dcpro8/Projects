<?php
require 'backend/db_connection.php';

// Fetch site settings
$sql = "SELECT * FROM home_page_settings WHERE id = 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();
?>

<?php

// Function to get the user's IP address
function getUserIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ip_address = getUserIP();

// Check if the IP is already recorded
$checkIPQuery = "SELECT COUNT(*) AS ip_count FROM visitors WHERE ip_address = ?";
$stmt = $conn->prepare($checkIPQuery);
$stmt->bind_param("s", $ip_address);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['ip_count'] == 0) {
    // Insert new visitor only if IP is not found
    $insertIPQuery = "INSERT INTO visitors (ip_address) VALUES (?)";
    $stmt = $conn->prepare($insertIPQuery);
    $stmt->bind_param("s", $ip_address);
    $stmt->execute();
}

// Count total unique visitors
$totalVisitorsQuery = "SELECT COUNT(*) AS total_visitors FROM visitors";
$totalVisitorsResult = $conn->query($totalVisitorsQuery);
$totalVisitors = ($totalVisitorsResult->num_rows > 0) ? $totalVisitorsResult->fetch_assoc()['total_visitors'] : 0;
?>

<?php

$query1 = "SELECT * FROM section_images WHERE section_id = 1 LIMIT 1";
$result1 = mysqli_query($conn, $query1);

// Check if data exists
if ($result1 && mysqli_num_rows($result1) > 0) {
    $row = mysqli_fetch_assoc($result1);
} else {
    // Default values if no data is found
    $row = [
        'image_url' => 'images/default.jpg',
        'title' => 'Default Title',
        'description' => 'Default description text.',
    ];
}

?>

<?php
$sql2 = "SELECT * FROM feature_sections ORDER BY id ASC";
$result2 = $conn->query($sql2);

?>

<?php
$sql_personnel = "SELECT * FROM personnel ORDER BY id ASC";
$result_personnel = $conn->query($sql_personnel);
?>

<?php
$sql_fetch_footer = "SELECT * FROM footer_settings WHERE id=1";
$result_footer = $conn->query($sql_fetch_footer);
$footer_data = $result_footer->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Home</title>
    <meta charset="utf-8">
    <meta name="format-detection" content="telephone=no" />
    <link rel="icon" href="frontendside/images/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="frontendside/images/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" media="screen" href="frontendside/css/style.css">
    <script src="frontendside/js/jquery.js"></script>
    <script src="frontendside/js/jquery-migrate-1.1.1.js"></script>
    <script src="frontendside/js/jquery.easing.1.3.js"></script>
    <script src="frontendside/js/script.js"></script>
    <script src="frontendside/js/superfish.js"></script>
    <script src="frontendside/js/jquery.mobilemenu.js"></script>
    <script src="frontendside/js/jquery.animate-colors-min.js"></script>
    <script src="frontendside/js/camera.js"></script>
    <script src="frontendside/js/jquery.ui.totop.js"></script>
    <script src="frontendside/js/sForm.js"></script>
    <!--[if (gt IE 9)|!(IE)]><!-->
    <script src="frontendside/js/jquery.mobile.customized.min.js"></script>
    <!--<![endif]-->
    <!-- font-awesome font -->
    <link rel="stylesheet" href="frontendside/fonts/font-awesome.css" type="text/css" media="screen">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- end font-awesome font -->

    <style>
        body {
            background-color: #1f1f1f;
        }

        footer {
            background-color: #eada51;
        }

        .sf-menu li a {
            color: white;
        }

        .sf-menu li a:hover {
            color: rgb(164, 164, 164);
        }

        .btn {
            transition: all 0.2s ease-in;
        }

        .btn:hover {
            box-shadow: 0px 0px 7px 1px yellow;
        }

        #title_pre_tag {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
        }

        .block_3 .icon {
            margin-top: 20px;
        }

        #h2-heading {
            font-size: 38px;
        }

        /* @media only screen and (max-width: 480px) {
            .heading_main {
                margin-top: 34px;
            }

            .block_2 h2 {
                font-size: 30px;
                line-height: 42px;
            }

            .logoHolder {
                margin-bottom: 0;
                padding-bottom: 0;
            }

            .slider_box {
                margin-bottom: 0;
                padding-bottom: 0;
            }

            hr {
                display: none;
            }
            nav {
                margin-top: 10px;
                margin-bottom: 10px;
            }
        } */
        @media only screen and (max-width: 768px) {

            /* ======== Fix Menu Alignment ======== */
            .menu-holder {
                text-align: center;
            }

            .sf-menu {
                flex-direction: column;
                padding: 0;
                margin: 0;
            }

            .sf-menu li {
                display: block;
                width: 100%;
                border-bottom: 1px solid #333;
            }

            .sf-menu li a {
                display: block;
                padding: 12px 0;
                color: white;
                text-align: center;
                background-color: #1f1f1f;
            }

            .sf-menu li a:hover {
                background-color: #eada51;
                color: #1f1f1f;
            }

            /* ======== Remove Unwanted Gaps ======== */
            .slider_box,
            .logoHolder {
                margin-bottom: 0;
                padding-bottom: 0;
            }

            hr {
                display: none;
            }

            /* ======== Adjust Heading Margin ======== */
            .heading_main {
                margin-top: 34px;
            }

            .block_2 h2 {
                font-size: 28px;
                line-height: 38px;
            }

            nav {
                margin-top: 10px;
                margin-bottom: 10px;
            }

            /* ======== Prevent Extra Space on Left/Right ======== */
            body {
                overflow-x: hidden;
            }
        }
    </style>

    <script>
        jQuery
        $(window).load(function() {
            jQuery('#camera').camera({

                loader: true,
                pagination: false,
                thumbnails: false,
                transPeriod: 2000,
                height: '39.9%',
                caption: false,
                navigation: false,
                fx: 'simpleFade',
                autoAdvance: true

            });

            $('.block_1 .button1').hover(function() {
                $(this).parent().find('.block_num').animate({
                    'color': '#fff'
                });
                $(this).parent().find('h5').animate({
                    'color': '#eada51'
                });
                $(this).parent().find('p').animate({
                    'color': '#fff'
                });
            }, function() {
                $(this).parent().find('.block_num').animate({
                    'color': '#4c4b4b'
                });
                $(this).parent().find('h5').animate({
                    'color': '#fff'
                });
                $(this).parent().find('p').animate({
                    'color': '#4c4b4b'
                });
            });

            $('.block_3 .button1').hover(function() {
                $(this).parent().find('.block_letter').animate({
                    'color': '#fff'
                });
                $(this).parent().parent().find('.icon').animate({
                    'backgroundColor': '#eada51'
                });
                $(this).parent().parent().find('.icon div').animate({
                    'color': '#fff'
                });
            }, function() {
                $(this).parent().find('.block_letter').animate({
                    'color': '#4c4b4b'
                });
                $(this).parent().parent().find('.icon').animate({
                    'backgroundColor': '#1f1f1f'
                });
                $(this).parent().parent().find('.icon div').animate({
                    'color': '#4c4b4b'
                });
            })

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
                <div class="menu-holder">
                    <nav>
                        <ul class="sf-menu" style="position: relative; z-index: 1000;">
                            <li class="current"><a href="home.php">Home</a></li>
                            <li><a href="/chemical_website/about">About</a></li>
                            <li><a href="/chemical_website/products">Products</a></li>
                            <li><a href="/chemical_website/blog">Blog</a></li>
                            <li><a href="/chemical_website/findus">Find Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>


        <div class="slider_box">
            <div class="camera_wrap camera_azure_skin" id="camera">
                <div data-src="<?= htmlspecialchars($settings['slider_image1']) ?>">
                    <div class="camera_caption fadeIn"></div>
                </div>
                <div data-src="<?= htmlspecialchars($settings['slider_image2']) ?>">
                    <div class="camera_caption fadeIn"></div>
                </div>
                <div data-src="<?= htmlspecialchars($settings['slider_image3']) ?>">
                    <div class="camera_caption fadeIn"></div>
                </div>
            </div>
            <div class="logoHolder">
                <div class="container_12">
                    <div class="grid text_center">
                        <h1 class="heading_main" style="font-size: 2rem;"><?= htmlspecialchars($settings['company_name']) ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- 
        


        -->
    </header>
    <!--=======content================================-->
    <section>
        
    </section>

    <!-- <div class="dropdown">
            <div class="dropdown-item">
                <h4>Feature 1</h4>
                <p>Description of Feature 1. This could include details about the innovation.</p>
            </div>
            <div class="dropdown-item">
                <h4>Feature 2</h4>
                <p>Description of Feature 2. This could explain performance advancements.</p>
            </div>
            <div class="dropdown-item">
                <h4>Feature 3</h4>
                <p>Description of Feature 3. This could highlight technological benefits.</p>
            </div>
        </div> -->


    <div class="container_12" style="margin-top: 100px;">
        <div class="grid_12">
            <div class="block_2">
                <img src="backend/<?php echo $row['image_url']; ?>" alt="">
                <div class="txt_info">
                    <h2 style="letter-spacing: 2px;" id="h2-heading"><?php echo nl2br($row['title']); ?></h2>
                    <p style="color: white"><?php echo $row['description']; ?></p>
                    <!-- <a class="button1" href="#">More</a> -->
                </div>
            </div>
            <hr class="marg_2">
        </div>
    </div>
    <div class="container_12">
        <?php
        $count = 0;
        while ($row = $result2->fetch_assoc()):
            if ($count % 2 == 0) echo '<div class="container_12' . ($count > 0 ? ' marg_3' : '') . '">';
        ?>
            <div class="grid_6">
                <div class="block_3">
                    <div class="icon" style="background-color: #eada51;">
                    <div> <?= htmlspecialchars_decode($row['icon_class']) ?> </div>

                    </div>
                    <div class="txt_info">
                        <div class="block_letter" style="color: white;">
                            <p><?= htmlspecialchars($row['letter']) ?></p>
                        </div>
                        <pre id="title_pre_tag"><?= htmlspecialchars($row['title1']) ?></pre>
                        <p style="color: white"><?= htmlspecialchars($row['description']) ?></p>
                    </div>
                </div>
            </div>
        <?php
            if ($count % 2 == 1) echo '</div>';
            $count++;
        endwhile;
        if ($count % 2 == 1) echo '</div>'; // Close last row if odd number of items
        ?>
    </div>
    <div class="container_12">
        <div class="grid_12">
            <hr class="marg_4">
        </div>
    </div>
    <div class="container_12">
        <div class="grid_12">
            <h3 class="marg_5 corr_01">Highly experienced personnel</h3>
        </div>
        <div class="clearfix"></div>
        <?php while ($person = $result_personnel->fetch_assoc()): ?>
            <div class="grid_3">
                <div class="block_4">
                    <img src="<?= htmlspecialchars($person['image_url']) ?>" alt="">
                    <h5><a class="link_1 animate"> <?= htmlspecialchars($person['name']) ?> </a></h5>
                    <p style="color: white;"><?= htmlspecialchars($person['description']) ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    </section>
    <!--=======footer=================================-->
    <footer>
        <div class="container_12">
            <div class="wrapper">
                <div class="grid_12">
                    <div class="top_arr"></div>
                </div>
                <div class="clearfix"></div>
                <div class="grid_12"></div>
                <div class="clearfix"></div>
                <div class="grid_12">
                    <h1 class="marg_6 footer_main_heading" style="line-height: 30px;"><?= htmlspecialchars($footer_data['company_name']) ?></h1> <!-- company_name remains unchanged -->
                </div>
                <div class="clearfix"></div>
                <div class="grid_12 marg_7">
                    <div class="grid_3 prefix_3 alpha f_left">
                        <p class="pad_1"><?= nl2br(htmlspecialchars($footer_data['address'])) ?></p>
                    </div>
                    <div class="grid_3 suffix_3 omega f_left">
                        <p class="pad_2">
                            <span class="width_1">Telephone:</span> <?= htmlspecialchars($footer_data['telephone']) ?><br>
                            <span class="width_2">Fax:</span> <?= htmlspecialchars($footer_data['fax']) ?>
                        </p>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="grid_12">
                    <p class="copyright"><span><?= htmlspecialchars($footer_data['copyright']) ?></span></p>
                    <div><!--{%FOOTER_LINK} --></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>