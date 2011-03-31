<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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