<?php
session_start();
session_destroy();
header("Location: /chemical_website/admin_login");
exit();
?>
