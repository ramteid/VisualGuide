<?php

/* * *********************************
 * Global include data
 * ******************************** */
//require_once '../global.inc.php';

/* * *******************************************
 * Classes
 * ****************************************** */
require_once PATH_CLASSES . 'Furniture.class.php';
require_once PATH_CLASSES . 'Coordinates.class.php';
require_once PATH_CLASSES . 'Room.class.php';
require_once PATH_CLASSES . 'Size.class.php';
require_once PATH_CLASSES . 'FurnitureManager.class.php';

/* * *******************************************
 * Exceptions
 * ****************************************** */
require_once PATH_EXCEPTIONS . 'TransferException.class.php';

/* * *******************************************
 * Lib
 * ****************************************** */
require_once PATH_LIB . 'database/MysqlDatabaseConnection.class.php';

/**
 * Class: FurnitureTransfer
 * Provides methods for data transfer between JavaScript/JQuery and PHP
 * 
 * @author DK Group
 * @version 1.0
 * @since 2012-09-14 / DS
 * 
 * Last modification: 2012-09-27 / DS
 */
class Transfer {

    /**
     * An instance of the FurnitureManager
     * @var FurnitureManager 
     */
    private $manager;

    /**
     * Default constructor
     */
    public function __construct() {
        $this->manager = new FurnitureManager();
    }

    /**
     * Get the rooms from the database
     * @return Array $rooms Array of room_name and size
     */
    public function getAllRooms() {
        try {
            MysqlDatabaseConnection::open();
            MysqlDatabaseConnection::query("SELECT * FROM room");
            $result = MysqlDatabaseConnection::getResult();
            MysqlDatabaseConnection::close();
            return $result;
        } catch (TransferException $ex) {
            return NULL;
        }
    }

    /**
     * Get the information of a room
     * @param string $roomName
     * @return array Return an Array of rooms
     */
    public function getRoomInfo($roomName) {
        if (!empty($roomName)) {
            $sql = sprintf("SELECT * FROM room WHERE room_name = '%s'", mysql_real_escape_string($roomName));
            try {
                MysqlDatabaseConnection::open();
                MysqlDatabaseConnection::query($sql);
                $result = MysqlDatabaseConnection::getResult();
                MysqlDatabaseConnection::close();
                return $result;
            } catch (TransferException $ex) {
                
            }
        }
        return null;
    }

