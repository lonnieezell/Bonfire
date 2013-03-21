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
 * Settings Module
 *
 * Allows the user to management the preferences for the site.
 *
 * @package    Bonfire
 * @subpackage Modules_Settings
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Settings extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Sets up the require permissions and loads required classes
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		// restrict access - View and Manage
		$this->auth->restrict('Bonfire.Settings.View');
		$this->auth->restrict('Bonfire.Settings.Manage');

		Template::set('toolbar_title', 'Site Settings');

		$this->load->helper('config_file');
		$this->lang->load('settings');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Displays a form with various site setings including site name and
	 * registration settings
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		$this->load->config('extended_settings');
		$extended_settings = config_item('extended_settings_fields');

		if ($this->input->post('save'))
		{
			if ($this->save_settings($extended_settings))
			{
				Template::set_message(lang('settings_saved_success'), 'success');
				redirect(SITE_AREA .'/settings');
			}
			else
			{
				Template::set_message(lang('settings_error_success'), 'error');
			}
		}

		// Read our current settings
		$settings = $this->settings_lib->find_all();
		Template::set('settings', $settings);
		Template::set('extended_settings', $extended_settings);

		// Get the possible languages
		$this->load->helper('translate/languages');
		Template::set('languages', list_languages());
		Template::set('selected_languages', unserialize($settings['site.languages']));

		Assets::add_module_js('settings', 'js/settings.js');

		Template::set_view('settings/settings/index');
		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Performs the form validation and saves the settings to the database
	 *
	 * @access	private
	 *
	 * @param	array	$extended_settings	An optional array of settings from the extended_settings config file
	 *
	 * @return	bool
	 */
	private function save_settings($extended_settings=array())
	{
		$this->form_validation->set_rules('title', 'lang:bf_site_name', 'required|trim');
		$this->form_validation->set_rules('system_email', 'lang:bf_site_email', 'required|trim|valid_email');
		$this->form_validation->set_rules('list_limit','Items <em>p.p.</em>', 'required|trim|numeric');
		$this->form_validation->set_rules('password_min_length','lang:bf_password_length', 'required|trim|numeric');
		$this->form_validation->set_rules('password_force_numbers', 'lang:bf_password_force_numbers', 'trim|numeric');
		$this->form_validation->set_rules('password_force_symbols', 'lang:bf_password_force_symbols', 'trim|numeric');
		$this->form_validation->set_rules('password_force_mixed_case', 'lang:bf_password_force_mixed_case', 'trim|numeric');
		$this->form_validation->set_rules('password_show_labels', 'lang:bf_password_show_labels', 'trim|numeric');
		$this->form_validation->set_rules('languages[]', 'lang:bf_language', 'required|trim|is_array');

		$extended_data = array();
		foreach ($extended_settings as $field)
		{
			if ( ! isset($field['permission'])
				|| empty($field['permission'])
				|| $field['permission'] === FALSE
				|| isset($field['permission']) && ! empty($field['permission']) && has_permission($field['permission'])
			)
			{
				$this->form_validation->set_rules($field['name'], $field['label'], $field['rules']);

				$extended_data['ext.' . $field['name']] = $this->input->post($field['name']);
			}

		}

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		$data = array(
			array('name' => 'site.title', 'value' => $this->input->post('title')),
			array('name' => 'site.system_email', 'value' => $this->input->post('system_email')),
			array('name' => 'site.status', 'value' => $this->input->post('status')),
			array('name' => 'site.list_limit', 'value' => $this->input->post('list_limit')),

			array('name' => 'auth.allow_register', 'value' => isset($_POST['allow_register']) ? 1 : 0),
			array('name' => 'auth.user_activation_method', 'value' => isset($_POST['user_activation_method']) ? $_POST['user_activation_method'] : 0),
			array('name' => 'auth.login_type', 'value' => $this->input->post('login_type')),
			array('name' => 'auth.use_usernames', 'value' => isset($_POST['use_usernames']) ? $this->input->post('use_usernames') : 0),
			array('name' => 'auth.allow_remember', 'value' => isset($_POST['allow_remember']) ? 1 : 0),
			array('name' => 'auth.remember_length', 'value' => (int)$this->input->post('remember_length')),
			array('name' => 'auth.use_extended_profile', 'value' => isset($_POST['use_ext_profile']) ? 1 : 0),
			array('name' => 'auth.allow_name_change', 'value' => $this->input->post('allow_name_change') ? 1 : 0),
			array('name' => 'auth.name_change_frequency', 'value' => $this->input->post('name_change_frequency')),
			array('name' => 'auth.name_change_limit', 'value' => $this->input->post('name_change_limit')),
			array('name' => 'auth.password_min_length', 'value' => $this->input->post('password_min_length')),
			array('name' => 'auth.password_force_numbers', 'value' => $this->input->post('password_force_numbers')),
			array('name' => 'auth.password_force_symbols', 'value' => $this->input->post('password_force_symbols')),
			array('name' => 'auth.password_force_mixed_case', 'value' => $this->input->post('password_force_mixed_case')),
			array('name' => 'auth.password_show_labels', 'value' => $this->input->post('password_show_labels') ? 1 : 0),

			array('name' => 'updates.do_check', 'value' => isset($_POST['do_check']) ? 1 : 0),
			array('name' => 'updates.bleeding_edge', 'value' => isset($_POST['bleeding_edge']) ? 1 : 0),
			array('name' => 'site.show_profiler', 'value' => isset($_POST['show_profiler']) ? 1 : 0),
			array('name' => 'site.show_front_profiler', 'value' => isset($_POST['show_front_profiler']) ? 1 : 0),
			array('name' => 'site.languages', 'value' => $this->input->post('languages') != '' ? serialize($this->input->post('languages')) : ''),
			array('name' => 'password_iterations', 'value' => $this->input->post('password_iterations')),

		);

		//destroy the saved update message in case they changed update preferences.
		if ($this->cache->get('update_message'))
		{
			$this->cache->delete('update_message');
		}

		// Log the activity
		$this->activity_model->log_activity($this->current_user->id, lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'core');

		// save the settings to the DB
		$updated = $this->settings_model->update_batch($data, 'name');

		// if the update was successful and we have extended settings to save,
		if ($updated && ! empty($extended_data))
		{
			// go ahead and save them
			$updated = $this->save_extended_settings($extended_data);
		}

		return $updated;

	}//end save_settings()

	//--------------------------------------------------------------------

	/**
	 * Save the extended settings
	 *
	 * @access	private
	 *
	 * @param	array	$extended_data	An array of settings to save
	 *
	 * @return	mixed/bool				TRUE or an inserted id if all settings saved successfully, else FALSE
	 */
	private function save_extended_settings($extended_data)
	{
		if ( ! is_array($extended_data) || empty($extended_data) || ! count($extended_data))
		{
			return FALSE;
		}

		$setting = FALSE;
		foreach ($extended_data as $key => $value)
		{
			$setting = $this->settings_lib->set($key, $value);
			if ($setting === FALSE)
			{
				return FALSE;
			}
		}

		return $setting;
	}

	//--------------------------------------------------------------------
}//end Settings()
