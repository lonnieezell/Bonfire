<?php

/*
 * Output path of the modules
 */
$config[ 'modulebuilder' ]['output_path'] = APPPATH."../modules/";
/*
 * form actions which will appear in the list
 */
$config[ 'modulebuilder' ][ 'form_action_options' ] = array('index' => 'List', 
														'create' => 'Create',
														'edit' => 'Edit', 
														'delete' => 'Delete');


/*
 * valiation rules which will appear
 */
$config[ 'modulebuilder' ][ 'validation_rules' ] = array('required', 'unique', 'trim', 'xss_clean');
$config[ 'modulebuilder' ][ 'validation_limits' ] = array('alpha', 'is_numeric', 'alpha_numeric', 'alpha_dash', 'valid_email', 'integer', 'is_decimal', 'is_natural', 'is_natural_no_zero','valid_ip','valid_base64','alpha_extra');
														
/*
 * default primary key field
 */
$config[ 'modulebuilder' ][ 'primary_key_field' ] = 'id';

/*
 * default form input delimiters
 */
$config[ 'modulebuilder' ][ 'form_input_delimiters' ] = array('<p>','</p>');

/*
 * default form error delimiters
 */
$config[ 'modulebuilder' ][ 'form_error_delimiters' ] = array('<span class="error">', '</span>');
