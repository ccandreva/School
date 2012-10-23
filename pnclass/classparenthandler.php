<?php

class School_admin_classparentHandler extends pnFormHandler
{

  var $id;

  function initialize(&$render)
  {

      /* Load Data from Database */
      if ($this->id) {
	  $formData = DBUtil::selectObjectByID('School_classparents', $this->id);
	  $render->assign($formData);
      }
    $render->assign( array (
	'TeacherItems' => pnModAPIFunc('School', 'user', 'GetTeacherItems'),
	'GradeItems' => GradeItems(),
    ));
    return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();
    if ($this->id) {
	$formData[id] = $this->id;
	DBUtil::updateObject ($formData, 'School_classparents');
	LogUtil::registerStatus("Your changes have been saved.");
    } else {
	DBUtil::insertObject($formData, 'School_classparents');
	LogUtil::registerStatus("The new class parent has been added.");
    }
  $clearData = array (
      'Name' => '',
      'Phone' => '',
      'Email' => '',
      'Grade' => $formData['Grade'],
      'Teacher' => $formData['Teacher']
  );
    $render->pnFormSetValues( $clearData);
    $classparents = pnModAPIFunc('School', 'user', 'GetClassParents') ;
    $render->assign('ClassParents', $classparents);

    return;
  }

}
