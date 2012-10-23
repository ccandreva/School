<?php
/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2001, PostNuke Development Team
 * @link http://www.postnuke.com
 * @version $Id: pnuserapi.php 22371 2007-07-10 12:47:15Z rgasch $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function School_userapi_SaveEmergencyForm($args)
{
    $formData = $args['formData'];
    $contactData = $formData['ContactData'];
    $studentData = $formData['StudentData'];
    unset($formData['ContactData']);
    unset($formData['StudentData']);

    $familyid = $formData['id'];
    // Save update information for emergency form
    $formData['EmergencyLastUpdate'] = date('c');
    $formData['EmergencyUpdatedBy'] = pnUserGetVar('uid');
    
    DBUtil::updateObject ($formData, 'School_family');

    $tables = pnDBGetTables();
    $ContactCol = $tables['School_emergencyContact_column'][familyid];
    $where = "WHERE $ContactCol=$familyid";
    DBUtil::deleteWhere ('School_emergencyContact', $where);
    DBUtil::insertObjectArray($contactData, 'School_emergencyContact' );

    $StudentCol = $tables['School_student_column'][familyid];
    DBUtil::updateObjectArray($studentData, 'School_student' );

    return true;
}

function School_userapi_LoadEmergencyForm($args)
{
    if ($args['familyid']) {
        $familyid = $args['familyid'];
    } else {
        $user = pnUserGetVar('uid');
        $uservars = pnUserGetVars($user);
        $attr = $uservars['__ATTRIBUTES__'];
        $familyid = $attr['FamilyID'];
    }

    if ( !($familyid >0) ) return false;

    // Only procede if familyid is a positive integer
    if (!(preg_match('/^\d+$/', $familyid))) return false;

    $formData = DBUtil::selectObjectByID('School_family', $familyid);
    if ($formData) {
        $tables = pnDBGetTables();

        $familyidCol = $tables['School_emergencyContact_column'][familyid];
        $where = "WHERE $familyidCol=$familyid";
        $contactData = DBUtil::selectObjectArray ('School_emergencyContact', $where, 'id');
        $formData['ContactData'] = $contactData;

	$formData['StudentData'] = pnModAPIFunc('School', 'user', 'GetStudents', 
	    array('familyid' => $familyid, 'status' => 'active'));
	
    } else {
        // Insert empty object, populated from account data
        $formData = array(
            'id'        => $familyid,
            'LastName'  => $attr['Lastname'],
            'Phone'     => $attr['phone'],
            'Address'   => $attr['Address'],
            'City'      => $attr['City'],
            'State'     => $attr['State'],
            'Zip'       => $attr['Zip'],
        );
        DBUtil::insertObject($formData, 'School_family', true);
        $formData['ContactData'] = array();
        $formData['StudentData'] = array();
    }

    return $formData;
}

function School_userapi_GetStudents($args)
{
    if ($args['familyid']) {
        $familyid = $args['familyid'];
    } else {
        $user = pnUserGetVar('uid');
        $uservars = pnUserGetVars($user);
        $attr = $uservars['__ATTRIBUTES__'];
        $familyid = $attr['FamilyID'];
    }

    if ( !($familyid >0) ) return false;

    // Only procede if familyid is a positive integer
    if (!(preg_match('/^\d+$/', $familyid))) return false;

    if ($args['columnArray']) {
	$columnArray = $args['columnArray'];
    } else {
	$columnArray = null;
    }
    if ($args['orderby']) {
	$orderby = $args['orderby'];
    } else {
	$orderby = 'FirstName';
    }
    
    $tables = pnDBGetTables();
    $cols = &$tables['School_student_column'];
    
    $familyidCol = $cols[Familyid];
    $YearCol = $cols['ClassYear'];
    $ReturningCol = $cols['Returning'];
    $where = "WHERE $familyidCol=$familyid";

    switch($args['status'])
    {
	case 'active';
	    $where .= " and $ReturningCol=true and $YearCol>" . LatestAlumniClass();
	    break;
    }
    
    // $showcols = array($cols[id], $cols[FirstName], $cols[Grade], $cols[Teacher]);
    $studentData = DBUtil::selectObjectArray ('School_student', $where, $orderby,
	    	      -1, -1, '',null, null, $columnArray);

    return $studentData;

}

