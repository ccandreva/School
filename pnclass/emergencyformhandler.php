<?php
class School_user_emergencyHandler extends pnFormHandler
{

  var $showId;
  var $familyid;
  var $studentIDs = array();
  
  function initialize(&$render)
  {
      $parents = array('Mother', 'Father');
      $doctors = array('Doctor', 'Dentist', 'Orthodontist');
      $render->assign( array(
          'Parents' => $parents,
          'Doctors' => $doctors,
      ) );    
      if ($this->familyid) $loadArgs = array('familyid' => $this->familyid);
      $formData =  pnModAPIFunc('School', 'user', 'LoadEmergencyForm', $loadArgs);
      if ($formData == false) return false;

      $this->familyid = $formData['id'];

      $contactData = $formData['ContactData'];
      $i = 0;
      foreach ($contactData as $c) {
        $i++;
        foreach ($c as $k => $v) {
            $formData["$k$i"] = $v;
        }
      }
      $studentData = $formData['StudentData'];
      $i = 0;
      foreach ($studentData as $c) {
        $i++;
        $this->studentIDs[$i] = $c['id'];
        unset($c['id']);
        foreach ($c as $k => $v) {
            $formData["$k$i"] = $v;
        }
      }
      $NumStudents = $i;
      $render->assign('NumStudents', $i);
      unset ($formData['ContactData']);
      unset ($formData['StudentData']);
      
      $TeacherItems = initListValues(DBUtil::selectFieldArray('School_teachers', 'Name'));
      for ($i=1;$i<=$NumStudents;$i++) {
          $render->assign('Teacher' . $i . 'Items', $TeacherItems);
      }
      $render->assign($formData);
      if ($this->showId) $render->assign('showId', true);
      return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();
    $contactData = array();
    $studentData = array();
    $matches=array();
    // Extract values for other tables.
    foreach ($formData as $key => $value) {
            if (preg_match('/\d$/', $key, $matches) ) {
                $n = $matches[0];
                $newkey = preg_replace('/\d+$/', '', $key);
                if (preg_match('/Contact/', $newkey)) {
                    if ($n <= 4)
                    $contactData[$n][$newkey] = $formData[$key];
                    // echo "'$n' '$newkey'  '$formData[$key]<br/>\n";
                } else {
                    // if ($n <= 6)  // No need to check now that students are defined
                    $studentData[$n][$newkey] = $formData[$key];
                }
                unset($formData[$key]);
            }
    }

    $familyid= $this->familyid;
    $formData['id'] = $familyid;

    $n = 0;
    foreach ($contactData as $k => $v) {
        if (empty($v['ContactName'])) unset($contactData[$k]);
        else {
	    $contactData[$k]['familyid'] = $familyid;
	    $n++;
	}
    }
    if ($n == 0) {
	$render->assign('errormsg', "Please enter at least one emergency contact.");
	return false;
    }
    foreach ($studentData as $k => $v) {
        if (empty($v['FirstName'])) unset($studentData[$k]);
        else {
            $studentData[$k]['familyid'] = $familyid;
            $studentData[$k]['id'] = $this->studentIDs[$k];
        }    
    }
//     $studentData['id'] = $studentData['studentid'];
//    unset($studentData['studentid']);
    $formData['ContactData'] = $contactData;
    $formData['StudentData'] = $studentData;

    $result = pnModAPIFunc('School', 'user', 'SaveEmergencyForm',
            array ('formData' => $formData)
            );
    pnModAPIFunc('School', 'user', 'MailFormUpdated',
	    array('formname'=>'Emergency', 'familyid'=>$familyid)
	    );
    LogUtil::registerStatus('Your form has been saved. You may review and make additional changes, or navigate to another page.');
    return $result;
  }

}



?>
