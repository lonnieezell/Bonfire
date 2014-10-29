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
 * @package    Bonfire\Core\Modules\UI\Libraries\Contexts
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/bonfire/contexts
 */
class Contexts
{
    /*
     * Templates and related strings for building the context menus
     */
    protected static $templateContextNav    = "<ul class='{class}'{extra}>\n{menu}</ul>\n";
    protected static $templateContextMenu   = "<li class='{parent_class}'><a href='{url}' id='{id}' class='{current_class}' title='{title}'{extra}>{text}</a>{content}</li>\n";
    protected static $templateMenu          = "<li><a {extra}href='{url}' title='{title}'>{display}</a>\n</li>\n";
    protected static $templateSubMenu       = "<li class='{submenu_class}'><a href='{url}'>{display}</a><ul class='{child_class}'>{view}</ul></li>\n";

    protected static $templateContextEnd                = "<span class='caret'></span>";
    protected static $templateContextImage              = "<img src='{image}' alt='{title}' />";
    protected static $templateContextText               = "{title}";
    protected static $templateContextMenuAnchorClass    = 'dropdown-toggle';
    protected static $templateContextMenuExtra          = " data-toggle='dropdown' data-id='{dataId}_menu'";
    protected static $templateContextNavMobileClass     = 'mobile_nav';

	/**
	 * @var array Stores the available menu actions.
	 */
	protected static $actions = array();

	/**
	 * @var array Stores the organized menu actions.
	 */
	protected static $menu = array();

	/**
	 * @var string The class name to attach to the outer ul tag.
	 */
	protected static $outer_class = 'nav';

	/**
	 * @var string The id to apply to the outer ul tag.
	 */
	protected static $outer_id = null;

	/**
	 * @var string The class to attach to li tags with children
	 */
	protected static $parent_class = 'dropdown';

	/**
	 * @var string The class to apply to li tags within ul tags inside.
	 */
	protected static $submenu_class	= 'dropdown-submenu';

	/**
	 * @var string The class to apply to ul tags within li tags.
	 */
	protected static $child_class = 'dropdown-menu';

	/**
	 * @var object Pointer to the CodeIgniter instance.
	 */
	protected static $ci;

	/**
	 * @var string Admin Area to Link to or other Context.
	 */
	protected static $site_area = SITE_AREA;

	/**
	 * @var array The context menus configuration
	 */
	protected static $contexts = array();

	/**
	 * @var array Any errors which occurred during the Context creation.
	 */
	protected static $errors = array();

    /**
     * @var string[] Contexts which are required.
     */
    protected static $requiredContexts = array('settings', 'developer');

	//--------------------------------------------------------------------

	/**
	 * Get the CI instance and call the init method
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		self::init();
	}

	/**
	 * Load the configured contexts
	 *
	 * @return void
	 */
	protected static function init()
	{
		self::$contexts = self::$ci->config->item('contexts');
		log_message('debug', 'UI/Contexts library loaded');
	}

	/**
	 * Set the contexts array
	 *
	 * @param  array  Array of Context Menus to Display normally stored in
	 * application config.
	 * @param  string Area to link to defaults to SITE_AREA or Admin area.
	 *
	 * @return void
	 */
	public static function set_contexts($contexts = array(), $site_area = SITE_AREA)
	{
		if (empty($contexts) || ! is_array($contexts) || ! count($contexts)) {
			die(lang('bf_no_contexts'));
		}

		self::$contexts  = $contexts;
		self::$site_area = $site_area;

		log_message('debug', 'UI/Contexts set_contexts has been called.');
	}

	/**
	 * Returns the context array just in case it is needed later.
	 *
	 * @return array
	 */
	public static function get_contexts()
	{
		return self::$contexts;
	}

	/**
	 * Returns a string of any errors during the create context process.
	 *
	 * @param	string	$open	A string to place at the beginning of each error.
	 * @param	string	$close	A string to place at the close of each error.
	 *
	 * @return 	string
	 */
	public static function errors($open = '<li>', $close = '</li>')
	{
		$out = '';
		foreach (self::$errors as $error) {
			$out .= "{$open}{$error}{$close}\n";
		}

		return $out;
	}

