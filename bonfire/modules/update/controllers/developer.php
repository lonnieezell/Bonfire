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

class Developer extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->auth->restrict('Site.Developer.View');

		$this->lang->load('update');

		Template::set('toolbar_title', lang('up_toolbar_title'));

		if (!function_exists('curl_version'))
		{
			Template::set('curl_disabled', 1);
		}
	}

	//--------------------------------------------------------------------

	public function index()
	{
		if ($this->settings_lib->item('updates.do_check') && function_exists('curl_version'))
		{
			$this->load->library('GitHub_lib');
			$this->load->helper('date');

			// Latest commits
			Template::set('commits', $this->github_lib->user_timeline('ci-bonfire', 'Bonfire', 'develop'));

			$tags = $this->github_lib->repo_refs('ci-bonfire', 'Bonfire');

			$version = 0.0;

			if (is_object($tags) && count($tags))
			{
				foreach ($tags as $tag => $ref)
				{
					if ($tag > $version)
					{
						$version = $tag;
					}
				}

				if (BONFIRE_VERSION === $version)
				{
					Template::set('update_message', sprintf(lang('up_update_message_latest'), '<b>'. BONFIRE_VERSION .'</b>'));
				}
				else
				{
					Template::set('update_message', sprintf(lang('up_update_message_old'), '<b>'. BONFIRE_VERSION .'</b>', '<b>'. $version .'</b>'));
				}
			}
			else
			{
				Template::set('update_message', sprintf(lang('up_update_message_unable'), '<b>'. BONFIRE_VERSION .'</b>'));
			}
		}

		Template::render();
	}

	//--------------------------------------------------------------------


}
