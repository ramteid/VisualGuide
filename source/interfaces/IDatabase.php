<?php

/**
 * Inferace: IDatabase
 * 
 * @author Florian
 * @version 1.0
 * @since 2012-09-06
 * 
 * Last Modification: 2012-09-13 / MM
 */

interface IDatabase {

    /**
     * Open the database connection
     */
    public static function open();

    /**
     * Fire a sql statement
     * @param string $sqlStmt
     */
    public static function query($sqlStmt);

    /**
     * Close the database connection
     */
    public static function close();

    /**
     * Get the result as an array
     */
    public static function getResult();
    
    /**
     * Get the number of records
     */
    public static function getNumRows();
    
    /**
     * Get database errors
     */
    public static function error();
}

?>
