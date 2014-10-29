# Installing Bonfire

## Overview
Bonfire has a simple installation script that is designed to help you, the developer, get up and running with a minimum of fuss.  It is not designed to be used for an end product that you distribute.  Installation is a simple process mainly composed of uploading your files and letting Bonfire install your database schema for you.

## Upload Your Files

Upload all of the files/folders from your package to your web server or development environment.  The web root should point to the /public folder:

    /application
    /bonfire
    /public          // Web Root in here...
    /tests

By keeping the majority of your application files outside of your web root, your security is increased because the files and folders are not accessible directly through the browser. While CodeIgniter comes with default htaccess files in many of the sensitive folders, configuration issues can happen that accidentally allow your php files to be served up as text files, allowing potential hackers to gain too much information about your system. Moving the files removes this possibility. It also makes sniffing the folder structure from a browser, a common first step in hacking a site, much more difficult since the files are not there in the first place.

## Configuration

Before accessing your website, you will need to enter the credentials for your database, else a database error will be thrown when you try to access your site. Enter the details for your site in `application/config/database.php`.

If you are using multiple environments (production, testing, and development), you should create a folder matching the environment name inside your config folder. Then copy the existing database.php config file into that folder and setting the details for you environment there. 


## Write Permissions

Verify that the following folders are writeable during the install process:

    /bonfire/application/cache
    /bonfire/application/logs
    /bonfire/application/config
    /bonfire/application/archives
    /bonfire/application/db/backups
    /bonfire/application/db/migrations
    /assets/cache

Also, make sure the following file has write permissions:

    /bonfire/application/config/application.php



## The Install Script

Now head to your site. Since it has not been installed you will see a small greeting screen that checks your PHP version, and the various files and folders to ensure they are writable. If everything looks good here, click the button and your database will be installed for you. 

## Logging In

During the installation process, a default admin user has been created for you. You can log in with the following credentials: 

* email: admin@mybonfire.com
* username: admin
* password: password

The first thing you should do when logging in the first time is to modify your profile and change your email address and password to be something unique. 

By default, Bonfire is setup to use emails to login with, and not use usernames at all.  This can be easily configured on the main settings screen.

## Troubleshooting Your Install

While we have tried to make the install process as simple as possible, sometimes things happen that stop you from completing your installation. We, unfortunately, cannot test in every possible configuration of server out there.

Hopefully, these tips will help you debug your broken install and get up and running quickly.


### Only The 'Welcome to Bonfire' Screen Displays

It might be that your server environment does not support the `PATH_INFO` variable needed to serve search-engine friendly pages.  As a first step, open your `bonfire/application/config/config.php` and look for the URI Protocol setting.  By default, this is set to `AUTO` and works in most cases.  Try changing this variable to one each of the other settings, one at a time, and see if one of these works for your environment.



### Page Not Found

The most common cause of this is not having `mod_rewrite` (or equivalent) installed, or you have a missing `.htaccess` file.

If you know your server does not have mod_rewrite installed, then you will need to edit the `bonfire/application/config/config.php` file.  Find the Index File section and add `index.php` to it.


    $config['index_page'] = 'index.php';


If this works, but all of your URL’s now redirect you to the welcome screen, you might need to add a question mark to the end of it.


    $config['index_page'] = 'index.php?';



### WAMP and mod_rewrite

If you're having problems with the correct page appearing after hitting **Test Database** on the first screen, try the following under WAMP:

- Go to WAMP and select `Apache->Apache Modules->Rewrite Module` and enable it.
- Edit your `httpd.conf` file and uncomment the line: LoadModule rewrite_module modules/mod_rewrite.so
- Restart Apache