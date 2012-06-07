<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * User Model
 *
 * The central way to access and perform CRUD on users.
 *
 * @package    Bonfire
 * @subpackage Modules_Users
 * @category   Models
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com
 */
class User_model extends BF_Model
{

	/**
	 * Name of the table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * Use soft deletes or not
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $soft_deletes = TRUE;

	/**
	 * The date format to use
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $date_format = 'datetime';

	/**
	 * Set the created time automatically on a new record
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	protected $set_modified = FALSE;

	//--------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Helper Method for Generating Password Hints based on Settings library.
	 *
	 * Call this method in your controller and echo $password_hints in your view.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function password_hints()
	{
		$min_length = (string) $this->settings_lib->item('auth.password_min_length');

		$message = sprintf( lang('bf_password_min_length_help'), $min_length );


		if ( $this->settings_lib->item('auth.password_force_numbers') == 1 )
		{
			$message .= '<br />' . lang('bf_password_number_required_help');
		}

		if ( $this->settings_lib->item('auth.password_force_symbols') == 1 )
		{
			$message .= '<br />' . lang('bf_password_symbols_required_help');
		}

		if ( $this->settings_lib->item('auth.password_force_mixed_case') == 1 )
		{
			$message .= '<br />' . lang('bf_password_caps_required_help');
		}

		Template::set('password_hints', $message);

		unset ($min_length, $message);

	}//end password_hints()

	//--------------------------------------------------------------------

	/**
	 * Creates a new user in the database.
	 *
	 * Required parameters sent in the $data array:
	 * * password
	 * * A unique email address
	 *
	 * If no _role_id_ is passed in the $data array, it will assign the default role from <Roles> model.
	 *
	 * @access public
	 *
	 * @param array $data An array of user information.
	 *
	 * @return bool|int The ID of the new user.
	 */
	public function insert($data=array())
	{
		if (!$this->_function_check(FALSE, $data))
		{
			return FALSE;
		}

		if (!isset($data['password']) || empty($data['password']))
		{
			$this->error = lang('us_no_password');
			return FALSE;
		}

		if (!isset($data['email']) || empty($data['email']))
		{
			$this->error = lang('us_no_email');
			return FALSE;
		}

		// Is this a unique email?
		if ($this->is_unique('email', $data['email']) == FALSE)
		{
			$this->error = lang('us_email_taken');
			return FALSE;
		}

		if (empty($data['username']))
		{
		  unset($data['username']);
		}

		// Display Name
		if (!isset($data['display_name']) || (isset($data['display_name']) && empty($data['display_name'])))
		{
			if ($this->settings_lib->item('auth.use_usernames') == 1 && !empty($data['username']))
			{
				$data['display_name'] = $data['username'];
			}
			else
			{
				$data['display_name'] = $data['email'];
			}
		}

		list($password, $salt) = $this->hash_password($data['password']);

		unset($data['password'], $data['pass_confirm'], $data['submit']);

		$data['password_hash'] = $password;
		$data['salt'] = $salt;

		// What's the default role?
		if (!isset($data['role_id']))
		{
			// We better have a guardian here
			if (!class_exists('Role_model'))
			{
				$this->load->model('roles/Role_model','role_model');
			}

			$data['role_id'] = $this->role_model->default_role_id();
		}

		$id = parent::insert($data);

		Events::trigger('after_create_user', $id);

		return $id;

	}//end insert()

	//--------------------------------------------------------------------

	/**
	 * Updates an existing user. Before saving, it will:
	 * * generate a new password/salt combo if both password and pass_confirm are passed in.
	 * * store the country code
	 *
	 * @access public
	 *
	 * @param int   $id   An INT with the user's ID.
	 * @param array $data An array of key/value pairs to update for the user.
	 *
	 * @return bool TRUE/FALSE
	 */
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

