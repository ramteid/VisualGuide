<?php

/* * ******************************
 * Global
 * ***************************** */
require_once 'global.inc.php';

/* * ******************************
 * Classes
 * ***************************** */
require_once 'classes/TransferCalls.class.php';

/**
 * TransferManager
 * Manage and handles calls
 * 
 * @author DK Group
 * @version 1.0
 * @since 2012-09-24 / FW
 * 
 * Last modification: 2012-09-24 / FW
 */
if (isset($_POST["key"])) {
    $key = $_POST["key"];
    $room = (isset($_POST["room"])) ? $_POST["room"] : "";
    $category = (isset($_POST["category"])) ? $_POST["category"] : "";
    $data = (isset($_POST["data"])) ? $_POST["data"] : "";
    
    $call = new TransferCall();
    $response = $call->autochoose($key, $data, $room, $category);

    // Ausgabe zwingend erforderlich, damit jQuery die Antwort abgreifen kann!
    echo json_encode($response);

    // oder auch so aufrufbar!
    // POST
    //echo json_encode($call->POST($_POST["key"], $room, $category));
    // DELETE
    //echo json_encode($call->POST($_POST["key"], $_POST["deleteData"]));
    // ADD
    // echo json_encode($call->add($_POST["key"], $_POST["data"]));
} else {
    echo "500 KEY PARAM NOT SET";
}
?>
