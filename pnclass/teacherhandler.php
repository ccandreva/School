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
    $teachers = DBUtil::selectObjectArray ('School_teachers', '', 'Grade, Name');
    $render->assign('Teachers', $teachers);

    return;
  }

}