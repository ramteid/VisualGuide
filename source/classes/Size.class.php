<?php
/**
 * Final Class: Size
 * 
 * @author DK Group
 * @version 1.0
 * @since 2012-09-12
 * 
 * Last modification: 2012-09-12 / FW
 */
final class Size {
    
    /**
     * Width of the object
     * @var int
     */
    private $width;
    
    /**
     * Height of the object
     * @var int
     */
    private $height;
    
    /**
     * Default constructor
     * @param int $width Width in pixel
     * @param int $height Height in pixel
     */
    public function __construct($width = -1, $height = -1) {
        if(!is_numeric($width) || !is_numeric($height)) {
                throw new Exception("Invalid width or height");
        }
        $this->width = $width;
        $this->height = $height;
    }
    
    /**
     * Get the width in pixel
     */
    public function getWidth() {
            return $this->width;
    }
    
    /**
     * Set the width in pixel
     * @param int $width
     */
    public function setWidth($width) {
            $this->width = $width;
    }
    
    /**
     * Get the height in pixel
     */
    public function getHeight() {
            return $this->height;
    }
    
    /**
     * Set the height in pixel
     * @param int $height
     */
    public function setHeight($height) {
            $this->height = $height;
    }
    
    /**
     * Get the width and height as a string
     */
    public function __toString() {
            $newLine = "\r\n";
            $retVal = "Width: " . $this->width . $newLine;
            $retVal .= "Height: " . $this->height;
            return $retVal;
    }
    
    /**
     * Get the size as point e.g. 300x400
     */
    public function toPoint() {
            return $this->width . "x" . $this->height;
    }
}