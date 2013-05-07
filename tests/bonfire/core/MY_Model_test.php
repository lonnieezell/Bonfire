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

    public function test_delete_without_soft_deletes()
    {
        $this->model->db->expectOnce('delete', array('records_table', array('id' => 1)));
        $this->model->db->returns('delete', TRUE);

        $this->assertTrue( $this->model->soft_delete(false)->delete(1) );
    }

    //--------------------------------------------------------------------

    public function test_delete_with_soft_deletes()
    {
        $this->model->db->expectOnce('where', array('id', 1));
        $this->model->db->expectOnce('update', array('records_table', array('deleted' => 1)));
        $this->model->db->returns('update', TRUE);

        $this->assertTrue( $this->model->soft_delete(true)->delete(1) );
    }

    //--------------------------------------------------------------------

    public function test_delete_where_without_soft_deletes()
    {
        $this->model->db->expectOnce('where', array('userid', 5));
        $this->model->db->expectOnce('delete', array('records_table'));
        $this->model->db->returns('affected_rows', 1);
        $this->model->db->returns('delete', TRUE);

        $this->assertTrue( $this->model->soft_delete(false)->delete_where( array('userid' => 5) ) );
    }

    //--------------------------------------------------------------------

    public function test_delete_where_with_soft_deletes()
    {
        $this->model->db->expectOnce('where', array('userid', 5));
        $this->model->db->expectOnce('update', array('records_table', array('deleted' => 1)));
        $this->model->db->returns('affected_rows', 1);
        $this->model->db->returns('update', TRUE);

        $this->assertTrue( $this->model->soft_delete()->delete_where( array('userid' => 5) ) );
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Utility Methods
    //--------------------------------------------------------------------

    public function test_count_all()
    {
        $this->model->db->expectOnce('count_all_results', array('records_table'));
        $this->model->db->returns('count_all_results', 5);

        $this->assertEqual( $this->model->count_all(), 5 );
    }

    //--------------------------------------------------------------------

    public function test_count_by()
    {
        $this->model->db->expectOnce('where', array('column', 'value'));
        $this->model->db->expectOnce('count_all_results', array('records_table'));
        $this->model->db->returns('count_all_results', 5);

        $this->assertEqual( $this->model->count_by('column', 'value'), 5 );
    }

    //--------------------------------------------------------------------

    public function test_get_field()
    {
        $data = new stdClass();
        $data->field = 'value';

        $this->model->db->expectOnce('select', array('field'));
        $this->model->db->expectOnce('where', array('id', 1));
        $this->model->db->expectOnce('get', array('records_table'));
        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('num_rows', 1);
        $this->model->db->returns('row', $data);

        $this->assertEqual( $this->model->get_field(1, 'field'), 'value' );
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // Chainable Utility Methods
    //--------------------------------------------------------------------

    public function test_where()
    {
        $this->model->db->expectOnce('where', array('field', 'value'));
        $return = $this->model->where('field', 'value');

        $this->assertIsA($return, 'Record_model');
    }

    //--------------------------------------------------------------------

    public function test_limit()
    {
        $this->model->db->expectOnce('limit', array(10, 5));
        $return = $this->model->limit(10, 5);
        $this->assertIsA($return, 'Record_model');
    }

    //--------------------------------------------------------------------

    public function test_join()
    {
        $this->model->db->expectOnce('join', array('newTable', 'condition', 'type'));

        $return = $this->model->join('newTable', 'condition', 'type');
        $this->assertIsA($return, 'Record_model');
    }

    //--------------------------------------------------------------------

    public function test_order_by()
    {
        $this->model->db->expectOnce('order_by', array('field', 'order'));
        $return = $this->model->order_by('field', 'order');
        $this->assertIsA($return, 'Record_model');
    }

    //--------------------------------------------------------------------

}