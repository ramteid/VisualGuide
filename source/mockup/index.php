<?php
/* * ********************************
 * Global
 * ******************************* */
require_once 'mockup.global.inc.php';

/* * ********************************
 * Global
 * ******************************* */
require_once PATH_LIB . 'database/MysqlDatabaseConnection.class.php';

/* * ********************************
 * Classes
 * ******************************* */
require_once PATH_CLASSES . 'QRImage.class.php';
require_once PATH_CLASSES . 'LinkGenerator.class.php';

$bookResult = null;
if (isset($_POST["book_title"])) {
    $bookTitle = $_POST["book_title"];
    if (!empty($bookTitle)) {
        try {
            MysqlDatabaseConnection::open();

            $bookTitle = mysql_real_escape_string(strtolower($bookTitle));
            $sql = "SELECT * FROM book WHERE title LIKE '$bookTitle%'";

            MysqlDatabaseConnection::query($sql);
            $bookResult = MysqlDatabaseConnection::getResult();
            MysqlDatabaseConnection::close();
        } catch (Exception $ex) {
            $bookResult = null;
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>VIAUC Staging (Mockup Page)</title>

        <link rel="stylesheet" type="text/css" href="css/main.css" />
        <link rel="stylesheet" href="css/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" type="text/css" href="css/jquery.fancybox-1.3.4.css" />

        <script src="scripts/jquery-1.8.0.js"></script>
        <script src="scripts/ui/jquery.ui.core.js"></script>
        <script src="scripts/ui/jquery.ui.widget.js"></script>
        <script src="scripts/ui/jquery.ui.position.js"></script>
        <script src="scripts/ui/jquery.ui.autocomplete.js"></script>
        <script src="scripts/jquery.fancybox-1.3.4.pack.js"></script>
        <script src="scripts/jquery.blockUI.js"></script>

        <link rel="stylesheet" href="css/demos.css">

        <script type="text/javascript">
            $(document).ready(function() {
                /* $(".openbox-map").fancybox({
                    'height' : 330,
                    'enableEscapeButton' : false,
                    'overlayShow' : true,
                    'overlayOpacity' : 0,
                    'hideOnOverlayClick' : false,
                    'type': 'iframe'
                });
                 */
                $('#search-button').click(function() {
                    $.blockUI({ css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff'
                        } });

                    setTimeout($.unblockUI, 2000);
                });
                var availableTags = function( request, response ) {
                    $.ajax({
                        url: "get_booktitle.php",
                        type: "get",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                };
                $( "#quickfind" ).autocomplete({
                    source: availableTags,
                    minLength: 3
                });

                $(".openbox-qr").fancybox({
                    'width'  : 200,
                    'height' : 200,
                    'type': 'iframe',
                    'transitionIn' : 'elastic',
                    'speedIn' : 500
                });
            });
        </script>
    </head>
    <body>
        <h1 style="color: #c5c900">VIA Library Mockup Page</h1>
        <section class="search-form">
            <form method="post" action="">
                <section class="demo">
                    <section class="ui-widget">
                        <label for="tags">Book title: </label>
                        <input id="quickfind" name="book_title" type="text" placeholder="Book title" />
                        <input type="submit" name="search" value="Search" id="search-button" />
                    </section>
                </section>
            </form>
        </section>

        <h3>Book result</h3>
        <section class="book-content-result">
            <?php
            if (!empty($bookResult)) {
                $linkGen = new LinkGenerator();
                $newLine = "<br />";
                foreach ($bookResult as $book) {
                    if (!empty($book)) {
                        $title = $book["title"];
                        $catno = $book["catno"];

                        $qrLink = $linkGen->createQRCode($title, $catno);
                        $mapLink = $linkGen->createLinkMap($title, $catno);
                        if(empty($qrLink) || empty($mapLink)) {
                            continue;
                        }

                        echo '<div class="book-result">';
                        echo "<b>Title</b>: " . $title . $newLine;
                        echo "<b>Author</b>: " . $book["author"] . $newLine;
                        echo "<b>Type</b>: " . $book["type"] . $newLine;
                        echo "<b>Location</b>: " . $catno . $newLine;
                        echo "<b>University</b>: " . $book["university"] . $newLine . $newLine;
                        echo '<b>MAP</b>: <a href="' . $mapLink . '"' . '" class="openMap">Show MAP</a>';
                        echo '<b><br/>QR-Code</b>: <a href="' . $qrLink . '" class="openbox-qr">Show QR-Code</a>';
                        echo '</div>';
                    }
                }
            } else {
                echo "No result";
            }
            ?>
        </section>
    </body>
</html>