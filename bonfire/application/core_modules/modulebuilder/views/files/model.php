<?php
$model = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($controller_name).'_model extends BF_Model {

	protected $table		= "'.$controller_name.'";
	protected $key			= "'.$primary_key_field.'";
	protected $soft_deletes	= false;
	protected $date_format	= "datetime";
	protected $set_created	= false;
	protected $set_modified = false;

}
';

echo $model;
?>
