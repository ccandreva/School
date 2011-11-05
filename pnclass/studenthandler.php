<?php

class School_user_studentHandler extends pnFormHandler
{

  var $showId;
  var $studentid, $familyid;
  var $redirect;

  function initialize(&$render)
  {

      /* Load Data from Database */
      $studentid = $this->studentid;
      if ($studentid > 0) {
          $formData = DBUtil::selectObjectByID('School_student', $studentid);
          if ( ($this->familyid > 0) && ($formData['Familyid'] != $this->familyid) ){
               return false;
          }
	  $rereg = pnModGetVar('School', 'ReregOpen');
          $formData['Grade'] = Year2Grade($formData['ClassYear'], $rereg);
      } else {
          $formData = array();
      }
      $formData['AppDate'] = date('Y-m-d');
      $render->assign($formData);

      $gender = array ( array(),
            array('text' => 'Male', 'value' => 'Male'),
            array('text' => 'Female', 'value' => 'Female'),
        );
	$TeacherItems = initListValues(DBUtil::selectFieldArray('School_teachers', 'Name'), true);
        $Sacraments=array('Baptism' =>'Baptism', 'Reconciliation'=>'Reconciliation',
            'Communion'=>'First Holy Communion', 'Confirmation' => 'Confirmation');
        $Custodial = initListValues(array('N/A','Mother','Father'));
        $Evaluations = array('Edu'=>'Educational', 'Psych'=>'Psychological',
            'Speech'=>'Speech','Other'=>'Other',
            );
        $GradeItems = initListValues(array('', 'PK3', 'PK4', 'K', 1,2,3,4,5,6,7,8));
        $yesno = initListValues(array( 'No', 'Yes'));
        $yesnoBool = array( array('text' => '', 'value' => ''),
            array('text' => 'Yes', 'value' => 1),
            array('text' => 'No', 'value' => 0) );
        $ey = EnrollYear();
        $newyear = $ey + 11;
        $ClassYearItems = array ( array(text=>"New ($newyear)", value=>$newyear));
	$SessionPK3Items = initListValues(array('', 'AM (8:30-11:00)', 'PM (12:00-2:30)', 'Either'));
	$SessionPK4Items = initListValues(array('', 'AM (8:30-11:00)', 'Full Day'));
        for ($y = $ey+10; $y >=$ey; $y-- ) {
            $text = Year2Grade($y) . " ($y)";
            $ClassYearItems[] = array(text => $text, value => $y);
        }
        $render->assign( array(
            'Lang' => 'English',
            'Religion' => 'Catholic',
            'Parish' => 'Resurrection',
            'GenderItems' => $gender,
            'GradeItems'  => $GradeItems,
	    'TeacherItems' => $TeacherItems,
            'Sacraments' => $Sacraments,
            'CustodialParentItems' => $Custodial,
            'DistrictEvalItems' => $yesno,
            'ReturningItems' => $yesnoBool,
            'PrivateEvalItems' => $yesno,
            'Evaluations' => $Evaluations,
            'IEPItems' => $yesno,
            '504PlanItems' => $yesno,
            'ClassYearItems' => $ClassYearItems,
	    'SessionPK3Items' => $SessionPK3Items,
	    'SessionPK4Items' => $SessionPK4Items,
        ) );
    return true;
  }

  function handleCommand(&$render, &$args)
  {
    $formData = $render->pnFormGetValues();

    if ($render->pnFormIsValid()) {
        $formData[LastSaveValid] = 1;
    } else {
        if ($formData[SaveAnyway]) {
            $formData[LastSaveValid] = 0;
        } else {
            $render->assign('ShowSaveAnyway', 1);
            return false;
        }
    }

    $Grade = $formData['Grade'];
    if (strlen($Grade) == 3) {
	    $sesKey = "Session$Grade";
	    if ($formData[$sesKey]) {
		$formData['Sesson'] = $formData[$sesKey];
	    } else {
		$render->assign('errormsg', "Please select a session.");
		return false;
	    }
    }
    
    if ($this->studentid > 0) {
        $formData[id] = $this->studentid;
        LogUtil::registerStatus("Updated Student $this->studentid");
        DBUtil::updateObject ($formData, 'School_student');
    } else {
        $formData[Familyid] = $this->familyid;
        $formData[ClassYear] = Grade2Year($formData[Grade]);
        LogUtil::registerStatus("Added Student");
        DBUtil::insertObject ($formData, 'School_student');
	$this->redirect=pnModURL('School', 'user', 'newstudent', array('id' => $formData['id']));
    }

    if ($this->redirect) return pnRedirect($this->redirect);
    return pnRedirect ( pnModURL('School', 'user', 'main') );
  }

}

