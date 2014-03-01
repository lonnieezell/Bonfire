<?php

$ucControllerName = ucfirst($controller_name);

$fields = "protected \$table_name	= '{$table_name}';
	protected \$key			= '{$primary_key_field}';
	protected \$date_format	= 'datetime';

	protected \$log_user 	= {$logUser};
	protected \$set_created	= {$useCreated};
	protected \$set_modified = {$useModified};
	protected \$soft_deletes	= {$useSoftDeletes};
";

// Use the created field? Add field and custom name if chosen.
if ($useCreated == 'true') {
    $fields .= "
	protected \$created_field     = '{$created_field}';";
    if ($logUser == 'true') {
	$fields .= "
    protected \$created_by_field  = '{$created_by_field}';";
    }
}

// Use the modified field? Add field and custom name if chosen.
if ($useModified == 'true') {
    $fields .= "
	protected \$modified_field    = '{$modified_field}';";
    if ($logUser == 'true') {
	$fields .= "
    protected \$modified_by_field = '{$modified_by_field}';";
    }
}

if ($useSoftDeletes == 'true') {
    $fields .= "
    protected \$deleted_field     = '{$delete_field}';";
    if ($logUser == 'true') {
	$fields .= "
    protected \$deleted_by_field  = '{$deleted_by_field}';";
    }
}

//--------------------------------------------------------------------
// Validation Rules  and Search feature
//--------------------------------------------------------------------

$rules = '';

$last_field = 0;


$searchtypes = array('VARCHAR', 'TEXT', 'LONGTEXT', 'MEDIUMTEXT', 'TINYTEXT'); //Search enabled types

$searchcols = array();  //list of table columns interested to search

