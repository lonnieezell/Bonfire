<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class bf_date {
	public static function date($format, $timestamp="") {
		return date($format, $timestamp);
	}

	public static function strftime($format, $timestamp="") {
		return strftime($format,$timestamp);
	}

	public static function mktime($hour=0, $minute=0, $second=0, $month=0, $day=0, $year=0, $is_dst=-1) {
		return mktime($hour, $minute, $second, $month, $day, $year, $is_dst);
	}

	public static function checkdate($month, $day, $year) {
		return checkdate($month, $day, $year);
	}

	public static function getdate($timestamp="") {
		getdate($timestamp);
	}
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