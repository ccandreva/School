<?php
/* 
 * School module for Resurrection School
 * Table Definitions
 */

function School_pntables()
{
    //Initialize table array
    $table = array();

    $table['School_emergencyContact'] = DBUtil::getLimitedTablename('School_emergencyContact');
    $table['School_directory'] = DBUtil::getLimitedTablename('School_directory');

    $table['School_emergencyContact_column'] = array(
        'id'                => 'school_emergencyContact_id',
        'familyid'                => 'school_emergencyContact_familyid',
        'ContactName'       =>  'school_emergencyContact_ContactName',
        'ContactPhone'       =>  'school_emergencyContact_ContactPhone',
        'ContactCell'       =>  'school_emergencyContact_ContactCell',
        'ContactWork'       =>  'school_emergencyContact_ContactWork',
        'ContactRelation'       =>  'school_emergencyContact_ContactRelation',
    );

    $table['School_emergencyContact_column_def'] = array(
        'id'          => 'I NOTNULL PRIMARY AUTOINCREMENT',
        'familyid'          => 'I NOTNULL',
        'ContactName'       => 'C(255)',
        'ContactPhone'       => 'C(255)',
        'ContactCell'       => 'C(255)',
        'ContactWork'       => 'C(255)',
        'ContactRelation'       => 'C(255)',
    );

    $table['School_directory_column'] = array(
        'id'        => 'School_directory_id',
	'FamilyName'		=> 'School_directory_FamilyName',
	'Address'		=> 'School_directory_Address',
	'City'		=> 'School_directory_City',
	'State'		=> 'School_directory_State',
	'Zip'		=> 'School_directory_Zip',
        'Students'      => 'School_directory_Students',
	'Phone'		=> 'School_directory_Phone',
	'Email'		=> 'School_directory_Email',
	'BusNumber'		=> 'School_directory_BusNumber',
    );
    $table['School_directory_column_def'] = array(
        'id'            => 'I NOTNULL PRIMARY',
	'FamilyName'		=> 'C(50)',
	'Address'		=> 'C(50)',
	'City'		=> 'C(50)',
	'State'		=> 'C(2)',
	'Zip'		=> 'C(10)',
        'Students'      => 'C(50)',
	'Phone'		=> 'C(15)',
	'Email'		=> 'C(50)',
	'BusNumber'		=> 'C(10)',
    );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_directory_column'],
            'School_directory_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_directory_column_def']
            );


    /*** Family Information Table  ***/
    $table['School_family'] = DBUtil::getLimitedTablename('School_family');

    $table['School_family_column'] = array(
	'id'        => 'School_family_id',
	'LastName'		=> 'School_family_LastName',
	'Phone'			=> 'School_family_Phone',
	'Cell'			=> 'School_family_Cell',
	'Lang'			=> 'School_family_Lang',
	'BusNumber'		=> 'School_family_BusNumber',
	'Address'		=> 'School_family_Address',
	'Apt'			=> 'School_family_Apt',
	'City'			=> 'School_family_City',
	'State'			=> 'School_family_State',
	'Zip'			=> 'School_family_Zip',
	'District'		=> 'School_family_District',
	'MotherFirstName'	=> 'School_family_MotherFirstName',
	'MotherMiddleName'	=> 'School_family_MotherMiddleName',
	'MotherLastName'	=> 'School_family_MotherLastName',
	'MotherMaidenName'	=> 'School_family_MotherMaidenName',
        'MotherStatus'          => 'School_family_MotherStatus',
        'MotherHasAddress'      => 'School_family_MotherHasAddress',
        'MotherAddress'         => 'School_family_MotherAddress',
        'MotherApt'             => 'School_family_MotherApt',
        'MotherCity'            => 'School_family_MotherCity',
        'MotherState'           => 'School_family_MotherState',
        'MotherZip'		=> 'School_family_MotherZip',
	'MotherPhone'		=> 'School_family_MotherPhone',
        'MotherBirthplace'      => 'School_family_MotherBirthplace',
        'MotherReligion'        => 'School_family_MotherReligion',
        'MotherOccupation'      => 'School_family_MotherOccupation',
        'MotherWorkName'	=> 'School_family_MotherWorkName',
        'MotherWorkAddress'	=> 'School_family_MotherWorkAddress',
        'MotherWorkCityStateZip'=> 'School_family_MotherWorkCityStateZip',
	'MotherWorkPhone'	=> 'School_family_MotherWorkPhone',
	'MotherCell'		=> 'School_family_MotherCell',
        'MotherEmail'           => 'School_family_MotherEmail',
	'FatherFirstName'	=> 'School_family_FatherFirstName',
	'FatherMiddleName'	=> 'School_family_FatherMiddleName',
	'FatherLastName'	=> 'School_family_FatherLastName',
        'FatherStatus'          => 'School_family_FatherStatus',
        'FatherHasAddress'      => 'School_family_FatherHasAddress',
        'FatherAddress'         => 'School_family_FatherAddress',
        'FatherApt'             => 'School_family_FatherApt',
        'FatherCity'            => 'School_family_FatherCity',
        'FatherState'           => 'School_family_FatherState',
        'FatherZip'		=> 'School_family_FatherZip',
	'FatherPhone'		=> 'School_family_FatherPhone',
        'FatherBirthplace'      => 'School_family_FatherBirthplace',
        'FatherReligion'        => 'School_family_FatherReligion',
        'FatherOccupation'      => 'School_family_FatherOccupation',
	'FatherWorkName'	=> 'School_family_FatherWorkName',
	'FatherWorkAddress'	=> 'School_family_FatherWorkAddress',
        'FatherWorkCityStateZip'=> 'School_family_FatherWorkCityStateZip',
	'FatherWorkPhone'	=> 'School_family_FatherWorkPhone',
	'FatherCell'		=> 'School_family_FatherCell',
        'FatherEmail'           => 'School_family_FatherEmail',
	'DoctorName'		=> 'School_family_DoctorName',
	'DoctorAddress'		=> 'School_family_DoctorAddress',
	'DoctorPhone'		=> 'School_family_DoctorPhone',
	'DentistName'		=> 'School_family_DentistName',
	'DentistAddress'	=> 'School_family_DentistAddress',
	'DentistPhone'		=> 'School_family_DentistPhone',
	'OrthodontistName'	=> 'School_family_OrthodontistName',
	'OrthodontistAddress'	=> 'School_family_OrthodontistAddress',
	'OrthodontistPhone'	=> 'School_family_OrthodontistPhone',
	'EmergencyLastUpdate'	=> 'School_emergency_lu_date',
	'EmergencyUpdatedBy'	=> 'School_emergency_lu_uid',
	'Accepted'		=> 'School_family_Accepted'
    );


    $table['School_family_column_def'] = array(
        'id'            => 'I NOTNULL PRIMARY AUTOINCREMENT',
	'LastName'		=> 'C(255)',
	'Phone'		=> 'C(255)',
	'Cell'		=> 'C(255)',
        'Lang'          => 'C(255)',
	'BusNumber'		=> 'C(255)',
	'Address'		=> 'C(255)',
	'Apt'		=> 'C(255)',
	'City'		=> 'C(255)',
	'State'		=> 'C(255)',
	'Zip'		=> 'C(255)',
	'District'		=> 'C(255)',
	'MotherFirstName'		=> 'C(255)',
	'MotherMiddleName'		=> 'C(255)',
	'MotherLastName'		=> 'C(255)',
	'MotherMaidenName'		=> 'C(255)',
        'MotherStatus'          => 'C(20)',
	'MotherHasAddress'		=> 'C(255)',
	'MotherAddress'		=> 'C(255)',
	'MotherApt'		=> 'C(255)',
	'MotherCity'		=> 'C(255)',
	'MotherState'		=> 'C(255)',
	'MotherZip'		=> 'C(255)',
	'MotherPhone'		=> 'C(255)',
	'MotherBirthplace'		=> 'C(255)',
	'MotherReligion'		=> 'C(255)',
	'MotherOccupation'		=> 'C(255)',
	'MotherWorkName'		=> 'C(255)',
	'MotherWorkAddress'		=> 'C(255)',
        'MotherWorkCityStateZip'    => 'C(255)',
	'MotherWorkPhone'		=> 'C(255)',
	'MotherCell'		=> 'C(255)',
	'MotherEmail'		=> 'C(255)',
	'FatherFirstName'		=> 'C(255)',
	'FatherMiddleName'		=> 'C(255)',
	'FatherLastName'		=> 'C(255)',
        'FatherStatus'          => 'C(20)',
	'FatherHasAddress'		=> 'C(255)',
	'FatherAddress'		=> 'C(255)',
	'FatherApt'		=> 'C(255)',
	'FatherCity'		=> 'C(255)',
	'FatherState'		=> 'C(255)',
	'FatherZip'		=> 'C(255)',
	'FatherPhone'		=> 'C(255)',
	'FatherBirthplace'		=> 'C(255)',
	'FatherReligion'		=> 'C(255)',
	'FatherOccupation'		=> 'C(255)',
	'FatherWorkName'		=> 'C(255)',
	'FatherWorkAddress'		=> 'C(255)',
        'FatherWorkCityStateZip'    => 'C(255)',
	'FatherWorkPhone'		=> 'C(255)',
	'FatherCell'		=> 'C(255)',
	'FatherEmail'		=> 'C(255)',
	'DoctorName'		=> 'C(255)',
	'DoctorAddress'		=> 'C(255)',
	'DoctorPhone'		=> 'C(255)',
	'DentistName'		=> 'C(255)',
	'DentistAddress'		=> 'C(255)',
	'DentistPhone'		=> 'C(255)',
	'OrthodontistName'		=> 'C(255)',
	'OrthodontistAddress'		=> 'C(255)',
	'OrthodontistPhone'		=> 'C(255)',
	'EmergencyLastUpdate'		=> 'T',
	'EmergencyUpdatedBy'		=> 'I4',
	'Accepted'		=> 'L'
    );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_family_column'],
            'School_family_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_family_column_def']
            );

    /*** Student Information Table  ***/
    $table['School_student'] = DBUtil::getLimitedTablename('School_student');

    $table['School_student_column'] = array(
        'id'            =>      'School_student_id',
        'Familyid'      =>      'School_student_Familyid',
	'AppDate'	=>	'School_student_AppDate',
        'Returning'     =>      'School_student_Returning',
	'Grade'         =>	'School_student_Grade',
	'ClassYear'         =>	'School_student_ClassYear',
	'Teacher'	=>	'School_student_Teacher',
	'LastName'	=>	'School_student_LastName',
	'FirstName'	=>	'School_student_FirstName',
	'MiddleName'	=>	'School_student_MiddleName',
	'NickName'	=>	'School_student_NickName',
	'DOB'           =>	'School_student_DOB',
	'POB'           =>	'School_student_POB',
	'BirthCertificate'           =>	'School_student_BirthCertificate',
	'Gender'	=>	'School_student_Gender',
	'Religion'	=>	'School_student_Religion',
	'Parish'	=>	'School_student_Parish',
	'Resides'	=>	'School_student_Resides',
	'Relationship'	=>	'School_student_Relationship',
	'BaptismDate'	=>	'School_student_BaptismDate',
	'BaptismChurch'	=>	'School_student_BaptismChurch',
	'BaptismLoc'	=>	'School_student_BaptismLoc',
	'ReconciliationDate'	=>	'School_student_ReconciliationDate',
	'ReconciliationChurch'	=>	'School_student_ReconciliationChurch',
	'ReconciliationLoc'	=>	'School_student_ReconciliationLoc',
	'CommunionDate'         =>	'School_student_CommunionDate',
	'CommunionChurch'	=>	'School_student_CommunionChurch',
	'CommunionLoc'          =>	'School_student_CommunionLoc',
	'ConfirmationDate'	=>	'School_student_ConfirmationDate',
	'ConfirmationChurch'	=>	'School_student_ConfirmationChurch',
	'ConfirmationLoc'	=>	'School_student_ConfirmationLoc',
	'CustodialParent'	=>	'School_student_CustodialParent',
	'CustodialDocumentation'	=>	'School_student_CustodialDocumentation',
	'CustodialDocumentationDate'	=>	'School_student_CustodialDocumentationDate',
	'GuardianName'          =>	'School_student_GuardianName',
	'GuardianRelation'	=>	'School_student_GuardianRelation',
	'GuardianDocumentation'	=>	'School_student_GuardianDocumentation',
	'GuardianDocumentationDate'	=>	'School_student_GuardianDocumentationDate',
	'PrevSchool1'           =>	'School_student_PrevSchool1',
	'PrevSchoolAddress1'	=>	'School_student_PrevSchoolAddress1',
	'PrevSchoolGrades1'	=>	'School_student_PrevSchoolGrades1',
	'PrevSchoolDates1'	=>	'School_student_PrevSchoolDates1',
	'PrevSchool2'           =>	'School_student_PrevSchool2',
	'PrevSchoolAddress2'	=>	'School_student_PrevSchoolAddress2',
	'PrevSchoolGrades2'	=>	'School_student_PrevSchoolGrades2',
	'PrevSchoolDates2'	=>	'School_student_PrevSchoolDates2',
	'PrevSchool3'           =>	'School_student_PrevSchool3',
	'PrevSchoolAddress3'	=>	'School_student_PrevSchoolAddress3',
	'PrevSchoolGrades3'	=>	'School_student_PrevSchoolGrades3',
	'PrevSchoolDates3'	=>	'School_student_PrevSchoolDates3',
	'PrevSchool4'           =>	'School_student_PrevSchool4',
	'PrevSchoolAddress4'	=>	'School_student_PrevSchoolAddress4',
	'PrevSchoolGrades4'	=>	'School_student_PrevSchoolGrades4',
	'PrevSchoolDates4'	=>	'School_student_PrevSchoolDates4',
	'DistrictEval'	=>	'School_student_DistrictEval',
	'PrivateEval'	=>	'School_student_PrivateEval',
	'EduDate'	=>	'School_student_EduDate',
	'EduAgencyName'	=>	'School_student_EduAgencyName',
	'EduAgencyContact'	=>	'School_student_EduAgencyContact',
	'EduAgencyPhone'	=>	'School_student_EduAgencyPhone',
	'PsychDate'	=>	'School_student_PsychDate',
	'PsychAgencyName'	=>	'School_student_PsychAgencyName',
	'PsychAgencyContact'	=>	'School_student_PsychAgencyContact',
	'PsychAgencyPhone'	=>	'School_student_PsychAgencyPhone',
	'SpeechDate'	=>	'School_student_SpeechDate',
	'SpeechAgencyName'	=>	'School_student_SpeechAgencyName',
	'SpeechAgencyContact'	=>	'School_student_SpeechAgencyContact',
	'SpeechAgencyPhone'	=>	'School_student_SpeechAgencyPhone',
	'OtherEvalName'	=>	'School_student_OtherEvalName',
	'OtherDate'	=>	'School_student_OtherDate',
	'OtherAgencyName'	=>	'School_student_OtherAgencyName',
	'OtherAgencyContact'	=>	'School_student_OtherAgencyContact',
	'OtherAgencyPhone'	=>	'School_student_OtherAgencyPhone',
	'IEP'	=>	'School_student_IEP',
	'IEPSubmitDate'	=>	'School_student_IEPSubmitDate',
	'504Plan'	=>	'School_student_504Plan',
	'504PlanSubmitDate'	=>	'School_student_504PlanSubmitDate',
	'DistrictName'	=>	'School_student_DistrictName',
	'LastIEPDate'	=>	'School_student_LastIEPDate',
	'LastPsychDate'	=>	'School_student_LastPsychDate',
	'Placement'	=>	'School_student_Placement',
        'Allergies'       => 'school_student_Allergies',
        'Conditions'       => 'school_student_Conditions',
        'LastSaveValid' =>      'School_student_LastSaveValid',
	'Accepted'		=> 'School_student_Accepted'
        );

    $table['School_student_column_def'] = array(
        'id'            => 'I NOTNULL PRIMARY AUTOINCREMENT',
        'Familyid'            => 'I NOTNULL INDEX idx_familyid',
	'AppDate'	=>	'C(255)',
        'Returning'     =>      'L',
	'Grade'	=>	'C(255)',
        'ClassYear'     => 'I',
	'Teacher'	=>	'C(255)',
	'LastName'	=>	'C(255)',
	'FirstName'	=>	'C(255)',
	'MiddleName'	=>	'C(255)',
	'NickName'	=>	'C(255)',
	'DOB'	=>	'C(255)',
	'POB'	=>	'C(255)',
	'BirthCertificate'           =>	'C(75)',
	'Gender'	=>	'C(255)',
	'Religion'	=>	'C(255)',
	'Parish'	=>	'C(255)',
	'Resides'	=>	'C(255)',
	'Relationship'	=>	'C(255)',
	'BaptismDate'	=>	'C(255)',
	'BaptismChurch'	=>	'C(255)',
	'BaptismLoc'	=>	'C(255)',
	'ReconciliationDate'	=>	'C(255)',
	'ReconciliationChurch'	=>	'C(255)',
	'ReconciliationLoc'	=>	'C(255)',
	'CommunionDate'	=>	'C(255)',
	'CommunionChurch'	=>	'C(255)',
	'CommunionLoc'	=>	'C(255)',
	'ConfirmationDate'	=>	'C(255)',
	'ConfirmationChurch'	=>	'C(255)',
	'ConfirmationLoc'	=>	'C(255)',
	'CustodialParent'	=>	'C(255)',
	'CustodialDocumentation'	=>	'C(255)',
	'CustodialDocumentationDate'	=>	'C(255)',
	'GuardianName'	=>	'C(255)',
	'GuardianRelation'	=>	'C(255)',
	'GuardianDocumentation'	=>	'C(255)',
	'GuardianDocumentationDate'	=>	'C(255)',
	'PrevSchool1'	=>	'C(255)',
	'PrevSchoolAddress1'	=>	'C(255)',
	'PrevSchoolGrades1'	=>	'C(255)',
	'PrevSchoolDates1'	=>	'C(255)',
	'PrevSchool2'	=>	'C(255)',
	'PrevSchoolAddress2'	=>	'C(255)',
	'PrevSchoolGrades2'	=>	'C(255)',
	'PrevSchoolDates2'	=>	'C(255)',
	'PrevSchool3'	=>	'C(255)',
	'PrevSchoolAddress3'	=>	'C(255)',
	'PrevSchoolGrades3'	=>	'C(255)',
	'PrevSchoolDates3'	=>	'C(255)',
	'PrevSchool4'	=>	'C(255)',
	'PrevSchoolAddress4'	=>	'C(255)',
	'PrevSchoolGrades4'	=>	'C(255)',
	'PrevSchoolDates4'	=>	'C(255)',
	'DistrictEval'	=>	'C(255)',
	'PrivateEval'	=>	'C(255)',
	'EduDate'	=>	'C(255)',
	'EduAgencyName'	=>	'C(255)',
	'EduAgencyContact'	=>	'C(255)',
	'EduAgencyPhone'	=>	'C(255)',
	'PsychDate'	=>	'C(255)',
	'PsychAgencyName'	=>	'C(255)',
	'PsychAgencyContact'	=>	'C(255)',
	'PsychAgencyPhone'	=>	'C(255)',
	'SpeechDate'	=>	'C(255)',
	'SpeechAgencyName'	=>	'C(255)',
	'SpeechAgencyContact'	=>	'C(255)',
	'SpeechAgencyPhone'	=>	'C(255)',
	'OtherEvalName'	=>	'C(255)',
	'OtherDate'	=>	'C(255)',
	'OtherAgencyName'	=>	'C(255)',
	'OtherAgencyContact'	=>	'C(255)',
	'OtherAgencyPhone'	=>	'C(255)',
	'IEP'	=>	'C(255)',
	'IEPSubmitDate'	=>	'C(255)',
	'504Plan'	=>	'C(255)',
	'504PlanSubmitDate'	=>	'C(255)',
	'DistrictName'	=>	'C(255)',
	'LastIEPDate'	=>	'C(255)',
	'LastPsychDate'	=>	'C(255)',
	'Placement'	=>	'C(255)',
        'Allergies'          => 'C(255)',
        'Conditions'          => 'C(255)',
        'LastSaveValid' =>      'L',
	'Accepted'		=> 'L'
    );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_student_column'],
            'School_student_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_student_column_def']
            );

    /*** Tuition table  ***/
    $table['School_tuition'] = DBUtil::getLimitedTablename('School_tuition');

    $table['School_tuition_column'] = array(
        'id'            =>      'School_tuition_id',
        'Parishioner'   =>      'School_tuition_Parishioner',
        'EnvelopeNumber'   =>      'School_tuition_EnvelopeNumber',
        'Contribution'  =>      'School_tuition_Contribution',
        'PaymentPref'   =>      'School_tuition_PaymentPref',
        'BankName'      =>      'School_tuition_BankName',
        'AcctNum'       =>      'School_tuition_AcctNum',
        'RoutingNum'       =>      'School_tuition_RoutingNum',
        'AcctHolderName'    =>  'School_tuition_AcctHolderName',
        'AcctHolderName2'    =>  'School_tuition_AcctHolderName2',
    );
    $table['School_tuition_column_def'] = array(
        'id'            => 'I NOTNULL PRIMARY AUTOINCREMENT',
        'Parishioner'   =>      'L',
        'EnvelopeNumber'   =>   'I',
        'Contribution'  =>      'N',
        'PaymentPref'   =>      'C(6)',
        'BankName'      =>      'C(100)',
        'AcctNum'       =>      'C(50)',
        'RoutingNum'       =>      'C(50)',
        'AcctHolderName'    =>  'C(100)',
        'AcctHolderName2'    =>  'C(100)',
    );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_tuition_column'],
            'School_tuition_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_tuition_column_def']
            );

    $table['School_register'] = DBUtil::getLimitedTablename('School_register');

    $table['School_register_column'] = array(
        'id'            =>      'School_register_id',
        'TransferReason' =>     'School_register_TransferReason',
        'SibName1'      =>      'School_register_SibName1',
        'SibAge1'       =>      'School_register_Sib1Age1',
        'SibSchool1'    =>      'School_register_SibSchool1',
        'SibGrade1'     =>      'School_register_SibGrade1',
        'SibName2'      =>      'School_register_SibName2',
        'SibAge2'       =>      'School_register_Sib1Age2',
        'SibSchool2'    =>      'School_register_SibSchool2',
        'SibGrade2'     =>      'School_register_SibGrade2',
        'SibName3'      =>      'School_register_SibName3',
        'SibAge3'       =>      'School_register_Sib1Age3',
        'SibSchool3'    =>      'School_register_SibSchool3',
        'SibGrade3'     =>      'School_register_SibGrade3',
        'SibName4'      =>      'School_register_SibName4',
        'SibAge4'       =>      'School_register_Sib1Age4',
        'SibSchool4'    =>      'School_register_SibSchool4',
        'SibGrade4'     =>      'School_register_SibGrade4',
        'Parishioner'   =>      'School_register_Parishioner',
        'EnvelopeNumber'   =>      'School_register_EnvelopeNumber',
        'ChooseMont'    =>  'School_register_ChooseMont',
        'ChooseCatholic'    =>  'School_register_ChooseCatholic',
        'ChooseOtherKids'    =>  'School_register_ChooseOtherKids',
        'ChoseRecommend'    =>  'School_register_ChoseRecommend',
        'ChoseOther'    =>  'School_register_ChoseOther',
        'OtherPertinent'    =>  'School_register_OtherPertinent',
        'OtherInfo'    =>  'School_register_OtherInfo',
        );
        
    $table['School_register_column_def'] = array(
        'id'            =>      'I NOTNULL PRIMARY AUTOINCREMENT',
        'TransferReason' =>     'X(4000)',
        'SibName1'      =>      'C(255)',
        'SibAge1'       =>      'C(255)',
        'SibSchool1'    =>      'C(255)',
        'SibGrade1'     =>      'C(255)',
        'SibName2'      =>      'C(255)',
        'SibAge2'       =>      'C(255)',
        'SibSchool2'    =>      'C(255)',
        'SibGrade2'     =>      'C(255)',
        'SibName3'      =>      'C(255)',
        'SibAge3'       =>      'C(255)',
        'SibSchool3'    =>      'C(255)',
        'SibGrade3'     =>      'C(255)',
        'SibName4'      =>      'C(255)',
        'SibAge4'       =>      'C(255)',
        'SibSchool4'    =>      'C(255)',
        'SibGrade4'     =>      'C(255)',
        'Parishioner'   =>      'L',
        'EnvelopeNumber'   =>   'I',
        'ChooseMont'    =>  'L',
        'ChooseCatholic'    =>  'L',
        'ChooseOtherKids'    => 'L',
        'ChoseRecommend'    =>  'C(255)',
        'ChoseOther'    =>  'C(255)',
        'OtherPertinent'    =>  'X(4000)',
        'OtherInfo'    =>  'X(4000)',
        );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_register_column'],
            'School_tuition_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_register_column_def']
            );
        
        

    $table['School_districts'] = DBUtil::getLimitedTablename('School_districts');

    $table['School_districts_column'] = array(
	'id'	    => 'School_districts_id',
	'Name'	    => 'School_districts_Name',
	'Code'	    => 'School_districts_Code',
    );
    $table['School_districts_column_def'] = array(
	'id'	    => 'I NOTNULL PRIMARY AUTOINCREMENT',
	'Name'	    => 'C(255)',
	'Code'	    => 'C(6)',
    );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_districts_column'],
            'School_districts_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_districts_column_def']
            );
    
    $table['School_teachers'] = DBUtil::getLimitedTablename('School_teachers');

    $table['School_teachers_column'] = array(
	'id'	    => 'School_teachers_id',
	'Name'	    => 'School_teachers_Name',
	'Grade'	    => 'School_teachers_Grade',
    );
    $table['School_teachers_column_def'] = array(
	'id'	    => 'I NOTNULL PRIMARY AUTOINCREMENT',
	'Name'	    => 'C(255)',
	'Grade'	    => 'C(3)',
    );
    ObjectUtil::addStandardFieldsToTableDefinition(
            $table['School_teachers_column'],
            'School_teachers_'
            );
    ObjectUtil::addStandardFieldsToTableDataDefinition(
            $table['School_teachers_column_def']
            );

    
    return $table;

}

?>
