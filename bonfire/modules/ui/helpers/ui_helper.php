<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * UI Helper
 *
 * @package Bonfire\Modules\UI\Helpers\ui_helper
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs
 */

if (! function_exists('renderSearchBox')) {
    /**
     * Display a search box.
     *
     * @return void
     */
    function renderSearchBox($template = '', $searchLabel = '', $searchPlaceholder = '')
    {
        $ci =& get_instance();

        // Handle any empty arguments.
        if (empty($searchLabel) || empty($searchPlaceholder)) {
            $search            = $ci->lang->line('bf_action_search');
            $searchLabel       = empty($searchLabel) ? "{$search}&hellip;" : $searchLabel;
            $searchPlaceholder = empty($searchPlaceholder) ? strtolower($search) . '&hellip;' : $searchPlaceholder;
        }

        if (empty($template)) {
            $template = "<a href='#' class='list-search'>{searchLabel}</a>
<div id='search-form' style='display: none'>
    <input type='search' class='list-search' value='' placeholder='{searchPlaceholder}' />
</div>";
        }

        // Allow use of a localized label/placeholder with the 'lang:' prefix.
        if (strpos($searchLabel, 'lang:') === 0) {
            $searchLabel = $ci->lang->line(str_replace('lang:', '', $searchLabel));
        }

        if (strpos($searchPlaceholder, 'lang:') === 0) {
            $searchPlaceholder = $ci->lang->line(str_replace('lang:', '', $searchPlaceholder));
        }

        echo str_replace(
            array('{searchLabel}', '{searchPlaceholder}'),
            array($searchLabel, $searchPlaceholder),
            $template
        );
    }
}
