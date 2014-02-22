<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
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
 * Emailer Settings Context
 *
 * Allows the management of the Emailer. Assists in setting up the proper email
 * settings, as well as editing the template and viewing emails in the queue.
 *
 * @package    Bonfire\Modules\Emailer\Controllers\Settings
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides
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

		$this->lang->load('emailer');

		Assets::add_module_js('emailer', 'js/settings');

		Template::set_block('sub_nav', 'settings/_sub_nav');
	}

	/**
	 * Displays the emailer settings
	 *
	 * @return void
	 */
	public function index()
	{
		$this->load->library('form_validation');

		if (isset($_POST['save'])) {
			$this->form_validation->set_rules('sender_email', 'lang:em_system_email', 'required|trim|valid_email|max_length[120]');
			$this->form_validation->set_rules('protocol', 'lang:em_email_server', 'trim');

            $protocol = $this->input->post('protocol');
			if ($protocol == 'sendmail') {
				$this->form_validation->set_rules('mailpath', 'lang:em_sendmail_path', 'required|trim');
			} elseif ($protocol == 'smtp') {
				$this->form_validation->set_rules('smtp_host', 'lang:em_smtp_address', 'required|trim');
				$this->form_validation->set_rules('smtp_user', 'lang:em_smtp_username', 'trim');
				$this->form_validation->set_rules('smtp_pass', 'lang:em_smtp_password', 'trim|matches_pattern[[A-Za-z0-9!@#\%$^&+=]{2,20}]');
				$this->form_validation->set_rules('smtp_port', 'lang:em_smtp_port', 'trim|numeric');
				$this->form_validation->set_rules('smtp_timeout', 'lang:em_smtp_timeout', 'trim|numeric');
			}

			if ($this->form_validation->run() !== false) {
				$data = array(
                    array('name' => 'sender_email', 'value' => $this->input->post('sender_email')),
                    array('name' => 'mailtype',     'value' => $this->input->post('mailtype')),
                    array('name' => 'protocol',     'value' => $protocol),
                    array('name' => 'mailpath',     'value' => $_POST['mailpath']),
                    array('name' => 'smtp_host',    'value' => isset($_POST['smtp_host']) ? $_POST['smtp_host'] : ''),
                    array('name' => 'smtp_user',    'value' => isset($_POST['smtp_user']) ? $_POST['smtp_user'] : ''),
                    array('name' => 'smtp_pass',    'value' => isset($_POST['smtp_pass']) ? $_POST['smtp_pass'] : ''),
                    array('name' => 'smtp_port',    'value' => isset($_POST['smtp_port']) ? $_POST['smtp_port'] : ''),
                    array('name' => 'smtp_timeout', 'value' => isset($_POST['smtp_timeout']) ? $_POST['smtp_timeout'] : '5'),
                );

				// Save the settings to the db
				$updated = $this->settings_model->update_batch($data, 'name');

				if ($updated) {
					// Success, reload the page so they can see their settings
					Template::set_message('Email settings successfully saved.', 'success');
					redirect(SITE_AREA . '/settings/emailer');
				} else {
					Template::set_message('There was an error saving your settings.', 'error');
				}
			} else {
				Template::set_message('There was an error saving your settings.', 'error');
			}
		}

		// Load our current settings
		$settings = $this->settings_model->select('name,value')
                                         ->find_all_by('module', 'email');

		Template::set($settings);
		Template::set('toolbar_title', 'Email Settings');

		Template::render();
	}

	/**
	 * Display the screen to edit the email templagtes
	 *
	 * @return void
	 */
	public function template()
	{
		if (isset($_POST['save'])) {
			$header = $_POST['header'];
			$footer = $_POST['footer'];

			$this->load->helper('file');

			write_file(BFPATH . 'modules/emailer/views/email/_header.php', $header, 'w+');
			write_file(BFPATH . 'modules/emailer/views/email/_footer.php', $footer, 'w+');

			Template::set_message('Template successfully saved.', 'success');

			redirect(SITE_AREA . '/settings/emailer/template');
		}

		Template::set('toolbar_title', lang('em_email_template'));

		Template::render();
	}

	/**
	 * @todo Remove this?
	 *
	 * @return void
	 */
	public function emails()
	{
		Template::set('toolbar_title', lang('em_email_contents'));
		Template::render();
	}

	/**
	 * Send a test email
	 *
	 * @return void
	 */
	public function test()
	{
		if ( ! isset($_POST['test'])) {
			$this->security->csrf_show_error();
		}

		$this->load->library('emailer');
		$this->emailer->enable_debug(true);

		$data = array(
            'to'		=> $this->input->post('email'),
            'subject'	=> lang('em_test_mail_subject'),
            'message'	=> lang('em_test_mail_body'),
         );

		$success = $this->emailer->send($data, false);

		Template::set('success', $success);
		Template::set('debug', $this->emailer->debug_message);

		Template::render();
	}

	/**
	 * Display all of the emails currently in the queue.
	 *
	 * @return void
	 */
	public function queue()
	{
		$offset = $this->uri->segment(5);

		$this->load->library('pagination');
		$this->load->model('Emailer_model', 'emailer_model', true);

		// Deleting anything?
		if (isset($_POST['delete'])) {
			$checked = $this->input->post('checked');
			if (is_array($checked) && count($checked)) {
				$result = false;
				foreach ($checked as $pid) {
					$result = $this->emailer_model->delete($pid);
				}

				if ($result) {
					Template::set_message(sprintf(lang('em_delete_success'), count($checked)), 'success');
				} else {
					Template::set_message(sprintf(lang('em_delete_failure'), $this->emailer_model->error), 'error');
				}
			} else {
				Template::set_message(sprintf(lang('em_delete_error'), $this->emailer_model->error), 'error');
			}
		} elseif (isset($_POST['force_process'])) {
			$this->load->library('emailer');
			$this->emailer->enable_debug(true);

			// Use ob to catch output designed for CRON only
			ob_start();
			$success = $this->emailer->process_queue();
			ob_end_clean();

			if ( ! $success) {
				Template::set('email_debug', $this->emailer->debug_message);
			}
		} elseif (isset($_POST['insert_test'])) {
			$this->load->library('emailer');

			$data = array(
				'to'		=> $this->settings_lib->item('site.system_email'),
				'subject'	=> lang('em_test_mail_subject'),
				'message'	=> lang('em_test_mail_body'),
			);

			$this->emailer->send($data, true);
		}

		Template::set('emails', $this->emailer_model->limit($this->limit, $offset)->find_all());
		Template::set('total_in_queue', $this->emailer_model->count_by('date_sent IS NULL'));
		Template::set('total_sent', $this->emailer_model->count_by('date_sent IS NOT NULL'));

		$total_emails = $this->emailer_model->count_all();

		$this->pager['base_url']    = site_url(SITE_AREA . '/settings/emailer/queue');
		$this->pager['total_rows']  = $total_emails;
		$this->pager['per_page']    = $this->limit;
		$this->pager['uri_segment']	= 5;

		$this->pagination->initialize($this->pager);

		Template::set('toolbar_title', lang('em_emailer_queue'));

		Template::render();
	}

	/**
	 * Displays a preview of the email as stored in the database.
	 *
	 * @param int $id An INT with the ID of the email to preview from the queue.
	 *
	 * @return void
	 */
	public function preview($id = 0)
	{
		$this->output->enable_profiler(false);
		$this->load->model('emailer/emailer_model');

		if ( ! empty($id) && is_numeric($id)) {
			$email = $this->emailer_model->find($id);

			if ($email) {
				Template::set('email', $email);
				Template::render('blank');
			}
		}
	}

	/**
	 * Create a new email and send to selected recipents
	 *
	 * @return void
	 */
	public function create()
	{
		$this->load->model('users/user_model');
		$this->load->library('emailer');

		if (isset($_POST['create'])) {
			// Validate subject, content and recipients
			$this->form_validation->set_rules('email_subject', 'lang:em_email_subject', 'required|trim|min_length[1]|max_length[255]');
			$this->form_validation->set_rules('email_content', 'lang:em_email_content', 'required|trim|min_length[1]');
			$this->form_validation->set_rules('checked', 'lang:bf_users', 'required');

			if ($this->form_validation->run() === false) {
                // @todo This shouldn't be necessary, is set_value() not used in the view?
				Template::set('email_subject', $this->input->post('email_subject'));
				Template::set('email_content', $this->input->post('email_content'));
				Template::set('checked', $this->input->post('checked'));
			} else {
				$data = array (
					'subject'	=> $this->input->post('email_subject'),
					'message'	=> $this->input->post('email_content'),
				);
				$checked = $this->input->post('checked');

				$success_count = 0;
				if (is_array($checked) && count($checked)) {
					$result = false;
					foreach ($checked as $user_id) {
						// Get the email from $user_id
						$user = $this->user_model->find($user_id);
						if ($user != null) {
							$data['to'] = $user->email;

							$result = $this->emailer->send($data, true);
							if ($result) {
                                $success_count++;
                            }
						}
					}

					if ($result) {
						Template::set_message($success_count . ' ' . lang('em_create_email_success'), 'success');
						redirect(SITE_AREA . '/settings/emailer/queue');
					} else {
						Template::set_message(sprintf(lang('em_create_email_failure'), $this->user_model->error), 'error');
					}
				} else {
					Template::set_message(sprintf(lang('em_create_email_error'), $this->user_model->error), 'error');
				}
			}
		}//end if (isset($_POST['create']))

		$users = $this->user_model->where('users.deleted', 0)->find_all();

		Template::set('users', $users);
		Template::set('toolbar_title', lang('em_create_email'));

        Template::render();
	}
}
/* End of file settings.php */