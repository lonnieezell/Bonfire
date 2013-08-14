<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

//------------------------------------------------------------------------
// User Meta Fields Config - These are just examples of various options
// The following examples show how to use regular inputs, select boxes,
// state and country select boxes.
//------------------------------------------------------------------------

$config['user_meta_fields'] =  array(
	array(
		'name'   => 'street_name',
		'label'   => lang('user_meta_street_name'),
		'rules'   => 'trim|max_length[100]',
		'frontend' => TRUE,
		'form_detail' => array(
			'type' => 'input',
			'settings' => array(
				'name'		=> 'street_name',
				'id'		=> 'street_name',
				'maxlength'	=> '100',
				'class'		=> 'span6',
//				'required'	=> TRUE,
			),
		),
	),


	array(
		'name'   => 'state',
		'label'   => lang('user_meta_state'),
		'rules'   => 'trim|max_length[2]',
		'form_detail' => array(
			'type' => 'state_select',
			'settings' => array(
				'name'		=> 'state',
				'id'		=> 'state',
				'maxlength'	=> '2',
				'class'		=> 'span1'
			),
		),
	),

	array(
		'name'   => 'country',
		'label'   => lang('user_meta_country'),
		'rules'   => 'required|trim|max_length[100]',
		'admin_only' => FALSE,
		'form_detail' => array(
			'type' => 'country_select',
			'settings' => array(
				'name'		=> 'country',
				'id'		=> 'country',
				'maxlength'	=> '100',
				'class'		=> 'span6'
			),
		),
	),

	array(
		'name'   => 'type',
		'label'   => lang('user_meta_type'),
		'rules'   => 'required',
		'frontend' => FALSE,
		'admin_only' => TRUE,
		'form_detail' => array(
			'type' => 'dropdown',
			'settings' => array(
				'name'		=> 'type',
				'id'		=> 'type',
				'class'		=> 'span6',
			),
			'options' =>  array(
                  'small'  => 'Small Shirt',
                  'med'    => 'Medium Shirt',
                  'large'   => 'Large Shirt',
                  'xlarge' => 'Extra Large Shirt',
                ),
		),
	),
);