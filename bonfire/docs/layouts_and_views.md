# Layouts and Views

## Overview: How the Pieces Fit Together

This guide covers how Bonfire handles rendering views within your application. It presents the relationship between standard CodeIgniter views and how they fit into the overall Theme.

In a straight CodeIgniter application you might be used to having a view for your header and a view for your footer. Then, in each view file, you would use `$this->load->view('header')` to keep a consistent layout across your site. This works well, and is one of the fastest options available to you. But it's not the most efficient or flexible from a developer's viewpoint.

Bonfire uses Themes, which you'll be accustomed to using in nearly any CMS out there. Each theme contains one or more layouts (one-column, two-column, blog, etc) as well as any assets (like CSS or Javascript files) that the theme might need. A single command in your controller is all that is needed to make this work.

Views are exactly what you are accustomed to working with, but follow a simple convention that keeps things consistent among all of the developers on your team, and keeps things simple and organized.


<a name="organize"></a>
## Views

Views in Bonfire are the same views that are available within stock CodeIgniter. They are just organized to take advantage of conventions that make keeping which view belongs to which method much simpler and consistent across projects and between co-workers. Since most of the work that you will be doing will be focused within modules, all of the examples will use a `blog` module as an example. However, these same principles apply directly to the `application/views` folder itself.

<a name="render"></a>
### Using `render`

Wherever you would have used CodeIgniter's `$this->load->view()` previously, you should use `Template::render()` instead. It displays a view, wrapped within your themed layout files, checks if it's an AJAX or mobile request, and handles it all without you having to do much. At its simplest, all you have to do to display a fully-themed page from within your controller is:


    class Blog extends Front_Controller
    {
        public function index()
        {
            Template::render();
        }
    }


<a name="render-method"></a>
### Rendering a Method's View

Bonfire's template system favors certain conventions in order to keep your project organized and make your job easier. When you use the `render()` method it looks for a view with the same name as the active method.

All public context views (those front-facing pages) will be found in your module's `/view` folder. All other contexts (content, settings, etc) should have a folder matching the context name, and the view would be inside of that folder.


    /**
     * Public Context for Blog Module
     *
     * View would be found at:
     *  module_name/
     *      views/
     *          index.php
     */
    class Blog extends Front_Controller
    {
        public function index()
        {
            Template::render();
        }
    }


    /**
     * Settings Context
     *
     * View would be found at:
     *  module_name/
     *      views/
     *          settings/
     *              index.php
     */
    class Settings extends Admin_Controller
    {
        public function index()
        {
            Template::render();
        }
    }


<a name="render-view"></a>
### Rendering an Arbitrary View

Sometimes you will need to use a different view than what Bonfire defaults to, such as when you want to share a form between your `create` and `edit` methods. This can be handled by using the `set_view()` method of the Template library. The only parameter is the name of the view file to display. The view name is relative to the module's view folder.


    class Settings extends Admin_Controller
    {
        public function create()
        {
            Template::set_view('settings/post_form');
            Template::render();
        }
    }


<a name="theme-views"></a>
### Rendering Theme Views

Often you will want to display one of the files from your theme within one of your view files. This is most often done within one of your layouts (see below) but can be done from within any view. You would accomplish this by using the `theme_view()` helper function. You don't need to load any helpers to make this happen, it is ready for you when your Template library is loaded.

The first parameter is the name of the view to display. This view is relative to your active theme's folder.


    echo theme_view('recent_posts');


The second parameter is an optional array of data to pass to the view. This functions exactly like the second parameter of CodeIgniter's `$this->load->view()` method. Many times this won't be necessary, as any view data that is already available should be usable by any theme views also.


    echo theme_view('recent_posts', array('posts' => $posts));


By default, this method will check to see if the user is viewing this from a mobile device (like a smartphone or tablet). If they are, then it will first look for a view that is prefixed with `mobile_`.


    /*
     * Would look for a file called: mobile_recent_posts
     * in the active theme folder.
     */
    echo theme_view('recent_posts');


If you want to ignore the mobile version (like when they have said they want to view the full site anyway) you may pass `true` in as the third parameter.


    /*
     * Would look for a file called: recent_posts
     * in the active theme folder, even if they
     * were viewing on a mobile device.
     */
    echo theme_view('recent_posts', null, true);


<a name="parsing"></a>
### Parsing Views

Bonfire assumes that you are using PHP as your template language. However, you can still use CodeIgniter's built-in `parser` library to display your views instead of letting it parse straight PHP. This can be turned on and off with the Template library's `parse_views()` method.

    Template::parse_views(true);


