<?php

class School_user_tuitionHandler extends pnFormHandler
{

  var $showId;
  var $familyid;

  function initialize(&$render)
  {

      /* Load Data from Database */
      $familyid = $this->familyid;
      $formData = DBUtil::selectObjectByID('School_tuition', $familyid);
      if (! is_array($formData)) {
          $formData['id'] = $familyid;
          DBUtil::insertObject ($formData, 'School_tuition', true);
      }
      $yesnoBool = array( array('text' => '', 'value' => ''),
            array('text' => 'Yes', 'value' => 1),
            array('text' => 'No', 'value' => 0) );
      $render->assign('ParishionerItems', $yesnoBool);
      $render->assign($formData);
      if ($this->showId == true) {
	  $render->assign('showId', 1);
      }

    return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();

    if ($formData[PaymentPref] == 'Direct') {
            if  ( !$formData[BankName] || !$formData[AcctNum] || !$formData[AcctHolderName]) {
                $render->assign('errormsg', "Please enter bank account information.");
                return false;
            }
    }
    if ($formData[Parishioner] == 1) {
            if (!$formData[EnvelopeNumber] || $formData[EnvelopeNumber]==0 ) {
                $render->assign('errormsg', "Parishioners must provide their envelope number.");
                return false;
            }
    }

    $formData[id] = $this->familyid;
    DBUtil::updateObject ($formData, 'School_tuition');
    return pnRedirect ( pnModURL('School', 'user') );
  }

}