	/**
	 * Renders a list-based menu (with submenus) for each context.
	 *
	 * @param string $mode           What to display in the top menu. Either 'icon', 'text', or 'both'.
	 * @param string $order_by       Determines the sort order of the elements. Valid options are 'normal', 'reverse', 'asc', 'desc'.
	 * @param bool   $top_level_only If true, will only display the top-level links.
	 * @param bool   $benchmark      If true, benchmark start/end marks will be output
	 *
	 * @return string A string with the built navigation.
	 */
	public static function render_menu($mode = 'text', $order_by = 'normal', $top_level_only = false, $benchmark = false)
	{
        if ($benchmark) {
            self::$ci->benchmark->mark('render_menu_start');
        }

		$contexts = self::$contexts;
		if (empty($contexts) || ! is_array($contexts) || ! count($contexts)) {
			die(lang('bf_no_contexts'));
		}

		// Ensure required contexts exist
        foreach (self::$requiredContexts as $requiredContext) {
            if ( ! in_array($requiredContext, $contexts)) {
                $contexts[] = $requiredContext;
            }
        }

		// Sorting
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
                // no break
			case 'default':
                // no break
            default:
				break;
		}

        $parentClass = self::$parent_class;
        $siteAreaUrl = site_url(self::$site_area) . '/';

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
        $search = array('{parent_class}', '{url}', '{id}', '{current_class}', '{title}', '{extra}', '{text}', '{content}');

        $menu = '';
		// Build out the navigation.
		foreach ($contexts as $context) {
            $viewPermission = 'Site.' . ucfirst($context) . '.View';
			if (has_permission($viewPermission)
                || ! permission_exists($viewPermission)
               ) {
				$url    = $siteAreaUrl . $context;
				$class  = check_class($context, true);
				$id     = "tb_{$context}";
                $title  = lang("bf_context_{$context}");
                $icon   = $mode == 'text' ? '' : Template::theme_url("images/context_{$context}.png");

                $navTitle = str_replace(
                    array('{title}', '{image}'),
                    array($title, $icon),
                    $template
                );

                $replace = array(
                    "{$parentClass} {$class}",
                    $url,
                    $id,
                    self::$templateContextMenuAnchorClass,
                    $title,
                    str_replace('{dataId}', $context, self::$templateContextMenuExtra),
                    $navTitle,
                    $top_level_only ? '' : self::context_nav($context),
                );

                $menu .= str_replace($search, $replace, self::$templateContextMenu);
			}
		}

        $extra = trim(self::$outer_id) == '' ? '' : ' id="' . self::$outer_id . '"';
        $nav = str_replace(
            array('{class}', '{extra}', '{menu}'),
            array(self::$outer_class, $extra, $menu),
            self::$templateContextNav
        );

        if ($benchmark) {
    		self::$ci->benchmark->mark('render_menu_end');
        }

