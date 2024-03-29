<?php
/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2001, PostNuke Development Team
 * @link http://www.postnuke.com
 * @version $Id: pnadminapi.php 22371 2007-07-10 12:47:15Z rgasch $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function School_adminapi_getlinks()
{
    return array(
	array('url' => pnModUrl("School", "admin", "showemergencyforms"), 'text' => 'Emergency Forms'),
	array('url' => pnModUrl("School", "admin", "showdirectory"), 'text' => 'Directory'),
	array('url' => pnModUrl("School", "admin", "showregistration"), 'text' => 'Registration'),
//	array('url' => pnModUrl("School", "admin", "showpendingstudents"), 'text' => 'Pending Students'),
	array('url' => pnModUrl("School", "admin", "searchstudents"), 'text' => 'Search Students'),
	array('url' => pnModUrl("School", "admin", "classlist"), 'text' => 'Class List'),
	array('url' => pnModUrl("School", "admin", "emergencysearch"), 'text' => 'Emergency Contacts'),
	array('url' => pnModUrl("School", "admin", "modifyconfig"), 'text' => 'Settings'),
	array('url' => pnModUrl("School", "admin", "districts"), 'text' => 'Districts'),
	array('url' => pnModUrl("School", "admin", "teachers"), 'text' => 'Teachers'),
	array('url' => pnModUrl("School", "admin", "classparents"), 'text' => 'Class Parents'),
    );
}

function School_adminapi_LoadEmergencyForms($args)
{
    if ($args['where']) $where = $args['where'] . ' and ';
    $where .= "School_family_Withdrawn=0";
    
    $tables = pnDBGetTables();
    $ContactCol = $tables['School_emergencyContact_column'][familyid];
    $StudentCol = $tables['School_student_column'][Familyid];

    $objArray = DBUtil::selectObjectArray ('School_family', $where, 'LastName');
	// Limit to 5 for testing //, -1, 5);

    // Loop through all Families and export
    foreach ($objArray as &$obj) {
        $familyid = $obj['id'];
        $obj['MotherWorkAddress'] = preg_replace('/(^|\s)([A-Z])([A-Z]+)/e',"'$1$2' . strtolower('\\3')", $obj['MotherWorkAddress'] );
        $obj['FatherWorkAddress'] = preg_replace('/(^|\s)([A-Z])([A-Z]+)/e',"'$1$2' . strtolower('\\3')", $obj['FatherWorkAddress'] );
        $where = "WHERE $ContactCol=$familyid";
        $obj['contactData'] = DBUtil::selectObjectArray ('School_emergencyContact', $where);
        $obj['studentData'] = pnModAPIFunc('School', 'user', 'GetStudents',
            array('familyid' => $familyid, 'status' => 'active'));
    }
    unset($obj);

    return $objArray;
}