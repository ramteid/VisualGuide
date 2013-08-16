<?php
/**
 * @author DK-Group Augsburg students
 * @version 1.0
 * @since 2012-09-10
 * 
 * Last Modification: 2012-09-20 / FW
 *  
 */

/* * *******************************************
 * Global include data
 * ****************************************** */
require_once '../global.inc.php';
/* * *******************************************
 * Lib
 * ****************************************** */
require_once PATH_LIB . 'database/MysqlDatabaseConnection.class.php';


if (isset($_POST["action"]) && isset($_POST["user"]) && isset($_POST["password"]) && $_POST['action'] == 'user_login') {
    
    $username = $_POST["user"];
    $password = $_POST["password"];
    
    if (!empty($username) && !empty($password)) {
        try {
            MysqlDatabaseConnection::open();

            $sql = sprintf("SELECT user, password 
                       FROM account 
                       WHERE user = '%s' 
                       AND password = md5('%s');", 
                    mysql_real_escape_string($username), 
                    mysql_real_escape_string($password));

            MysqlDatabaseConnection::query($sql);
            $count = MysqlDatabaseConnection::getNumRows();
            MysqlDatabaseConnection::close();

            if ($count == 1) {
                session_set_cookie_params(7200);
                session_start();
                $_SESSION['loggedIn'] = true;
                echo true;
            } else {
                echo false;
            }
        } catch (Exception $ex) {
            echo false;
        }
    }
} else {
    echo false;
}
?>