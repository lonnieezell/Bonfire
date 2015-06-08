<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
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
 * Template
 *
 * The Template class makes the creation of consistently themed web pages across
 * an entire site simple and as automatic as possible.
 *
 * Supports parent/child themes, controller-named automatic overrides, and more.
 *
 * @package Bonfire\Libraries\Template
 * @author  Bonfire Dev Team
 * @version 3.0
 * @link    http://cibonfire.com/docs/developer/layouts_and_views
 */
class Template
{
    /** @var array Named blocks and path/filename of views for those blocks. */
    public static $blocks = array();

    /** @var boolean Set the debug mode on the template to output messages. */
    public static $debug = false;

    /**
     * Disable Session use, primarily for unit testing.
     *
     * @deprecated since 0.7.3 use setSessionUse(). This will likely become a protected
     * property in a future version. Note that setSessionUse() expects the opposite
     * value of $ignore_session, so setSessionUse(false) is equivalent to setting
     * $ignore_session = true.
     *
     * @var boolean
     */
    public static $ignore_session = false;

    /**
     * @var string The layout into which views will be rendered.
     *
     * @deprecated since 0.7.1 This will become a protected property. Use setLayout()
     * and getLayout().
     */
    public static $layout;

    /** @var boolean When true, CI's Parser will be used to parse the views. */
    public static $parse_views = false;

    /** @var string The full server path to the site root. */
    public static $site_path;


    /** @var string The active theme directory, with a trailing slash. */
    protected static $active_theme = '';

    /** @var string The view to load. Set in some methods to override automagic. */
    protected static $current_view;

    /** @var array Variable names and their values to be passed into the views. */
    protected static $data = array();

    /** @var string The default theme ('template.default_theme' in the config). */
    protected static $default_theme = '';

    /** @var string Prefix added to debug messages in the log. */
    protected static $log_prefix = '[Template] ';

    /**
     * @var array Status message.
     * The 'type' stores the type of message.
     * The 'message' stores the message itself.
     */
    protected static $message;

    /** @var string CI's default view path. */
    protected static $orig_view_path;

    /** @var array The paths to the themes. */
    protected static $theme_paths = array();


    /** @var {CI} An instance of the CI super object. */
    private static $ci;

    //--------------------------------------------------------------------------

    /**
     * This constructor is here purely for CI's benefit, as this is a static class.
     *
     * @return void
     */
    public function __construct()
    {
        self::$ci =& get_instance();

        self::init();
    }

    /**
     * Grabs an instance of the CI superobject, loads the Ocular config file, and
     * sets our default layout.
     *
     * @return void
     */
    public static function init()
    {
        // If the application config file hasn't been loaded, do it now
        if (! self::$ci->config->item('template.theme_paths')) {
            self::$ci->config->load('application');
        }

        // Store our settings
        self::$default_theme = self::$ci->config->item('template.default_theme');
        self::$layout        = self::$ci->config->item('template.default_layout');
        self::$parse_views   = self::$ci->config->item('template.parse_views');
        self::$site_path     = self::$ci->config->item('template.site_path');
        self::$theme_paths   = self::$ci->config->item('template.theme_paths');

        log_message('debug', 'Template library loaded');
    }

    /**
     * Get the name of the layout into which the views will be rendered.
     *
     * The default layout is stored in the config file as 'template.default_layout'.
     *
     * @return string
     */
    public static function getLayout()
    {
        return self::$layout;
    }

    /**
     * Specify the layout into which the views will be rendered.
     *
     * Allows overriding the default layout. This is especially useful to set a
     * default layout for a controller which overrides the default layout of the
     * application.
     *
     * @param string $layout The name of the layout.
     *
     * @return void
     */
    public static function setLayout($layout)
    {
        self::$layout = $layout;
    }

    /**
     * Enable/disable the library's use of sessions.
     *
     * This is primarily used by the installer (when sessions are not likely to
     * be available), but is also useful for testing.
     *
     * @param boolean $useSession If true, the library uses sessions. If false,
     * the library will not use sessions.
     *
     * @return void
     */
    public static function setSessionUse($useSession = true)
    {
        self::$ignore_session = ! $useSession;
    }