			$data['password_hash'] = $password;
			$data['salt'] = $salt;
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

	}//end update()


	/**
	 * Returns the number of users that belong to each role.
	 *
	 * @access public
	 *
	 * @return bool|array An array of objects representing the number in each role.
	 */
	public function set_to_default_role($current_role)
	{
		$prefix = $this->db->dbprefix;

		if (!is_int($current_role)) {
			return FALSE;
		}

		// We better have a guardian here
		if (!class_exists('Role_model'))
		{
			$this->load->model('roles/Role_model','role_model');
		}

		$data = array();
		$data['role_id'] = $this->role_model->default_role_id();

		$query = $this->db->where('role_id', $current_role)
				->update($this->table, $data);

		if ($query)
		{
			return TRUE;
		}

		return FALSE;

	}//end set_to_default_role()


	//--------------------------------------------------------------------

	/**
	 * Finds an individual user record. Also returns role information for the user.
	 *
	 * @access public
	 *
	 * @param int $id An INT with the user's ID.
	 *
	 * @return bool|object An object with the user's information.
	 */
	public function find($id=null)
	{
		if (empty($this->selects))
		{
			$this->select($this->table .'.*, role_name');
		}

		$this->db->join('roles', 'roles.role_id = users.role_id', 'left');

		return parent::find($id);

	}//end find()

	//--------------------------------------------------------------------

	/**
	 * Returns all user records, and their associated role information.
	 *
	 * @access public
	 *
	 * @param bool $show_deleted If FALSE, will only return non-deleted users. If TRUE, will return both deleted and non-deleted users.
	 *
	 * @return bool An array of objects with each user's information.
	 */
	public function find_all($show_deleted=FALSE)
	{
		if (empty($this->selects))
		{
			$this->select($this->table .'.*, role_name');
		}

		if ($show_deleted === FALSE)
		{
			$this->db->where('users.deleted', 0);
		}

		$this->db->join('roles', 'roles.role_id = users.role_id', 'left');

		return parent::find_all();

	}//end find_all()

	//--------------------------------------------------------------------

	/**
	 * Locates a single user based on a field/value match, with their role information.
	 * If the $field string is 'both', then it will attempt to find the user
	 * where their $value field matches either the username or email on record.
	 *
	 * @access public
	 *
	 * @param string $field A string with the field to match.
	 * @param string $value A string with the value to search for.
	 *
	 * @return bool|object An object with the user's info, or FALSE on failure.
	 */
	public function find_by($field=null, $value=null)
	{
		$this->db->join('roles', 'roles.role_id = users.role_id', 'left');

		if (empty($this->selects))
		{
			$this->select($this->table .'.*, role_name');
		}

		if ($field == 'both')
		{
			$field = array(
				'username'	=> $value,
				'email'		=> $value
			);

			return parent::find_by($field, null, 'or');
		}

		return parent::find_by($field, $value);

	}//end find_by()

	//--------------------------------------------------------------------

	/**
	 * Returns the number of users that belong to each role.
	 *
	 * @access public
	 *
	 * @return bool|array An array of objects representing the number in each role.
	 */
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

		return FALSE;

	}//end count_by_roles()

	//--------------------------------------------------------------------

	/**
	 * Counts all users in the system.
	 *
	 * @access public
	 *
	 * @param bool $get_deleted If FALSE, will only return active users. If TRUE, will return both deleted and active users.
	 *
	 * @return int An INT with the number of users found.
	 */
	public function count_all($get_deleted = FALSE)
	{
		if ($get_deleted)
		{
			// Get only the deleted users
			$this->db->where('users.deleted !=', 0);
		}
		else
		{
			$this->db->where('users.deleted', 0);
		}

		return $this->db->count_all_results('users');

	}//end count_all()

	//--------------------------------------------------------------------

	/**
	 * Performs a standard delete, but also allows for purging of a record.
	 *
	 * @access public
	 *
	 * @param int  $id    An INT with the record ID to delete.
	 * @param bool $purge If FALSE, will perform a soft-delete. If TRUE, will permanently delete the record.
	 *
	 * @return bool TRUE/FALSE
	 */
	public function delete($id=0, $purge=FALSE)
	{
		if ($purge === TRUE)
		{
			// temporarily set the soft_deletes to TRUE.
			$this->soft_deletes = FALSE;
		}

		return parent::delete($id);

	}//end delete()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !AUTH HELPER METHODS
	//--------------------------------------------------------------------

	/**
	 * Generates a new salt and password hash for the given password.
	 *
	 * @access public
	 *
	 * @param string $old The password to hash.
	 *
	 * @return array An array with the hashed password and new salt.
	 */
	public function hash_password($old='')
	{
		if (!function_exists('do_hash'))
		{
			$this->load->helper('security');
		}

		$salt = $this->generate_salt();
		$pass = do_hash($salt . $old);

		return array($pass, $salt);

	}//end hash_password()

	//--------------------------------------------------------------------

	/**
	 * Create a salt to be used for the passwords
	 *
	 * @access private
	 *
	 * @return string A random string of 7 characters
	 */
	private function generate_salt()
	{
		if (!function_exists('random_string'))
		{
			$this->load->helper('string');
		}

		return random_string('alnum', 7);

	}//end generate_salt()

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !HMVC METHOD HELPERS
	//--------------------------------------------------------------------

	/**
	 * Returns the most recent login attempts and their description.
	 *
	 * @access public
	 *
	 * @param int $limit An INT which is the number of results to return.
	 *
	 * @return bool|array An array of objects with the login information.
	 */
	public function get_login_attempts($limit=15)
	{
		$this->db->limit($limit);
		$this->db->order_by('login', 'desc');
		$query = $this->db->get('login_attempts');

		if ($query->num_rows())
		{
			return $query->result();
		}

		return FALSE;

	}//end get_login_attempts()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !META METHODS
	//--------------------------------------------------------------------

	/**
	 * Saves one or more key/value pairs of additional meta information for a user.
	 *
	 * @access public
	 * @example
	 * $data = array(
	 *    'location'	=> 'That City, Katmandu',
	 *    'interests'	=> 'My interests'
	 *    );
	 * $this->user_model->save_meta_for($user_id, $data);
	 *
	 * @param int   $user_id The ID of the user to save the meta for.
	 * @param array $data    An array of key/value pairs to save.
	 *
	 * @return void
	 */
	public function save_meta_for($user_id=null, $data=array())
	{
		if (!is_numeric($user_id))
		{
			$this->error = lang('us_invalid_user_id');
		}

		$this->table	= 'user_meta';
		$this->key		= 'meta_id';

		foreach ($data as $key => $value)
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('meta_key', $key);
			$query = $this->db->get('user_meta');

			$obj = array(
				'user_id'		=> $user_id,
				'meta_key'		=> $key,
				'meta_value'	=> $value
			);

			if ($query->num_rows() == 0 && !empty($value))
			{
				// Insert
				$this->db->insert('user_meta', $obj);
			}
			// Update
			else if ($query->num_rows() > 0)
			{
				$row = $query->row();
				$meta_id = $row->meta_id;

				$this->db->where('user_id', $user_id);
				$this->db->where('meta_key', $key);
				$this->db->set('meta_value', $value);
				$this->db->update('user_meta', $obj);
			}//end if
		}//end foreach


		// Reset our table info
		$this->table	= 'users';
		$this->key		= 'id';
	}//end save_meta_for()

	//--------------------------------------------------------------------

	/**
	 * Retrieves all meta values defined for a user.
	 *
	 * @access public
	 *
	 * @param int   $user_id An INT with the user's ID to find the meta for.
	 * @param array $fields  An array of meta_key names to retrieve.
	 *
	 * @return null A stdObject with the key/value pairs, or NULL.
	 */
	public function find_meta_for($user_id=null, $fields=null)
	{
		if (!is_numeric($user_id))
		{
			$this->error = lang('us_invalid_user_id');
		}

		$this->table	= 'user_meta';
		$this->key		= 'meta_id';

		// Limiting to certain fields?
		if (is_array($fields))
		{
			$this->db->where_in('meta_key', $fields);
		}

		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_meta');

		if ($query->num_rows())
		{
			$rows = $query->result();

			$result = null;
			foreach ($rows as $row)
			{
				$key = $row->meta_key;
				$result->$key = $row->meta_value;
			}
		}
		else
		{
			$result = null;
		}

		// Reset our table info
		$this->table	= 'users';
		$this->key		= 'id';

		return $result;

	}//end find_meta_for()

	//--------------------------------------------------------------------

	/**
	 * Locates a single user and joins there meta information based on a the user id match.
	 *
	 * @access public
	 *
	 * @param int $user_id Integer of User ID to fetch
	 *
	 * @return bool|object An object with the user's info and meta information, or FALSE on failure.
	 */
	public function find_user_and_meta($user_id=null)
	{
		if (!is_numeric($user_id))
		{
			$this->error = lang('us_invalid_user_id');
		}

		$result = $this->find( $user_id );

		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_meta');

		if ($query->num_rows())
		{
			$rows = $query->result();

			foreach ($rows as $row)
			{
				$key = $row->meta_key;
				$result->$key = $row->meta_value;
			}
		}

		$query->free_result();
		return $result;

	}//end find_user_and_meta()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !ACTIVATION
	//--------------------------------------------------------------------

	/**
	 * Count Inactive users.
	 *
	 * @access public
	 *
	 * @return int Inactive user count.
	 */
	public function count_inactive_users()
	{
        $this->db->where('active',-1);
        return $this->count_all(FALSE);

    }//end count_inactive_users()


	/**
	 * Accepts an activation code and validates is against a matching entry int eh database.
	 *
	 * There are some instances where we want to remove the activation hash yet leave the user
	 * inactive (Admin Activation scenario), so leave_inactive handles this use case.
	 *
	 * @access public
	 *
	 * @param string $email          The email address to be verified
	 * @param string $code           The activation code to be verified
	 * @param bool   $leave_inactive Flag whether to remove the activate hash value, but leave active = 0
	 *
	 * @return int User Id on success, FALSE on error
	 */
	public function activate($email = FALSE, $code = FALSE, $leave_inactive = FALSE)
	{

		if ($code === FALSE)
		{
	        $this->error = lang('us_err_no_activate_code');
			return FALSE;
	    }

		if (!empty($email))
		{
			$this->db->where('email', $email);
		}

	    $query = $this->db->select('id')
               	      ->where('activate_hash', $code)
               	      ->limit(1)
               	      ->get($this->table);

		if ($query->num_rows() !== 1)
		{
		    $this->error = lang('us_err_no_matching_code');
	        return FALSE;
		}

	    $result = $query->row();
		$active = ($leave_inactive === FALSE) ? 1 : 0;
		if ($this->update($result->id, array('activate_hash' => '','active' => $active)))
		{
			return $result->id;
		}

	}//end activate()


	/**
	 * This function is triggered during account set up to assure user is not active and,
	 * if not supressed, generate an activation hash code. This function can be used to
	 * deactivate accounts based on public view events.
	 *
	 * @param int    $user_id    The username or email to match to deactivate
	 * @param string $login_type Login Method
	 * @param bool   $make_hash  Create a hash
	 *
	 * @return mixed $activate_hash on success, FALSE on error
	 */
	public function deactivate($user_id = FALSE, $login_type = 'email', $make_hash = TRUE)
	{
	    if ($user_id === FALSE)
		{
	        return FALSE;
	    }

		// create a temp activation code.
        $activate_hash = '';
		if ($make_hash === true)
		{
			$this->load->helpers(array('string', 'security'));
			$activate_hash = do_hash(random_string('alnum', 40) . time());
		}

		$this->db->update($this->table, array('active'=>0,'activate_hash' => $activate_hash), array($login_type => $user_id));

		return ($this->db->affected_rows() == 1) ? $activate_hash : FALSE;

	}//end deactivate()


	/**
	 * Admin specific activation function for admin approvals or re-activation.
	 *
	 * @access public
	 *
	 * @param int $user_id The user ID to activate
	 *
	 * @return bool TRUE on success, FALSE on error
	 */
	public function admin_activation($user_id = FALSE)
	{

		if ($user_id === FALSE)
		{
			$this->error = lang('us_err_no_id');
	        return FALSE;
	    }

		$query = $this->db->select('id')
               	      ->where('id', $user_id)
               	      ->limit(1)
               	      ->get($this->table);

		if ($query->num_rows() !== 1)
		{
		    $this->error = lang('us_err_no_matching_id');
	        return FALSE;
		}

		$result = $query->row();
		$this->update($result->id, array('activate_hash' => '','active' => 1));

		if ($this->db->affected_rows() > 0)
		{
			return $result->id;
		}
		else
		{
			$this->error = lang('us_err_user_is_active');
			return FALSE;
		}

	}//end admin_activation()


	/**
	 * Admin only deactivation function.
	 *
	 * @access public
	 *
	 * @param int $user_id The user ID to deactivate
	 *
	 * @return bool TRUE on success, FALSE on error
	 */
	public function admin_deactivation($user_id = FALSE)
	{
		if ($user_id === FALSE)
		{
			$this->error = lang('us_err_no_id');
	        return FALSE;
	    }

		if ($this->deactivate($user_id, 'id', FALSE))
		{
			return $user_id;
		}
		else
		{
			$this->error = lang('us_err_user_is_inactive');
			return FALSE;
		}

	}//end admin_deactivation()

	//--------------------------------------------------------------------

}//end User_model
