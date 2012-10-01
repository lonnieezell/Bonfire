/*
 * codeigniter-csrf.js - Read the CodeIgniter CSRF cookie from javascript,
 *                       useful for sending POST requests with AJAX.
 * 
 * readCookie() copied verbatim from http://www.quirksmode.org/js/cookies.html
 * Thanks to Peter-Paul Koch, and Scott Andrew
 * 
 * The rest of ci_csrf_token() is trivial.  Please feel free to copy it!
 * Written 2012 by Alan Jenkins <alan.christopher.jenkins@gmail.com>.
 */
function ci_csrf_token()
{
	// Match the config of the same name in application/config/config.php
	var csrf_cookie_name = "ci_csrf_token";

	return readCookie(csrf_cookie_name);

	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
}