Once this command has been called, all views from this point on would use the parser.

<a name="themes"></a>
## Themes

Bonfire supports a very flexible theme engine that allows for common layouts to wrap around your views, as well as more complex parent/child relationships between themes. Themes can include their own assets (CSS, Javascript and images), as well as multiple layout files. They can also providing layout switching based on the controller that's currently being ran.

<a name="layouts"></a>
### Layouts

A layout is a view file that is contains common content that you want displayed on each page. This is commonly used to create consistent headers and footers across your site.

You can also have layouts for two- and three-column (or more!) formats that are ready to be used within any controller in your site.

<a name="layouts-default"></a>
### Default Layouts

When you display a view using `Template::render()` Bonfire will use a layout called `index.php`. This is the base layout that is used across your site.

    themes/
        my_theme/
            index.php

**AJAX Calls**

When your page is displaying a view in response to an AJAX call Bonfire will ignore the current view that has been set and instead use the `ajax.php` layout. You can use this file to fire any common javascript after an AJAX response.

    themes/
        my_theme/
            ajax.php

<a name="layouts-other"></a>
### Using Arbitrary Layouts

If a page requires a different layout than the default ones, say to display a two-column layout, you can easily choose a different layout to use with the `render()` method.

    class Blog extends Front_Controller
    {
        public function index()
        {
            Template::render('two_column');
        }
    }

You can also set a layout to be used by an entire controller by directly tapping into the Template library's class variables.


    class Blog extends Front_Controller
    {
        public function __construct()
        {
            Template::layout = 'two_column';
        }
    }


This is best used when you want this controller to use a layout that is shared with other controllers.

<a name="controllers"></a>
### Controller-Based Layouts

If you have a controller that needs its own layout all you need to do is to create a new layout in your theme folder with the same name as your controller.


    /**
     * Uses the blog layout file at:
     *  themes/
     *      my_theme/
     *          blog.php
     */
    class Blog extends Front_Controller
    {
        public function index()
        {
            Template::render();
        }
    }

<a name="parent-child"></a>
### Parent and Child Themes

Parent/Child themes make it simple to make small tweaks to a theme without actually modifying any of the original theme's files. Instead, you create a new theme and modify only the files that you need to change. If a file is not found in the active theme it will use the file from the default theme in its place.

Parent/child themes are perfect to use when you have a module that requires its own theme, like a forum, but you want to give the users the ability to choose different themes. To create these new themes you would simply create new theme folders that only changed the layouts or assets that needed to change, while defaulting to the original theme for all other assets or layouts.

There are two methods that you would use to setup a parent/child theme situation.

**set_theme()**

The first parameter should be the name of the active (or primary) theme to use. This is the one that will be looked into first when trying to display any themed files. The second parameter is the name of the default (or parent) theme that is used as a fallback when themed files are not found in the active theme. Unless otherwise specified, all themes will attempt to fall back on a theme named `default`.


    Template::set_theme('my_theme', 'default');


**set_default_theme()**

There may be situations where you need to only change the default theme that is used as a fallback and not change the active theme. You would use the `set_default_theme()` method to handle this. The only parameter is the name of the theme to use as the default.


    Template::set_default_theme('my_other_theme');


<a name="template-content"></a>
### Understanding Template Content

In your theme's layout files, you can specify where the controller's views are set to display by using the `Template::content()` method.


    <?php Template::block('header'); ?>

        <?php echo Template::content(); ?>

    <?php Template::block('footer'); ?>


<a name="blocks"></a>
## Blocks

Blocks allow you to set aside room for content within your layouts. It can be used as a placeholder for content that varies across layouts, like a sidebar. On a two-column layout you can set aside an area for the sidebar content, but then define what content is used there in each controller or method, allowing each page to be customized without needing additional layouts.


    Template::block('block_name', 'default_view');


The first parameter is a name that the block can be referenced by when you set the view to use in your controller. The second optional parameter is the name of a view to use as a default, in case other content has not been set.

If you need to pass a specific set of data to the block's view, you can pass an array of key/value pairs as the third parameter.


    Template::block('block_name', 'default_view', $data);

Sometimes you will want to keep all of the various files that can be used in this block within your theme. In this case, you can pass `true` as the fourth parameter. Instead of looking within your module's view folder, it will look within the theme folder.


    Template::block('block_name', 'default_view', $data, true);


To set a different view to use in that block, you would use the `Template::set_block()` method within your controller. The first parameter is the name of the block to set the content for. The second parameter is the name of the view file to use. By default, this will look within your module's view folder.


    Template::set_block('sidebar', 'blog_sidebar');



