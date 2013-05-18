<?php

require_once (TESTS_DIR .'_support/database.php');

class activity_model_test extends CI_UnitTestCase {

    public function __construct()
    {
        parent::__construct();

        // Make sure the moded db class is available
        Mock::generate('MY_DB');

        Template::$ignore_session = true;
    }

    //--------------------------------------------------------------------

    public function setUp()
    {
        if (!class_exists('Activity_model'))
        {
            $this->ci->load->model('activities/activity_model');
        }

        $this->ci->activity_model->db = new MockMY_DB();
        $this->am = $this->ci->activity_model;
    }

    //--------------------------------------------------------------------


    public function test_is_loaded()
    {
        $this->assertTrue(isset($this->ci->activity_model));
        $this->assertIsA($this->am, 'Activity_model');
    }

    //--------------------------------------------------------------------

    public function test_log_activity_returns_false_with_no_userid()
    {
        $this->assertTrue($this->am->log_activity() === FALSE);
    }

    //--------------------------------------------------------------------

    public function test_log_activity_returns_false_with_string_userid()
    {
        $this->assertTrue($this->am->log_activity('abcd') === FALSE);
    }

    //--------------------------------------------------------------------

    public function test_log_activity_returns_false_with_zero_userid()
    {
        $this->assertTrue($this->am->log_activity(0) === FALSE);
    }

    //--------------------------------------------------------------------

    public function test_log_activity_returns_false_with_empty_activity()
    {
        $this->assertTrue($this->am->log_activity(12) === FALSE);
    }

    //--------------------------------------------------------------------

    public function test_log_activity_returns_int_on_success()
    {
        $this->am->db->expectOnce('insert', array('activities', array('user_id' => 12, 'activity' => 'test activity', 'module' => 'tests', 'created_on' => date('Y-m-d H:i:s'))) );
        $this->am->db->returns('insert', 15);
        $this->am->db->returns('insert_id', 15);

        $this->assertIsA($this->am->log_activity(12, 'test activity', 'tests'), 'Integer');
    }

    //--------------------------------------------------------------------

    public function test_find_by_modules_returns_false_with_no_modules()
    {
        $this->assertTrue($this->am->find_by_module() === FALSE);
    }

    //--------------------------------------------------------------------

    public function test_find_by_modules()
    {
        $this->am->db->expectOnce('get');
        $this->am->db->returns('get', $this->am->db);
        $this->am->db->returns('num_rows', 1);
        $this->am->db->returns('result', 'good stuff');

        $this->assertEqual($this->am->find_by_module( array('module1', 'module2') ), 'good stuff');
    }

    //--------------------------------------------------------------------



}