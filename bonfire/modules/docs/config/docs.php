<?php defined('BASEPATH') || exit('No direct script access allowed');

$config['docs.theme'] = 'docs';

/*
 * Sets the default group (developer, application, or modules) that the docs
 * will redirect to if no area is provided.
 */
$config['docs.default_group'] = 'developer';

/*
 * If true, the 'developer' docs will be displayed in environments other than
 * the development environment.
 */
$config['docs.show_dev_docs'] = true;

/*
 * If true, the 'application' specific documentation will be shown.
 */
$config['docs.show_app_docs'] = true;

/*
 * The name of the file containing the table of contents.
 */
$config['docs.toc_file'] = '_toc.ini';

/*
 * Environments in which displaying the docs is permitted. If the environment
 * is not included in the array, an error message will be displayed and the user
 * will be redirected to the site's base URL.
 */
$config['docs.permitted_environments'] = array('development', 'testing', 'production');