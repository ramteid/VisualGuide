<?php

/* * *****************************
 * Global
 * **************************** */
//require_once '../global.inc.php';

/* * *****************************
 * Classes
 * **************************** */
require_once PATH_CLASSES . 'Transfer.class.php';
require_once PATH_CLASSES . 'FurnitureManager.class.php';

/* * *****************************
 * Lib
 * **************************** */

/**
 * Class: TransferCall
 *
 * @author DK Group
 * @version 1.1
 * @since 2012-09-24 / FW
 * 
 * Last modification: 2012-09-24 / FW
 */
class TransferCall {

    /**
     * The Transfer class instance 
     * @var Transfer 
     */
    private $transfer;

    /**
     * The loggedin variable
     * @var bool
     */
    private $loggedIn;

    /**
     * Default constructor
     * @param string $postParam The post parameter which is given
     */
    public function __construct() {
        $this->transfer = new Transfer();
        $this->initSession();
    }

    /**
     * Initialize the session
     * @return boolean
     */
    private function initSession() {
        session_set_cookie_params(EXPIRE_TIME);
        session_start();
        if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
            return ($this->loggedIn = true);
        }
        return ($this->loggedIn = false);
    }

    /**
     * Validate the given key
     * @param string $key
     * @return boolean true if the key is valid, otherwise false
     */
    private function isKeyValid($key) {
        if (empty($key)) {
            return FALSE;
        }
        $allKeys = array("getDistinctFurnitures", "addCategories",
            "addFurniture", "getTargetCoordinates", "getRooms", "getAllFurnitures",
            "deleteFurniture", "getSessionTimes", "getRoomInfo", "getCategories", "setPosition");
        return in_array($key, $allKeys);
    }

    /**
     * Set data in the database
     * @param string $key
     * @param string $data
     */
    public function set($key, $data = "") {
        if (!empty($key) && $this->isKeyValid($key)) {
            $jsonData = json_decode($data);
            if (!empty($jsonData)) {
                switch ($key) {
                    case "setPosition":
                        $oldFurniture = new Furniture();
                        $oldFurniture->setCoordinates(new Coordinates($jsonData->furniture->coordX, $jsonData->furniture->coordY));
                        $oldFurniture->setRoom(new Room($jsonData->furniture->room));

                        $newFurniture = new Furniture();
                        $newFurniture->setCoordinates(new Coordinates($jsonData->coordX, $jsonData->coordY));
                        $newFurniture->setName($jsonData->model_name);
                        return $this->transfer->updateFurniture($oldFurniture, $newFurniture);
                        /*
                          'furniture': {
                          'coordX': furniture.data('coordx'),
                          'coordY': furniture.data('coordy'),
                          'room': furniture.data('room')
                          },
                          'coordX': furniture.css('left'),
                          'coordY': furniture.css('top'),
                          'model_name': furniture.data('modelname')
                          }
                         */
                }
            }
        }
        return false;
    }

    /**
     * Add the given data to the database
     * @param string $key The needed key
     * @param string $data Data to add in the database
     * @return boolean Return true on success, otherwise false
     */
    public function add($key, $data) {
        if (!empty($key) && $this->isKeyValid($key) && !empty($data)) {
            $jsonData = json_decode($data);
            if (!empty($jsonData) && $this->loggedIn) {
                switch ($key) {
                    case "addCategories":
                        return $this->transfer->addCategories($jsonData->furniture->coordX, $jsonData->furniture->coordY, $jsonData->furniture->room, $jsonData->categories);

                    case "addFurniture":
                        return $this->transfer->addFurnitureFromJSON($jsonData);
                }
            }
        }
        return false;
    }

    /**
     * Remove the data from the database
     * @param string $key
     * @param string $data
     * @return boolean Return true on success, otherwise false
     */
    public function remove($key, $data) {
        if (!empty($key) && !empty($data) && $this->isKeyValid($key)) {
            $jsonData = json_decode($data);
            if (!empty($jsonData)) {
                switch ($key) {
                    case "deleteFurniture":
                        return $this->transfer->deleteFurniture($jsonData->coordX, $jsonData->coordY, $jsonData->room);
                }
            }
        }
        return false;
    }

    /**
     * Get data from the database
     * @param string $key The key which is needed
     * @param string $room The room number
     * @param string $category The category number
     * @return string 
     */
    public function get($key, $data = "", $room = "", $category = "") {
        if (!empty($key) && $this->isKeyValid($key)) {
            switch ($key) {
                case "getDistinctFurnitures":
                    if ($this->loggedIn) {
                        return $this->transfer->getFurnitureModels();
                    } else {
                        return -1;
                    }

                case "getTargetCoordinates":
                    return $this->transfer->getTargetCoordinates($category);

                case "getRooms":
                    return $this->transfer->getAllRooms();

                case "getRoomInfo":
                    return $this->transfer->getRoomInfo($room);

                case "getAllFurnitures":
                    return $this->transfer->getAllFurnituresForJSON($room);

                case "getSessionTimes":
                    $retArr['expireTime'] = EXPIRE_TIME;
                    $retArr['expireWarningTime'] = EXPIRE_WARNING_TIME;
                    return $retArr;

                case "getCategories":
                    return $this->transfer->getCategories($data);
            }
        }
        return "";
    }

    /**
     * Try to choose the correct method
     * @param string $key The key which is needed
     * @param string $data The data which should add in the database
     * @param string $room The room number
     * @param string $category The category number
     * @return mixed String or boolean 
     */
    public function autochoose($key, $data = "", $room = "", $category = "") {
        if ($this->isKeyValid($key)) {    
            $k = substr($key, 0, 3);
            if (strlen($k) == 3) {
                switch ($k) {
                    case "add":
                        return $this->add($key, $data);

                    case "get":
                        return $this->get($key, $data, $room, $category);

                    case "del":
                        return $this->remove($key, $data);
                        
                        case "set":
                            return $this->set($key, $data);

                    default:
                        break;
                }
            }
        } else {
            return "INVALID KEY PARAM";
        }
    }

}

?>
