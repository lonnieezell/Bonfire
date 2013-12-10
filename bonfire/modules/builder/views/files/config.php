<?php

$build_config = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';


$build_config .= PHP_EOL . '
// $module_name_translate = lang("'.$module_name_lower.'_module_name");
// $module_description_translate = lang("'.$module_name_lower.'_module_description");

$config[\'module_config\'] = array(
	\'description\'	=> \''.$module_description.'\',
	\'name\'		=> \''.$module_name.'\',
	\'version\'		=> \'0.0.1\',
	\'author\'		=> \'' . $username . '\'
);';

echo $build_config;