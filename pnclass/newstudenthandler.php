<?php

class School_user_newstudentHandler extends pnFormHandler
{

  var $showId;
  var $studentid, $familyid;
  var $redirect;
  var $insert = false;
  var $Grade;  
  function initialize(&$render)
  {

    /* Load Data from Database */
    $studentid = $this->studentid;
    if ($studentid <= 0) {
        return false;
    }
    $studentData = DBUtil::selectObjectByID('School_student', $studentid);
    $formData = DBUtil::selectObjectByID('School_register', $studentid);
    if (!is_array($formData)) $this->insert = true;
	
    if ( $this->familyid > 0){
	if ( $studentData['Familyid'] != $this->familyid) {
	    return LogUtil::registerError("Invalid student ID");
	}
	if ( !is_array($formData) ) {
	    $tuition = DBUtil::selectObjectByID('School_tuition', $this->familyid);
	    if (is_array($tuition)) {
		$formData['Parishioner'] = $tuition['Parishioner'];
		$formData['EnvelopeNumber'] = $tuition['EnvelopeNumber'];
	    }
	}
    }
    $rereg = pnModGetVar('School', 'ReregOpen');
    // $formData['Grade'] = 'PK3';
    $Grade = $formData['Grade'];
    $this->Grade = $Grade;
    
    $yesnoBool = array( array('text' => '',    'value' => ''),
                        array('text' => 'Yes', 'value' => 1),
                        array('text' => 'No',  'value' => 0) );
    if ($Grade=='PK3') {
 	$render->assign('SessionItems',initListValues(array('', 'AM (8:30-11:00)', 'PM (12:00-2:30)', 'Either')));
    } else if ($Grade=='PK4') {
	$render->assign('SessionItems', initListValues(array('', 'AM (8:30-11:00)', 'Full Day')));
    }
    
    $render->assign('ParishionerItems', $yesnoBool);
    $render->assign($studentData);
    $render->assign($formData);
    return true;
  }

  function handleCommand(&$render, &$args)
  {
    $formData = $render->pnFormGetValues();

    if (!$render->pnFormIsValid()) { return false; }

    if (strlen($this->Grade) == 3) {
	$choose=0;
	foreach ($formData as $key => $value) {
		if ( (substr($key, 0, 6) == 'Choose') && ($formData[$key]) ) $choose++;
	}
	if ($choose == 0) {
	    LogUtil::registerError("Please choose at least one reason for attending Resurrection.");
	    return false;
	}
    }
    
    $formData['id'] = $this->studentid;
    if ($this->insert) {
        LogUtil::registerStatus("Student Application Saved.");
        DBUtil::insertObject ($formData, 'School_register', true);
    } else {
        LogUtil::registerStatus("Student Application Updated");
        DBUtil::updateObject ($formData, 'School_register');
    }

    if ($this->redirect) return pnRedirect($this->redirect);
    return pnRedirect ( pnModURL('School', 'user', 'main') );
  }

}

