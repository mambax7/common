/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/*
 * common module
 *
 * @copyright       XOOPS Project https://xoops.org/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         common
 * @since           1.00
 * @author          luciorota, studiopas
 * @version         svn:$Id$
 */

$(document).ready(function() {
    $("#ajax_call").click(function(e) {
        $.ajax({
            // Specifies the URL to send the request to
            url: 'ajax.php',
            // Specifies the type of request
            type: 'POST',
            // The data type expected of the server response
            dataType: 'json',
            // Specifies data to be sent to the server
            data: {
                //token: XOOPS_TOKEN, // in progress
                script: 'common.php',
                op: 'op'
                },
            // A function to run before the request is sent
            beforeSend: function(request) {
                // NOP
            },
            // A function to be run when the request succeeds
            success: function (data, state) {
                if (!data.error) {
                    // no error
                    if (data.data) {
                        // NOP
                    }
                    // in progress
                } else {
                    // error
                    alert('Server error!\nerror: ' + data.message);
                }
            },
            // A function to run if the request fails
            error: function (request, state, error) {
                // error
                alert('Ajax error!\ncall state: ' + state + '\nerror: ' + error);
            },
            // A function to run when the request is finished (after success and error functions)
            complete: function (request, state) {
                // NOP
            }
        });
    });
});
