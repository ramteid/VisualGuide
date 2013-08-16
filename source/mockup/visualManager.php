<?php

/* * ********************************
 * Global
 * ******************************* */
require_once 'mockup.global.inc.php';
require_once PATH_CLASSES . 'QRImage.class.php';

if (isset($_GET["display"]) && isset($_GET["title"]) && isset($_GET["catNo"])) {
    $choice = $_GET["display"];
    $title = $_GET["title"];
    $catNo = $_GET["catNo"];
    if (!empty($title) && !empty($catNo)) {
        if ($choice == 'qr') {
            $title = urlencode($title);
            $url = 'http://';
            $url .= $_SERVER['SERVER_NAME'];
            $url .= '/mobile.php';
            $url .= "?title=" . $title;
            $url .= "&catNo=" . $catNo;
            $qr = new QRImage($url);
            echo $qr->get_qr_code();
        } else if ($choice == 'map') {
            $url = '../index.php';
            $url .= "?title=" . $title;
            $url .= "&catNo=" . $catNo;
            header('Location: ' .  $url);
        } else {
            echo "INVALID COMMAND";
        }
    }
} else {
    echo "INVALID COMMAND";
}
?>
