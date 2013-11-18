<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
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
	protected $table_name = 'users';

	/**
	 * Name of the user meta table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $meta_table = 'user_meta';

	/**
	 * Name of the roles table
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected $roles_table = 'roles';

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

    /**
     * @var Bool Optionally skip the validation.
     */
    protected $skip_validation = TRUE;

    /**
     * @var Array Validation rules
     */
    protected $validation_rules = array(
        array(
            'field' => 'password',
            'label' => 'lang:bf_password',
            'rules' => 'max_length[120]|valid_password|matches[pass_confirm]',
        ),
        array(
            'field' => 'pass_confirm',
            'label' => 'lang:bf_password_confirm',
            'rules' => '',
        ),
        array(
            'field' => 'display_name',
            'label' => 'lang:bf_display_name',
            'rules' => 'trim|max_length[255]',
        ),
        array(
            'field' => 'language',
            'label' => 'lang:bf_language',
            'rules' => 'required|trim',
        ),
        array(
            'field' => 'timezones',
            'label' => 'lang:bf_timezone',
            'rules' => 'required|trim|max_length[4]',
        ),
        array(
            'field' => 'username',
            'label' => 'lang:bf_username',
            'rules' => 'trim|max_length[30]',
        ),
        array(
            'field' => 'email',
            'label' => 'lang:bf_email',
            'rules' => 'required|trim|valid_email|max_length[120]',
        ),
        array(
            'field' => 'role_id',
            'label' => 'lang:us_role',
            'rules' => 'trim|max_length[2]|is_numeric',
        ),
    );

    /**
     * @var Array Additional Validation rules only used on insert
     */
    protected $insert_validation_rules = array(
        array(
            'field' => 'password',
            'label' => 'lang:bf_password',
            'rules' => 'required',
        ),
        array(
            'field' => 'pass_confirm',
            'label' => 'lang:bf_password_confirm',
            'rules' => 'required',
        ),
    );

    /**
     * @var array Metadata for the model's database fields
     */
    protected $field_info = array(
        array('name' => 'id', 'primary_key' => 1),
        array('name' => 'created_on'),
        array('name' => 'deleted'),
        array('name' => 'role_id'),
        array('name' => 'email'),
        array('name' => 'username'),
        array('name' => 'password_hash'),
        array('name' => 'reset_hash'),
        array('name' => 'last_login'),
        array('name' => 'last_ip'),
        array('name' => 'banned'),
        array('name' => 'ban_message'),
        array('name' => 'reset_by'),
        array('name' => 'display_name'),
        array('name' => 'display_name_changed'),
        array('name' => 'timezone'),
        array('name' => 'language'),
        array('name' => 'active'),
        array('name' => 'activate_hash'),
        array('name' => 'force_password_reset'),
    );

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
     * Extracts the model's fields (except the key and those handled by
     * Observers) from the $post_data and returns an array of name => value pairs
     *
     * @param Array $post_data The post data, usually $this->input->post() when called from the controller
     *
     * @return Array    An array of name => value pairs containing the data for the model's fields
     */
    public function prep_data($post_data)
    {
        $data = parent::prep_data($post_data);

        if ( ! empty($post_data['timezones'])) {
            $data['timezone'] = $post_data['timezones'];
        }
        if ( ! empty($post_data['password'])) {
            $data['password'] = $post_data['password'];
        }
        if ($data['display_name'] === '') {
            unset($data['display_name']);
        }
        if (isset($post_data['restore']) && $post_data['restore']) {
            $data['deleted'] = 0;
        }
        if (isset($post_data['unban']) && $post_data['unban']) {
            $data['banned'] = 0;
        }
		if (isset($post_data['activate']) && $post_data['activate']) {
			$data['active'] = 1;
		} elseif (isset($post_data['deactivate']) && $post_data['deactivate']) {
			$data['active'] = 0;
		}

        return $data;
    }

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
		// Display Name
		if (empty($data['display_name']))
		{
			if ($this->settings_lib->item('auth.use_usernames') == 1 && ! empty($data['username']))
			{
				$data['display_name'] = $data['username'];
			}
			else
			{
				$data['display_name'] = $data['email'];
			}
		}

		$password = $this->auth->hash_password($data['password']);

		if (empty($password) || empty($password['hash']))
		{
			return false;
		}

		$data['password_hash'] = $password['hash'];

		unset($data['password'], $password);

		// What's the default role?
		if ( ! isset($data['role_id']))
		{
			// We better have a guardian here
			if ( ! class_exists('Role_model'))
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
		if (empty($id))
		{
			return NULL;
		}

		$trigger_data = array('user_id'=>$id, 'data'=>$data);
		Events::trigger('before_user_update', $trigger_data);

		if ( ! empty($data['password']))
		{
			$password = $this->auth->hash_password($data['password']);

			if (empty($password) || empty($password['hash']))
			{
				return false;
			}

			$data['password_hash'] = $password['hash'];

			unset($data['password'], $password);
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
			$trigger_data = array('user_id' => $id, 'data' => $data);
			Events::trigger('after_user_update', $trigger_data);
		}

		return $return;

	}//end update()


	/**
	 * For all users with the matching role, set them to the default role instead.
	 *
	 * @access public
	 *
	 * @return bool
	 */
	public function set_to_default_role($current_role)
	{
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
				->update($this->table_name, $data);

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
			$this->select($this->table_name . '.*, role_name');
		}

		$this->db->join($this->roles_table, $this->roles_table . '.role_id = ' . $this->table_name . '.role_id', 'left');

		return parent::find($id);

	}//end find()

	//--------------------------------------------------------------------

	/**
	 * Returns all user records, and their associated role information.
	 *
	 * @access public
	 *
	 * @return bool An array of objects with each user's information.
	 */
	public function find_all()
	{
		if (empty($this->selects))
		{
			$this->select($this->table_name . '.*, role_name');
		}

		$this->db->join($this->roles_table, $this->roles_table . '.role_id = ' . $this->table_name . '.role_id', 'left');

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
	 * @param string $type  The type of where clause to create. Either 'and' or 'or'.
	 *
	 * @return bool|object An object with the user's info, or FALSE on failure.
	 */
	public function find_by($field=null, $value=null, $type='and')
	{
		if (empty($this->selects))
		{
			$this->select($this->table_name . '.*, role_name');
		}

		$this->db->join($this->roles_table, $this->roles_table . '.role_id = ' . $this->table_name . '.role_id', 'left');

		return parent::find_by($field, $value, $type);

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
		$this->db->select(array(
				$this->roles_table . '.role_name',
				'count(1) as count',
			))
			->from($this->table_name)
			->join($this->roles_table, $this->roles_table . '.role_id = ' . $this->table_name . '.role_id', 'left')
			->group_by($this->table_name . '.role_id');

		$query = $this->db->get();

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
			$this->db->where($this->table_name . '.deleted !=', 0);
		}
		else
		{
			$this->db->where($this->table_name . '.deleted', 0);
		}

		return $this->db->count_all_results($this->table_name);

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

	/**
	 * Flags one or more user accounts to require their password to
	 * be reset on their next login.
	 *
	 * @param  int $user_id
	 *
	 * @return bool TRUE/FALSE
	 */
	public function force_password_reset($user_id=null)
	{
		if (!empty($user_id) && is_numeric($user_id))
		{
			$this->db->where('id', $user_id);
		}

		return $this->db->set('force_password_reset', 1)->update($this->table_name);
	}

	//--------------------------------------------------------------------


	//--------------------------------------------------------------------
	// !AUTH HELPER METHODS
	//--------------------------------------------------------------------

	/**
	 * Generates a new password hash for the given password.
	 *
	 * @access public
	 *
	 * @param string $old The password to hash.
	 * @param int	 $iterations	The number of iterations to use in generating the hash
	 *
	 * @return array An array with the hashed password and the number of iterations, or false
	 */
	public function hash_password($old='', $iterations=0)
	{
		$password = $this->auth->hash_password($old, $iterations);

		if (empty($password) || empty($password['hash']))
		{
			return false;
		}

		return array($password['hash'], $password['iterations']);

	}//end hash_password()

	//--------------------------------------------------------------------

	/**
	 * Create a salt (no longer used for passwords)
	 *
	 * @access private
	 *
	 * @return string A random string of 7 characters
	 */
	private function generate_salt()
	{
		if ( ! function_exists('random_string'))
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
	 * @return boolean true/false
	 */
	public function save_meta_for($user_id=null, $data=array())
	{
		if (!is_numeric($user_id))
		{
			$this->error = lang('us_invalid_user_id');
			return false;
		}

		// While this won't give us the results of each
		// insert, it does give some sort of indiciation about
		// the success/failure since if one fails, it's likely
		// others will also.
		$result = false;

		foreach ($data as $key => $value)
		{
			$this->db->where('user_id', $user_id);
			$this->db->where('meta_key', $key);
			$query = $this->db->get($this->meta_table);

			$obj = array(
				'user_id'		=> $user_id,
				'meta_key'		=> $key,
				'meta_value'	=> $value
			);

			if ($query->num_rows() == 0)
			{
				// Insert
				$result = $this->db->insert($this->meta_table, $obj);
			}
			// Update
			else if ($query->num_rows() > 0)
			{
				$row = $query->row();
				$meta_id = $row->meta_id;

				$this->db->where('user_id', $user_id);
				$this->db->where('meta_key', $key);
				$this->db->set('meta_value', $value);
				$result = $this->db->update($this->meta_table, $obj);
			}//end if
		}//end foreach

		return $result;
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

		// Limiting to certain fields?
		if (is_array($fields))
		{
			$this->db->where_in('meta_key', $fields);
		}

		$this->db->where('user_id', $user_id);
		$query = $this->db->get($this->meta_table);

		if ($query->num_rows())
		{
			$rows = $query->result();

			$result = new stdClass;
			foreach ($rows as $row)
			{
				$key = $row->meta_key;
				$result->$key = $row->meta_value;
			}
		}
		else
		{
			$result = new stdClass;
		}

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
		$query = $this->db->get($this->meta_table);

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
	 * @param int    $user_id        The user to be activated (NULL will match any)
	 * @param string $code           The activation code to be verified
	 * @param bool   $leave_inactive Flag whether to remove the activate hash value, but leave active = 0
	 *
	 * @return int User Id on success, FALSE on error
	 */
	public function activate($user_id, $code, $leave_inactive = FALSE)
	{
		if ($user_id)
		{
			$this->db->where('id', $user_id);
		}

		$query = $this->db->select('id')
		                  ->where('activate_hash', $code)
		                  ->get($this->table_name);

		if ($query->num_rows() !== 1)
		{
		    $this->error = lang('us_err_no_matching_code');
	        return FALSE;
		}

		// Now we can find the $user_id, even if it was passed as NULL
		$result = $query->row();
		$user_id = $result->id;

		$active = ($leave_inactive === FALSE) ? 1 : 0;
		if ($this->update($user_id, array('activate_hash' => '','active' => $active)))
		{
			return $user_id;
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
	public function deactivate($user_id, $make_hash = TRUE)
	{
		// create a temp activation code.
        $activate_hash = '';
		if ($make_hash === true)
		{
			$this->load->helpers(array('string', 'security'));
			$activate_hash = do_hash(random_string('alnum', 40) . time());
		}

		$this->db->update($this->table_name, array('active'=>0,'activate_hash' => $activate_hash), array('id' => $user_id));

		if ($this->db->affected_rows() != 1)
		{
			return FALSE;
		}

		return $make_hash ? $activate_hash : TRUE;

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
               	      ->get($this->table_name);

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

    /**
     * Configure activation for the given user based on current user_activation_method
     *
     * @param Number $user_id User's ID
     *
     * @return Array    An array containing a 'message' (String) and 'error' (Boolean, true if an error occurred sending the activation email)
     */
    public function set_activation($user_id)
    {
        // User activation method
		$activation_method = $this->settings_lib->item('auth.user_activation_method');

        // Prepare user messaging vars
        $emailMsgData   = array();
        $emailView      = '';
        $subject        = '';
        $email_mess     = '';
        $message        = lang('us_email_thank_you');
        $type           = 'success';
        $site_title     = $this->settings_lib->item('site.title');
        $error          = false;

        switch ($activation_method) {
            case 0:
                // No activation required. Activate the user and send confirmation email
                $subject 	=  str_replace('[SITE_TITLE]', $this->settings_lib->item('site.title'), lang('us_account_reg_complete'));
                $emailView  = '_emails/activated';
                $message 	.= lang('us_account_active_login');

                $emailMsgData = array(
                    'title' => $site_title,
                    'link'  => site_url(),
                );
                break;

            case 1:
                // Email Activiation.
                // Run the account deactivate to assure everything is set correctly
                $activation_code    = $this->deactivate($user_id);
                // Create the link to activate membership
                $activate_link      = site_url('activate/' . $user_id);
                $subject            =  lang('us_email_subj_activate');
                $emailView          = '_emails/activate';
                $message            .= lang('us_check_activate_email');

                $emailMsgData = array(
                    'title' => $site_title,
                    'code'  => $activation_code,
                    'link'  => $activate_link
                );
                break;

            case 2:
                // Admin Activation
                $subject    =  lang('us_email_subj_pending');
                $emailView  = '_emails/pending';
                $message    .= lang('us_admin_approval_pending');

                $emailMsgData = array(
                    'title' => $site_title,
                );
                break;
        }//end switch

        $email_mess = $this->load->view($emailView, $emailMsgData, true);

        // Now send the email
        $this->load->library('emailer/emailer');
        $data = array(
            'to'		=> $this->find($user_id)->email,
            'subject'	=> $subject,
            'message'	=> $email_mess,
        );

        if ( ! $this->emailer->send($data)) {
            $message    .= lang('us_err_no_email') . $this->emailer->error;
            $error      = true;
        }

        return array('message' => $message, 'error' => $error);
    }

	//--------------------------------------------------------------------

}//end User_model