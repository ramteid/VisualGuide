<?php

/* * **********************************************
 * Lib
 * ********************************************* */
require_once PATH_LIB . 'database/MysqlDatabaseConnection.class.php';

/* * **********************************************
 * Classes
 * ********************************************* */
require_once PATH_CLASSES . 'Furniture.class.php';

/* * **********************************************
 * Exceptions
 * ********************************************* */
require_once PATH_EXCEPTIONS . "FurnitureManagerException.class.php";

/**
 * Class: FurnitureManager
 *
 * @author DK Group
 * @version 1.01
 * @since 2012-09-07 
 * 
 * Last modification: 2012-09-27 / DS
 */
class FurnitureManager {

    /**
     * Add a furniture to the database
     * @param Furniture $furniture
     * @return boolean
     * @throws Exception Thrown if the $furniture is null
     */
    public function add(Furniture $furniture) {
        if (empty($furniture)) return false;

        $coords = $furniture->getCoordinates();
        $room = $furniture->getRoom();
         
        if (empty($coords)) return false;
        if (empty($room)) return false;
        if (!$this->isAvailable($coords, $room)) return false;

        try {
            $sqlFurn = sprintf("INSERT INTO furniture(x_coord, y_coord, room_name, model_name)
					VALUES(%s, %s, '%s', '%s')", mysql_real_escape_string($furniture->getCoordinates()->getCoordX()), mysql_real_escape_string($furniture->getCoordinates()->getCoordY()), mysql_real_escape_string($furniture->getRoom()->getName()), mysql_real_escape_string($furniture->getName())
            );
            MysqlDatabaseConnection::open();
            MysqlDatabaseConnection::query($sqlFurn);
            $succeeded = MysqlDatabaseConnection::getNumAffectedRows();
            if ($succeeded != 1) {
                return false;
            }
            if ($furniture->getCategoryNumbers()) {
                $categories = $furniture->getCategoryNumbers();
                foreach ($categories as &$cat) {
                    $sqlCat = sprintf("INSERT INTO categories (category_number)
                                        VALUES ('%s')", mysql_real_escape_string($cat));

                    MysqlDatabaseConnection::query($sqlCat);
                    $succeeded = MysqlDatabaseConnection::getNumAffectedRows();
                    if ($succeeded != 1) {
                        return false;
                    }

                    $sqlCatFur = sprintf("INSERT INTO cat_fur (category_number, x_coord, y_coord, room_name)
                                        VALUES ('%s', %s, %s, %s)", mysql_real_escape_string($cat), mysql_real_escape_string($furniture->getCoordinates()->getCoordX()), mysql_real_escape_string($furniture->getCoordinates()->getCoordY()), mysql_real_escape_string($furniture->getRoom()->getName())
                    );

                    MysqlDatabaseConnection::query($sqlCatFur);
                    $succeeded = MysqlDatabaseConnection::getNumAffectedRows();
                    if ($succeeded != 1) {
                        return false;
                    }
                }
            }
            MysqlDatabaseConnection::close();
            return true;
        } catch (FurnitureManagerException $ex) {
            return false;
        }
    }

    /**
     * Update an existing furniture in the database
     * @param int $furnitureId
     * @param Furniture $data
     * @return boolean True on success, false on failure
     */
    public function update(Furniture $oldFurniture, Furniture $newFurniture) {
        if (!empty($oldFurniture) && !empty($newFurniture)) {
            try {
                $oldXCoord = $oldFurniture->getCoordinates()->getCoordX();
                $oldYCoord = $oldFurniture->getCoordinates()->getCoordY();
                $oldRoomName = $oldFurniture->getRoom()->getName();

                $newXCoord = $newFurniture->getCoordinates()->getCoordX();
                $newYCoord = $newFurniture->getCoordinates()->getCoordY();
                $newModelName = $newFurniture->getName();
                $fur = sprintf("UPDATE furniture SET 
                                x_coord = %s, 
                                y_coord = %s, 
                                model_name = '%s' 
                                WHERE x_coord = %s 
                                AND y_coord = %s 
                                AND room_name = '%s'", 
                        mysql_real_escape_string($newXCoord), 
                        mysql_real_escape_string($newYCoord), 
                        mysql_real_escape_string($newModelName), 
                        mysql_real_escape_string($oldXCoord), 
                        mysql_real_escape_string($oldYCoord), 
                        mysql_real_escape_string($oldRoomName));
                
                $catFur = sprintf("UPDATE cat_fur SET 
                                x_coord = %s, 
                                y_coord = %s, 
                                WHERE x_coord = %s 
                                AND y_coord = %s 
                                AND room_name = '%s'", 
                        mysql_real_escape_string($newXCoord), 
                        mysql_real_escape_string($newYCoord), 
                        mysql_real_escape_string($oldXCoord), 
                        mysql_real_escape_string($oldYCoord), 
                        mysql_real_escape_string($oldRoomName));

                MysqlDatabaseConnection::query($fur);
                MysqlDatabaseConnection::query($catFur);
                MysqlDatabaseConnection::close();

                return true;
            } catch (FurnitureManagerException $ex) {
                return false;
            }
        }
        return false;
    }

    /**
     * Delete an existing furniture in the database
     * @param Coordinates $coord Instance of the Coordinates class
     * @param Room $room Instance of the Coordinates class
     * @return boolean True on success, false on failure
     */
    public function delete(Coordinates $coord, Room $room) {
        if (empty($coord) || empty($room)) {
            return false;
        }

        $deleteCategory = sprintf("DELETE FROM category WHERE category_number IN 
                                   (SELECT category_number FROM cat_fur 
                                   WHERE x_coord=%s AND y_coord=%s AND room_name='%s')", mysql_real_escape_string($coord->getCoordX()), mysql_real_escape_string($coord->getCoordY()), mysql_real_escape_string($room->getName()));

        $deleteFurniture = sprintf("DELETE FROM furniture WHERE x_coord=%s 
                                    AND y_coord=%s AND room_name='%s';", 
									mysql_real_escape_string($coord->getCoordX()), 
									mysql_real_escape_string($coord->getCoordY()), 
									mysql_real_escape_string($room->getName()));

        $deleteCatFur = sprintf("DELETE FROM cat_fur WHERE x_coord=%s 
                                 AND y_coord=%s AND room_name='%s';", 
									mysql_real_escape_string($coord->getCoordX()), 
									mysql_real_escape_string($coord->getCoordY()), 
									mysql_real_escape_string($room->getName()));
        try {
            MysqlDatabaseConnection::query($deleteCategory);
            MysqlDatabaseConnection::query($deleteFurniture);
            $affectedRows = MysqlDatabaseConnection::getNumAffectedRows();
            MysqlDatabaseConnection::query($deleteCatFur);
            MysqlDatabaseConnection::close();
            if ($affectedRows > 1) {
                return false;
            } elseif ($affectedRows < 1) {
                return false;
            }
            return true;
        } catch (FurnitureManagerException $ex) {
//            echo $ex->getMessage();
            return false;
        }
    }

    /**
     * Get a furniture
     * @param Coordinates $coord The coords of the furniture
     * @param Room $room The room of the furniture
     */
    public function get(Coordinates $coord, Room $room) {
        if (!empty($coord) && !empty($room)) {
            try {
                $sql = sprintf("SELECT f.*, cf.category_number, m.fwidth, m.fheight, m.image, m.is_shelf, r.rheight, r.rwidth FROM furniture AS f 
                                INNER JOIN cat_fur AS cf ON (cf.x_coord = f.x_coord AND cf.y_coord = f.y_coord AND cf.room_name = f.room_name) 
                                INNER JOIN model AS m ON f.model_name = m.model_name 
                                INNER JOIN room AS r ON r.room_name = f.room_name 
                                WHERE f.x_coord = %s 
                                AND f.y_coord = %s 
                                AND f.room_name = '%s'", mysql_real_escape_string($coord->getCoordX()), mysql_real_escape_string($coord->getCoordY()), mysql_real_escape_string($room->getName()));

                MysqlDatabaseConnection::query($sql);
                $dbResult = MysqlDatabaseConnection::getResult();
                if (empty($dbResult)) {
                    return NULL;
                }

                $furniture = new Furniture();
                $furniture->setCoordinates($coord);
                $furniture->setImage($dbResult["image"]);
                $furniture->setIsShelf($dbResult["is_shelf"]);
                $room = new Room();
                $room->setName($room->getName());
                $room->setSize(new Size($dbResult["rwidth"], $dbResult["rheight"]));
                $furniture->setRoom($room);
                $furniture->setName($dbResult["model_name"]);
                $size = new Size($dbResult["fwidth"], $dbResult["fheight"]);
                $furniture->setSize($size);

                if ($dbResult["is_shelf"]) {
                    $categories = $this->getCategoriesFromShelf(new Coordinates($coord->getCoordX(), $coord->getCoordY()), new Room($room->getName()));
                    $furniture->setCategoryNumbers($categories);
                }

                return $furniture;
            } catch (FurnitureManagerException $ex) {
                return null;
            }
        }
        return null;
    }

    /**
     * Check if the location is free
     * @param Coordinates $coord
     * @param Room $room
     */
    public function isAvailable(Coordinates $coord, Room $room) {
        $res = $this->get($coord, $room);
        return ((empty($res)) ? true : false);
    }

    /**
     * Get a list of furniture elemens
     * @return array An array with all furnitures
     */
    public function getAll(Room $room) {
        if (empty($room)) {
            return null;
        }

        $roomQuery = "";
        $room_name = $room->getName();
        if ($room_name) {
            $roomQuery = "WHERE f.room_name = '$room_name'";
        }

        $query = sprintf("SELECT f.* , m.fwidth, m.fheight, m.image, m.is_shelf, r.rheight, r.rwidth
                                FROM furniture AS f 
                                INNER JOIN model AS m ON f.model_name = m.model_name 
                                INNER JOIN room AS r ON r.room_name = f.room_name 
                                $roomQuery");

        try {
            $furnitureElements = array();

            MysqlDatabaseConnection::query($query);
            $results = MysqlDatabaseConnection::getResult();
            foreach ($results as &$result) {
                $furnitureObj = new Furniture();
                $furnitureObj->setName($result["model_name"]);
                $furnitureObj->setCoordinates(new Coordinates($result["x_coord"], $result["y_coord"]));
                $furnitureObj->setSize(new Size($result["fwidth"], $result["fheight"]));
                $furnitureObj->setRoom(new Room($result["room_name"], new Size($result["rwidth"], $result["rheight"])));
                $furnitureObj->setImage($result["image"]);
                $furnitureObj->setIsShelf($result["is_shelf"]);
                if ($result["is_shelf"]) {
                    $categories = $this->getCategoriesFromShelf(new Coordinates($result["x_coord"], $result["y_coord"]), new Room($result['room_name']));
                    if (!empty($categories)) {
                        $furnitureObj->setCategoryNumbers($categories);
                    }
                }
                $furnitureElements[] = $furnitureObj;
            }
            MysqlDatabaseConnection::close();
            return $furnitureElements;
        } catch (FurnitureManagerException $ex) {
//            echo $ex->getMessage();
            return null;
        }
    }

    /**
     * Get all categorie numbers for a single shelf
     * Use with caution because it overwrites $result in MysqlDatabaseConnection.class.php
     * @param int $x_coord
     * @param int $y_coord
     * @param string $room_name
     * @return Array Array of category_numbers
     */
    public function getCategoriesFromShelf(Coordinates $coordinates, Room $room) {
        if (empty($coordinates) || empty($room)) {
            return null;
        }

        $room_name = $room->getName();
        $x_coord = $coordinates->getCoordX();
        $y_coord = $coordinates->getCoordY();

        $sql = sprintf("SELECT category_number FROM cat_fur 
                        WHERE x_coord = %s 
                        AND y_coord = %s 
                        AND room_name = '%s'", mysql_real_escape_string($x_coord), mysql_real_escape_string($y_coord), mysql_real_escape_string($room_name));

        try {
            $categories = array();
            MysqlDatabaseConnection::open();
            MysqlDatabaseConnection::query($sql);
            $results = MysqlDatabaseConnection::getResult();
            if ($results) {
                foreach ($results as &$result) {
                    $categories[] = $result['category_number'];
                }
                MysqlDatabaseConnection::close();
                return $categories;
            } else {
                MysqlDatabaseConnection::close();
            }
        } catch (FurnitureManagerException $ex) {
//            echo $ex->getMessage();
        }
        return null;
    }

    /**
     * Validate the model name
     * @param string $model_name
     * @return bool True if model_name exists, otherwise false
     */
    public function validateModelName($model_name) {
        $returnResult = false;
        if (!empty($model_name)) {
            $sql = sprintf("SELECT model_name FROM model WHERE model_name='%s'", mysql_real_escape_string($model_name));
            try {
                MysqlDatabaseConnection::open();
                MysqlDatabaseConnection::query($sql);
                $dbResult = MysqlDatabaseConnection::getResult();
                $returnResult = (empty($dbResult) ? false : true);
                MysqlDatabaseConnection::close();
            } catch (FurnitureManagerException $ex) {
                
            }
        }
        return $returnResult;
    }

}

?>