<?php

require_once(TESTS_DIR . '_support/database.php');
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
        $this->model->db->expectOnce('where', array('id', 1));
        $this->model->db->expectOnce('delete', array('records_table'));
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
        $this->model->db->expectOnce('where', array(array('userid' => 5)));
        $this->model->db->expectOnce('delete', array('records_table'));
        $this->model->db->returns('affected_rows', 1);
        $this->model->db->returns('delete', TRUE);

        $this->assertTrue( $this->model->soft_delete(false)->delete_where( array('userid' => 5) ) );
    }

    //--------------------------------------------------------------------

    public function test_delete_where_with_soft_deletes()
    {
        $this->model->db->expectOnce('where', array(array('userid' => 5)));
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
        $this->model->db->returns('select', $this->model->db);
        $this->model->db->returns('where', $this->model->db);
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

    public function test_order_by()
    {
        $this->model->db->expectOnce('order_by', array('field', 'order'));
        $return = $this->model->order_by('field', 'order');
        $this->assertIsA($return, 'Record_model');
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Trigger Tests
    //--------------------------------------------------------------------
    // These test the automatic functioning of the before_ and after_
    // triggers in the insert and updates. WE do the test by testing for the
    // addition of the created_on and modified_on fields into the data streams.
    //

    public function test_reset_model()
    {
        // This 'test' simply resets the model to use for the following tests.
        $this->load_model = 'Trigger_model';
    }

    //--------------------------------------------------------------------

    public function test_triggered_insert()
    {
        $this->model->db->expectOnce('insert', array('records_table', array('title' => 'MyTitle', 'created_on' => time()) ));
        $this->model->db->expectOnce('insert_id');
        $this->model->db->returns('insert', true);
        $this->model->db->returns('insert_id', 5);

        $data = array('title' => 'MyTitle');

        $id = $this->model->insert($data);
        $this->assertEqual($id, 5);
    }

    //--------------------------------------------------------------------

    public function test_triggered_insert_batch()
    {
        $data = array(
            array('title' => 'My Title'),
            array('title' => 'Another Title')
        );

        $triggered_data = array(
            array('created_on' => time(), 'title' => 'My Title'),
            array('created_on' => time(), 'title' => 'Another Title')
        );

        $this->model->db->expectOnce('insert_batch', array( 'records_table', $triggered_data ));

        $this->assertTrue($this->model->insert_batch($data));
    }

    //--------------------------------------------------------------------

    public function test_triggered_update()
    {
        $triggered_data = array(
            'column' => 'value',
            'modified_on' => time()
        );

        $this->model->db->expectOnce('update', array( 'records_table', $triggered_data, array('id' => 5) ));
        $this->model->db->returns('update', true);

        $this->assertTrue($this->model->update(5, array('column' => 'value')));
    }

    //--------------------------------------------------------------------

    public function test_triggered_update_batch()
    {
        $data = array(
            array('title' => 'My Title'),
            array('title' => 'Another Title')
        );

        $triggered_data = array(
            array('title' => 'My Title', 'modified_on' => time()),
            array('title' => 'Another Title', 'modified_on' => time())
        );

        $this->model->db->expectOnce('update_batch', array( 'records_table', $triggered_data, 'title' ));

        $this->assertTrue($this->model->update_batch($data, 'title'));
    }

    //--------------------------------------------------------------------

    public function test_triggered_update_where()
    {
        $triggered_data = array(
            'column' => 'value',
            'modified_on' => time()
        );

        $this->model->db->expectOnce('update', array( 'records_table', $triggered_data, array('id' => 5) ));
        $this->model->db->returns('update', true);

        $this->assertTrue($this->model->update_where('id', 5, array('column' => 'value')));
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Protected Attributes
    //--------------------------------------------------------------------

    public function test_protected_reset_model()
    {
        // This 'test' simply resets the model to use for the
        // following tests.
        $this->load_model = 'Protected_attribute_model';
    }

    //--------------------------------------------------------------------

    public function test_protected_insert()
    {
        $data = array('name' => 'MyName', 'title' => 'MyTitle');

        $this->model->db->expectOnce('insert', array('records_table', array('title' => 'MyTitle')));
        $this->model->db->returns('insert', true);
        $this->model->db->expectOnce('insert_id');
        $this->model->db->returns('insert_id', 5);

        $id = $this->model->insert($data);
        $this->assertEqual($id, 5);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Output Types Tests (array, json)
    //--------------------------------------------------------------------

    public function test_find_as_json()
    {
        $data = new stdClass();
        $data->title = 'Mytitle';

        $this->model->db->returns('get_where', $this->model->db);
        $this->model->db->returns('num_rows', 1);
        $this->model->db->returns('row', $data);

        $obj = $this->model->as_json()->find(1);
        $this->assertEqual($obj, json_encode($data));
    }

    //--------------------------------------------------------------------

    public function test_find_by_as_json()
    {
        $data = new stdClass();
        $data->title = 'Mytitle';

        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('row', $data);
        $this->model->db->returns('num_rows', 1);

        $obj = $this->model->as_json()->find_by('column', 'value');
        $this->assertEqual($obj, json_encode($data));
    }

    //--------------------------------------------------------------------

    public function test_find_all_as_json()
    {
        $data = new stdClass();
        $data->title = 'Mytitle';

        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('result', $data);
        $this->model->db->returns('num_rows', 1);

        $obj = $this->model->as_json()->find_all();
        $this->assertEqual($obj, json_encode($data));
    }

    //--------------------------------------------------------------------

    public function test_find_as_array()
    {
        $data = new stdClass();
        $data->title = 'Mytitle';

        $this->model->db->returns('get_where', $this->model->db);
        $this->model->db->returns('row_array', array('title'=>'Mytitle'));
        $this->model->db->returns('num_rows', 1);

        $obj = $this->model->as_array()->find(1);
        $this->assertEqual($obj, (array)$data);
    }

    //--------------------------------------------------------------------

    public function test_find_by_as_array()
    {
        $data = new stdClass();
        $data->title = 'Mytitle';

        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('row_array', array('title'=>'Mytitle'));
        $this->model->db->returns('num_rows', 1);

        $obj = $this->model->as_array()->find_by('column', 'value');
        $this->assertEqual($obj, (array)$data);
    }

    //--------------------------------------------------------------------

    public function test_find_all_as_array()
    {
        $data = new stdClass();
        $data->title = 'Mytitle';

        $this->model->db->returns('get', $this->model->db);
        $this->model->db->returns('result_array', array('title'=>'Mytitle'));
        $this->model->db->returns('num_rows', 1);

        $obj = $this->model->as_array()->find_all();
        $this->assertEqual($obj, (array)$data);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Database Wrapper Tests
    //--------------------------------------------------------------------
    // Since these are simply wrapping features within the database class,
    // we only need to test if they are actually called as tehy should be.

    public function test_select()
    {
        $this->model->db->expectOnce('select', array('one, two, three', TRUE));
        $this->model->select('one, two, three', TRUE);
    }

    //--------------------------------------------------------------------

    public function test_select_max()
    {
        $this->model->db->expectOnce('select_max', array('select', 'alias'));
        $this->model->select_max('select', 'alias');
    }

    //--------------------------------------------------------------------

    public function test_select_min()
    {
        $this->model->db->expectOnce('select_min', array('select', 'alias'));
        $this->model->select_min('select', 'alias');
    }

    //--------------------------------------------------------------------

    public function test_select_avg()
    {
        $this->model->db->expectOnce('select_avg', array('select', 'alias'));
        $this->model->select_avg('select', 'alias');
    }

    //--------------------------------------------------------------------

    public function test_select_sum()
    {
        $this->model->db->expectOnce('select_sum', array('select', 'alias'));
        $this->model->select_sum('select', 'alias');
    }

    //--------------------------------------------------------------------

    public function test_distinct()
    {
        $this->model->db->expectOnce('distinct', array(FALSE));
        $this->model->distinct(FALSE);
    }

    //--------------------------------------------------------------------

    public function test_from()
    {
        $this->model->db->expectOnce('from', array('me'));
        $this->model->from('me');
    }

    //--------------------------------------------------------------------

    public function test_join()
    {
        $this->model->db->expectOnce('join', array('table', 'condition', 'type'));
        $this->model->join('table', 'condition', 'type');
    }

    //--------------------------------------------------------------------
/*
    public function test_where()
    {
        $this->model->db->expectOnce('where', array('key', 'value', FALSE));
        $this->model->where('key', 'value', FALSE);
    }

    //--------------------------------------------------------------------
*/
    public function test_or_where()
    {
        $this->model->db->expectOnce('or_where', array('key', 'value', FALSE));
        $this->model->or_where('key', 'value', FALSE);
    }

    //--------------------------------------------------------------------

    public function test_where_in()
    {
        $this->model->db->expectOnce('where_in', array('key', 'values'));
        $this->model->where_in('key', 'values');
    }

    //--------------------------------------------------------------------

    public function test_or_where_in()
    {
        $this->model->db->expectOnce('or_where_in', array('key', 'values'));
        $this->model->or_where_in('key', 'values');
    }

    //--------------------------------------------------------------------

    public function test_where_not_in()
    {
        $this->model->db->expectOnce('where_not_in', array('key', 'values'));
        $this->model->where_not_in('key', 'values');
    }

    //--------------------------------------------------------------------

    public function test_or_where_not_in()
    {
        $this->model->db->expectOnce('or_where_not_in', array('key', 'values'));
        $this->model->or_where_not_in('key', 'values');
    }

    //--------------------------------------------------------------------

    public function test_like()
    {
        $this->model->db->expectOnce('like', array('field', 'match', 'left'));
        $this->model->like('field', 'match', 'left');
    }

    //--------------------------------------------------------------------

    public function test_or_like()
    {
        $this->model->db->expectOnce('or_like', array('field', 'match', 'left'));
        $this->model->or_like('field', 'match', 'left');
    }

    //--------------------------------------------------------------------

    public function test_not_like()
    {
        $this->model->db->expectOnce('not_like', array('field', 'match', 'left'));
        $this->model->not_like('field', 'match', 'left');
    }

    //--------------------------------------------------------------------

    public function test_or_not_like()
    {
        $this->model->db->expectOnce('or_not_like', array('field', 'match', 'left'));
        $this->model->or_not_like('field', 'match', 'left');
    }

    //--------------------------------------------------------------------

    public function test_group_by()
    {
        $this->model->db->expectOnce('group_by', array('by'));
        $this->model->group_by('by');
    }

    //--------------------------------------------------------------------

    public function test_having()
    {
        $this->model->db->expectOnce('having', array('key', 'value', FALSE));
        $this->model->having('key', 'value', FALSE);
    }

    //--------------------------------------------------------------------

    public function test_or_having()
    {
        $this->model->db->expectOnce('or_having', array('key', 'value', FALSE));
        $this->model->or_having('key', 'value', FALSE);
    }

    //--------------------------------------------------------------------

    public function test_limit()
    {
        $this->model->db->expectOnce('limit', array('value', 'offset'));
        $this->model->limit('value', 'offset');
    }

    //--------------------------------------------------------------------

    public function test_offset()
    {
        $this->model->db->expectOnce('offset', array('offset'));
        $this->model->offset('offset');
    }

    //--------------------------------------------------------------------

    public function test_set()
    {
        $this->model->db->expectOnce('set', array('key', 'value', FALSE));
        $this->model->set('key', 'value', FALSE);
    }

    //--------------------------------------------------------------------

}