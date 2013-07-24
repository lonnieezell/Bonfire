<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends Base_Controller {

    public function index()
    {
        $this->load->model('roles/role_model');
        $role = $this->role_model->find(1);

        die('<pre>'. print_r($role, true));
    }

    //--------------------------------------------------------------------

}