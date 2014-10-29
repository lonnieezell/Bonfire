<?php

class MY_array_helper_test extends CI_UnitTestCase {

    public function __construct()
    {
        parent::__construct();

        $this->ci->load->helper('array');
    }

    //--------------------------------------------------------------------

    public function test_is_loaded()
    {
        $this->assertTrue(function_exists('array_index_by_key'));
    }

    //--------------------------------------------------------------------

    public function test_array_index_by_key_returns_false_on_empty()
    {
        $this->assertFalse(array_index_by_key());
    }

    //--------------------------------------------------------------------

    public function test_array_index_by_key_returns_index()
    {
        $array = array(
            array('value' => 1),
            array('value' => 2),
            array('value' => 5),
        );

        // Find the index of the array where 'value' == 2
        $this->assertEqual(array_index_by_key('value', 2, $array), 1);  // Remember - the array is 0-based!
        $this->assertEqual(array_index_by_key('value', 5, $array), 2);
    }

    //--------------------------------------------------------------------

    public function test_array_index_by_key_returns_false_when_not_found()
    {
        $array = array(
            array('value' => 1),
            array('value' => 2),
            array('value' => 5),
        );

        $this->assertTrue(array_index_by_key('value', 51, $array) === FALSE);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // array_multi_sort_by_column
    //--------------------------------------------------------------------

    public function test_multisort_returns()
    {
        $users = array(
            array('username'=>'darth', 'name'=>'Vader'),
            array('username'=>'luke', 'name'=>'Skywalker')
        );

        $users2 = array(
            array('username'=>'darth', 'name'=>'Vader'),
            array('username'=>'luke', 'name'=>'Skywalker')
        );

        array_multi_sort_by_column($users, 'name');
        $this->assertNotEqual($users, $users2);
    }

    //--------------------------------------------------------------------

}