<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['assets.url'] = '/';
$config['assets.base_url'] = '';

$config['assets.asset_folders'] = array(
	'css'	=> 'css',
	'js'	=> 'js',
	'image'	=> 'images'
);


$config['assets.js_opener'] = '$(document).ready(function(){'. "\n";
$config['assets.js_closer'] = '});'. "\n";