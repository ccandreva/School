<?php

/**
 * Zikula - Resurrection Custom Module
 *
 * @copyright (c) 2011 Chris Candreva
 * @link http://www.zikula.org/
 * @version  Id:                                              $
 */

require_once('modules/School/common.php');

/**
 * Return block info
 */
function school_formstatusblock_info()
{
    return array(
	'module'    => 'School',
	'text_type' => 'Form Status',
	'text_type_long' => 'Form Status block',
	'allow_multiple'    => false,
	'form_content'    => false,
	'form_refresh'    => false,
	'show_preview'    => false,
	
    );
}

/**
 * initialise the block
 *
 * Adds the blocks security schema to the Zikula environment
*/
function school_formstatusblock_init()
{
    pnSecAddSchema('Formstatusblock::', 'Block title::');
}

/**
 * Display the block
 *
 * Display the output of the Form Status block
*/
function school_formstatusblock_display($blockinfo)
{
    if (!SecurityUtil::checkPermission('Formstatusblock::', "$row[title]::", ACCESS_READ)) {
        return;
    }
    if (!pnUserLoggedIn()) return;
    $ret = School_checkuser($user, $familyid);
    if ($ret) return;
    $args = array('familyid' => $familyid);
    $emergencyObj =  pnModAPIFunc('School', 'user', 'LoadEmergencyForm', $args);
    $directoryObj =  pnModAPIFunc('School', 'user', 'LoadDirectory', $args);
    
    $Render = pnRender::getInstance('School');
    $startdate = '2011-08-01';
    $n=0;
    if ($emergencyObj['EmergencyLastUpdate'] < $startdate) {
        $Render->assign('EmergencyDate' ,$emergencyObj['EmergencyLastUpdate']);
	$n++;
    }
    if ($directoryObj['lu_date'] < $startdate) {
        $Render->assign('DirectoryDate' ,$directoryObj['lu_date']);
	$n++;
    }
    if ($n==0) return;
    
    $Render->assign('familyid', $familyid);

    $blockinfo['content'] = $Render->fetch('School_block_formstatus.html');
    return themesideblock($blockinfo);
    

}
