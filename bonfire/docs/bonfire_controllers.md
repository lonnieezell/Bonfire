# Bonfire Controllers

CodeIgniter provides a `CI_Controller` that is meant to be used as the basis for all of your own controllers. It handles the behind-the-scenes work of assigning class vars and the Loader so that you can access them. Bonfire extends this concept and provides 4 additional Controllers that can be used as base classes throughout your project. This helps you to keep from repeating code any more than necessary by providing a central place for any site-wide code to sit. For example, it makes a user object available that can be accessed from any controller, library, or view to know details about the current user. You can use it to set a custom theme for all of your public pages. And much more.

Each controller is stored in its own file in the `application/libraries` folder and the file is named the same as the class name. This allows the provided autoloader to easily find your base classes.

The `MY_Controller` file is currently not used by Bonfire and is left alone so that you can use it for your own needs.

<a name="controllers"></a>
## The Controllers

Each controller type is meant to serve a specific purpose, but they are all easily adaptable to fit your needs. This files are meant to be customized for your application! Don't be afraid to edit them. That said, however, please be sure to back the files up during any upgrades of Bonfire.

<a name="base"></a>
### Base_Controller

All of the custom controllers extend from the `Base_Controller`.  This class extends the MX_Controller which gives you all of the power of WireDesign’s [HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home) available to all of your classes.  That allows for a different way of working, but also a very powerful one, and one that is not necessary to use.

This controller is the place that you want to setup anything that should happen for every page of your site, like:

* Setup environment-specific settings, like turning the profiler on for development and off for production and testing.
* Get the cache setup correctly.  This is currently setup to only use a file-based cache, but you can easily tell it to use APC if available, and fallback to the file system if not.
* This controller also sets up [System Events](system_events.html) that will get executed just before and just after the Base_Controller’s constructor runs.

Some of the things that would normally be auto-loaded are handled here so that any AJAX controllers you may write don't need to process any of these other settings.

By default, Bonfire's Base_Controller provides the following features for any of your classes that extend it:

* `$previous_page` and `$requested_page` class vars that help you know where you came from. These are auto-populated for you.
* `$current_user` class var that, if logged in, will contain all of the information from the users table, as well as a link to the user's avatar. This same information is automatically made available to the view files that are rendered with the [Template](layouts_and_views) class.
* Loads the cache drivers. For development environments, we simply harness the 'dummy' driver which always returns FALSE. Production and test environments default to APC caching with a file-based backup, if that's not available.
* Gets the `activity model` loaded and ready.
* Loads the `application language` file.
* If the child class sets `$requires_authentication` to TRUE, will ensure that the user is logged in, set the `$current_user` variable, and ensure that the user's chosen language is set.

<a name="front"></a>
### Front_Controller

The `Front_Controller` is intended to be used as the base for any public-facing controllers.  As such, anything that needs to be done for the front-end can be done here.

Currently, it simply ensures that the Assets and Template libraries are available.  You could also set the active and default themes here, if you create a parent theme ‘framework’ to use with all of your sites that you extend with child themes.


<a name="auth"></a>
### Authenticated_Controller

This controller forms the base for the Admin Controller.  It was broken into two parts in case you needed to create a front-end area that was only accessible to your users, but that was not part of the Admin area and didn’t share the same themes, etc.  All changes you make here will affect your Admin Controller’s, though, so use with care.  If you need to, reset the values in the Admin Controller.

This controller currently...

* Loads in all of the authentication library
* Restricts access to only logged in users
* Gets form_validation setup and working correctly with HMVC.


<a name="admin"></a>
### Admin_Controller

The final controller sets things up even more for use within the Admin area of your site.  That is, the area that Bonfire has setup for you as a base of operations.  It currently...

* Sets the pagination settings for a consistent user experience.
* Gets the admin theme loaded and makes sure that some consistent CSS files are loaded so we don’t have to worry about it later.


<a name="create"></a>
## Creating Controllers

Creating controllers in Bonfire is nearly identical to creating controllers in straight CodeIgniter. The only difference is the naming of some of the classes when you're dealing with the Administration side of Bonfire and [Contexts](contexts). It does provide some basic tools for you to use, though. 

### Class Variables
The following class variables are available to you from any controller that extends from `Base_Controller` or one of it's child classes.

#### $current_user
 If a user is logged in, there basic information will be stored in the `$current_user` class variable. This is also made available within views as `$current_user`. If the user is not logged in, the value will be `NULL`.
 
#### $previous_page
Will store the uri of the previous page, if that page was a page within your application. This is set during the `post_controller` hook and will filter out any pages in the `$ignore_pages` class variable of the `App_hooks` file.  

Hooks must be enabled for this variable to be populated.

#### $requested_page
The page that the web server thinks you were requesting. Set during the `pre_controller` hook, it will attempt to determine the page that the user was requesting, while filtering out the `$ignore_pages` uris so that we don't get things like images, etc. 

This value is also saved to the $_SESSION if CodeIgniter's Session class has not been loaded.

Hooks must be enabled for this variable to be populated.

#### $require_authentication
If set to TRUE, the Base_Controller will load up the authentication class, ensure that the user is logged in via `$this->auth->restrict()` and handle setting up the current user for access in the controller. 

If you are extending from Authenticated_Controller of Admin_Controller, this will have already been called.

#### $autoload
This array provides a simple means of loading various libraries, helpers and models automatically within your application. 

	protected $autoload = array(
        'libraries' => array('settings/settings_lib'),
        'helpers'   => array(),
        'models'    => array()
    );

You will not, however, want to extend this directly since you will override parent class autoload needs. Instead, set the value in your class' constructor prior to calling the parent's constructor.

	public function __construct()
	{
		$this->autoload['helpers'][] = 'my_new_helper';
		
		parent::__construct();
	}
 