function School_userapi_LoadDirectory($args)
{
    if ($args['familyid']) {
        $familyid = $args['familyid'];
    } else {
        $user = pnUserGetVar('uid');
        $uservars = pnUserGetVars($user);
        $attr = $uservars['__ATTRIBUTES__'];
        $familyid = $attr['FamilyID'];
    }

    if ( !($familyid >0) ) return false;

    // Only procede if familyid is a positive integer
    if (!(preg_match('/^\d+$/', $familyid))) return false;

    $formData = DBUtil::selectObjectByID('School_directory', $familyid);
    return $formData;
}

function School_userapi_MailFormUpdated($args)
{
    $formname = $args['formname'];
    // $familyname = $args['familyname'];
    $familyid = $args['familyid'];
    if (isset($args['extratext']))  $extratext = $args['extratext'];
    if (isset($args['firstname'])) {
	$firstname=$args['firstname'];
    }
    
    $obj = DBUtil::selectObjectByID('School_family', $familyid, 'id', array('LastName'));
    $familyname = $obj['LastName'];
    $subject = "$formname form updated for $familyname";
    if ($firstname) $subject .= ", $firstname";
    $toaddress= array('Doreenp@resurrectionschool.com'); //, 'chris@westnet.com');
    // $toaddress= array( 'chris@westnet.com');
    
    $mail = "\nThe \"$familyname\" family ($familyid) has updated their $formname form";
    if ($firstname) $mail .= " for $firstname";
    $mail .= ".\n";
    if ($extratext) $mail .= $extratext;
    pnModAPIFunc('Mailer', 'user', 'sendmessage',
	    array('toaddress' => $toaddress,
		'subject' => $subject,
		'fromname' => "Resurrection Website",
		'fromaddress' => 'webmaster@resurrectionschool.com',
		'body' => $mail,
		'html' => false )
	    );
}

function School_userapi_AssignID($args)
{
    $familyid = DBUtil::selectFieldMax('School_family', 'id') + 1;
    if ($familyid < 910000) $familyid = 910000;
    pnUserSetVar('FamilyID', $familyid);
    return $familyid;
}

function School_userapi_GetTeacherItems($args)
{
    if ($args['text']) $text = true;
    if ($args['where']) $where = $args['where']; else $where = '';
    $teachers = DBUtil::selectObjectArray('School_teachers', $where, 'Grade, LastName',
	   -1, -1, '',null, null,
	    array('id', 'Title', 'LastName', 'Grade')
	    );
    $teacherItems = array(array('text'=>'', 'value'=>''));
    foreach ($teachers as $teacher) {
	if ($teacher['id'] > 0)
            $name = $teacher['Title'] . ' ' . $teacher['LastName'];
            if ($text) $value = $name;
            else $value = $teacher['id'];
            $teacherItems[] = array(
                'text' => $name,
                'value' => $value
            );
    }

    return $teacherItems;
}

function School_userapi_GetClassParents($args)
{
    //$cps = DBUtil::selectObjectArray ('School_classparents', '', 'Grade, Name');
    
    $joininfo = array ( array (
	'join_table' => 'School_teachers',
	'join_field' => array('Name'),
	'join_method' => 'LEFT JOIN',
	'object_field_name' => array('TeacherName'),
	'compare_field_table' => 'Teacher',
	'compare_field_join' => 'id',
    ));

    $cps = DBUtil::selectExpandedObjectArray ('School_classparents', $joininfo,
	    '', 'Grade, Name');
    
    return $cps;

}
