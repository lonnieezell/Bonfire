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
 */

// ------------------------------------------------------------------------

/**
 * Contexts Library
 *
 * Provides helper methods for displaying Context Navigation.
 *
 * @package    Bonfire\Core\Modules\Libraries\Modules_Ui
 * @category   Libraries
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides/contexts.html
 *
 */
class Contexts
{
    /**
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
	 * Stores the available menu actions.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $actions = array();

	/**
	 * Stores the organized menu actions.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $menu	= array();

	/**
	 * The class name to attach to the outer ul tag.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $outer_class	= 'nav';

	/**
	 * The id to apply to the outer ul tag.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $outer_id	= null;

	/**
	 * The class to attach to li tags with children
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $parent_class	= 'dropdown';

	/**
	 * The class to apply to li tags within ul tags inside.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $submenu_class	= 'dropdown-submenu';

	/**
	 * The class to apply to ul tags within li tags.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $child_class	= 'dropdown-menu';

	/**
	 * Pointer to the CodeIgniter instance.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $ci;

	/**
	 * Admin Area to Link to or other Context.
	 *
	 * @access protected
	 * @static
	 *
	 * @var string
	 */
	protected static $site_area = SITE_AREA;

	/**
	 * Stores the context menus config.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $contexts = array();

	/**
	 * Stores errors created during the
	 * Context creation.
	 *
	 * @access protected
	 * @static
	 *
	 * @var array
	 */
	protected static $errors = array();

	//--------------------------------------------------------------------

	/**
	 * Calls the class init
	 *
	 * @return void
	 */
	public function __construct()
	{
		self::$ci =& get_instance();
		self::init();

	}//end __construct()

	//--------------------------------------------------------------------

	/**
	 * Loads application helper
	 *
	 * @access protected
	 * @static
	 *
	 * @return void
	 */
	protected static function init()
	{
		if ( ! function_exists('module_list')) {
			self::$ci->load->helper('application');
		}

		self::$contexts = self::$ci->config->item('contexts');
		log_message('debug', 'UI/Contexts library loaded');
	}//end init()

	//--------------------------------------------------------------------

	/**
	 * Sets the contexts array
	 *
	 * @static
	 *
	 * @param  array  Array of Context Menus to Display normally stored in application config.
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

		unset($contexts, $site_area);

		log_message('debug', 'UI/Contexts set_contexts has been called.');
	}//end set_contexts()


	//--------------------------------------------------------------------

	/**
	 * Returns the context array just in case it is needed later.
	 *
	 * @static
	 *
	 * @return array
	 */
	public static function get_contexts()
	{
		return self::$contexts;
	}//end get_contexts()


	//--------------------------------------------------------------------

	/**
	 * Returns a string of any errors during the create context process.
	 *
	 * @access	public
	 * @static
	 *
	 * @param	string	$open	A string to place at the beginning of every error.
	 * @param	string	$close	A string to place at the close of every error.
	 *
	 * @return 	string
	 */
	public static function errors($open='<li>', $close='</li>')
	{
		$out = '';

		foreach (self::$errors as $error) {
			$out .= $open . $error . $close . "\n";
		}

		return $out;
	}

	//--------------------------------------------------------------------

