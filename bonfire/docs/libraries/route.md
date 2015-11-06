# Route

Bonfire includes a Route library to supplement the functionality provided by CodeIgniter's (and the Wiredesignz HMVC) router(s).
Additional information may be available in the section on [Improved Routes](routes).

## Methods

### map([$routes])

Combines the routes which have been defined in the Route class with the passed `$routes`.
This is intended to be used after all routes have been defined to merge CI's default $route array with routes defined with this Route library.

Note that in CI3 the `'translate_uri_dashes'` setting should be set after calling this method.

Example:

    $route['default_controller'] = 'home';
    Route::resource('posts');
    $route = Route::map($route);

`$routes` is an array of routes to merge with the routes defined by the Route library.
Usually, the variable passed into this method will be the CI `$route` array.

If a 'default_controller' route is not set in the passed `$routes`, it will be set to `'home'` (or whatever value is defined in the Route library's protected `$default_home` property).

Returns the merged array, which should be assigned to the CI `$route` array.

### any($from, $to[, $options[, $nested = false]])

Create a basic route.
In its most basic form, this method will create a route similar to defining a CI route in the form `$route[$from] = $to;`.

- If the `$options` array contains a value in the `'as'` key, the value will be used to configure a named route, allowing you to easily map names to pre-existing routes.
- `$nested` may be set to a callable function to define your own handler for this route.

### HTTP Verb-based routing

#### get($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is a `GET` request.
The arguments match those in the `any()` method.

#### post($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is a `POST` request.
The arguments match those in the `any()` method.

#### put($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is a `PUT` request.
The arguments match those in the `any()` method.

#### delete($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is a `DELETE` request.
The arguments match those in the `any()` method.

#### head($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is a `HEAD` request.
The arguments match those in the `any()` method.

#### patch($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is a `PATCH` request.
The arguments match those in the `any()` method.

#### options($from, $to[, $options[, $nested = false]])

Creates a route which will only be accessible when the `$_SERVER['REQUEST_METHOD']` indicates that the current request is an `OPTIONS` request.
The arguments match those in the `any()` method.

### block($paths)

Prevents access to an array of routes by setting each of the supplied values in the `$paths` array to an empty path.

If `$paths` is not an array, this method will do nothing.

### context($name[, $controller[, $options]])

Provides a method for assigning controllers in modules to an area of the site based on the name of the controller.

Note that `$options` can be passed as the second argument if the `$controller` argument is not needed.

- `$name` is the name of the URL segment for the context.
- `$controller` is the controller name which will be mapped into this context. If not supplied, `$name` will be used for `$controller`.
- `$options` is an array of options with the following (optional) keys:
    - `'offset'` allows the numeric arguments in the `$to` portion of the route to be increased by the offset value (so, if `'offset'` is `1`, `$1` becomes `$2`, `$2` becomes `$3`, etc.).
    - `'home'` allows the definition of a default route for the context.

### named($name)

Returns the `$from` portion of a route which was previously saved as `$name`.
Returns null if a route was not found with the given `$name`.

### prefix($name, Closure $callback)

Prefix a set of routes (defined in the `$callback` closure) with the `$name` prefix.
All routes defined in the closure will be defined with the `$from` portion of the route prefixed with `$name` (e.g. `'users'` becomes `'api/users'` if `$name` is `'api'`).

### reset()

Resets the internal state of the Route library, eliminating any routes which have not already been output.

### resources($name[, $options[, $nested = false]])

Creates a pre-defined set of HTTP-verb based routes for the `$name` controller.

- `$name` specifies the name used in the `$from` portion of the routes.
- `$options` may include the following keys:
    - `'controller'` specifies a name to be used for the controller in the `$to` portion of the created routes (if not included, `$name` will be used).
    - `'module'` specifies a module name to be included in the `$to` portion of the created routes.
    - `'constraint'` specifies permitted values for ID arguments in get/put/delete routes, defaults to `'([a-zA-Z0-9\-_]+)'`.
    - `'offset'` specifies an offset for numbered parameters in the route. Usually numbered parameters start at `$1`, but supplying an `'offset'` allows starting the numbers at a higher value. For instance, setting `'offset'` to `1` would cause the numbered parameters to start at `$2`.
- `$nested` may be set to a callable function to define your own handler for these routes (note that the same handler will be used for all of the routes generated by this method).

Example:

    Route::resources('photos');

Generates the following routes:

<table>
    <thead>
        <tr>
            <th>Verb</th>
            <th>Path</th>
            <th>Action</th>
            <th>used for</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>GET</td>
            <td>/photos</td>
            <td>index</td>
            <td>displaying a list of photos</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/photos/new</td>
            <td>create_new</td>
            <td>return an HTML form for creating a photo</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/photos/{id}/edit</td>
            <td>edit</td>
            <td>return the HTML form for editing a single photo</td>
        </tr>
        <tr>
            <td>GET</td>
            <td>/photos/{id}</td>
            <td>show</td>
            <td>display a specific photo</td>
        </tr>
        <tr>
            <td>POST</td>
            <td>/photos</td>
            <td>create</td>
            <td>create a new photo</td>
        </tr>
        <tr>
            <td>PUT</td>
            <td>/photos/{id}</td>
            <td>update</td>
            <td>update a specific photo</td>
        </tr>
        <tr>
            <td>DELETE</td>
            <td>/photos/{id}</td>
            <td>delete</td>
            <td>delete a specific photo</td>
        </tr>
    </tbody>
</table>
