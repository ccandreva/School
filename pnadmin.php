<?php
/**
 * Zikula - Resurrection Custom Module
 *
 * @copyright (c) 2011 Chris Candreva
 */

Loader::requireOnce('includes/pnForm.php');
require_once('common.php');
require_once('pnclass/emergencyformhandler.php');
require_once('pnclass/directoryformhandler.php');
require_once('pnclass/familyhandler.php');
require_once('pnclass/studenthandler.php');
require_once('pnclass/teacherhandler.php');
require_once('pnclass/classparenthandler.php');
require_once('pnclass/districthandler.php');
require_once('pnclass/confighandler.php');
require_once('pnclass/searchstudenthandler.php');
require_once('pnclass/tuitionhandler.php');

function School_admin_main()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    
    $render = pnRender::getInstance('School', false);
    return $render->fetch('School_admin_main.htm');
}

function School_admin_showemergencyforms()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $render = pnRender::getInstance('School', false);
    $where = "School_family_Withdrawn=0";
    $objArray = DBUtil::selectObjectArray ('School_family', $where, 'LastName');
    $render->assign('data', $objArray);
    return $render->fetch('School_admin_showemergencyforms.html');

}

function School_admin_exportemergencyforms()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    //Read in field list
    $csvNames = School_ReadCsvNames("ExportEmergency.txt");
    if (!$csvNames) return false;
    
    $tables = pnDBGetTables();
    $ContactCol = $tables['School_emergencyContact_column'][familyid];
    $StudentCol = $tables['School_student_column'][Familyid];
    $csvContact = array('ContactName', 'ContactPhone', 'ContactCell',
        'ContactWork','ContactRelation');
    $csvStudent = array('FirstName','Grade','DOB','Teacher','Allergies','Conditions');

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="EmergencyOutput.csv"');

    $objArray = DBUtil::selectObjectArray ('School_family', '', 'LastName');
    $data = array();

    echo $HeaderLine;
    // Loop through all Families and export
    foreach ($objArray as $obj) {
        $familyid = $obj['id'];
        $obj['MotherWorkAddress'] = preg_replace('/(^|\s)([A-Z])([A-Z]+)/e',"'$1$2' . strtolower('\\3')", $obj['MotherWorkAddress'] );
        $obj['FatherWorkAddress'] = preg_replace('/(^|\s)([A-Z])([A-Z]+)/e',"'$1$2' . strtolower('\\3')", $obj['FatherWorkAddress'] );
        $where = "WHERE $ContactCol=$familyid";
        $contactData = DBUtil::selectObjectArray ('School_emergencyContact', $where);
        $where = "WHERE $StudentCol=$familyid";
        $studentData = DBUtil::selectObjectArray ('School_student', $where);

        $line = '';

        foreach ($csvNames as $field) {

            if (substr($field, 0, 1) == '~') {
                $db = substr($field, 1);

                if ($db == 'studentData') {
                    for ($i=0; $i<6; $i++) {
                        foreach ($csvStudent as $s) {
                            $line = $line . ',"' . $studentData[$i][$s] . '"';
                        }
                    }
                } else {
                    for ($i=0; $i<4; $i++) {
                        foreach ($csvContact as $c) {
                            $line = $line . ',"' . $contactData[$i][$c] . '"';
                        }
                    }
                }
            } elseif ($field == 'FamilyID') {
                    $line = $line . $familyid;
            } else {
                    $line = $line . ',"' . $obj[$field] . '"';

            }
        }
        
        echo $line . "\n";
    }

    return true;
}

function School_admin_editemergencyform()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // User who's information we are going to edit.
    $familyid = FormUtil::getPassedValue('familyid');
    
    $render = FormUtil::newpnForm('School');
    $render->assign('admin', 1);
    $formobj = new School_user_emergencyHandler();
    $formobj->familyid = $familyid;
    $formobj->showId = true;
    return $render->pnFormExecute('School_user_emergencyForm.html', $formobj);

}

function School_admin_showdirectory()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $view = FormUtil::getPassedValue('view');
    $render = pnRender::getInstance('School', false);
    if ($view) {
        $objArray = DBUtil::selectObjectArray ('School_directory', '', 'FamilyName');
        $render->assign('data', $objArray);
        $render->assign('view', $view);

        if ($view === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="DirectoryOutput.csv"');
            echo $render->fetch('School_admin_showdirectory_csv.html');
            return true;
        }

    }
    RenderSchoolYear($render);
    return $render->fetch('School_admin_showdirectory.html');

}

