<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * UI Helper
 *
 * Manages the keyboard shortcuts used in the Bonfire admin interface.
 *
 * @package    Bonfire\Modules\UI\Helpers\ui_helper
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/keyboard_shortcuts
 */

if ( ! function_exists('render_search_box')) {
	/**
	 * Display a search box
	 *
	 * @return void
	 */
	function render_search_box()
	{
		$ci =& get_instance();
		$search = $ci->lang->line('bf_action_search');
		$search_lower = strtolower($search);

		echo "<a href='#' class='list-search'>{$search}...</a>
<div id='search-form' style='display: none'>
    <input type='search' class='list-search' value='' placeholder='{$search_lower}...' />
</div>";
	}
}
/* end /ui/helpers/ui_helper.php */