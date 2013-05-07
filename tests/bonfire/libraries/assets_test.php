<?php

class assets_test extends CI_UnitTestCase {

    public function __construct()
    {
        parent::__construct();

        if (!class_exists('Assets'))
        {
            require APP_DIR .'libraries/Assets.php';
        }
        if (!class_exists('Assets'))
        {
            require APP_DIR .'libraries/Template.php';
        }

        Template::set_theme('admin');
    }

    //--------------------------------------------------------------------

    public function test_asset_lib_loaded()
    {
        $this->assertTrue(class_exists('Assets'));
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // !UNCOMBINED TESTS
    //--------------------------------------------------------------------

    //--------------------------------------------------------------------

    public function test_css_with_string_passed_no_extension()
    {
        $r = Assets::css('screen');
        $this->assertIsA($r, 'string', 'Value = "'. htmlentities($r) .'"');
    }

    //--------------------------------------------------------------------

    public function test_css_returns_screen_style_when_empty()
    {
        $r = Assets::css();
        $this->assertTrue(strpos($r, 'link'));
    }

    //--------------------------------------------------------------------

    public function test_css_does_not_include_screen_with_string_passed()
    {
        $r = Assets::css('ui');
        $this->assertFalse(strpos($r, 'screen.css'), 'Value = "'. htmlentities($r) .'"');
    }

    //--------------------------------------------------------------------

    public function test_css_includes_screen_when_empty_passed()
    {
        $r = Assets::css();
        $this->assertTrue(strpos($r, 'screen.css'));
    }

    //--------------------------------------------------------------------

    public function test_css_with_array_passed()
    {
        $r = Assets::css( array('style1', 'style2') );
        $this->assertIsA($r, 'string','Value = "'. htmlentities($r) .'"');
    }

    //--------------------------------------------------------------------

    public function test_css_does_not_show_missing_files()
    {
        $r = Assets::css('abcdef');
        $this->assertFalse(strpos($r, 'abcdef'));
    }

    //--------------------------------------------------------------------

    public function test_css_includes_media_type()
    {
        $r = Assets::css();
        $this->assertTrue(strpos($r, 'media="screen"') !== false, 'Value = "'. htmlentities($r) .'"');
    }

    //--------------------------------------------------------------------

    public function test_css_includes_media_type_with_string_passed()
    {
        $r = Assets::css('screen');
        $this->assertTrue(strpos($r, 'media="screen"') !== false, 'Value = "'. htmlentities($r) .'"');
    }

    //--------------------------------------------------------------------

    public function test_add_css_includes_file()
    {
        touch(FCPATH .'themes/admin/css/null.css');

        Assets::add_css('null');
        $r = Assets::css();
        $this->assertTrue(strpos($r, 'null.css') !== false);
    }

    //--------------------------------------------------------------------

    public function test_add_css_includes_arrays()
    {
        touch(FCPATH .'themes/admin/css/null1.css');

        Assets::add_css( array('null', 'null1') );
        $r = Assets::css();
        $this->assertTrue(strpos($r, 'null.css') !== false && strpos($r, 'null1.css') !== false);

        unlink(FCPATH .'themes/admin/css/null.css');
        unlink(FCPATH .'themes/admin/css/null1.css');
    }

    //--------------------------------------------------------------------

    public function test_external_js_returns_script_tags()
    {
        $r = Assets::external_js();
        $this->assertTrue(strpos($r, '<script') !== false, 'Value = "'. htmlentities($r) .'"');
    }

    //--------------------------------------------------------------------

    public function test_external_js_does_not_include_default_when_scripts_passed_in()
    {
        $r = Assets::external_js('ui');
        $this->assertFalse(strpos($r, 'jquery'));
    }

    //--------------------------------------------------------------------

    public function test_external_js_includes_global()
    {
        $r = Assets::external_js();
        $this->assertTrue(strpos($r, 'global') !== false);
    }

    //--------------------------------------------------------------------

    public function test_inline_js_includes_text()
    {
        Assets::add_js('abcdef', 'inline');
        $r = Assets::inline_js();
        $this->assertTrue(strpos($r, 'abcdef') !== false);
    }

    //--------------------------------------------------------------------

    public function test_js_returns_inline_text()
    {
        $r = Assets::js();
        $this->assertTrue(strpos($r, 'abcdef') !== false);
    }

    //--------------------------------------------------------------------

    public function test_js_returns_external_links()
    {
        $r = Assets::js();
        $this->assertTrue(strpos($r, 'global') !== false);
    }

    //--------------------------------------------------------------------

    public function test_image_returns_img_tag()
    {
        $r = Assets::image('abcdef.png');
        $this->assertTrue(strpos($r, '<img') !== false);
    }

    //--------------------------------------------------------------------

    public function test_image_returns_correct_img()
    {
        $r = Assets::image('abcdef.png');
        $this->assertTrue(strpos($r, 'abcdef.png') !== false);
    }

    //--------------------------------------------------------------------

    public function test_image_returns_extra_attributes()
    {
        $r = Assets::image('abcdef.png', array('width' => 48));
        $this->assertTrue(strpos($r, 'width=') !== false);
    }

    //--------------------------------------------------------------------
}