/* 
 * @author         DK-Group Augsburg students
 * @since          2012-09-13
 * @version        1.0
 * @description    Call the main functions of the toolbar and manage
 * Last Modification: 2012-09-27 / DS
 */
(function() {
    
    /**
     * @constant URI TransferCalls
     */
    const TRANSFER_CALLS_URI = '../transfermanager.php';
    
    var methods = {
        
        /**
         * @constructor
         * @description Initialize the toolbar and put all distinct furnitures
         * @function init
         */
        init: function() {
            $.fn.toolbar('putRoomsIntoSelect');
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=getDistinctFurnitures',
                success: function(response) {
                    for(key in response) {
                        var keyElem = response[key];
                         for(key in keyElem) {
                            if (key == 'image') {
                                $('#toolbar .content').append("<img class='tools' data-modelname='" + keyElem['model_name'] + "' data-fwidth='" + keyElem['fwidth'] + "' fheight='" + keyElem['fheight'] + "' data-image='" + keyElem[key] + "' data-shelf='" + keyElem['is_shelf'] + "' title='" + keyElem['model_name'] + "' src='../gfx/map/" + keyElem[key] + "' />");
                            }
                        }
                    }
                },
                error: function() {
                     $.error("AJAX-Error: Can't create the toolbar.");
                }
            });
        },
        
        /**
         * @description Delete the furniture from the database. If the ajax request fails, an exception is thrown.
         * @function
         * @param furnitureObject
         */
        deleteFurniture: function(furnitureObject) {
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=deleteFurniture&data=' + JSON.stringify(furnitureObject),
                success: function(response) {
                    if (response) {
                        $.fn.popup({'style': 'success', 'msg': "Furniture successfully deleted."});
                    } else {
                        $.fn.popup({'style': 'error', 'msg': "Can't delete furniture."});
                    }
                },
                error: function() {
                    $.error("AJAX-Error: Can't delete furniture.");
                }
            });
        },
        
        /**
         * @description Add the furniture to the database. If the request is successfully the furniture will add to the map and will draggable in 10px x 10px grid. If the ajax request fails, an exception is thrown
         * @function
         * @param furniture
         */
        addFurniture: function(furniture) {
            if ($('.rooms').val() == "")
            {
                $.fn.popup({'style': 'error', 'msg': "No room selected."});
                return;
            }
            var furnitureData = {
                'coordX': 20,
                'coordY': 20,
                'room': $('.rooms').val(),
                'model_name': furniture.data('modelname')
            }
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=addFurniture&data=' + JSON.stringify(furnitureData),
                success: function(response) {
                    if (response) {
                        $.fn.popup({'style': 'success', 'msg': "Furniture successfully added."});
                        var fur = $("<section class='furnitures' data-room='" + furnitureData.room + "' data-coordx='" + furnitureData.coordX + "' data-coordy='" + furnitureData.coordY + "' data-modelname='" + furniture.data('modelname') + "' data-fwidth='" + furniture.data('fwidth') + "' fheight='" + furniture.data('fheight') + "' data-image='" + furniture.data('image') + "' data-shelf='" + furniture.data('shelf') + "'><img title='" + furniture.attr('title') + "' src='" + furniture.attr('src') + "' /></section>").css({
                            position: 'absolute',
                            left: furnitureData.coordX + parseInt($('#content > .content').position().left),
                            top: furnitureData.coordY + parseInt($('#content > .content').position().top)
                        });
                        $('#content > .content').append(fur);
                        $('.furnitures').draggable({
                            containment: '#content .content',
                            grid: [10, 10],
                            stop: function(event, ui) {
                                $.fn.vgMap('setPosition', $(this));
                            }
                        });
                    } else {
                        $.fn.popup({'style': 'error', 'msg': "Can't add furniture."});
                    }
                },
                error: function() {
                    $.error("AJAX-Error: Can't add furniture.");
                }
            });
        },
        
        /**
         * @description Put all rooms from the database into the selectbox in the toolbar
         * @function
         */
        putRoomsIntoSelect: function() {
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=getRooms',
                success: function(response) {
                    $('.rooms').append("<option value='' selected='selected'>Select a room</option>");
                    for (var i in response) {
                        $('.rooms').append("<option class='roomOption' value='" + response[i].room_name + "'>" + response[i].room_name + "</option>");
                    }
                    $.each($('.roomOption'), function() {
                        if ($(this).val() != "D203") {
                            $(this).attr('disabled', 'disabled');
                        }
                    });
                },
                error: function() {
                    $.error("AJAX-Error: Can't put the rooms into the selectbox.");
                }
            });
        }
    };
    
    /**
     * @class Call the methods of the toolbar
     * @param method
     */
    $.fn.toolbar = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.toolbar');
        }
    };
})(jQuery);
