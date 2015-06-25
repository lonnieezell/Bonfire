<?php

/**
 * Route library.
 *
 * Provides enhanced Routing capabilities to CodeIgniter-based applications.
 */
class Route {

    /**
     * Our built routes.
     * @var array
     */
    protected static $routes    = array();

    protected static $prefix    = NULL;

    protected static $named_routes  = array();

    protected static $default_home  = 'home';

    protected static $nested_prefix = '';
    protected static $nested_depth  = 0;

    //--------------------------------------------------------------------

    /**
     * Combines the routes that we've defined with the Route class with the
     * routes passed in. This is intended to be used  after all routes have been
     * defined to merge CI's default $route array with our routes.
     *
     * Example:
     *     $route['default_controller'] = 'home';
     *     Route::resource('posts');
     *     $route = Route::map($route);
     *
     * @param  array  $route The array to merge
     * @return array         The merge route array.
     */
    public static function map($routes=array())
    {
        $controller = isset($routes['default_controller']) ? $routes['default_controller'] : self::$default_home;

        foreach (self::$routes as $from => $to)
        {
            $routes[$from] = str_replace('{default_controller}', $controller, $to);
        }

        return $routes;
    }

    //--------------------------------------------------------------------

    /**
     * A single point to the basic routing. Can be used in place of CI's $route
     * array if desired. Used internally by many of the methods.
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public static function any($from, $to, $options=array(), $nested=false)
    {
        return self::createRoute($from, $to, $options, $nested);
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // HTTP Verb-based routing
    //--------------------------------------------------------------------
    // Verb-based Routing works by only creating routes if the
    // $_SERVER['REQUEST_METHOD'] is the proper type.
    //

    public static function get($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET')
        {
            self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    public static function post($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            return self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    public static function put($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT')
        {
            return self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    public static function delete($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE')
        {
            return self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    public static function head($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'HEAD')
        {
            return self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    public static function patch($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PATCH')
        {
            return self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    public static function options($from, $to, $options=array(), $nested=false)
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            return self::createRoute($from, $to, $options, $nested);
        }
    }

    //--------------------------------------------------------------------

    /**
     * Creates HTTP-verb based routing for a controller.
     *
     * Generates the following routes, assuming a controller named 'photos':
     *
     *      Route::resources('photos');
     *
     *      Verb    Path            Action      used for
     *      ------------------------------------------------------------------
     *      GET     /photos         index       displaying a list of photos
     *      GET     /photos/new     create_new  return an HTML form for creating a photo
     *      POST    /photos         create      create a new photo
     *      GET     /photos/{id}    show        display a specific photo
     *      GET     /photos/{id}/edit   edit    return the HTML form for editing a single photo
     *      PUT     /photos/{id}    update      update a specific photo
     *      DELETE  /photos/{id}    delete      delete a specific photo
     *
     * @param  string $name The name of the controller to route to.
     * @param  array $options An list of possible ways to customize the routing.
     */
    public static function resources($name, $options=array(), $nested=false)
    {
        if (empty($name))
        {
            return;
        }

        $nest_offset = '';

        // In order to allow customization of the route the
        // resources are sent to, we need to have a new name
        // to store the values in.
        $new_name = $name;

        // If a new controller is specified, then we replace the
        // $name value with the name of the new controller.
        if (isset($options['controller']))
        {
            $new_name = $options['controller'];
        }

        // If a new module was specified, simply put that path
        // in front of the controller.
        if (isset($options['module']))
        {
            $new_name = $options['module'] .'/'. $new_name;
        }

        // In order to allow customization of allowed id values
        // we need someplace to store them.
        $id = '([a-zA-Z0-9\-_]+)';

        if (isset($options['constraint']))
        {
            $id = $options['constraint'];
        }

        // If the 'offset' option is passed in, it means that all of our
        // parameter placeholders in the $to ($1, $2, etc), need to be
        // offset by that amount. This is useful when we're using an API
        // with versioning in the URL.
        $offset = isset($options['offset']) ? (int)$options['offset'] : 0;

        if (self::$nested_depth)
        {
            $nest_offset = '/$1';
            $offset++;
        }

        self::get($name,                    $new_name .'/index'. $nest_offset,                        null,   $nested);
        self::get($name .'/new',            $new_name .'/create_new'. $nest_offset,                   null,   $nested);
        self::get($name .'/'. $id .'/edit', $new_name .'/edit'. $nest_offset .'/$'. (1 + $offset),    null,   $nested);
        self::get($name .'/'. $id,          $new_name .'/show'. $nest_offset .'/$'. (1 + $offset),    null,   $nested);
        self::post($name,                   $new_name .'/create'. $nest_offset,                       null,   $nested);
        self::put($name .'/'. $id,          $new_name .'/update'. $nest_offset .'/$'. (1 + $offset),  null,   $nested);
        self::delete($name .'/'. $id,       $new_name .'/delete'. $nest_offset .'/$'. (1 + $offset),  null,   $nested);
    }

    //--------------------------------------------------------------------

