/* 
 * functions called for editstudent form
 */

var redirect;
Event.observe(window, 'load', user_application_init);

function user_application_init()
{
    Event.observe('Cancel', 'click', function(event) {
        if ( !redirect) {
            redirect = "School.html";
        }
        window.location.href = redirect;
        Event.stop(event);}
        );
}
