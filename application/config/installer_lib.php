<?php defined('BASEPATH') || exit('No direct script access allowed');

// string[] Folders the installer checks for write access.
$config['writable_folders'] = array(
    'application/cache',
    'application/logs',
    'application/config',
    'application/archives',
    'application/db/backups',
    'public/assets/cache',
);

// string[] Files the installer checks for write access.
$config['writable_files'] = array(
    'application/config/application.php',
    'application/config/database.php',
);