    /**
     * Add a prefix to the $from portion of the route. This is handy for
     * grouping items under a similar URL, like:
     *
     *      Route::prefix('admin', function()
     *      {
     *          Route::resources('users');
     *      });
     *
     * @param  string  $name      The prefix to add to the routes.
     * @param  Closure $callback
     */
    public static function prefix($name, Closure $callback)
    {
        self::$prefix = $name;

        call_user_func($callback);

        self::$prefix = null;
    }

    //--------------------------------------------------------------------

    /**
     * Returns the $from portion of the route if it has been saved with a name
     * previously.
     *
     * Example:
     *
     *      Route::get('posts', 'posts/show', array('as' => 'posts'));
     *      redirect( Route::named('posts') );
     *
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public static function named($name)
    {
        if (isset(self::$named_routes[$name]))
        {
            return self::$named_routes[$name];
        }

        return NULL;
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Contexts
    //--------------------------------------------------------------------

    /**
     * Contexts provide a way for modules to assign controllers to an area of the
     * site based on the name of the controller. This can be used for making a
     * '/developer' area of the site that all modules can create functionality into.
     *
     * @param  string $name       The name of the URL segment
     * @param  string $controller The name of the controller
     * @param  array  $options
     *
     * @return void
     */
    public static function context($name, $controller=null, $options=array())
    {
        // If $controller is an array, then it's actually the options array,
        // so we'll reorganize parameters.
        if (is_array($controller))
        {
            $options        = $controller;
            $controller     = null;
        }

        // If $controller is empty, then we need to rename it to match
        // the $name value.
        if (empty($controller))
        {
            $controller = $name;
        }

        $offset = isset($options['offset']) ? (int)$options['offset'] : 0;

        // Some helping hands
        $first      = 1 + $offset;
        $second     = 2 + $offset;
        $third      = 3 + $offset;
        $fourth     = 4 + $offset;
        $fifth      = 5 + $offset;
        $sixth      = 6 + $offset;

        self::any($name .'/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)',  "\${$first}/{$controller}/\${$second}/\${$third}/\${$fourth}/\${$fifth}/\${$sixth}");
        self::any($name .'/(:any)/(:any)/(:any)/(:any)/(:any)',         "\${$first}/{$controller}/\${$second}/\${$third}/\${$fourth}/\${$fifth}");
        self::any($name .'/(:any)/(:any)/(:any)/(:any)',                "\${$first}/{$controller}/\${$second}/\${$third}/\${$fourth}");
        self::any($name .'/(:any)/(:any)/(:any)',                       "\${$first}/{$controller}/\${$second}/\${$third}");
        self::any($name .'/(:any)/(:any)',                              "\${$first}/{$controller}/\${$second}");
        self::any($name .'/(:any)',                                     "\${$first}/{$controller}");

        unset($first, $second, $third, $fourth, $fifth, $sixth);

        // Are we creating a home controller?
        if (isset($options['home']) && ! empty($options['home']))
        {
            self::any($name, "{$options['home']}");
        }
    }

    //--------------------------------------------------------------------

    /**
     * Allows you to easily block access to any number of routes by setting
     * that route to an empty path ('').
     *
     * Example:
     *     Route::block('posts', 'photos/(:num)');
     *
     *     // Same as...
     *     $route['posts']          = '';
     *     $route['photos/(:num)']  = '';
     */
    public static function block()
    {
        $paths = func_get_args();

        if ( ! is_array($paths))
        {
            return;
        }

        foreach ($paths as $path)
        {
            self::createRoute($path, '');
        }
    }

    //--------------------------------------------------------------------


    //--------------------------------------------------------------------
    // Utility Methods
    //--------------------------------------------------------------------

    /**
     * Resets the class to a first-load state. Mainly useful during testing.
     *
     * @return void
     */
    public static function reset()
    {
        self::$routes = array();
        self::$named_routes     = array();
        self::$nested_depth     = 0;
    }

    //--------------------------------------------------------------------

    //--------------------------------------------------------------------
    // Private Methods
    //--------------------------------------------------------------------

    /**
     * Does the heavy lifting of creating an actual route. You must specify
     * the request method(s) that this route will work for. They can be separated
     * by a pipe character "|" if there is more than one.
     *
     * @param  string $from
     * @param  array $to
     *
     * @return array          The built route.
     */
    private static function createRoute($from, $to, $options=array(), $nested=false)
    {
        $prefix = is_null(self::$prefix) ? '' : self::$prefix .'/';

        $from = self::$nested_prefix . $prefix . $from;

        // Are we saving the name for this one?
        if (isset($options['as']) && ! empty($options['as']))
        {
            self::$named_routes[ $options['as'] ] = $from;
        }

        self::$routes[$from] = $to;

        // Do we have a nested function?
        if ($nested && is_callable($nested) && self::$nested_depth === 0)
        {
            self::$nested_prefix    .= rtrim($from, '/') .'/';
            self::$nested_depth     += 1;
            call_user_func($nested);

            self::$nested_prefix = '';
        }

        self::$nested_depth = self::$nested_depth === 0 ? self::$nested_depth : self::$nested_depth -1;
    }

    //--------------------------------------------------------------------

}