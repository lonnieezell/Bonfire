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
 */

/**
 * Contexts Library
 *
 * Provides helper methods for displaying Context Navigation.
 *
 * @package Bonfire\Core\Modules\UI\Libraries\Contexts
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/contexts
 */
class Contexts
{
    /*
     * Templates and related strings for building the context menus
     */
    protected static $templateContextNav  = "<ul class='{class}'{extra}>\n{menu}</ul>\n";
    protected static $templateContextMenu = "<li class='{parent_class}'><a href='{url}' id='{id}' class='{current_class}' title='{title}'{extra}>{text}</a>{content}</li>\n";
    protected static $templateMenu        = "<li><a {extra}href='{url}' title='{title}'>{display}</a>\n</li>\n";
    protected static $templateSubMenu     = "<li class='{submenu_class}'><a href='{url}'>{display}</a><ul class='{child_class}'>{view}</ul></li>\n";

    protected static $templateContextEnd             = "<span class='caret'></span>";
    protected static $templateContextImage           = "<img src='{image}' alt='{title}' />";
    protected static $templateContextText            = "{title}";
    protected static $templateContextMenuAnchorClass = 'dropdown-toggle';
    protected static $templateContextMenuExtra       = " data-toggle='dropdown' data-id='{dataId}_menu'";
    protected static $templateContextNavMobileClass  = 'mobile_nav';

    /** @var string The class name to attach to the outer ul tag. */
    protected static $outer_class = 'nav';

    /** @var string The class to attach to li tags with children. */
    protected static $parent_class = 'dropdown';

    /** @var string The class to apply to li tags within ul tags inside. */
    protected static $submenu_class = 'dropdown-submenu';

    /** @var string The class to apply to ul tags within li tags. */
    protected static $child_class = 'dropdown-menu';

    /** @var string The id to apply to the outer ul tag. */
    protected static $outer_id = null;

    /** @var array Stores the available menu actions. */
    protected static $actions = array();

    /** @var object Pointer to the CodeIgniter instance. */
    protected static $ci;

    /** @var array The context menus configuration. */
    protected static $contexts = array();

    /** @var array Any errors which occurred during the Context creation. */
    protected static $errors = array();

    /** @var array Stores the organized menu actions. */
    protected static $menu = array();

    /** @var string[] Contexts which are required. */
    protected static $requiredContexts = array('settings', 'developer');

    /** @var string Admin area to link to or other context. */
    protected static $site_area;

    //--------------------------------------------------------------------------

    /**
     * Get the CI instance and call the init method.
     *
     * @return void
     */
    public function __construct()
    {
        self::$ci =& get_instance();
        self::init();
    }

    /**
     * Load the configured contexts.
     *
     * @return void
     */
    protected static function init()
    {
        self::setContexts(self::$ci->config->item('contexts'), SITE_AREA);
        log_message('debug', 'UI/Contexts library loaded');
    }

    /**
     * Set the contexts array and, optionally, the site area.
     *
     * @param array  Context menus to display, normally stored in application config.
     * @param string Area to link to, if not provided (or null), will remain unchanged.
     *
     * @return void
     */
    public static function setContexts($contexts = array(), $siteArea = null)
    {
        if (empty($contexts) || ! is_array($contexts)) {
            die(lang('bf_no_contexts'));
        }

        // Ensure required contexts exist.
        foreach (self::$requiredContexts as $requiredContext) {
            if (! in_array($requiredContext, $contexts)) {
                $contexts[] = $requiredContext;
            }
        }

        self::$contexts = $contexts;
        if (! is_null($siteArea)) {
            self::$site_area = $siteArea;
        }

        log_message('debug', 'UI/Contexts setContexts has been called.');
    }

    /**
     * Return the context array, just in case it is needed later.
     *
     * @param boolean $landingPageFilter If true, only returns contexts which have
     * a landing page (index.php) available.
     *
     * @return array The names of the contexts.
     */
    public static function getContexts($landingPageFilter = false)
    {
        if (! $landingPageFilter) {
            return self::$contexts;
        }

        $returnContexts = array();
        foreach (self::$contexts as $context) {
            if (file_exists(realpath(VIEWPATH) . '/' . self::$site_area . "/{$context}/index.php")) {
                $returnContexts[] = $context;
            }
        }

        return $returnContexts;
    }

