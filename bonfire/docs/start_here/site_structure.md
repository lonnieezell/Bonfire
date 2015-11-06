# Site Structure

Bonfire's site is structured with three goals in mind:

- keep as much of Bonfire out of the application folder as possible, so you can keep Bonfire up to date without overwriting your application's code
- keep as much of your code (and ours) outside of the web root as possible for added security
- help you keep your project organized amid the pressures of everyday development.

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
        docs/
        errors/
        helpers/
        hooks/
        language/
        libraries/
        logs/
        models/
        modules/
        third_party/
            MX/
            Sparks_Loader.php
        views/
    bonfire/
        codeigniter/
        controllers/
        core/
        docs/
        helpers/
        libraries/
        migrations/
        modules/
    public/
        assets/
            cache/
            css/
            images/
            js/
        index.php
        themes/
            admin/
            default/
            docs/
    tests/
        _support/
        application/
        bonfire/
        simpletest/
    tools/
        lib/
            spark/
                spark_types/
        spark


### Application

The __application__ folder is where you will do most of your work. This contains all of your controllers, libraries, helpers, models, and any modules you might use.

As much as possible, Bonfire stays away from this folder. However, there are a few files that we cannot move out of the application folder due to restrictions in either CodeIgniter or the HMVC solution that we use. Most of these files are located in the `application/third_party` folder or the `application/core` folder.

The `application/third_party` folder contains `Sparks_Loader.php` for sparks support and the bulk of the HMVC solution, located in the `application/third_party/MX` folder.

While it is best to stay away from the `*_Controller.php` files in the `application/core` folder (you can create your own controllers in this folder to extend them), the `MY_Model.php` file is intended to be modified as you see fit.

Most of the folders within the application folder are the standard CodeIgniter folders you would expect. There are a few new ones that Bonfire adds.

#### DB folder

The db folder stores two things:

- *backups* contains any backups created using the built-in Database Backup funtionality
- *migrations* holds the migration files specific to your application

#### Modules folder

This folder is where you should create all of your own modules. It is also where Bonfire creates module-related files for you. Each module should have a folder with its own unique name here.

It is also possible to override portions of Bonfire's core modules by adding files here, following the folder/file names in the Bonfire module you wish to override.


### Bonfire

The bonfire folder holds Bonfire, itself, as well as the codeigniter system files.

- *codeigniter* houses the CodeIgniter system files
- *controllers* contains Bonfire's custom controllers for images and the installer
- *core* holds Bonfire's overrides for HMVC and custom routing functionality
- *docs* you are here...
- *helpers* has additional helper files that Bonfire provides for extra functionality, including extensions of CodeIgniter helpers
- *libraries* Bonfire's libraries, like the Assets and Template libraries, including extensions of CodeIgniter libraries
- *migrations* migration files specific to the core of Bonfire only, these are typically run during installation and when updating the Bonfire core
- *modules* all of Bonfire's core modules are stored here, to keep them separate from your modules (and make upgrading a bit simpler)


### Public

This folder is where you should point your domain name to. It holds the files and folders that should be accessible from the web.

- *assets* a place to put all of your scripts, images, and styles that can be used site-wide.
- *themes* holds the admin, docs, and default (front-end) themes. Also where you should put your own themes.


## Reverting To Traditional Structure

In some situations, you may prefer a more traditional folder structure that has all of the files and folders located in the web root. This might be due to restrictions on shared hosting, or becuase you prefer easier installation for your customers. This can be easily done by moving the files and folders from the public folder into the same folder as the others, overwriting the root `index.php` with the `public/index.php` file. This would leave a folder structure like:

    application/
    assets/
    bonfire/
    index.php
    tests/
    themes/

After you've moved the files, you need to edit the main `index.php` file to let CodeIgniter know where to find the application and system folders.

    $path = '.'

Changing this variable will set all of the other paths.
