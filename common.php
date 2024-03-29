<?php
/* 
 * Common functions not worth going through the API for
 *
 */

/* Take a list of items and format it
 * for a pnForm select list.
 */
function initListValues($list, $firstnull)
{
        $temp = array();
	if ($firstnull ) $temp[] = array('text' => '', 'value' => '');
        foreach ($list as $item)
                $temp[] = array('text' => $item, 'value' => $item);

        return $temp;
}

function DirectoryEditDate()
{
        return pnModGetVar('School', 'DirectoryEditDate'); //'yyyy-mm-dd'
}

/* The year enrollments are being accepted for */
function EnrollYear()
{
        return pnModGetVar('School', 'NextYearStart');  //2011
}

function RenderSchoolYear(&$Render)
{
    $Render->assign(ThisYear, pnModGetVar('School', 'ThisYear'));  //2011-2012
    $Render->assign(NextYear, pnModGetVar('School', 'NextYear'));  //2011-2012
    $Render->assign(NextYearStart, pnModGetVar('School', 'NextYearStart'));  //2011
}

function EnrollStart()
{
    return pnModGetVar('School', 'EnrollStart');  //2010-12-01
}

function LatestAlumniClass()
{
    return pnModGetVar('School', 'LatestAlumniClass');
}

function Year2Grade($class, $promote)
{
    $mon = date('n');
    $year = date('Y');
    if ( !is_numeric($class) || ($class < $year) || ($class > $year + 11) ) return 'Err';
    if ($mon > 6) $year++;
    $d = $class - $year;
    if ($promote && $class != EnrollYear()) $d--;
    if ($d < 8) {
            $grade = 8 - $d;
    } else {
            $g = array('K', 'PK4', 'PK3', 'New');
            $grade = $g[$d - 8];
    }
    return $grade;
}

function Grade2Year($grade)
{
    $mon = date('n');
    $year = date('Y');
    if ($mon > 6) $year++;

    switch ($grade) {
        case 'K':
            $d = 8;
            break;
        case 'PK4':
            $d = 9;
            break;
        case 'PK3':
            $d = 10;
            break;
        case 'New':
            $d = 11;
            break;
        case ( ($grade>0) && ($grade<9)):
            $d = 8 - $grade;
            break;
        default:
            return "Err";
    }

    return EnrollYear() + $d;
}

/*
 * Return an item-list of the valid grades.
 */
function GradeItems()
{
        return initListValues(array('', 'PK3', 'PK4', 'K', 1,2,3,4,5,6,7,8));
}

function School_checkuser(&$user, &$familyid, $application)
{
    // Check for family ID
    $user = pnUserGetVar('uid');
    if ($user <= 1) {
        $url = pnModUrl('users', 'user', 'loginscreen',
                array( 'returnpage' => urlencode(pnGetCurrentURI()),
                     )
        );
        return pnRedirect($url);
    }

    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_READ)) {
        return __("Not authorised to access School module.");
    }

    $uservars = pnUserGetVars($user);

    $attr = $uservars['__ATTRIBUTES__'];
    $familyid = $attr['FamilyID'];
    if ($application) return false;
    
    if ( $familyid < 1 ) {
        return "Your account is not configured for use of forms. Please contact webmaster@www.resurrectionschool.com for assistance.";
    }
    return false;




}

function School_initStudent($render){

        $Sacraments=array('Baptism' =>'Baptism', 'Reconciliation'=>'Reconciliation',
            'Communion'=>'First Holy Communion', 'Confirmation' => 'Confirmation');
        $parents = array('Mother', 'Father');
        $Evaluations = array('Edu'=>'Educational', 'Psych'=>'Psychological',
            'Speech'=>'Speech','Other'=>'Other',
            );
        $render->assign( array(
            'Sacraments' => $Sacraments,
            'Parents' => $parents,
            'Evaluations' => $Evaluations,
            ) );
        return;
}


?>

