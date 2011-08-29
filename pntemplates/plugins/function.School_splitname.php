<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_function_School_splitname($args, &$smarty)
{

    $students = $args['students'];
    $names = explode(',', $students);

    if ( strlen($students) <= 40 || (count($names) < 2) ) {
        $smarty->assign('Students1', $students);
        $smarty->assign('Students2', '');
    } else {
        $s1 = array();
        $s2 = array();
        $l1 = 0;
        foreach ($names as $name) {
            $l2 = strlen($name);
            if ( ($l1 + $l2) <= 40) {
                    $s1[] = $name;
                    $l1 += $l2;
            } else {
                    $s2[] = $name;
                    $l1 = 100;
            }
        }
        $smarty->assign('Students1', implode(',', $s1));
        $Students2 = preg_replace('/^\s+/', '', implode(',', $s2));

        $smarty->assign('Students2', $Students2);
    }
}


?>
