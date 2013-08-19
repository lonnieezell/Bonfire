## Removing index.php From the URL

To maintain compatibility with more servers, Bonfire ships without any form of pretty URLs, and has URLs with the index.php confusing the path.

    http://mybonfire.dev/index.php/home

While CodeIgniter provides a pretty good writeup for [removing index.php](http://ellislab.com/codeigniter/user-guide/general/urls.html), here are the full docs needed to end up with a clean URL for your project. 

### URL Rewriting

If you're using the **Apache*** web server, we provide an <tt>.htaccess</tt> file that is ready for you to use that also contains many performance enhancements as recommended by the [HTML5Boilerplate Project](http://html5boilerplate.com/). Simply rename the <tt>1.htaccess</tt> to <tt>.htaccess</tt>.

At the moment, we don't provide starter files for other servers like nginx, but are looking into it. If you use other servers, we would love for your input or file contributions.

### Sub-Folders

If your project is installed in a subfolder of your webroot, or your development environment requries /public to be in the URL, you can modify your .htaccess file to respect the subfolder. 

Edit the .htaccess file and to add a RewriteBase option pointing to the folder that you're using around line 151.

    <IfModule mod_rewrite.c>
      Options +FollowSymlinks
      RewriteEngine On
      RewriteBase /public

### Configuration

To finish the step, you need to edit the <tt>application/config/config.php</tt> and remove the index_file setting on line 35. 

    $config['index_page'] = "";