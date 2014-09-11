<?php
namespace Bonfire\Libraries;

use Bonfire\Interfaces\TemplateInterface;

class OcularTemplate implements TemplateInterface
{
    /**
     * @var bool Set the debug mode on the template to output messages
     */
    protected $debug = false;

    /**
     * @var boolean If true, we won't use Session-related functions.
     * This is helpful during unit testing.
     */
    protected $ignoreSession = false;

    /**
     * @var string Prefix added to debug messages in the log
     */
    protected $logPrefix = '[Template] ';

    /**
     * @var bool If true, CodeIgniter's Template Parser will be used to parse the
     * view. If false, the view is displayed with no parsing.
     * Used by content() and block().
     */
    protected $useParser = false;

    /**
     * @var string The name of the active theme (folder) with a trailing slash.
     */
    protected $activeTheme;
    protected $defaultTheme;

    /**
     * @var array An array of blocks. The key is the name to reference it by, and
     * the value is the file. The class will loop through these, parse them, and
     * push them into the layout.
     */
    protected $blocks = array();

    /**
     * @var string The view to load. Normally not set unless you need to bypass
     * the automagic.
     */
    protected $currentView;

    /**
     * @var array The data to be passed into the views. The keys are the names of
     * the variables and the values are the values.
     */
    protected $data = array();

    /**
     * @var string The layout to render the views into.
     */
    protected $layout;
    protected $ajaxLayout;

    /**
     * @var string Holds a simple array to store the status Message that gets displayed
     * using the message() function.
     */
    protected $message;

    /**
     * @var string The full server path to the site root.
     */
    protected $sitePath;
    protected $themePaths = array();

    protected $currentVariant;
    protected $variants = array();

    /**
     * @var object An instance of the CI super object.
     */
    private $ci;

    /**
     * Grabs an instance of the CI superobject, loads the config file, and sets
     * the default layout.
     *
     * @return void
     */
    public function __construct()
    {
        $this->ci =& get_instance();

        // If the application config file hasn't been loaded, do it now.
        if (! $this->ci->config->item('template.theme_paths')) {
            $this->ci->config->load('application');
        }

        // Store our settings
        $this->ajaxLayout   = $this->ci->config->item('template.ajax_layout');
        $this->defaultTheme = $this->ci->config->item('template.default_theme');
        $this->layout       = $this->ci->config->item('template.default_layout');
        $this->useParser    = $this->ci->config->item('template.parse_views');
        $this->sitePath     = $this->ci->config->item('template.site_path');

        $tempthemePaths = $this->ci->config->item('template.theme_paths');
        foreach ($tempthemePaths as $key => $value) {
            if (is_numeric($key)) {
                $this->themePaths[$value] = $value;
            } else {
                $this->themePaths[$key] = $value;
            }
        }

        log_message('debug', 'Template library loaded');
    }

    /**
     * The main entryway into rendering a view. This is called from the controller
     * and is generally the last method called.
     *
     * @param string $layout If provided, will override the default layout.
     *
     * @return void
     */
    public function render($layout = null)
    {
        $controller = $this->ci->router->class;

        // We need to know which layout to render
        $layout = empty($layout) ? $this->layout : $layout;

        // Is it in an AJAX call? If so, override the layout
        if ($this->ci->input->is_ajax_request()) {
            $layout     = $this->ajaxLayout;
            $controller = null;
        }

        // Grab our current view name, based on controller/method
        // which routes to views/controller/method.
        if (empty($this->currentView)) {
            $this->currentView = "{$this->ci->router->class}/{$this->ci->router->method}";
        }

        // Time to render the layout
        $output = $this->display($layout, $this->data, $controller, true);
        if (empty($output)) {
            show_error("Unable to find theme layout: '{$layout}'");
        }

        // \Events::trigger('after_layout_render', $output);

        $this->ci->output->set_output($output);
    }

    /**
     * Used within the template layout file to render the current content. This
     * is typically used to display the current view.
     *
     * @return string A string containing the output of the render process.
     */
    public function content()
    {
        $this->debugMessage("Current View = '{$this->currentView}'");
        $output = $this->display($this->currentView, null, "{$this->ci->router->class}/{$this->ci->router->method}", false);

        // \Events::trigger('after_page_render', $output);

        return $output;
    }

