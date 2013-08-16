<?php
require_once 'mockup.global.inc.php';
require_once PATH_CLASSES . 'QRImage.class.php';

if(isset($_GET["catno"]) && isset($_GET["booktitle"])) {
    $catno = $_GET["catno"];
    $booktitle = $_GET["booktitle"];
    if(!empty($catno) && !empty($booktitle)) {
        $qr = new QRImage("www.viauc.dk?catno=$catno&booktitle=$booktitle");
        $qr->get_qr_code();
    }
}
?>
