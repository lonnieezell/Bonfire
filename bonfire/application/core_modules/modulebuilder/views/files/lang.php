<?php

$lang = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';

$lang .= '
$lang[\''.$module_name_lower.'_manage\']			= \'Manage '.$module_name.'\';
$lang[\''.$module_name_lower.'_edit\']				= \'Edit\';
$lang[\''.$module_name_lower.'_true\']				= \'True\';
$lang[\''.$module_name_lower.'_false\']				= \'False\';
$lang[\''.$module_name_lower.'_create\']			= \'Create\';
$lang[\''.$module_name_lower.'_edit_text\']			= \'Edit this to suit your needs\';
$lang[\''.$module_name_lower.'_no_records\']			= \'There aren\\\'t any '.$module_name_lower.' in the system.\';
$lang[\''.$module_name_lower.'_create_new\']			= \'Create a new '.$module_name.'.\';
$lang[\''.$module_name_lower.'_create_success\']			= \''.$module_name.' successfully created.\';
$lang[\''.$module_name_lower.'_create_failure\']			= \'There was a problem creating the '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_create_new_button\']			= \'Create New '.$module_name.'\';
$lang[\''.$module_name_lower.'_invalid_id\']			= \'Invalid '.$module_name.' ID.\';
$lang[\''.$module_name_lower.'_edit_success\']			= \''.$module_name.' successfully saved.\';
$lang[\''.$module_name_lower.'_edit_failure\']			= \'There was a problem saving the '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_delete_success\']			= \'The '.$module_name.' was successfully deleted.\';
$lang[\''.$module_name_lower.'_delete_failure\']			= \'We could not delete the '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_actions\']			= \'Actions\';
$lang[\''.$module_name_lower.'_cancel\']			= \'Cancel\';
$lang[\''.$module_name_lower.'_delete_record\']			= \'Delete this '.$module_name.'\';
$lang[\''.$module_name_lower.'_delete_confirm\']			= \'Are you sure you want to delete this '.$module_name_lower.'?\';
$lang[\''.$module_name_lower.'_edit_heading\']			= \'Edit '.$module_name.'\';

// Activities
$lang[\''.$module_name_lower.'_act_create_record\']			= \'Created record with ID\';
$lang[\''.$module_name_lower.'_act_edit_record\']			= \'Updated record with ID\';
$lang[\''.$module_name_lower.'_act_delete_record\']			= \'Deleted record with ID\';
';

echo $lang;
?>