    /**
     * Renders the specified layout.
     *
     * Starts the process of rendering the page content and determines the correct
     * view to use based on the current controller/method.
     *
     * @uses Output Calls CI's output->set_output() to render the layout.
     *
     * @param  string $layout The name of a layout to override the current layout.
     *
     * @return void
     */
    public static function render($layout = null)
    {
        // Determine whether to override the current layout.
        $layout = empty($layout) ? self::$layout : $layout;

        // If the current view has not been set, use the current controller/method.
        $controller = self::$ci->router->class;
        if (empty(self::$current_view)) {
            self::$current_view = "{$controller}/" . self::$ci->router->method;
        }

        // Override the layout if this is an AJAX request.
        if (self::$ci->input->is_ajax_request()) {
            $layout = self::$ci->config->item('template.ajax_layout');

            // $controller is passed to load_view to set a controller-based override
            // of the layout, which should not be done for AJAX requests.
            $controller = '';
        }

        // Time to render the layout.
        $output = '';
        self::load_view($layout, self::$data, $controller, true, $output);

        if (empty($output)) {
            show_error("Unable to find theme layout: {$layout}");
        }

        Events::trigger('after_layout_render', $output);

        self::$ci->output->set_output($output);
    }

    /**
     * Renders the current view into the layout.
     *
     * The name of the view is usually based on the controller/action being run.
     * @see render().
     *
     * @return string A string containing the output of the render process.
     */
    public static function content()
    {
        self::debug_message('Current View = ' . self::$current_view);

        $output = '';
        self::load_view(
            self::$current_view,
            null,
            self::$ci->router->class . '/' . self::$ci->router->method,
            false,
            $output
        );

        Events::trigger('after_page_render', $output);

        return $output;
    }

    //--------------------------------------------------------------------------
    // !BLOCKS
    //--------------------------------------------------------------------------

    /**
     * Stores the block named $name in the blocks array for later rendering.
     * The $current_view variable is the name of an existing view. If it is empty,
     * your script should still function as normal.
     *
     * @param string $block_name The name of the block. Must match the name in the block() method.
     * @param string $view_name  The name of the view file to render.
     *
     * @return void
     */
    public static function set_block($block_name = '', $view_name = '')
    {
        if (! empty($block_name)) {
            self::$blocks[$block_name] = $view_name;
        }
    }

    /**
     * Renders a "block" to the view.
     *
     * A block is a partial view contained in a view file in the application/views
     * folder. It can be used for sidebars, headers, footers, or any other recurring
     * element within a site. It is recommended to set a default when calling this
     * function within a layout. The default will be rendered if no methods override
     * the view (using the set_block() method).
     *
     * @param string $block_name   The name of the block to render.
     * @param string $default_view The view to render if no other view has been set
     * with the set_block() method.
     * @param array  $data         An array of data to pass to the view.
     * @param bool   $themed       Whether we should look in the themes or standard
     * view locations.
     *
     * @return void
     */
    public static function block($block_name = '', $default_view = '', $data = array(), $themed = false)
    {
        if (empty($block_name)) {
            self::debug_message('No block name provided.');
            return;
        }

        // Use $default_view if the block has not been set.
        $block_view_name = isset(self::$blocks[$block_name]) ? self::$blocks[$block_name] : $default_view;

        if (empty($block_view_name) && empty($default_view)) {
            self::debug_message("No default block provided for `{$block_name}`");
            return;
        }

        self::debug_message("Looking for block: <b>{$block_view_name}</b>.");

        $output = '';
        self::load_view($block_view_name, $data, false, $themed, $output);

        echo $output;
    }

    //--------------------------------------------------------------------------
    // !THEME PATHS
    //--------------------------------------------------------------------------

