<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class bf_date {
	private static $pdateWeekName = array(
	    "شنبه",
	    "یکشنبه",
	    "دوشنبه",
	    "سه شنبه",
	    "چهارشنبه",
	    "پنج شنبه",
	    "جمعه");
	private static $pdateMonthName = array(
	    "",
	    "فروردین",
	    "اردیبهشت",
	    "خرداد",
	    "تیر",
	    "مرداد",
	    "شهریور",
	    "مهر",
	    "آبان",
	    "آذر",
	    "دی",
	    "بهمن",
	    "اسفند");
	private static $MonthDays = array(0, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

	public static function date($format, $timestamp="") {
		if ($timestamp === "") {
			$timestamp = time();
		}

		// Create need date parametrs
		$date = date("Y-m-d-w", $timestamp);
		list( $gYear, $gMonth, $gDay, $gWeek) = explode('-', $date);
		list( $pYear, $pMonth, $pDay ) = self::gregorian_to_jalali($gYear, $gMonth, $gDay);
		$pWeek = $gWeek + 1;
		if ($pWeek == 7)
			$pWeek = 0;

		$lenghFormat = strlen($format);
		$i = 0;
		$result = "";
		while ($i < $lenghFormat) {
			$par = $format{$i};
			if ($par == '\\') {
				$result.=$format{++$i};
				$i++;
				continue;
			}
			switch ($par) {
				//Day
				case 'd':
					$result.=($pDay < 10) ? "0" . $pDay : $pDay;
					break;

				case 'D':
					$result.=substr(self::$pdateWeekName[$pWeek], 0, 2);
					break;

				case 'j':
					$result.=$pDay;
					break;

				case 'l':
					$result.=self::$pdateWeekName[$pWeek];
					break;

				case 'N':
					$result.=$pWeek + 1;
					break;

				case 'w':
					$result.=$pWeek;
					break;

				case 'z':
					$result.=self::DayOfYear($pYear, $pMonth, $pDay);
					break;

				case 'S':
					$result.="م";
					break;

				//Week
				case 'W':
					$result.=ceil(self::DayOfYear($pYear, $pMonth, $pDay) / 7);
					break;

				//Month
				case 'F':
					$result.=self::$pdateMonthName[$pMonth];
					break;

				case 'm':
					$result.=($pMonth < 10) ? "0" . $pMonth : $pMonth;
					break;

				case 'M':
					$result.=substr(self::$pdateMonthName[$pMonth], 0, 6);
					break;

				case 'n':
					$result.=$pMonth;
					break;

				case 't':
					$result.=(self::isKabise($pYear) and $pMonth == 12) ? 30 : self::$MonthDays[$pMonth];
					break;

				//Years
				case 'L':
					$result.=(int) self::isKabise($pYear);
					break;

				case 'Y':
				case 'o':
					$result.=$pYear;
					break;

				case 'y':
					$result.=substr($pYear, 2);
					break;

				//Time
				case 'a':
				case 'A':
					if (date('a', $timestamp) == 'am') {
						$result.=($par == 'a') ? 'ق.ظ' : 'قبل از ظهر';
					} else {
						$result.=($par == 'a') ? 'ب.ظ' : 'بعد از ظهر';
					}
					break;

				case 'B':
				case 'g':
				case 'G':
				case 'h':
				case 'H':
				case 's':
				case 'u':
				case 'i':

				//Timezone
				case 'e':
				case 'I':
				case 'O':
				case 'P':
				case 'T':
				case 'Z':
					$result.=date($par, $timestamp);
					break;

				//Full Date/Time

				case 'c':
					$result.=$pYear . "-" . $pMonth . "-" . $pDay . "T" . date("H::i:sP", $timestamp);
					break;

				case 'r':
					$result.=substr(self::$pdateWeekName[$pWeek], 0, 2) . "، " . $pDay . " " . substr(self::$pdateMonthName[$pMonth], 0, 6) . " " . $pYear . " " . date("H::i:s P", $timestamp);
					break;
				case 'U':
					$result.=$timestamp;
					break;
				default:
					$result.=$par;
			}
			$i++;
		}
		return $result;
	}

	public static function strftime($format, $timestamp="") {
		if ($timestamp === "") {
			$timestamp = time();
		}
		// Create need date parametrs
		$date = date("Y-m-d-w", $timestamp);
		list( $gYear, $gMonth, $gDay, $gWeek) = explode('-', $date);
		list( $pYear, $pMonth, $pDay ) = self::gregorian_to_jalali($gYear, $gMonth, $gDay);
		$pWeek = $gWeek + 1;
		if ($pWeek == 7)
			$pWeek = 0;

		$lenghFormat = strlen($format);
		$i = 0;
		$result = "";
		while ($i < $lenghFormat) {
			$par = $format{$i};
			if ($par == "%") {
				$type = $format{++$i};
				switch ($type) {
					//Day
					case 'a':
						$result.=substr(self::$pdateWeekName[$pWeek], 0, 2);
						break;

					case 'A':
						$result.=self::$pdateWeekName[$pWeek];
						break;

					case 'd':
						$result.=($pDay < 10) ? "0" . $pDay : $pDay;
						break;

					case 'e':
						$result.=$pDay;
						break;

					case 'j':
						$dayinM = self::DayOfYear($pYear, $pMonth, $pDay);
						$result.=($dayinM < 10) ? "00" . $dayinM : (($dayinM < 100) ? "0" . $dayinM : $dayinM);
						break;

					case 'u':
						$result.=$pWeek + 1;
						break;

					case 'w':
						$result.=$pWeek;
						break;

					//Week
					case 'U':
						$result.=floor(self::DayOfYear($pYear, $pMonth, $pDay) / 7);
						break;

					case 'V':
					case 'W':
						$result.=ceil(self::DayOfYear($pYear, $pMonth, $pDay) / 7);
						break;

					//Month
					case 'b':
					case 'h':
						$result.=substr(self::$pdateMonthName[$pMonth], 0, 6);
						break;

					case 'B':
						$result.=self::$pdateMonthName[$pMonth];
						break;

					case 'm':
						$result.=($pMonth < 10) ? "0" . $pMonth : $pMonth;
						break;

					//Year
					case 'C':
						$result.=ceil($pYear / 100);
						break;

					case 'g':
					case 'y':
						$result.=substr($pYear, 2);
						break;

					case 'G':
					case 'Y':
						$result.=$pYear;
						break;

					//Time
					case 'H':
					case 'I':
					case 'l':
					case 'M':
					case 'R':
					case 'S':
					case 'T':
					case 'X':
					case 'z':
					case 'Z':
						$result.=strftime("%" . $type, $timestamp);
						break;
					case 'p':
					case 'P':
					case 'r':
						if (date('a', $timestamp) == 'am') {
							$result.=($type == 'p') ? 'ق.ظ' : (($type == 'P') ? 'قبل از ظهر' : strftime("%I:%M:%S قبل از ظهر", $timestamp));
						} else {
							$result.=($type == 'p') ? 'ب.ظ' : (($type == 'P') ? 'بعد از ظهر' : strftime("%I:%M:%S بعد از ظهر", $timestamp));
						}
						break;

					//Time and Date Stamps
					case 'c':
						$result.=substr(self::$pdateWeekName[$pWeek], 0, 2) . " " . substr(self::$pdateMonthName[$pMonth], 0, 6) . " " . $pDay . " " . strftime("%T", $timestamp) . " " . $pYear;
						break;

					case 'D':
					case 'x':
						$result.=(($pMonth < 10) ? "0" . $pMonth : $pMonth) . "/" . (($pDay < 10) ? "0" . $pDay : $pDay) . "/" . substr($pYear, 2);
						break;

					case 'F':
						$result.=$pYear . "-" . (($pMonth < 10) ? "0" . $pMonth : $pMonth) . "-" . (($pDay < 10) ? "0" . $pDay : $pDay);
						break;

					case 's':
						$result.=$timestamp;
						break;

					//Miscellaneous
					case 'n':
						$result.="\n";
						break;

					case 't':
						$result.="\t";
						break;

					case '%':
						$result.="%";
						break;

					default: $result.="%" . $type;
				}
			} else {
				$result.=$par;
			}
			$i++;
		}
		return $result;
	}

	private static function DayOfYear($pYear, $pMonth, $pDay) {
		$days = 0;
		for ($i = 1; $i < $pMonth; $i++) {
			$days+=self::$MonthDays[$i];
		}
		return $days + $pDay;
	}

	private static function isKabise($year) {
		$mod = $year % 33;
		if ($mod == 1 or $mod == 5 or $mod == 9 or $mod == 13 or $mod == 17 or $mod == 22 or $mod == 26 or $mod == 30)
			return true;
		return false;
	}

	public static function mktime($hour=0, $minute=0, $second=0, $month=0, $day=0, $year=0, $is_dst=-1) {

		if ($hour == 0 && $minute == 0 && $second == 0 && $month == 0 && $day == 0 && $year == 0)
			return time();

		list($year, $month, $day) = self::jalali_to_gregorian($year, $month, $day);

		return mktime($hour, $minute, $second, $month, $day, $year, $is_dst);
	}

	public static function checkdate($month, $day, $year) {
		if ($month < 1 || $month > 12 || $year < 1 || $year > 32767 || $day < 1) {
			return false;
		}
		if ($day > self::$MonthDays[$month]) {
			if ($month != 12 || $day != 30 || !self::isKabise($year)) {
				return false;
			}
		}
		return true;
	}

	public static function getdate($timestamp="") {
		if ($timestamp === "")
			$timestamp = mktime();
		list($seconds, $minutes, $hours, $mday, $wday, $mon, $year, $yday, $weekday, $month) = explode("-", self::c_date("s-i-G-j-w-n-Y-z-l-F", $timestamp));
		return array(
		    0 => $timestamp,
		    "seconds" => $seconds,
		    "minutes" => $minutes,
		    "hours" => $hours,
		    "mday" => $mday,
		    "wday" => $wday,
		    "mon" => $mon,
		    "year" => $year,
		    "yday" => $yday,
		    "weekday" => $weekday,
		    "month" => $month,
		);
	}
	private static function div($a, $b) {
		return (int) ($a / $b);
	}

	private static function gregorian_to_jalali($g_y, $g_m, $g_d) {
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);





		$gy = $g_y - 1600;
		$gm = $g_m - 1;
		$gd = $g_d - 1;

		$g_day_no = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);

		for ($i = 0; $i < $gm; ++$i)
			$g_day_no += $g_days_in_month[$i];
		if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
		/* leap and after Feb */
			$g_day_no++;
		$g_day_no += $gd;

		$j_day_no = $g_day_no - 79;

		$j_np = self::div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */
		$j_day_no = $j_day_no % 12053;

		$jy = 979 + 33 * $j_np + 4 * self::div($j_day_no, 1461); /* 1461 = 365*4 + 4/4 */

		$j_day_no %= 1461;

		if ($j_day_no >= 366) {
			$jy += self::div($j_day_no - 1, 365);
			$j_day_no = ($j_day_no - 1) % 365;
		}

		for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
			$j_day_no -= $j_days_in_month[$i];
		$jm = $i + 1;
		$jd = $j_day_no + 1;

		return array($jy, $jm, $jd);
	}

	private static function jalali_to_gregorian($j_y, $j_m, $j_d) {
		$g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		$j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);



		$jy = $j_y - 979;
		$jm = $j_m - 1;
		$jd = $j_d - 1;

		$j_day_no = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
		for ($i = 0; $i < $jm; ++$i)
			$j_day_no += $j_days_in_month[$i];

		$j_day_no += $jd;

		$g_day_no = $j_day_no + 79;

		$gy = 1600 + 400 * self::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
		$g_day_no = $g_day_no % 146097;

		$leap = true;
		if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
			$g_day_no--;
			$gy += 100 * self::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
			$g_day_no = $g_day_no % 36524;

			if ($g_day_no >= 365)
				$g_day_no++;
			else
				$leap = false;
		}

		$gy += 4 * self::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
		$g_day_no %= 1461;

		if ($g_day_no >= 366) {
			$leap = false;

			$g_day_no--;
			$gy += self::div($g_day_no, 365);
			$g_day_no = $g_day_no % 365;
		}

		for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
			$g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
		$gm = $i + 1;
		$gd = $g_day_no + 1;

		return array($gy, $gm, $gd);
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