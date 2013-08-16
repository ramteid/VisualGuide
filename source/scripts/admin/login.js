/* 
 * @author         DK-Group Augsburg students
 * @since          2012-09-11
 * @version        1.1
 * @description    Open a login form in a dialog box
 * Last Modification: 2012-09-26 / DS
 */

$(document).ready(function() {
    /**
     * If the dialog box opens, a login form is shown
     * On submitting the form, an ajax request compare the login data with the
     * data from the database
     * To buttons are shown on the dialog box
     *  - one as a submit button
     *  - the other one for the redirection to the frontend (index.php at the 
     *    root directory)
     */
    $('#login-form').dialog({
        title: "Visual Guide - ADMIN",
        modal: true,
        draggable: false,
        resizable: false,
        closeOnEscape: false,
        buttons: {
            "Login": function() {
                var str = $('#login input').serialize();
                $.ajax({  
                    type: "POST",
                    url: "login.php",
                    data: str,  
                    success: function(response) {
                        $("#status").ajaxComplete(function(event, request, settings) {
                            if (response) {
                                $('.ui-dialog-buttonpane').hide();
                                $(this).html("<p style='text-align: center;'><b>You are successfully logged in! <br /> Please wait while you're redirected...</b></p>");
                                setTimeout(function() {
                                    window.location = 'admin.php';
                                }, 2500);
                            } else {
                                $(this).html("<p style='text-align: center;'><b>Username or password incorrect.</b></p>");
                                setTimeout(function() {
                                    window.location = 'index.php';
                                }, 2500);
                            }
                        });
                    }
                });
            }
        }
    });
    
    /**
     * The close button is hidden
     */
    $('.ui-dialog-titlebar-close').hide();

});