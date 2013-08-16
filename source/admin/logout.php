<?php
session_set_cookie_params(7200);
session_start();
$_SESSION = array();
session_destroy();
header('Location: index.php');
?>
