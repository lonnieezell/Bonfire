<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
	
	public function insert($data=array()) 
	{
		list($password, $salt) = $this->hash_password($data['password']);
		
		unset($data['password'], $data['pass_confirm'], $data['submit']);
		
		$data['password_hash'] = $password;
		$data['salt'] = $salt;
		
		$data['zipcode'] = isset($data['zipcode']) ? (int)$data['zipcode'] : null;
		$data['zip_extra'] = isset($data['zip_extra']) ? (int)$data['zip_extra'] : null;
		
		// What's the default role?
		if (!isset($data['role_id']))
		{
			$data['role_id'] = $this->role_model->default_role_id();
		}
		
		return parent::insert($data);
	}
	
	//--------------------------------------------------------------------
	
	public function update($id=null, $data=array()) 
	{
		if (empty($data['pass_confirm']) && isset($data['password'])) 
		{
			unset($data['pass_confirm'], $data['password']);
		} 
		else if (!empty($data['password']) && !empty($data['pass_confirm']) && $data['password'] == $data['pass_confirm'])
		{
			list($password, $salt) = $this->hash_password($data['password']);
		
			unset($data['password'], $data['pass_confirm']);
		}
		
		if (isset($data['zipcode']))
		{
			$data['zipcode'] = (int)$data['zipcode'];
			$data['zip_extra'] = (int)$data['zip_extra'];
		}
		
		return parent::update($id, $data);
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
			$this->db->where('deleted', 1);
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
		if (!function_exists('dohash'))
		{
			$this->load->helper('security');
		}
	
		$salt = $this->generate_salt();
		$pass = dohash($salt . $old);
		
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