    /**
     * Returns a string of any errors during the create context process.
     *
     * @param string $open  A string to place at the beginning of each error.
     * @param string $close A string to place at the end of each error.
     *
     * @return string All of the current errors with the provided $open/$close strings,
     * with each close string followed by a newline (\n) character.
     */
    public static function errors($open = '<li>', $close = '</li>')
    {
        $out  = '';
        foreach (self::$errors as $error) {
            $out .= "{$open}{$error}{$close}\n";
        }

        return $out;
    }

    /**
     * Renders a list-based menu (with submenus) for each context.
     *
     * @param string $mode            What to output in the top menu ('icon'/'text'/'both').
     * @param string $order_by        The sort order of the elements ('normal'/'reverse'/'asc'/'desc').
     * @param boolean $top_level_only If true, output only the top-level links.
     * @param boolean $benchmark      If true, output benchmark start/end marks.
     *
     * @return string A string with the built navigation.
     */
    public static function render_menu($mode = 'text', $order_by = 'normal', $top_level_only = false, $benchmark = false)
    {
        if ($benchmark) {
            self::$ci->benchmark->mark('render_menu_start');
        }

        // As long as the contexts were set with setContexts(), the required contexts
        // should be in place. However, it's still a good idea to make sure an array
        // of contexts was provided.
        $contexts = self::getContexts();
        if (empty($contexts) || ! is_array($contexts)) {
            die(self::$ci->lang->line('bf_no_contexts'));
        }

        // Sorting (top-level menus).
        switch ($order_by) {
            case 'reverse':
                $contexts = array_reverse($contexts);
                break;
            case 'asc':
                natsort($contexts);
                break;
            case 'desc':
                rsort($contexts);
                break;
            case 'normal':
            case 'default':
            default:
                break;
        }

        $template = '';
        if ($mode == 'text') {
            $template = self::$templateContextText;
        } else {
            $template = self::$templateContextImage;
            if ($mode == 'both') {
                $template .= self::$templateContextText;
            }
        }
        $template .= self::$templateContextEnd;

        // Build out the navigation.
        $menu = '';
        foreach ($contexts as $context) {
            // Don't display an entry in the menu if the user doesn't have permission
            // to view it (unless the permission doesn't exist).
            $viewPermission = 'Site.' . ucfirst($context) . '.View';
            if (self::$ci->auth->has_permission($viewPermission)
                || ! self::$ci->auth->permission_exists($viewPermission)
            ) {
                // The text/image displayed in the top-level context menu.
                $title    = self::$ci->lang->line("bf_context_{$context}");
                $navTitle = str_replace(
                    array('{title}', '{image}'),
                    array(
                        $title,
                        $mode == 'text' ? '' : Template::theme_url("images/context_{$context}.png"),
                    ),
                    $template
                );

                // Build the menu for this context.
                $menu .= str_replace(
                    array('{parent_class}', '{url}', '{id}', '{current_class}', '{title}', '{extra}', '{text}', '{content}'),
                    array(
                        self::$parent_class . ' ' . check_class($context, true),
                        site_url(self::$site_area . "/{$context}"),
                        "tb_{$context}",
                        $top_level_only ? '' : self::$templateContextMenuAnchorClass,
                        $title,
                        str_replace('{dataId}', $context, self::$templateContextMenuExtra),
                        $navTitle,
                        $top_level_only ? '' : self::context_nav($context),
                    ),
                    self::$templateContextMenu
                );
            }
        }

        // Put the generated menu into the context nav template.
        $nav = str_replace(
            array('{class}', '{extra}', '{menu}'),
            array(
                self::$outer_class,
                trim(self::$outer_id) == '' ? '' : ' id="' . self::$outer_id . '"',
                $menu,
            ),
            self::$templateContextNav
        );

        if ($benchmark) {
            self::$ci->benchmark->mark('render_menu_end');
        }

        return $nav;
    }

