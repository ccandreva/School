/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var MaxStudents;
Event.observe(window, 'load', user_emergencyForm_init, false);
function user_emergencyForm_init()
{
    MaxStudents = document.getElementById('MaxStudents').value;

    /* Hide all non-prefilled out elements,
     * unless there are none, them leave one blank visible
     * */

    Event.observe('DelStudent1', 'click', user_emergencyForm_delstudent );
    for (i=2; i<=MaxStudents; i++){
        if (!document.getElementById('StudentName' + i).value)  {
            document.getElementById('StudentInfo' + i).style.display = 'none';
        }

        // Observe all delete events
        Event.observe('DelStudent' + i, 'click', user_emergencyForm_delstudent );

    }
    Event.observe('AddStudent', 'click', user_emergencyForm_addstudent, false );
}

function user_emergencyForm_addstudent()
{
    for (i=1; i<=MaxStudents; i++) {
        name=document.getElementById('StudentName' + i);
        if (!name.value ) {
            ul = document.getElementById('StudentInfo' + i);
            if (ul.style.display == 'none') {
                ul.style.display='';
                break;
            }
        }
    }
}

function user_emergencyForm_delstudent()
{
    i = this.id.substr(10,2);
    delID = document.getElementById('StudentInfo' + i);
    fields = delID.getElementsByTagName('input');
    for (i=0; i<fields.length; i++) {
        f = fields[i];
        if (f.type != 'button') {
            f.value='';
        }
    }
    delID.style.display = 'none';
}