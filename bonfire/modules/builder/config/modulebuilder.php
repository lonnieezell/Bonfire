<?php

/**
 * The path to the folder in which modules will be built
 */
$config['modulebuilder']['output_path'] = APPPATH . '/modules/';

/**
 * Form actions to appear in the module builder's "Controller Actions" list
 */
$config['modulebuilder']['form_action_options'] = array(
    'index'  => 'List',
    'create' => 'Create',
    'edit'   => 'Edit',
    'delete' => 'Delete',
);

/**
 * Rules to display under "Validation Rules" in each field's details/options
 */
$config['modulebuilder']['validation_rules'] = array(
    'required',
    'unique',
    'trim',
);

/**
 * Items to display under "Input Limitations" in each field's details/options
 */
$config['modulebuilder']['validation_limits'] = array(
    'alpha',                'is_numeric',           'alpha_numeric',
    'alpha_dash',           'valid_email',          'integer',
    'is_decimal',           'is_natural',           'is_natural_no_zero',
    'valid_ip',             'valid_base64',         'alpha_extra',
);

/**
 * Default primary key field
 */
$config['modulebuilder']['primary_key_field'] = 'id';

/**
 * Default form error delimiters
 */
$config['modulebuilder']['form_error_delimiters'] = array(
    '<span class="error">', '</span>'
);

$config['modulebuilder']['languages_available'] = array(
    'english',
    'portuguese_br',
    'spanish_am',
	'italian',
);