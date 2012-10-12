<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Enable/Disable Migrations
|--------------------------------------------------------------------------
|
| Migrations are disabled by default for security reasons.
| You should enable migrations whenever you intend to do a schema migration
| and disable it back when you're done.
|
| Some more severe security measures might take place in future releases.
|
*/
$config['migrations_enabled'] = true;


/*
|--------------------------------------------------------------------------
| Migrations version
|--------------------------------------------------------------------------
|
| This is used to set the default migration for this code base. 
| Sometimes you want the system to automaticly migrate the database
| to the most current migration. Or there might be higher migrations
| that are not part of the migration-> env. Setting the migration does 
| does nothing here. It is a way for a programer to check the config.
|
| On login you might want to do something like this 
| $this->migration->version($this->config->item('migrations_version'));
|
*/
$config['migrations_version'] = 1;


/*
|--------------------------------------------------------------------------
| Migrations Path
|--------------------------------------------------------------------------
|
| Path to your migrations folder.
| Typically, it will be within your application path.
| Also, writing permission is required within the migrations path.
|
*/
$config['migrations_path'] = APPPATH . '../bonfire/application/db/migrations/';