    /**
     * Takes the identifier of an furniture and returns the assigned categories
     * @param int $coordX
     * @param int $coordY
     * @param string $room_name
     * @return array Return an Array of category numbers
     */
    public function getCategories($data) {  //  {"coordX":211,"coordY":147,"room":"D203"}[{"category_number":"69"}]
        $jsonData = json_decode($data);
        if (empty($jsonData)) {
            return null;
        } elseif (!is_numeric($jsonData->coordX) || !is_numeric($jsonData->coordY) || empty($jsonData->room)) {
            return null;
        } else {
            $coordX = $jsonData->coordX;
            $coordY = $jsonData->coordY;
            $room_name = $jsonData->room;
            try {
                MysqlDatabaseConnection::open();
                $sql = sprintf("SELECT category_number FROM cat_fur 
                WHERE x_coord = %s AND y_coord = %s AND room_name = '%s'", $coordX, $coordY, $room_name);
                MysqlDatabaseConnection::query($sql);
                $categories = MysqlDatabaseConnection::getResult();
                MysqlDatabaseConnection::close();
                return $categories;
            } catch (TransferException $ex) {
                return null;
            }
        }
    }

    /**
     * Creates an instance of an Furniture and adds it to the database.
     * @param Array $furnitureData
     * @return boolean Success
     */
    public function addFurnitureFromJSON($furnitureData) {
        if (empty($furnitureData)) {
            throw new TransferException("FurnitureData was null");
        }

        $roomName = (string) $furnitureData->room; // e.g. D201
        $coordX = (int) $furnitureData->coordX;
        $coordY = (int) $furnitureData->coordY;
        $modelName = (string) $furnitureData->model_name; // e.g. desk1

        if (empty($roomName) || empty($modelName) || $coordX < 0 || $coordY < 0) {
            throw new Exception("Invalid furniture data given");
        }

        $furniture = new Furniture();
        $furniture->setRoom(new Room($roomName));
        $furniture->setCoordinates(new Coordinates($coordX, $coordY));
        $furniture->setName($modelName);

        return $this->manager->add($furniture);
    }

    /**
     * Get all furnitures of a room from the database
     * @param string $room_name
     * @param boolean $distinct If set, each furniture type will be returned only once
     * @return array $furnitures
     */
    public function getAllFurnituresForJSON($room_name = "") {
        $allFurnitures = $this->manager->getAll(new Room($room_name));

        $furnitures = Array();
        foreach ($allFurnitures as &$furnitureInstance) {
            $x_coord = $furnitureInstance->getCoordinates()->getCoordX();
            $y_coord = $furnitureInstance->getCoordinates()->getCoordY();
            $room_name = $furnitureInstance->getRoom()->getName();
            $furnitures[$x_coord . ',' . $y_coord . ',' . $room_name]['model_name'] = $furnitureInstance->getName();
            $furnitures[$x_coord . ',' . $y_coord . ',' . $room_name]['fwidth'] = $furnitureInstance->getSize()->getWidth();
            $furnitures[$x_coord . ',' . $y_coord . ',' . $room_name]['fheight'] = $furnitureInstance->getSize()->getHeight();
            $furnitures[$x_coord . ',' . $y_coord . ',' . $room_name]['image'] = $furnitureInstance->getImage();
            $furnitures[$x_coord . ',' . $y_coord . ',' . $room_name]['is_shelf'] = $furnitureInstance->getIsShelf();
            if ($furnitureInstance->getIsShelf()) {
                $categories = $furnitureInstance->getCategoryNumbers();
                if (!empty($categories)) {
                    $furnitures[$x_coord . ',' . $y_coord . ',' . $room_name]['category_numbers'] = implode(",", $categories);
                }
            }
        }
        return $furnitures;
    }

    /**
     * Delete furniture
     * @param int $coordX
     * @param int $coordY
     * @param string $room
     * @return boolean
     */
    public function deleteFurniture($coordX, $coordY, $room) {
        //{"coordX":20,"coordY":20,"room":"D203"}
        return $this->manager->delete(new Coordinates($coordX, $coordY), new Room($room));
    }

    /**
     * Get all the furniture models from the database
     * @return array Array of furniture models
     */
    public function getFurnitureModels() {
        $sql = "SELECT * FROM model;";
        try {
            MysqlDatabaseConnection::open();
            MysqlDatabaseConnection::query($sql);
            $result = MysqlDatabaseConnection::getResult();
            MysqlDatabaseConnection::close();
            return (($result === false) ? null : $result);
        } catch (TransferException $ex) {
            return null;
        }
    }

    /**
     * Calculates the coordinates to draw the X
     * @author DS
     * @param string $category_number The category number
     * @return Array 2-dimensional array with sets of 'x_coord','y_coord','room_name'
     */
    public function getTargetCoordinates($category_number) {
        $sql = "SELECT cf.x_coord, cf.y_coord, m.fwidth, m.fheight, r.room_name FROM cat_fur AS cf 
            INNER JOIN furniture AS f ON  (cf.x_coord = f.x_coord AND cf.y_coord = f.y_coord AND cf.room_name = f.room_name)
            INNER JOIN model AS m ON m.model_name = f.model_name
            INNER JOIN room AS r ON r.room_name = cf.room_name
            WHERE cf.category_number = '$category_number'";

        try {
            MysqlDatabaseConnection::open();
            MysqlDatabaseConnection::query($sql);
            $result = MysqlDatabaseConnection::getResult();

            $picXSizeX = 20;
            $picXSizeY = 19;
            $coordinates = Array();
            foreach ($result as &$coordRoom) {
                $tmp = Array();
                $tmp['x'] = (int) ($coordRoom['x_coord'] + ($coordRoom['fwidth'] / 2) - ($picXSizeX / 2));
                $tmp['y'] = (int) ($coordRoom['y_coord'] + ($coordRoom['fheight'] / 2) - ($picXSizeY / 2));
                $tmp['room_name'] = $coordRoom['room_name'];
                $coordinates[] = $tmp;
            }
            return $coordinates;
        } catch (TransferException $ex) {
            return null;
        }
    }

    /**
     * Add categories to the database
     * @param int $coordX The x-coordinate
     * @param int $coordY The y-coordinate
     * @param string $room The room number
     * @param array $categories Categories
     * @return boolean
     */
    public function addCategories($coordX, $coordY, $room, $categories) {
        if (empty($coordX) || empty($coordY) || empty($room)) {
            return false;
        }
        $succeeded = 0;
        if (is_array($categories)) {
            try {
                MysqlDatabaseConnection::open();
                $deleteCats = sprintf("DELETE FROM cat_fur WHERE x_coord = %s AND y_coord = %s AND room_name = '%s'", $coordX, $coordY, $room
                );
                MysqlDatabaseConnection::query($deleteCats);
                foreach ($categories as $cat) {
                    $checkCatSQL = sprintf("SELECT category_number FROM category WHERE category_number = '%s'", mysql_real_escape_string($cat));
                    MysqlDatabaseConnection::query($checkCatSQL);
                    $count = MysqlDatabaseConnection::getNumRows();
                    if ($count == 0) {
                        $catSQL = sprintf("INSERT INTO category (category_number) VALUES ('%s')", mysql_real_escape_string($cat));
                        MysqlDatabaseConnection::query($catSQL);
                    }
                    $catFurSQL = sprintf("INSERT INTO cat_fur (category_number, x_coord, y_coord, room_name) VALUES
                          ('%s', %s, %s, '%s')", mysql_real_escape_string($cat), mysql_real_escape_string($coordX), mysql_real_escape_string($coordY), mysql_real_escape_string($room));
                    MysqlDatabaseConnection::query($catFurSQL);
                    $succeeded += MysqlDatabaseConnection::getNumAffectedRows();
                }
            } catch (TransferException $ex) {
                return false;
            }
        }
        return(($succeeded == count($categories)) ? true : false);
    }

    /**
     * Update furnitures
     * @param Furniture $oldFurniture
     * @param Furniture $newFurniture
     * @return bool Return true on success, false on failure
     */
    public function updateFurniture(Furniture $oldFurniture, Furniture $newFurniture) {
        if (!empty($oldFurniture) && !empty($newFurniture)) {
            return $this->manager->update($oldFurniture, $newFurniture);
        }
        return false;
    }

}

?>
