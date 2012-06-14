<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class bf_date {
	public static function date($format, $timestamp="") {

		$ci =& get_instance();
		$ci->lang->load('calendar');

		$patterns = array();
		$replacements = array();
		
		// Short day name ?
		if (preg_match('/D/', $format, $matches))
		{
			$patterns[0] = '/' . date('D', $timestamp) . '/';
			$replacements[0] = $ci->lang->line('cal_' . strtolower(date('D', $timestamp)));
		}
		
		// Long day name ?
		if (preg_match('/l/', $format, $matches))
		{
			$patterns[1] = '/' . date('l', $timestamp) . '/';
			$replacements[1] = $ci->lang->line('cal_' . strtolower(date('l', $timestamp)));
		}

		// Short month name ?
		if (preg_match('/M/', $format, $matches))
		{
			$patterns[2] = '/' . date('M', $timestamp) . '/';
			$replacements[2] = strtolower($ci->lang->line('cal_' . strtolower(date('M', $timestamp))));
		}
		
		// Long month name ?
		if (preg_match('/F/', $format, $matches))
		{
			$patterns[3] = '/' . date('F', $timestamp) . '/';
			$replacements[3] = strtolower($ci->lang->line('cal_' . strtolower(date('F', $timestamp))));
		}

		ksort($patterns);
		ksort($replacements);

		return preg_replace($patterns, $replacements, date($format, $timestamp));
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

		$periods = array("moment", "minute", "heure", "jour", "semaine",
		"mois", "années", "décade");

		$lengths = array("60","60","24","7","4.35","12","10");

		if ($difference > 0)
		{
			// this was in the past
			$beginning = "Il y a";
		}
		else
		{
			// this was in the future
			$difference = -$difference;
			$beginning = "Il y aura";
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
			$text = "$beginning $periods[$j]";
		}
		else
		{
			$text = "$beginning $difference $periods[$j]";
		}

		return $text;

	}//end relative_time()	
}