function School_admin_editdirectoryForm()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // User who's information we are going to edit.
    $familyid = FormUtil::getPassedValue('familyid');

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_directoryHandler();
    $formobj->familyid = $familyid;
    $formobj->redirect =  pnModUrl('School', 'admin', 'showdirectory',
                array( 'view' => 'table')
                     );
    $formobj->showId = true;

    return $render->pnFormExecute('School_user_directoryForm.html', $formobj);
}

function School_admin_showregistration()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $table = pnDBGetTables();
    $familycolumn = $table['School_family_column'];
    $render = pnRender::getInstance('School', false);

    $status = FormUtil::getPassedValue('admin_reg_status');
    if (strlen($status) > 0) pnSessionDelVar('admin_reg_status');
    else $status = pnSessionGetVar('admin_reg_status');
    $where='';
    if ( ($status === '0') || ($status == '1') ){
	$where = "$familycolumn[Accepted]=$status";
	$render->assign('admin_reg_status', $status);
	pnSessionSetVar('admin_reg_status', $status);
    }

    $joinInfo = array ( array(
        'join_table'  => 'School_tuition',
        'join_field'  => array('lu_date'),
        'join_method' => 'LEFT JOIN',
        'object_field_name' => array('tuition_lu_date'),
        'compare_field_table' => 'id',
        'compare_field_join' => 'id',
        ) );

    //$familyData = DBUtil::selectObjectArray ('School_family', '', 'LastName');
    $familyData = DBUtil::selectExpandedObjectArray ('School_family', $joinInfo, $where, 'LastName');
    $EnrollYear = EnrollYear();
    foreach ($familyData as &$family)
    {
        $familyid = $family[id];
        $students = pnModAPIFunc('School', 'user', 'GetStudents', array('familyid' => $familyid));
        $n = 0;
        $t = 0;
        foreach ($students as $student) {
            $t++;
            if ($student[ClassYear] != $EnrollYear) {
                    $n++;
            }
        }
        if ($n>0) $family[numReturn] = $n;
        $family[numStudents] = $t;
    }
    $render->assign('familyData', $familyData);
    $render->assign('EnrollStart', EnrollStart() );
    RenderSchoolYear($render);
    return $render->fetch('School_admin_showregistration.html');

}

function School_admin_showpendingstudents()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $render = pnRender::getInstance('School', false);

    $table = pnDBGetTables();
    $studentcolumn = $table['School_student_column'];
    $where = "$studentcolumn[Accepted]=0";

    $objArray = DBUtil::selectObjectArray ('School_student', $where, 'LastName');
    $render->assign('data', $objArray);
    return $render->fetch('School_admin_showpendingstudents.html');

}

function School_admin_searchstudents()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $render = FormUtil::newpnForm('School');
    $formobj = new School_admin_searchstudentHandler();
    return $render->pnFormExecute('School_admin_searchstudents.html', $formobj);
}

function School_admin_editfamily()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $familyid = FormUtil::getPassedValue('familyid');

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_familyHandler();
    $formobj->familyid = $familyid;
    $render->assign('admin', 1);
    $formobj->redirect = pnModURL('School', 'admin', 'showregistration' );
    return $render->pnFormExecute('School_user_editfamily.htm', $formobj);
}
function School_admin_deletefamily()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // User who's information we are going to delete.
    $familyid = FormUtil::getPassedValue('familyid');
    $family = DBUtil::selectObjectByID('School_family', $familyid);
    if (!is_array($family)) {
        return LogUtil::registerError("Family does not exist.");
    }

    if (!FormUtil::getPassedValue('confirmdelete', '', 'POST' )) {

        $render = pnRender::getInstance('School', false);
        $render->assign('familyid', $familyid);
        $render->assign('family', $family);
        return $render->fetch('School_admin_deletefamily.html');
    }

    $tables = pnDBGetTables();
    $ContactCol = $tables['School_emergencyContact_column'][familyid];
    $where = "WHERE $ContactCol=$familyid";
    DBUtil::deleteWhere ('School_emergencyContact', $where);

    DBUtil::deleteObjectByID('School_directory' ,$familyid);
    DBUtil::deleteObjectByID('School_tuition' ,$familyid);
    DBUtil::deleteObjectByID('School_family' ,$familyid);
    $StudentCol = $tables['School_student_column'][Familyid];
    $where = "WHERE $StudentCol=$familyid";
    DBUtil::deleteWhere ('School_student', $where);
    LogUtil::registerStatus("Deleted family " . $family.LastName . " ($familyid)");
    return pnRedirect( pnModUrl('School', 'admin', 'showregistration') );

     /*
    $url = pnModUrl('School', 'admin', 'showemergencyforms');
    return pnRedirect($url);
     */

}
function School_admin_deletedirectory()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // User who's information we are going to delete.
    $familyid = FormUtil::getPassedValue('familyid');

    if (!FormUtil::getPassedValue('confirmdelete', '', 'POST' )) {

        $render = pnRender::getInstance('School', false);
        $render->assign('familyid', $familyid);
        //$render->assign('family', $family);
        return $render->fetch('School_admin_deletedirectory.html');
    }

    DBUtil::deleteObjectByID('School_directory' ,$familyid);
    return pnRedirect( pnModUrl('School', 'admin', 'showdirectory',
	    array('view' => 'table') ) );

}

