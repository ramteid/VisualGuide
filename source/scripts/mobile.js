/* 
 * @author         DK-Group Augsburg students
 * @since          2012-09-11
 * @version        1.2
 * @description    Call the main javascript functions of the mobile version of Visual Guide
 * Last Modification: 2012-09-25 / PVO
 */

(function() {
    
    /**
     * @constant URI TransferCalls
     */
    const TRANSFER_CALLS_URI = '../transfermanager.php';
    
    /**
     * @constant Get the url parameters
     */
    var title = $(document).getUrlParam('title');
    var catNo = $(document).getUrlParam('catNo');
    
    /**
     * @description Get the coordinates from the database to set the X on the map. If there are no url parameters an exception is thrown. If the request is successfully the map will shown. Othewise an exception is thrown.
     */
    if (title != null && catNo != null) {
        $.ajax({
           url: TRANSFER_CALLS_URI,
           type: 'post',
           dataType: 'json',
           data: 'key=getTargetCoordinates&category=' + catNo,
           success: function(response) {
               var showMap = false;
               for (var i in response)
               {
                   if (!showMap) { // If it is possible to show more than one room, than remove the if-Statement and the boolean variable showMap
                       
                       $.fn.vgMap({'room': response[i].room_name});
                       showMap = true;
                   }
                   $.fn.vgMap('setRoomName', title);
                   $.fn.vgMap('setX', response[i].x, response[i].y);
               }
           },
           error: function() {
               $.error("AJAX-Error: Can't request target coordinates/room.");
           }
        });
    } else {
        $('#content .content').html("Missing parameters.");
        $.error("No parameters: Can't request room.");
    }
    
})(jQuery);
