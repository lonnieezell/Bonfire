# CodeIgniter 2.x Support in Bonfire

This document is intended to help you configure your Bonfire application to use CodeIgniter 2.x (CI2).
Since CI2 is scheduled to reach end of life in October, 2015, this functionality is intended as a vehicle for upgrading an existing application to support CodeIgniter 3.x (CI3).
While there is every reason to believe that CI2 will work fine with Bonfire 0.8.x, this is not intended for long-term production use.

## Installing CI2

While CI2 is not included within Bonfire v0.8+, it can be installed and used in v0.8.x.
If you run into any problems while using CI2 with Bonfire, please feel free to [open an issue](https://github.com/ci-bonfire/Bonfire/issues).

To install CI2, you will need to do the following:
- Setup (or update) a Bonfire installation with the [latest code from GitHub](https://github.com/ci-bonfire/Bonfire).
- Download [the latest CI2 release](http://www.codeigniter.com/download) ([CI 2.2.4](https://github.com/bcit-ci/CodeIgniter/archive/2.2.4.zip) is the latest 2.x release at the time of this writing).
- Making sure to *not* overwrite the existing files in the `/bonfire/ci2/` directory of your working test site, copy the files from the CI2 system directory into the `/bonfire/ci2/` directory. (If you do happen to overwrite the files, you can pull them down from the Bonfire GitHub repository.)

## Switching CodeIgniter versions

Additionally, the /public/index.php file must be updated.
In this file, when the `$system_path` variable is first defined, you can easily switch between CI2 and CI3 by commenting out the current `$system_path` definition and uncommenting the definition for the version of CI you wish to use.
In other words:
- to use CI2, the `$system_path` should point to `"{$path}/bonfire/ci2"`
- to use CI3, the `$system_path` should point to `"{$path}/bonfire/ci3"`

## Upgrading from CI2 to CI3

Finally, check the [CI3 upgrade guide](http://www.codeigniter.com/userguide3/installation/upgrade_300.html) for changes you will need to make to your application's modules.

If you have issues related to the changes in CI3's `directory_map()` function, Bonfire has added a `bcDirectoryMap()` function to provide the results of `directory_map()` in the format returned by CI2's version of the function.
The function is in `/bonfire/helpers/BF_directory_helper.php`, so it should be available whenever `directory_map()` is available.
This is intended as a temporary compatibility function while transitioning an application from CI2 to CI3.