    /**
     * Theme paths allow you to have multiple locations for themes to be stored.
     * This might be used for separating themes for different sub-applications,
     * or a core theme and user-submitted themes.
     *
     * @param string $path A new path where themes can be found.
     *
     * @return bool Returns true if the path already exists. Otherwise, returns
     * false, even if the path was successfully added...
     */
    public static function add_theme_path($path = null)
    {
        if (empty($path) || ! is_string($path)) {
            return false;
        }

        // Make sure the path has a '/' at the end.
        if (substr($path, -1) != '/') {
            $path .= '/';
        }

        // If the path already exists, we're done here.
        if (isset(self::$theme_paths[$path])) {
            return true;
        }

        // Make sure the folder actually exists.
        if (is_dir(self::$site_path . $path)) {
            array_push(self::$theme_paths, $path);
            return false;
        }

        self::debug_message("Cannot add theme path: '{$path}' does not exist");
        return false;
    }

    /**
     * Remove the theme path
     *
     * @param string $path The path to remove from the theme paths.
     *
     * @return void
     */
    public static function remove_theme_path($path = null)
    {
        if (empty($path) || ! is_string($path)) {
            return;
        }

        if (isset(self::$theme_paths[$path])) {
            unset(self::$theme_paths[$path]);
        }
    }

    /**
     * Stores the name of the active theme to use. This theme should be relative
     * to one of the 'template.theme_paths' folders.
     *
     * @param string $theme         The name of the active theme.
     * @param string $default_theme (Optional) The name of the desired default theme.
     *
     * @return void
     */
    public static function set_theme($theme = null, $default_theme = null)
    {
        if (empty($theme) || ! is_string($theme)) {
            return;
        }

        // Make sure a trailing slash is there
        if (substr($theme, -1) !== '/') {
            $theme .= '/';
        }

        self::$active_theme = $theme;

        // Default theme?
        if (! empty($default_theme) && is_string($default_theme)) {
            self::set_default_theme($default_theme);
        }
    }

    /**
     * Stores the name of the default theme to use. This theme should be relative
     * to one of the template.theme_paths folders.
     *
     * @param string $theme The name of the desired default theme to use.
     *
     * @return void
     */
    public static function set_default_theme($theme = null)
    {
        if (empty($theme) || ! is_string($theme)) {
            return;
        }

        // Make sure a trailing slash is there
        if (substr($theme, -1) !== '/') {
            $theme .= '/';
        }

        self::$default_theme = $theme;
    }

    /**
     * Returns the active theme.
     *
     * @return string The name of the active theme.
     */
    public static function theme()
    {
        return empty(self::$active_theme) ? self::$default_theme : self::$active_theme;
    }

    /**
     * Returns the full url to a file in the currently active theme.
     *
     * @param string $resource Path to a resource in the theme
     *
     * @return string The full url (including http://) to the resource.
     */
    public static function theme_url($resource = '')
    {
        $url = base_url();

        // Add theme path and theme.
        $url .= self::$theme_paths[0] . '/' . self::theme();

        // Cleanup, just to be safe.
        $url = str_replace(array('//', ':/'), array('/', '://'), $url);

        return $url . $resource;
    }

    /**
     * Set the current view to render.
     *
     * @param string $view The name of the view file to render as content.
     *
     * @return void
     */
    public static function set_view($view = null)
    {
        if (empty($view) || ! is_string($view)) {
            return;
        }

        self::$current_view = $view;
    }

    /**
     * Makes it easy to save information to be rendered within the views.
     *
     * This should probably be updated to clarify the intended functionality when
     * an array is passed into $var_name and $value is ''.
     *
     * @todo If $var_name is an array and $value != '', the else condition will
     * probably be problematic.
     *
     * @param string $var_name The name of the variable to set
     * @param mixed  $value    The value to set it to.
     *
     * @return void
     */
    public static function set($var_name = '', $value = '')
    {
        if (is_array($var_name) && $value == '') {
            foreach ($var_name as $key => $val) {
                self::$data[$key] = $val;
            }
        } else {
            self::$data[$var_name] = $value;
        }
    }

    /**
     * Returns a variable that has been previously set, or false if not exists.
     * As of 3.0, will also return class properties.
     *
     * @param string $var_name The name of the data item to return.
     *
     * @return mixed The value of the class property or view data.
     */
    public static function get($var_name = null)
    {
        if (empty($var_name)) {
            return false;
        }

        // First, is it a class property?
        if (isset(self::$$var_name)) {
            return self::$$var_name;
        }

        if (isset(self::$data[$var_name])) {
            return self::$data[$var_name];
        }

        return false;
    }

