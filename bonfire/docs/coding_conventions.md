# Coding Conventions

## File Format
All files should be in the UTF-8 format. Line endings should be set to Unix-style line endings (LF). It is possible to have git auto-correct the line-endings for you. Please see [GitHub’s Help Pages](http://help.github.com/dealing-with-lineendings/) for more information.

## Tabs
Spaces, not tabs, are preferred.

## Code Indentation
Use Allman-style indenting. With the exception of class declarations, braces are always placed on a line by themselves and indented at the same level as the parent that ‘owns’ them.

    class Class_name {

        public function method_name()
        {
            foreach ($array as $item)
            {
                // Do something
            }
        }

        //----------------------------------------------------------------------
    }

## PHP Closing Tag
The PHP closing tag for the file should be _omitted_. This protects files from echoing data before they need to.

## Visibility
All class variables and methods must have visibility declared.

    class Class_name {

        public     $property;
        protected  $other_property;
        private    $ci;

        //--------------------------------------------------------------------

        public function method_name()
        {

        }

        //--------------------------------------------------------------------
    }

## Class and Method Naming
Class names should always have their first letter uppercase.

Use the PHP 5 *__construct()* function for constructors unless absolutely necessary.

Multiple words should be separated with an underscore, and not CamelCased.

All other class methods should be entirely lowercased and named to clearly indicate their function.

## Variable Naming
All variable names should describe what they are without being overly verbose.

They should only contain letters, numbers and underscores (no CamelCase).

Avoid short variable names whenever possible, and opt for a more descriptive name.

## Readable Code
You should strive to make your code as easy to read as possible. This makes it easier for other developers to get familiar with your code.

### Avoid Nesting
Avoid deep nesting of logic checks where possible. This only serves to make the code confusing down the road. Instead, check for an error condition and return, or similar function, as early as possible.

GOOD:

    public function method_name()
    {
        if ( ! isset($var))
        {
            return false;
        }

        if ($other_var !== TRUE)
        {
            return false;
        }

        foreach ($this as $that)
        {
            // Code here...
        }
    }

    //--------------------------------------------------------------------


BAD:

    public function method_name()
    {
        if (isset($var))
        {
            if ($other_var === TRUE)
            {
                foreach ($this as $that)
                {
                    if ($that == $something)
                    {
                        // Code here...
                    }
                }
            }
        }
    }

    //--------------------------------------------------------------------

### Comment as Needed
Comments should be used as frequently as needed, but not overdone. If your variable names and method names are descriptive then your code will be understandable without a lot of comments.

This doesn’t mean don’t comment at all. That’s even worse.

Use comments to

- Describe why you are doing something (not how - that’s what the code is for)
- Separate the logic into self-contained chunks to make finding your place easier.
- As a reminder of what something is, or where it came from (if it is handled in another class).