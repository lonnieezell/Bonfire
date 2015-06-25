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
    'none_of_the_above',
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

// Values below are MySQL database types
$config['modulebuilder']['database_types'] = array(
    'BIGINT'        => array('numeric', 'integer'),
    'BINARY'        => array('binary'),
    'BIT'           => array('numeric', 'integer', 'bit'),
    'BLOB'          => array('binary', 'object'),
    'BOOL'          => array('numeric', 'integer', 'boolean'),
    'BOOLEAN'       => array('numeric', 'integer', 'boolean'),
    'CHAR'          => array('string'),
    'DATE'          => array('date'),
    'DATETIME'      => array('date', 'time'),
    'DEC'           => array('numeric', 'real'),
    'DECIMAL'       => array('numeric', 'real'),
    'DOUBLE'        => array('numeric', 'real'),
    'ENUM'          => array('string', 'list'),
    'FLOAT'         => array('numeric', 'real'),
    'INT'           => array('numeric', 'integer'),
    'INTEGER'       => array('numeric', 'integer'),
    'LONGBLOB'      => array('binary', 'object'),
    'LONGTEXT'      => array('string', 'object'),
    'MEDIUMBLOB'    => array('binary', 'object'),
    'MEDIUMINT'     => array('numeric', 'integer'),
    'MEDIUMTEXT'    => array('string', 'object'),
    'NUMERIC'       => array('numeric', 'real'),
    'REAL'          => array('numeric', 'real'),
    'SET'           => array('string', 'list'),
    'SMALLINT'      => array('numeric', 'integer'),
    'TIME'          => array('time'),
    'TIMESTAMP'     => array('date', 'time'),
    'TINYBLOB'      => array('binary', 'object'),
    'TINYINT'       => array('numeric', 'integer'),
    'TINYTEXT'      => array('string', 'object'),
    'TEXT'          => array('string', 'object'),
    'VARBINARY'     => array('binary'),
    'VARCHAR'       => array('string'),
    'YEAR'          => array('year', 'integer'),
);