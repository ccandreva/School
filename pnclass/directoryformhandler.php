<?php

class School_user_directoryHandler extends pnFormHandler
{

  // private $loadOptions=array();
  var $showId;
  var $familyid;
  var $ShouldInsert = false;
  var $redirect;

  function initialize(&$render)
  {
      //if ($this->familyid < 1) return false;
      //$formData =  pnModAPIFunc('School', 'user', 'LoadDirectoryForm', $this->loadOptions);
      $familyid = $this->familyid;
      $formData = DBUtil::selectObjectByID('School_directory', $familyid);
      if (! is_array($formData)) {
          $formData['id'] = $familyid;
          $obj =  DBUtil::selectObjectByID('School_family', $familyid);
          $formData['FamilyName'] = $obj['LastName']
            . ', ' . preg_replace('/^(\S+).*$/','$1', $obj['MotherFirstName'] )
            . ' & ' . preg_replace('/^(\S+).*$/','$1', $obj['FatherFirstName'] ) ;
          $formData['Address'] = $obj['Address'];
          $formData['City'] = $obj['City'] ;
          $formData['State'] = $obj['State'] ;
          $formData['Zip'] = $obj['Zip'] ;
          $formData['Phone'] = $obj['Phone'] ;
          $formData['Email'] = pnUserGetVar('email');
          $this->ShouldInsert = true;
          LogUtil::registerStatus('It seems this is the first time you are filling out this form. ' .
                  'The information below is taken from your main registration data. ' .
                  'Please review it and make any needed changes. ' .
                  'You will not be in the directory if you do not click <strong>Submit</strong>');
      }
      $tables = pnDBGetTables();
      $StudentCol = $tables['School_student_column']['Familyid'];
      $YearCol = $tables['School_student_column']['ClassYear'];
      $ReturningCol = $tables['School_student_column']['Returning'];
      $where = "WHERE $StudentCol=$familyid and $ReturningCol=true and $YearCol>" . LatestAlumniClass();

      $students = DBUtil::selectObjectArray('School_student', $where, 'FirstName',
	      -1, -1, '',null, null,
	      array('id', 'FirstName', 'NickName', 'ClassYear', 'Teacher') );
      foreach ($students as $student) {
          $id = $student['id'];
          $formData['NickName'.$id] = $student['NickName'];
          $formData['Teacher'.$id] = $student['Teacher'];
	  $grade = Year2Grade($student['ClassYear']);
	  if (is_numeric($grade)) {
	    $where = "School_teachers_Grade='" . $grade . "'";
	  } else {
	    $where = "School_teachers_Grade='M' or School_teachers_Grade='K'";
	  }
          $formData['Teacher'.$id.'Items'] = 
            pnModAPIFunc('School','user','GetTeacherItems',array('where' => $where));
      }
    
      $render->assign($formData);
      $render->assign('students', $students);
      if ($this->showId) $render->assign('showId', true);
      return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();

    $familyid =  $this->familyid;
    $formData['id'] = $familyid;

    if ($this->ShouldInsert) {
      SessionUtil::delVar('_PNStatusMsg');
      SessionUtil::delVar('_PNStatusMsgType');
      DBUtil::insertObject ($formData, 'School_directory', true);
    } else {
      DBUtil::updateObject ($formData, 'School_directory');
    }

    if ($this->redirect) {
        LogUtil::registerStatus('Changes have been saved.');
        return pnRedirect($this->redirect);
    }
    
    LogUtil::registerStatus('Your form has been saved. You may review and make additional changes, or navigate to another page.');
    return $result;
  }

}



?>
