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
 * Emailer Settings Context
 *
 * Allows the management of the Emailer. Assists in setting up the proper email
 * settings, as well as editing the template and viewing emails that are in the queue.
 *
 * @package    Bonfire
 * @subpackage Modules_Emailer
 * @category   Controllers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */
class Settings extends Admin_Controller
{

	/**
	 * Sets up the permissions and loads the language file
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Settings.View');
		$this->auth->restrict('Bonfire.Emailer.Manage');

		Template::set_block('sub_nav', 'settings/_sub_nav');

		$this->lang->load('emailer');

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Displays the emailer settings
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function index()
	{
		$this->load->library('form_validation');

		if ($this->input->post('submit'))
		{
			$this->form_validation->set_rules('sender_email', 'System Email', 'required|trim|valid_email|max_length[120]|xss_clean');
			$this->form_validation->set_rules('protocol', 'Email Server', 'trim|xss_clean');

			if ($this->input->post('protocol') == 'sendmail')
			{
				$this->form_validation->set_rules('mailpath', 'Sendmail Path', 'required|trim|xss_clean');
			}
			elseif ($this->input->post('protocol') == 'smtp')
			{
				$this->form_validation->set_rules('smtp_host', 'SMTP Server Address', 'required|trim|strip_tags|xss_clean');
				$this->form_validation->set_rules('smtp_user', 'SMTP Username', 'trim|strip_tags|xss_clean');
				$this->form_validation->set_rules('smtp_pass', 'SMTP Password', 'trim|strip_tags|matches_pattern[[A-Za-z0-9!@#\%$^&+=]{2,20}]');
				$this->form_validation->set_rules('smtp_port', 'SMTP Port', 'trim|strip_tags|numeric|xss_clean');
				$this->form_validation->set_rules('smtp_timeout', 'SMTP timeout', 'trim|strip_tags|numeric|xss_clean');
			}

			if ($this->form_validation->run() !== FALSE)
			{
				$data = array(
						array('name' => 'sender_email', 'value' => $this->input->post('sender_email')),
						array('name' => 'mailtype', 'value' => $this->input->post('mailtype')),
						array('name' => 'protocol', 'value' => strtolower($_POST['protocol'])),
						array('name' => 'mailpath', 'value' => $_POST['mailpath']),
						array('name' => 'smtp_host', 'value' => isset($_POST['smtp_host']) ? $_POST['smtp_host'] : ''),
						array('name' => 'smtp_user', 'value' => isset($_POST['smtp_user']) ? $_POST['smtp_user'] : ''),
						array('name' => 'smtp_pass', 'value' => isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : ''),
						array('name' => 'smtp_port', 'value' => isset($_POST['smtp_port']) ? $_POST['smtp_port'] : ''),
						array('name' => 'smtp_timeout', 'value' => isset($_POST['smtp_timeout']) ? $_POST['smtp_timeout'] : '5')
					 );

				$updated = FALSE;
				// save the settings to the db
				$updated = $this->settings_model->update_batch($data, 'name');

				if ($updated)
				{
					// Success, so reload the page, so they can see their settings
					Template::set_message('Email settings successfully saved.', 'success');
					redirect(SITE_AREA .'/settings/emailer');
				}
				else
				{
					Template::set_message('There was an error saving your settings.', 'error');
				}
			}
			else
			{
				Template::set_message('There was an error saving your settings.', 'error');
			}
		}//end if

		// Load our current settings
		$settings = $this->settings_model->select('name,value')->find_all_by('module', 'email');
		Template::set($settings);

		Assets::add_module_js('emailer', 'js/settings');

		Template::set('toolbar_title', 'Email Settings');

		Template::render();

	}//end index()

	//--------------------------------------------------------------------

	/**
	 * Display the screen to edit the email templagtes
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function template()
	{
		if ($this->input->post('submit'))
		{
			$header = $_POST['header'];
			$footer = $_POST['footer'];

			$this->load->helper('file');

			write_file(APPPATH .'core_modules/emailer/views/email/_header.php', $header, 'w+');
			write_file(APPPATH .'core_modules/emailer/views/email/_footer.php', $footer, 'w+');

			Template::set_message('Template successfully saved.', 'success');

			redirect(SITE_AREA .'/settings/emailer/template');
		}


		Assets::add_js(Template::theme_url('js/editors/ace/ace.js'));
		Assets::add_js(Template::theme_url('js/editors/ace/theme-monokai.js'));
		Assets::add_js(Template::theme_url('js/editors/ace/mode-html.js'));
		Assets::add_module_js('emailer', 'js/ace');

		Template::set('toolbar_title', lang('em_email_template'));

		Template::render();
	}//end template()

	//--------------------------------------------------------------------

	/**
	 * @access puublic
	 *
	 * @return void
	 */
	public function emails()
	{
		Template::set('toolbar_title', lang('em_email_contents'));
		Template::render();

	}//end emails()

	//--------------------------------------------------------------------

