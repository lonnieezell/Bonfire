# Changing Constants for Better Security

## Site Area

The `SITE_AREA` is the location of your admin interface within your website. By default, all of Bonfire’s Contexts are available by visiting `http://example.com/admin/`.  In this case the Site Area is admin.  It is the URL that all of your contexts are made available under.

Since the original intent of Bonfire was to create an admin area for your web applications, the admin site area made perfect sense.  As other developers started using Bonfire, though, new ways of structuring apps became apparent.  Site Areas were implemented to make these new types of app organization make more sense.

<a name="changing"></a>
### Changing the Site Area

Changing the site area for you app is as easy as changing a single constant.

Open the `bonfire/application/config/constants.php` file.  2. Edit the SITE_AREA constant to match your needs.


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


## User Login &amp; Registration

To help protect your site from script-related brute-force attacks you can globally change the `login` and `register` URLs to something unique on your site. This primarily keeps spammers from doing a simple Google and located a Bonfire-specific string on your website, collecting the URL, and spamming user registrations or logins onto your site or attempting brute-force logins. It is not a foolproof method of security, by any means, but can keep your site free from the script-kiddies and link spammers.

The `config/constants.php` file defines a constant that is used throughout the system's core modules and themes. Changing the destination of these defines will also change the URL used throughout the system.

    define('LOGIN_URL', 'login');
    define('REGISTER_URL', 'register');

### When No Public Login is Needed

If your site does not require public login, only admin login, you can take the following steps to tighten up security a little bit more.

1. Change LOGIN_URL to equal SITE_AREA .'/login' to bring the login page into the admin area.
2. Change SITE_AREA to a different value (not the default /admin)
3. Make sure that user registration is disabled. Uncheck 'Allow User Registrations' within Settings. Edit routes.php to a) remove /register and b) block users/register.
4. Check your site theme and remove any login links.

