<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('render_search_box'))
{
	function render_search_box()
	{
		$form =<<<END
<a href="#" class="list-search">Search...</a>
			
<div id="search-form" style="display: none">
	<input type="search" class="list-search" value="" placeholder="search..."  />
</div>
END;
	
		echo $form;
	}
}