function School_admin_showstudents()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $familyid = FormUtil::getPassedValue('familyid');
    $students = pnModAPIFunc('School', 'user', 'GetStudents', array('familyid' => $familyid));

    $render = pnRender::getInstance('School', false);
    $render->assign('students', $students);
    return $render->fetch('School_admin_showstudents.html');

}
function School_admin_editstudent()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $familyid = FormUtil::getPassedValue('familyid');
    $studentid = FormUtil::getPassedValue('studentid');
    $redirect = FormUtil::getPassedValue('redirect');
    if ($redirect == '') $redirect='showstudents';
    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_studentHandler();
    $formobj->familyid = $familyid;
    $formobj->studentid = $studentid;
    $formobj->redirect = pnModURL('School', 'admin', $redirect,
                array('familyid' => $familyid));
    $render->assign('redirect', $redirect);
    $render->assign('admin', 1);
    return $render->pnFormExecute('School_user_editstudent.htm', $formobj);
}

function School_admin_addstudent()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $familyid = FormUtil::getPassedValue('familyid');

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_studentHandler();
    $formobj->familyid = $familyid;
    $formobj->redirect = pnModURL('School', 'admin', 'showstudents',
                array('familyid' => $familyid));
    return $render->pnFormExecute('School_user_editstudent.htm', $formobj);
}

function School_admin_tuition()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $familyid = FormUtil::getPassedValue('familyid');

    $Render = FormUtil::newpnForm('School');
    $formobj = new School_user_tuitionHandler();
    $formobj->familyid = $familyid;
    RenderSchoolYear($Render);
    $formobj->showId = true;
    return $Render->pnFormExecute('School_user_tuition.html', $formobj);
}

function School_admin_deletestudent()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // User who's information we are going to edit.
    $familyid = FormUtil::getPassedValue('familyid');
    $studentid = FormUtil::getPassedValue('studentid');

    DBUtil::deleteObjectByID('School_student' ,$studentid);
    return pnRedirect( pnModUrl('School', 'admin', 'showregistration') );

}

function School_admin_classlist()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    
    // Build where clause to show only registered students
    $tables = pnDBGetTables();
    $studentcolumn = $tables['School_student_column'];
    $where = "$studentcolumn[Accepted]=1 and $studentcolumn[Returning]=1 and School_family_Withdrawn=0";

    $joinInfo = array( array (
	'join_table' => 'School_family',
	'join_field' => array('Withdrawn'),
	'join_method' => 'LEFT JOIN',
	'object_field_name' => array('Withdrawn'),
	'compare_field_table' => 'Familyid',
	'compare_field_join' => 'id'
	) );
    $students = DBUtil::selectExpandedObjectArray ("School_student", $joinInfo,
            $where, 'ClassYear DESC, Teacher, LastName, FirstName', -1, -1, '',null, null,
            array('id', 'FirstName', 'LastName', 'ClassYear', 'Teacher', 'Returning', 'LastSaveValid', 'lu_date')
            );
            //array('id', 'AppDate', 'Returning'. 'FirstName') );

    $render = pnRender::getInstance('School', false);
    $render->assign('students', $students);
    $render->assign('EnrollStart', EnrollStart() );
    RenderSchoolYear($render);
    return $render->fetch('School_admin_classlist.html');

}

