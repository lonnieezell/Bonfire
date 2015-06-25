<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
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
 * UI Helper
 *
 * Manages the keyboard shortcuts used in the Bonfire admin interface.
 *
 * @package Bonfire\Modules\UI\Helpers\ui_helper
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/keyboard_shortcuts
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

//------------------------------------------------------------------------------
// Deprecated function(s) - do not use.
//------------------------------------------------------------------------------

if (! function_exists('render_search_box')) {
    /**
     * Display a search box.
     *
     * @deprecated since 0.7.2 use renderSearchBox().
     *
     * @return void
     */
    function render_search_box()
    {
        $ci =& get_instance();
        $search = $ci->lang->line('bf_action_search');

        // Although the defaults of renderSearchBox() are currently the same as
        // the values below, it is possible that the defaults for renderSearchBox()
        // could be changed before this function is removed.

        $searchLabel = "{$search}&hellip;";
        $searchPlaceholder = strtolower($search) . '&hellip;';
        $template = "<a href='#' class='list-search'>{searchLabel}</a>
<div id='search-form' style='display: none'>
    <input type='search' class='list-search' value='' placeholder='{searchPlaceholder}' />
</div>";
        renderSearchBox($template, $searchLabel, $searchPlaceholder);
    }
}
/* end /ui/helpers/ui_helper.php */
