<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function smarty_modifier_csv($string)
{

    return '"' . preg_replace('/"/', '""', $string) . '"';

}


?>
