/* 
 * functions called for editstudent form
 */

var redirect;
Event.observe(window, 'load', admin_searchstudents_init);

function admin_searchstudents_init()
{
    Event.observe('Reset', 'click', admin_searchstudents_onReset );
}

function admin_searchstudents_onReset()
{
    oForm = document.getElementById('pnFormForm');
    var frm_elements = oForm.elements;
    for (i = 0; i < frm_elements.length; i++) {
    field_type = frm_elements[i].type.toLowerCase();
    switch (field_type)
    {
    case "text":
    case "password":
    case "textarea":
    // case "hidden":
        frm_elements[i].value = "";
        break;
    case "radio":
    case "checkbox":
        if (frm_elements[i].checked)
        {
            frm_elements[i].checked = false;
        }
        break;
    case "select-one":
    case "select-multi":
        frm_elements[i].selectedIndex = -1;
        break;
    default:
        break;
    }
  }
}


