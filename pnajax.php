<?php

/**
 * getfamilies
 * performs a family search based on the fragment entered so far
 *
 * @author Chris Candreva
 * @param fragment string the fragment of the familyname entered
 * @return void nothing, direct ouptut using echo!
 */
function School_ajax_getfamilies()
{
    if (!SecurityUtil::checkPermission('School::', '::', ACCESS_ADMIN)) {
        return true;
    }

    $fragment = FormUtil::getpassedValue('fragment');

    pnModDBInfoLoad('School');
    $pntable = pnDBGetTables();
    $familycolumn = $pntable['School_family_column'];

    $where = 'WHERE School_family_Withdrawn=0 and ' . $familycolumn['LastName'] . ' REGEXP \'(' . DataUtil::formatForStore($fragment) . ')\'';
    $results = DBUtil::selectObjectArray('School_family', $where);

    $out = '<ul>';
    // $out .= '<li>Lincoln<input type="hidden" id="Lincoln" value="666" /></li>';
    $prevname = '';
    if (is_array($results) && count($results) > 0) {
        foreach($results as $result) {
            $lastname = DataUtil::formatForDisplay($result['LastName']);
	    if ($lastname != $prevname) {
		$out .= '<li>' . $lastname .'<input type="hidden" id="' . $lastname . '" value="' . $result['id'] . '" /></li>';
		$prevname = $lastname;
	    }
        }
    }
    $out .= '</ul>';
    echo DataUtil::convertToUTF8($out);
    return true;
}
