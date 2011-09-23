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
	array('url' => pnModUrl("School", "admin", "classlist"), 'text' => 'Class List'),
	array('url' => pnModUrl("School", "admin", "emergencysearch"), 'text' => 'Emergency Contacts'),
    );
}

function School_adminapi_LoadEmergencyForms($args)
{
    $where = $args['where'];
    
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
        $where = "WHERE $StudentCol=$familyid";
        $obj['studentData'] = DBUtil::selectObjectArray ('School_student', $where);
    }
    unset($obj);

    return $objArray;
}