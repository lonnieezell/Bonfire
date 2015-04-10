<?php defined('BASEPATH') || exit('No direct script access allowed');

/** @var string The theme used to display the documentation. */
$config['docs.theme'] = 'docs';

/**
 * @var string The default group displayed when no area is provided.
 * Valid values are developer, application, or modules.
 */
$config['docs.default_group'] = 'developer';

/** @var boolean Display developer docs in environments other than development. */
$config['docs.show_dev_docs'] = true;

/** @var boolean Display application documents. */
$config['docs.show_app_docs'] = true;

/** @var string The name of the file containing the table of contents. */
$config['docs.toc_file'] = '_toc.ini';

/**
 * @var array Environments in which displaying the docs is permitted.
 *
 * If the current environment is not included in the array, an error message will
 * be displayed and the user will be redirected to the site's base URL.
 */
$config['docs.permitted_environments'] = array('development', 'testing', 'production');

/**
 * @var string Override the commonmark.driver configured in application/config/application.php
 */
// $config['docs.commonmark_driver'] = 'MarkdownExtended';
