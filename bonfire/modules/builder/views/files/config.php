<?php

$build_config = '<?php defined(\'BASEPATH\') || exit(\'No direct script access allowed\');';


$build_config .= PHP_EOL . '
// $module_name_translate = lang("' . $module_name_lower . '_module_name");
// $module_description_translate = lang("' . $module_name_lower . '_module_description");

$config[\'module_config\'] = array(
	\'description\'	=> \'' . $module_description . '\',
	\'name\'		=> \'' . $module_name . '\',
	\'version\'		=> \'0.0.1\',
	\'author\'		=> \'' . $username . '\',';
if ($textarea_editor == 'elrte') {
    $build_config .= '
	\'uploaded_dir_path\'	=> \'/my_uploads_folder\'	//relative to the document root, without trailing slash (for ElFinder file manager)';
}
$build_config .= "\n".');';

echo $build_config;
