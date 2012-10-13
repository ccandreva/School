<?php
class School_user_configHandler extends pnFormHandler
{

  var $fields = array('ThisYear', 'NextYear', 'NextYearStart', 'EnrollStart', 'ReregOpen', 'LatestAlumniClass');
  
  function initialize(&$render)
  {
      $render->assign(pnModGetVar('School'));
      return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();
    
    // Make sure we don't create invalid configvars
    $config = array();
    foreach ($this->fields as $field) {
        $config[$field] = $formData[$field];
    }
    pnModSetVars('School', $config);
    LogUtil::registerStatus('Configuration Saved.');
    return $result;
  }

}



?>
