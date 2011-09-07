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
	array('url' => pnModUrl("School", "print", "emergencyforms"), 'text' => 'Emergency Contacts'),
    );
}
