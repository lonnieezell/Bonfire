## Site Area

The <tt>SITE_AREA</tt> is the location of your admin interface within your website. By default, all of Bonfire’s Contexts are available by visiting <tt>http://example.com/admin/</tt>.  In this case the Site Area is admin.  It is the URL that all of your contexts are made available under.

Since the original intent of Bonfire was to create an admin area for your web applications, the admin site area made perfect sense.  As other developers started using Bonfire, though, new ways of structuring apps became apparent.  Site Areas were implemented to make these new types of app organization make more sense.

<a name="changing"></a>
### Changing the Site Area

Changing the site area for you app is as easy as changing a single constant.

Open the <tt>bonfire/application/config/constants.php</tt> file.  2. Edit the SITE_AREA constant to match your needs.


    /*
       The 'App Area' allows you to specify the base folder used for all of
       the contexts in the app. By default, this is set to '/admin', but this
       does not make sense for all applications.
    */
    define('SITE_AREA', 'admin');



<a name="linking"></a>
### Linking to Admin Pages

When you need to create a link within your modules to an admin page you should use the SITE_AREA constant in your link.


    <a href="<?php echo site_url(SITE_AREA .'/my_link') ?>">My Link</a>