	/**
	 * Renders a list-based menu (with submenus) for each context.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $mode           What to display in the top menu. Either 'icon', 'text', or 'both'.
	 * @param string $order_by       Determines the sort order of the elements. Valid options are 'normal', 'reverse', 'asc', 'desc'.
	 * @param bool   $top_level_only If true, will only display the top-level links.
	 * @param bool   $benchmark      If true, benchmark start/end marks will be output
	 *
	 * @return string A string with the built navigation.
	 */
	public static function render_menu($mode='text', $order_by='normal', $top_level_only=false, $benchmark=false)
	{
        if ($benchmark) {
            self::$ci->benchmark->mark('render_menu_start');
        }

		$contexts = self::$contexts;

		if (empty($contexts) || ! is_array($contexts) || ! count($contexts)) {
			die(lang('bf_no_contexts'));
		}

		// Ensure settings context exists
		if ( ! in_array('settings', $contexts)) {
			array_push($contexts, 'settings');
		}

		// Ensure developer context exists
		if ( ! in_array('developer', $contexts)) {
			array_push($contexts, 'developer');
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
			case 'default':
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
		// Build out our navigation.
		foreach ($contexts as $context) {
            $viewPermission = 'Site.' . ucfirst($context) . '.View';
			if (has_permission($viewPermission) || ! permission_exists($viewPermission)) {
				$url        = $siteAreaUrl . $context;
				$class      = check_class($context, true);
				$id         = 'tb_' . $context;
                $title      = lang('bf_context_' . $context);
                $icon       = $mode == 'text' ? '' : Template::theme_url('images/context_' . $context . '.png');

                $navTitle = str_replace(array('{title}', '{image}'), array($title, $icon), $template);

                $replace = array(
                    $parentClass . ' ' . $class,
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
        $nav = str_replace(array('{class}', '{extra}', '{menu}'), array(self::$outer_class, $extra, $menu), self::$templateContextNav);

        if ($benchmark) {
    		self::$ci->benchmark->mark('render_menu_end');
        }

		return $nav;
	}//end render_menu()

	//--------------------------------------------------------------------

	/**
	 * Creates a series of divs that each contain a <ul> of links within
	 * that context. This is intended for the tab-style mobile navigation.
	 *
	 * @access public
	 * @static
	 *
	 * @return string A string with the navigation lists.
	 */
	public static function render_mobile_navs()
	{
		$out = '';
		foreach (self::$contexts as $context) {
            $contextNav = self::context_nav($context, '', true);
            $currentId  = " id='{$context}_menu'";
            $out .= str_replace(array('{class}', '{extra}', '{menu}'), array(self::$templateContextNavMobileClass, $currentId, $contextNav), self::$templateContextNav);
		}

		return $out;
	}//end render_mobile_navs()

	//--------------------------------------------------------------------


	/**
	 * Builds the main navigation menu for each context.
	 *
	 * @access public
	 *
	 * @param string $context   The context to build the nav for.
	 * @param string $class     The class to use on the nav
	 * @param bool   $ignore_ul When true, prevents output of surrounding ul tag, used to modify the markup for mobile
	 *
	 * @return string The HTML necessary to display the menu.
	 */
	public static function context_nav($context=null, $class='dropdown-menu', $ignore_ul=false)
	{
		// Get a list of modules with a controller matching
		// $context ('content', 'settings', 'reports', or 'developer')
		$module_list = module_list();

		foreach ($module_list as $module) {
			if (Modules::controller_exists($context, $module) === true) {
				$mod_config = Modules::config($module);

				self::$actions[$module] = array(
					'weight'		=> isset($mod_config['weights'][$context]) ? $mod_config['weights'][$context] : 0,
					'display_name'	=> isset($mod_config['name']) ? $mod_config['name'] : $module,
					'title' 		=> isset($mod_config['description']) ? $mod_config['description'] : $module,
					'menus'			=> isset($mod_config['menus']) ? $mod_config['menus'] : false,
				);

                // This is outside the array because the else portion uses the 'display_name' value,
				self::$actions[$module]['menu_topic'] = isset($mod_config['menu_topic']) ? $mod_config['menu_topic'] : self::$actions[$module]['display_name'];
			}
		}

		unset($module_list);

		// Do we have any actions?
		if ( ! count(self::$actions)) {
            return str_replace(array('{class}', '{extra}', '{menu}'), array($class, '', ''), self::$templateContextNav);
		}

		// Order our actions by their weight, then alphabetically
		self::sort_actions();

		// Build up our menu array
        $ucContext = ucfirst($context);
		foreach (self::$actions as $module => $config) {
			if (has_permission('Bonfire.' . ucfirst($module) . '.View') || has_permission(ucfirst($module) . '.' . $ucContext . '.View')) {
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

	}//end context_nav()

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !BUILDER METHODS
	//--------------------------------------------------------------------

	/**
	 * Creates everything needed for a new context to run. Includes
	 * creating permissions, assigning them to certain roles, and
	 * even creating an application migration for the permissions.
	 *
	 * @access public
	 * @static
	 *
	 * @param	string	$name	The name of the context to create.
	 * @param	array	$roles	The names or id's of the roles to give permissions to view.
	 * @param	bool	$migrate	If TRUE, will create an app migration file.
	 *
	 * @return 	bool
	 */
	public static function create_context($name='', $roles=array(), $migrate=false)
	{
		if (empty($name)) {
			self::$errors = lang('ui_no_context_name');
			return false;
		}

		// 1. Try to write it to the config file so that it will show in the menu no matter what.
		self::$ci->load->helper('config_file');

		$contexts = self::$contexts;
        $lowerName = strtolower($name);

		// If it already exists, we don't need to do anything!
		if ( ! in_array($lowerName, $contexts)) {
			array_unshift($contexts, $lowerName);

			if ( ! write_config('application', array('contexts' => $contexts), null)) {
				self::$errors[] = lang('ui_cant_write_config');
				return false;
			}
		}

        // 2. Create our permissions
		$cname = 'Site.' . ucfirst($name) . '.View';

		// First - create the actual permission
		self::$ci->load->model('permissions/permission_model');

		if ( ! self::$ci->permission_model->permission_exists($cname)) {
			$pid = self::$ci->permission_model->insert(array(
				'name'			=> $cname,
				'description'	=> 'Allow user to view the ' . ucwords($name) . ' Context.',
			));
		} else {
			$pid = self::$ci->permission_model->find_by('name', $cname)->permission_id;
			$exists = true;
		}

		// Do we have any roles to apply this to?
		// If we don't we can quit since there won't be anything to migrate.
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

		// If we made it here, we were successful!
		return true;
	}

	//--------------------------------------------------------------------

	//--------------------------------------------------------------------
	// !UTILITY METHODS
	//--------------------------------------------------------------------

	/**
	 * Takes an array of key/value pairs and sets the class/id names.
	 *
	 * @access public
	 * @static
	 *
	 * @param array $attrs An array of key/value pairs that correspond to the class methods for classes and ids.
	 *
	 * @return void
	 */
	public static function set_attrs($attrs=array())
	{
		if ( ! is_array($attrs)) {
			return null;
		}

		foreach ($attrs as $attr => $value) {
			if (isset(self::$attr)) {
				self::$attr = $value;
			}
		}
	}//end set_attrs()

	//--------------------------------------------------------------------

	/**
	 * Handles building out the HTML for the menu.
	 *
	 * @access public
	 * @static
	 *
	 * @param string $context   The context to build the nav for.
	 * @param bool   $ignore_ul
	 *
	 * @return string HTML for the sub menu
	 */
	public static function build_sub_menu($context, $ignore_ul=false)
	{
		$list           = '';
        $childClass     = self::$child_class;
        $search = array('{submenu_class}', '{url}', '{display}', '{child_class}', '{view}');

		foreach (self::$menu as $topic_name => $topic) {
			// If there is more than one item in the topic, we need to build
			// out a menu based on the multiple items.
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

        return str_replace(array('{class}', '{extra}', '{menu}'), array($childClass, '', $list), self::$templateContextNav);
	}//end build_sub_menu()

	//--------------------------------------------------------------------


	/**
	 * Handles building an individual list item (with sub-menus) for the menu.
	 *
	 * @access private
	 * @static
	 *
	 * @param string $module       The name of the module this link belongs to
	 * @param string $title        The title used on the link
	 * @param string $display_name The name to display in the menu
	 * @param string $context      The name of the context
	 * @param string $menu_view    The name of the view file that contains the sub-menu
	 *
	 * @return string The HTML necessary for a single item and it's sub-menus.
	 */
	private static function build_item($module, $title, $display_name, $context, $menu_view='')
	{
        $displayName = ucwords(str_replace('_', '', $display_name));

		if (empty($menu_view)) {
            $search = array('{extra}', '{url}', '{title}', '{display}');
            $replace = array(
                $module == self::$ci->uri->segment(3) ? 'class="active" ' : '',
                site_url(self::$site_area . '/' . $context . '/' . $module),
                $title,
                $displayName,
            );
            $template = self::$templateMenu;
		}
		// Sub Menus?
		else {
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
            $template = self::$templateSubMenu;
		}

        return str_replace($search, $replace, $template);
	}//end build_item()

	//--------------------------------------------------------------------

	/**
	 * Sort the actions array
	 *
	 * @access private
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
	}//end sort_actions()

	//--------------------------------------------------------------------

}//end Contexts