<a name="helpers"></a>
## Helper Functions

The Template library contains several other, smaller, functions and methods that change the way you might be used to using standard CodeIgniter features, as well as some to aid with building out your navigation.

<a name="data"></a>
### View Data

Instead of using a $data array to pass content to your view, or using `$this->load->vars()`, you can use `Template::set()` to make the data usable within the view. This should follow the same format that you are used to using, namely being an array of key/value pairs.


    // Instead of...
    $data = array(
        'title' => 'Page title',
    );
    $this->load->view('index', $data);

    // You should use...
    Template::set('title', 'Page title');
    Template::render();


In order to make the transition from a familiar CodeIgniter practice to Bonfire simpler, you can pass an array in as the only parameter.


    $data = array(
        'title' => 'Page title'
    );
    Template::set($data);

Within your views, you can access the variables with a name that matches the key of the array pairs.

<a name="messages"></a>
### Flash Messages

CodeIgniter's Session library provides flash messages that allow you to set short status messages in your views. This is commonly used for small notifications when your object saved successfully. The only limitation this has is that it only is available on a page reload, which can soon become costly due to the sheer number of server hits it creates.

Bonfire provides a variation on this that is available both to the current page as well as the next page load. To use it you would call the `set_message()` method within your controllers. The first parameter is the text to be displayed. The second parameter is the class name that will be given to the output string.


    Template::set_message('Your message displayed just fine.', 'success');


To display the message, use the `message()` method within your view.


    Template::message();

The code is wrapped within HTML that you can define in the `application.php` config file. It defaults to a Bootstrap compatible alert message.


    $config['template.message_template'] =<<<EOD
     <div class="alert alert-block alert-{type} fade in notification">
            <a data-dismiss="alert" class="close" href="#">&times;</a>
            <div>{message}</div>
        </div>
    EOD;


<a name="redirect"></a>
### Redirect

When you are using AJAX a lot in your application, you will find times when CodeIgniter's stock `redirect()` method will not work for you, since it redirects within the AJAX call. What happens when you want to completely break out of the AJAX call and do a complete page refresh? You can use `Template::redirect('some/other/page')` instead. This returns an empty file that simply has a Javascript redirect code inserted.

The only parameter is a URL (either absolute or relative) to redirect the user to.


    Template::redirect('users/profile');


<a name="paths"></a>
### Theme Paths

By default, all of your application's themes are stored within `bonfire/themes`. There may be times, though, when you need to have multiple locations to store your theme files. This might be handy when you ship a forums module and want to provide themes specifically for your forums in their own location.

You can add a new path to look for themes with the `add_theme_path()` method. The only parameter is the path to your theme folder. This must be relative to your application's web root (FCPATH).


    Template::add_theme_path('user_themes');

If you only need that path there temporarily, you can provide a slight performance increase by removing the path when you're done with it.


    Template::remove_theme_path('user_themes');


If you want to permanently add that path to your application, you can set this in the `application.php` config file.


    $config['template.theme_paths'] = array('user_themes', 'bonfire/themes');


<a name="menus"></a>
###  Working With Menus

The template library contains two functions to ease working with navigation items and setting the active link within the menu.

**`check_class()`**

The `check_class()` function checks the passed in url segments against the controller that is running. The first parameter is the name of the class to check against. If you wanted to highlight the Blog link in your main application and your controller was named 'blog' you could use:


    <a href="/blog" <?php echo check_class('blog'); ?>>Blog</a>

    // Outputs:
    <a href="/blog" class="active">Blog</a>


If you already have other classes applied to the link and just want the word `active` output, you can pass `true` as the second parameter.


    <a href="/blog" class="dropdown <?php echo check_class('blog', true); ?>">Blog</a>

    // Outputs:
    <a href="/blog" class="dropdown active">Blog</a>


**`check_method()`**

Check method works the same as `check_class()` but compares it to the active method running within your controller. The parameters are identical.

**`check_segment()`**

Check_segment is used in much the same way as the previous two methods, but is more generic, and, often, more flexible. The first parameter is the URI segment to check. The second parameter is the value to compare the segment to.

	<a href="/blog" <?php echo check_segment(1, 'blog'); ?>>Blog</a>

    // Outputs (assuming current URL is /blog)
    <a href="/blog" class="active">Blog</a>

If you already have other classes applied to the link and just want the word `active` output, you can pass `true` as the third parameter.


    <a href="/blog" class="dropdown <?php echo check_class(1, 'blog', true); ?>">Blog</a>

    // Outputs:
    <a href="/blog" class="dropdown active">Blog</a>