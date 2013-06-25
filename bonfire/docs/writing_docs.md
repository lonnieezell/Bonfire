## Writing Your Own Documentation

Bonfire makes including documentation with your application, or even just one of your custom modules, as simple as including some text files.

### Docs Locations

To create application-specific documentation that can easily be versioned and shipped out with your application, simply place [Markdown](http://daringfireball.net/projects/markdown/)-formatted text files in the <tt>application/docs</tt> folder. (Technically, we use [Markdown Extra](http://michelf.ca/projects/php-markdown/extra/) for even more formatting possibilities).

For any modules that you create and want to create documentation for, just place the same Markdown-formatted docs in the module's folder, under a new folder named <tt>docs</tt>.

    my_module/
        css/
        docs/
        . . .

The files must have the file extension of <tt>.md</tt> in order to be recognized by the system. When a help page is displayed, the Table of Contents in the sidebar will automatically find your docs and display them in the order found.

### Custom Table of Contents

If you need more organization to your docs, you can specify a custom TOC to be used. This allows for specifying custom names for the files, as well as splitting doc files into logical groups that you specify, but only one level deep.

To use a custom TOC, create a file called <tt>_toc.ini</tt> within your docs folder. If this file is present, it will be used to display the links, instead of auto-generating the links from the existing files. This file is a standard PHP .ini file. The "options" within the file are the filename and the display name. The filename is listed on the left of the '=' with the text used to display the link listed on the right.

    my_page = My Great New Documentation Package

To group the files and provide a header, you would use the .ini's section syntax.

<pre>
[Section 1]
my_page = My New Documentation Package
</pre>

The filename must include in it's "path" the area the documentation came from, either 'application', 'bonfire', or your module's folder name.

    application/my_page = My New Documentation Package
    my_module/my_page   = My New Module Docs