<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers get a jumpstart their development of CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2013, Bonfire Dev Team
 * @license   http://guides.cibonfire.com/license.html
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Array Helpers
 *
 * Provides additional functions for working with arrays.
 *
 * @package    Bonfire
 * @subpackage Helpers
 * @category   Helpers
 * @author     Bonfire Dev Team
 * @link       http://guides.cibonfire.com/helpers/array_helpers.html
 *
 */

if ( ! function_exists('array_index_by_key'))
{

	/**
	 * When given an array of arrays (or objects) it will return the index of the
	 * sub-array where $key == $value.
	 *
	 * <code>
	 * $array = array(
	 *	array('value' => 1),
	 *	array('value' => 2),
	 * );
	 *
	 * // Returns 1
	 * array_index_by_key('value', 2, $array);
	 * </code>
	 *
	 * @param $key mixed The key to search on.
	 * @param $value The value the key should be
	 * @param $array array The array to search through
	 * @param $identical boolean Whether to perform a strict type-checked comparison
	 *
	 * @return false|int An INT that is the index of the sub-array, or false.
	 */
	function array_index_by_key($key=null, $value=null, $array=null, $identical=false)
	{
		if (empty($key) || empty($value) || !is_array($array))
		{
			return false;
		}

		foreach ($array as $index => $sub_array)
		{
			$sub_array = (array)$sub_array;

			if (array_key_exists($key, $sub_array))
			{
				if ($identical)
				{
					if ($sub_array[$key] === $value)
					{
						return $index;
					}
				}
				else
				{
					if ($sub_array[$key] == $value)
					{
						return $index;
					}
				}
			}
		}//end foreach

		return FALSE;
	}//end array_index_by_key()
}

if (!function_exists('array_multi_sort_by_column'))
{
	/**
	 * Sort a multi-dimensional array by a column in the sub array
	 *
	 * @param array  $arr Array to sort
	 * @param string $col The name of the column to sort by
	 * @param int    $dir The sort directtion SORT_ASC or SORT_DESC
	 *
	 * @return void
	 */
	function array_multi_sort_by_column(&$arr, $col, $dir = SORT_ASC)
	{
		if (empty($col) || !is_array($arr))
		{
			return false;
		}

		$sort_col = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
		}

		array_multisort($sort_col, $dir, $arr);
	}//end array_multi_sort_by_column()
}
