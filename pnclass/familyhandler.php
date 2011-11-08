<?php

class School_user_familyHandler extends pnFormHandler
{

  var $showId;
  var $familyid;
  var $ShouldInsert = false;
  var $redirect;
  
  function initialize(&$render)
  {

      /* Load Data from Database */
    $familyid = $this->familyid;
    if ($familyid > 0) {
	$formData = DBUtil::selectObjectByID('School_family', $familyid);
	if (is_array($formData)) {
	    $render->assign($formData);
	} else {
	    $this->ShouldInsert = true;
	}
    } else {
	$this->ShouldInsert = true;
    }

    // Load Districts for dropdown
    $districtsObj = DBUtil::selectObjectArray ('School_districts', '', 'Name');
    $districtsItems = array('','');
    foreach ($districtsObj as $obj) {
	    $districtsItems[] = array(
		    'text'  => $obj['Name'],
		    'value' => $obj['Code'],
		    );
    }
    $districtsItems[] = array('text' => 'Other', 'value' => 'Other');
    
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
    $yesno = initListValues(array('No', 'Yes'));
    $render->assign( array(
        'Lang' => 'English',
        'Religion' => 'Catholic',
        //'Parish' => 'Resurrection',
        'GenderItems' => $gender,
	'DistrictItems' => $districtsItems,
        'Sacraments' => $Sacraments,
        'Parents' => $parents,
        'MotherStatusItems' => $MarStat,
        'FatherStatusItems' => $MarStat,
        'MotherHasAddressItems' => $yesno,
        'FatherHasAddressItems'  => $yesno,
    ) );
    return true;
  }

  function handleCommand(&$render, &$args)
  {
    if (!$render->pnFormIsValid()) return false;

    $formData = $render->pnFormGetValues();
    if ($this->familyid > 0) {
	    $familyid = $this->familyid;
    } else {
	    $familyid = pnModAPIFunc('School', 'user', 'AssignID');
    }
    $formData[id] = $familyid;
    if ($this->ShouldInsert) {
        LogUtil::registerStatus("Added Family Information");
        DBUtil::insertObject ($formData, 'School_family', true);
    } else {
        LogUtil::registerStatus("Updated Family Information");
        DBUtil::updateObject ($formData, 'School_family');
    }
    pnModAPIFunc('School', 'user', 'MailFormUpdated',
	    array('formname'=>'Family Information', 'familyid'=>$familyid)
	    );

    if ($this->redirect) return pnRedirect($this->redirect);
    return pnRedirect ( pnModURL('School', 'user', 'main') );
  }

}