for ($counter = 1; $field_total >= $counter; $counter++) {
    // Only build on fields that have data entered.
    if (set_value("view_field_label$counter") == null) {
	continue;
    }

    //Search feature columns selection (@author: Lorenzo Sanzari - www.icomlab.it)//////////////////////////
    $ftype = set_value("db_field_type" . $counter); //field db type
    $fname = set_value("view_field_name$counter");  //field name
    if ((in_array($ftype, $searchtypes)) AND (trim($fname) != '')) {
	$searchcols[] = $fname; //field name
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Set this variable as it will be used to place the comma after the last item to build the insert db array
    $last_field = $counter;

    if ($db_required == 'new' && $table_as_field_prefix === true) {
	$field_name = "{$module_name_lower}_" . set_value("view_field_name$counter");
    } elseif ($db_required == 'new' && $table_as_field_prefix === false) {
	$field_name = set_value("view_field_name$counter");
    } else {
	$field_name = set_value("view_field_name$counter");
    }

    $form_name = "{$module_name_lower}_" . set_value("view_field_name$counter");
    $rules .= "
		array(
			'field' => '{$form_name}',
			'label' => 'lang:" . set_value("view_field_name$counter") . "',
			'rules' => '";

    // set a friendly variable name
    $validation_rules = $this->input->post('validation_rules' . $counter);

    // rules have been selected for this fieldset
    $rule_counter = 0;
    $tempRules = array();

    if (is_array($validation_rules)) {
	// Add rules such as trim|required
	foreach ($validation_rules as $key => $value) {
	    if ($value == 'unique') {
		$prefix = $this->db->dbprefix;
		$value .= "[{$prefix}{$table_name}.{$field_name},{$prefix}{$table_name}.{$primary_key_field}]";
	    }
	    $tempRules[] = $value;
	}
    }

    $db_field_type = set_value("db_field_type{$counter}");

    if ($db_field_type != 'ENUM' && $db_field_type != 'SET' && set_value("db_field_length_value$counter") != null
    ) {
	if ($db_field_type == 'DECIMAL' || $db_field_type == 'FLOAT' || $db_field_type == 'DOUBLE'
	) {
	    list($len, $decimal) = explode(",", set_value("db_field_length_value$counter"));
	    $max = $len;

	    if (isset($decimal) && $decimal != 0) {
		$max = $len + 1; // Add 1 to allow for the decimal point
	    }
	} else {
	    $max = set_value("db_field_length_value$counter");
	}

	$tempRules[] = "max_length[{$max}]";
    }

    $rules .= implode('|', $tempRules);

    // End the validation rules definition and close the array for this field
    $rules .= "',
		),";
}

if (!empty($rules)) {
    // Minor formatting to close the array on the next line
    $rules .= '
	';
}

//Search statement building
if (count($searchcols) > 0) {

    $like_list = '';
    $match_list = '';
    $i = 0;
    foreach ($searchcols as $sc) {
	//Like list
	if (($i == 0) AND (trim($sc) != '')) {
	    $like_list .= "\n\t" . '$this->db->like(\'' . $sc . '\', $search);';  //first field
	} else {
	    $like_list .= "\n\t" . '$this->db->or_like(\'' . $sc . '\', $search);';
	}
	$i++;
    }//end foreach

    $serach_feature_text = '
	    /**
	    * Simple "like based" search (NOT full text!).
	    *
	    * @author  Lorenzo Sanzari (ulisse73@quipo.it)
	    * @param   string $search
	    * @param   int $limit
	    * @param   int $offset
	    * @return  object
	    */
	   function search($search, $limit = NULL, $offset = NULL) {
	       $this->db->select();
	       $this->db->from(\'' . $table_name . '\');
	       {like_list}
	       if ($limit != NULL)
		   $this->db->limit($limit, $offset);
	       $res = $this->db->get()->result();
	       return $res;
	   }//end search

	   /**
	    * Full text search.
	    *
	    * For this feature, you must create a full text index over the search interested fields: ex.
	    *
	    * ALTER TABLE  `bf_' . $table_name . '` ADD FULLTEXT ({alter_text});
	    *
	    * @author  Lorenzo Sanzari (ulisse73@quipo.it)
	    * @param   string $search
	    * @param   int $limit
	    * @param   int $offset
	    * @return  object
	    */
	   public function ft_search($search, $limit = NULL, $offset = NULL) {
	       $this->db->select();
	       $this->db->from(\'' . $table_name . '\');
	       $this->db->where("MATCH({match_list}) AGAINST(\'" . $search . "\')");
	       if ($limit != NULL)
		   $this->db->limit($limit, $offset);
	       $res = $this->db->get()->result();
	       return $res;
	   }//end ft_search';

    $match_list = implode(',', $searchcols);
    $alter_text = implode(',', $searchcols);

    //$serach_feature_text
    $serach_feature_text = str_replace('{like_list}', $like_list, $serach_feature_text);
    $serach_feature_text = str_replace('{match_list}', $match_list, $serach_feature_text);
    $serach_feature_text = str_replace('{alter_text}', $alter_text, $serach_feature_text);
} else { //end if searchcols
    //No search feature implementation
    $serach_feature_text = '

		//NO TEXT FIELDS! - SEARCH NOT IMPLEMENTED.

		/**
		* Simple "like based" search (NOT full text!).
		*
		* @author  Lorenzo Sanzari (ulisse73@quipo.it)
		* @param   string $search
		* @param   int $limit
		* @param   int $offset
		* @return  object
		*/
	       function search($search, $limit = NULL, $offset = NULL) {
		   /*
		   $this->db->select();
		   $this->db->from(\'' . $table_name . '\');
		   $this->db->like(\'fieldname1\');
		   $this->db->like(\'fieldname2\');
		   if ($limit != NULL)
		       $this->db->limit($limit, $offset);
		   $res = $this->db->get()->result();
		   return $res;
		   */
		   return NULL;
	       }//end search


	       /**
		* Full text search.
		*
		* For this feature, you must create a full text index over the search interested fields: ex.
		*
		* ALTER TABLE  `bf_' . $table_name . '` ADD FULLTEXT (fieldname1, fieldname2);
		*
		* @author  Lorenzo Sanzari (www.icomlab.it)
		* @param   string $search
		* @param   int $limit
		* @param   int $offset
		* @return  object
		*/
	       public function ft_search($search, $limit = NULL, $offset = NULL) {
		   /*
		   $this->db->select();
		   $this->db->from(\'' . $table_name . '\');
		   $this->db->where("MATCH(fieldname1, fieldname2) AGAINST(\'" . $search . "\')");
		   if ($limit != NULL)
		       $this->db->limit($limit, $offset);
		   $res = $this->db->get()->result();
		   return $res;
		   */
		   return NULL;
	       }//end ft_search';
}




//------------------------------------------------------------------------------
// Output the model
//------------------------------------------------------------------------------

echo "<?php defined('BASEPATH') || exit('No direct script access allowed');

class {$ucControllerName}_model extends BF_Model
{
    {$fields}

	// Customize the operations of the model without recreating the insert,
    // update, etc. methods by adding the method names to act as callbacks here.
	protected \$before_insert 	= array();
	protected \$after_insert 	= array();
	protected \$before_update 	= array();
	protected \$after_update 	= array();
	protected \$before_find 	    = array();
	protected \$after_find 		= array();
	protected \$before_delete 	= array();
	protected \$after_delete 	= array();

	// For performance reasons, you may require your model to NOT return the id
	// of the last inserted row as it is a bit of a slow method. This is
    // primarily helpful when running big loops over data.
	protected \$return_insert_id = true;

	// The default type for returned row data.
	protected \$return_type = 'object';

	// Items that are always removed from data prior to inserts or updates.
	protected \$protected_attributes = array();

	// You may need to move certain rules (like required) into the
	// \$insert_validation_rules array and out of the standard validation array.
	// That way it is only required during inserts, not updates which may only
	// be updating a portion of the data.
	protected \$validation_rules 		= array({$rules});
	protected \$insert_validation_rules  = array();
	protected \$skip_validation 			= false;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    " . $serach_feature_text . "

}";
