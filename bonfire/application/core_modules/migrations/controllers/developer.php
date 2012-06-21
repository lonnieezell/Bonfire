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
 * Migrations Controller
 *
 * Manages the database migrations in Bonfire.
 *
 * @package    Bonfire
 * @subpackage Modules_Migrations
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Developer extends Admin_Controller
{


	/**
	 * Sets up the permissions and loads the language file
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Developer.View');
		$this->auth->restrict('Bonfire.Database.Manage');

		$this->config->load('migrations');
		$this->load->library('Migrations');
		$this->lang->load('migrations');

		Template::set_block('sub_nav', 'database/developer/_sub_nav');
	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Display the list og migrations available at core, application and module level
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post('submit') == lang('mig_migrate_button'))
		{
			$core = $this->input->post('core_only') ? '' : 'app_';

			if ($version = $this->input->post('migration'))
			{
				redirect(SITE_AREA .'/developer/migrations/migrate_to/'. $version .'/'. $core);
			}
		}

		Assets::add_js('jquery-ui-1.8.8.min');

		Template::set('installed_version', $this->migrations->get_schema_version('app_'));
		Template::set('latest_version', $this->migrations->get_latest_version('app_'));

		Template::set('core_installed_version', $this->migrations->get_schema_version('core'));
		Template::set('core_latest_version', $this->migrations->get_latest_version());

		Template::set('core_migrations', $this->migrations->get_available_versions());
		Template::set('app_migrations', $this->migrations->get_available_versions('app_'));

		Template::set('mod_migrations', $this->get_module_versions());

		Template::set('toolbar_title', 'Database Migrations');
		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Migrate the selected migration type to a specific migration number
	 *
	 * @access public
	 *
	 * @param int    $version The version number to migrate to
	 * @param string $type    The migration type (core, app_, MODULE_)
	 *
	 * @return void
	 */
	public function migrate_to($version=0, $type='')
	{
		if (!is_numeric($version))
		{
			$version = $this->uri->segment(5);
		}

		$result = $this->migrations->version($version, $type);

		if ($result !== FALSE)
		{
			if ($result === 0)
			{
				Template::set_message('Successfully uninstalled module\'s migrations.', 'success');

				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, 'Migrate Type: '. $type .' Uninstalled Version: ' . $version . ' from: ' . $this->input->ip_address(), 'migrations');

				redirect(SITE_AREA .'/developer/migrations');
			}
			else
			{
				Template::set_message('Successfully migrated database to version '. $result, 'success');

				// Log the activity
				$this->activity_model->log_activity($this->current_user->id, 'Migrate Type: '. $type .' to Version: ' . $version . ' from: ' . $this->input->ip_address(), 'migrations');

				redirect(SITE_AREA .'/developer/migrations');
			}
		}
		else
		{
			Template::set_message('There was an error migrating the database.', 'error');
		}//end if

		Template::set_message('No version to migrate to.', 'error');
		redirect(SITE_AREA .'/developer/migrations');

	}//end migrate_to()

	//--------------------------------------------------------------------

	/**
	 * Migrate a module to a particular version
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function migrate_module()
	{
		$module = $this->uri->segment(5);
		$file   = $this->input->post('version');

		$version = $file !== 'uninstall' ? (int)(substr($file, 0, 3)) : 0;

		$path = module_path($module, 'migrations');

		// Reset the migrations path for this run only.
		$this->migrations->set_path($path);

		// Do the migration
		$this->migrate_to($version, $module .'_');

		// Log the activity
		$this->activity_model->log_activity($this->current_user->id, 'Migrate module: ' . $module . ' Version: ' . $version . ' from: ' . $this->input->ip_address(), 'migrations');

	}//end migrate_module()

	//--------------------------------------------------------------------

	/**
	 * Get all versions available for the modules
	 *
	 * @access private
	 *
	 * @return array Array of available versions for each module
	 */
	private function get_module_versions()
	{
		$mod_versions = array();

		$modules = module_files(null, 'migrations');

		if ($modules === false)
		{
			return false;
		}

		foreach ($modules as $module => $migrations)
		{
			$mod_versions[$module] = array(
				'installed_version'	=> $this->migrations->get_schema_version($module .'_'),
				'latest_version'	=> $this->migrations->get_latest_version($module .'_'),
				'migrations'		=> $migrations['migrations']
			);
		}

		return $mod_versions;
	}//end get_module_versions()

	//--------------------------------------------------------------------
}//end class