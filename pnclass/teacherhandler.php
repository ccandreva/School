<?php

class School_admin_teacherHandler extends pnFormHandler
{

  var $id;

  function initialize(&$render)
  {

      /* Load Data from Database */
      if ($this->id) {
	  $formData = DBUtil::selectObjectByID('School_teachers', $this->id);
	  $render->assign($formData);
      }
    $gradeItems = initListValues(array('','M','K',1,2,3,4,5,6,7,8));
    $titleItems = initListValues(array('', 'Mrs.', 'Ms.', 'Miss', 'Mr.', 'Dr.', 'Father', 'Brother', 'Sister'));
    $render->assign( array('GradeItems' => $gradeItems,
        'TitleItems' => $titleItems));
    return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();
    if ($this->id) {
	$formData[id] = $this->id;
	DBUtil::updateObject ($formData, 'School_teachers');
	LogUtil::registerStatus("Your changes have been saved.");
    } else {
	DBUtil::insertObject($formData, 'School_teachers');
	LogUtil::registerStatus("The new teacher has been added.");
    }
    $teachers = DBUtil::selectObjectArray ('School_teachers', '', 'Grade, LastName');
    $render->assign('Teachers', $teachers);

    return;
  }

}
