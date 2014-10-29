<?php

echo "<?php defined('BASEPATH') || exit('No direct script access allowed');" .
PHP_EOL . "
\$config['module_config'] = array(
	'description'	=> '{$module_description}',
	'name'		    => '{$module_name}',
     /*
      * Replace the 'name' entry above with this entry and create the entry in
      * the application_lang file for localization/translation support in the
      * menu
     'name'          => 'lang:bf_menu_{$module_name_lower}',
      */
	'version'		=> '0.0.1',
	'author'		=> '{$username}',
);";