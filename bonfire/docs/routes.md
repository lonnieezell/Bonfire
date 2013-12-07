# Improved Routes

The addition of Packages in CodeIgniter 2.x branch was a welcome improvement, but did not go far enough. It only allowed for shared code, not Routing support. True support of modules is necessary to help developers with code sharing and to help build up the community once again. In addition, CodeIgniter's Router is showing it's age against the powerful solutions found in other PHP frameworks like Laravel. Bonfire's Routing is meant to help bring CodeIgniter into the modern age, or at least help it take a step in that direction.

## CodeIgniter Mods

This is one area where we have decided that modifying the core of CodeIgniter is allowed. We've completely overridden the core Loader and Router classes with our own strange combination of [WireDesignz' HMVC code](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc) and [jenssegers' HMVC code](https://github.com/jenssegers/CodeIgniter-HMVC-Modules) and tweaked it to allow loading core files from the bonfire folder and more.

## Route Library

The Route library is the core of the new flexibility. It is inspired by Jamie Rumbelow's excellent [Pigeon](https://github.com/jamierumbelow/pigeon) class, as well as [Laravel's routing system](http://laravel.com/docs/routing).

### HTTP Verb Routing

To make building REST-based routing simpler and more consistent, you can use the

    Route::resources('controller_name');

This function will automatically create RESTful resources for the common HTTP verbs. In this example, `controller_name` is the name of the controller you want to map the resources to. If you controller is named `photos`, you would call it like:

    Route::resources('photos');

If the `photos` controller is part of the `Gallery` module, then you would route it like:

    Route::resources('gallery/photos');

This would map the resources to the `Photos` controller, like:

HTTP Verb   |  Path             |  action   |  used_for
------------|-------------------|-----------|----------------
GET         | /photos           | index     | display a list of photos
GET         | /photos/new       | create_new| return an HTML form for creating a new photo
POST        | /photos           | create    | create a new photo
GET         | /photos/{id}      | show      | display a specific photo
GET         | /photos/{id}/edit | edit      | return the HTML for editing a single photo
PUT         | /photos/{id}      | update    | update a specific photo
DELETE      | /photos/{id}      | destroy   | delete a specific photo


You can also set a single verb-based routes with any of the route methods:

    Route::get('from', 'to');
    Route::post('from', 'to');
    Route::put('from', 'to');
    Route::delete('from', 'to');
    Route::head('from', 'to');
    Route::patch('from', 'to');
    Route::options('from', 'to');

These routes will then only be available when the corresponding HTTP verb is used to initiate the call.


### Customizing Resourceful Routes

While the standard naming convention provided by the `resources` Route method will often serve you well, you may find that you need to customize the route to easily control where your URL's route to.

#### Specifying a controller to use

You can pass an array of options into the `resources` method as the second parameter. By specifying a `controller` key, you will tell the router to replace all instances of the original route with the defined controller, like:

    Route::resources('photos', array('controller' => 'images'));

Will recognize incoming paths beginning with `/photos` but will route to the `images` controller:

#### Specifying the module to use

You can also specify a module to use in the options array by passing a `module` key. This is helpful when the module and controller share different names.

    Route::resources('photos', array('module' => 'gallery', 'controller' => 'images'));

Will recognize incoming paths beginning with `/photos` but will route to the `gallery/images` module and controller.

#### Constraining the {id} format

By default, the {id} used in the routing allows any letter, lower- or upper-case, any digit (0-9), a dash (-) and an underscore(_). If you need to restrict the {id} to another format, you may use the `constraint` option to pass a new, valid, format string:

    Route::resources('photos', array('constraint' => '(:num)'));

 Would restrict the {id} to be only numerals, while:

    Route:resources('photos', array('constraint' => '([A-Z][A-Z][0-9]+)'));

would restrict the {id} to be something like RR27.

#### Offsetting Parameters

By default, the resulting parameters in the $to portion of the route will start at $1. In most cases this is what you want. However, there may be times where you need to change that and offset the value in some form or another. This is most often see when dealing with an API version number in the URL that you're fixing manually later in the routes config file, or when taking out a language string from the URL. In these cases, you would be removing $1 from the routes and would need the parameters to start at $2 instead. You can do this by passing 'offset' in the options array with a value matching the number you need to offset.

    Route::resources('photos', array('offset' => 1));


## Blocking Routes

You might find times where you need to block access to one or more routes. For example, you might have relocated the default user login page so that script-kiddies couldn't find your page by assuming it's a Bonfire site and would be at a normal location. In this case, you would want to block any access to /users/login, which would normally work just fine. In this case you can use the `block()` method to block as many routes as you'd like.

    Route::block('users/login', 'photos/(:num)');

    // The same as:
    $route['users/login']    = '';
    $route['photos/(:num)']  = '';



## Route Prefixing

There are times when you'll want to group a disparate set of routes under a single section. You can use route prefixing for this.

    Route::prefix('api', function() {
        Route::all('users', 'users/index');
        Route::get('photos', 'photos/show');
    });

Would be equivalent to the following routes:

    $route['api/users'] = 'users/index';
    $route['api/photos'] = 'photos/show';




## Named Routes

You can save routes with a name associated with them that makes it much easier and safer to call routes within your application. The provides a single name that you can always count on being the same that maps to the $from portion of the route. If you need to restructure your site, simply change the routing while keeping the name the same. Any place in your application that called that route will still work. This works with prefixing and all of the other flexbile routing the class provides.

    Route::prefix('area', function(){
        Route::any('posts', 'posts/index', array('as' => 'blog'));
    });

    redirect( Route::named('blog') );



## Routing Contexts

Contexts provide a way for modules to assign controllers to an area of the site based on the name of the controller. This can be used for making a /developer area of the site that all modules can create functionality into.

This can be better explained with an example. We want to provide a collection of tools available under the /developer URL of our site. We have a number of modules, like a database manager, a code builder, etc, that all need to have easy access to that area. Instead of creating routes for each module, we'll just create a general set of routes that will take any controller named 'developer.php' in any of our modules, and route it to `developer/{module_name}/{method}`.

    Route::context('developer');

If, we change our mind down the road and want to rename all of the URL's to /tools instead of /developer, we can do that by passing in two parameters instead. The first is the name of route (tools in this case), and the second is the controller to map to.

    Route::context('tools', 'developer');

This creates a series of routes that map the parameters into the module. It's a little hacky but works well for up to 5 parameters. If you need more than that, you might examine your application to see if you could use the routes differently or restructure your application. The equivalent CI routes would be:

    $route['tools/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)']   = '$1/developer/$2/$3/$4/$5/$6';
    $route['tools/(:any)/(:any)/(:any)/(:any)/(:any)']          = '$1/developer/$2/$3/$4/$5';
    $route['tools/(:any)/(:any)/(:any)/(:any)']                 = '$1/developer/$2/$3/$4';
    $route['tools/(:any)/(:any)/(:any)']                        = '$1/developer/$2/$3';
    $route['tools/(:any)/(:any)']                               = '$1/developer/$2';
    $route['tools/(:any)']                                      = '$1/developer';

If you need to offset your parameter numbers for the above routes, you can pass on 'offset' key/value in your options array as the last parameter.

### Context Homes

You can also have it create a 'home' controller that would handle the calls to '/developer' all by itself. This will map to a controller outside of any modules, but in your application/controllers folder, under a new folder named after the context. The controller can be any name you wish, but can be made to match the name of the default_controller by using a {default_controller} tag.

    Route::context('developer', array('home' => 'some_controller'));
    // Creates...
    $route['developer'] = 'developer/some_controller';
    // Maps to...
    application/controllers/developer/some_controller.php

    Route::context('developer', array('home' => '{default_controller}'));
    // Creates
    $route['developer'] = 'developer/welcome';
    // Maps to
    application/controllers/developer/welcome.php