		return $nav;
	}

	/**
	 * Create a series of divs, each of which contain a <ul> of links within
	 * that context. This is intended for the tab-style mobile navigation.
	 *
	 * @return string The navigation lists.
	 */
	public static function render_mobile_navs()
	{
		$out = '';
		foreach (self::$contexts as $context) {
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
	 * @param string $context   The context to build the nav for.
	 * @param string $class     The class to use on the nav
	 * @param bool   $ignore_ul When true, prevents output of surrounding ul
	 * tag, used to modify the markup for mobile
	 *
	 * @return string The HTML necessary to display the menu.
	 */
	public static function context_nav($context = null, $class = 'dropdown-menu', $ignore_ul = false)
	{
		// Get a list of modules with a controller matching
		// $context ('content', 'settings', 'reports', or 'developer')
		foreach (Modules::list_modules() as $module) {
			if (Modules::controller_exists($context, $module)) {
				$mod_config = Modules::config($module);

				self::$actions[$module] = array(
					'weight'		=> isset($mod_config['weights'][$context]) ? $mod_config['weights'][$context] : 0,
					'display_name'	=> isset($mod_config['name']) ? $mod_config['name'] : $module,
					'title' 		=> isset($mod_config['description']) ? $mod_config['description'] : $module,
					'menus'			=> isset($mod_config['menus']) ? $mod_config['menus'] : false,
				);

                // This is outside the array because the else portion uses the
                // 'display_name' value,
				self::$actions[$module]['menu_topic'] = isset($mod_config['menu_topic']) ? $mod_config['menu_topic'] : self::$actions[$module]['display_name'];
			}
		}

		// Are there any actions?
		if ( ! count(self::$actions)) {
            return str_replace(
                array('{class}', '{extra}', '{menu}'),
                array($class, '', ''),
                self::$templateContextNav
            );
		}

		// Order the actions by weight, then alphabetically
		self::sort_actions();

		// Build up the menu array
        $ucContext = ucfirst($context);
		foreach (self::$actions as $module => $config) {
			if (has_permission('Bonfire.' . ucfirst($module) . '.View')
                || has_permission(ucfirst($module) . ".{$ucContext}.View")
               ) {
				// Drop-down menus?
				$menu_view = $config['menus'] && isset($config['menus'][$context]) ? $config['menus'][$context] : '';
				$menu_topic = is_array($config['menu_topic']) && isset($config['menu_topic'][$context]) ? $config['menu_topic'][$context] : $config['display_name'];

				self::$menu[$menu_topic][$module] = array(
                    'title'			=> $config['title'],
                    'display_name'	=> $config['display_name'],
                    'menu_view'		=> $menu_view,
                    'menu_topic'	=> $menu_topic
				);
			}
		}

		$menu = self::build_sub_menu($context, $ignore_ul);
		self::$actions = array();

		return $menu;
	}

	//--------------------------------------------------------------------
	// !BUILDER METHODS
	//--------------------------------------------------------------------

	/**
	 * Creates everything needed for a new context to run. Includes
	 * creating permissions, assigning them to certain roles, and
	 * even creating an application migration for the permissions.
	 *
	 * @param	string	$name	The name of the context to create.
	 * @param	array	$roles	The names or id's of the roles to give permissions to view.
	 * @param	bool	$migrate	If TRUE, will create an app migration file.
	 *
	 * @return 	bool
	 */
	public static function create_context($name = '', $roles = array(), $migrate = false)
	{
		if (empty($name)) {
			self::$errors = lang('ui_no_context_name');
			return false;
		}

		// 1. Try to write to the config file so it will show in the menu no
        // matter what.
		self::$ci->load->helper('config_file');

		$contexts  = self::$contexts;
        $lowerName = strtolower($name);

		// If it isn't in the list of contexts, add it
		if ( ! in_array($lowerName, $contexts)) {
			array_unshift($contexts, $lowerName);

			if ( ! write_config('application', array('contexts' => $contexts), null)) {
				self::$errors[] = lang('ui_cant_write_config');
				return false;
			}
		}

        // 2. Language File
        if ( ! function_exists('addLanguageLine')) {
            self::$ci->load->helper('translate/languages');
            $temp = addLanguageLine('application_lang.php', array("bf_context_{$lowerName}" => $name), 'english');
        }

        // 3. Create the relevant permissions
		$cname = 'Site.' . ucfirst($name) . '.View';

		// 3.1. create the actual permission
		self::$ci->load->model('permissions/permission_model');
		if (self::$ci->permission_model->permission_exists($cname)) {
			$pid = self::$ci->permission_model->find_by('name', $cname)->permission_id;
		} else {
			$pid = self::$ci->permission_model->insert(array(
				'name'			=> $cname,
				'description'	=> 'Allow user to view the ' . ucwords($name) . ' Context.',
			));
		}

		// Are there any roles to apply this to? If not, quit, since there will
        // be nothing to migrate
		if (count($roles) == 0) {
			return true;
		}

		self::$ci->load->model('roles/role_permission_model');
		foreach ($roles as $role) {
			// Assign By Id
			if (is_numeric($role)) {
				self::$ci->role_permission_model->delete_role_permissions($role, $pid);
				self::$ci->role_permission_model->create_role_permissions($role, $pid);
			}
			// Assign By Name
			else {
				self::$ci->role_permission_model->assign_to_role($role, $cname);
			}
		}

		return true;
	}

	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Take an array of key/value pairs and set the class/id names.
	 *
	 * @param array $attrs An array of key/value pairs that correspond to the
	 * class methods for classes and ids.
	 *
	 * @return void
	 */
	public static function set_attrs($attrs = array())
	{
		if ( ! is_array($attrs)) {
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
	 * @param string $context   The context to build the nav for.
	 * @param bool   $ignore_ul
	 *
	 * @return string HTML for the sub menu
	 */
	public static function build_sub_menu($context, $ignore_ul = false)
	{
		$list       = '';
        $childClass = self::$child_class;
        $search     = array('{submenu_class}', '{url}', '{display}', '{child_class}', '{view}');

		foreach (self::$menu as $topic_name => $topic) {
			// If there is more than one item in the topic, we need to build out
			// a menu based on the multiple items.
			if (count($topic) > 1) {
                $subMenu = '';
				foreach ($topic as $module => $vals) {
					// If it has no sub-menu, add it like normal
					if (empty($vals['menu_view'])) {
						$subMenu .= self::build_item($module, $vals['title'], $vals['display_name'], $context, $vals['menu_view']);
					}
					// Otherwise, echo out the sub-menu only
					else {
						$view = self::$ci->load->view($vals['menu_view'], null, true);
						// To maintain backwards compatility, strip out any <ul> tags
						$subMenu .= str_ireplace(array('<ul>', '</ul>'), array('', ''), $view);
					}
				}

                // Handle localization of the topic name, if needed.
                if (strpos($topic_name, 'lang:') === 0) {
                    $topic_name = lang(str_replace('lang:', '', $topic_name));
                }

                $replace = array(
                    self::$submenu_class,
                    '#',
                    ucwords($topic_name),
                    $childClass,
                    $subMenu,
                );
                $list .= str_replace($search, $replace, self::$templateSubMenu);
			} else {
				foreach ($topic as $module => $vals) {
					$list .= self::build_item($module, $vals['title'], $vals['display_name'], $context, $vals['menu_view']);
				}
			}
		}

		self::$menu = array();

        if ($ignore_ul) {
            return $list;
        }

        return str_replace(
            array('{class}', '{extra}', '{menu}'),
            array($childClass, '', $list),
            self::$templateContextNav
        );
	}

	/**
	 * Build an individual list item (with sub-menus) for the menu.
	 *
	 * @param string $module       The name of the module this link belongs to
	 * @param string $title        The title used on the link
	 * @param string $display_name The name to display in the menu
	 * @param string $context      The name of the context
	 * @param string $menu_view    The name of the view file that contains the
	 * sub-menu
	 *
	 * @return string The HTML necessary for a single item and it's sub-menus.
	 */
	private static function build_item($module, $title, $display_name, $context, $menu_view = '')
	{
        // Handle localization of the display name, if needed.
        if (strpos($display_name, 'lang:') === 0) {
            $display_name = lang(str_replace('lang:', '', $display_name));
        }
        $displayName = ucwords(str_replace('_', '', $display_name));

		if (empty($menu_view)) {
            $search = array('{extra}', '{url}', '{title}', '{display}');
            $replace = array(
                $module == self::$ci->uri->segment(3) ? 'class="active" ' : '',
                site_url(self::$site_area . "/{$context}/{$module}"),
                $title,
                $displayName,
            );

            return str_replace($search, $replace, self::$templateMenu);
		}

		// Sub Menus?
        // Only works if it's a valid view…
        $view = self::$ci->load->view($menu_view, null, true);

        $search = array('{submenu_class}', '{url}', '{display}', '{child_class}', '{view}');
        $replace = array(
            self::$submenu_class,
            '#',
            $displayName,
            self::$child_class,
            // To maintain backwards compatility, strip out any <ul> tags
            str_ireplace(array('<ul>', '</ul>'), array('', ''), $view),
        );

        return str_replace($search, $replace, self::$templateSubMenu);
	}

	/**
	 * Sort the actions array
	 *
	 * @return void
	 */
	private static function sort_actions()
	{
		$weights 		= array();
		$display_names	= array();

		foreach (self::$actions as $key => $action) {
			$weights[$key] 			= $action['weight'];
			$display_names[$key]	= $action['display_name'];
		}

		array_multisort($weights, SORT_DESC, $display_names, SORT_ASC, self::$actions);
	}
}
/* end /ui/libraries/contexts.php */