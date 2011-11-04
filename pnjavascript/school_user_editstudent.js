/* 
 * functions called for editstudent form
 */

var redirect;
Event.observe(window, 'load', user_application_init);

function user_application_init()
{
    Event.observe('DistrictEval', 'click', user_application_onchange );
    Event.observe('PrivateEval', 'click', user_application_onchange );
    Event.observe('Grade', 'click', user_editstudent_onGrade );
    Event.observe('Cancel', 'click', function(event) {
        if ( !redirect) {
            redirect = "School.html";
        }
        window.location.href = redirect;
        Event.stop(event);}
        );
    user_application_onchange();
    user_editstudent_onGrade();

}

function user_editstudent_onGrade()
{
    if ( $('Grade').value=='PK3')
    {
	$('sessionsPK3').show();
    }
    else {
	$('sessionsPK3').hide();
    }
    if ( $('Grade').value=='PK4')
    {
	$('sessionsPK4').show();
    }
    else {
	$('sessionsPK4').hide();
    }
}

function  user_application_onchange()
{
    if ( ($('DistrictEval').value == 'Yes') || ($('PrivateEval').value=='Yes') )
    {
        $('EvaluationInfo').show();
    } 
    else
    {
        $('EvaluationInfo').hide();
    }
    
    if ( $('DistrictEval').value=='Yes')
    {
        $('DistrictEvalInfo').show();
    } 
    else
    {
        $('DistrictEvalInfo').hide();
    }
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
