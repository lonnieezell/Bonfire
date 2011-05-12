<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

class User_model extends MY_Model {

	protected $table		= 'users';
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';
	protected $set_modified = false;

	public function __construct() 
	{
		parent::__construct();
	}
	
	//--------------------------------------------------------------------
	
	/*
		Method: insert()
		
		Creates a new user in the database.
		
		Parameters:
			$data	- An array of user information.
		
		Returns:
			$id	- The ID of the new user.
	*/
	public function insert($data=array()) 
	{
		if (!$this->_function_check(false, $data))
		{
			return false;
		}
		
		if (!isset($data['password']) || empty($data['password']))
		{
			$this->error = 'No Password present.';
			return false;
		}
		
		if (!isset($data['email']) || empty($data['email']))
		{
			$this->error = 'No Email given.';
			return false;
		}
		
		// Is this a unique email?
		if ($this->is_unique('email', $data['email']) == false)
		{
			$this->error = 'Email already exists.';
			return false;
		}
	
		list($password, $salt) = $this->hash_password($data['password']);
		
		unset($data['password'], $data['pass_confirm'], $data['submit']);
		
		$data['password_hash'] = $password;
		$data['salt'] = $salt;
		
		$data['zipcode'] = isset($data['zipcode']) ? $data['zipcode'] : null;
		
		// Handle the country
		if (isset($data['iso']))
		{
			$data['country_iso'] = $data['iso'];
			unset($data['iso']);
		}
		
		// What's the default role?
		if (!isset($data['role_id']))
		{
			$data['role_id'] = $this->role_model->default_role_id();
		}
		
		$id = parent::insert($data);
		
		Events::trigger('after_create_user', $id);
		
		return $id;
	}
	
	//--------------------------------------------------------------------
	
	public function update($id=null, $data=array()) 
	{
		if ($id)
		{
			$trigger_data = array('user_id'=>$id, 'data'=>$data);
			Events::trigger('before_user_update', $trigger_data);
		}
	
		if (empty($data['pass_confirm']) && isset($data['password'])) 
		{
			unset($data['pass_confirm'], $data['password']);
		} 
		else if (!empty($data['password']) && !empty($data['pass_confirm']) && $data['password'] == $data['pass_confirm'])
		{
			list($password, $salt) = $this->hash_password($data['password']);
		
			unset($data['password'], $data['pass_confirm']);
		}
		
		// Handle the country
		if (isset($data['iso']))
		{
			$data['country_iso'] = $data['iso'];
			unset($data['iso']);
		}
		
		$return = parent::update($id, $data);
		
		if ($return)
		{
			$trigger_data = array('user_id'=>$id, 'data'=>$data);
			Events::trigger('after_user_update', $trigger_data);
		}
		
		return $return;
	}
	
	//--------------------------------------------------------------------
	
	public function find($id=null) 
	{
		$this->db->join('roles', 'roles.role_id = users.role_id', 'left');
	
		return parent::find($id);
	}
	
	//--------------------------------------------------------------------
	
	
	public function find_all($show_deleted=false) 
	{
		if ($show_deleted === false)
		{
			$this->db->where('deleted', 0);
		}
		
		$this->db->join('roles', 'roles.role_id = users.role_id', 'left');
		
		return parent::find_all();
	}
	
	//--------------------------------------------------------------------
	
	public function find_by($field=null, $value=null) 
	{
		if ($field == 'both')
		{
			$field = array(
				'username'	=> $value,
				'email'		=> $value
			);
			
			return parent::find_by($field, null, 'or');
		}
		
		$this->db->join('roles', 'roles.role_id = users.role_id', 'left');
		
		return parent::find_by($field, $value);
	}
	
	//--------------------------------------------------------------------
	
	public function count_by_roles() 
	{
		$prefix = $this->db->dbprefix;
	
		$sql = "SELECT role_name, COUNT(1) as count
				FROM {$prefix}users, {$prefix}roles
				WHERE {$prefix}users.role_id = {$prefix}roles.role_id
				GROUP BY {$prefix}users.role_id";
		
		$query = $this->db->query($sql);
		
		if ($query->num_rows())
		{
			return $query->result();
		}

		return false;
	}
	
	//--------------------------------------------------------------------
	
	public function count_all($get_deleted = false) 
	{	
		if ($get_deleted)
		{
			// Get only the deleted users
			$this->db->where('deleted !=', 0);
		}
		else 
		{
			$this->db->where('deleted', 0);
		}
		
		return $this->db->count_all_results('users');
	}
	
	//--------------------------------------------------------------------
	
	public function delete($id=0, $purge=false) 
	{
		if ($purge === true)
		{
			// temporarily set the soft_deletes to true.
			$this->soft_deletes = false;
		}
		
		return parent::delete($id);
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !AUTH HELPER METHODS
	//--------------------------------------------------------------------
	
	public function hash_password($old='') 
	{
		if (!function_exists('do_hash'))
		{
			$this->load->helper('security');
		}
	
		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);
		
		return array($pass, $salt);
	}
	
	//--------------------------------------------------------------------
	
	private function generate_salt() 
	{
		if (!function_exists('random_string'))
		{
			$this->load->helper('string');
		}
		
		return random_string('alnum', 7);
	}
	
	//--------------------------------------------------------------------
	
	
	//--------------------------------------------------------------------
	// !HMVC METHOD HELPERS
	//--------------------------------------------------------------------
	
	public function get_login_attempts($limit=15) 
	{
		$this->db->limit($limit);
		$this->db->order_by('login', 'desc');
		$query = $this->db->get('login_attempts');
		
		if ($query->num_rows())
		{
			return $query->result();
		}
		
		return false;
	}
	
	//--------------------------------------------------------------------
	
	public function get_access_logs($limit=15) 
	{
		$this->db->limit($limit);
		$this->db->order_by('last_login', 'desc');
			
		return $this->find_all();
	}
	
	//--------------------------------------------------------------------
	
}

// End User_model class