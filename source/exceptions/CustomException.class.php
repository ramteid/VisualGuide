<?php

/* * *******************************************
 * Interfaces
 * ****************************************** */
require_once PATH_INTERFACES . 'ICustomException.php';

/**
 * Class: CustomException
 *
 * @author DK Group
 * @version 1.0
 * @since 2012-09-19
 * 
 * Last modification: 2012-09-20 / FW
 */
class CustomException extends Exception implements ICustomException {


    /**
     * Default constructor
     * @param string $message 
     * @param int $code
     * @param object $previous
     * @param bool $logToDatabase if it is true, messages will written in the database
     */
    public function __construct($message = "", $code = 0, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the exception as html string
     */
    public function getHtmlString() {
        $newLine = "<br />";
        $retVal = "<b>Message: </b>" . $this->getMessage() . $newLine;
        $retVal .= "<b>Code: </b>" . $this->getCode() . $newLine;
        $retVal .= "<b>File: </b>" . $this->getFile() . $newLine;
        $retVal .= "<b>Line: </b>" . $this->getLine() . $newLine;
        $previous = $this->getPrevious();
        if (!empty($previous)) {
            $retVal .= "<b>Previous: </b>" . $previous . $newLine;
        }
        $retVal .= "<b>Trace: </b>" . $this->getTraceAsString() . $newLine;
        return $retVal;
    }

}

?>