    /**
     * Create the mobile navigation.
     *
     * The tab-style mobile navigation is made up of a series of divs, each of which
     * contains a list of links within that context.
     *
     * @return string The navigation lists.
     */
    public static function render_mobile_navs()
    {
        $out = '';
        foreach (self::getContexts() as $context) {
            $contextNav = self::context_nav($context, '', true);
            $currentId  = " id='{$context}_menu'";
            $out .= str_replace(
                array('{class}', '{extra}', '{menu}'),
                array(self::$templateContextNavMobileClass, $currentId, $contextNav),
                self::$templateContextNav
            );
        }

        return $out;
    }

    /**
     * Build the main navigation menu for each context.
     *
     * @param string  $context   The context of the nav to be built.
     * @param string  $class     The class to use on the nav.
     * @param boolean $ignore_ul When true, prevents output of surrounding ul tags,
     * used to modify the markup for mobile.
     *
     * @return string The HTML necessary to display the menu.
     */
    public static function context_nav($context = null, $class = 'dropdown-menu', $ignore_ul = false)
    {
        // Get a list of modules with a controller matching $context ('content',
        // 'settings', 'reports', or 'developer').
        foreach (Modules::list_modules() as $module) {
            if (Modules::controller_exists($context, $module)) {
                $mod_config = Modules::config($module);

                self::$actions[$module] = array(
                    'display_name' => isset($mod_config['name']) ? $mod_config['name'] : $module,
                    'menus'        => isset($mod_config['menus']) ? $mod_config['menus'] : false,
                    'title'        => isset($mod_config['description']) ? $mod_config['description'] : $module,
                    'weight'       => isset($mod_config['weights'][$context]) ? $mod_config['weights'][$context] : 0,
                );

                // This is outside the array because the else portion uses the
                // 'display_name' value,
                self::$actions[$module]['menu_topic'] = isset($mod_config['menu_topic']) ?
                    $mod_config['menu_topic'] : self::$actions[$module]['display_name'];
            }
        }

        // Are there any actions?
        if (empty(self::$actions)) {
            return str_replace(
                array('{class}', '{extra}', '{menu}'),
                array($class, '', ''),
                self::$templateContextNav
            );
        }

        // Order the actions by weight, then alphabetically.
        self::sortActions();

        // Build up the menu array.
        $ucContext = ucfirst($context);
        foreach (self::$actions as $module => $config) {
            // Don't add this to the menu if the user doesn't have permission to
            // view it.
            if (self::$ci->auth->has_permission('Bonfire.' . ucfirst($module) . '.View')
                || self::$ci->auth->has_permission(ucfirst($module) . ".{$ucContext}.View")
            ) {
                // Drop-down menus?
                $menu_topic = is_array($config['menu_topic']) && isset($config['menu_topic'][$context]) ?
                    $config['menu_topic'][$context] : $config['display_name'];

                self::$menu[$menu_topic][$module] = array(
                    'display_name' => $config['display_name'],
                    'title'        => $config['title'],
                    'menu_topic'   => $menu_topic,
                    'menu_view'    => $config['menus'] && isset($config['menus'][$context]) ?
                        $config['menus'][$context] : '',
                );
            }
        }

        // Add any sub-menus and reset the $actions array for the next pass.
        $menu = self::build_sub_menu($context, $ignore_ul);
        self::$actions = array();

        return $menu;
    }

    //--------------------------------------------------------------------------
    // !BUILDER METHODS
    //--------------------------------------------------------------------------

