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

/**
 * Date Helpers
 *
 * Includes additional date-related functions helpful in Bonfire development.
 *
 * @package    Bonfire
 * @subpackage Helpers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/address_helpers.html
 *
 */

if ( ! function_exists('relative_time'))
{
	/**
	 * Takes a UNIX timestamp and returns a string representing how long ago that date was, like "moments ago", "2 weeks ago", etc.
	 *
	 * @param $timestamp int A UNIX timestamp
	 *
	 * @return string A human-readable amount of time 'ago'
	 */
	function relative_time($timestamp)
	{
		$difference = time() - $timestamp;

		$periods = array("moment", "min", "hour", "day", "week",
		"month", "years", "decade");

		$lengths = array("60","60","24","7","4.35","12","10");

		if ($difference > 0)
		{
			// this was in the past
			$ending = "ago";
		}
		else
		{
			// this was in the future
			$difference = -$difference;
			$ending = "to go";
		}

		for ($j = 0; $difference >= $lengths[$j]; $j++)
		{
			$difference /= $lengths[$j];
		}

		$difference = round($difference);

		if ($difference != 1)
		{
			$periods[$j].= "s";
		}

		if ($difference < 60 && $j == 0)
		{
			$text = "$periods[$j] $ending";
		}
		else
		{
			$text = "$difference $periods[$j] $ending";
		}

		return $text;

	}//end relative_time()
}

//---------------------------------------------------------------

if (!function_exists('date_difference'))
{
	/**
	 * Returns the difference between two dates.
	 *
	 * @param $start mixed The start date in either unix timestamp or a format that can be used within strtotime().
	 * @param $end mixed The ending date in either unix timestamp or a format that can be used within strtotime().
	 * @param $interval string A string with the interval to use. Choices 'week', 'day', 'hour', 'minute'.
	 * @param $reformat boolean If TRUE, will reformat the time using strtotime().
	 *
	 * @return int A number representing the difference between the two dates in the interval desired.
	 */
	function date_difference($start=null, $end=null, $interval='day', $reformat=false)
	{
		if (is_null($start))
		{
			return false;
		}

		if (is_null($end))
		{
			$end = date('Y-m-d H:i:s');
		}

		$times = array(
			'week'		=> 604800,
			'day'		=> 86400,
			'hour'		=> 3600,
			'minute'	=> 60
		);

		if ($reformat === true)
		{
			$start 	= strtotime($start);
			$end	= strtotime($end);
		}

		$diff = $end - $start;

		return round($diff / $times[$interval]);

	}//end date_difference()
}

//---------------------------------------------------------------

if ( ! function_exists('user_time'))
{
	/**
	 * Converts unix time to a human readable time in user timezone
	 * or in a given timezone.
	 *
	 * For supported timezones visit - http://php.net/manual/timezones.php
	 * For accepted formats visit - http://php.net/manual/function.date.php
	 *
	 * @example echo user_time();
	 * @example echo user_time($timestamp, 'EET', 'l jS \of F Y h:i:s A');
	 *
	 * @param int    $timestamp A UNIX timestamp. If non is given current time will be used.
	 * @param string $timezone  The timezone we want to convert to. If none is given a current logged user timezone will be used.
	 * @param string $format    The format of the outputted date/time string
	 *
	 * @return string A string formatted according to the given format using the given timestamp and given timezone or the current time if no timestamp is given.
	 */
	function user_time($timestamp = NULL, $timezone = NULL, $format = 'r')
	{
		if ( ! $timezone)
		{
			$CI =& get_instance();
			$CI->load->library('users/auth');
			if ($CI->auth->is_logged_in())
			{
				$current_user = $CI->user_model->find($CI->auth->user_id());
				$timezone = $current_user->timezone;
			}
		}

		$timestamp = ($timestamp) ? $timestamp : time();

		$dtzone = new DateTimeZone($timezone);
		$dtime = new DateTime();

		return $dtime->setTimestamp($timestamp)->setTimeZone($dtzone)->format($format);

	}//end user_time()
}