# CommonMark

Bonfire includes a basic library for converting [CommonMark] (http://spec.commonmark.org/) (and/or [MarkDown] (http://daringfireball.net/projects/markdown/syntax)) documents to HTML.

This library uses a configurable driver to perform the conversion, so the underlying conversion library can be easily updated/replaced as needed.

## Using the Library

Using the library is fairly simple. Load the library like any other CodeIgniter library:

    $this->load->library('CommonMark');

The library is then accessed like any other CodeIgniter library:

    $content = $this->commonmark->convert($content);

You may also pass a configuration array to the library:

    $this->load->library('CommonMark', $config);

The driver currently accepts `'driver'` and `'defaultDriver'` as keys in the configuration array.
Any value passed in this manner must be a string containing the name of a valid driver.

## Library Methods

The following public methods are available when using the CommonMark library.

### loadDriver($driver)

Load the driver named `CommonMark_{$driver}` from the `/libraries/CommonMark/drivers/` directory (in either the application or bonfire directory) and set the library's current driver to the newly-loaded driver.
This method is primarily used to over-ride the application's configured driver when using the library.

The value of $driver still must be in the library's configured list of valid drivers.

### convert($text)

Convert the text (usually from CommonMark format to HTML) and return the converted text.

## Configuration

This library is extendable in a manner similar to CodeIgniter's driver system.

In order to use a driver other than the default, you must configure the library to load the driver.
In `/application/config/application.php`, set the `commonmark.driver` to the desired driver.
Make sure the driver is also listed in the `commonmark.valid_drivers` array (the default driver, `MarkdownExtended`, will always be included in the list of valid drivers by the library itself, so it does not need to be included in the configuration file).

The filename of each driver is prefixed with `CommonMark_`, but this prefix should not be included in the name used in either `commonmark.driver` or `commonmark.valid_drivers`.

## Creating a Driver

CommonMark drivers may be included with Bonfire or may be application-specific, so the library will load drivers located in either of the following locations:
- `/application/libraries/CommonMark/drivers/`
- `/bonfire/libraries/CommonMark/drivers/`

The default configuration uses the MarkdownExtended driver, which is `/bonfire/libraries/CommonMark/drivers/CommonMark_MarkdownExtended.php`.

An example implementation is included for Parsedown in `/application/libraries/CommonMark/drivers/CommonMark_Parsedown.php` (the Parsedown library is included in `/application/libraries/Parsedown.php`).

The following additional application drivers are included: Markdown, MarkdownExtra, and LeagueCommonMark.
In order to use these drivers, the driver must be added to the valid_drivers array in the application configuration and the associated libraries must be installed:
- Markdown: [michelf/php-markdown](https://github.com/michelf/php-markdown) v1.5.0
- MarkdownExtra: [michelf/php-markdown](https://github.com/michelf/php-markdown) v1.5.0
- LeagueCommonMark: [league/commonmark](https://github.com/thephpleague/commonmark) v0.7.2

It is recommended that you use an autoloader (like Composer) when using these libraries (especially league/commonmark).
It should also be noted that league/commonmark currently requires a higher version of PHP than Bonfire itself.

If you choose not to use an autoloader, the drivers will attempt to locate the associated library in the following locations:
- Markdown/MarkdownExtra:
    - `APPPATH . 'vendor/michelf/php-markdown/Michelf'`
    - `APPPATH . '../vendor/michelf/php-markdown/Michelf'`
    - `APPPATH . 'third_party/michelf/php-markdown/Michelf'`
    - `APPPATH . 'third_party/Michelf'`
- LeagueCommonMark:
    - `APPPATH . 'vendor/league/commonmark/src'`
    - `APPPATH . '../vendor/league/commonmark/src'`
    - `APPPATH . 'third_party/league/commonmark/src'`
    - `APPPATH . 'third_party/league/commonmark'`


A driver is simply a class with the `CommonMark_` prefix located in one of the driver directories listed above.
The driver must extend CommonMarkDriver (which can be found in bonfire/libraries/CommonMark/CommonMarkDriver.php).
The class must include a protected function named `toHtml()` which accepts a string as input and returns a string as output:

    <?php

    class CommonMark_Example extends CommonMarkDriver
    {
        protected function toHtml($text)
        {
            // Convert the CommonMark text to HTML
            // ...

            return $text;
        }
    }

The code above would be placed into `/application/libraries/CommonMark/drivers/CommonMark_Example.php` and loaded as the `Example` driver.

In most cases, you will have to instantiate a library to perform the conversion and pass the argument to the library, then return the converted text.
For a better example of handling that, see any of the drivers mentioned above.

### CommonMarkDriver Properties

The CommonMarkDriver is an abstract class, which means it can only be used as a base class and can't be instantiated directly.
While it may be possible to create an extending driver/adapter which does not set at least one of the properties supplied by the CommonMarkDriver, it is usually necessary to set at least the `$converterLib` property.

All of the properties are internal to the driver/adapter (protected) and should not be made public.

#### $converter

The `$converter` property is the instance of the underlying library used by the driver to perform the conversion.
In most cases, this property is set by the CommonMarkDriver's base methods and is only used to access the conversion method in `toHtml()`.
The extending driver/adapter may instantiate the library and set `$this->converter` in the `init()` method, but this is uncommon.

#### $converterLib

This is a string identifying the class to instantiate and load into `$this->converter`.
In most cases, this would be the name of the class which is normally called with the `new` keyword.
This can include a namespace.
For example, the `CommonMark_Markdown` driver defines this property as follows:

    protected $converterLib = '\Michelf\Markdown';

When the `CommonMarkDriver` instantiates the library, it calls `new $this->converterLib()`, which, in this case, translates to `new \Michelf\Markdown()`.
The result is then assigned to `$this->converter` for later use in the `toHtml()` method.

#### $files

This is an array of file names which must be loaded if the underlying library is to be loaded manually (by calling `require_once()` for each of the files).

Each file name may include folder names which must be relative to the search path (one of the paths defined in `$this->paths`), but should not begin with a slash/directory separator.
When attempting to load a library manually, the driver will search through the defined paths for the first file in this array.
If it can't find the first file, it will not attempt to locate other files which may be in the list.

#### $paths

This is an array of search paths which will be used to locate the underlying library if it is to be loaded manually.

Because these should be absolute paths, it is usually necessary to define this variable in the driver/adapter's `__construct()` method.
Each path should not end with a trailing slash/directory separator.

### CommonMarkDriver Methods

The CommonMarkDriver contains one abstract method, `toHtml()`, which must be implemented by the driver/adapter which extends CommonMarkDriver.

#### toHtml($text)

This method *must* be implemented by the driver/adapter.
It should be declared as a protected method (it may be public, but that is not recommended).

`toHtml()` is the method which will be called to perform the actual conversion from CommonMark to HTML.
When an application calls `$this->commonmark->convert($text)`, this is the method which eventually receives the text, converts it, and returns it to the application.

In most cases, this method will only contain one line, returning the result of passing `$text` to the underlying library's conversion method.
For example, CommonMark_LeagueCommonMark uses the following:

    protected function toHtml($text)
    {
        return $this->converter->convertToHtml($text);
    }

#### init()

This method *may* be implemented by the driver/adapter.
It should be declared as a protected method (it may be public, but that is not recommended).

`init()` is a hook point to allow a driver to load the underlying library if it has unusual requirements.
Most drivers should not need to define this method, but if they do, the method should return (boolean) `true` when the library is loaded successfully or `false` when it is not.

In most cases, it should be possible to load the library by supplying the class name in the `$this->converterLib` property and utilizing either Composer to autoload the library or the `$this->files` and `$this->paths` properties to define the files needed to load the library and the paths in which the library may be located.
`init()` is primarily used when `$this->load->library()` or `$this->load->helper()` should be used.
For example, CommonMark_MarkdownExtended uses the following:

    protected function init()
    {
        get_instance()->load->helper('markdown_extended');
        return true;
    }

If the library can't be instantiated by calling `new $this->converterLib();`, it should be instantiated and assigned to `$this->converter` before returning `true`.

If `init()` returns `false`, the CommonMarkDriver will attempt to locate and instantiate the library as usual.

#### convert($text)

This method *should not* be implemented by the driver/adapter.
This is the public method used by the CommonMark library to interface with the CommonMarkDriver.
It checks whether the underlying library has been loaded (attempting to load it if not), then passes `$text` to the driver/adapter's `toHtml()` method.
