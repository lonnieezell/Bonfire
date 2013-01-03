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
 * UI Module
 *
 * Manages the keyboard shortcuts used in the Bonfire admin interface.
 *
 * @package    Bonfire
 * @subpackage Modules_Ui
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Settings extends Admin_Controller
{

	//--------------------------------------------------------------------

	/**
	 * Setups the required permissions and loads required classes
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Bonfire.UI.View');
		$this->auth->restrict('Bonfire.UI.Manage');
		$this->lang->load('ui');

		Template::set('toolbar_title', 'UI Settings');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Displays the available shortcuts and the details of the keys setup
	 * for these shortcut options.  Manages adding, editing and deleting of
	 * the shortcut keys.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post('add_shortcut'))
		{
			if ($this->add())
			{
				Template::set_message(lang('ui_shortcut_success'), 'success');
			}
			else
			{
				Template::set_message(lang('ui_shortcut_add_error'), 'error');
			}
		}
		elseif ($this->input->post('remove_shortcut'))
		{
			if ($this->remove())
			{
				Template::set_message(lang('ui_shortcut_remove_success'), 'success');
			}
			else
			{
				Template::set_message(lang('ui_shortcut_remove_error'), 'error');
			}
		}
		elseif ($this->input->post('save'))
		{
			if ($this->save_settings())
			{
				Template::set_message(lang('ui_shortcut_save_success'), 'success');
				redirect(uri_string());
			}
			else
			{
				Template::set_message(lang('ui_shortcut_save_error'), 'error');
			}
		}//end if

		// Read available shortcuts from the application config
		Template::set('current', config_item('ui.current_shortcuts'));

		$settings = $this->settings_lib->find_all_by('module', 'core.ui');
		Template::set('settings', $settings);

		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------


	/**
	 * Add a shortcut key for an option
	 *
	 * @access private
	 *
	 * @return bool
	 */
	private function add()
	{

		$this->form_validation->set_rules('new_action', lang('ui_actions'), 'required');
		$this->form_validation->set_rules('new_shortcut', lang('ui_shortcuts'), 'required|callback__validate_shortcuts');

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		$action   = $this->input->post('new_action');
		$shortcut = $this->input->post('new_shortcut');

		// Read available shortcuts from the application config
		$available_actions = config_item('ui.current_shortcuts');

		if (array_key_exists($action, $available_actions))
		{
			return $this->save_settings(array($action => $shortcut));
		}

		return FALSE;

	}//end add()

	//--------------------------------------------------------------------


	/**
	 * Remove a shortcut key
	 *
	 * @access private
	 *
	 * @return bool
	 */
	private function remove()
	{
		$this->form_validation->set_rules('remove_shortcut[]', lang('ui_actions'), 'required|is_array');

		if ($this->form_validation->run() === FALSE)
		{
			return FALSE;
		}

		$action   = key($this->input->post('remove_shortcut'));

		// Read our current settings
		$available_actions = $this->settings_lib->find_all_by('module', 'core.ui');

		if (array_key_exists($action, $available_actions))
		{
			return $this->settings_lib->delete($action, 'core.ui');
		}

		return FALSE;

	}//end remove()

	//--------------------------------------------------------------------

	/**
	 * Save multiple shortcut keys at the same time allowing the user to
	 * edit the settings
	 *
	 * @param array $settings Array of shortcuts
	 *
	 * @return bool
	 */
	private function save_settings($settings = array())
	{
		if (empty($settings))
		{
			// Read available shortcuts from the application config
			$available_actions = config_item('ui.current_shortcuts');

			// We can't use an array like the remove buttons do
			// because the text inputs need set_value().
			// set_value("shortcut[$action]") is not supported
			foreach ($available_actions as $action => $shortcut)
			{
				if ($this->input->post("shortcut_$action"))
				{
					$this->form_validation->set_rules("shortcut_$action", lang('ui_shortcuts'), 'required|callback__validate_shortcuts');

					$settings[$action] = $this->input->post("shortcut_$action");
				}
			}

			if ($this->form_validation->run() === FALSE)
			{
				return FALSE;
			}

		}//end if

		if (is_array($settings))
		{
			foreach($settings as $action => $shortcut)
			{
				$updated = $this->settings_lib->set($action, $shortcut, 'core.ui');
			}
		}

		// Log the activity
		$this->activity_model->log_activity($this->current_user->id, lang('bf_act_settings_saved').': ' . $this->input->ip_address(), 'ui');

		return $updated;

	}//end save_settings()

	//--------------------------------------------------------------------

	/**
	 * Callback method to make sure that the shortcut ksys are valid
	 *
	 * @param string $shortcut The shortcut key
	 *
	 * @return bool
	 */
	public function _validate_shortcuts($shortcut)
	{
		// Make sure that the shortcuts don't have spaces

		if (stristr($shortcut, " ") !== FALSE)
		{
			$this->form_validation->set_message('_validate_shortcuts', lang('ui_shortcut_error'));
			return FALSE;
		}

		return TRUE;

	}//end _validate_shortcuts()

}//end Settings
