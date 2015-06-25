# Template

Bonfire includes a Template library which makes it easier to build pages for a website based on layouts and partial views.
Additional information about using the Template library may be found in [Layouts and Views](layouts_and_views).

## Configuration

The Template library can be configured by setting several values in `/application/config/application.php`.

### template.site_path

The path to the root folder that holds the application.
This does not have to be the site root folder, or even the folder defined in FCPATH.

    $config['template.site_path'] = FCPATH;

### template.theme_paths

An array of folders to look in for themes.
There must be at least one folder path at all times, to serve as the fall-back for when a theme isn't found.
Paths are relative to the `template.site_path`.

    $config['template.theme_paths'] = array('themes');

### template.default_layout

This is the name of the default layout used if no others are specified.
NOTE: do not include an ending ".php" extension.

    $config['template.default_layout'] = "index";

### template.ajax_layout

This is the name of the default layout used when the page is displayed via an AJAX call.
NOTE: do not include an ending ".php" extension.

    $config['template.ajax_layout'] = 'ajax';

### template.use_mobile_themes

When set to true, the Template library will check the user agent during the rendering process against the `template.themes`, allowing you to create mobile versions of your site, and versions targetted specifically at a single type of phone (ie, Blackberry or iPhone).
NOTE: when rendering, if the file doesn't exist in the targetted theme, the Template library then checks the default site for the same file.

    $config['template.use_mobile_themes'] = false;

### template.default_theme

This is the folder name that contains the default theme to use when searching for a view in your site's themes.

    $config['template.default_theme'] = 'default/';

### template.admin_theme

This is the folder name that contains the default theme to use for the site's admin area (`SITE_AREA`).

    $config['template.admin_theme'] = 'admin';

### template.message_template

This is the template that the Template library will use when displaying messages through the message() function.
To set the class for the type of message (error, success, etc), the `{type}` placeholder will be replaced.
The message will replace the `{message}` placeholder.

    $config['template.message_template'] =<<<EOD
    <div class="alert alert-block alert-{type} fade in notification">
        <a data-dismiss="alert" class="close" href="#">&times;</a>
        <div>{message}</div>
    </div>
    EOD;

### template.breadcrumb_symbol

Breadcrumb separator, the symbol displayed between the breadcrumb elements.

    $config['template.breadcrumb_symbol'] = ' : ';

### template.parse_views

If set to true, views will be parsed via CodeIgniter's parser.
If false, views will be considered PHP views only.

    $config['template.parse_views'] = false;

## Methods

### add_theme_path($path)

Add a theme path (`$path`) to the list of paths to be used when searching for themed views.

### block($block_name[, $default_view[, $data[, $themed]]])

Places a named block in the layout/view which acts as an insertion point for a view.
This is ideal for setting locations for recurring elements within a site's layout, such as sidebars, headers, footers, etc.

- `$block_name` is the name used to reference this block, especially when calling `set_block()` to change/set the view.
- If `$default_view` is set to the path/name of an existing view, the view will be used if no other view is set (by calling `set_block()`).
- `$data` may be set to pass an array of data to the view rendered in the block.
- `$themed`:
    - If `true`, the view will be loaded from the current theme, if possible.
    - If `false` (default), the view will be loaded from the standard view locations.

### content()

Specifies the area in the layout into which the current view will be rendered.

Returns a string containing the output of the render process for the view.

### get($var_name)

Returns the value of a variable which has been previously set, or false if the variable does not exist.

### getLayout()

Returns the layout into which views will be rendered.

### init()

Used to initialize the Template library after it is loaded by CodeIgniter.
Not intended for external use, despite being a public method.

### load_view($view, $data = null, $override = '', $is_themed = true, &$output)

Load a view from the current theme.
- `$view` the name of the view to load.
- `$data` An optional array of data elements to be made available to the views. Array keys will be the variable names used to access the values in the view.
- `$override` An optional string containing the name of a view to override `$view`, if available.
- `$is_themed` An optional boolean value (defaults to `true`) to determine whether themes are checked when attempting to locate a view.
- `&$output` A reference to a variable to store the output of the loaded view.

### message([$message[, $type]])

Displays a status message.
- If `$message` is not included, displays a message set previously via either `set_message()` or `session->flashdata('message')`. Otherwise, displays `$message`.
- `$type` is the type of message (defaults to `'information'`), usually added as a value of the class attribute on the message's container. This value is only used if not already set on the message to be displayed.

### parse_views([$parse])

Sets the `$parse_views` property, which controls whether views will be parsed by CI's Parser.
- If `$parse` is `true`, the views will be parsed.
- If `false` (default), they will not be parsed by CI's Parser.

### redirect([$url])

Performs a redirect, similar to CodeIgniter's `redirect()`, but, if it detects that this is in response to an AJAX request, it will inject a JavaScript redirect into the response.
- `$url` is an optional URL to which the user will be redirected. If omitted, it will be set to the site's base URL.

### render([$layout])

Starts the process of rendering the page content and determines the correct view to use based on the current controller/method.

Optionally, `$layout` may be set to the path/name of a layout to use instead of the current layout.

This method is usually called in the last line of most controller actions/methods (except when those methods return data for use by another controller or in response to an AJAX call).

