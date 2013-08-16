/* 
 * @author         DK-Group Augsburg students
 * @since          2012-09-21
 * @version        1.0
 * @description    If the function popup is calling e. g. $.fn.popup the box 
 *                    appears by default for 3 seconds with the given message 
 *                    and style
 * Last Modification: 2012-09-24 / PVO
 */

(function() {
    var methods = {
        /**
         * @constructor
         * @function init
         * @param Object options
         * @default key=time, value=3000
         */
        init: function(options) {
            var settings = $.extend({
                'time': 3000
            }, options);
            $('.msgBox').addClass(settings.style).html(settings.msg).show();
            setTimeout(function() {
                $('.msgBox').hide();
                $('.msgBox').removeClass(settings.style);
            }, settings.time);
        }
    };
    
    $.fn.popup = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.popup');
        }
    };
})(jQuery);