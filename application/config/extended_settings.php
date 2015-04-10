<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Extended Settings Fields Config.
 *
 * The following examples show how to use regular inputs, select boxes, and
 * state/country select boxes.
 *
 * Note: the name field is limited to 26 characters.
 *
 * @package Bonfire\Config\extended_settings
 * @author  Bonfire Dev Team
 * @link http://cibonfire.com/docs/developer/settings/extended_settings
 */
$config['extended_settings_fields'] = array(
    array(
        'name'        => 'extended_settings_test',
        'label'       => 'Test Label',
        'rules'       => 'trim',
        'form_detail' => array(
            'type'     => 'dropdown',
            'settings' => array(
                'name'  => 'extended_settings_test',
                'id'    => 'extended_settings_test',
            ),
            'options'  => array(
                '0' => 'Passed',
                '1' => 'Failed',
            ),
        ),
        'permission'  => 'This.Shouldnt.ShowUp',
    ),
    array(
        'name'        => 'street_name',
        'label'       => lang('user_meta_street_name'),
        'rules'       => 'trim|max_length[100]',
        'form_detail' => array(
            'type'     => 'input',
            'settings' => array(
                'name'      => 'street_name',
                'id'        => 'street_name',
                'maxlength' => '100',
                'class'     => 'span6',
            ),
        ),
        'permission'  => 'Site.Settings.View',
    ),
    array(
        'name'        => 'country',
        'label'       => lang('user_meta_country'),
        'rules'       => 'required|trim|max_length[100]',
        'form_detail' => array(
            'type'     => 'country_select',
            'settings' => array(
                'name'      => 'country',
                'id'        => 'country',
                'maxlength' => '100',
                'class'     => 'span6'
            ),
        ),
    ),
    array(
        'name'        => 'state',
        'label'       => lang('user_meta_state'),
        'rules'       => 'trim|max_length[2]',
        'form_detail' => array(
            'type'     => 'state_select',
            'settings' => array(
                'name'      => 'state',
                'id'        => 'state',
                'maxlength' => '2',
                'class'     => 'span1'
            ),
        ),
        'permission'  => 'Site.Content.View',
    ),
    array(
        'name'        => 'type',
        'label'       => lang('user_meta_type'),
        'rules'       => 'required',
        'form_detail' => array(
            'type'     => 'dropdown',
            'settings' => array(
                'name'   => 'type',
                'id'     => 'type',
                'class'  => 'span6',
            ),
            'options'  =>  array(
                'small'  => 'Small Shirt',
                'med'    => 'Medium Shirt',
                'large'  => 'Large Shirt',
                'xlarge' => 'Extra Large Shirt',
              ),
        ),
    ),
);
