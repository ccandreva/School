/**
 * Zikula Application Framework
 *
*/
var entrypoint;

function livefamilysearch()
{
    Element.removeClassName('livefamilysearch', 'z-hide');
    Event.observe('searchfamily', 'click', function() { window.location.href=document.location.entrypoint + entrypoint + $F('familyname');}, false);
    new Ajax.Autocompleter('familyname', 'familyname_choices', document.location.pnbaseURL + 'ajax.php?module=School&func=getfamilies',
       {paramName: 'fragment',
        minChars: 3,
        afterUpdateElement: function(data){
            Event.observe('searchfamily', 'click', function() { window.location.href=document.location.entrypoint + entrypoint + $F('familyname');}, false);
            }
        }
    );
}