	/**
	 * Send a test email
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function test()
	{
		if ($_SERVER['REQUEST_METHOD'] != 'POST')
		{
			$this->security->csrf_show_error();
		}

		$this->load->library('emailer');
		$this->emailer->enable_debug(TRUE);

		$data = array(
				'to'		=> $this->input->post('email'),
				'subject'	=> lang('em_test_mail_subject'),
				'message'	=> lang('em_test_mail_body')
			 );

		$results = $this->emailer->send($data, FALSE);

		Template::set('results', $results);
		Template::render();

	}//end test()

	//--------------------------------------------------------------------

	/**
	 * Displays all of the emails currently in the queue to be sent.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function queue()
	{
		$this->load->library('pagination');

		$offset = $this->uri->segment(5);

		$this->load->model('Emailer_model', 'emailer_model', TRUE);

		// Deleting anything?
		if (isset($_POST['action_delete']))
		{
			$checked = $this->input->post('checked');
			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->emailer_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(sprintf(lang('em_delete_success'), count($checked)), 'success');
				}
				else
				{
					Template::set_message(lang('em_delete_failure') . $this->emailer_model->error, 'error');
				}
			}
			else
			{
				Template::set_message(lang('em_delete_error') . $this->emailer_model->error, 'error');
			}
		}
		elseif (isset($_POST['action_force_process']))
		{
			$this->load->library('emailer');

			// Use ob to catch output designed for CRON only
			ob_start();
			$this->emailer->process_queue();
			ob_end_clean();
		}
		elseif (isset($_POST['action_insert_test']))
		{
			$this->load->library('emailer');

			$data = array(
				'to'		=> $this->settings_lib->item('site.system_email'),
				'subject'	=> lang('em_test_mail_subject'),
				'message'	=> lang('em_test_mail_body')
			);

			$this->emailer->send($data, TRUE);
		}

		Template::set('emails', $this->emailer_model->limit($this->limit, $offset)->find_all());
		Template::set('total_in_queue', $this->emailer_model->count_by('date_sent IS NULL'));
		Template::set('total_sent', $this->emailer_model->count_by('date_sent IS NOT NULL'));

		$total_emails = $this->emailer_model->count_all();

		$this->pager['base_url'] = site_url(SITE_AREA .'/settings/emailer/queue');
		$this->pager['total_rows'] = $total_emails;
		$this->pager['per_page'] = $this->limit;
		$this->pager['uri_segment']	= 5;

		$this->pagination->initialize($this->pager);

		if ($debug_msg = $this->session->userdata('email_debug'))
		{
			Template::set('email_debug', $debug_msg);
			$this->session->unset_userdata('email_debug');
			unset($debug_msg);
		}

		Template::set('toolbar_title', lang('em_emailer_queue'));
		Template::render();

	}//end queue()

	//--------------------------------------------------------------------

	/**
	 * Displays a preview of the email as stored in the database.
	 *
	 * @access public
	 *
	 * @param int $id An INT with the ID of the email to preview from the queue.
	 *
	 * @return void
	 */
	public function preview($id=0)
	{
		$this->output->enable_profiler(FALSE);

		$this->load->model('emailer/emailer_model');

		if (!empty($id) && is_numeric($id))
		{
			$email = $this->emailer_model->find($id);

			if ($email)
			{
				Template::set('email', $email);

				Template::render('blank');
			}
		}

	}//end preview()

	/**
	 * Create a new email and send to selected recipents
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function create()
	{
		
		$this->load->model('users/user_model');
		$this->load->library('emailer');

		if ($this->input->post('submit'))
		{
			// validate subject, content and recipients
			$this->form_validation->set_rules('email_subject', 'Email Subject', 'required|xss_clean|trim|min_length[1]|max_length[255]');
			$this->form_validation->set_rules('email_content', 'Email Content', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('checked','Users', 'required');
			
			if ($this->form_validation->run() === FALSE)
			{
				Template::set('email_subject', $this->security->xss_clean($this->input->post('email_subject')));
				Template::set('email_content', $this->input->post('email_content'));
				Template::set('checked', $this->input->post('checked'));
			}
			else
			{
				$data = array (
					'subject'	=> $this->input->post('email_subject'),
					'message'	=> $this->input->post('email_content'),
				);

				$checked = $this->input->post('checked');
				$success_count = 0;
				if (is_array($checked) && count($checked))
				{
					$result = FALSE;
					foreach ($checked as $user_id)
					{
						//get the email from user_id
						$user = $this->user_model->find($user_id);
						if ($user != NULL){
							$data['to'] = $user->email;
							$result = $this->emailer->send($data,TRUE);
							if ($result) $success_count++;
						}
	
					}

					if ($result)
					{
						Template::set_message($success_count .' '. lang('em_create_email_success'), 'success');
						Template::redirect(SITE_AREA . '/settings/emailer/queue');
					}
					else
					{
						Template::set_message(lang('em_create_email_failure') . $this->user_model->error, 'error');
					}
				}
				else
				{
					Template::set_message(lang('em_create_email_error') . $this->user_model->error, 'error');
				}//end if
			}//end if
		}//end if

		$users = $this->user_model->find_all();
		Template::set('users', $users);
		Template::set('toolbar_title', lang('em_create_email'));
		Template::render();

	}//end create()

	//--------------------------------------------------------------------
}//end class

// End Admin class
/* End of file settings.php */
/* Location: ./application/core_modules/emailer/controllers/settings.php */