### set($var_name[, $value])

Set a value or an array of values to be provided to the view(s) in the content area.

- `$var_name` can be an array of key/value pairs (with the key being the variable name to be used in the view), in which case `$value` should be omitted.
- If `$var_name` is a string, it will be interpreted as the name of a variable to be set to `$value` in the view.

### setLayout($layout)

Specify the layout into which the views will be rendered.

Allows overriding the default layout.
This is especially useful to set a default layout for a controller which overrides the default layout of the application.

### set_block($block_name[, $view_name])

This method is used to override the default view (or set a view if no default was provided) in a named block.
- `$block_name` must match the value passed in the first parameter of the `block()` method. The name of the block.
- `$view_name` is the path/name of the view to render in the block.

### set_default_theme($theme)

Set the default theme (`$theme`) to use in case a view is not found in the active theme.
This theme should be relative to one of the current theme paths.

### set_message($message[, $type])

Sets a status message (primarily intended for displaying small success/error messages).
This function is used in place of `session->flashdata` to allow the message to show up without requiring a page refresh.

- `$message` is the text of the message.
- `$type` is the type of message (defaults to `'info'`), usually added as a value of the class attribute on the message's container.

### set_theme($theme[, $default_theme])

Set the name of the active theme (`$theme`).
This theme should be relative to one of the current theme paths.

The optional `$default_theme` allows you to also set a default theme to use in case a view is not found in the active theme.

### set_view($view)

Sets the view to be rendered in the content block.

### setSessionUse($useSession = true)

Enable/disable the library's use of sessions.
This is primarily used by the installer (when sessions are not likely to be available), but is also useful for testing.

If `$useSession` is `true`, the library will use sesssions; if `false`, the library will not use sessions.

### theme()

Returns the name of the active theme.

### theme_url([$resource])

Returns the full URL to the currently active theme.
If `$resource` is supplied, returns the full URL to that resource within the currently active theme (does not validate the existence of the resource within the theme).

### themeView($view[, $data = null[, $ignore_mobile = false]])

Set an insertion point for a view (`$view`) within a view.
- `$view`: The name of the view to be rendered.
- `$data`: An array of data to be passed to the view.
- `$ignore_mobile`:
    - If `true`, the library will not attempt to find a version of this view named for mobile devices (prefixed with `mobile_`).
    - If `false` (default), the library will attempt to find a version of this view named for mobile devices when it detects that the page is being accessed from a mobile device.

### remove_theme_path($path)

Remove a theme path (`$path`) from the list of paths to be used when searching for themed views.

## Helper Functions

Helper functions are not methods of the Template library (so they are called without using the `Template::` prefix).
They are included automatically when loading the Template library.

### breadcrumb([$my_segments[, $wrap = false[, $echo = true]]])

Creates a breadcrumb from either `uri->segments` or a key/value paired array passed via `$my_segments`.
- If `$wrap` is `true`, the breadcrumbs will be wrapped in an un-ordered list (default is `false`).
- If `$echo` is `true` (default), the output is sent to `echo`, if `false`, the output will be returned.

### check_class($item[, $class_only = false])

If the current class/controller name matches `$item` (in a case-insensitive comparison), this function returns `'class="active"'` if `$class_only` is `false` (default) or `'active'` if `$class_only` is `true`.

If `$item` does not match, an empty string is returned.

This (and the other `check_*` helper functions in the Template library) is intended primarily for use in menus and other areas of the page to help indicate the active page or active site area, especially when using blocks to display a single view for a portion of the layout displayed on multiple pages.

### check_method($item[, $class_only = false])

If the current method (controller action) matches `$item`, this function  returns `'class="active"'` if `$class_only` is `false` (default) or `'active'` if `$class_only` is `true`.

If `$item` does not match, an empty string is returned.

### check_segment($segment_num, $item[, $class_only = false])

Checks the value of `$item` against the value of the specified URI segment (`$segment_num`).
If they match, the function returns `'class="active"'` if `$class_only` is `false` (default) or `'active'` if `$class_only` is `true`.

If `$item` does not match, an empty string is returned.

### theme_view($view[, $data = null[, $ignore_mobile = false]])

Set an insertion point for a view (`$view`) within a view.
A helper function for `Template::themeView()`.

## Properties

### $blocks

An array of named blocks and the path/filename of the view for each block.

### $debug

A boolean value which controls the library's output of debug messages.

### $ignore_session *Deprecated*

A boolean value which disables the library's use of sessions, primarily for unit testing.

Use `setSessionUse()` instead. Note that `setSessionUse()` expects the opposite value of `$ignore_session`, so `setSessionUse(false)` is equivalent to setting `$ignore_session = true`.

### $layout *Deprecated*

The layout into which views will be rendered.

Use `getLayout()` and `setLayout()` instead.

### $parse_views

A boolean value which determines whether CI's Parser will be used to parse the views.

### $site_path

The full server path to the site root.

## Events

### after_layout_render

    Events::trigger('after_layout_render', $output);

Triggered near the end of the `render()` method, before calling `output->set_output($output)`.

### after_page_render

    Events::trigger('after_page_render', $output);

Triggered near the end of the `content()` method, before returning `$output`.
