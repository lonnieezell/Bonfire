<?php

require (TESTS_DIR .'_support/database.php');
//require (APPPATH .'core/MY_Model.php');

class MY_Model_test extends CI_UnitTestCase {

    public $load_model = 'Record_model';

    public $model;

    //--------------------------------------------------------------------

    public function __construct()
    {
        parent::__construct();

        // Make sure the moded db class is available
        Mock::generate('MY_DB');
    }

    //--------------------------------------------------------------------

    public function setUp()
    {
        if (!class_exists($this->load_model))
        {
            $file = strtolower($this->load_model);
            require (TESTS_DIR ."_support/models/{$file}.php");
        }

        $model_name = $this->load_model;
        $this->model = new $model_name();
        $this->model->db = new MockMY_DB();
    }

    //--------------------------------------------------------------------

    public function tearDown()
    {
        unset($this->model);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Generic Tests
    //--------------------------------------------------------------------

    public function test_is_loaded()
    {
        $this->assertTrue(class_exists('BF_Model'));
    }

    //--------------------------------------------------------------------

    public function test_mocking_setup()
    {
        $this->assertEqual(get_class($this->model->db), 'MockMY_DB');
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // CRUD Methods
    //--------------------------------------------------------------------

    public function test_find()
    {
        $this->model->db->expectOnce('get_where');
        $this->model->db->expectOnce('row');
        $this->model->db->returns('get_where', $this->model->db);
        $this->model->db->returns('num_rows', 2);
        $this->model->db->returns('row', 'fake object');

        $obj = $this->model->find(1);
        $this->assertEqual($obj, 'fake object');
    }

    //--------------------------------------------------------------------

    public function test_find_by()
    {
        $this->model->db->expectOnce('where');
        $this->model->db->expectOnce('get', array('records_table'));
        $this->model->db->expectOnce('row');
        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('num_rows', 1);
        $this->model->db->returns('row', 'fake object');

        $obj = $this->model->find_by('column', 'value');
        $this->assertEqual($obj, 'fake object');
    }

    //--------------------------------------------------------------------

    public function test_find_all()
    {
        $this->model->db->expectOnce('get');
        $this->model->db->returns('get', $this->model->db);
        $this->model->db->expectOnce('result');
        $this->model->db->returns('num_rows', 2);
        $this->model->db->returns('result', 'fake object');

        $obj = $this->model->find_all();
        $this->assertEqual($obj, 'fake object');
    }

    //--------------------------------------------------------------------

    public function test_find_all_by()
    {
        $this->model->db->expectOnce('where');
        // From 'find_all()'
        $this->model->db->expectOnce('get');
        $this->model->db->returns('get', $this->model->db);
        $this->model->db->expectOnce('result');
        $this->model->db->returns('num_rows', 2);
        $this->model->db->returns('result', 'fake object');

        $obj = $this->model->find_all_by('column', 'value');
        $this->assertEqual($obj, 'fake object');
    }

    //--------------------------------------------------------------------

    public function test_insert()
    {
        $data = array('title' => 'MyTitle');

        $this->model->db->expectOnce('insert', array('records_table', $data));
        $this->model->db->returns('insert', true);
        $this->model->db->expectOnce('insert_id');
        $this->model->db->returns('insert_id', 5);

        $id = $this->model->insert($data);
        $this->assertEqual($id, 5);
    }

    //--------------------------------------------------------------------

    public function test_insert_batch()
    {
        $this->model->db->expectOnce('insert_batch', array('records_table', array(1,2)));
        $this->model->db->returns('insert_batch', TRUE);

        $this->assertTrue($this->model->insert_batch(array(1, 2)));
    }

    //--------------------------------------------------------------------

    public function test_update()
    {
        $this->model->db->expectOnce('update');
        $this->model->db->returns('update', TRUE);

        $this->assertTrue($this->model->update(5, array('column' => 'value')));
    }

    //--------------------------------------------------------------------

    public function test_update_batch()
    {
        $this->model->db->expectOnce('update_batch', array('records_table', array(1,2), 'title'));
        $this->model->db->returns('update_batch', null);

        $this->assertTrue($this->model->update_batch(array(1, 2), 'title'));
    }

    //--------------------------------------------------------------------

    public function test_update_where()
    {
        $wheres = array('column' => 'value');
        $data = array('deleted_by' => 1);

        $this->model->db->expectOnce('update');
        $this->model->db->returns('update', TRUE);

        $this->assertTrue($this->model->update_where('column', 'value', $data));
    }

    //--------------------------------------------------------------------
}