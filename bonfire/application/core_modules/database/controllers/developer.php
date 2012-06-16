<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 * Database Tools controller
 *
 * Various tools to manage the Database tables.
 *
 * @package    Bonfire
 * @subpackage Modules_Database
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Developer extends Admin_Controller
{

	/**
	 * Path to the backups
	 *
	 * @access private
	 *
	 * @var string
	 */
	private $backup_folder	= 'db/backups/';

	//---------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Bonfire.Database.Manage');

		$this->backup_folder = APPPATH . $this->backup_folder;

		$this->lang->load('database');

		Template::set_block('sub_nav', 'developer/_sub_nav');
		Template::set('sidebar', 'admin/sidebar');

	}//end __construct()

	//---------------------------------------------------------------

	/**
	 * Displays a list of tables in the database.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		$hide_form = FALSE;

		// Are we performing an action?
		if (isset($_POST['action']))
		{
			// Checked the checked() variable
			$_POST['checked'] = isset($_POST['checked']) ? $_POST['checked'] : '';

			switch(strtolower($_POST['action']))
			{
				case 'backup':
					$hide_form = $this->backup($_POST['checked']);
					break;
				case 'repair':
					$this->repair($_POST['checked']);
					break;
				case 'optimize':
					$this->optimize();
					break;
				case 'drop':
					$hide_form = $this->drop($_POST['checked']);
					break;
			}
		}

		if (!$hide_form)
		{
			$this->load->helper('number');
			Template::set('tables', $this->db->query('SHOW TABLE STATUS')->result());
		}

		if (!Template::get('toolbar_title'))
		{
			Template::set('toolbar_title', lang('db_database_maintenance'));
		}

		Template::render();

	}//end index()

	//---------------------------------------------------------------

	/**
	 * Browse the DB tables
	 *
	 * @access public
	 *
	 * @param string $table Name of the table to browse
	 *
	 * @return void
	 */
	public function browse($table = '')
	{
		if (empty($table))
		{
			Template::set_message(lang('db_browse_none'), 'error');
			redirect(SITE_AREA .'/developer/database');
		}

		$query = $this->db->get($table);

		if ($query->num_rows())
		{
			Template::set('rows', $query->result());
		}

		Template::set('query', $this->db->last_query());

		Template::set('toolbar_title', lang('db_table_browse_heading') . ' ' . $table);
		Template::render();

	}//end browse()

	//--------------------------------------------------------------------

	/**
	 * List the existing backups
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function backups()
	{
		// Make sure we have something to delete
		if (isset($_POST['checked']) && is_array($_POST['checked']) && count($_POST['checked']) > 0)
		{
			// Delete the files.
			$count = count($_POST['checked']);

			$this->load->helper('file');

			foreach ($_POST['checked'] as $file)
			{
				// Make sure the file is closed
				$fh = fopen($this->backup_folder . $file, 'w') or die("can't open file");
				fclose($fh);

				// Actually delete it.
				unlink($this->backup_folder . $file);
			}

			// Tell them it was good.
			if ($count == 1)
			{
				Template::set_message(lang('db_backup_file_delete_success'), 'success');
			}
			else
			{
				Template::set_message(sprintf(lang('db_backup_files_delete_success'), $count), 'success');
			}
		}
		else if ($this->input->post() && !isset($_POST['checked']))
		{
			Template::set_message(lang('db_backup_delete_none'), 'error');
		}

		// Get a list of existing backup files
		$this->load->helper('file');
		Template::set('backups', get_dir_file_info($this->backup_folder));

		Template::set('toolbar_title', lang('db_database_backups'));
		Template::render();
	}//end backups()

	//---------------------------------------------------------------

	/**
	 * Performs the actual backup.
	 *
	 * @access public
	 *
	 * @param array $tables Array of tables
	 *
	 * @return bool
	 */
	public function backup($tables=null)
	{
		// Show the form
		if (!empty($tables) && is_array($tables) && count($tables) > 0)
		{
			Template::set_view('developer/backup');
			Template::set('tables', $tables);
			Template::set('file', ENVIRONMENT .'_backup_' . date('Y-m-j_His'));

			Template::set('toolbar_title', lang('db_backup_create_heading'));
			return TRUE;
		}
		else if (isset($_POST['submit']))
		{
			$this->load->library('form_validation');

			$yes_no = lang('bf_no').','.lang('bf_yes');

			$this->form_validation->set_rules('file_name', 'lang:db_filename', 'required|trim|max_length[220]|xss_clean');
			$this->form_validation->set_rules('drop_tables', 'lang:db_drop_tables', 'trim|strip_tags|numeric|xss_clean');
			$this->form_validation->set_rules('add_inserts', 'lang:db_add_inserts', 'trim|strip_tags|numeric|xss_clean');
			$this->form_validation->set_rules('file_type', 'lang:db_compress_type', 'required|trim|one_of[txt,gzip,zip]|xss_clean');
			$this->form_validation->set_rules('tables', 'lang:db_tables', 'required|is_array|xss_clean');

			if ($this->form_validation->run() !== FALSE)
			{
				// Do the backup.
				$this->load->dbutil();

				$filename = $this->backup_folder . $_POST['file_name'] . '.' . $_POST['file_type'];

				$prefs = array(
								'tables' 		=> $_POST['tables'],
								'format'		=> $_POST['file_type'],
								'filename'		=> $filename,
								'add_drop'		=> isset($_POST['drop_tables']),
								'add_insert'	=> isset($_POST['add_inserts'])
							);
				$backup =& $this->dbutil->backup($prefs);

				$this->load->helper('file');
				write_file($filename, $backup);

				if (file_exists($filename))
				{
					Template::set_message(sprintf(lang('db_backup_file_save_success'), '<a href="'. site_url() . $filename .'">'. $filename .'</a>'), 'success');
				}
				else
				{
					Template::set_message(lang('db_backup_file_save_failure'), 'error');
				}

				redirect(SITE_AREA .'/developer/database');
			}
			else
			{
				Template::set('tables', $this->input->post('tables'));
				Template::set_message(lang('db_backup_file_save_failure'), 'error');
			}
		}//end if

		Template::set('toolbar_title', lang('db_backup_create_heading'));
		Template::render();

	}//end backup()

	//---------------------------------------------------------------

	/**
	 * Do a force download on a backup file.
	 *
	 * @access public
	 *
	 * @param string $filename Name of the file to download
	 *
	 * @return void
	 */
	public function get_backup($filename=null)
	{
		$this->load->helper('download');

		if (file_exists($this->backup_folder . $filename))
		{
			$data = file_get_contents($this->backup_folder . $filename);
			force_download($filename, $data);

			redirect(SITE_AREA .'/database/backups');
		}
		else 	// File doesn't exist
		{
			Template::set_message(sprintf(lang('db_backup_file_not_found'), $filename), 'error');
			redirect(SITE_AREA .'/developer/database/backups');
		}

	}//end get_backup()

	//---------------------------------------------------------------

	/**
	 * Perform a restore from a database backup.
	 *
	 * @access public
	 *
	 * @param string $filename Name of the file to restore
	 *
	 * @return void
	 */
	public function restore($filename=null)
	{
		Template::set('filename', $filename);

		if (!empty($filename) && isset($_POST['submit']))
		{
			// Load the file from disk.
			$this->load->helper('file');
			$file = file($this->backup_folder . $filename);

			$s = '';
			$templine = '';

			if (!empty($file))
			{
				// Loop through each line
				foreach ($file as $line)
				{
					// Skip it if it's a comment
					if (substr(trim($line), 0, 1) != '#' || substr($line, 0 ,1) != '')
					{
						// Add this line to the current segment
						$templine .= $line;
						// If it has a semicolon at the end, it's the end of a query.
						if (substr(trim($line), -1, 1) == ';')
						{
							// Perform the query...
							if($this->db->query($templine))
							{
								// Query Success
								$s .= "<strong style='color: green;'>" . lang('db_successful_query') . "</strong>: <span class='small'>$templine</span><br/>";

								// so reset our templine so we can start a new one
								$templine = '';
							}
							else {
								$s .= "<strong style='color:red'>" . lang('db_unsuccessful_query') . "</strong> $templine<br/><br/>";
								$templine = '';
							}
						}
					}
				}//end foreach

				// Tell the results
				Template::set('results', $s);
			}
			else
			{
				// Couldn't read from file.
				Template::set_message(sprintf(lang('db_backup_file_read_failure'), '/application/db/backups/' . $filename), 'error');
				redirect(SITE_AREA .'/developer/database/backups');
			}
		}//end if

		Template::set_view('developer/restore');
		Template::set('toolbar_title', lang('db_database_restore_heading'));
		Template::render();

	}//end restore()

	//---------------------------------------------------------------

	/**
	 * Repairs database tables.
	 *
	 * @access public
	 *
	 * @param array $tables Array of tables to repair
	 *
	 * @return mixed
	 */
	public function repair($tables=null)
	{
		if (is_array($tables))
		{
			$count = count($tables);
			$failed = 0;

			$this->load->dbutil();

			foreach ($tables as $table)
			{
				if (!$this->dbutil->repair_table($table))
				{
					$failed += 1;
				}
			}

			// Tell them the results
			$quality = $failed == 0 ? 'success' : 'alert';

			Template::set_message(sprintf(lang('db_table_repair_success'), ($count - $failed), $count), $quality);
			redirect(SITE_AREA .'/developer/database');
		}
		else
		{
			Template::set_message(lang('db_repair_none'), 'error');
			redirect(SITE_AREA .'/developer/database');
		}//end if

		return;

	}//end repair()

	//---------------------------------------------------------------

	/**
	 * Optimize the entire database
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function optimize()
	{
		$this->load->dbutil();

		$result = $this->dbutil->optimize_database();

		if ($result == FALSE)
		{
			Template::set_message(lang('db_database_optimize_failure'), 'alert');
		}
		else
		{
			Template::set_message(lang('db_database_optimize_success'), 'success');
		}

		redirect(SITE_AREA .'/developer/database', 'location');

	}//end optimize()

	//---------------------------------------------------------------

	/**
	 * Drop database tables.
	 *
	 * @access public
	 *
	 * @param array $tables Array of table to drop
	 *
	 * @return bool
	 */
	public function drop($tables=null)
	{
		if (!empty($tables))
		{
			// Show our verification screen.
			Template::set('tables', $tables);

			Template::set_view('developer/drop');
			return TRUE;
		}
		else if (isset($_POST['tables']) && is_array($_POST['tables']))
		{
			// Actually delete the files....
			$this->load->dbforge();

			foreach ($_POST['tables'] as $table)
			{
				@$this->dbforge->drop_table($table);
			}

			if (count($_POST['tables']) == 1)
			{
				Template::set_message(lang('db_table_drop_success'), 'success');
			}
			else
			{
				Template::set_message(sprintf(lang('db_tables_drop_success'), $count), 'success');
			}
			redirect(SITE_AREA .'/developer/database');
		}
		else
		{
			Template::set_message(lang('db_drop_none'), 'error');
			redirect(SITE_AREA .'/developer/database');
		}

	}//end drop()

	//---------------------------------------------------------------

	//--------------------------------------------------------------------
	// !MIGRATIONS
	//--------------------------------------------------------------------

	/**
	 * Display migrations
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function migrations()
	{
		$this->load->library('Migrations');

		Template::set('installed_version', $this->migrations->get_schema_version());
		Template::set('latest_version', $this->migrations->get_latest_version());

		Template::render();

	}//end migrations()

	//--------------------------------------------------------------------

	/**
	 * Perform a migration
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function migrate()
	{
		$this->load->library('Migrations');

		if ($this->migrations->install())
		{
			Template::set_message(lang('db_database_update_success'), 'success');
		} else
		{
			Template::set_message(lang('db_database_update_failure'). $this->migrations->error, 'error');
		}

		redirect(SITE_AREA .'/database/migrations');

	}//end migrate()

	//--------------------------------------------------------------------


}//end class
