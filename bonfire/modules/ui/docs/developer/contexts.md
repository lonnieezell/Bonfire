# Contexts

Bonfire includes the `Contexts` library in the `ui` module for generating the contexts menu in the admin interface and creating new contexts.

## Configuration

The `Contexts` library can be configured by setting the `$config['contexts']` array in `/application/config/application.php`.
When adding new contexts, you will also have to edit `/application/config/routes.php` if you wish to add special routing for the new context(s).
More information on routing contexts is available in the section on [Improved Routes](routes).

### contexts

This setting allows configuration of the contexts which will be available in your application.
Only two contexts are required (`'settings'` and `'developer'`), but four are provided by default.


    $config['contexts'] = array('content','reports','settings','developer');

The name of the context displayed in the menu is determined by language strings defined in `/application/language/{current_language}/application_lang.php`.
The language strings follow the format `bf_context_{context_name}`.
For example:

    $lang['bf_context_content']   = 'Content';
    $lang['bf_context_reports']   = 'Reports';
    $lang['bf_context_settings']  = 'Settings';
    $lang['bf_context_developer'] = 'Developer';

If icons are enabled in the menu, the library will attempt to use the file `/public/themes/{current_theme}/images/context_{context_name}.png`.

## Methods

### setContexts(array $contexts[, $siteArea])

Sets the internal contexts array used by the library to the desired `$contexts` array, ensuring the required contexts are included (they will be added if they are not included).
Optionally, the value used by the library to represent the `SITE_AREA` constant can be overridden by passing the desired value in the second parameter, `$siteArea`.
Any subsequent method calls will use the values last passed to this method.
The library's constructor passes the configured `'contexts'` value from `/application/config/application.php` and `SITE_AREA` to this method to initialize the library.

If `$contexts` is empty or not an array, this method will call `die(lang('bf_no_contexts'))`.

### getContexts([$landingPageFilter])

Return the context array.

`$landingPageFilter`: if `true`, only contexts which have a landing page available in `VIEWPATH/{site_area}/{context}/index.php` will be returned, but the internal contexts array is not modified. Defaults to `false`.

### errors([$open[, $close]])

Returns a string containing any errors which have occurred so far.
Each error will be preceeded by the string supplied in `$open` and followed by the value supplied in `$close`, followed by a newline (`\n`) character.
By default, `$open` is set to `'<li>'` and `$close` is set to `'</li>'`.

### render_menu([$mode[, $order_by[, $top_level_only[, $benchmark]]]])

Returns a string containing the HTML for a list-based menu with optional sub-menus for each context.

* `$mode`: determines the content of the top-level menu (one entry per context). The valid values are `'text'`, `'icon'`, or `'both'`. Defaults to `'text'`, but an invalid value will result in it being set to `'icon'`.
* `$order_by`: determines the sort order of the elements. Valid values are `'normal'`, `'reverse'`, `'asc'`, or `'desc'`. Defaults to `'normal'`.
* `$top_level_only`: if `true`, only the top-level links will be output. Defaults to `false`.
* `$benchmark`: If `true`, start/end marks will be added via the `benchmark` class to profile the rendering of the menu. Defaults to `false`.

### render_mobile_navs()

Returns a string containing the HTML for a mobile-optimized menu.

### context_nav($context[, $class[, $ignore_ul]])

Returns a string containing the menu for the desired context.

* `$context`: the name of the context to build.
* `$class`: the string applied to the `'{class}'` entry in the context's template. Defaults to `'dropdown-menu'`.
* `$ignore_ul`: prevents output of surrounding `ul` elements in the output when `true`. Defaults to `false`.

### create_context($name[, array $roles[, $migrate]])

The `create_context()` method is used by the Builder module to add a new context to your application.
Creates a context and the associated permissions, then assigns those permissions to the desired roles.

* `$name` is the name of the context to be created.
* `$roles` is an array of roles (names or IDs) which should have permission to view this context.
* `$migrate` is a currently-unused parameter intended to control whether a migration file is created (this method does not create migration files at this time).

If an error occurs, `create_context()` will return `false`, otherwise it will return `true`.

### set_attrs(array $attrs)

Takes an array of key/value pairs (`$attrs`) and sets the properties (named for the keys) to the values.

### build_sub_menu($context[, $ignore_ul]) *Deprecated*

Returns the HTML for a sub-menu.

* `$context`: the name of the context to which this sub-menu is attached.
* `$ignore_ul`: bypass placing the sub-menu into the template if `true`. Defaults to `false`.
