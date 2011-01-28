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
		
		$data['zipcode'] = (int)$data['zipcode'];
		$data['zip_extra'] = (int)$data['zip_extra'];
		
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
		
		return parent::find_by($field, $value);
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
	
	
}

// End User_model class