    /**
     * Stores the name of the active theme to use. This theme should be
     * relative to one of the 'template.theme_paths' folders.
     *
     * @param string $theme         The name of the active theme.
     * @param string $defaultTheme (Optional) The name of the desired default theme.
     *
     * @return void
     */
    public function setTheme($theme, $defaultTheme = null)
    {
        if (empty($theme) || ! is_string($theme)) {
            return;
        }

        // Make sure a trailing slash is there
        if (substr($theme, -1) !== '/') {
            $theme .= '/';
        }

        $this->activeTheme = $theme;

        // Default theme?
        if (! empty($defaultTheme) && is_string($defaultTheme)) {
            $this->setDefaultTheme($defaultTheme);
        }
    }

    /**
     * Returns the active theme.
     *
     * @return string The name of the active theme.
     */
    public function theme()
    {
        return empty($this->activeTheme) ? $this->defaultTheme : $this->activeTheme;
    }

    /**
     * Set the current view to render.
     *
     * @param string $view The name of the view file to render as content.
     *
     * @return void
     */
    public function setView($view)
    {
        if (empty($view) || ! is_string($view)) {
            return;
        }

        $this->currentView = $view;
    }

    /**
     * Returns the current view.
     *
     * @return string
     */
    public function view()
    {
        return $this->currentView;
    }

