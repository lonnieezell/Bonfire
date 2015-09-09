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
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Permissions Settings Context
 *
 * Allows the management of the Bonfire permissions.
 *
 * @package Bonfire\Modules\Permissions\Controllers\Settings
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 *
 */
class Settings extends Admin_Controller
{
    /**
     * Sets up the require permissions and loads required classes
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->auth->restrict('Bonfire.Permissions.View');
        $this->auth->restrict('Bonfire.Permissions.Manage');

        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('permission_model');
        $this->lang->load('permissions');
        $this->load->helper('inflector');

        Template::set_block('sub_nav', 'settings/_sub_nav');

    }

    /**
     * Displays a list of all permissions with pagination
     *
     * @return void
     */
    public function index()
    {
        // Deleting anything?
        if (isset($_POST['delete'])) {
            $checked = $this->input->post('checked');

            if (! empty($checked) && is_array($checked)) {
                $result = false;
                foreach ($checked as $pid) {
                    $result = $this->permission_model->delete($pid);
                }

                if ($result) {
                    Template::set_message(count($checked) .' '. lang('permissions_deleted') .'.', 'success');
                } else {
                    Template::set_message(lang('permissions_del_failure') . $this->permission_model->error, 'error');
                }
            } else {
                Template::set_message(lang('permissions_del_error') . $this->permission_model->error, 'error');
            }
        }

        $total = $this->permission_model->count_all();

        // Pagination
        $this->load->library('pagination');

        $offset = $this->input->get('per_page');
        $limit = $this->settings_lib->item('site.list_limit');

        $this->pager['base_url']            = current_url() .'?';
        $this->pager['total_rows']          = $total;
        $this->pager['per_page']            = $limit;
        $this->pager['page_query_string']   = true;

        $this->pagination->initialize($this->pager);

        Template::set('results', $this->permission_model->limit($limit, $offset)->find_all());
        Template::set("toolbar_title", lang("permissions_manage"));
        Template::render();
    }

    /**
     * Create a new permission in the database
     *
     * @return void
     */
    public function create()
    {
        if (isset($_POST['save'])) {
            if ($this->savePermissions()) {
                Template::set_message(lang("permissions_create_success"), 'success');
                redirect(SITE_AREA . '/settings/permissions');
            }
        }

        Template::set('toolbar_title', lang("permissions_create_new_button"));
        Template::set_view('settings/permission_form');
        Template::render();
    }

    /**
     * Edit a permission record
     *
     * @return void
     */
    public function edit()
    {
        $id = (int) $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang("permissions_invalid_id"), 'error');
            redirect(SITE_AREA . '/settings/permissions');
        }

        if (isset($_POST['save'])) {
            if ($this->savePermissions('update', $id)) {
                Template::set_message(lang("permissions_edit_success"), 'success');
            }
        }

        Template::set('permissions', $this->permission_model->find($id));
        Template::set('toolbar_title', lang("permissions_edit_heading"));
        Template::set_view('settings/permission_form');
        Template::render();
    }

    /**
     * Save the permission record to the database
     *
     * @param string $type The type of save operation (insert or edit)
     * @param int    $id   The record ID in the case of edit
     *
     * @return bool
     */
    private function savePermissions($type = 'insert', $id = 0)
    {
        $this->form_validation->set_rules('name', 'lang:bf_name', 'required|trim|max_length[30]');
        $this->form_validation->set_rules('description', 'lang:bf_description', 'trim|max_length[100]');
        $this->form_validation->set_rules('status', 'lang:bf_status', 'required|trim');
        if ($this->form_validation->run() === false) {
            return false;
        }

        unset($_POST['submit'], $_POST['save']);

        if ($type == 'insert') {
            $id = $this->permission_model->insert($_POST);
            return is_numeric($id);
        } elseif ($type == 'update') {
            return $this->permission_model->update($id, $_POST);
        }

        // Unsupported value for $type.
        return false;
    }
}
