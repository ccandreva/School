<?php

class School_admin_searchstudentHandler extends pnFormHandler
{

  var $id;
  function initialize(&$render)
  {

    $yesnoBool = array( array('text' => '', 'value' => ''),
	array('text' => 'Yes', 'value' => 1),
	array('text' => 'No', 'value' => 0) );
    $gender = array ( array(),
	array('text' => 'Male', 'value' => 'Male'),
	array('text' => 'Female', 'value' => 'Female'),
    );
    $GradeItems = initListValues(array('', 'PK3', 'PK4', 'K', 1,2,3,4,5,6,7,8));
    $TeacherItems = initListValues(DBUtil::selectFieldArray('School_teachers', 'Name'), true);
    $TeacherItems[] = array('text' => 'N/A', 'value' => 'N/A');
    $SessionPK3Items = initListValues(array('', 'AM (8:30-11:00)', 'PM (12:00-2:30)', 'Either'));
    $SessionPK4Items = initListValues(array('', 'AM (8:30-11:00)', 'Full Day'));
    $SessionKItems = initListValues(array('', 'Montessori', 'Traditional'));
    $OrderItems = array( array('text' => 'Name', 'value' => 0), array('text' => 'Grade', 'value' => 1));
    
    $render->assign( array(
	'GenderItems' => $gender,
	'GradeItems'  => $GradeItems,
	'TeacherItems' => $TeacherItems,
	'AcceptedItems' => $yesnoBool,
	'ReturningItems' => $yesnoBool,
	'SessionPK3Items' => $SessionPK3Items,
	'SessionPK4Items' => $SessionPK4Items,
	'SessionKItems' => $SessionKItems,
	'NewStudentItems' => $yesnoBool,
	'OrderItems' => $OrderItems,
    ) );


    return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();
    $table = pnDBGetTables();
    $studentcolumn = $table['School_student_column'];
    $def = $table['School_student_column_def'];
    $where = array();

    $fields = array('Returning', 'Accepted', 'Teacher','Grade', 'Gender');
    
    foreach ($fields as $field) {
	$val = $formData[$field];
	if (strlen($val) > 0) {
	    if (substr($def[$field],0,1) == 'C') {
		$val = "'" . pnVarPrepForStore($val) . "'";
	    }
	    $where[] = $studentcolumn[$field] . '=' . $val;
	}
    }
    if (strlen($formData['NewStudent']) > 0) {
	$EnrollStart = pnModGetVar('School', 'EnrollStart');
	if ($formData['NewStudent'] === '1') {
	    $where[] = $studentcolumn['cr_date'] . " >= '$EnrollStart'";
	} else {
	    $where[] = $studentcolumn['cr_date'] . " < '$EnrollStart'";
	    
	}
    }
    $w = implode(' and ', $where);

    if ($formData['Order'] == 1) $Order = 'ClassYear DESC, LastName';
    else $Order = 'LastName';
    
    $objArray = DBUtil::selectObjectArray ('School_student', $w, $Order);
    $render->assign('data', $objArray);
    $render->assign('count', count($objArray));
    
    return;
  }

}
