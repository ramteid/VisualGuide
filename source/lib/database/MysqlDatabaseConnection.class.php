<?php

/* * *******************************************
 * Interfaces
 * ****************************************** */
require_once PATH_INTERFACES . "IDatabase.php";

/* * *******************************************
 * Exceptions
 * ****************************************** */
require_once PATH_EXCEPTIONS . 'DatabaseException.class.php';

/**
 * Class: MysqlDatabaseConnection
 *
 * @author DK-Group Augsburg students
 * @version 1.0
 * @since 2012-09-07 / FW
 * 
 * Last Modification: 2012-09-27 / DS
 */
class MysqlDatabaseConnection implements IDatabase {

    /**
     * Database host
     * @var string 
     */
    private static $host = SQL_HOST;

    /**
     * Database user
     * @var stringgetNumRows()
     */
    private static $user = SQL_USER;

    /**
     * Database password
     * @var string
     */
    private static $password = SQL_PASSWORD;

    /**
     * Database port
     * @var string
     */
    private static $port = SQL_PORT;

    /**
     * Database 
     * @var string
     */
    private static $database = SQL_DATABASE;

    /**
     * The database link identifier
     * @var resource
     */
    private static $linkIdentifier = NULL;

    /**
     * The result as resource
     * @var resource 
     */
    private static $result;

    /**
     * Close the mysql database connection
     */
    public static function close() {
        if (is_resource(self::$linkIdentifier)) {
            mysql_close(self::$linkIdentifier);
        }
        self::$linkIdentifier = NULL;
    }

    /**
     * Get the result as an array
     * @return array
     */
    public static function getResult() {
        if (is_resource(self::$result)) {
            $error = self::error();
            if (!empty($error)) {
                return false;
            }
            $rows = Array();
            while (($row = mysql_fetch_assoc(self::$result))) {
                $rows[] = $row;
            }
            return $rows;
        }
    }

    /**
     * Open the database connection
     * @throws DatabaseException Thrown if the database data is incorrect
     */
    public static function open() {
        if (empty(self::$linkIdentifier)) {
            $host = self::$host . ":" . self::$port;
            self::$linkIdentifier = mysql_connect($host, self::$user, self::$password);
            if (empty(self::$database) || self::$linkIdentifier === false) {
                $error = mysql_error();
                throw new DatabaseException($error);
            }
            mysql_select_db(self::$database, self::$linkIdentifier);
        }
    }

    /**
     * Fire the sql statement to the database
     * @param string $sqlStmt
     */
    public static function query($sqlStmt) {
        self::open();
        if (is_resource(self::$linkIdentifier)) {
            if (!empty($sqlStmt)) {
                if (is_resource(self::$result)) {
                    mysql_free_result(self::$result);
                }
                self::$result = mysql_query($sqlStmt);
//                if (!self::$result) {
//                  $error = self::error();
//                  if (!empty($error)) {
//                  $query = "Query: " . $sqlStmt . "<br>";
//                  echo $query." ERROR: " . $error;
//                  }
//                  }
            } else {
                self::$result = null;
            }
        }
    }

    /**
     * Get the mysql error as string
     * @return string
     */
    public static function error() {
        if (is_resource(self::$linkIdentifier)) {
            return mysql_error(self::$linkIdentifier);
        }
    }

    /**
     * Get the number of records.                      
     * @return null or int
     */
    public static function getNumRows() {
        if (is_resource(self::$result)) {
            $num = mysql_num_rows(self::$result);
            return (is_numeric($num) ? $num : null);
        }
    }

    /**
     * Get the number of affected rows
     * @return int The number of rows
     */
    public static function getNumAffectedRows() {
        if (is_resource(self::$linkIdentifier)) {
            $num = mysql_affected_rows(self::$linkIdentifier);
            return (is_numeric($num) ? $num : -2);
        }
    }

}

?>