    /**
     * Enable/disable passing views through CI's Parser.
     *
     * @param  boolean $parse If true, the views will be parsed.
     * @return void
     */
    public function parse_views($parse = false)
    {
        self::$parse_views = (bool) $parse;
    }

    /**
     * Sets a status message (for displaying small success/error messages).
     *
     * This function is used in place of the session->flashdata function to allow
     * the message to show up without requiring a page refresh.
     *
     * @param string $message The text of the message.
     * @param string $type    The type of message, usually added as the value of
     * the class attribute on the message's container.
     *
     * @return void
     */
    public static function set_message($message = '', $type = 'info')
    {
        if (empty($message)) {
            return;
        }

        if (! self::$ignore_session && isset(self::$ci->session)) {
            self::$ci->session->set_flashdata('message', "{$type}::{$message}");
        }

        self::$message = array('type' => $type, 'message' => $message);
    }

    /**
     * Displays a status message (small success/error messages).
     *
     * If data exists in 'message' session flashdata, that will override any other
     * messages. Renders the message based on the template provided in the config
     * file ('template.message_template').
     *
     * @param string $message A string to be the message. (Optional) If included, will override any other messages in the system.
     * @param string $type    The class to attached to the div. (i.e. 'information', 'attention', 'error', 'success')
     *
     * @return string A string with the results of inserting the message into the message template.
     */
    public static function message($message = '', $type = 'information')
    {
        // Does session data exist?
        if (empty($message)
            && ! self::$ignore_session
            && class_exists('CI_Session', false)
        ) {
            $message = self::$ci->session->flashdata('message');
            if (! empty($message)) {
                // Split out the message parts
                $temp_message = explode('::', $message);
                $type = $temp_message[0];
                $message = $temp_message[1];

                unset($temp_message);
            }
        }

        // If message is empty, check the $message property.
        if (empty($message)) {
            if (empty(self::$message['message'])) {
                return '';
            }

            $message = self::$message['message'];
            $type = self::$message['type'];
        }

        // Get the message template and replace the placeholders.
        $template = str_replace(
            array('{type}', '{message}'),
            array($type, $message),
            self::$ci->config->item('template.message_template')
        );

        // Clear the session data to prevent extra messages. (This was a very rare
        // occurence, but clearing should resolve the problem.)
        if (! self::$ignore_session && class_exists('CI_Session', false)) {
            self::$ci->session->set_flashdata('message', '');
        }

        return $template;
    }

    /**
     * Like CodeIgniter redirect(), but uses javascript if needed to redirect out
     * of an ajax request.
     *
     * @param string $url The url to redirect to. If not a full url, will wrap it
     * in site_url().
     *
     * @return void
     */
    public static function redirect($url = null)
    {
        if (! preg_match('#^https?://#i', $url)) {
            $url = site_url($url);
        }

        if (! self::$ci->input->is_ajax_request()) {
            header("Location: {$url}");

            // The default header specifies the content type as HTML, which requires
            // certain elements to be considered valid. No content is included,
            // so use a content type which does not require any.
            header("Content-Type: text/plain");
        } else {
            // Output URL in a known location and escape it for safety.
            echo '<div id="url" data-url="';
            e($url);
            echo '"></div>';

            // Now JS can grab the URL and perform the redirect.
            echo <<<EOF
<script>
window.location = document.getElementById('url').getAttribute('data-url');
</script>
EOF;
        }

        exit();
    }

