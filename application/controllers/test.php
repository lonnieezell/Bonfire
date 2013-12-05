<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends Base_Controller {

    public function index()
    {
        $this->load->model('users/user_model');

        $data = array(
            array(
                'id'    => 5,
                'username'  => 'jerk'
            ),
            array(
                'id'    => 6,
                'username'  => 'jerk'
            )
        );

        $this->user_model->update_batch($data, 'id');
    }

    //--------------------------------------------------------------------

}