    /**
     * Create everything needed for a new context to run.
     *
     * This includes creating permissions, assigning them to certain roles, and
     * creating an application migration for the permissions.
     *
     * @todo Create the migration file if $migrate is true...
     *
     * @param string  $name    The name of the context to create.
     * @param array   $roles   The roles (names or IDs) which should have permission
     * to view this module.
     * @param boolean $migrate If true, will create a migration file.
     *
     * @return boolean False on error, else true.
     */
    public static function create_context($name = '', $roles = array(), $migrate = false)
    {
        if (empty($name)) {
            self::$errors = lang('ui_no_context_name');
            return false;
        }

        // Write the context name to the config file.

        self::$ci->load->helper('config_file');

        $contexts  = self::getContexts();
        $lowerName = strtolower($name);

        // Add the context if it is not already in the list of contexts.
        if (! in_array($lowerName, $contexts)) {
            array_unshift($contexts, $lowerName);

            if (! write_config('application', array('contexts' => $contexts), null)) {
                self::$errors[] = lang('ui_cant_write_config');
                return false;
            }
        }

        // Create an entry in the application_lang file for the context.

        if (! function_exists('addLanguageLine')) {
            self::$ci->load->helper('translate/languages');
        }

        $temp = addLanguageLine('application_lang.php', array("bf_context_{$lowerName}" => $name), 'english');
        if (! $temp) {
            // @todo set error/return if the language line was not added successfully?
        }

        // Create the relevant permissions.

        $cname = 'Site.' . ucfirst($name) . '.View';

        // Get the permission ID, either from an existing permission or by inserting
        // a new permission.
        self::$ci->load->model('permissions/permission_model');
        if (self::$ci->permission_model->permission_exists($cname)) {
            $pid = self::$ci->permission_model->find_by('name', $cname)->permission_id;
        } else {
            $pid = self::$ci->permission_model->insert(
                array(
                    'name'        => $cname,
                    'description' => 'Allow user to view the ' . ucwords($name) . ' Context.',
                )
            );
        }

        // Assign the permission to the supplied roles.

        // If no roles were supplied, exit, indicating success.
        if (empty($roles)) {
            return true;
        }

        // Assign the permission to each role.
        self::$ci->load->model('roles/role_permission_model');
        foreach ($roles as $role) {
            if (is_numeric($role)) {
                // Assign By Id.
                self::$ci->role_permission_model->delete($role, $pid);
                self::$ci->role_permission_model->create($role, $pid);
            } else {
                // Assign By Name.
                self::$ci->role_permission_model->assign_to_role($role, $cname);
            }
        }

        // if ($migrate) {
        //  @todo create a migration file.
        // }

        return true;
    }

    //--------------------------------------------------------------------------
    // !UTILITY METHODS
    //--------------------------------------------------------------------------

    /**
     * Take an array of key/value pairs and set the class/id names.
     *
     * @param array $attrs An array of key/value pairs that correspond to the class
     * methods for classes and ids.
     *
     * @return void
     */
    public static function set_attrs($attrs = array())
    {
        if (empty($attrs) || ! is_array($attrs)) {
            return null;
        }

        foreach ($attrs as $attr => $value) {
            if (isset(self::$attr)) {
                self::$attr = $value;
            }
        }
    }

    /**
     * Build out the HTML for the menu.
     *
     * @param string  $context   The context to build the nav for.
     * @param boolean $ignore_ul If true, the list will be returned without being
     * placed into the template.
     *
     * @return string HTML for the sub menu.
     */
    public static function build_sub_menu($context, $ignore_ul = false)
    {
        $search = array('{submenu_class}', '{url}', '{display}', '{child_class}', '{view}');
        $list   = '';
        foreach (self::$menu as $topic_name => $topic) {
            if (count($topic) <= 1) {
                foreach ($topic as $module => $vals) {
                    $list .= self::buildItem(
                        $module,
                        $vals['title'],
                        $vals['display_name'],
                        $context,
                        $vals['menu_view']
                    );
                }
            } else {
                // If there is more than one item in the topic, build out a menu
                // based on the multiple items.
                $subMenu = '';
                foreach ($topic as $module => $vals) {
                    if (empty($vals['menu_view'])) {
                        // If it has no sub-menu, add the item.
                        $subMenu .= self::buildItem(
                            $module,
                            $vals['title'],
                            $vals['display_name'],
                            $context,
                            $vals['menu_view']
                        );
                    } else {
                        // Otherwise, echo out the sub-menu only. To maintain backwards
                        // compatility, strip out any <ul> tags.
                        $subMenu .= str_ireplace(
                            array('<ul>', '</ul>'),
                            array('', ''),
                            self::$ci->load->view($vals['menu_view'], null, true)
                        );
                    }
                }

                // Handle localization of the topic name, if needed.
                if (strpos($topic_name, 'lang:') === 0) {
                    $topic_name = self::$ci->lang->line(str_replace('lang:', '', $topic_name));
                }

                $list .= str_replace(
                    $search,
                    array(
                        self::$submenu_class,
                        '#',
                        ucwords($topic_name),
                        self::$child_class,
                        $subMenu,
                    ),
                    self::$templateSubMenu
                );
            }
        }

        self::$menu = array();

        if ($ignore_ul) {
            return $list;
        }

        return str_replace(
            array('{class}', '{extra}', '{menu}'),
            array(self::$child_class, '', $list),
            self::$templateContextNav
        );
    }

