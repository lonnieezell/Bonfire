<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Assets_test extends Unit_Tester {

	public function pre() 
	{
		if (!class_exists('Assets'))
		{
			$this->load->library('Assets');
		}
		
		// Reset our combined status to false
		$this->ci->config->set_item('assets.combine', false);
	}
	
	//--------------------------------------------------------------------
	// !UNCOMBINED TESTS
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	
	public function test_css_with_string_passed_no_extension() 
	{
		$r = Assets::css('screen');
		$this->assert_is_type($r, 'string', 'Value = "'. htmlentities($r) .'"');
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_returns_screen_style_when_empty() 
	{
		$r = Assets::css();
		$this->assert_true(strpos($r, 'link'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_does_not_include_screen_with_string_passed() 
	{
		$r = Assets::css('ui');
		$this->assert_false(strpos($r, 'screen.css'), 'Value = "'. htmlentities($r) .'"');
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_includes_screen_when_empty_passed() 
	{
		$r = Assets::css();
		$this->assert_true(strpos($r, 'screen.css'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_with_array_passed() 
	{
		$r = Assets::css( array('style1', 'style2') );
		$this->assert_is_type($r, 'string','Value = "'. htmlentities($r) .'"');
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_does_not_show_missing_files() 
	{
		$r = Assets::css('abcdef');
		$this->assert_false(strpos($r, 'abcdef'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_includes_media_type() 
	{
		$r = Assets::css();
		$this->assert_true(strpos($r, 'media="screen"') !== false, 'Value = "'. htmlentities($r) .'"');
	}
	
	//--------------------------------------------------------------------
	
	public function test_css_includes_media_type_with_string_passed() 
	{
		$r = Assets::css('screen');
		$this->assert_true(strpos($r, 'media="screen"') !== false, 'Value = "'. htmlentities($r) .'"');
	}
	
	//--------------------------------------------------------------------
	
	public function test_add_css_includes_file() 
	{
		Assets::add_css('null');
		$r = Assets::css();
		$this->assert_true(strpos($r, 'null.css') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_add_css_includes_arrays() 
	{
		Assets::add_css( array('null', 'null1') );
		$r = Assets::css();
		$this->assert_true(strpos($r, 'null.css') !== false && strpos($r, 'null1.css') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_external_js_returns_script_tags() 
	{
		$r = Assets::external_js();
		$this->assert_true(strpos($r, '<script') !== false, 'Value = "'. htmlentities($r) .'"');
	}
	
	//--------------------------------------------------------------------
	
	public function test_external_js_does_not_include_default_when_scripts_passed_in() 
	{
		$r = Assets::external_js('ui');
		$this->assert_false(strpos($r, 'jquery'));
	}
	
	//--------------------------------------------------------------------
	
	public function test_external_js_includes_global() 
	{
		$r = Assets::external_js();
		$this->assert_true(strpos($r, 'global') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_inline_js_includes_text() 
	{
		Assets::add_js('abcdef', 'inline');
		$r = Assets::inline_js();
		$this->assert_true(strpos($r, 'abcdef') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_js_returns_inline_text() 
	{
		$r = Assets::js();
		$this->assert_true(strpos($r, 'abcdef') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_js_returns_external_links() 
	{
		$r = Assets::js();
		$this->assert_true(strpos($r, 'global') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_image_returns_img_tag() 
	{
		$r = Assets::image('abcdef.png');
		$this->assert_true(strpos($r, '<img') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_image_returns_correct_img() 
	{
		$r = Assets::image('abcdef.png');
		$this->assert_true(strpos($r, 'abcdef.png') !== false);
	}
	
	//--------------------------------------------------------------------
	
	public function test_image_returns_extra_attributes() 
	{
		$r = Assets::image('abcdef.png', array('width' => 48));
		$this->assert_true(strpos($r, 'width=') !== false);
	}
	
	//--------------------------------------------------------------------
	
	//--------------------------------------------------------------------
	// !COMBINED TESTS
	//--------------------------------------------------------------------
	
	
}