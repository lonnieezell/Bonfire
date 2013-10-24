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

To disable migrations, edit the following line in *application/core modules/migrations/config/migrations.php* to be <tt>false</tt>.

    $config['migrations_enabled'] = true;


<a name="anatomy"></a>
## Anatomy of a Migration

A migration is a subclass of <tt>Migration</tt> that implements two methods: up (perform the required transformations) and down (revert them). Within each migration you can use any of the methods that CodeIgniter provides, like the [dbutils](http://codeigniter.com/user_guide/database/utilities.html) and [dbforge](http://codeigniter.com/user_guide/database/forge.html) classes.


<a name="creating"></a>
## Creating a Migration

<a name="filename"></a>
### File Name

Migration files MUST be numbered sequentially.  The rest of the file name is up to you, but it is recommended that the name describe what actually happens in the file.  Like <tt>Install_initial_tables</tt>, <tt>Permissions_upgrade</tt>, etc.  It must end with the *.php* extension.

    001_Install_initial_tables.php
    002_Version_02_upgrades.php
    003_Permissions_upgrade.php

<a name="file-structure"></a>
### File Structure

The file is a standard PHP class, that must follow three simple rules:

* The class must be named the same as the file, except the number is replaced by Migration.  For a file named<tt>001_Install_initial_tables.php</tt>, the class would be named <tt>Migration_Install_initial_tables</tt>.  The name is case-sensitive.
* The class MUST extend the <tt>Migration</tt> class
* The class MUST include two methods: <tt>up()</tt> and <tt>down()</tt>.  As the names imply, the <tt>up()</tt> method is ran whenever you are migrating up to that version.  The <tt>down()</tt> method is ran whenever uninstalling that migration.


<a name="skeleton"></a>
### A Skeleton Migration

```php
    class Migration_Install_initial_tables extends Migration {

      function up() {
          ...
      }

      //--------------------------------------------------------------------

      function down() {
          ...
      }

      //--------------------------------------------------------------------
    }
```

<a name="running"></a>
## Running Migrations

Migrations can be run, both up and down, in the Bonfire admin pages. You can find them under *Database / Database Tools / Migrations*.

### Auto-Running Migrations

Migrations can be set to auto-run when discovered by changing a couple of lines in the <tt>application/config/config.php</tt> file. At the bottom of the file you'll find the following lines.

    $config['migrate.auto_core']  = TRUE;
    $config['migrate.auto_app']   = FALSE;


<tt>migrate.auto_core</tt>, when set to TRUE, will run a check for new migrations for Bonfire Core on every page load.

<tt>migrate.auto_app</tt>, when set to TRUE, will run a check for new migrations for your application-specific migrations on every page load.

These are very handy to have set to TRUE in both Development and Staging/Test environments, but will slow your site down some since they check on every page load. It is recommended that Production environments set both of these to FALSE and run your migrations manually or as part of an update script.