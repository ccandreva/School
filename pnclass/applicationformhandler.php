<?php

class School_user_applicationHandler extends pnFormHandler
  {

  function initialize(&$render)
  {
        $gender = array ( array(),
            array('text' => 'Male', value => 'Male'),
            array('text' => 'Female', value => 'Female'),
        );
        $Sacraments=array('Bap' =>'Baptism', 'Recon'=>'Reconciliation',
            'Communion'=>'First Holy Communion', 'Confirmation' => 'Confirmation');
        $parents = array('Mother', 'Father');
        $MarStat = initListValues(array('Married','Single','Separated','Divorced','Deceased'));
        $Custodial = initListValues(array('N/A','Mother','Father'));
        $Evaluations = array('Edu'=>'Educational', 'Psych'=>'Psychological',
            'Speech'=>'Speech','Other'=>'Other',
            );
        $yesno = initListValues(array('', 'Yes', 'No'));
        $render->assign( array(
            'Lang' => 'English',
            'Religion' => 'Catholic',
            'Parish' => 'Resurrection',
            'GenderItems' => $gender,
            'Sacraments' => $Sacraments,
            'Parents' => $parents,
            'MotherStatusItems' => $MarStat,
            'FatherStatusItems' => $MarStat,
            'CustodialParentItems' => $Custodial,
            'DistrictEvalItems' => $yesno,
            'PrivateEvalItems' => $yesno,
            'Evaluations' => $Evaluations,
            'IEPItems' => $yesno,
            '504PlanItems' => $yesno,
        ) );
    return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    return true;
  }

}
