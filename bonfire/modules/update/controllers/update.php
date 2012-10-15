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

class Update extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('GitHub_lib');
		$this->lang->load('update');
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !HMVC METHODS
	//--------------------------------------------------------------------

	/*
		update_check()

		Checks with github for any Bonfire updates and notifies the develoepr.
	*/
	public function update_check()
	{
		if (!$this->settings_lib->item('updates.do_check') || !function_exists('curl_version'))
		{
			return;
		}

		$message = $this->cache->get('update_message');

		/*
			If they're living on the bleeding edge, then we need to find
			the latest commit reference and compare to what this installed
			version is at.
		*/
		if (empty($message))
		{
			if ($this->settings_lib->item('updates.bleeding_edge'))
			{
				$commits = $this->github_lib->user_timeline('ci-bonfire', 'Bonfire');

				if (is_array($commits))
				{
					$last_commit = $commits[0]->id;

					if ($last_commit !== $this->settings_lib->item('updates.last_commit'))
					{
						$message .= lang('up_update_message_bleeding');
					}
				}

				unset($commits, $last_commit);
			}

			/*
				Also check for major, tagged releases.
			*/
			$tags = $this->github_lib->repo_refs('ci-bonfire', 'Bonfire');

			if ($tags && is_array($tags))
			{
				foreach ($tags as $tag => $ref)
				{
					if ($tag > BONFIRE_VERSION)
					{
						$message .= sprintf(lang('up_update_message_new'), '<b>'. $tag .'</b>') . BONFIRE_VERSION;
						break;
					}
				}
			}

			unset($tags);

			// Cache the message for 1 hour
			$this->cache->save('update_message', $message, 60*60*24);
		}

		/*
			Show the message(s)
		*/
		if (!empty($message))
		{
			echo '<div class="notification attention">';
			echo $message .' <a href="'. site_url(SITE_AREA .'/developer/update') .'">View Updates</a>.';
			echo '</div>';
		}
	}

	//--------------------------------------------------------------------
}

// End Update/Developer controller