function School_admin_export()
{

    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    // $table = FormUtil::getPassedValue('table');

    // We are hardcoding for now, goal is to make this a generic function
    // This is the 'main' table we are going to export.
    $table='student';
    $tables = pnDBGetTables();
    $ContactCol = $tables['School_emergencyContact_column'][familyid];

    $tmp = 'School_' . $table . '_column_def';
    $tableDef = $tables[$tmp];

    if (!is_array($tableDef)) return "Error -- table '$table' does not exist";
    /* Set up headers for CSV file output */
    $filename = $table . '-' . date('Y-m-d') . '.csv';
    //Read in field list
    $csvNames = School_ReadCsvNames("ExportStudents.txt");
    if (!$csvNames) return false;

    // Array to translage married status to number, hack for now
    $MarStatus = array (
        'Single'  => 1,
        'Married' => 2,
        'Separated'  => 3,
        'Divorced'   => 4,
        'Deceased '  => 5,
    );
    $EnrollStart = EnrollStart();
    // Now we set up any tables we are going to join
    $joinTables = array('School_family' => 'id', 'School_tuition' => 'id');
    $joinInfo = array();
    foreach ($joinTables as $join_table => $compare_field_join) {
        $join_field = array();
        $object_field_name = array();
        foreach ($tables[$join_table . '_column_def'] as $name => $type ) {
            //if ($name == 'obj_status') break;
            if ($name != $compare_field_join) {
                $name2 = $join_table . '_' . $name;
                $tableDef[$name2] = $type;
                $join_field[] = $name;
                $object_field_name[] = $name2;
            }
        }
        $joinInfo[] = array(
            'join_table' => $join_table,
            'join_field' => $join_field,
            'join_method' => 'LEFT JOIN',
            'object_field_name' => $object_field_name,
            'compare_field_table' => 'Familyid',
            'compare_field_join' => $compare_field_join,
            );

    }

    $familycolumn = $tables['School_student_column'];
    $where = "$familycolumn[Accepted]=1";

    $objArray = DBUtil::selectExpandedObjectArray ("School_student", $joinInfo,
            $where, '', -1, -1, '',null, null, null );

    header('Content-Type: text/csv');
    header("Content-Disposition: attachment; filename=\"$filename\"");

    /* Output CSV header line */
    $FirstCol = true;
    foreach ( $csvNames as $col) {
        // Print comma between fields (before all except first)
	if ($FirstCol) $FirstCol = false; else print ',';
        print '"' . $col . '"';
    }
    print "\n";

    /* Now output the data in CSV format */
    $pattern = array ('/"/', '/,/');
    $replace = array('""', '');
    foreach ($objArray as $rec) {
        // Add text to Acct & Routing Numbers so Excel doesn't convert to scientific notation.
        if ($rec['School_tuition_AcctNum']) {
            $rec['School_tuition_AcctNum'] = 'No. ' . $rec['School_tuition_AcctNum'];
        }
        if ($rec['School_tuition_RoutingNum']) {
            $rec['School_tuition_RoutingNum'] = 'No. ' . $rec['School_tuition_RoutingNum'];
        }
        $rec['School_family_MotherStatus'] = $MarStatus[$rec['School_family_MotherStatus']];
        $rec['School_family_FatherStatus'] = $MarStatus[$rec['School_family_FatherStatus']];
	if ($rec['School_family_cr_date'] > $EnrollStart) {
	    $rec['NewFamily'] = 'New';
	}
	if ($rec['cr_date'] > $EnrollStart) {
	    $rec['NewStudent'] = 'New';
	}

	$familyid = $rec['Familyid'];
	$where = "WHERE $ContactCol=$familyid";
        $contactData = DBUtil::selectObjectArray ('School_emergencyContact', $where);
	$i = 0;
	foreach ($contactData as $crec) {
	    $i++;
	    foreach ($crec as $key => $value) {
		$key = $key . $i;
		$rec[$key] = $value;
	    }
	}

        $FirstCol = true;
	foreach ( $csvNames as $col) {
	    // Print comma between fields (before all except first)
	    if ($FirstCol) $FirstCol = false; else print ',';

	    $def = $tableDef[$col];
            if (substr($def,0,1) == 'C') {
                print '"' . preg_replace($pattern, $replace, $rec[$col]) . '"';
            } else {
                print $rec[$col];
            }
        }
        print "\n";
    }

    return true;


}

