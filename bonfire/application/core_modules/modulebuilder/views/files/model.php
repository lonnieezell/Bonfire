<?php
$model =<<<EOF
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class {$controller_name}_model extends BF_Model {

	protected \$table		= "{$controller_name}";
	protected \$key			= "{$primary_key_field}_id";
	protected \$soft_deletes	= false;
	protected \$date_format	= "datetime";
	protected \$set_created	= true;
	protected \$set_modified = false;

}
EOF;

echo $model;
?>
