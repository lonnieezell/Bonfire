# Coding Conventions

To the extent possible, code should follow the [PHP-FIG PSR-2 Coding Style Guide](http://www.php-fig.org/psr/psr-2/). Documentation of files, classes, methods/functions, and properties/variables should follow the [proposed PSR-5 PHPDoc standards](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md).

Due to the use of CodeIgniter 2.x (and for backwards compatibility), strict adherence to PSR-2 is not always possible. For example, most classes are not namespaced, most class names are not in StudlyCaps format, and many method names are not in camelCaps format. Fully namespaced, StudlyCaps classes may not be loadable by the CodeIgniter loader, so how a class is to be used must be taken into consideration when following the coding style guide.

The conventions below are intended to help clarify the PSR-2 standards as related to this project, not act as a replacement or formal set of guidelines. Should is used throughout simply to address the current state of the code, not to imply that the guidelines are any more or less optional than presented in PSR-2.

## File Format
All files should be in the UTF-8 format. Line endings should be set to Unix-style line endings (LF). It is possible to have git auto-correct the line-endings for you. Please see [GitHub’s Help Pages](http://help.github.com/dealing-with-lineendings/) for more information.

## Tabs
Spaces, not tabs, are preferred.

## Code Indentation
Indentation should be 4 spaces. Braces are placed on a line by themselves and indented at the same level as the parent that ‘owns’ them, except when following control structures or multi-line argument lists. For control structures and multi-line argument lists, the opening brace should be on the same line, with one space between the closing parenthesis and the opening brace.

    class ClassName
    {
        public function methodName()
        {
            foreach ($array as $item) {
                if ($something) {
                    // Do something
                } elseif ($nothing) {
                    // Do nothing
                }
            }
        }
    }

## PHP Closing Tag
The PHP closing tag for the file should be _omitted_. This protects files from echoing data before they need to.

## Visibility
All class variables and methods must have visibility declared.

    class ClassName
    {
        public $property;
        protected $other_property;
        private $ci;

        public function methodName()
        {

        }
    }

## Class and Method Naming
Class names should always have their first letter uppercase.

Use the PHP 5 *__construct()* function for constructors unless absolutely necessary.

Multiple words should be camelCased (or StudlyCased), unless underscore separation is necessary for CI loading (usually only needed for class names).

All other class methods should be camelCased and named to clearly indicate their function.

## Variable Naming
All variable names should describe what they are without being overly verbose.

They should only contain letters, numbers and underscores.

Avoid short variable names whenever possible, and opt for a more descriptive name.

## Readable Code
You should strive to make your code as easy to read as possible. This makes it easier for other developers to get familiar with your code.

### Avoid Nesting
Avoid deep nesting of logic checks where possible. This only serves to make the code confusing down the road. Instead, check for an error condition and return, or similar function, as early as possible.

GOOD:

    public function methodName()
    {
        if (! isset($var)) {
            return false;
        }

        if ($other_var !== true) {
            return false;
        }

        foreach ($this as $that) {
            // Code here...
        }
    }

BAD:

    public function methodName()
    {
        if (isset($var)) {
            if ($other_var === true) {
                foreach ($this as $that) {
                    if ($that == $something) {
                        // Code here...
                    }
                }
            }
        }
    }

### Comment as Needed
Documentation of files, classes, methods/functions, and properties/variables should follow the [proposed PSR-5 PHPDoc standards](https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md).

Additional comments should be used as frequently as needed, but not overdone. If your variable names and method names are descriptive then your code will be understandable without a lot of comments.

This doesn’t mean don’t comment at all. That’s even worse.

Use comments to:

- Describe why you are doing something (not how - that’s what the code is for)
- Separate the logic into self-contained chunks to make finding your place easier.
- As a reminder of what something is, or where it came from (if it is handled in another class).

Remember: sometimes the person that is going to need help figuring out what you intended when you wrote that code will be you.
