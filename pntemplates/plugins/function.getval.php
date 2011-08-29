<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



function smarty_function_getval($args, &$smarty)
{
    $var = $args['var'];
    $tmp =  $smarty->get_template_vars($var);
    if ( strlen($tmp) == 0) $tmp = '&nbsp;';
    if ($args['assign']) {
        $smarty->assign($args['assign'], $tmp);
        return;
    }
    return $tmp;
}


?>
