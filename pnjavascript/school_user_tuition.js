/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


Event.observe(window, 'load', user_tuition_init);

function user_tuition_init()
{
    Event.observe('paymentpref1', 'click', user_tuition_onchange );
    Event.observe('paymentpref2', 'click', user_tuition_onchange );
    user_tuition_onchange();
    /*
    Event.observe('Submit', 'click', function(event) {
        document.pnFormForm.submit();
        window.location.href="School.html";
        Event.stop(event);}
    );
    */
}



function  user_tuition_onchange()
{
    
    var tmp = Form.getInputs('pnFormForm','radio','PaymentPref').find(function(radio) { return radio.checked; });
    if (tmp) var payment=tmp.value;

if (payment == 'Direct')
    {
        $('DirectForm').show();
    }
    else
    {
        $('DirectForm').hide();
    }
}

