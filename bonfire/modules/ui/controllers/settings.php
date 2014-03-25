<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * UI Module
 *
 * Manages the keyboard shortcuts used in the Bonfire admin interface.
 *
 * @package    Bonfire\Modules\UI\Controllers\Settings
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/keyboard_shortcuts
 */
class Settings extends Admin_Controller
{
	/**
	 * Setup the required permissions and loads required classes
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Bonfire.UI.View');
		$this->auth->restrict('Bonfire.UI.Manage');
		$this->lang->load('ui');

		Template::set('toolbar_title', lang('ui_default_title'));
	}

	/**
	 * Display the available shortcuts and the details of the keys setup for
	 * these shortcut options.
	 *
	 * Manages adding, editing and deleting of the shortcut keys.
	 *
	 * @return void
	 */
	public function index()
	{
		if (isset($_POST['add_shortcut'])) {
			if ($this->add()) {
				Template::set_message(lang('ui_shortcut_success'), 'success');
			} else {
				Template::set_message(lang('ui_shortcut_add_error'), 'error');
			}
		} elseif (isset($_POST['remove_shortcut'])) {
			if ($this->remove()) {
				Template::set_message(lang('ui_shortcut_remove_success'), 'success');
			} else {
				Template::set_message(lang('ui_shortcut_remove_error'), 'error');
			}
		} elseif (isset($_POST['save'])) {
			if ($this->save_settings()) {
				Template::set_message(lang('ui_shortcut_save_success'), 'success');
			} else {
                Template::set_message(lang('ui_shortcut_save_error'), 'error');
            }
		}

		// Read available shortcuts from the application config
		Template::set('current', config_item('ui.current_shortcuts'));
		Template::set('settings', $this->settings_lib->find_all_by('module', 'core.ui'));
		Template::set('toolbar_title', lang('ui_shortcuts'));

		Template::render();
	}

	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Add a shortcut key for an option
	 *
	 * @return bool
	 */
	private function add()
	{
		$this->form_validation->set_rules('new_action', 'lang:ui_actions', 'required');
		$this->form_validation->set_rules('new_shortcut', 'lang:ui_shortcuts', 'required|callback__validate_shortcuts');
		if ($this->form_validation->run() === false) {
			return false;
		}

		$action   = $this->input->post('new_action');
		$shortcut = $this->input->post('new_shortcut');

		// Read available shortcuts from the application config
		$available_actions = config_item('ui.current_shortcuts');

		if (array_key_exists($action, $available_actions)) {
			return $this->save_settings(array($action => $shortcut));
		}

		return false;
	}

	/**
	 * Remove a shortcut key
	 *
	 * @return bool
	 */
	private function remove()
	{
		$this->form_validation->set_rules('remove_shortcut[]', 'lang:ui_actions', 'required|is_array');
		if ($this->form_validation->run() === false) {
			return false;
		}

		$action = key($this->input->post('remove_shortcut'));

		// Read the current settings
		$available_actions = $this->settings_lib->find_all_by('module', 'core.ui');

		if (array_key_exists($action, $available_actions)) {
			return $this->settings_lib->delete($action, 'core.ui');
		}

		return false;
	}

	/**
	 * Save multiple shortcut keys at the same time allowing the user to edit
	 * the settings
	 *
	 * @param array $settings Array of shortcuts
	 *
	 * @return bool
	 */
	private function save_settings($settings = array())
	{
		if (empty($settings)) {
			// Read available shortcuts from the application config
			$available_actions = config_item('ui.current_shortcuts');

            // The text inputs need set_value(), so an array can't be used the
            // way the remove buttons use them.
			// set_value("shortcut[$action]") is not supported
			foreach ($available_actions as $action => $shortcut) {
				if (isset($_POST["shortcut_$action"])) {
					$this->form_validation->set_rules("shortcut_$action", 'lang:ui_shortcuts', 'required|callback__validate_shortcuts');
					$settings[$action] = $this->input->post("shortcut_$action");
				}
			}

			if ($this->form_validation->run() === false) {
				return false;
			}
		}

		if (is_array($settings)) {
			foreach ($settings as $action => $shortcut) {
				$updated = $this->settings_lib->set($action, $shortcut, 'core.ui');
			}
		}

		log_activity($this->auth->user_id(), lang('bf_act_settings_saved') . ': ' . $this->input->ip_address(), 'ui');

		return $updated;
	}

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
		if (stristr($shortcut, " ") !== false) {
			$this->form_validation->set_message('_validate_shortcuts', lang('ui_shortcut_error'));

			return false;
		}

		return true;
	}
}
/* end /ui/controllers/settings.php */