<?php

/* * ********************************
 * Global
 * ******************************* */
require_once 'mockup.global.inc.php';

/* * ********************************
 * Global
 * ******************************* */
require_once PATH_LIB . 'database/MysqlDatabaseConnection.class.php';

if (isset($_GET["term"]) && !empty($_GET["term"])) {
    $booktitle = strip_tags($_GET["term"]);
    try {
        MysqlDatabaseConnection::open();

        $booktitle = mysql_real_escape_string(strtolower($booktitle));
        $sql = "SELECT title FROM book WHERE title LIKE '%$booktitle%' LIMIT 0,5";

        MysqlDatabaseConnection::query($sql);
        $bookResult = MysqlDatabaseConnection::getResult();
        MysqlDatabaseConnection::close();

        $row_set = array();
        foreach ($bookResult as $book) {
            $bTitle = htmlentities(stripslashes($book['title']));
            if(empty($bTitle)) {
                continue;
            }
            $row_set[] = $bTitle;
        }
        echo json_encode($row_set);
    } catch (Exception $ex) {
        echo "";
    }
}
?>
