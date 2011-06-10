<?php
$model =<<<EOF
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class {$model_name_cap}_model extends BF_Model {

	protected \$table		= "{$model_name}";
	protected \$key			= "{$model_name}_id";
	protected \$soft_deletes	= false;
	protected \$date_format	= "datetime";
	protected \$set_created	= true;
	protected \$set_modified = false;

}
EOF;

echo $model;
?>
