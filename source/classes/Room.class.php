<?php

/**
 * Final class: Room
 *
 * @author DK-Group Augsburg students
 * @version 1.0
 * @since 2012-09-07
 * 
 * Last Modification: 2012-09-12 / FW
 */
final class Room {

    /**
     * The name of the room
     * @var string
     */
    private $name;

    /**
     * The size of the room
     * @var Size Size in pixel
     */
    private $size;

    /**
     * The default constructor
     * @param string $name The name of the room e.g. D201
     * @param Size $size The size of the room in pixel
     */
    public function __construct($name = "", Size $size = NULL) {
        $this->name = $name;
        if ($size == NULL) {
            $this->size = new Size();
        } else {
            $this->size = $size;
        }
    }

    /**
     * Get the name of the room
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set the name of the room
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Get the size of the room
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Set the size of the room in Pixel
     * @param Size $size
     */
    public function setSize(Size $size) {
        $this->size = $size;
    }

    /**
     * Get the current object as string
     */
    public function __toString() {
        $newLine = "\r\n";
        $retVal = "Name: " . $this->name . $newLine;
        $retVal .= "Size: " . $this->size->toPoint();
        return $retVal;
    }

}

?>
