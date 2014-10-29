# Performance Tips

This page will provide some tips for getting better performance out of your Bonfire-built applications. These tips will grow over time.

Most of these tips are intended for production environments, not development environments.

Feel free to add your own tips to help other developers out.

## Set a Caching Type

While Bonfire has caching loaded and enabled by default, it sets the driver to a non-caching driver that makes development easier since you don't have to worry about results not showing up because the previous cache hasn't expired yet. While the use of caching is small at the moment, it will increase in future releases to boost performance in the admin area. You should definitely implement caching, where appropriate, in your own applications.

To change the type of cache driver used, you need to edit the `MY_Controller` file. Find the `__construct()__` method of the `Base_Controller` and you'll see that in a production environment it defaults to using `APC` caching with a `file cache` as the backup. For some servers, the file-based caching may be slowing things down so you will need to adjust and tweak as necessary for your specific environment.

For types of caching, refer to the [CodeIgniter User Guide](http://ellislab.com/codeigniter/user-guide/libraries/caching.html).

## Disable Database Debugging

By default, CodeIgniter will store bits of debugging information, like the last query ran, etc. This takes up both memory and processing power. To get a tiny boost in performance and memory usage, edit your production environment's `db_debug` setting for your database and set it to `FALSE`.

Be aware that this will make query information unavailable for the Profiler. This typically is not a problem, as the profiler is usually turned off for production sites.

## Turn Off Auto-Migrations

On every page load, Bonfire can check to see if migrations should be run. This makes an additional hit or two on the database and extra time is spent doing something that is not required, and often not even desired, in a production environment. It is recommended that you turn off auto migrations for production environments and handle these manually, or as part of an update script.

These can be turned off by modifying your `application.php` file and setting the following settings to `FALSE`.


    $config['migrate.auto_core']  = FALSE;
    $config['migrate.auto_app']   = FALSE;
