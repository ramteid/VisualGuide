<?php

/**
 * Class: LinkGenerator
 *
 * @author DK-Group Augsburg students
 * @version 1.3
 * @since 2012-09-08
 *
 * Last modification: 2012-09-21 / MM
 */
class LinkGenerator {
    /**
     * URL adress
     * @var String
     */

    const BASE_URL = 'visualManager.php';

    /**
     * The FurnitureManager object
     * @var FurnitureManager
     */
    public function __construct() {
        
    }

    /**
     * Create a link to the map
     * @return string
     * @note: return the Link to the Map
     */
    public function createLinkMap($title, $catNo) {
        if (($title != '') && ($catNo != '')) {
            $url = self::BASE_URL;
            $url .= "?title=" . $title;
            $url .= "&catNo=" . $catNo;
            $url .= "&display=map";
            return $url;
        }
        return "";
    }

    /**
     *
     * @param type $bookname
     * @return String
     * @note: return the Link to the QR-Code - Link, if the book exist.
     */
    public function createQRCode($title, $catNo) {
        if (($title != '') && ($catNo != '')) {
            $url = self::BASE_URL;
            $url .= "?title=" . $title;
            $url .= "&catNo=" . $catNo;
            $url .= "&display=qr";
            return $url;
        }
        return "";
    }

}