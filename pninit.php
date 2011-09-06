<?php
/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2001, PostNuke Development Team
 * @link http://www.postnuke.com
 * @version $Id: pninit.php 22371 2007-07-10 12:47:15Z rgasch $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 */

function School_init()
{
    pnModSetVar('School', 'modulestylesheet', 'School.css');

    // Get the db driver
    $dbdriver = DBConnectionStack::getConnectionDBDriver();

    if ( !DBUtil::createTable('School_directory') ) return false;
    if ( !DBUtil::createTable('School_family')) return false;
    if ( !DBUtil::createTable('School_student')) return false;
    // if ( !DBUtil::createTable('School_emergencyform') ) return false;
    // if ( !DBUtil::createTable('School_emergencyStudent') ) return false;
    if ( !DBUtil::createTable('School_emergencyContact') ) return false;
    // if (!DBUtil::createIndex('Indfamilyid', 'School_emergencyform', 'familyid')) return false;
    // if (!DBUtil::createIndex('Indfamilyid', 'School_emergencyStudent', 'familyid')) return false;
    if (!DBUtil::createIndex('Indfamilyid', 'School_emergencyContact', 'familyid')) return false;
    if ( !DBUtil::createTable('School_tuition')) return false;
    if ( !DBUtil::createTable('School_districts')) return false;
    if ( !DBUtil::createTable('School_teachers')) return false;

    return true;
}

function School_upgrade($oldversion)
{
    switch($oldversion)
    {
      case '0.0';
      case '0.1';
        if ( !DBUtil::createTable('School_directory') ) return false;
      case '0.3';
        if (!DBUtil::changeTable('School_family')) return false;
      case '0.4.2';
        if ( !DBUtil::changeTable('School_student') ) return false;
      case '0.4.3';
        if ( !DBUtil::createTable('School_tuition') ) return false;
      case '0.4.4';
        if ( !DBUtil::createTable('School_districts') ) return false;
      case '0.4.5';
	if ( !DBUtil::createTable('School_teachers')) return false;
    }

    return true;
}

function School_delete()
{
/* We don't EVER actually want to delete this module now.
    pnModDelVar('School');
    DBUtil::dropTable('School_emergencyform');
    DBUtil::dropTable('School_emergencyStudent');
    DBUtil::dropTable('School_emergencyContact');
 *
 */
    return true;
}