    /**
     * Makes it easy to save information to be rendered within the views.
     *
     * This should probably be updated to clarify the intended functionality
     * when an array is passed into $var_name and $value is '' (and maybe
     * change the $value=='' to empty($value)?).
     *
     * @param string $var_name The name of the variable to set
     * @param mixed  $value    The value to set it to.
     *
     * @return $this
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
            return $this;
        }

        $this->data[$key] = $value;
        return $this;
    }

    /**
     * Returns a variable that has been previously set, or false if not exists.
     * As of 3.0, will also return class properties.
     *
     * @param string $var_name The name of the data item to return.
     *
     * @return mixed The value of the class property or view data, or null if the
     * $key was not found.
     */
    public function get($key)
    {
        if (empty($key)) {
            return null;
        }

        // First, is it a class property?
        if (isset($this->$key)) {
            return $this->$key;
        }

        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Set whether or not the views will be passed through CI's parser.
     *
     * @param bool $parse Boolean value. Should we parse views?
     *
     * @return $this
     */
    public function parseViews($parse = false)
    {
        $this->useParser = (bool) $parse;
        return $this;
    }

    /**
     * Theme paths allow you to have multiple locations for themes to be stored.
     * This might be used for separating themes for different sub-applications,
     * or a core theme and user-submitted themes.
     *
     * @param string $path A new path where themes can be found.
     *
     * @return bool
     */
    public function addThemePath($alias, $path)
    {
        if (empty($path) || ! is_string($path)) {
            return $this;
        }

        // Make sure the path has a '/' at the end.
        if (substr($path, -1) != '/') {
            $path .= '/';
        }

        // Make sure the folder actually exists
        if (is_dir($this->sitePath . $path)) {
            array_push($this->themePaths, $path);
            // $this->themePaths[$alias] = $path;
            return $this;
        }

        $this->debugMessage("Cannot add theme path: '{$path}' does not exist");
        return $this;
    }

    /**
     * Remove a single theme path.
     *
     * @param string $alias The alias of the path to remove from the theme paths.
     *
     * @return $this
     */
    public function removeThemePath($alias)
    {
        if (empty($alias) || ! is_string($alias)) {
            return;
        }

        unset($this->themePaths[$alias]);

        return $this;
    }

    /**
     * Loads a view based on the current themes.
     *
     * @param string $view     The view to load.
     * @param array  $data     An array of data elements to be made available to the views
     * @param string $override The name of a view to check for first (used for controller-based layouts)
     * @param bool   $themed   Whether it should check in the theme folder first.
     * @param object $output   A pointer to the variable to store the output of the loaded view into.
     *
     * @return void
     */
    public function display($view, $data = null, $override = '', $themed = true)
    {
        if (empty($view)) {
            return '';
        }

        if (empty($data)) {
            $data = $this->data;
        }

        $output = '';
        if ($themed) {
            // First check for the overridden file...
            if (! empty($override)) {
                $output = $this->findFile($override, $data);
            }

            // If we didn't find it, try the standard view
            if (empty($output)) {
                $output = $this->findFile($view, $data);
            }

            // Should it be parsed?
            if ($this->useParser === true) {
                if (! class_exists('CI_Parser')) {
                    $this->ci->load->library('parser');
                }
                $output = $this->ci->parser->parse($output, $data, true, false);
            }
        } else {
            // Just a normal view (possibly from a module, though.)
            // First check within our themes...
            $output = $this->findFile($view, $data);

            // if $output is empty, no view was overriden, so go for the default
            if (empty($output)) {
                if ($this->useParser === true) {
                    if (! class_exists('CI_Parser')) {
                        $this->ci->load->library('parser');
                    }
                    $output = $this->ci->parser->parse($view, $data, true);
                } else {
                    $output = $this->ci->load->view($view, $data, true);
                }
            }
        }

        return $output;
    }

    /**
     * Sets the variant used when creating view names. These variants can be anything,
     * but by default are used to render specific templates for desktop, tablet,
     * and phone.
     *
     * @todo implement variants
     *
     * @param $variant The name of the variant to use as the current variant.
     *
     * @return $this
     */
    public function setVariant($variant)
    {
        if (isset($this->variants[$variant])) {
            $this->currentVariant = $variant;
        }

        return $this;
    }

    /**
     * Adds a new variant to the system.
     *
     * @todo implement variants
     *
     * @param string $name    The name which will be used to reference this variant.
     * @param string $postfix The string to be added to the view name for this variant.
     *
     * @return $this
     */
    public function addVariant($name, $postfix)
    {
        $this->variants[$name] = $postfix;

        return $this;
    }

    /**
     * Removes a variant from the system.
     *
     * @todo implement variants
     *
     * @param $name The name of the variant to remove.
     *
     * @return $this
     */
    public function removeVariant($name)
    {
        unset($this->variants[$name]);

        return $this;
    }

    //--------------------------------------------------------------------------
    // !BLOCKS
    //--------------------------------------------------------------------------

    /**
     * Stores the block named $name in the blocks array for later rendering.
     * The $currentView variable is the name of an existing view. If it is empty,
     * your script should still function as normal.
     *
     * @param string $block_name The name of the block. Must match the name in the block() method.
     * @param string $view_name  The name of the view file to render.
     *
     * @return $this
     */
    public function setBlock($block_name, $view_name = '')
    {
        if (! empty($block_name)) {
            $this->blocks[$block_name] = $view_name;
        }

        return $this;
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
     * @param string $default_view The view to render if no other view has been set with the set_block() method.
     * @param array  $data         An array of data to pass to the view.
     * @param bool   $themed       Whether we should look in the themes or standard view locations.
     *
     * @return void
     */
    public function block($block_name, $default_view = '', $data = array(), $themed = false)
    {
        if (empty($block_name)) {
            $this->debugMessage('No block name provided.');
            return '';
        }

        // If a block has been set previously use it; otherwise, use the default view.
        $block_view_name = isset($this->blocks[$block_name]) ? $this->blocks[$block_name] : $default_view;

        if (empty($block_view_name) && empty($default_view)) {
            $this->debugMessage("No default block provided for '{$block_name}'.");
            return '';
        }

        $this->debugMessage("Looking for block: '{$block_view_name}'.");

        return $this->display($block_view_name, $data, false, $themed);
    }

    //--------------------------------------------------------------------------
    // Utility methods
    //--------------------------------------------------------------------------

    /**
     * Stores the name of the default theme to use. This theme should be
     * relative to one of the template.theme_paths folders.
     *
     * @param string $theme The name of the desired default theme to use.
     *
     * @return $this
     */
    public function setDefaultTheme($theme)
    {
        if (empty($theme) || ! is_string($theme)) {
            return $this;
        }

        // Make sure a trailing slash is there
        if (substr($theme, -1) !== '/') {
            $theme .= '/';
        }

        $this->defaultTheme = $theme;
        return $this;
    }

    public function setLayout($layout)
    {
        if (empty($layout) || ! is_string($layout)) {
            return $this;
        }

        $this->layout = $layout;
        return $this;
    }

    //--------------------------------------------------------------------------
    // Message
    //--------------------------------------------------------------------------

    /**
     * Sets a status message (for displaying small success/error messages).
     * This function is used in place of the session->flashdata function,
     * because you don't always want to have to refresh the page to get the
     * message to show up.
     *
     * @param string $message A string with the message to save.
     * @param string $type    A string to be included as the CSS class of the containing div.
     *
     * @return $this
     */
    public function setMessage($message = '', $type = 'info')
    {
        if (! empty($message)) {
            if (isset($this->ci->session)
                && ! $this->ignoreSession
            ) {
                $this->ci->session->set_flashdata('message', "{$type}::{$message}");
            }

            $this->message = array(
                'message' =>$message,
                'type'    =>$type,
            );
        }

        return $this;
    }

    /**
     * Displays a status message (small success/error messages).
     *
     * If data exists in 'message' session flashdata, that will override any other
     * messages. Renders the message based on the template provided in the config
     * file ('OCU_message_template').
     *
     * @param string $message The message. If included, will override any other
     * messages in the system.
     * @param string $type    The class to attach to the div (i.e. 'information',
     * 'attention', 'error', 'success').
     *
     * @return string A string with the results of inserting the message into the
     * message template.
     */
    public function message($message = '', $type = 'information')
    {
        // Does session data exist?
        if (empty($message)
            && class_exists('CI_Session')
        ) {
            $message = $this->ci->session->flashdata('message');

            if (! empty($message)) {
                // Split out our message parts
                $temp_message = explode('::', $message);
                $type    = $temp_message[0];
                $message = $temp_message[1];
                unset($temp_message);
            }
        }

        // If message is empty, we need to check our own storage.
        if (empty($message)) {
            if (empty($this->message['message'])) {
                return '';
            }

            $message = $this->message['message'];
            $type = $this->message['type'];
        }

        // Grab out message template and replace the placeholders
        $output = str_replace(
            array('{type}', '{message}'),
            array($type, $message),
            $this->ci->config->item('template.message_template')
        );

        // Clear our session data so we don't get extra messages.
        // (This was a very rare occurence, but clearing should resolve the problem.
        if (! $this->ignoreSession
            && class_exists('CI_Session')
        ) {
            $this->ci->session->set_flashdata('message', '');
        }

        return $output;
    }

    /**
     * Debugging script to echo out message to the Console (if loaded) and log.
     *
     * By default it will only log the messages if $this->debug == true,
     * but this behaviour can be modified by passing $force as true.
     *
     * @param  string  $message The message to log
     * @param  boolean $force   If false, will respect $this->debug setting.
     *                          If true, will force the message to be logged.
     *
     * @return void
     */
    protected function debugMessage($message, $force = false)
    {
        // Only log the message when in debug mode or when forced, to avoid
        // cluttering applications.
        if ($this->debug || $force) {
            logit($this->logPrefix . $message);
        }
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
    private function findFile($view = null, $data = null)
    {
        if (empty($view)) {
            return false;
        }

        $data = empty($data) ? array() : (array) $data;

        $activeThemeSet = ! empty($this->activeTheme); // Is the active theme set?
        $view_file      = $view . '.php'; // filename for the view
        $view_path      = ''; // The location of the file.

        // When $this->themePaths includes multiple locations, the last takes precedence
        // for the search. Instead of searching forward through the list and replacing
        // the $view_path when later views are found, reverse the array and break
        // the loop when the file is found.
        $theme_locations = array_reverse($this->themePaths);
        foreach ($theme_locations as $path) {
            $site_theme_path = $this->sitePath . $path . '/';

            // First, check the active theme
            if ($activeThemeSet) {
                $activeThemePath = $site_theme_path . $this->activeTheme;
                $this->debugMessage("[Find File] Looking for view in active theme: '{$activeThemePath}{$view_file}'");

                if (is_file($activeThemePath . $view_file)) {
                    $view_path = $activeThemePath;
                    $this->debugMessage("Found '{$view}' in active theme.");
                    // exit the loop
                    break;
                }
            }

            // Either the active theme is not set or the file was not found in the
            // active theme, so try the default theme.
            $defaultTheme_path = $site_theme_path . $this->defaultTheme;
            $this->debugMessage("[Find File] Looking for view in default theme: '{$defaultTheme_path}{$view_file}'");

            if (is_file($defaultTheme_path . $view_file)) {
                $view_path = $defaultTheme_path;
                $this->debugMessage("Found '{$view}' in default theme.");
                // exit the loop
                break;
            }
        }

        // If the view was found, its path is stored in $view_path.
        if (empty($view_path)) {
            return '';
        }

        // Load the view.
        $view_path = str_replace('//', '/', $view_path);
        $this->debugMessage("[Find File] Loading file at: '{$view_path}{$view_file}'");

        // Grab the output of the view.
        if ($this->useParser === true) {
            $data = array_merge((array) $data, $this->ci->load->_ci_cached_vars);
        }

        return $this->ci->load->_ci_load(array(
            '_ci_path'   => $view_path . $view_file,
            '_ci_vars'   => $data,
            '_ci_return' => true,
        ));
    }

    //--------------------------------------------------------------------------
    // URL Methods
    //--------------------------------------------------------------------------

    /**
     * Like CodeIgniter redirect(), but uses javascript if needed to redirect out
     * of an ajax request.
     *
     * @param string $url The url to redirect to. If not a full url, will wrap it
     * in site_url().
     *
     * @return void
     */
    public function redirect($url = null)
    {
        if (! preg_match('#^https?://#i', $url)) {
            $url = site_url($url);
        }

        if (! $this->ci->input->is_ajax_request()) {
            header("Location: {$url}");

            // Since the default HTML content type requires certain elements to
            // be present in order to be valid, set it to something that doesn't
            // require any content to be present.
            header("Content-Type: text/plain");
        } else {
            // Output the URL somewhere where we know how to escape it safely.
            echo '<div id="url" data-url="';
            e($url);
            echo '"></div>';

            // then JS can grab it
            echo <<<EOF
<script>
window.location = document.getElementById('url').getAttribute('data-url');
</script>
EOF;
        }

        exit();
    }

    /**
     * Returns the full url to a file in the currently active theme.
     *
     * @param string $resource Path to a resource in the theme
     *
     * @return string The full url (including http://) to the resource.
     */
    public function themeUrl($resource = '')
    {
        $url = base_url();

        // Add theme path
        reset($this->themePaths);
        $url .= current($this->themePaths) . '/' . $this->theme();

        // Cleanup, just to be safe
        $url = str_replace(array('//', ':/'), array('/', '://'), $url);

        return $url . $resource;
    }

    /**
     * A simple helper method for checking menu items against the currently
     * executing class/controller.
     *
     * <code>
     *   <a href="<?php echo site_url(SITE_AREA . '/content'); ?>" <?php echo checkClass(SITE_AREA . '/content'); ?> >
     *    Admin Home
     *  </a>
     *
     * </code>
     *
     * @param string $item       The name of the class to check against.
     * @param bool   $class_only If true, will only return 'active'. If false, will return 'class="active"'.
     *
     * @return string Either <b>class="active"</b> or an empty string.
     */
    public function checkClass($item = '', $class_only = false)
    {
        if (strtolower($this->ci->router->fetch_class()) == strtolower($item)) {
            return $class_only ? 'active' : 'class="active"';
        }

        return '';
    }

    /**
     * A simple helper method for checking menu items against the method currently
     * being executed (as far as the Router knows.)
     *
     * @param string    $item       The name of the method to check against. Can be an array of names.
     * @param bool      $class_only If true, will only return 'active'. If false, will return 'class="active"'.
     *
     * @return string Either <b>class="active"</b> or an empty string.
     */
    public function checkMethod($item, $class_only = false)
    {
        $items = is_array($item) ? $item : array($item);

        if (in_array($this->ci->router->fetch_method(), $items)) {
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
     * @param   bool    $class_only     If true, will only return 'active'. If false,
     * will return 'class="active"'.
     *
     * @return string An empty string if $item did not match the segment, else
     * 'active' or 'class="active"', depending on the value of $class_only.
     */
    public function checkSegment($segment_num, $item, $class_only = false)
    {
        if ($this->ci->uri->segment($segment_num) == $item) {
            return $class_only ? 'active' : 'class="active"';
        }

        return '';
    }

    /**
     * Will create a breadcrumb from either the uri->segments or from a key/value
     * paired array.
     *
     * @param array $my_segments (optional) Array of Key/Value to make Breadcrumbs from
     * @param bool  $wrap        (boolean)  Set to true to wrap in un-ordered list
     * @param bool  $echo        (boolean)  Set to true to echo the output, set to false to return it.
     *
     * @return string A Breadcrumb of your page structure.
     */
    public function breadcrumb($my_segments = null, $wrap = false, $echo = true)
    {
        if (! class_exists('CI_URI')) {
            $this->ci->load->library('uri');
        }

        if ($this->ci->config->item('template.breadcrumb_symbol') == '') {
            $separator = '/';
        } else {
            $separator = $this->ci->config->item('template.breadcrumb_symbol');
        }

        if (empty($my_segments) || ! is_array($my_segments)) {
            $segments = $this->ci->uri->segment_array();
            $total    = $this->ci->uri->total_segments();
        } else {
            $segments = $my_segments;
            $total    = count($my_segments);
        }

        // Are we in the admin section of the site?
        if (is_array($segments) && in_array(SITE_AREA, $segments)) {
            $home_link = site_url(SITE_AREA);
        } else {
            $home_link = site_url();
        }

        $output = '';
        if ($wrap === true) {
            $separator = "<span class='divider'>{$separator}</span>\n";
            $output  = "<ul class='breadcrumb'>\n";
            $output .= "<li><a href='{$home_link}'><span class='icon-home'></span></a> {$separator}</li>\n";
        } else {
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
                    if ($wrap === true) {
                        $output .= "<li class='active'>" . ucfirst(str_replace('_', ' ', $segment)) . "</li>\n";
                    } else {
                        $output .= ucfirst(str_replace('_', ' ', $segment)) . "\n";
                    }
                } else {
                    if ($wrap === true) {
                        $output .= "<li><a href='{$url}'>"
                                . str_replace('_', ' ', ucfirst(mb_strtolower($segment)))
                                . "</a> {$separator}</li>\n";
                    } else {
                        $output .= "<a href='{$url}'>"
                                . str_replace('_', ' ', ucfirst(mb_strtolower($segment)))
                                . "</a>{$separator}\n";
                    }
                }
            }
        } else {
            // USER-SUPPLIED BREADCRUMB
            foreach ($my_segments as $title => $segment) {
                $url .= "/{$segment}";
                ++$count;
                if ($count == $total) {
                    if ($wrap === true) {
                        $output .= "<li class='active'>" . str_replace('_', ' ', $title) . "</li>\n";
                    } else {
                        $output .= str_replace('_', ' ', $title);
                    }
                } else {
                    if ($wrap === true) {
                        $output .= "<li><a href='{$url}'>"
                                . str_replace('_', ' ', ucfirst(mb_strtolower($title)))
                                . "</a> {$separator}</li>\n";
                    } else {
                        $output .= "<a href='{$url}'>"
                                . str_replace('_', ' ', ucfirst(mb_strtolower($title)))
                                . "</a>{$separator}\n";
                    }
                }
            }
        }

        if ($wrap === true) {
            $output .= "\n</ul>\n";
        }

        if ($echo === true) {
            echo $output;
        }

        return $output;
    }
}
