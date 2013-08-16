<?php

/* * *******************************************
 * Global include data
 * ****************************************** */
//require_once '../global.inc.php';

/* * *******************************************
 * Classes
 * ****************************************** */
require_once PATH_CLASSES . 'Room.class.php';
require_once PATH_CLASSES . 'Size.class.php';
require_once PATH_CLASSES . 'Coordinates.class.php';

/**
 * Final Class: Furniture
 *
 * @author DK-Group Augsburg students
 * @version 1.0
 * @since 2012-09-07
 * 
 * Last modification: 2012-09-12 / FW
 */
final class Furniture {
    /**
     * The furniture name
     * @var string
     */
    private $model_name;
    
    /**
     * The furniture size
     * @var Size An instance of the class Size
     */
    private $size;
    
    /**
     * The furniture coord
     * @var Coordinates 
     */
    private $coordinates;
    
    /**
     * The furniture image name
     * @var string
     */
    private $image;

    /**
     * The location of the furniture
     * @var Room An instance of the class Room
     */
    private $room;
    
    /**
     * A boolean value if the furniture is a shelf or a dish
     * @var bool
     */
    private $isShelf;
    
    /**
     * An array of category numbers
     * @var array
     */
    private $category_numbers;
    
    /**
     * Default constructor
     */
    public function __construct() {
        $this->category_numbers = array();
        $this->coordinates = null;
        $this->image = "";
        $this->isShelf = false;
        $this->model_name = "";
        $this->room = null;
        $this->size = null;
    }
    
    /**
     * Get the category numbers
     * @return An array of category numbers
     */	
     public function getCategoryNumbers() {
            return $this->category_numbers;
    }
    
    /**
     * Set the category numbers of this furniture
     * @param Array $array An array of category numbers
     */
    public function setCategoryNumbers($array) {
            $this->category_numbers = $array;
    }
        
    /**
     * Add category numbers to the existing array
     * Both arrays must have numeric keys!
     * @param Array $array An array of category numbers
     */
    public function addCategoryNumbers($array) {
            $this->category_numbers = array_merge( $this->category_numbers, $array );
    }
    
    /**
     * Get the coords 
     * @return Coordinates The coords of the furniture
     */
    public function getCoordinates() {
            return $this->coordinates;
    }
    
    /**
     * Set the coords of the furniture
     * @param Coordinates $coord
     */
    public function setCoordinates(Coordinates $coord) {
            $this->coordinates = $coord;
    }
    
    /**
     * Get the image
     * @return string The image name
     */
    public function getImage() {
            return $this->image;
    }
    
    /**
     * Set the image name
     * @param string $image
     */
    public function setImage($image) {
            $this->image = $image;
    }
    
    /**
     * Get the room instance
     * @return room An instance of the class Room
     */
    public function getRoom() {
            return $this->room;
    }
    
    /**
     * Set the room instance
     * @param Room An instance of the class Room
     */
    public function setRoom(Room $room) {
            $this->room = $room;
    }
    
    /**
     * Get a boolean value if the current furniture is a shelf or not
     * @return bool 
     */
    public function getIsShelf() {
            return $this->isShelf;
    }
    
    /**
     * Set the bool value if the current furniture is a shelf
     * @param bool $isShelf
     */
    public function setIsShelf($isShelf) {
            $this->isShelf = $isShelf;
    }
    
    /**
     * Get the furniture's Size instance
     * @return Size An instance of the class Size
     */
    public function getSize() {
            return $this->size;
    }
    
    /**
     * Set the furniture's Size instance
     * @param Size An instance of the class Size
     */
    public function setSize(Size $size) {
            $this->size = $size;
    }
    
    /**
     * Get the furniture model's name
     * @return string
     */
    public function getName() {
            return $this->model_name;
    }
    
    /**
     * Set the name of the furniture model
     * @param string Name
     */
    public function setName($name) {
            $this->model_name = $name;
    }
    
    /**
     * Validates the furniture's coordinates against the furniture & room dimensions
     * @return boolean Validation
     */
    public function validateCoordinates() {
        $roomSizeInstance = $this->room->getSize();
        $roomWidth = $roomSizeInstance->getWidth();
        $roomHeight = $roomSizeInstance->getHeight();
        $furnitureWidth = $this->size->getWidth();
        $furnitureHeight = $this->size->getHeight();
        $coordX = $this->coordinates->getCoordX();
        $coordY = $this->coordinates->getCoordY();
        if (!is_numeric($coordX))
            return false;
        if (!is_numeric($coordY))
            return false;
        if ($coordX < 0)
            return false;
        if ($coordY < 0)
            return false;
        if ($coordX + $furnitureWidth > $roomWidth)
            return false;
        if ($coordY + $furnitureHeight > $roomHeight)
            return false;
        return true;
    }
    
    /**
     * Get the current object as string
     * @return string
     */
    public function __toString() {
       $newLine = "\r\n";
        $retVal = "Name: " . $this->model_name . $newLine;
      //  $retVal .= "Category No: " . $this->category_numbers . $newLine;
        $retVal .= $this->coordinates->__toString() . $newLine;
        $retVal .= "Image: " . $this->image . $newLine;
        $retVal .= "Is shelf: " . $this->isShelf . $newLine;
        $retVal .= $this->room->getName(). $newLine;
        $retVal .= $this->size;
        return $retVal;
    }
}

?>