    /**
     * Build an individual list item (with sub-menus) for the menu.
     *
     * @param string $module       The name of the module this link belongs to.
     * @param string $title        The title used on the link.
     * @param string $display_name The name to display in the menu.
     * @param string $context      The name of the context.
     * @param string $menu_view    The name of the view file that contains the sub-menu.
     *
     * @return string The HTML necessary for a single item and its sub-menus.
     */
    private static function buildItem($module, $title, $display_name, $context, $menu_view = '')
    {
        // Handle localization of the display name, if needed.
        if (strpos($display_name, 'lang:') === 0) {
            $display_name = lang(str_replace('lang:', '', $display_name));
        }
        $displayName = ucwords(str_replace('_', '', $display_name));

        if (empty($menu_view)) {
            return str_replace(
                array('{extra}', '{url}', '{title}', '{display}'),
                array(
                    $module == self::$ci->uri->segment(3) ? 'class="active" ' : '',
                    site_url(self::$site_area . "/{$context}/{$module}"),
                    $title,
                    $displayName,
                ),
                self::$templateMenu
            );
        }

        // Sub Menus?. Only works if it's a valid view…
        return str_replace(
            array('{submenu_class}', '{url}', '{display}', '{child_class}', '{view}'),
            array(
                self::$submenu_class,
                '#',
                $displayName,
                self::$child_class,
                str_ireplace(
                    array('<ul>', '</ul>'),
                    array('', ''),
                    self::$ci->load->view($menu_view, null, true)
                ), // To maintain backwards compatility, strip out any <ul> tags
            ),
            self::$templateSubMenu
        );
    }

    /**
     * Sort the actions array.
     *
     * @return void
     */
    private static function sortActions()
    {
        $weights       = array();
        $display_names = array();

        foreach (self::$actions as $key => $action) {
            $weights[$key]       = $action['weight'];
            $display_names[$key] = $action['display_name'];
        }

        array_multisort($weights, SORT_DESC, $display_names, SORT_ASC, self::$actions);
    }

    //--------------------------------------------------------------------------
    // Deprecated methods (do not use)
    //--------------------------------------------------------------------------

    /**
     * Return the context array, just in case it is needed later.
     *
     * @deprecated since 0.7.1 Use getContexts().
     *
     * @param boolean $landingPageFilter If true, only returns contexts which have
     * a landing page (index.php) available.
     *
     * @return array The names of the contexts.
     */
    public static function get_contexts($landingPageFilter = false)
    {
        return self::getContexts($landingPageFilter);
    }

    /**
     * Set the contexts array
     *
     * @deprecated since 0.7.2 Use setContexts(). Note: SITE_AREA should be passed
     * as the second argument of setContexts() to replicate the behavior provided
     * when calling set_contexts() without a second argument. If the second argument
     * to set_contexts() was never provided, it can probably be safely omitted for
     * setContexts().
     *
     * @param  array  Context menus to display, normally stored in application config.
     * @param  string Area to link to, defaults to SITE_AREA or Admin area.
     *
     * @return void
     */
    public static function set_contexts($contexts = array(), $site_area = SITE_AREA)
    {
        self::setContexts($contexts, $site_area);
    }
}
/* end /ui/libraries/contexts.php */
