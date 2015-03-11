# CommonMark

Bonfire includes a basic library for parsing CommonMark (and/or MarkDown) documents.

## Using the Library

Using the library is fairly simple. Load the library like any other CodeIgniter library:

    $this->load->library('CommonMark');

The library is then accessed like any other CodeIgniter library:

    $content = $this->commonmark->parse($content);

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

### parse($text)

Parse the text (usually convert it from CommonMark format to HTML) and return the parsed text.

## Configuration

This library is extendable in a manner similar to CodeIgniter's driver system.

In order to use a driver other than the default, you must configure the library to load the driver.
In `/application/config/application.php`, set the `commonmark.driver` to the desired driver.
Make sure the driver is also listed in the `commonmark.valid_drivers` array (the default driver, `MarkdownExtended`, will always be included in the list of valid drivers by the library itself, so it does not need to be included in the configuration file).

The filenames of each driver are prefixed with `CommonMark_`, but this prefix should not be included in the name used in either `commonmark.driver` or `commonmark.valid_drivers`.

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
The class must include a public function named `parse()` which accepts a string as input and returns a string as output:

    <?php

    class CommonMark_Example
    {
        public function parse($text)
        {
            // Convert the CommonMark text to HTML
            // ...

            return $text;
        }
    }

The code above would be placed into `/application/libraries/CommonMark/drivers/CommonMark_Example.php` and loaded as the `Example` driver.

In most cases, you will have to instantiate a parser and pass the argument to the parser, then return the parsed text.
For a better example of handling that, see any of the drivers mentioned above.
