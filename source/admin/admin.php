<?php
require_once '../global.inc.php';

session_set_cookie_params(EXPIRE_TIME);
session_start();
if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn'])
{
    session_destroy();
    header('Location: index.php');
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>VisualGuide - ADMIN</title>
        <link rel="stylesheet" href="../css/ui-lightness/jquery-ui.custom.css" type="text/css" />
        <link rel="stylesheet" href="../css/main.css" type="text/css" />
        <link rel="stylesheet" href="../css/admin.css" type="text/css" />
        <link rel="shortcut icon" href="../gfx/favicon.ico" type="image/x-icon" />
        <script type="text/javascript" src="../scripts/jquery.min.js"></script>
        <script type="text/javascript" src="../scripts/jquery-ui.custom.min.js"></script>
        <script type="text/javascript" src="../scripts/jquery.getUrlParam.js"></script>
        <script type="text/javascript" src="../scripts/jquery.sessionTimeout.min.js"></script>
        <script type="text/javascript" src="../scripts/jquery.popup.js"></script>
        <script type="text/javascript" src="../scripts/jquery.toolbar.js"></script>
        <script type="text/javascript" src="../scripts/jquery.vgMap.js"></script>
        <script type="text/javascript" src="../scripts/admin/admin.js"></script>
    </head>
    <body>
        <section id="main">
            <?php include PATH_INCLUDES . 'head.inc.php'; ?>
            <section id="toolbar" class="ui-widget-content ui-corner-all">
                
                <header class="head ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                    <span class="contentTitle ui-dialog-title">Toolbar</span>
                </header>
                <section class="panel">
                    <select name="rooms" class="rooms"></select>
                    <img class="del" src="../gfx/trash.png" alt="Trash" title="Delete" />
                    <img class="delHide" src="../gfx/trash.png" alt="Trash" title="Delete" />
                </section>
                <section class="content"></section>
            </section>
            <section id="content" class="ui-widget-content ui-corner-all">
                <section class="head ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
                    <span class="contentTitle ui-dialog-title">Treasure Map</span>
                </section>
                <section class="content"></section>
                <section class="buttonPanel">
                    <button class="resetButton">Reset position</button>
                </section>
            </section>
            <section id="addCategories-form" title="Add categories">
                <form>
                    <fieldset>
                        <div id="inputboxes"></div>
                        <div id="addButton"></div>
                    </fieldset>
                </form>
            </section>
            <section id="logout"><button class="logout">Logout</button></section>
        </section>
    </body>
</html>