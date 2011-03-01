<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Page_model extends MY_Model {

	protected $table			= 'pages';
	protected $version_table	= 'versions';
	protected $key				= 'page_id';
	protected $soft_deletes		= true;
	protected $date_format		= 'datetime';
	protected $set_created		= true;
	protected $set_modified 	= true;
	
	//--------------------------------------------------------------------
	
	public function find_all($show_unpublished=false, $show_deleted=false) 
	{
		if ($show_unpublished === false)
		{
			$this->db->where('published', 1);
		}
	
		if ($show_deleted === true)
		{
			$this->db->where('deleted', 1);
		} 
		else 
		{
			$this->db->where('deleted', 0);
		}
		
		
		return parent::find_all();
	}
	
	//--------------------------------------------------------------------
	
	
	/*
		Updates an existing page and saves a version of the old data.	
	*/
	public function update($id=0, $data=null) 
	{
		// Sanity check
		if (empty($id) || !is_numeric($id) || !is_array($data))
		{
			$this->error = 'Not enough data.';
			return false;
		}
		
		$return = false;
		
		// Grab our existing page so we can save a version of it
		$old_page = $this->find($id);
		
		// Update our version
		$data['revision'] += 1;
		
		// Save the new page data
		$return = parent::update($id, $data);
		
		// Save the version
		$this->save_version($id, $old_page);
		
		// Clear this page's cache
		if (isset($data['alias']) && !empty($data['alias']))
		{
			$this->cache->delete('pages_'. $data['alias']);
		}
					
		return $return;
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !VERSIONING
	//--------------------------------------------------------------------
	
	/*
		Saves a version of the page data provided.
		
		Parameters:
			$id		- The id of the page to version.
			$data	- An array of data for the parent page. The data needed to save
						the version is pulled from this array. In order to properly
						save the verision, the array MUST include: 
							body		- the unprocessed body of the page.
							rte_type	- a string representing the type of text processor used. 'html', 'markdown', 'textile'
							revision	- the current version of the page. (This will be incremented in this method.)
							
		Return:
			true/false
	*/
	public function save_version($id=0, $data=null) 
	{	
		// Make sure data is an array
		$data = (array)$data;
	
		// Sanity check
		if (empty($id) || !is_numeric($id))
		{
			$this->error = 'Not enough data.';
			return false;
		}

		// Data check
		if (!isset($data['body']) || !isset($data['rte_type']) || !isset($data['revision']))
		{
			$this->error = 'Not all of the page data was provided.';
			return false;
		}
		
		// Save it! 
		$new_data = array(
			'page_id'		=> $id,
			'revision'		=> $data['revision'],
			'body'			=> $data['body'],
			'rte_type'		=> $data['rte_type'],
			'created_on'	=> date('Y-m-d H:i:s', time()),
			'created_by'	=> $this->auth->user_id()	
		);
		
		$i = $this->db->insert($this->version_table, $new_data);
		
		if ($i > 0)
		{
			return true;
		}
		
		$this->error = mysql_error();
		return false;
	}
	
	//--------------------------------------------------------------------
	

}