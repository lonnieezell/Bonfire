## Installing Bonfire

Bonfire has a simple installation script that is designed to help you, the developer, get up and running with a minimum of fuss.  It is not designed to be used for an end product that you distribute.  Installation is a simple process mainly composed of uploading your files and running through a quick 2-step install.

### Upload Your Files

Upload all of the files/folders from your package to your web server or development environment.  The web root should point to the main folder that contains:

    /assets
    /bonfire
    /docs
    index.php

### Configuration

If you do not have mod_rewrite installed on your server, change the Index File to include <tt>index.php</tt>.

    $config['index_page'] = 'index.php';


### Write Permissions

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



### The Install Script

To start the installation process, head to <tt>http://yoursite.com/install</tt>.

You should be greeted by a Welcome screen that asks you for **Your Database information**.  Enter the connection details for your database and then click **Test DB**.

If all goes well, it should direct you to Step 2 (the final step), where you just need to enter your:

- Site Title - The name of your site, as it will appear in the browser’s title bar or tab.
- Username   - Choose a username to log into the site with (if the site is setup that way).
- Password   - Type a password and confirm it.
- Your Email - This will be the address that you use to log in with, as well as the address used to send system emails from.  This can be changed later to be able to use separate addresses for login and system emails.

By default, Bonfire is setup to use emails to login with, and not use usernames at all.  This can be easily configured on the main settings screen.

Assuming that everything proceeds without a hitch, you will be redirected to the login screen.  Enter the email and password you just used, and you will be sent to the admin dashboard where you can start building your app.



## Troubleshooting Your Install

While we have tried to make the install process as simple as possible, sometimes things happen that stop you from completing your installation. We, unfortunately, cannot test in every possible configuration of server out there.

Hopefully, these tips will help you debug your broken install and get up and running quickly.


### Only The 'Welcome to Bonfire' Screen Displays

It might be that your server environment does not support the <tt>PATH_INFO</tt> variable needed to serve search-engine friendly pages.  As a first step, open your <tt>bonfire/application/config/config.php</tt> and look for the URI Protocol setting.  By default, this is set to <tt>AUTO</tt> and works in most cases.  Try changing this variable to one each of the other settings, one at a time, and see if one of these works for your environment.



### Page Not Found

The most common cause of this is not having <tt>mod_rewrite</tt> (or equivalent) installed, or you have a missing <tt>.htaccess</tt> file.

If you know your server does not have mod_rewrite installed, then you will need to edit the <tt>bonfire/application/config/config.php</tt> file.  Find the Index File section and add <tt>index.php</tt> to it.


    $config['index_page'] = 'index.php';


If this works, but all of your URL’s now redirect you to the welcome screen, you might need to add a question mark to the end of it.


    $config['index_page'] = 'index.php?';



### WAMP and mod_rewrite

If you're having problems with the correct page appearing after hitting **Test Database** on the first screen, try the following under WAMP:

- Go to WAMP and select <tt>Apache->Apache Modules->Rewrite Module</tt> and enable it.
- Edit your <tt>httpd.conf</tt> file and uncomment the line: LoadModule rewrite_module modules/mod_rewrite.so
- Restart Apache