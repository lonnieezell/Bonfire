# Upgrading to CodeIgniter 3.x

Bonfire is currently in the process of upgrading to support CodeIgniter 3.x (CI3).
This document is intended to help you upgrade your own application to support CI3 and use it within Bonfire.

## Installing CI3

While CI3 has not currently been fully tested within Bonfire, all of the obvious functionality is in place.
If you run into any problems while testing CI3 with Bonfire, please feel free to [open an issue](https://github.com/ci-bonfire/Bonfire/issues).

If you would like to test it out for yourself, you will need to do the following:
- Setup (or update) a Bonfire installation with the latest code from the [develop branch on GitHub](https://github.com/ci-bonfire/Bonfire). Make sure the site is configured and working under CodeIgniter 2.x.
- Download [the latest CI3 release](http://www.codeigniter.com/download) ([CI3.0.1](https://github.com/bcit-ci/CodeIgniter/archive/3.0.1.zip) is the latest release at the time of this writing). The more adventurous can download the latest code from [their development branch on GitHub](https://github.com/bcit-ci/CodeIgniter).
- Making sure to *not* overwrite the existing files in the /bonfire/ci3/ directory of your working test site, copy the files from the CI3 system directory into the /bonfire/ci3/ directory. (If you do happen to overwrite the files, you can pull them down from the Bonfire GitHub repository.)

## Updating your application

If you're updating an existing site, you will probably need to update some of your application files (in addition to the Bonfire files) with the latest versions from the Bonfire repository.
As usual when updating application files, you will probably want to review the changes and merge any modifications you have made to these files.
If you don't already use one for this purpose, it is recommended that you look into a good diff tool for your development platform (e.g. Meld for Linux or WinMerge for Windows; Meld is also available from MacPorts or Fink for OS X).

In some cases, the changes below simply merge changes from CI3 which could also be applied to CI2.
In other cases, the changes include conditional checks to determine the version of CodeIgniter currently in use, so the site can be used with either version of CodeIgniter with these changes in place.
Either way, all of the files below will work in both versions of CodeIgniter.

The following application files have been modified to support CI3:
- /application/config/ (including versions of these files you may have in environment-specific directories, as needed)
    - application.php
    - config.php
    - constants.php
    - database.php
    - doctypes.php
    - foreign_chars.php
    - hooks.php
    - memcached.php
    - migration.php
    - mimes.php
    - profiler.php
    - routes.php
    - smileys.php
    - user_agents.php
- /application/controllers/
    - All files in this location must be renamed to make the first letter of the filename uppercase (while the loader currently supports either ucfirst or lowercase names, eventually support for lowercase names will be dropped).
- /application/core/
    - Base_Controller.php
    - Admin_Controller.php
- /application/hooks/App_hooks.php
- /application/language/
    - application_lang.php (this file must be updated in each language which will be available on your site)
- /application/libraries/
    - All files in this location must be renamed to make the first letter of the filename uppercase (while the loader currently supports either ucfirst or lowercase names, eventually support for lowercase names will be dropped).
    - Profiler.php
    - you may also wish to add the CommonMark/drivers directory and included drivers, as well as the Parsedown library (if you wish to use it, with or without the associated CommonMark/driver).
- /application/models/
    - All files in this location must be renamed to make the first letter of the filename uppercase (while the loader currently supports either ucfirst or lowercase names, eventually support for lowercase names will be dropped).
- /application/third_party/MX/
    - All files in this location must be updated to support CI3.
- /application/views/
    - errors/ - this is the new location for error pages in CI3. Default error pages from CI3 are included in the Bonfire repository.
    - profiler_template.php

## Switching CodeIgniter versions

Additionally, the /public/index.php file must be updated.
In this file, when the `$system_path` variable is first defined, you can easily switch between CI2 and CI3 by commenting out the current `$system_path` definition and uncommenting the definition for the version of CI you wish to use.
In other words:
- to use CI2, the `$system_path` should point to `"{$path}/bonfire/codeigniter"`
- to use CI3, the `$system_path` should point to `"{$path}/bonfire/ci3"`

## Upgrading from CI2 to CI3

Finally, check the [CI3 upgrade guide](http://www.codeigniter.com/userguide3/installation/upgrade_300.html) for changes you will need to make to your application's modules.

If you have issues related to the changes in CI3's `directory_map()` function, Bonfire has added a `bcDirectoryMap()` function to provide the results of `directory_map()` in the format returned by CI2's version of the function.
The function is in `/bonfire/helpers/BF_directory_helper.php`, so it should be available whenever `directory_map()` is available.
This is intended as a temporary compatibility function while transitioning an application from CI2 to CI3.
