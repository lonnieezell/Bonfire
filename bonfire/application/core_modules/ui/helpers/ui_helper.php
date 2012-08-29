<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2012, Bonfire Dev Team
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

if (!function_exists('render_filter_first_letter'))
{
	/**
	 * Displays an alpha list used to filter a list by first letter.
	 *
	 * @access public
	 *
	 * @param string $caption A string with the text to display before the list.
	 *
	 * @return void
	 */
	function render_filter_first_letter($caption=null)
	{
		$ci =& get_instance();

		$out = '<span class="filter-link-list">';

		// All get params
		$params = $ci->input->get();

		// Current Filter
		if (isset($params['firstletter']))
		{
			$current = strtolower($params['firstletter']);
			unset($params['firstletter']);
		}
		else
		{
			$current = '';
		}

		// Build our url
		if (is_array($params))
		{
			$url_params = array();

			foreach ($params as $key => $value)
			{
				$url_params[urlencode($key)] = urlencode($value);
			}
			$url = current_url() .'?'. array_implode('=', '&', $url_params);
		}
		else
		{
			$url = current_url() .'?';
		}

		// If there's a current filter, we need to
		// replace the caption with a clear button.
		if (!empty($current))
		{
			$href = htmlentities($url, ENT_QUOTES, 'UTF-8');

			$out .= '<a href="'. $href .'" class="btn btn-small btn-primary">'. lang('bf_clear') .'</a>';
		}
		else
		{
			$out .= $caption;
		}

		// Source
		$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

		// Create our list.
		foreach ($letters as $letter)
		{
			$href_url = $url . '&firstletter='. strtolower($letter);
			$href = htmlentities($href_url, ENT_QUOTES, 'UTF-8');

			$out .= '<a href="'. $href .'">';
			$out .= $letter;
			$out .= '</a>';
		}

		$out .= '</span>';

		echo $out;

	}//end render_filter_first_letter()

}
//--------------------------------------------------------------------
