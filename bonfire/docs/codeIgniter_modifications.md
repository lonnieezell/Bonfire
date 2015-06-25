# Modifications to CodeIgniter

## index.php

A few small changes have been made to the main <tt>index.php</tt> file that ships with CodeIgniter and is used as the base for launching your application.

### <tt>BFPATH</tt>

A new constant, <tt>BFPATH</tt>, is available that points to the root of Bonfire's specific code. By default this is <tt>/bonfire/</tt>.

## CodeIgniter.php

The controller loading section has been modified to check in /bonfire/controllers if a controller is not found in the application/controllers folder. This allows the installer controller (and potentially others) to remain tucked away with the rest of the Bonfire code.

## Common.php

The <tt>load_class()</tt> method has been modified to allow it to search for core files not only in the Codeigniter folder and the application folder, but also in the bonfire folder. To allow graceful overriding in the application, the path is first searched in APPPATH, then BFPATH, then BASEPATH.

We also search in the bonfire/ folder for BF_ files. These files are like MY_Model, etc, but are provided with class names prefixed with BF_ so that your MY_ files can extend them.

## Router.php

The Router class has been completely replaced with a modified version that includes ideas and code from segersjens/CodeIgniter-HMVC-Modules and Laravels Router. By replacing the file, we get a slight performance increase, and increased functionality.

## Loader

The Loader class has a lot of changes to it, but most of them are adding in the modules code. However, after that is all said and done, the order of adding views was altered so that the module loaded would take precedence over Bonfire view files.

    // Line 1273 (in add_package_path):
    $this->_ci_view_paths = $this->_ci_view_paths + array($path.'views/' => $view_cascade);