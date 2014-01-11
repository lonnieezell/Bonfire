<?php

/**
 * Menu Library
 *
 * Provides the capabilities to work with menus, creating, deleting,
 * inserting children, etc, within any CodeIgniter-based application.
 *
 * @author Lonnie Ezell
 * @license MIT
 */

class Menus {

    protected $menu_path         = '../application/menus';
    protected $ttl               = 300;
    protected $initialized       = false;
    protected $default_weight    = 50;

    protected $module_folders    = array();
    protected $contexts          = array();

    protected $full_tag_open     = '<ul class="nav">';
    protected $full_tag_close    = '</ul>';
    protected $tag_open          = '<li>';
    protected $tag_close         = '</li>';
    protected $anchor_class      = '';
    protected $parent_class      = 'dropdown-menu';
    protected $child_class       = 'dropdown';
    protected $dropdown_class    = 'dropdown-toggle';
    protected $dropdown_tag_open    = '<li class="dropdown">';

    //--------------------------------------------------------------------

    /**
     * Constructor
     *
     * @param array $params Initialization Parameters
     */
    public function __construct($params=array())
    {
        $ci =& get_instance();

        if (count($params) > 0)
        {
            foreach ($params as $key => $val)
            {
                if (isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }

        if ($this->anchor_class != '')
        {
            $this->anchor_class = 'class="'. $this->anchor_class .'" ';
        }

        if ($this->parent_class != '')
        {
            $this->parent_class = 'class="'. $this->parent_class .'" ';
        }

        if ($this->child_class != '')
        {
            $this->child_class = 'class="'. $this->child_class .'" ';
        }

        $ci->load->helper('url');

        // If no cache library is loaded, then load up a dummy cache
        if ( ! isset($ci->cache))
        {
            $ci->load->driver('cache', array('adapter' => 'file'));
        }

        log_message('debug', "Menu Class Initialized");
    }

    //--------------------------------------------------------------------

    /**
     * Displays a single menu in HTML form.
     *
     * @param  string $menu_name   The name of the menu to render.
     *
     * @return string               The resulting HTML
     */
    public function display($menu_name)
    {
        $ci =& get_instance();

        // First, get our menu structure, either from
        // the cache, or building anew...
        if ( ! $structure = $ci->cache->get('menu_'. $menu_name))
        {
            $menu = $this->readMenu($menu_name);

            $structure = $this->buildMenu($menu);

            // Save the combined menu layout to cache
            $ci->cache->save('menu_'. $menu_name, serialize($structure), $this->ttl);
        }

        // Now actually create the HTML
        return $this->renderItem($structure, true);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // STRUCTURE METHODS
    //--------------------------------------------------------------------

    /**
     * Builds out the menu structure as an multi-dimensional array.
     * This is what gets stored in the cache and is then rendered
     * to a proper menu on the fly, which is much quicker than rebuilding
     * the structure from scratch every time, but still allows us to check
     * visibility and permissions, set active elements, etc.
     *
     * @param  string $menu     The results of a readMenu() call.
     *
     * @return array            The complete structure of the menu.
     */
    public function buildMenu($menu)
    {
        if (is_string($menu))
        {
            $menu = $this->readMenu($menu);
        }

        /*
            Items
         */
        if (isset($menu['items']))
        {
            $menu['items'] = $this->constructItems($menu['items']);
        }

        /*
            Context?

            If it's a context, then menu items are built from the
            file-structure, using the Context as a reference to
            know which modules and files to use.
         */
        if (isset($menu['context']))
        {
            $menu['items'] = $this->constructContext($menu['context']);
        }

        return $menu;
    }

    //--------------------------------------------------------------------

    /**
     * Builds out the items array. Handles all of the various form of
     * items, such as menus, contexts, and even regular old items.
     *
     * @param  array $items The 'items' array from a menu.
     *
     * @return array        The built-out items.
     */
    public function constructItems($items)
    {
        $structure = array();

        $count = 0;

        foreach ($items as $item)
        {
            // Parse any route we might have.
            if (isset($item['route']))
            {
                $item['route'] = $this->parseRoute($item['route']);
            }

            /*
                Child Menu Files?

                We can reference other menu files within a menu file making for
                easier organization of demands, etc. These are stored in the
                'menu' key so pull those in if we have any.
             */
            if (isset($item['menu']) && ! empty($item['menu']))
            {
                $temp = $this->buildMenu($item['menu']);

                $slug = isset($temp['slug']) ? $temp['slug'] : url_title($temp['title'], '_', true);

                $structure[ strtolower($slug) ] = $temp;
                unset($temp);
            }

            /*
             * Context?
             */
            else if (isset($item['context']) && ! empty($item['context']))
            {
                $temp = $this->constructContext($item['context']);

                $structure[] = array(
                    'items' => $temp,
                    'title' => ucwords( str_replace('_', ' ', $item['context']) )
                );
            }

            /*
                Regular Old Menu Item (ROMI)
             */
            else
            {
                // If we don't have a weight we need to add one for sorting later...
                if ( ! isset($item['weight']))
                {
                    $item['weight'] = $this->default_weight;
                }

                if (isset($item['title']) && ! empty($item['title']))
                {
                    $item['title'] = $this->parseName($item['title']);
                }

                $slug = isset($item['slug']) ? $item['slug'] : url_title($item['title'], '_', true);
                $structure[ $slug ] = $item;

                if ( isset($structure[$slug]['items']) )
                {
                    $structure[$slug]['items'] = $this->constructItems($structure[$slug]['items']);
                }
            }

            $count++;
        }

        return $structure;
    }

    //--------------------------------------------------------------------

    /**
     * Parses an item's route string to expand any special tags to the URI.
     *
     * @param  string $route The route to parse.
     *
     * @return string        The expanded route.
     */
    public function parseRoute($route)
    {
        $route = str_ireplace('{area}', SITE_AREA, $route);

        return $route;
    }

    //--------------------------------------------------------------------

    /**
     * Parse out any lang file references in the name or title.
     *
     * @param  string $str  The string to parse
     *
     * @return string       The parsed string.
     */
    public function parseName($str)
    {
        if (strpos($str, 'lang:') === 0)
        {
            $str = str_replace('lang:', '', $str);

            $str = lang($str);
        }

        return $str;
    }

    //--------------------------------------------------------------------


    /**
     * Builds an array of menu items based on the Contexts that are found
     * within the system and all modules.
     *
     * @param  string $context_name The name of the context to build a menu for.
     *
     * @return array                The constructed menu items array.
     */
    public function constructContext($context_name)
    {
        $structure = array();

        if (empty($this->module_folders))
        {
            return $structure;
        }

        if ( ! count($this->contexts) || ! is_array($this->contexts))
        {
            return $structure;
        }

        // Is this a valid context?
        if ( ! in_array($context_name, $this->contexts) )
        {
            return array();
        }

        // Build out our menu items based on the context
        $modules = $this->validModules($context_name);

        foreach ($modules as $module_name => $module_folder)
        {
            $item = $this->constructContextItem($context_name, $module_name, $module_folder);

            if ( empty($item) || (is_array($item) && ! count($item)) )
            {
                continue;
            }

            $structure[$module_name] = $item;
        }

        return $structure;
    }

    //--------------------------------------------------------------------

    /**
     * Builds the necessary data for a menu item from a module and it's
     * menu config file, if any.
     *
     * @param  string $context       The name of the context to render.
     * @param  string $module        The name of the module (as defined in the filesystem)
     * @param  string $module_folder The path to the module folder.
     *
     * @return array                The built out item.
     */
    public function constructContextItem($context, $module, $module_folder)
    {
        $item = array();

        $menu = $this->buildMenu($context, $module_folder .'/menus/');

        if ( ! empty($menu))
        {
            return $menu;
        }

        // Build some default values.
        $name = str_replace('_', ' ', $module);
        $name = $this->parseName( ucwords($name) );

        $route = "{$context}/{$module}";

        $weight = 50;

        $item['title']  = $name;
        $item['route']  = $this->parseRoute($route);
        $item['weight'] = $weight;

        return $item;
    }

    //--------------------------------------------------------------------

    /**
     * Scans the module_folders for folders with controllers matching the
     * context name passed in.
     *
     * @param  string The name of the context we're working with.
     *
     * @return array  A list of valid modules for this context.
     */
    public function validModules($context)
    {
        $modules = array();
        get_instance()->load->helper('directory');

        // Scan our module folders for directories and see if we have
        // any controllers matching the context name.
        foreach ($this->module_folders as $folder)
        {
            $map = directory_map($folder, 1);

            if (empty($map))
            {
                continue;
            }

            foreach ($map as $module)
            {
                // Must be a folder to contain modules.
                if ( ! is_dir($folder . $module))
                {
                    continue;
                }

                // Must have a controllers folder.
                if ( ! is_dir($folder . $module .'/controllers'))
                {
                    continue;
                }

                // Must have a controller name matching our context
                if ( ! is_file($folder . $module ."/controllers/{$context}.php"))
                {
                    continue;
                }

                $modules[$module] = $folder . $module;
            }
        }

        return $modules;
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // RENDERING METHODS
    //--------------------------------------------------------------------

    /**
     * Converts the menu structure to a rendered html list, complete
     * with our params.
     *
     * @param  array $item      The menu structure
     * @param  bool  $is_outer  If true, let's us know this is the
     *                          very outermost level. Used for wrappers.
     *
     * @return string           The built HTML.
     */
    public function renderItem($item, $is_outer=false)
    {
        $html = '';
        $in_tag = false;

        if ($is_outer)
        {
            $html .=  $this->full_tag_open ."\n";
        }

        if (isset($item['title']) && ! $is_outer )
        {
            $in_tag = true;

            $route = isset($item['route']) ? $item['route'] : '#';

            $attrs = array();

            // Open Tag
            if ( isset($item['items']) )
            {
                $html .= $this->dropdown_tag_open ."\n";
                $attrs['class'] = $this->dropdown_class;
            }
            else
            {
                $html .= $this->tag_open ."\n";
            }

            $html .= anchor($route, $item['title'], $attrs) ."\n";
        }

        // Any child menu items?
        if (isset($item['items']) && is_array($item['items']) && count($item['items']))
        {
            if ( ! $is_outer)
            {
                $html .= "<ul {$this->parent_class}>\n";
            }

            foreach ($item['items'] as $row)
            {
                $html .= $this->renderItem($row);
            }

            if ( ! $is_outer)
            {
                $html .= "</ul>\n";
            }
        }

        // Close tag
        if ($in_tag)
        {
            $html .= $this->tag_close ."\n";
        }

        if ($is_outer)
        {
            $html .= $this->full_tag_close ."\n";
        }

        return $html;
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // ACCESSOR METHODS
    //--------------------------------------------------------------------

    /**
     * Sets the folders that modules can be looked for in. When building
     * menus by contexts, these folders are searched in for possible
     * modules.
     */
    public function setModuleFolders($folders)
    {
        if ( ! is_array($folders)) {
            return;
        }

        $this->module_folders = $folders;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the current list of module folders.
     *
     * @return array
     */
    public function moduleFolders()
    {
        return $this->module_folders;
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------

    /**
     * Reads a menu from the APPPATH/menus folder. Menu files
     * have the same name as the menu name being passed in.
     * They are JSON files.
     *
     * @param  string $menu         The name of the menu to load
     * @param  string $menu_path
     * @return array                The parsed JSON file as an array, or NULL if no menu found.
     */
    protected function readMenu($menu, $menu_path=null)
    {
        $menu_path = empty($menu_path) ? $this->menu_path : $menu_path;
        $menu_path = rtrim($menu_path, '/');
        $menu = str_replace('.json', '', $menu);

        if ( ! is_file($menu_path .'/'. $menu .'.json'))
        {
            return NULL;
        }

        $contents = file_get_contents($menu_path .'/'. $menu .'.json');

        if (empty($contents))
        {
            return NULL;
        }

        return json_decode($contents, true);
    }

    //--------------------------------------------------------------------

}
