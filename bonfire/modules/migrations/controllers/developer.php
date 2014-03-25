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
 * Manage the database migrations in Bonfire.
 *
 * @package    Bonfire\Modules\Migrations\Controllers\Developer
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/migrations
 */
class Developer extends Admin_Controller
{
	/**
	 * Setup the permissions and load the language file
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Developer.View');
		$this->auth->restrict('Bonfire.Database.Manage');

        // Load the database lang file because Migrations is on the database subnav
        $this->lang->load('database/database');
		$this->lang->load('migrations');

		$this->load->library('Migrations');

        Assets::add_module_css('migrations', 'migrations');

		Template::set_block('sub_nav', 'database/developer/_sub_nav');
	}

	/**
	 * Display the list of migrations available at core, application, and module
	 * level
	 *
	 * @return void
	 */
	public function index()
	{
		if (isset($_POST['migrate'])) {
			$core = $this->input->post('core_only') ? '' : 'app_';

			if ($version = $this->input->post('migration')) {
				$this->migrate_to($version, $core);
			}
		}

		Template::set('installed_version', $this->migrations->get_schema_version('app_'));
		Template::set('latest_version', $this->migrations->get_latest_version('app_'));

		Template::set('core_installed_version', $this->migrations->get_schema_version('core'));
		Template::set('core_latest_version', $this->migrations->get_latest_version());

		Template::set('core_migrations', $this->migrations->get_available_versions());
		Template::set('app_migrations', $this->migrations->get_available_versions('app_'));
		Template::set('mod_migrations', $this->get_module_versions());

		Template::set('toolbar_title', lang('migrations_title_index'));
		Template::render();
	}

	/**
	 * Migrate the selected migration type to a specific migration number
	 *
	 * @param int    $version The version number to migrate to
	 * @param string $type    The migration type (core, app_, MODULE_)
	 *
	 * @return void
	 */
	private function migrate_to($version, $type)
	{
		$result = $this->migrations->version($version, $type);
		if ($result !== false && strlen($this->migrations->error) == 0) {
			if ($result === 0) {
				Template::set_message(lang('migrations_uninstall_success'), 'success');
				log_activity(
                    $this->auth->user_id(),
                    sprintf(lang('migrations_act_uninstall_success'), $type, $version, $this->input->ip_address()),
                    'migrations'
                );
			} else {
				Template::set_message(sprintf(lang('migrations_migrate_success'), $result), 'success');
				log_activity(
                    $this->auth->user_id(),
                    sprintf(lang('migrations_act_migrate_success'), $type, $version, $this->input->ip_address()),
                    'migrations'
                );
			}
		} else {
			log_message(lang('migrations_migrate_error') . "\n" . $this->migrations->error, 'error');
			Template::set_message(lang('migrations_migrate_error') . '<br />' . $this->migrations->error, 'error');
		}
	}

	/**
	 * Migrate a module to a particular version
	 *
	 * @return void
	 */
	public function migrate_module($module = '')
	{
		if (isset($_POST['migrate'])) {
			$file = $this->input->post('version');
			if (empty($file)) {
				Template::set_message(lang('migrations_module_none'), 'info');

				redirect(SITE_AREA . '/developer/migrations');
			}

			$version = $file !== 'uninstall' ? (int) substr($file, 0, 3) : 0;

			// Do the migration
			$this->migrate_to($version, "{$module}_");
			log_activity(
                $this->auth->user_id(),
                sprintf(lang('migrations_act_module'), $module, $version, $this->input->ip_address()),
                'migrations'
            );
		}

		redirect(SITE_AREA . '/developer/migrations');
	}

	/**
	 * Get all versions available for the modules
	 *
	 * @return array Array of available versions for each module
	 */
	private function get_module_versions()
	{
		$modules = Modules::files(null, 'migrations');
		if ($modules === false) {
			return false;
		}

        // Sort modules by key (module directory name)
		ksort($modules);

		// Sort module migrations in reverse order
		foreach ($modules as &$mod) {
			if ( ! array_key_exists('migrations', $mod)) {
				continue;
			}

			arsort($mod['migrations']);
		}

		$mod_versions = array();
		foreach ($modules as $module => $migrations) {
			$mod_versions[$module] = array(
				'installed_version'	=> $this->migrations->get_schema_version("{$module}_"),
				'latest_version'	=> $this->migrations->get_latest_version("{$module}_"),
				'migrations'		=> $migrations['migrations'],
			);
		}

		return $mod_versions;
	}
}
/* end /migrations/controllers/developer.php */