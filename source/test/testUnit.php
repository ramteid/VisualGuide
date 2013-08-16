<?php
require_once 'global.inc.php';
require_once PATH_CLASSES . 'TransferCalls.class.php';
require_once PATH_CLASSES . 'Room.class.php';
require_once PATH_CLASSES . 'Size.class.php';

/**
 * Description of test
 *
 * @author Mueller Michael
 */

/**
 * Test the class TranferCall
 * 
 * @author DK Group
 * @version 1.0
 * @since 2012-09-26 / MM
 * 
 * Last Modification: 2012-09-26 / MM
 */
class Test_TransferCall {
    public function __construct() {
        
    }
   /**
     * Test the isKeyValid method in TransferCall 
     * @return True
     */
    public function isValid($key) {
        $tC = new TransferCall();
        return $tC->isKeyValid($key);
    }
     /**
     * Test the setPosition method in TransferCall 
     * @return False
     */  
    public function setPosition($key, $data) {
        $tC = new TransferCall();
        return $tC->set($key, $data);
        
    }
    
    public function delFurniture($key, $data) {
    $tC = new TransferCall();
    return $tC->set($key, $data);
        
    }
    
       /**
     * Test the addCategories method in TransferCall 
     * @return True
     */
    public function addCateories($key, $data) {
        $tC = new TransferCall();
        return $tC->set($key, $data);
        
    }
       /**
     * Test the rem method in TransferCall 
     * @return True
     */
    public function delFurniture($key, $data){
        $tC = new TransferCall();
        return $tC->set($key, $data);
    }
}
/**
 * Test the class Room
 * 
 * @author DK Group
 * @version 1.0
 * @since 2012-09-26 / MM
 * 
 * Last Modification: 2012-09-26 / MM
 */

class Test_Room {
     /**
     * Test the setName method in Room 
     * @return True
     */
    public function get_Room($name) {
        $room = new Room();
        $room->setName($name);
        return $room->getName();
        
    }
    /**
     * Test the getSize method in Room
     * @return True
     */
    public function getSize($size) {
        $room = new Room();
        $room->setSize($size);
        return $room->getSize();
    }
    
}
    /*
     * Test the class Size
     * @author DK Group
     * @version 1.0
     * @since 2012-09-26 / MM
     * 
     */
class Test_Size {
    /**
     * Test the getHight method in Size 
     * @return True
     */
    public function test_getHight($height) {
        $size = new Size();
        $size->setHeight($height);
        return $size->getHeight();
    }
     /**
     * Test the getWidth method in Size 
     * @return True
     */
    public function test_getWidth($height) {
        $size = new Size();
        $size->setWidth($height);
        return $size->getWidth();
        
    }
    
}

//test1
$test1 = new test_TransferCall();
$expect = True; 
$response = $test1->isValid('addFurniture'); 
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test2
$test2 = new test_TransferCall();
$expect = False;
$response = $test2->setPosition('setPosition',0); 
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test3
$test3 = new test_TransferCall();
$expect = True;
$response = $test3->addCateories('addCategories','{"furniture":{"coordX":251,"coordY":147,"room":"D203"},"categories":["61.01","33.00"]}');
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test4 Failed, because not known 
$test4 = new test_TransferCall();
$expect = True;
$response = $test4->delFurniture('deleteFurniture','{"coordX":80,"coordY":30,"room":"D203"}');
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test5
$test5 = new test_Room();
$expect = 'shelf1';
$response = $test5->get_Room('shelf1');
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test6
$test6 = new test_Room();
$expect = 12;
$response = $test5->get_Room(12);
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test7
$test7 = new Test_Size();
$expect = 5;
$response = $test7->test_getHight(5);
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");

//test8
$test8 = new Test_Size();
$expect = 30;
$response = $test8->test_getHight(30);
echo "isValid " . (($expect == $response) ?  "OK" : "FALSE");


?>
