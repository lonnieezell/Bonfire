<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011-2012 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
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
		'rules'   => 'trim|max_length[100]|xss_clean',
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
		'rules'   => 'required|trim|max_length[2]|xss_clean',
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
		'rules'   => 'required|trim|max_length[100]|xss_clean',
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
		'rules'   => 'required|xss_clean',
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