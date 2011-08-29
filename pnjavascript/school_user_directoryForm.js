/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var fields = ["FamilyName", "Address", "City", "State", "Zip", "Phone","Email"]

Event.observe(window, 'load', user_directoryForm_init, false);

function user_directoryForm_init()
{
    Event.observe('Preview', 'click', user_directoryForm_updatePreview, false );

    user_directoryForm_updatePreview();
    for (var i in fields) {
        Event.observe(fields[i], 'change', user_directoryForm_updatePreview, false);
    }
}

function user_directoryForm_updatePreview()
{
    text = '';
    a = document.getElementById('FamilyName').value;
    if(a) { text = '<b>' + a + '</b><br />'; }

    a = document.getElementById('Address').value;
    if(a) { text = text + a + '<br />'; }

    c = document.getElementById('City').value;
    s = document.getElementById('State').value;
    z = document.getElementById('Zip').value;
    a='';
    if (s) {
        a = s;
        if (z) { a = a +' ' + z; }
    } else {
        if (z) { a = z; }
    }
    if (c) { text = text + c; }
    if (a) {
        if(c) { text = text +', '; }
        text = text + a;
    }
    if (c || s || z)
    text = text + '<br />';

    a = document.getElementById('Phone').value;
    if(a) { text = text + a + '<br />'; }

    if (text.length>1) {
        a = document.getElementById('Students').value;
        if(a) { text = text + a + '<br />'; }

        a = document.getElementById('Email').value;
        if(a) { text = text + a + '<br />'; }
    }
    document.getElementById('CXC').innerHTML = text;
    if (text) {
        document.getElementById('PreviewBlock').style.display = '';
    } else {
        document.getElementById('PreviewBlock').style.display = 'none';
    }

    // document.getElementById('StudentInfo' + i).style.display = 'none';
/*    for (var i in fields) {
        document.getElementById('Preview-' + fields[i]).innerHTML =
            document.getElementById(fields[i]).value;

    }
*/
}