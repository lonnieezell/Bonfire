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
	File: MY_date_helper
	
	Includes additional date-related functions helpful in Bonfire development.
*/


/*
	Function: relative_time()
	
	Takes a UNIX timestamp and returns a string representing how long ago
	that date was, like "moments ago", "2 weeks ago", etc.
	
	Parameters:
		$timestamp	- A UNIX timestamp
		
	Returns: 
		string	- a human-readable amount of time 'ago'
*/
if (!function_exists('relative_time'))
{
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
		} else 
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
		} else 
		{
			$text = "$difference $periods[$j] $ending";
		}
		
		return $text;
	}
}

//---------------------------------------------------------------

/*
	Function: date_difference
	
	Returns the difference between two dates. 
	
	Parameters:
		$start		- The start date in either unix timestamp or a format 
					  that can be used within strtotime().
		$end		- The ending date in either unix timestamp or a format 
					  that can be used within strtotime().
		$interval	- A string with the interval to use. Choices 'week', 'day', 'hour', 'minute'
		$reformat	- If TRUE, will reformat the time using strtotime()
		
	Returns:
		A number representing the difference between the two dates in the interval desired.
*/
if (!function_exists('date_difference'))
{
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
	}
}