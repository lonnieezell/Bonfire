<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
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
 * @package Bonfire\Modules\Emailer\Controllers\Settings
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 */
class Settings extends Admin_Controller
{
    /**
     * Set up the permissions and load the language file.
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
        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('sender_email', 'lang:emailer_system_email', 'required|trim|valid_email|max_length[120]');
            $this->form_validation->set_rules('protocol', 'lang:emailer_email_server', 'trim');

            $protocol = $this->input->post('protocol');
            if ($protocol == 'sendmail') {
                $this->form_validation->set_rules('mailpath', 'lang:emailer_sendmail_path', 'required|trim');
            } elseif ($protocol == 'smtp') {
                $this->form_validation->set_rules('smtp_host', 'lang:emailer_smtp_address', 'required|trim');
                $this->form_validation->set_rules('smtp_user', 'lang:emailer_smtp_username', 'trim');
                $this->form_validation->set_rules('smtp_pass', 'lang:emailer_smtp_password', 'trim|matches_pattern[[A-Za-z0-9!@#\%$^&+=]{2,20}]');
                $this->form_validation->set_rules('smtp_port', 'lang:emailer_smtp_port', 'trim|numeric');
                $this->form_validation->set_rules('smtp_timeout', 'lang:emailer_smtp_timeout', 'trim|numeric');
            }

            if ($this->form_validation->run() === false) {
                Template::set_message(lang('emailer_settings_save_error'), 'error');
            } else {
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
                    Template::set_message(lang('emailer_settings_save_success'), 'success');
                    redirect(SITE_AREA . '/settings/emailer');
                }

                Template::set_message(lang('emailer_settings_save_error'), 'error');
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

        Template::set('toolbar_title', lang('emailer_email_template'));

        Template::render();
    }

    /**
     * Send a test email
     *
     * @return void
     */
    public function test()
    {
        if (! isset($_POST['test'])) {
            $this->security->csrf_show_error();
        }

        $this->load->library('emailer');
        $this->emailer->enable_debug(true);

        $data = array(
            'to'      => $this->input->post('email'),
            'subject' => lang('emailer_test_mail_subject'),
            'message' => lang('emailer_test_mail_body'),
         );

        $success = $this->emailer->send($data, false);

        Template::set('toolbar_title', lang('emailer_email_test'));
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
        $this->load->model('emailer/emailer_model');

        // Deleting anything?
        if (isset($_POST['delete'])) {
            $checked = $this->input->post('checked');
            if (! empty($checked) && is_array($checked)) {
                $result = true;
                $emailError = '';
                foreach ($checked as $pid) {
                    $deleted = $this->emailer_model->delete($pid);
                    if (! $deleted) {
                        $result = false;
                        $emailError = $this->emailer_model->error;
                    }
                }

                if ($result) {
                    Template::set_message(sprintf(lang('emailer_delete_success'), count($checked)), 'success');
                } else {
                    Template::set_message(sprintf(lang('emailer_delete_failure'), $emailError), 'error');
                }
            } else {
                Template::set_message(lang('emailer_delete_none'), 'error');
            }
        } elseif (isset($_POST['force_process'])) {
            $this->load->library('emailer');
            $this->emailer->enable_debug(true);

            // Use ob to catch output designed for CRON only
            ob_start();
            $success = $this->emailer->process_queue();
            ob_end_clean();

            if (! $success) {
                Template::set('email_debug', $this->emailer->debug_message);
            }
        } elseif (isset($_POST['insert_test'])) {
            $this->load->library('emailer');

            $data = array(
                'to'      => $this->settings_lib->item('site.system_email'),
                'subject' => lang('emailer_test_mail_subject'),
                'message' => lang('emailer_test_mail_body'),
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
        $this->pager['uri_segment'] = 5;

        $this->pagination->initialize($this->pager);

        Template::set('toolbar_title', lang('emailer_emailer_queue'));

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

        if (! empty($id) && is_numeric($id)) {
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

        if (isset($_POST['create'])) {
            // Validate subject, content and recipients
            $this->form_validation->set_rules('email_subject', 'lang:emailer_email_subject', 'required|trim|min_length[1]|max_length[255]');
            $this->form_validation->set_rules('email_content', 'lang:emailer_email_content', 'required|trim|min_length[1]');
            $this->form_validation->set_rules('checked[]', 'lang:bf_users', 'required');

            if ($this->form_validation->run() === false) {
                // @todo This shouldn't be necessary, is set_value() not used in the view?
                Template::set('email_subject', $this->input->post('email_subject'));
                Template::set('email_content', $this->input->post('email_content'));
                Template::set('checked', $this->input->post('checked'));
            } else {
                $data = array (
                    'subject' => $this->input->post('email_subject'),
                    'message' => $this->input->post('email_content'),
                );

                $checked = $this->input->post('checked');
                $success_count = 0;
                if (! empty($checked) && is_array($checked)) {
                    $this->load->library('emailer');
                    $result = false;
                    $emailError = '';
                    foreach ($checked as $user_id) {
                        // Get the email from $user_id
                        $user = $this->user_model->find($user_id);
                        if ($user != null) {
                            $data['to'] = $user->email;

                            $result = $this->emailer->send($data, true);
                            if ($result) {
                                ++$success_count;
                            } else {
                                $emailError = $this->emailer->error;
                            }
                        }
                    }

                    if ($result) {
                        Template::set_message(sprintf(lang('emailer_create_email_queued'), $success_count), 'success');
                        redirect(SITE_AREA . '/settings/emailer/queue');
                    }

                    Template::set_message(sprintf(lang('emailer_create_email_failure'), $emailError), 'error');
                } else {
                    Template::set_message(lang('emailer_create_email_no_users'), 'error');
                }
            }
        }

        Template::set('users', $this->user_model->where('users.deleted', 0)->find_all());
        Template::set('toolbar_title', lang('emailer_create_email'));

        Template::render();
    }
}
