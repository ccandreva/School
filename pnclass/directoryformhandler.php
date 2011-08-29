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
          $obj =  DBUtil::selectObjectByID('School_emergencyform', $familyid);
          $formData['FamilyName'] = $obj['LastName']
            . ', ' . preg_replace('/^(\S+).*$/','$1', $obj['MotherName'] )
            . ' & ' . preg_replace('/^(\S+).*$/','$1', $obj['FatherName'] ) ;
          $formData['Address'] = $obj['Address'];
          $formData['City'] = $obj['City'] ;
          $formData['State'] = $obj['State'] ;
          $formData['Zip'] = $obj['Zip'] ;
          $formData['Phone'] = $obj['Phone'] ;
          $formData['Email'] = pnUserGetVar('email');
          $tables = pnDBGetTables();
          $StudentCol = $tables['School_emergencyStudent_column'][familyid];
          $where = "WHERE $StudentCol=$familyid";

          $students = DBUtil::selectObjectArray('School_emergencyStudent', $where, 'StudentName');
                  //null, null, null, null, array('StudentName', 'Grade'));
          if (is_array($students) ) {
                $names = '';
                foreach ($students as $s) {
                        if ($names) $names = $names . ', ';
                        $names = $names . $s['StudentName'] . ' ' . $s['Grade'];
                }
                $formData['Students'] = $names;
          }
          $this->ShouldInsert = true;
          LogUtil::registerStatus('It seems this is the first time you are filling out this form. ' .
                  'The information below is taken from your emergency form data. ' .
                  'Please review it and make any needed changes. ' .
                  'You will not be in the directory if you do not click <strong>Submit</strong>');
      }

      $render->assign($formData);
      // if ($this->showId) $render->assign('showId', true);
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