    /**
     * Load a view based on the current themes.
     *
     * @param string $view      The view to load.
     * @param array  $data      An array of data elements to be made available to the views
     * @param string $override  The name of a view to check for first (used for controller-based layouts)
     * @param bool   $is_themed Whether it should check in the theme folder first.
     * @param object $output    A pointer to the variable to store the output of the loaded view into.
     *
     * @return void
     */
    public static function load_view($view = null, $data = null, $override = '', $is_themed = true, &$output)
    {
        if (empty($view)) {
            return '';
        }

        if (empty($data)) {
            $data = self::$data;
        }

            $output = '';
        if ($is_themed) {
            // First check for the overridden file...
            if (! empty($override)) {
                $output = self::find_file($override, $data);
            }

            // If it wasn't found, try the standard view
            if (empty($output)) {
                $output = self::find_file($view, $data);
            }

            // Should it be parsed?
            if (self::$parse_views === true) {
                if (! class_exists('CI_Parser', false)) {
                    self::$ci->load->library('parser');
                }

                $output = self::$ci->parser->parse($output, $data, true, false);
            }
        } else {
            // Just a normal view (possibly from a module, though.)

            // First check within the themes...
            $output = self::find_file($view, $data);

            // If it wasn't found, go for the default.
            if (empty($output)) {
                self::$ci->load->_ci_view_path = self::$orig_view_path;

                if (self::$parse_views === true) {
                    if (! class_exists('CI_Parser', false)) {
                        self::$ci->load->library('parser');
                    }

                    $output = self::$ci->parser->parse($view, $data, true);
                } else {
                    $output = self::$ci->load->view($view, $data, true);
                }
            }
            self::$ci->load->_ci_view_path = self::$orig_view_path;
        }
    }

    /**
     * Load a view (from the current theme) and return the content of that view.
     *
     * Allows for simple mobile templates by checking for a filename prefixed with
     * 'mobile_' when loading the view (if $ignoreMobile is false). For example,
     * if $view is 'index', the mobile version would be 'mobile_index'. If the file
     * is not found with the mobile prefix, it will load the regular view.
     *
     * @param string  $view         The name of the view to load.
     * @param array   $data         An array of data to pass to the view.
     * @param boolean $ignoreMobile Disable loading mobile_ prefixed views (if true).
     *
     * @return string The content of the loaded view.
     */
    public static function themeView($view = null, $data = null, $ignoreMobile = false)
    {
        if (empty($view)) {
            return '';
        }

        $output = '';

        // If allowed, try to load the mobile version of the file.
        if (! $ignoreMobile) {
            self::$ci->load->library('user_agent');
            if (self::$ci->agent->is_mobile()) {
                self::load_view("mobile_{$view}", $data, null, true, $output);
            }
        }

        // If output is empty, either mobile is ignored or no mobile file was found.
        if (empty($output)) {
            self::load_view($view, $data, null, true, $output);
        }

        return $output;
    }

    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Searches through the the active theme and the default theme to try to find
     * a view file. If found, it returns the rendered view.
     *
     * @param string $view The name of the view to find.
     * @param array  $data An array of key/value pairs to pass to the views.
     *
     * @return string The content of the file, if found, else empty.
     */
    private static function find_file($view = null, $data = null)
    {
        if (empty($view)) {
            return false;
        }

        if (! empty($data)) {
            $data = (array)$data;
        }

        $view_path = ''; // The location of the file.
        $view_file = "{$view}.php"; // filename for the view
        $active_theme_set = ! empty(self::$active_theme);   // Is the active theme set?

        // In most cases, self::$theme_paths will only include one location.
        // When it does not, the last will take precedence for the search.
        // Reverse the $theme_paths array and break the loop when the file is found.
        $theme_locations = array_reverse(self::$theme_paths);

        // Search through the theme locations.
        foreach ($theme_locations as $path) {
            $site_theme_path = self::$site_path . "{$path}/";

            // First, check the active theme
            $active_theme_path = $site_theme_path . self::$active_theme;
            self::debug_message("[Find File] Looking for view in active theme: '{$active_theme_path}{$view_file}'");

            if ($active_theme_set && is_file($active_theme_path . $view_file)) {
                // If the view was found, set the view path and exit the loop.
                $view_path = $active_theme_path;
                self::debug_message("Found '{$view}' in Active Theme.");
                break;
            }

            // Next, check the default theme.
            $default_theme_path = $site_theme_path . self::$default_theme;
            self::debug_message("[Find File] Looking for view in default theme: '{$default_theme_path}{$view_file}'");

            if (is_file($default_theme_path . $view_file)) {
                // If the view was found, set the view path and exit the loop.
                $view_path = $default_theme_path;
                self::debug_message("Found '{$view}' in Default Theme.");
                break;
            }
        }

        // If $view_path is empty, the view was not found.
        if (empty($view_path)) {
            return '';
        }

        // Parse or render the view based on current settings.

        // Clean up the view path, to be safe.
            $view_path = str_replace('//', '/', $view_path);
        self::debug_message("[Find File] Rendering file at: '{$view_path}{$view_file}'");

        // Get the output of the view.
        if (self::$parse_views === true) {
            $data = array_merge((array) $data, self::$ci->load->_ci_cached_vars);
        }

        return self::$ci->load->_ci_load(
            array(
                '_ci_path' => $view_path . $view_file,
                '_ci_vars' => $data,
                '_ci_return' => true,
            )
        );
    }

