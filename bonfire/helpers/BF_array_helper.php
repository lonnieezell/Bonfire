<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Array helper functions.
 *
 * Provides additional functions for working with arrays.
 *
 * @package    Bonfire\Helpers\BF_array_helper
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/developer
 */

if ( ! function_exists('array_index_by_key')) {
	/**
	 * When given an array of arrays (or objects), return the index of the
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
	 * @param mixed $key   The key to search on.
	 * @param mixed $value The value the key should be.
	 * @param array $array The array to search through.
	 * @param bool  $identical Whether to perform a strict (type-checked)
	 * comparison.
	 *
	 * @return false|int The index of the sub-array, or false.
	 */
	function array_index_by_key($key = null, $value = null, $array = null, $identical = false)
	{
		if (empty($key) || empty($value) || ! is_array($array)) {
			return false;
		}

		foreach ($array as $index => $subArray) {
			$subArray = (array) $subArray;

			if (array_key_exists($key, $subArray)) {
				if ($identical) {
					if ($subArray[$key] === $value) {
						return $index;
					}
				} else {
					if ($subArray[$key] == $value) {
						return $index;
					}
				}
			}
		}

		return false;
	}
}

if ( ! function_exists('array_multi_sort_by_column')) {
	/**
	 * Sort a multi-dimensional array by a column in the sub array.
	 *
	 * @param array  $arr Array to sort.
	 * @param string $col The name of the column to sort by.
	 * @param int    $dir The sort direction SORT_ASC or SORT_DESC.
	 *
	 * @return void/bool Returns false on invalid input.
	 */
	function array_multi_sort_by_column(&$arr, $col, $dir = SORT_ASC)
	{
		if (empty($col) || ! is_array($arr)) {
			return false;
		}

		$sortCol = array();
		foreach ($arr as $key => $row) {
			$sortCol[$key] = $row[$col];
		}

		array_multisort($sortCol, $dir, $arr);
	}
}
/* End /helpers/BF_array_helper.php */