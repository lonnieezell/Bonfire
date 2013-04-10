<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
			$checked = $this->input->post('checked');

			switch($this->input->post('action'))
			{
				case 'backup':
					$hide_form = $this->backup($checked);
					break;
				case 'repair':
					$this->repair($checked);
					break;
				case 'optimize':
					$this->optimize();
					break;
				case 'drop':
					$hide_form = $this->drop($checked);
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
			Template::set('toolbar_title', 'Database Maintenance');
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
			Template::set_message('No table name was provided.', 'error');
			redirect(SITE_AREA .'/developer/database');
		}

		$query = $this->db->get($table);

		if ($query->num_rows())
		{
			Template::set('rows', $query->result());
		}

		Template::set('query', $this->db->last_query());

		Template::set('toolbar_title', lang('db_browse') .': '. $table);
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
		if (isset($_POST['delete']))
		{
			$checked = $this->input->post('checked');

			// Make sure we have something to delete
			if (is_array($checked) && count($checked) > 0)
			{
				// Delete the files.

				foreach ($checked as $file)
				{
					unlink($this->backup_folder . $file) or die("can't delete file");
				}

				// Tell them it was good.
				Template::set_message(count($checked) . ' backup files were deleted.', 'success');
			}
			else
			{
				Template::set_message(lang('db_backup_delete_none'), 'error');
			}
		}

		// Get a list of existing backup files
		$this->load->helper('file');
		Template::set('backups', get_dir_file_info($this->backup_folder));

		Template::set('toolbar_title', 'Database Backups');
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

			Template::set('toolbar_title', 'Create New Backup');
			return TRUE;
		}
		else if (isset($_POST['backup']))
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules('file_name', 'lang:db_filename', 'required|trim|max_length[220]');
			$this->form_validation->set_rules('drop_tables', 'lang:db_drop_tables', 'required|trim|one_of[0,1]');
			$this->form_validation->set_rules('add_inserts', 'lang:db_add_inserts', 'required|trim|one_of[0,1]');
			$this->form_validation->set_rules('file_type', 'lang:db_compress_type', 'required|trim|one_of[txt,gzip,zip]');
			$this->form_validation->set_rules('tables', 'lang:db_tables', 'required|is_array');

			if ($this->form_validation->run() !== FALSE)
			{
				// Do the backup.
				$this->load->dbutil();

				$add_drop = ($_POST['drop_tables'] == '1');
				$add_insert = ($_POST['add_inserts'] == '1');

				$extension = $_POST['file_type'];
				if ($extension == 'gzip')
				{
					$extension = 'gz';
				}
				$basename = $_POST['file_name'] . '.' . $extension;
				$filename = $this->backup_folder . $basename;

				$prefs = array(
								'tables' 		=> $_POST['tables'],
								'format'		=> $_POST['file_type'],
								'filename'		=> $filename,
								'add_drop'		=> $add_drop,
								'add_insert'	=> $add_insert
							);
				$backup =& $this->dbutil->backup($prefs);

				$this->load->helper('file');
				write_file($filename, $backup);

				if (file_exists($filename))
				{
					Template::set_message('Backup file successfully saved. It can be found at <a href="'. html_escape(site_url(SITE_AREA . '/developer/database/get_backup/' .  $basename)) .'">'. html_escape($filename) .'</a>.', 'success');
				}
				else
				{
					Template::set_message('There was a problem saving the backup file.', 'error');
				}

				redirect(SITE_AREA .'/developer/database');
			}
			else
			{
				Template::set('tables', $this->input->post('tables'));
				Template::set_message('There was a problem saving the backup file.', 'error');
			}
		}//end if

		Template::set('toolbar_title', 'Create New Backup');
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
		// CSRF could try `../../dev/temperamental-special-file` or `COM1`
		// and we'd really rather exclude the possibility of that happening anyway.
		if (preg_match('{[\\/]}', $filename) || ! fnmatch('*.*', $filename))
		{
			$this->security->csrf_show_error();
		}

		$this->load->helper('download');

		if (file_exists($this->backup_folder . $filename))
		{
			$data = file_get_contents($this->backup_folder . $filename);
			force_download($filename, $data);

			redirect(SITE_AREA .'/database/backups');
		}
		else 	// File doesn't exist
		{
			Template::set_message($filename . ' could not be found.', 'error');
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

		if (isset($_POST['restore']) && !empty($filename))
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
								$s .= "<strong style='color: green;'>Successfull Query</strong>: <span class='small'>$templine</span><br/>";

								// so reset our templine so we can start a new one
								$templine = '';
							}
							else {
								$s .= "<strong style='color:red'>Unsuccessful Query:</strong> $templine<br/><br/>";
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
				Template::set_message('Could not read the file: /application/db/backups/' . $filename . '.', 'error');
				redirect(SITE_AREA .'/developer/database/backups');
			}
		}//end if

		// Show our verification screen.
		Template::set_view('developer/restore');
		Template::set('toolbar_title', 'Database Restore');
		Template::render();

	}//end restore()

	//---------------------------------------------------------------

	/**
	 * Repairs database tables.
	 *
	 * @access private
	 *
	 * @param array $tables Array of tables to repair
	 *
	 * @return mixed
	 */
	private function repair($tables=null)
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

			Template::set_message(($count - $failed) .' of '. $count .' tables were successfully repaired.', $quality);
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
	 * @access private
	 *
	 * @return void
	 */
	private function optimize()
	{
		$this->load->dbutil();

		$result = $this->dbutil->optimize_database();

		if ($result == FALSE)
		{
			$this->session->set_flashdata('message', 'alert::Unable to optimize the database.');
		}
		else
		{
			$this->session->set_flashdata('message', 'success::The database was successfully optimized.');
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
			// Actually delete the tables....
			$this->load->dbforge();

			foreach ($_POST['tables'] as $table)
			{
				// dbforge automatically adds the prefix, so we need to remove it.
				$prefix = $this->db->dbprefix;

				if (strncmp($table, $prefix, strlen($prefix)) === 0)
				{
					$table = substr($table, strlen($prefix));
					@$this->dbforge->drop_table($table);
				}
			}

			$grammar = count($_POST['tables'] == 1) ? ' table' : ' tables';
			Template::set_message(count($_POST['tables']) .$grammar.' successfully dropped.', 'success');
			redirect(SITE_AREA .'/developer/database');
		}
		else
		{
			Template::set_message(lang('db_drop_none'), 'error');
			redirect(SITE_AREA .'/developer/database');
		}

	}//end drop()

}//end class
