<?php
/**
 * Zikula - Resurrection Custom Module
 *
 * @copyright (c) 2011 Chris Candreva
 */

// Include pnForm in order to be able to inherit from pnFormHandler
// DO NOT use require_once since this has a different "once" logic than the PostNuke loader.
// (and Loader::requireOnce is used internally by PostNuke)
Loader::requireOnce('includes/pnForm.php');
require_once('common.php');
require_once('pnclass/emergencyformhandler.php');
require_once('pnclass/directoryformhandler.php');
require_once('pnclass/familyhandler.php');
require_once('pnclass/studenthandler.php');
require_once('pnclass/newstudenthandler.php');
require_once('pnclass/tuitionhandler.php');

function School_user_main()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $Render = pnRender::getInstance('School');
    $familyData = DBUtil::selectObjectByID('School_family', $familyid);
    // If they are not already accepted, redirect to application portal;
    if ($familyData{'Accepted'} != 1) {
        return pnredirect(pnModUrl('School', 'user', 'apply'));
    }

    $students = pnModAPIFunc('School', 'user', 'GetStudents', array('familyid' => $familyid));
    //$Render->assign('familyid', $familyid);
    $Render->assign($familyData);
    $Render->assign('Students', $students);
    $tuitionData = DBUtil::selectObjectByID('School_tuition', $familyid);
    $Render->assign('Tuition', $tuitionData);
    $Render->assign('EnrollStart', EnrollStart());
    RenderSchoolYear($Render);
    return $Render->fetch('School_user_main.htm');
}

function School_user_test()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return __("Not authorised to access School module.");
    }

    $user = pnUserGetVar('uid');
    $uservars = pnUserGetVars($user);
        foreach ($uservars as $k => $v)
          $output .= "$k -> $v<br />\n";
        $output .= "<hr /> \n";
        $attr = $uservars['__ATTRIBUTES__'];
        foreach ($attr as $k => $v)
          $output .= "$k -> $v<br />\n";

        return $output;
}

function School_user_apply()
{
    $ret = School_checkuser($user, $familyid, true);
    if ($ret) return $ret;

    $Render = pnRender::getInstance('School');
    if ($familyid == 0) {
        LogUtil::registerStatus("To begin the applicaiton process, please enter the following general family information.");
        return pnRedirect(pnModURL('School', 'user', 'addfamily' ));
    }

    if ($familyid > 0) {
        $familyData = DBUtil::selectObjectByID('School_family', $familyid);
	
	// If they are already accepted, redirect to registation portal;
	if ($familyData{'Accepted'} == 1) {
	    return pnredirect(pnModUrl('School', 'user', 'main'));
	}
	
        $Render->assign($familyData);
        $students = pnModAPIFunc('School', 'user', 'GetStudents', array('familyid' => $familyid));
	if (is_array($students)) $Render->assign('Students', $students);
    }
    
    $Render->assign('EnrollStart', EnrollStart());
    RenderSchoolYear($Render);
    return $Render->fetch('School_user_apply.htm');
}

/* function School_user_application()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_READ)) {
        return __("Not authorised to access School module.");
    }

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_applicationHandler();
    return $render->pnFormExecute('School_user_application.htm', $formobj);
} */

function School_user_emergencyForm()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_emergencyHandler();
    return $render->pnFormExecute('School_user_emergencyForm.html', $formobj);
}

function School_user_directoryForm()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_directoryHandler();
    $formobj->familyid = $familyid;
    $formobj->showId = true;
    return $render->pnFormExecute('School_user_directoryForm.html', $formobj);
}

function School_user_addfamily()
{
    $ret = School_checkuser($user, $familyid, true);
    if ($ret) return $ret;

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_familyHandler();
    $formobj->familyid = $familyid;
    $formobj->redirect = pnModURL('School', 'user', 'apply' );
    return $render->pnFormExecute('School_user_editfamily.htm', $formobj);
}

function School_user_editfamily()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_familyHandler();
    $formobj->familyid = $familyid;
    // $formobj->showId = true;
    return $render->pnFormExecute('School_user_editfamily.htm', $formobj);
}

function School_user_editstudent()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $studentid = FormUtil::getPassedValue('id');

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_studentHandler();
    $formobj->familyid = $familyid;
    $formobj->studentid = $studentid;
    return $render->pnFormExecute('School_user_editstudent.htm', $formobj);
}

function School_user_addstudent()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_studentHandler();
    $formobj->familyid = $familyid;
    return $render->pnFormExecute('School_user_editstudent.htm', $formobj);
}

function School_user_newstudent()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_newstudentHandler();
    $formobj->familyid = $familyid;
    $formobj->studentid = FormUtil::getPassedValue('id');
    return $render->pnFormExecute('School_user_newstudent.html', $formobj);
}

/*
function School_user_familyforms()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $studentid = FormUtil::getPassedValue('id');

    $render = FormUtil::newpnForm('School');
    $formobj = new School_user_studentHandler();
    $formobj->familyid = $familyid;
    $formobj->studentid = $studentid;
    // $formobj->showId = true;
    return $render->pnFormExecute('School_user_editstudent.htm', $formobj);
}
*/

function School_user_tuition()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $studentid = FormUtil::getPassedValue('id');

    $Render = FormUtil::newpnForm('School');
    $formobj = new School_user_tuitionHandler();
    $formobj->familyid = $familyid;
    $formobj->studentid = $studentid;
    RenderSchoolYear($Render);
    // $formobj->showId = true;
    return $Render->pnFormExecute('School_user_tuition.html', $formobj);
}
