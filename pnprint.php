<?php
/**
 * Zikula - Resurrection Custom Module
 *
 * @copyright (c) 2010 Chris Candreva
 * @link http://www.postnuke.com
 * @version  Id:                                              $
 */

require_once('common.php');
// Loader::requireOnce('includes/tcpdf/config/lang/eng.php');
// Loader::requireOnce('includes/tcpdf/tcpdf.php');

/*
function School_print_student()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $studentid = FormUtil::getPassedValue('id');
    $formData = DBUtil::selectObjectByID('School_student', $studentid);
    if ($formData['Familyid'] != $familyid) {
        return "Invalid student.";
    }
    $Render = pnRender::getInstance('School');
    $Render->caching=0;

    $familyData = DBUtil::selectObjectByID('School_family', $familyid);
    $Render->assign($formData);
    $Render->assign($familyData);
    School_initStudent($Render);

    return $Render->fetch('School_print_student.htm');

}
*/

function School_print_student()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $studentid = FormUtil::getPassedValue('id');
    $studentData = DBUtil::selectObjectByID('School_student', $studentid);
    if ( SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN) ) {
	$familyid = $studentData['Familyid'];
    }
    elseif ($studentData['Familyid'] != $familyid) {
        return "Invalid student.";
    }
    $familyData = DBUtil::selectObjectByID('School_family', $familyid);
    // Rename 'Accepted' field for families so it is unique.
    $familyData['FamilyAccepted'] = $familyData['Accepted'];
    unset($familyData['Accepted']);
    
    $Render = pnRender::getInstance('School');
    $Render->caching=0;
    $Render->assign($studentData);
    $Render->assign($familyData);

    if (!$studentData['Accepted']) {
        $registerData = DBUtil::selectObjectByID('School_register', $studentid);
        $Render->assign($registerData);
    }

    School_initStudent($Render);
    RenderSchoolYear($Render);

    return $Render->fetch('School_print_student.htm');

}

function School_print_textbook()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $students = pnModAPIFunc('School', 'user', 'GetStudents', array('familyid' => $familyid));
    $returning = array();
    $notreturning = array();
    $nr = 0;
    $EnrollYear = EnrollYear();
    foreach ($students as $student) {
        if ($student[ClassYear] != $EnrollYear) {

            if ($student['Accepted'] && $student[Returning] == '') 
                return "Please fill out information for all students.";

            $name = $student[FirstName]; // . ' ' . $student[LastName];
            if ($student[Returning] == '1') {
                    $returning[] = array('Name' => $name,
                            'Grade' => $student[Grade]);
            } else {
                    $notreturning[] = array('Name' => $name,
                            'Grade' => $student[Grade],
                            'ClassYear' => $student[ClassYear],
                            'Teacher' => $student[Teacher],
                        );
                    $nr++;
            }
        }
    }


    $Render = pnRender::getInstance('School');
    $Render->caching=0;

    $familyData = DBUtil::selectObjectByID('School_family', $familyid);
    $Render->assign($familyData);

    $tuitionData = DBUtil::selectObjectByID('School_tuition', $familyid);
    if (!is_array($tuitionData))  $tuitionData[BankName] = 'Nova1';
    $Render->assign($tuitionData);

    if ($tuitionData[PaymentPref] == 'Direct') {
        $n = count($returning);
        if ($n > 3) $n == 3;
        if ($tuitionData[Parishioner]) $n = $n + 3;
        $payments = array(0,6587,12875,18026, 5013,9717,13340);
        $payment = $payments[$n] / 10 . '0';
        $Render->assign('Payment', $payment);
    }
    $Render->assign('Returning', $returning);
    RenderSchoolYear($Render);

    if ($nr > 0) $Render->assign('NotReturning', $notreturning);
    School_initStudent($Render);

   return  $Render->fetch('School_print_textbook.htm');

   
}

function School_print_emergencyforms()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $Render = pnRender::getInstance('School');
    $Render->caching=0;
    $Render->assign('Families', pnModAPIFunc('School', 'admin', 'LoadEmergencyForms'));
    RenderSchoolYear($Render);
    return  $Render->fetch('School_print_emergencyforms.htm');
   
}

function School_print_showclasslist()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = pnRender::getInstance('School', false);
    $grade = FormUtil::getPassedValue('grade');
    $args = array();
    if ($grade) $args['grade'] = $grade;
    $students = pnModAPIFunc('School', 'user', 'GetClassList', $args) ;
    $render->assign('students', $students);

    $render->assign('EnrollStart', EnrollStart() );
    RenderSchoolYear($render);
    return $render->fetch('School_print_showclasslist.html');
}

function School_print_showdirectory()
{
    $ret = School_checkuser($user, $familyid);
    if ($ret) return $ret;

    $render = pnRender::getInstance('School', false);
    $where = "School_directory_lu_date >= '" . DirectoryEditDate() . "'";
    $objArray = DBUtil::selectObjectArray ('School_directory', $where, 'FamilyName');
    $columnArray = array('id', 'FirstName', 'NickName', 'ClassYear');
    
    foreach ($objArray as &$family) {
	$family['students'] = pnModAPIFunc('School', 'user', 'GetStudents', 
	    array('familyid' => $family['id'], 'status' => 'active',
	    'columnArray' => $columnArray));
    }
    $render->assign('data', $objArray);
    $render->assign('view', $view);

    RenderSchoolYear($render);
    return $render->fetch('School_print_showdirectory.html');

}
