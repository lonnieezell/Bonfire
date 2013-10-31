<?php

class template_test extends CI_UnitTestCase {

    public function test_is_loaded()
    {
        $this->assertTrue(class_exists('Template'));
    }

    //--------------------------------------------------------------------
/*
    //--------------------------------------------------------------------
    // Lex Parser
    //--------------------------------------------------------------------

    public function test_lex_parser()
    {
        Template::parse_views(true);

        // Create a simple file
        file_put_contents(APPPATH .'views/bf_parser_test.php', '<span>{{ title }}</span>');

        Template::set('title', 'Lex Is Working');
        Template::load_view('bf_parser_test', null, '', false, $output);


        $this->assertTrue(class_exists('MY_Parser'));
        $this->assertEqual($output, '<span>Lex Is Working</span>');

        // Remove the temp file
        unlink(APPPATH .'views/bf_parser_test.php');
    }

    //--------------------------------------------------------------------

    public function test_lex_parser_in_themes()
    {
        Template::parse_views(true);
        Template::set_theme('default');

        $file = FCPATH .'themes/default/bf_parser_test.php';

        // Create a simple file
        file_put_contents($file, '<span>{{ title }}</span>');

        Template::set('title', 'Lex Is Working');
        Template::load_view('bf_parser_test', null, '', true, $output);


        $this->assertTrue(class_exists('MY_Parser'));
        $this->assertEqual($output, '<span>Lex Is Working</span>');

        // Remove the temp file
        unlink($file);
    }

    //--------------------------------------------------------------------
*/
}