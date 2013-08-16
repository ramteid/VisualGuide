/* 
 * @author         DK-Group Augsburg students
 * @since          2012-09-11
 * @version        1.3
 * @description    Call the main javascript functions of the admin view of Visual Guide
 * Last Modification: 2012-09-27 / DS
 */

$(document).ready(function() {
    
    /**
     * @constant: Offset of content container [px]
     */
    const MAP_POS_X = parseInt($('#content .content').position().left);
    const MAP_POS_Y = parseInt($('#content .content').position().top);
    
    /**
     * @constant: URI TransferCalls
     */
    const TRANSFER_CALLS_URI = '../transfermanager.php';
    
    /**
     * @description: Check the session times and warn if the session is about to expire. If the ajax request failed, an exception is thrown
     * 
     */
    $.ajax({
        url: TRANSFER_CALLS_URI,
        type: 'post',
        dataTyp: 'json',
        data: 'key=getSessionTimes',
        success: function(response) {
            var obj = $.parseJSON(response);
            $.sessionTimeout({
                warnAfter: (obj.expireWarningTime * 1000),
                redirAfter: (obj.expireTime * 1000),
                keepUrlAlive: 'admin.php',
                redirUrl: 'index.php',
                logoutUrl: 'logout.php'
            });
        },
        error: function() {
            $.error("AJAX-Error: Can't activate the sessionTimout plugin.")
        }
    });
    
    /**
     * @description Set the minimum width of the main div to fit map background
     * 1236px = 10px toolbar margin left + 250px toolbar width + 
     *  976px room background picture D203 + 24px unknown
     */
    $('#main').css({
        'min-width': '1260px'
    });
    $('#content').css({
        'min-width': '976px'
    });
    
    /**
     * @description Initialize toolar show the logout button. If the user select a room the map will load and the button bar appear. Otherwise a note appear and the button bar is hidden.
     * @event change
     */
    $.fn.toolbar();
    $('.logout').button();
    $('.rooms').on('change', function() {
        $('#content .content').empty();
        if ($('.rooms').val() != '') {
            $.fn.vgMap({'room': $('.rooms').val()});
            $.each($('.buttonPanel').children(), function() {
                $(this).button();
            });
            $('.buttonPanel').show();
        } else {
            $('.buttonPanel').hide();
            $('#content .content').removeAttr('style').html("Please select a room.");
        }
    });

    /**
     * @description Add furniture
     * @event click
     */
    $('.tools').live('click', function() {
        $.fn.toolbar('addFurniture', $(this));
    });
    
    /**
     * @description Show delete icons
     * @event click
     */
    $('.del').live('click', function() {
        $('.del').hide();
        $('.delHide').show();
        $.each($('.furnitures'), function() {
            $('<div />').addClass('delIco').appendTo($(this));
        });
    });
    
    /**
     * @description Hide delete icons
     * @event click
     */
    $('.delHide').live('click', function() {
        $('.del').show();
        $('.delHide').hide();
        $('.delIco').remove();
    });
    
    /**
     * @description Call the method deleteFurniture with a furnitureObject as parameter
     * @event click
     */
    $('.delIco').live('click', function() {
        $(this).parent().effect('explode', 750, function() {
            var furnitureObject = {
                coordX: parseInt($(this).css('left')) - MAP_POS_X,
                coordY: parseInt($(this).css('top')) - MAP_POS_Y,
                room: $('.rooms').val()
            };
            $.fn.toolbar('deleteFurniture', furnitureObject);
            $(this).remove();
        });
        return false;
    });
    
    /**
     * @description Add categories to shelfs
     * @event click
     */
    $('.furnitures').live('click', function() {
        if ($(this).data('shelf') == 1) {
            var pos = $(this).position();
            var furnitureObject = {
                coordX: parseInt(pos.left) - MAP_POS_X,
                coordY: parseInt(pos.top) - MAP_POS_Y,
                room: $('.rooms').val()
            }
            $.fn.vgMap('addCategories', furnitureObject);
        } else {
            $.fn.popup({'style': 'info', 'msg': "You can't add any categories."});
        }
    });
    
    /**
     * @description Reset furnitures
     * @event click
     */
    $('.resetButton').live('click', function() {
        $.fn.vgMap('resetFurniture');
    });
    
    /**
     * @description Logout if the user click on the logout button
     * @event click
     */
    $('.logout').live('click', function() {
        window.location = 'logout.php';
    });
    
});