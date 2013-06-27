## Site Structure

Bonfire's site is structured with three goals in mind:

- Keep as much of Bonfire out of the application folder.
- Keep as much of your code (and ours) outside of the web root as possible for added security.
- Help you keep your project organized amid the pressures of everyday development.

Hopefully, this page will give you an idea of the reasoning and purpose behind the structure.

## Folder Structure


    application/
        archives/
        cache/
        config/
        controllers/
        core/
        db/
            backups/
            migrations/
        errors/
        helpers/
        hooks/
        language/
        libraries/
        logs/
        models/
        modules/
        third_party/
        views/
    bonfire/
        codeigniter/
        helpers/
        libraries/
        migrations/
        modules/
    install/
    public/
        assets/
            cache/
            css/
            images/
            js/
        index.php
        install/
            index.php
        themes/
            admin/
            default/
    tests/
        bugs/
        controllers/
        helpers/
        libraries/
        models/
        simpletest/unit_test.php
        views/


### Application

The __application__ folder is where you will do most of your work. This contains all of your controllers, libraries, helpers, models, and any modules you might use.

As much as possible, Bonfire stays away from this folder. However, there are a few files that we cannot move out of the application folder due to restrictions in either CodeIgniter or the HMVC solution that we use. In most cases, those files are just fine for you to edit, anyway. These are files like *MY_Controller*, etc.

Most of the folders with the application folder are the standard CodeIgniter folders you would expect. There are a few new ones that Bonfire adds.


#### DB folder

The db folder stores two things. The first are any backups that you create using the built-in Database Backup funtionality. The second folder, *migrations*, holds the migration files that are specific to your application as a whole.

#### Modules folder

This folder is where you should create all of your own modules. It is also where Bonfire creates module-related files for you. Each module should have a folder of it's own unique name here.




### Bonfire

The bonfire folder holds Bonfire, itself, as well as the codeigniter system files.

- helpers - has additional helper files that Bonfire provides for extra functionality.
- libraries - all of Bonfire's libraries, like the Assets and Template libraries.
- migrations - the migration files that are specific to the core of Bonfire only.
- modules - all of our core modules are stored here, to keep them separate from your modules and make upgrading a bit simpler.



### Install

This folder simply holds the files necessary to run the swanky installer that gets Bonfire setup for you. Once installed, feel free to delete this folder.

### Public

This folder is where you should point your domain name to. It holds the files and folders that should be accessible from the web.

- assets - a place to put all of your scripts, images, and styles that can be used site-wide.
- install - a simple folder that holds the index file that kicks off the installer. Once installed, a new file called *installed.txt* will be written here that will keep the installer off of your back and let you run the site without deleting files. Very handy during development when you know that you'll need the installer later on.
- themes - holds the admin and default front-end themes for now. Also where you should put your own themes.

## Reverting To Traditional Structure

In some situations, you may prefer a more traditional folder structure that has all of the files and folders located in the web root. This might be due to restrictions on shared hosting, or becuase you prefer easier installation for your customers. This can be easily done by moving the files and folders from the public folder into the same folder as the others. This would leave a folder structure like

    application/
    assets/
    bonfire/
    index.php
    install/
    tests/
    themes/

After you've moved the files, you need to edit the main <tt>index.php</tt> file to let CodeIgniter know where to find the application and system folders.

    $path = '.'

Changing this one variable will reset all of the other paths.
