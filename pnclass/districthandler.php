<?php

class School_admin_districtHandler extends pnFormHandler
{

  var $id;

  function initialize(&$render)
  {

      /* Load Data from Database */
      if ($this->id) {
	  $formData = DBUtil::selectObjectByID('School_districts', $this->id);
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
	DBUtil::updateObject ($formData, 'School_districts');
	LogUtil::registerStatus("Your changes have been saved.");
    } else {
	DBUtil::insertObject($formData, 'School_districts');
	LogUtil::registerStatus("The new district has been added.");
    }
    // Reload district list since it has changed.
    $districts = DBUtil::selectObjectArray ('School_districts', '', 'Name');
    $render->assign('Districts', $districts);
    return;
  }

}
