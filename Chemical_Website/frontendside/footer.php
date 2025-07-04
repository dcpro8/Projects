<?php
require '../backend/db_connection.php';

// Fetch site settings
$sql = "SELECT * FROM home_page_settings WHERE id = 1";
$result = $conn->query($sql);
$settings = $result->fetch_assoc();
?>

<?php
$sql_fetch_footer = "SELECT * FROM footer_settings WHERE id=1";
$result_footer = $conn->query($sql_fetch_footer);
$footer_data = $result_footer->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
</head>
<body>
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
                <h1 class="marg_6 footer_main_heading" style="line-height: 30px"><?= htmlspecialchars($footer_data['company_name']) ?></h1> <!-- company_name remains unchanged -->
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