<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['docs.theme'] = 'docs';

/*
 * Sets the default group (developer, application, or modules) that the
 * docs will redirect to if no area is provided.
 */
$config['docs.default_group'] = 'developer';

/*
 * Should we display the developer docs in environments other than
 * the develop environment?
 */
$config['docs.show_dev_docs']   = true;

/*
 * If TRUE, the 'application' specific documentation will be shown.
 */
$config['docs.show_app_docs']   = true;