    /**
     * Debugging script to echo out message to the Console (if loaded) and to the
     * log files.
     *
     * By default it will only log the messages if self::$debug == true, but this
     * behaviour can be modified by passing $force as true.
     *
     * @param  string  $message The message to log.
     * @param  boolean $force   If false, will respect self::$debug setting. If
     * true, will force the message to be logged.
     *
     * @return void
     */
    protected static function debug_message($message, $force = false)
    {
        // Only echo the message when in debug mode.
        if (self::$debug) {
            echo $message;
        }

        // Log the message in debug mode or when $force is true.
        if ($force || self::$debug) {
            logit(self::$log_prefix . $message);
        }
    }
}
//end class

// -----------------------------------------------------------------------------
// Helper Methods
// -----------------------------------------------------------------------------

/**
 * A shorthand method that allows views (from the current/default themes) to be
 * included in any other view.
 *
 * This function also allows for a very simple form of mobile templates. If being
 * viewed from a mobile site, it will attempt to load a file whose name is prefixed
 * with 'mobile_'. If that file is not found it will load the regular view.
 *
 * @example Rendering a view named 'index', the mobile version would be 'mobile_index'.
 *
 * @param string $view          The name of the view to render.
 * @param array  $data          An array of data to pass to the view.
 * @param bool   $ignore_mobile If true, will not change the view name based on mobile viewing. If false, will attempt to load a file prefixed with 'mobile_'
 *
 * @return string
 */
function theme_view($view = null, $data = null, $ignore_mobile = false)
{
    return Template::themeView($view, $data, $ignore_mobile);
}

/**
 * A simple helper method for checking menu items against the current class/controller.
 *
 * <code>
 *   <a href="<?php echo site_url(SITE_AREA . '/content'); ?>" <?php echo check_class(SITE_AREA . '/content'); ?> >
 *    Admin Home
 *  </a>
 *
 * </code>
 *
 * @param string $item       The name of the class to check against.
 * @param bool   $class_only If true, will only return 'active'. If false, will
 * return 'class="active"'.
 *
 * @return string Either 'active'/'class="active"' or an empty string.
 */
function check_class($item = '', $class_only = false)
{
    if (strtolower(get_instance()->router->class) == strtolower($item)) {
        return $class_only ? 'active' : 'class="active"';
    }

    return '';
}

/**
 * A simple helper method for checking menu items against the current method
 * (controller action) (as far as the Router knows).
 *
 * @param string    $item       The name of the method to check against. Can be an array of names.
 * @param bool      $class_only If true, will only return 'active'. If false, will return 'class="active"'.
 *
 * @return string Either 'active'/'class="active"' or an empty string.
 */
function check_method($item, $class_only = false)
{
    $items = is_array($item) ? $item : array($item);
    if (in_array(get_instance()->router->fetch_method(), $items)) {
        return $class_only ? 'active' : 'class="active"';
    }

    return '';
}

