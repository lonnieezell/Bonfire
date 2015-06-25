<?php defined('BASEPATH') || exit('No direct script access allowed');

$config['module_config'] = array(
    'author'      => 'Bonfire Team',
    'description' => 'Queues emails to be sent in bursts throughout the day.',
    'name'        => 'lang:bf_menu_emailer',
    'version'     => '0.7.3',
    'menus'       => array(
        'settings' => 'emailer/settings/menu',
    ),
);
