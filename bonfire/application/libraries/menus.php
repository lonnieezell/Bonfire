<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menus {

	/**
	 * An instance of the CI app
	 *
	 * @var 	object
	 * @access	protected
	 */
	protected static $ci;
	
	/**
	 * Stores our actual menus.
	 */
	private static $menus = array();

	//--------------------------------------------------------------------
	
	/**
	 * Constructor.
	 * 
	 * This if here solely for CI loading to work. Just calls the init( ) method.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
	
		self::init();
	}

	//--------------------------------------------------------------------
	
	public static function init() 
	{
		self::$ci->load->helper('config_file');
		
		self::$menus = read_config('menus');
	
		log_message('debug', 'Assets library loaded.');
	}
	
	//--------------------------------------------------------------------
	
}