/**
 * Checks the $item against the value of the specified URI segment as determined
 * by $this->uri->segment().
 *
 * @param   int     $segment_num    The segment to check the value against.
 * @param   string  $item           The value to check against the segment
 * @param   bool    $class_only     If true, will only return 'active'. If false, will return 'class="active"'.
 *
 * @return string Either 'active'/'class="active"' or an empty string.
 */
function check_segment($segment_num, $item, $class_only = false)
{
    if (get_instance()->uri->segment($segment_num) == $item) {
        return $class_only ? 'active' : 'class="active"';
    }

    return '';
}

/**
 * Will create a breadcrumb from either uri->segments or a key/value paired array.
 *
 * Uses 'template.breadcrumb_symbol' in the config for separators.
 *
 * @param array $my_segments (optional) Array of Key/Value to make Breadcrumbs from
 * @param bool  $wrap        (boolean)  Set to true to wrap in un-ordered list
 * @param bool  $echo        (boolean)  Set to true to echo the output, set to false to return it.
 *
 * @return string A Breadcrumb of the page structure.
 */
function breadcrumb($my_segments = null, $wrap = false, $echo = true)
{
    $ci =& get_instance();

    if (empty($my_segments) || ! is_array($my_segments)) {
        if (! class_exists('CI_URI', false)) {
            $ci->load->library('uri');
        }
        $segments = $ci->uri->segment_array();
        $total    = $ci->uri->total_segments();
    } else {
        $segments = $my_segments;
        $total    = count($my_segments);
    }

    // Are these segments in the admin section of the site?
    $home_link = site_url(is_array($segments) && in_array(SITE_AREA, $segments) ? SITE_AREA : '');
    $output    = '';
    $separator = $ci->config->item('template.breadcrumb_symbol') == '' ?
        '/' : $ci->config->item('template.breadcrumb_symbol');

    if ($wrap === true) {
        $separator = "<span class='divider'>{$separator}</span>" . PHP_EOL;

        $output  = "<ul class='breadcrumb'>" . PHP_EOL;
        $output .= "<li><a href='{$home_link}'><span class='icon-home'></span></a> {$separator}</li>" . PHP_EOL;
    } else {
        /** @todo Use a lang() value in place of home. */
        $output  = "<a href='{$home_link}'>home</a> {$separator}";
    }

    $url = '';
    $count = 0;

    // URI BASED BREADCRUMB
    if (empty($my_segments) || ! is_array($my_segments)) {
        foreach ($segments as $segment) {
            $url .= "/{$segment}";
            ++$count;

            if ($count == $total) {
                $currentSegment = ucfirst(str_replace('_', ' ', $segment));
                if ($wrap === true) {
                    $output .= "<li class='active'>{$currentSegment}</li>" . PHP_EOL;
                } else {
                    $output .= $currentSegment . PHP_EOL;
                }
            } else {
                $currentSegment = str_replace('_', ' ', ucfirst(mb_strtolower($segment)));
                if ($wrap === true) {
                    $output .= "<li><a href='{$url}'>{$currentSegment}</a>{$separator}</li>" . PHP_EOL;
                } else {
                    $output .= "<a href='{$url}'>{$currentSegment}</a>{$separator}" . PHP_EOL;
                }
            }
        }
    } else {
        // USER-SUPPLIED BREADCRUMB
        foreach ($my_segments as $title => $uri) {
            $url .= "/{$uri}";
            ++$count;

            if ($count == $total) {
                $currentTitle = str_replace('_', ' ', $title);
                if ($wrap === true) {
                    $output .= "<li class='active'>{$currentTitle}</li>" . PHP_EOL;
                } else {
                    $output .= $currentTitle;
                }
            } else {
                $currentTitle = str_replace('_', ' ', ucfirst(mb_strtolower($title)));
                if ($wrap === true) {
                    $output .= "<li><a href='{$url}'>{$currentTitle}</a>{$separator}</li>" . PHP_EOL;
                } else {
                    $output .= "<a href='{$url}'>{$currentTitle}</a>{$separator}" . PHP_EOL;
                }
            }
        }
    }

    if ($wrap === true) {
        $output .= "</ul>" . PHP_EOL;
    }

    if ($echo === true) {
        echo $output;
        return;
    }

    return $output;
}
