<?php
/**
 * @author DK-Group Augsburg students
 * @version 1.0
 * @since 2012-09-10
 * 
 * Last Modification: 2012-09-13 / PVO
 */

/* * *********************************
 * Global include data
 * ******************************** */
require_once '../global.inc.php';

session_set_cookie_params(EXPIRE_TIME);
session_start();
if (isset($_SESSION['loggedIn']))
{
    if ($_SESSION['loggedIn'])
    {
        header('Location: admin.php');
    }
    else
    {
    session_destroy();
    }
}
else
{
    session_destroy();
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="dk" xml:lang="dk">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>VisualGuide - ADMIN</title>
        <link rel="stylesheet" href="../css/ui-lightness/jquery-ui.custom.css" type="text/css" />
        <link rel="stylesheet" href="../css/main.css" type="text/css" />
        <link rel="stylesheet" href="../css/admin.css" type="text/css" />
        <script type="text/javascript" src="../scripts/jquery.min.js"></script>
        <script type="text/javascript" src="../scripts/jquery-ui.custom.min.js"></script>
        <script type="text/javascript" src="../scripts/admin/login.js"></script>
    </head>
    <body>
        <section id="login-form">
            <section id="status" style="text-align: left; margin-top: 20px;">
                <h1 style="text-align: center;"><img style="text-align: center;" src="../gfx/key.png" />&nbsp;LOGIN</h1>
                <form id="login" action="" style="text-align: center;">
                    <input type="hidden" name="function" value="login">
                    <input type="hidden" name="action" value="user_login">
                    <input class="username" type="text" name="user" placeholder="Username"><br />
                    <input class="password" type="password" name="password" placeholder="Password">
                </form>
            </section>
        </section>
    </body>
</html>
