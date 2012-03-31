<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

if (!function_exists('render_search_box'))
{
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
	}
}

//--------------------------------------------------------------------

/*
	Function: render filter_first_letter()

	Displays an alpha list used to filter a list by first letter.

	Parameters:
		$caption	- A string with the text to display before the list.
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
	else {
		$current = '';
	}

	// Build our url
	if (is_array($params))
	{
		$url = current_url() .'?'. array_implode('=', '&', $params);
	} else
	{
		$url = current_url() .'?';
	}

	// If there's a current filter, we need to
	// replace the caption with a clear button.
	if (!empty($current))
	{
		$out .= '<a href="'. $url .'" class="btn btn-small btn-primary">'. lang('bf_clear') .'</a>';
	} else
	{
		$out .= $caption;
	}

	// Source
	$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

	// Create our list.
	foreach ($letters as $letter)
	{
		$out .= '<a href="'. $url .'&firstletter='. strtolower($letter) .'">';
		$out .= $letter;
		$out .= '</a>';
	}

	$out .= '</span>';

	echo $out;
}

//--------------------------------------------------------------------
