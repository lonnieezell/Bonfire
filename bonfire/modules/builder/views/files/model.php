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

	/*
		You may need to move certain rules (like required) into the
		$insert_validation_rules array and out of the standard validation array.
		That way it is only required during inserts, not updates which may only
		be updating a portion of the data.
	 */
	protected $validation_rules 		= {validation_rules}
	protected $insert_validation_rules 	= array();
	protected $skip_validation 			= FALSE;

	//--------------------------------------------------------------------

}
';

//--------------------------------------------------------------------
// Validation Rules
//--------------------------------------------------------------------

$rules = 'array(';

$last_field = 0;
for ($counter = 1; $field_total >= $counter; $counter++)
{
	// only build on fields that have data entered.

	// Due to the required if rule if the first field is set the the others must be
	if (set_value("view_field_label$counter") == NULL)
	{
		continue; 	// move onto next iteration of the loop
	}

	// we set this variable as it will be used to place the comma after the last item to build the insert db array
	$last_field = $counter;

	if($db_required == 'new' && $table_as_field_prefix === TRUE)
	{
			$field_name = $module_name_lower . '_' . set_value("view_field_name$counter");
	}
	elseif($db_required == 'new' && $table_as_field_prefix === FALSE)
	{
			$field_name = set_value("view_field_name$counter");
	}
	else
	{
			$field_name = set_value("view_field_name$counter");
	}

	$form_name = $module_name_lower . '_' . set_value("view_field_name$counter");
	$rules .= '
		array(
			"field"		=> "'. $form_name .'",
			"label"		=> "'. set_value("view_field_label$counter") .'",
			"rules"		=> "';

	// set a friendly variable name
	$validation_rules = $this->input->post('validation_rules'.$counter);

	// rules have been selected for this fieldset
	$rule_counter = 0;

	if (is_array($validation_rules))
	{
		// add rules such as trim|required
		foreach ($validation_rules as $key => $value)
		{
			if ($rule_counter > 0)
			{
				$rules .= '|';
			}

			if ($value == 'unique')
			{
				$prefix = $this->db->dbprefix;
				$rules .= $value.'['.$prefix.$table_name.'.'.$field_name.','.$prefix.$table_name.'.'.$primary_key_field.']';
			}
			else
			{
				$rules .= $value;
			}
			$rule_counter++;
		}
	}

	$db_field_type = set_value("db_field_type".$counter);

	if ($db_field_type != 'ENUM' && $db_field_type != 'SET' && set_value("db_field_length_value$counter") != NULL)
	{
		if ($rule_counter > 0)
		{
			$rules .= '|';
		}

		if ($db_field_type == 'DECIMAL' || $db_field_type == 'FLOAT' || $db_field_type == 'DOUBLE')
		{
			list($len, $decimal) = explode(",", set_value("db_field_length_value$counter"));
			$max = $len;

			if (isset($decimal) && $decimal != 0)
			{
				$max = $len + 1;		// Add 1 to allow for the
			}
			$rules .= 'max_length['.$max.']';
		}
		else
		{
			$rules .= 'max_length['.set_value("db_field_length_value$counter").']';
		}
	}

	$rules .= '"
		),';
}

$rules .= '
	);';

$model = str_replace('{validation_rules}', $rules, $model);

unset($rules);

echo $model;
?>