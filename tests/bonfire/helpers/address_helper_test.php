<?php

require BF_DIR .'helpers/address_helper.php';

class address_helper_test extends CI_UnitTestCase {

    public function __construct()
    {
        parent::__construct();
        // We need to make sure the application config file is loaded.
        $this->ci->config->load('address');
    }

    //--------------------------------------------------------------------

    public function test_helper_is_loaded()
    {
        $this->assertTrue(function_exists('state_select'));
    }

    //--------------------------------------------------------------------

    public function test_state_select_gives_error_with_no_country()
    {
        $this->states = $this->ci->config->item('address.states');
        $this->ci->config->set_item('address.states', null);

        $select = state_select();
        $this->assertEqual($select, lang('us_no_states'));
    }

    //--------------------------------------------------------------------

    public function test_state_select_gives_error_with_invalid_country()
    {
        // Reload the config file to overwrite the null we created last test
        $this->ci->config->set_item('address.states', $this->states);
        unset($this->states);

        $select = state_select('', '', 'DE');
        $this->assertEqual($select, lang('us_no_states'));
    }

    //--------------------------------------------------------------------

    public function test_stat_select_gives_select_for_valid_country()
    {
        $select = state_select('', '', 'US');
        $this->assertTrue(strpos($select, '<select') === 0);
    }

    //--------------------------------------------------------------------

    public function test_state_select_includes_options()
    {
        $select = state_select('', '', 'US');
        $this->assertTrue(strpos($select, '<option') !== false);
    }

    //--------------------------------------------------------------------

    public function test_state_select_selects_default()
    {
        $select = state_select('', 'MO', 'US');
        $this->assertTrue(strpos($select, "value='MO' selected") !== false);
    }

    //--------------------------------------------------------------------

    public function test_state_select_selects_correct_state()
    {
        $select = state_select('AR', 'MO', 'US');
        $this->assertTrue(strpos($select, "value='AR' selected") !== false);
    }

    //--------------------------------------------------------------------

    public function test_state_select_uses_passed_name()
    {
        $select = state_select('', 'MO', 'US', 'valid_states');
        $this->assertTrue(strpos($select, 'name="valid_states"') !== false);
    }

    //--------------------------------------------------------------------

    public function test_state_select_sets_id_to_name()
    {
        $select = state_select('', 'MO', 'US', 'valid_states');
        $this->assertTrue(strpos($select, 'id="valid_states"') !== false);
    }

    //--------------------------------------------------------------------

    public function test_state_select_sets_class()
    {
        $select = state_select('', 'MO', 'US', 'valid_states', 'myClass');
        $this->assertTrue(strpos($select, 'class="myClass"') !== false);
    }

    //--------------------------------------------------------------------

    public function test_country_select_errors_with_no_countries()
    {
        $this->countries = $this->ci->config->item('address.countries');
        $this->ci->config->set_item('address.countries', null);

        $select = country_select();
        $this->assertEqual($select, lang('us_no_countries'));
    }

    //--------------------------------------------------------------------

    public function test_country_select_creates_select()
    {
        $this->ci->config->set_item('address.countries', $this->countries);
        unset($this->countries);

        $select = country_select('', 'US', 'myName');
        $this->assertTrue(strpos($select, '<select') === 0);
    }

    //--------------------------------------------------------------------

    public function test_country_select_includes_options()
    {
        $select = country_select('', 'US', 'myName');
        $this->assertTrue(strpos($select, '<option') !== false);
    }

    //--------------------------------------------------------------------

    public function test_country_select_selects_default()
    {
        $select = country_select('', 'US', 'myName');
        $this->assertTrue(strpos($select, "value='US' selected") !== false);
    }

    //--------------------------------------------------------------------

    public function test_country_select_selects_country()
    {
        $select = country_select('AF', 'US', 'myName');
        $this->assertTrue(strpos($select, "value='AF' selected") !== false);
    }

    //--------------------------------------------------------------------

    public function test_country_select_uses_passed_name()
    {
        $select = country_select('', 'US', 'myName');
        $this->assertTrue(strpos($select, 'name="myName"') !== false);
    }

    //--------------------------------------------------------------------

    public function test_country_select_sets_id_to_name()
    {
        $select = country_select('', 'US', 'myName');
        $this->assertTrue(strpos($select, 'id="myName"') !== false);
    }

    //--------------------------------------------------------------------

    public function test_country_select_sets_class()
    {
        $select = country_select('', 'US', 'myName', 'myClass');
        $this->assertTrue(strpos($select, 'class="myClass"') !== false);
    }

    //--------------------------------------------------------------------
}