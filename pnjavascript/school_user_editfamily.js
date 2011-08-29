/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Event.observe(window, 'load', user_editfamily_init, false);

function user_editfamily_init()
{
    Event.observe('MotherHasAddress', 'click', user_editfamily_machange, false);
    Event.observe('FatherHasAddress', 'click', user_editfamily_fachange, false);
    user_editfamily_machange();
    user_editfamily_fachange();
}

function user_editfamily_machange()
{
    if ( $('MotherHasAddress').value == 'Yes')
        $('MotherAddressInfo').show();
    else
        $('MotherAddressInfo').hide();
}

function user_editfamily_fachange()
{
    if ( $('FatherHasAddress').value == 'Yes')
        $('FatherAddressInfo').show();
    else
        $('FatherAddressInfo').hide();
}

/*
function family2Mother()
{
    $('MotherAddress').value = $('Address').value;
    $('MotherApt').value = $('Apt').value;
    $('MotherCity').value = $('City').value;
    $('MotherState').value = $('State').value;
    $('MotherZip').value = $('Zip').value;
}

function family2Father()
{
    $('FatherAddress').value = $('Address').value;
    $('FatherApt').value = $('Apt').value;
    $('FatherCity').value = $('City').value;
    $('FatherState').value = $('State').value;
    $('FatherZip').value = $('Zip').value;
}
*/
