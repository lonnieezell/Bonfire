<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * UI Helper
 *
 * @package    Bonfire
 * @subpackage Modules_Ui
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/file_helpers.html
 *
 */

if (!function_exists('render_search_box'))
{
	/**
	 * Displays a search box
	 *
	 * @access public
	 *
	 * @return void
	 */
	function render_search_box()
	{
		$ci =& get_instance();
		$search = $ci->lang->line('bf_action_search');
		$search_lower = strtolower($search);

		$form =<<<END
<a href="#" class="list-search">{$search}...</a>

<div id="search-form" style="display: none">
	<input type="search" class="list-search" value="" placeholder="{$search_lower}..."  />
</div>
END;

		echo $form;

	}//end render_search_box()
}

//--------------------------------------------------------------------
