<?php
session_start();
include '../backend/db_connection.php';

// Fetch Site Settings
$sql = "SELECT * FROM home_page_settings WHERE id = 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();

// Fetch Email Data
$sql_email = "SELECT email FROM email_data WHERE id=1";
$result_email = $conn->query($sql_email);
$email_data = $result_email->fetch_assoc();
$email = isset($email_data['email']) ? $email_data['email'] : "Not Available";

// Fetch Footer Settings
$sql_fetch_footer = "SELECT * FROM footer_settings WHERE id=1";
$result_footer = $conn->query($sql_fetch_footer);
$footer_data = $result_footer->fetch_assoc();

// Fetch data from email_data table
$sql_email_data = "SELECT email, telephone, fax, address FROM email_data LIMIT 1";
$result_email_data = $conn->query($sql_email_data);

// Declare variables to avoid undefined errors
$email = $telephone = $fax = $address = "";

// Check if any data exists
if ($result_email_data->num_rows > 0) {
    $row = $result_email_data->fetch_assoc();
    $email = $row['email'];
    $telephone = $row['telephone'];
    $fax = $row['fax'];
    $address = $row['address'];
} else {
    // If no data found, set default values
    $email = "Not Available";
    $telephone = "Not Available";
    $fax = "Not Available";
    $address = "Not Available";
}

// Fetch iframe Data
$sql_iframe = "SELECT * FROM iframe WHERE id=1";
$result_iframe = $conn->query($sql_iframe);
$iframe_data = $result_iframe->fetch_assoc();
$iframe = $iframe_data['iframe'] ?? "";

// Display success or error messages from session
$feedback_message = "";
if (isset($_SESSION['feedback_status'])) {
    $feedback_message = "<div class='" . ($_SESSION['feedback_status'] == "success" ? "success_wrapper" : "error_wrapper") . "'>
                            <div class='" . ($_SESSION['feedback_status'] == "success" ? "success" : "error-message") . "'>" . $_SESSION['feedback_message'] . "</div>
                         </div>";
    unset($_SESSION['feedback_status'], $_SESSION['feedback_message']);
}

// Handle Feedback Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($message)) {
        $sql = "INSERT INTO feedback (name, email, phone, message) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $name, $email, $phone, $message);
            if ($stmt->execute()) {
                $_SESSION['feedback_status'] = "success";
                $_SESSION['feedback_message'] = "Thank you, $name! Your feedback has been submitted.<br><strong>We will be in touch soon.</strong>";
            } else {
                $_SESSION['feedback_status'] = "error";
                $_SESSION['feedback_message'] = "Error submitting feedback. Please try again.";
            }
            $stmt->close();
        }
    } else {
        $_SESSION['feedback_status'] = "error";
        $_SESSION['feedback_message'] = "Please fill in all required fields!";
    }
    $conn->close();
    header("Location: /chemical_website/findus");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Find_us</title>
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
    <script src="js/forms.js"></script>
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

        #feedback-form {
            padding-top: 5px;
        }

        #feedback-form input {
            border: 1px solid #363636;
            font-size: 12px;
            color: #7e7e7e;
            line-height: 18px;
            padding: 5px 9px;
            outline: medium none;
            width: 100%;
            float: left;
            font-family: 'Open Sans', sans-serif;
            background: #1f1f1f;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            /*Firefox 1-3*/
            -webkit-box-sizing: border-box;
            /* Safari */
        }

        #feedback-form textarea {
            border: 1px solid #363636;
            font-size: 12px;
            color: #7e7e7e;
            height: 330px;
            outline: medium none;
            overflow: auto;
            padding: 6px 9px;
            line-height: 18px;
            width: 100%;
            position: relative;
            resize: none;
            background: #1f1f1f;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            /*Firefox 1-3*/
            -webkit-box-sizing: border-box;
            /* Safari */
            float: left;
            font-family: 'Open Sans', sans-serif;
            margin: 2px 0 0;
        }

        #feedback-form label {
            position: relative;
            display: block;
            min-height: 41px;
            width: 100%;
            float: left;
        }

        #feedback-form .error,
        #feedback-form .empty {
            font-family: 'Open Sans', sans-serif;
            color: #fff;
            display: none;
            font-size: 10px;
            line-height: 14px;
            width: auto;
            position: absolute;
            z-index: 999;
            right: 5px;
            top: 10px;
            float: left;
        }

        #feedback-form .error-empty {
            display: none;
            float: left;
        }

        #feedback-form .success {
            color: #656565;
            display: none;
            position: absolute;
            background: #1f1f1f;
            width: 100%;
            border: 1px solid #363636;
            text-align: center;
            padding: 37px 10px;
            z-index: 999;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            /*Firefox 1-3*/
            -webkit-box-sizing: border-box;
            /* Safari */
        }

        #feedback-form .message {
            width: 100%;
        }

        @media only screen and (max-width: 480px) {
            #address-res-center {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 10px;
            }
        }
    </style>

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
                            <li><a href="/chemical_website/blog">Blog</a></li>
                            <li class="current"><a href="/chemical_website/findus">Find Us</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!--=======content================================-->
    <section id="content" class="pad_3">
        <div class="container_12">
            <div class="grid_12 marg_3">
                <div class="grid_8 alpha" id="address-res-center">
                    <h3 class="marg_1 k2">stay in touch</h3>

                    <figure class="figure_1">
                        <?php echo $iframe; ?>
                    </figure>

                    <!-- <figure class="figure_1">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14759.75326961698!2d73.19621395!3d22.3559578!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1737957333858!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </figure> -->
                    <div class="grid_3 alpha marg_3 k1">
                        <h5 class="marg_11">
                            <?= htmlspecialchars($settings['company_name']) ?>,<br>
                            <?= nl2br(htmlspecialchars($address)) ?>
                        </h5>
                        <div class="dl-1">
                            <p style="color:rgb(225, 225, 225);">
                                <span class="width_1">Telephone:</span> <?= htmlspecialchars($telephone) ?><br>
                                <span class="width_2">Fax:</span> <?= htmlspecialchars($fax) ?>
                            </p>
                            <div style="color:rgb(225, 225, 225);" class="width_3">
                                Email: <?= htmlspecialchars($email) ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid_3 marg_3 k1">
                        <h5 class="marg_11"><br>
                        </h5>
                        <div class="dl-1">
                            <p><span></span><br>
                                <span></span><br>
                                <span></span><br>
                                <a href="#" class="link_1 animate"></a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="grid_4 omega">
                    <h3 class="marg_1 k2">Contact</h3>
                    <?php echo $feedback_message; ?>

                    <form id="feedback-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <label class="name">
                            <input type="text" name="name" placeholder="Name:" required>
                            <br class="clear">
                        </label>
                        <label class="email">
                            <input type="email" name="email" placeholder="E-mail:" required>
                            <br class="clear">
                        </label>
                        <label class="phone">
                            <input type="tel" name="phone" placeholder="Phone:" required>
                            <br class="clear">
                        </label>
                        <label class="message">
                            <textarea name="message" placeholder="Message:" required></textarea>
                            <br class="clear">
                        </label>
                        <div class="clear"></div>
                        <div class="btns">
                            <button type="reset" class="button1">Clear</button>
                            <button type="submit" name="submit_feedback" class="button1">Send</button>
                        </div>
                    </form>
                </div>


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