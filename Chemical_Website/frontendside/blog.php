<!DOCTYPE html>
<html lang="en">

<head>
    <title>Blog</title>
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

    <style>
        .sf-menu li a {
            color: white;
        }

        .sf-menu li a:hover {
            color: rgb(164, 164, 164);
        }

        .styled-pre {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            color: white;
            /* background-color: #f9f9f9; */
            padding: 10px;
            /* border: 1px solid #ddd; */
            border-radius: 5px;
            white-space: pre-wrap;
            /* This allows text wrapping inside the pre block */
            word-wrap: break-word;
            line-height: 1.8;
            /* Prevents overflow for long words */
        }

        @media only screen and (max-width: 480px) {
            .list-blog .txt_info {
                margin-left: 4px;
            }
        }

        @media only screen and (max-width: 767px) {
            .block_img {
                width: 10px;
                height: 10px;
            }
        }

        @media only screen and (max-width: 800px) {
            .list-blog .txt_info {
                margin-left: 14px;
            }
        }
    </style>

    <!-- end font-awesome font -->
    <script>
        jQuery
        $(window).load(function() {

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
                            <li><a href="/chemical_website/products">Products</a></li>
                            <li class="current"><a href="/chemical_website/blog">Blog</a></li>
                            <li><a href="/chemical_website/findus">Find Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!--=======content================================-->
    <?php
    require '../backend/db_connection.php'; // Include database connection
    ?>

    <section id="content" class="pad_3">
        <div class="container_12">
            <div class="grid_12 marg_5">
                <div class="grid alpha block_9 blog_test">
                    <h3>Blog</h3>
                    <ul class="list-blog">
                        <?php
                        require '../backend/db_connection.php'; // Ensure database connection

                        // Fetch blog posts from the database
                        $stmt = $conn->prepare("SELECT title, content, image_path, author, created_at FROM blog_posts ORDER BY created_at DESC");
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $title = $row['title'];
                            $content = $row['content'];
                            $image_path = $row['image_path'];
                            $author = $row['author'];
                            $created_at = date("d M", strtotime($row['created_at']));
                        ?>
                            <li class="clearfix">
                                <div class="date clearfix">
                                    <time datetime="<?= $row['created_at'] ?>" class="badge">
                                        <span><?= date("d", strtotime($row['created_at'])) ?></span><?= date("M", strtotime($row['created_at'])) ?>
                                    </time>
                                    <div class="extra-wrap">
                                        <h5 style="letter-spacing: 2px;"><?= htmlspecialchars($title) ?></h5>
                                        <span class="text-info1">Posted by <a href="#" class="link_3 animate"><?= htmlspecialchars($author) ?></a></span>
                                    </div>
                                </div>
                                <div class="clearfix">
                                    <?php 
                                        $image_path = substr($image_path, 2);
                                    ?>
                                    <img src="../chemical_website<?= htmlspecialchars($image_path) ?>" id="blog_img" alt="<?= htmlspecialchars($title) ?>" width="250">
                                    <div class="txt_info">
                                        <pre class="styled-pre"><?= nl2br(htmlspecialchars($content)) ?></pre>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>


    <!--=======footer=================================-->
    <?php
    include 'footer.php';
    ?>
</body>

</html>