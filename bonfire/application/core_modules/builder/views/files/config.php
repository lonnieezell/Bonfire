<?php

$build_config = '<?php if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');';

$build_config .= '$config[\'module_config\'] = array(
	\'description\'	=> \''.addslashes($module_description).'\',
	\'name\'		=> \''.$module_name.'\',
	\'version\'		=> \'0.0.1\',
	\'author\'		=> \''.$username.'\'
);
';

echo $build_config;
?>
