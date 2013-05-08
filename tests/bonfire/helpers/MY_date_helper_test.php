<?php

class MY_date_helper_test extends CI_UnitTestCase {

    public function __construct()
    {
        parent::__construct();

        $this->ci->load->helper('date');
    }

    //--------------------------------------------------------------------

    public function test_is_loaded()
    {
        $this->assertTrue(function_exists('relative_time'));
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_never_on_invalid()
    {
        $time = 'abdkgj';

        $result = relative_time($time);
        $this->assertEqual($result, 'never');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_moments()
    {
        $time = strtotime('-5 seconds');

        $result = relative_time($time);
        $this->assertEqual($result, 'moments ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_minute()
    {
        $time = strtotime('-61 seconds');

        $result = relative_time($time);
        $this->assertEqual($result, '1 min ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_minutes()
    {
        $time = strtotime('-240 seconds');

        $result = relative_time($time);
        $this->assertEqual($result, '4 mins ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_hour()
    {
        $time = strtotime('-61 minutes');

        $result = relative_time($time);
        $this->assertEqual($result, '1 hour ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_hours()
    {
        $time = strtotime('-2 hours');

        $result = relative_time($time);
        $this->assertEqual($result, '2 hours ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_hours_almost_day()
    {
        $time = strtotime('-11 hours');

        $result = relative_time($time);
        $this->assertEqual($result, '11 hours ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_day()
    {
        $time = strtotime('-24 hours');

        $result = relative_time($time);
        $this->assertEqual($result, '1 day ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_days()
    {
        $time = strtotime('-2 days');

        $result = relative_time($time);
        $this->assertEqual($result, '2 days ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_days_almost_week()
    {
        $time = strtotime('-6 days');

        $result = relative_time($time);
        $this->assertEqual($result, '6 days ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_week()
    {
        $time = strtotime('-8 days');

        $result = relative_time($time);
        $this->assertEqual($result, '1 week ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_weeks()
    {
        $time = strtotime('-2 weeks');

        $result = relative_time($time);
        $this->assertEqual($result, '2 weeks ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_month()
    {
        $time = strtotime('-32 days');

        $result = relative_time($time);
        $this->assertEqual($result, '1 month ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_months()
    {
        $time = strtotime('-61 days');

        $result = relative_time($time);
        $this->assertEqual($result, '2 months ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_year()
    {
        $time = strtotime('-366 days');

        $result = relative_time($time);
        $this->assertEqual($result, '1 year ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_years()
    {
        $time = strtotime('-2 years');

        $result = relative_time($time);
        $this->assertEqual($result, '2 years ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_decade()
    {
        $time = strtotime('-11 years');

        $result = relative_time($time);
        $this->assertEqual($result, '1 decade ago');
    }

    //--------------------------------------------------------------------

    public function test_relative_time_returns_minutes_from_now()
    {
        $time = strtotime('+240 seconds');

        $result = relative_time($time);
        $this->assertEqual($result, '4 mins to go');
    }

    //--------------------------------------------------------------------
}