/* 
 * @author         DK-Group Augsburg students
 * @since          2012-09-13
 * @version        1.5
 * @description    Call the main functions of the map
 * Last Modification: 2012-09-27 / DS
 * 
 * 
 * Known Bugs:
 * 
 * After moving a furniture that contains categories, the categories will not be displayed on the new 
 * location of the furniture.
 * Also, when moving a furniture with a category and resetting the position with the Reset-button, 
 * the furniture is back to its previous location but has no categories anymore.
 * This is probably because the cat_fur is not updated properly but the exact reason is unknown
 * 
 * 
 */
(function() {
    
    /**
     * @constant URI TransferCalls
     */
    const TRANSFER_CALLS_URI = '../transfermanager.php';
    
    var methods = {
        /**
         * @constructor
         * @description Initialize the vgMap. If the request is successfully the map will be filled by the database. If the request fails, an exception is thrown.
         * @function
         * @param options
         */
        init: function(options) {
            var settings = $.extend(options);
            $('#content .content').css({
                'background-image': 'url(../gfx/map/' + settings.room + '.png)',
                'background-color': 'transparent',
                'background-repeat': 'no-repeat'
            });
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=getAllFurnitures&room=' + settings.room,
                success: function(response) {
                    var posi = $('#content .content').position();
                    for (key in response) {
                        var item = key.split(',');
                        var keyElem = response[key];
                        for (key in keyElem) {
                            if (key == 'image') {
                                var fur = $('<section class="furnitures" data-coordx="' + parseInt(item[0]) + '" data-coordy="' + parseInt(item[1]) + '" data-room="' + item[2] + '" data-modelname="' + keyElem['model_name'] + '" data-fwidth="' + keyElem['fwidth'] + '" fheight="' + keyElem['fheight'] + '" data-image="' + keyElem[key] + '" data-shelf="' + keyElem['is_shelf'] + '" data-categories="' + keyElem['categories'] + '"><img title="' + keyElem['model_name'] + '" src="../gfx/map/' + keyElem[key] + '" /></section>').css({
                                    position: 'absolute',
                                    left: parseInt(posi.left) + parseInt(item[0]),
                                    top:  parseInt(item[1]) + parseInt(posi.top)
                                });
                                $('#content .content').append(fur);
                                if ($('#toolbar').length != 0) {
                                    $('.furnitures').draggable({
                                        containment: '#content .content',
                                        grid: [10, 10],
                                        stop: function(event, ui) {
                                            $.fn.vgMap('setPosition', $(this));
                                        }
                                    });
                                }
                            }
                        }
                    }
                },
                error: function() {
                    $.error("Can't create the map.");
                }
            });
        },
        
        /**
         * @description Add categories to shelfs
         * @function
         * @param furnitureObject
         */
        addCategories: function(furnitureObject) {
            var tips = $('.validateTips');
            
            /**
             * @description Function to update and show validation hints
             * @function
             * @param tipMsg
             */
            function updateTips(tipMsg) {
                tips.text(tipMsg).addClass("ui-state-highlight");
                setTimeout(function() {
                    tips.removeClass("ui-state-highlight", 1500);
                }, 500);
            }
            
            /**
             * @description Function to check the given values with regular expression
             * @function
             * @param obj
             * @param regexp
             * @param msg
             */
            function checkRegexp(obj, regexp, msg) {
                if (obj.val() == "") {
                    updateTips("Empty values.");
                    return false;
                }
                if (!(regexp.test(obj.val()))) {
                    obj.addClass("ui-state-error");
                    updateTips(msg);
                    return false;
                } else {
                    return true;
                }
            }

            /**
             * @description If the request is successfully the categories will save. If the request fails, an exception is thrown.
             */
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=getCategories&data=' + JSON.stringify(furnitureObject),
                success: function(response) {
                    $('#inputboxes').children().remove();
                    $('#addButton').children().remove();
                    var inputboxDelete = '<section class="inputboxContainer"> \
                                              <input value="§0" type="text" name="categories" class="categories-field text ui-widget-content ui-corner-all" style="float:left;" placeholder="enter a category ..." /> \
                                                  <img src="../gfx/error.png" class="deleteInputBoxImage" /> \
                                                  <br> \
                                              <div style="clear:left;"></div> \
                                           </section>';
                    if (response)
                    {
                        for (i in response)
                        {
                            var catNo = response[i].category_number;
                            var inputbox = inputboxDelete.replace(/§0/g, catNo);
                            $('#inputboxes').append(inputbox); 
                        }
                        var addBtn = $('<img class="addBtn" src="../gfx/add.png" />');
                        $('.deleteInputBoxImage').click( function() {
                            $(this).parent().remove()
                        });
                        $('#addButton').append(addBtn);
                        $('.addBtn').click( function() {
                            $('#inputboxes').append( inputboxDelete.replace(/§0/g, '') );
                            $('.deleteInputBoxImage').click( function() {
                                $(this).parent().remove()
                            });
                        });
                    }
                    else 
                    {
                        
                    }
                },
                error: function() {
                    $.fn.popup({'style': 'error', 'msg': "An error occured (cannot get categories)"});
                }
            });
            
            /**
             * @description Dialog box to show the addCategories form with two buttons
             */
            $('#addCategories-form').dialog({
                height: 250,
                width: 350,
                buttons: {
                    "Add categories": function() {
                        var valid = true;
                        $('#inputboxes').children().children().removeClass("ui-state-error");
                        
                        var catArr = [];
                        $.each($('.categories-field'), function() {
                            valid = valid && checkRegexp($(this), /^[a-zA-Z0-9\\.,_;]*$/i, "Categories may consist of a-zA-Z, 0-9, underscores and begin with a letter.");
                            if (valid)
                                catArr.push($(this).val());
                            else {
                                $(this).addClass('ui-state-error');
                            }
                        });
                        if ($('.ui-state-error').length > 0) {
                            return;
                        }
                        
                        var dataObject = {
                            furniture: furnitureObject,
                            categories: catArr
                        }
                        $.ajax({
                            url: TRANSFER_CALLS_URI,
                            type: 'post',
                            dataType: 'json',
                            data: 'key=addCategories&data=' + JSON.stringify(dataObject),
                            success: function(response) {
                                if (response) {
                                    $('#addCategories-form').dialog("close");
                                    $.fn.popup({'style': 'success', 'msg': "Categories successfully added."});
                                } else {
                                    $('#addCategories-form').dialog("close");
                                    $.fn.popup({'style': 'error', 'msg': "Can't add categories."});
                                }
                                $('#addCategories-form').dialog("close");
                                $('#inputboxes').children().remove();
                                $('#addButton').children().remove();
                            },
                            error: function() {
                                $.fn.popup({'style': 'error', 'msg': "An error occured (cannot set cateogires)"});
                            }
                        });
                    },
                    Cancel: function() {
                    $('#addCategories-form').dialog("close");
                    $('#inputboxes').children().remove();
                    $('#addButton').children().remove();
                    }
                },
                close: function() {
                    $('#inputboxes').children().remove();
                    $('#addButton').children().remove();
                }
            });
        },
        
        /**
         * @description Save furniture. If the request is successfully the furnitures will save. If the request fails, an exception is thrown.
         * @function
         * @param furniture
         */
        setPosition: function(furniture) {
            var data = {
                'furniture': {
                    'coordX': furniture.data('coordx'),
                    'coordY': furniture.data('coordy'),
                    'room': furniture.data('room')
                },
                'coordX': parseInt(furniture.css('left')) - parseInt($('#content .content').position().left),
                'coordY': parseInt(furniture.css('top')) - parseInt($('#content .content').position().top),
                'model_name': furniture.data('modelname')
            };
            $.ajax({
                url: TRANSFER_CALLS_URI,
                type: 'post',
                dataType: 'json',
                data: 'key=setPosition&data=' + JSON.stringify(data),
                success: function(response) {
                    if (response) {
                        $.fn.popup({'style': 'success', 'msg': "Furniture successfully moved."});
                    } else {
                        $.fn.popup({'style': 'error', 'msg': "Can't save furniture."});
                    }
                },
                error: function() {
                    $.error("AJAX-Error: Can't save furniture.");
                }
            });
        },
        
        /**
         * @description Reset the furniture to the original coordinate
         * @function
         */
        resetFurniture: function() {
            $.each($('#content .content').children(), function() {
                if ($(this).data('coordx') == 303 && $(this).data('coordy') == 94) {
                    var furnitureObject = {
                        'room': $('.rooms').val(),
                        'coordX': parseInt($(this).data('coordx')) + parseInt($('#content .content').position().left),
                        'coordY': parseInt($(this).data('coordy')) + parseInt($('#content .content').position().top)
                    };
                    $.fn.toolbar('deleteFurniture', furnitureObject);
                    $(this).remove();
                } else {
                    $(this).css({
                        position: 'absolute',
                        left: parseInt($(this).data('coordx')) + parseInt($('#content .content').position().left),
                        top: parseInt($(this).data('coordy')) + parseInt($('#content .content').position().top)
                    });
                }
            });
        },
        
        /**
         * @description Set the X on a shelf
         * @function
         * @param x
         * @param y
         */
        setX: function(x, y) {
            var pos = $('.content').position();
            leftPos = parseInt(pos.left + x);
            topPos = parseInt(pos.top + y);
            var divx = $('<section class="x" ><img title="' + $(document).getUrlParam('title') + '" src="../gfx/map/x.png" /></section>').css({
                position: 'absolute',
                left: leftPos,
                top:  topPos,
                'z-index': 2
            });
            $('.content').append(divx);
        },
        
        /**
         * @description Set the name of the room to the title bar
         * @function
         * @param room_name
         */
        setRoomName: function(room_name) {
            $('.contentTitle').html(room_name);
        }
    };
    
    /**
     * @class Call the methods of the vgMap
     * @param method
     */
    $.fn.vgMap = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.vgMap');
        }
    };
    
})(jQuery);
