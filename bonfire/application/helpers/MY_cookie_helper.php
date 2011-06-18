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

/*
	File: MY_cookie_helper

	Includes additional cookie-related functions helpful in Bonfire development.
*/



/*
	method: set_redirect_cookie()

	Creates a cookie with a URI to redirect to after user login

	parameters:
	$redirect_uri - the URI
*/
if (!function_exists('set_redirect_cookie'))
{
	function set_redirect_cookie($redirect_uri)
	{
		// Detect login page, and avoid setting it as the redirect_uri
		$is_login_uri = preg_match('/login$/', $redirect_uri) ? true : false;

		// --- Correct for silly bug ---
		// TODO Search for better solution
		//
		// Prevent a request for the login-page from changing the redirect_uri on the first try
		// if not, redirect('login') would always rewrite a fresh cookie since it would be the
		// second of a 2 hit request !!!
		$ci =& get_instance();
		$current_uri_is_login_page = preg_match('/login$/', $ci->uri->uri_string()) ? true : false;
		if ($current_uri_is_login_page && (get_cookie('bf_redirect_cookie') === false) && (get_cookie('bf_login_sent') === false))
		{
			$redirect_cookie = array(
				'name'   => 'bf_login_sent',
				'value'  => '1',
				'expire' => 0
			);
			set_cookie($redirect_cookie);
			return;
		}
		else if ($current_uri_is_login_page)
		{
			delete_cookie('bf_login_sent');
		}
		// --- End Correction for silly bug ---

		// Do not redirect to any login url
		if (!$is_login_uri)
		{
			$redirect_cookie = array(
				'name'   => 'bf_login_redirect',
				'value'  => $redirect_uri,
				'expire' => 0
			);
			set_cookie($redirect_cookie);
		}
	}
}

/*
	method: delete_redirect_cookie()

	Deletes the cookie(s) the cookies set by set_redirecti_cookie

	parameters:
*/
if (!function_exists('delete_redirect_cookie'))
{
	function delete_redirect_cookie()
	{
		delete_cookie('bf_login_redirect');
		delete_cookie('bf_login_sent');
	}
}

//---------------------------------------------------------------
