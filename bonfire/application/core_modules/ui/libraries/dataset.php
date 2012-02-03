<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
	Class: Dataset

	Provides a simple mechanism to render out a database result set
	into a table of the data with custom columns, headers and has
	filters, download options and pagination built in.
*/
class Dataset {

	/*
		Var: $data_model
		Stores the name of the model to use.
	*/
	protected	$data_model;

	/*
		Var: $data_method
		Stores the model's method to get results from.
	*/
	protected	$data_method;

	/*
		Var: $columns
		Stores the column information about how they should be displayed.
	*/
	protected	$columns;

	/*
		Var: $actions
		Stores the bulk actions to present to the user.
	*/
	protected	$actions;

	/*
		Var: $per_page
		The number of results to show per page.
		Defaults to the config item 'site.list_limit'
	*/
	protected	$per_page;

	/*
		Var: $total_rows
		The total number of results
	*/
	protected	$total_rows;

	/*
		Var: $data
		The database result set.
	*/
	protected 	$data;

	/*
		Var: $ci
		A pointer to the CodeIgniter instance.
	*/
	protected	$ci;

	//--------------------------------------------------------------------

	public function __construct()
	{
		$this->ci =& get_instance();
	}

	//--------------------------------------------------------------------

	/*
		Method: initialize()

		Stores the settings to use.

		Parameters:
			$params	- An array of key/value pairs that correspond to the
					  class vars.
	*/
	public function initialize($params=array())
	{
		foreach ($params as $param => $value)
		{
			if (isset($this->param))
			{
				$this->param = $key;
			}
		}
		// Per Page
		if (empty($this->per_page))
		{
			$this->per_page = $this->ci->settings_lib->item('site.list_limit');
		}

		// Get our database results.
		if (empty($this->data_model) || empty($this->data_method))
		{
			die('[Dataset] Unable to initialize the dataset without a source.');
		}

		$this->ci->load->model($this->data_model, 'data_model');
		$data_method = $this->data_method;

		// Setup our paging for results
		$this->ci->data_model->limit($this->per_page, 0);

		$this->data = $this->ci->data_model->$data_method();

		// Make the results avaialble to our views
		Template::set('results', $this->data);
	}

	//--------------------------------------------------------------------

	/*
		Method: set_source()

		Sets the model and method to use to retrieve the data
		from the database.

		Parameters:
			$data_model		- The name of the model class to use.
			$data_method	- The name of the method in that model that should
							  be used to retrieve the results from the database.
	*/
	public function set_source($data_model=null, $data_method=null)
	{
		if (empty($data_method) || empty($data_method))
		{
			trigger_error('[Dataset] Invalid data provided to the dataset.');
		}

		$this->data_model	= $data_model;
		$this->data_method	= $data_method;
	}

	//--------------------------------------------------------------------

	/*
		Method: columns()

		Takes an assoc array that describes the different columns that
		should be displayed in the table and the filters for that column.

		The array should look like:
			$columns = array(
				array(
					'field'	=> 'database_field',
					'type'	=> 'text',
					'title'	=> 'Column Title',
					'width'	=> '10%',
				),
				. . .
			);

		Available column types are 'text', 'date', 'select'.
	*/
	public function columns($columns = array())
	{
		foreach ($columns as $column)
		{
			/*
				Prep the column data.
			*/
			$this->columns[] = array(
				'field'				=> $column['field'],
				'title'				=> isset($column['title']) ? $column['title'] : ucwords(str_replace('_', ' ', $column['field'])),
				'type'				=> isset($column['type']) ? strtolower($column['type'])	: false,
				'width'				=> isset($column['width']) ? $column['width'] : 'auto',
	    		'filter_name' 		=> isset($column['filter']) ? $column['filter'] : false,
	    		'field_start_date'	=> isset($column['field_start_date']) ? $column['field_start_date'] : '',
	    		'field_end_date' 	=> isset($column['field_end_date']) ? $column['field_end_date'] : '',
	    		'options' 			=> isset($column['options']) ? $column['options'] : array(),
			);

			/*
				Error Checking
			*/

			// Date
			if (isset($column['type']) && $column['type'] == 'date' && ( !isset($column['field_start_date']) || !isset($column['field_end_date']) ))
			{
				trigger_error('[Dataset] Unable to create a "date" filter without valid field_start_date and field_end_date values.');
			}

			// Selects
			else if (isset($column['type']) && $column['type'] == 'select' && ( !isset($column['options']) || !is_array($column['options']) ))
			{
				trigger_error('[Dataset] Unable to create a "select" filter without a valid array for "options".');
			}

		}

		reset($this->columns);
	}

	//--------------------------------------------------------------------

	/*
		Method: actions()

		Sets the bulk actions that will be presented to the user.

		Parameters:
			$actions	- An array of actions. Each action should have
						  both a title and a path key/value pair.

		Examples:
			$actions = array(
				array(
					'title'	=> 'Delete',
					'path'	=> SITE_AREA .'settings/users/delete'
				)
			);
	*/
	public function actions($actions=array())
	{
		if (count($actions))
		{
			$this->actions = $actions;
		}
	}

	//--------------------------------------------------------------------

	public function set_selects($selects=null)
	{
		if (empty($selects) || empty($this->data_model))
		{
			return;
		}

		$this->ci->load->model($this->data_model, 'data_model');

		$this->ci->data_model->select($selects);
	}

	//--------------------------------------------------------------------

	/*
		Method: results()

		Returns the result set that was retrieved from the database.
	*/
	public function results()
	{
		return $this->data;
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !Rendering Methods
	//--------------------------------------------------------------------

	/*
		Method: table_open()

		Displays the table head tags based on $columns, along with a
		filters row, if applicable.
	*/
	public function table_open()
	{
		if (!is_array($this->columns) || !count($this->columns))
		{
			return '';
		}

		$output = '';

		if (is_array($this->actions))
		{
			$output .= '<form action="'. current_url() .'" method="post">';
		}

		$output .= "<table class='table table-striped'><thead><tr>";

		// If we have any actions, we need to make a series of checkboxes for them!
		if (is_array($this->actions))
		{
			$output .= '<th style="width: 2em"><input type="checkbox" class="check-all" /></th>';
		}

		foreach ($this->columns as $column)
		{
			$output .= "<th style='width: {$column['width']};'>{$column['title']}</th>";
		}

		$output .= "</tr></thead><tbody>";

		return $output;
	}

	//--------------------------------------------------------------------

	/*
		Method: table_close()

		Closes out the table, displays pagination and download links.
	*/
	public function table_close()
	{
		$output = '';

		$output .= "</tbody>";

		if (is_array($this->actions))
		{
			$output .= '<tfoot><tr><td colspan="'. (count($this->columns) + 1) .'"> With selected: ';

			foreach ($this->actions as $action)
			{
				$action = ucwords($action);
				$output .= "<input type='submit' name='submit' class='btn' value='{$action}' />&nbsp;&nbsp;";
			}

			$output .= '</td></tr></tfoot>';
		}

		$output .= '</table>';

		if (is_array($this->actions))
		{
			$output .= '</form>';
		}
		
		// Pagination
		echo "PerPage = {$this->per_page}, TotalRows={$this->total_rows}";

		return $output;
	}

	//--------------------------------------------------------------------

}
