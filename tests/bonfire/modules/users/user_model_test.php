<?php

require_once (TESTS_DIR .'_support/database.php');

class user_model_test extends CI_UnitTestCase {

    public function __construct()
    {
        parent::__construct();

        // Make sure the moded db class is available
        Mock::generate('MY_DB');

        $this->ci->load->model('roles/role_model');
        Mock::generate('Role_model');

        Template::$ignore_session = true;
    }

    //--------------------------------------------------------------------

    public function setUp()
    {
        $this->ci->user_model->db = new MockMY_DB();
        $this->model = $this->ci->user_model;

        $this->ci->role_model = new MockRole_model();
    }

    //--------------------------------------------------------------------

    public function test_is_loaded()
    {
        $this->assertTrue(class_exists('User_model'));
        $this->assertIsA($this->model->db, 'MockMY_DB');
    }

    //--------------------------------------------------------------------

    public function test_insert_returns_false_with_no_data()
    {
        $this->assertFalse($this->model->insert());
    }

    //--------------------------------------------------------------------

    public function test_insert_returns_false_with_only_password()
    {
        $data = array(
            'password' => 'abc'
        );

        $this->assertFalse($this->model->insert($data));
    }

    //--------------------------------------------------------------------

    public function test_insert_returns_false_with_only_email()
    {
        $data = array(
            'email' => 'abc'
        );

        $this->assertFalse($this->model->insert($data));
    }

    //--------------------------------------------------------------------

    public function test_insert_returns_false_with_no_email_only_pass()
    {
        $data = array(
            'password' => 'abc',
            'username'  => 'abc'
        );

        $this->assertFalse($this->model->insert($data));
    }

    //--------------------------------------------------------------------

    public function test_insert_returns_id()
    {
        $data = array(
            'password' => 'abc',
            'email'     => 'test@myemail.com'
        );

        // We can't test the data to the insert method since the pasword hash
        // will likely be different
        $this->model->db->expectOnce('insert');
        $this->model->db->returns('insert', 12);
        $this->model->db->returns('insert_id', 12);

        $this->assertEqual($this->model->insert($data), 12);
    }

    //--------------------------------------------------------------------

    public function test_update_returns_null_with_no_id()
    {
        $this->assertTrue($this->model->update() === NULL);
    }

    //--------------------------------------------------------------------

    public function test_update_returns_true()
    {
        $this->model->db->expectOnce('update', array('users', array('name'=>'myName'), array('id'=>12)));
        $this->model->db->returns('update', TRUE);

        $this->assertTrue($this->model->update(12, array('name'=>'myName')) );
    }

    //--------------------------------------------------------------------

    public function test_update_correct_iso()
    {
        $this->model->db->expectOnce('update', array('users', array('name'=>'myName', 'country_iso'=>'US'), array('id'=>12)));
        $this->model->db->returns('update', TRUE);

        $this->assertTrue($this->model->update(12, array('name'=>'myName', 'iso'=>'US')) );
    }

    //--------------------------------------------------------------------

    public function test_set_default_role_returns_false_with_string()
    {
        $this->assertTrue($this->model->set_to_default_role('abc') === FALSE);
    }

    //--------------------------------------------------------------------

    public function test_set_default_role()
    {
        $this->ci->role_model->expectOnce('default_role_id');
        $this->ci->role_model->returns('default_role_id', 8);
        $this->model->db->returns('where', $this->model->db);
        $this->model->db->expectOnce('update', array('users', array('role_id'=>8)));
        $this->model->db->returns('update', TRUE);

        $this->assertTrue($this->model->set_to_default_role(5));
    }

    //--------------------------------------------------------------------

    public function test_find_returns_false()
    {
        $this->model->db->expectOnce('select', array('users.*, role_name', null));
        $this->model->db->expectOnce('join', array('roles', 'roles.role_id = users.role_id', 'left'));
        $this->model->db->expectOnce('get_where');
        $this->model->db->returns('get_where', $this->model->db);
        $this->model->db->returns('num_rows', 1);
        $this->model->db->returns('row', array(1));

        $this->assertIsA($this->model->find(), 'Array');
    }

    //--------------------------------------------------------------------

    public function test_find_all()
    {
        $this->model->db->expectOnce('select', array('users.*, role_name', null));
        $this->model->db->expectOnce('join', array('roles', 'roles.role_id = users.role_id', 'left'));
        $this->model->db->expectOnce('get');
        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('num_rows', 1);
        $this->model->db->returns('result', array(1));

        $this->assertIsA($this->model->find_all(), 'Array');
    }

    //--------------------------------------------------------------------
}