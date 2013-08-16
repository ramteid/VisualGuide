<?php
/**
 * @author DK-Group Augsburg students
 * @version 1.1
 * @since 2012-09-16
 * 
 * Last Modification: 2012-09-16 / DS
 */

/* * *********************************
 * Global include data
 * ******************************** */
require_once 'global.inc.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>VisualGuide - Treasure Map</title>
        <link rel="stylesheet" href="css/ui-lightness/jquery-ui.custom.css" type="text/css" />
        <link rel="stylesheet" href="css/main.css" type="text/css" />
        <link rel="shortcut icon" href="gfx/favicon.ico" type="image/x-icon" />
        <script type="text/javascript" src="scripts/jquery.min.js"></script>
        <script type="text/javascript" src="scripts/jquery-ui.custom.min.js"></script>
        <script type="text/javascript" src="scripts/jquery.getUrlParam.js"></script>
        <script type="text/javascript" src="scripts/jquery.popup.js"></script>
        <script type="text/javascript" src="scripts/jquery.vgMap.js"></script>
        <script type="text/javascript" src="scripts/main.js"></script>
    </head>
    <body>
        <section id="main">
            <?php include PATH_INCLUDES . 'head.inc.php'; ?>
            <section id="content" class="ui-widget-content ui-corner-all">
                <header class="head ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                    <span class="contentTitle ui-dialog-title">Treasure Map</span>
                </header>
                <div class="content"></div>
            </section>
        </section>
    </body>
</html>