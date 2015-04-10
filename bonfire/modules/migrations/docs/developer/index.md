# Migrations

## What are Migrations?

Migrations are simple files that hold the commands to apply and remove changes to your database. This allows you and your team to easily keep track of changes made for each new module. They may create tables, modify tables or fields, etc. But they are not limited to just changing the schema. You could use them to fix bad data in the database or populate new fields.

While you could make the changes to the database by hand, migrations provide a simple, consistent way for developers to stay on track with each other's changes. It also makes it simple to apply the changes in your development environment to your production environment.

Using migration files also creates a version of your database that can be included in your current code versioning, whether you use git, svn, or another solution. While you might not have your data backed up in the case of a devastating loss, you can at least recreate your database and start over.

Migrations are contained in sequentially numbered files so the system knows the order to apply them or remove them.

<a name="spheres"></a>
## Migrations Spheres

Bonfire recognizes three distinct areas where migrations can be applied: Core, Application, and Module. All of the files are exactly the same structure, but are placed in different areas to allow you to keep your numbering systems separate.

<a name="core"></a>
### Core Migrations

Core migrations are files that are necessary for the database schema of Bonfire core. This keeps any Bonfire updates completely separate from your application's needs, and helps to remove any conflict between your own migration file numbers and Bonfire's.

These are stored under *application/db/migrations/core*.

<a name="application"></a>
### Application Migrations

For your own application, you should use application-level migrations. This is the perfect place for changes apply to application-specific changes. However, if you are planning on re-using modules from one application to the next, you should consider placing them at the module level.

These migration files are stored under *application/db/migrations*.

<a name="module"></a>
### Module Migrations

Each module can contain its own migrations, that can be applied completely separate of any application or core migrations. This allows for you to easily re-use your modules in other applications.

Module-level migrations are stored in *modules/my_module/migrations*.

<a name="enabling"></a>
## Enabling Migrations

A clean install has migrations enabled by default.  However, it is recommended when you move to production to disable migrations for security.

To disable migrations, edit the following line in *application/core modules/migrations/config/migrations.php* to be `false`.

    $config['migrations_enabled'] = true;

<a name="anatomy"></a>
## Anatomy of a Migration

A migration is a subclass of `Migration` that implements two methods: up (perform the required transformations) and down (revert them). Within each migration you can use any of the methods that CodeIgniter provides, like the [dbutils](http://codeigniter.com/user_guide/database/utilities.html) and [dbforge](http://codeigniter.com/user_guide/database/forge.html) classes.

<a name="creating"></a>
## Creating a Migration

<a name="filename"></a>
### File Name

Migration files MUST be numbered sequentially.  The rest of the file name is up to you, but it is recommended that the name describe what actually happens in the file.  Like `Install_initial_tables`, `Permissions_upgrade`, etc.  It must end with the *.php* extension.

    001_Install_initial_tables.php
    002_Version_02_upgrades.php
    003_Permissions_upgrade.php

<a name="file-structure"></a>
### File Structure

The file is a standard PHP class, that must follow three simple rules:

* The class must be named the same as the file, except the number is replaced by Migration.  For a file named`001_Install_initial_tables.php`, the class would be named `Migration_Install_initial_tables`.  The name is case-sensitive.
* The class MUST extend the `Migration` class
* The class MUST include two methods: `up()` and `down()`.  As the names imply, the `up()` method is ran whenever you are migrating up to that version.  The `down()` method is ran whenever uninstalling that migration.


<a name="skeleton"></a>
### A Skeleton Migration

```php
    class Migration_Install_initial_tables extends Migration
    {
      public function up()
      {
          // ...
      }

      public function down()
      {
          // ...
      }
    }
```

<a name="running"></a>
## Running Migrations

Migrations can be run, both up and down, in the Bonfire admin pages. You can find them under *Database / Database Tools / Migrations*.

### Auto-Running Migrations

Migrations can be set to auto-run when discovered by changing a couple of lines in the `application/config/config.php` file. At the bottom of the file you'll find the following lines.

    $config['migrate.auto_core']  = TRUE;
    $config['migrate.auto_app']   = FALSE;


`migrate.auto_core`, when set to TRUE, will run a check for new migrations for Bonfire Core on every page load.

`migrate.auto_app`, when set to TRUE, will run a check for new migrations for your application-specific migrations on every page load.

These are very handy to have set to TRUE in both Development and Staging/Test environments, but will slow your site down some since they check on every page load. It is recommended that Production environments set both of these to FALSE and run your migrations manually or as part of an update script.

## Migration Class

The Migration class is an abstract base class which your migrations must extend.
This class provides the ability to use `$this->` to reference any libraries currently loaded by Bonfire/CodeIgniter.
It also requires you to define the `up()`/`down()` methods and allows you to set the `migration_type` property to control the behavior of your migration.

### migration_type Property

By default, the `migration_type` property is set to `'forge'`, which means the library will load dbforge and your migration will be expected to execute the commands required to perform the migration.

If the property is set to `'sql'`, dbforge will not be loaded, and the Migrations library will attempt to execute your migration as a SQL migration.
A SQL migration is expected to return a SQL string from the `up()` and `down()` methods which will perform the required changes when executed against the database.

## Migrations Library

### Properties

#### error

_Deprecated_ since 0.7.1. Use `getErrorMessage()`.

The most recent error message.

### Methods

#### autoLatest()

Auto-run core and/or app migrations.
Used on page load to run current core and app migrations up to the latest version, if enabled in the config file.

`'migrate.auto_core'` determines whether core migrations are run when this method is called.

`'migrate.auto_app'` determines whether app migrations are run when this method is called.

#### doSqlMigration([$sql = ''])

Executes raw SQL migrations.
Multiple commands may be passed in $sql by separating them with a semicolon (`;`).

#### getAvailableVersions([$type = ''])

Return a list of available migrations files of the given `$type`.

- If `$type` is empty, returns a list of core migrations.
- If a module name is supplied in `$type`, returns a list of migrations for that module.
- If `$type` is `'app_'`, returns a list of app migrations.

#### getErrorMessage()

Returns the most recent error message, or an empty string.

#### getErrors([$key = ''])

Get all of the errors (in an array), or the error message associated with the given `$key`.

#### getModuleVersions()

Retrieve the module versions in a single DB call and set the cache.
The retrieved versions will be in an array with the module names as the keys and the versions as the values.
If the database query fails, an empty array is returned.

#### getVersion([$type = ''[, $getLatest = false]])

Get the schema version from the cache.
If a database query is required, cache the result.

If `$getLatest` is true, the latest available version for the given $type will be returned.

$type can be 'app_', 'core', or the name of a module.
If `$type` is empty, it will default to 'core'.

#### install([$type = ''])

Install all migrations up to the latest version for the given `$type`, where `$type` is the name of the module, `'app_'`, or empty for core migrations.

