<?php
$model = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($controller_name).'_model extends BF_Model {

	protected $table_name	= "'.$table_name.'";
	protected $key			= "'.$primary_key_field.'";
	protected $soft_deletes	= '.$this->input->post('use_soft_deletes').';
	protected $date_format	= "datetime";

	protected $log_user 	= FALSE;

	protected $set_created	= '.$this->input->post('use_created').';
	protected $set_modified = '.$this->input->post('use_modified').';';

	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_created') == 'true')
	{
		$model .= '
	protected $created_field = "'.$this->input->post('created_field').'";';
	}

	// use the created field? Add field and custom name if chosen.
	if ($this->input->post('use_modified') == 'true')
	{
		$model .= '
	protected $modified_field = "'.$this->input->post('modified_field').'";';
	}


	$model .=
'

	/*
		Customize the operations of the model without recreating the insert, update,
		etc methods by adding the method names to act as callbacks here.
	 */
	protected $before_insert 	= array();
	protected $after_insert 	= array();
	protected $before_update 	= array();
	protected $after_update 	= array();
	protected $before_find 		= array();
	protected $after_find 		= array();
	protected $before_delete 	= array();
	protected $after_delete 	= array();

	/*
		For performance reasons, you may require your model to NOT return the
		id of the last inserted row as it is a bit of a slow method. This is
		primarily helpful when running big loops over data.
	 */
	protected $return_insert_id 	= TRUE;

	// The default type of element data is returned as.
	protected $return_type 			= "object";

	// Items that are always removed from data arrays prior to
	// any inserts or updates.
	protected $protected_attributes = array();

	protected $validation_rules 		= array();
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;
}
';

echo $model;
?>