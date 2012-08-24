<?php
$model = '<?php if (!defined(\'BASEPATH\')) exit(\'No direct script access allowed\');

class '.ucfirst($controller_name).'_model extends BF_Model {

	protected $table		= "'.$table_name.'";
	protected $key			= "'.$primary_key_field.'";
	protected $soft_deletes	= '.$this->input->post('use_soft_deletes').';
	protected $date_format	= "datetime";
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
}
';

echo $model;
?>
