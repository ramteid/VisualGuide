<?php

/**
 * Final Class: Coord
 *
 * @author DK-Group Augsburg students
 * @version 1.0
 * @since 2012-09-08 / FW
 * 
 * Last Modification: 2012-09-14 / DS
 */
final class Coordinates {

    /**
     * The x-coordinate
     * @var double
     */
    private $coordX;

    /**
     * The y-coordinate
     * @var double
     */
    private $coordY;

    /**
     * The x and y coordinate of the corner point
     * @param int $coordX
     * @param int $coordY
     */
    public function __construct($coordX = 0, $coordY = 0) {
        $this->coordX = $coordX;
        $this->coordY = $coordY;
    }

    /**
     * Get the x corner point coordinate
     * @return double x-coordinate
     */
    public function getCoordX() {
        return $this->coordX;
    }

    /**
     * Set the x corner point coordinate
     * @param double $coordX
     */
    public function setCoordX($coordX) {
        $this->coordX = $coordX;
    }

    /**
     * Get the y corner point coordinate
     * @return double
     */
    public function getCoordY() {
        return $this->coordY;
    }

    /**
     * Set the y corner point coordinate
     * @param double $coordY
     */
    public function setCoordY($coordY) {
        $this->coordY = $coordY;
    }

    /**
     * Get the coordinates as string
     * @return string The coordinates
     */
    public function __toString() {
        $newLine = "\r\n";
        $retVal = "X-coord: " . $this->coordX . $newLine;
        $retVal .= "Y-coord: " . $this->coordY;
        return $retVal;
    }

}

?>