function School_admin_import()
{
    $file = "Imports/test.csv";
    if (($handle = fopen($file, "r")) == FALSE) {
	return LogUtil::registerError("Error opening file '$file' .");
    }
    
    // Read header line into array.
    $fields = fgetcsv($handle, 0, ",", '"');
    $numfields = count($fields);
    
    // Now read each record.
    while (($data = fgetcsv($handle, 0, ",", '"')) !== FALSE) {
        $num = count($data);
	echo "<p>$numfields $num ";
	if ($num > $numfields) $num = $numfields;
	$RawObj = array();
	for ($i=0; $i<$num;$i++) {
	    $RawObj[$fields[$i]] = $data[$i];
	}

	if ($RawObj['id'] && $RawObj['Teacher']) {
	    $UpdateObj = array(
		'id' => $RawObj['id'],
		'Teacher' => $RawObj['Teacher'],
	    );
	    DBUtil::updateObject ($UpdateObj, 'School_student');
	}
	
    }
    fclose($handle);
    LogUtil::registerStatus("File $file imported.");
    return pnRedirect( pnModUrl('School', 'admin', 'main'));
    
}

function School_admin_emergencyforms()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $Render = pnRender::getInstance('School');
    $Render->assign('Families', pnModAPIFunc('School', 'admin', 'LoadEmergencyForms'));
    RenderSchoolYear($Render);
    return  $Render->fetch('School_admin_emergencyforms.htm');
   
}

function School_admin_emergencysearch()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $Render = pnRender::getInstance('School');
    $familyname = FormUtil::getpassedValue('familyname');
    if ($familyname) {
        $table = pnDBGetTables();
        $familycolumn = $table['School_family_column'];
        $where = $familycolumn['LastName'] . " = '$familyname'";
        $Render->assign('Families', pnModAPIFunc('School', 'admin', 'LoadEmergencyForms',
                array('where' => $where)
                ));
    }

    RenderSchoolYear($Render);
    return  $Render->fetch('School_admin_emergencysearch.htm');
   
}

function School_admin_modifyconfig()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }
    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_configHandler();
    return $render->pnFormExecute('School_admin_modifyconfig.html', $formobj);
}

function School_admin_districts()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $districts = DBUtil::selectObjectArray ('School_districts', '', 'Name');
    
    $render = FormUtil::newpnForm('School');
    $render->assign('Districts', $districts);
    $editid = FormUtil::getPassedValue('editid');
    $formobj = new School_admin_districtHandler();
    if ($editid) $formobj->id = $editid;
    return $render->pnFormExecute('School_admin_districts.html', $formobj);
}

function School_admin_teachers()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $teachers = DBUtil::selectObjectArray ('School_teachers', '', 'Grade, LastName');
    $render = FormUtil::newpnForm('School');
    $render->assign('Teachers', $teachers);
    $editid = FormUtil::getPassedValue('editid');
    $formobj = new School_admin_teacherHandler();
    if ($editid) $formobj->id = $editid;
    return $render->pnFormExecute('School_admin_teachers.html', $formobj);

}

function School_admin_classparents()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return pnVarPrepHTMLDisplay(_MODULENOAUTH);
    }

    $cps = pnModAPIFunc('School', 'user', 'GetClassParents') ;
    $render = FormUtil::newpnForm('School');
    $render->assign('ClassParents', $cps);
    $editid = FormUtil::getPassedValue('editid');
    $formobj = new School_admin_classparentHandler();
    if ($editid) $formobj->id = $editid;
    return $render->pnFormExecute('School_admin_classparents.html', $formobj);

}

function School_ReadCsvNames($filename)
{
    $csvNames = array();
    $fullname = "/home/vwww/test.resurrectionschool/modules/School/" . $filename;
    $h = fopen($fullname, 'r') ;

    if (!$h) {
	LogUtil::registerError("Unable to open file '$filename'.");
	return false;
    }
	
    // $HeaderLine = fgets($h, 4096);
    while (!feof($h)) {
	$l = chop(fgets($h, 4096));
	if ($l && ( substr($l,0,1) != '#' ) ) {
	    $csvNames[] = $l;
	}
    }
    fclose($h);
    return $csvNames;
}
