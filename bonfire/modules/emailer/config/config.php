<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['module_config'] = array(
	'name'			=> 'lang:email_queue',
	'description'	=> 'Queues emails to be sent in bursts throughout the day.',
	'menus'	=> array(
		'settings'	=> 'emailer/settings/menu'
	),
	'author'		=> 'Bonfire Team'
);