<?php defined('BASEPATH') || exit('No direct script access allowed');

$config['module_config'] = array(
	'name'			=> 'lang:bf_menu_emailer',
	'description'	=> 'Queues emails to be sent in bursts throughout the day.',
	'menus'	=> array(
		'settings'	=> 'emailer/settings/menu',
	),
	'author'		=> 'Bonfire Team',
);