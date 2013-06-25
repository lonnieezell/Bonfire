## 1 Requirements

Module Builder is available in the Developer context and requires some knowledge of database table design, column types and form layout.


<a name="building-a-module"></a>
## 2 Building a module

The builder generates the code for a module based on the inputs to a few form fields and can build a DB based module or a module without DB access.  The builder can create a DB table for you or you can use an existing db table to create the module.

To create a new module which does not require DB access requires you to fill out one form field (the module name field) and pressing the "Build" button.

This will generate a module in the *bonfire/modules* folder with config, controllers, language, migrations (for permissions) and view files.  Amazing for one form field and a button click.

Of course that's just the basic module.  To get more you have to give more!

If you want to use a DB table then you need to fill out more fields.  You can choose to build a new DB table or choose an existing table.

<a name="new-db-table"></a>
### 2.1 With a new database table

You must choose the number of fields you would like in the table, excluding the primary key field which is automatically included.

Then for every field you fill in the form Label, table field name, form input element, table field type, table field length and the form validation settings you would like.

The primary key is assumed to be "id" but this is editable before (an of course after) the form is submitted.

Once the field details have been entered then just click the "Build" button.

Hey presto! Now you have a brand new shiny module with all files mentioned above as well as a new model file and a migration file to create the table in the database.  The migration file is even run automatically so you can use the module straight away.

<a name="existing-db-table"></a>
### 2.2 With an existing database table

You must choose the name of the table which you would like to use to build your module.

The table name must include the prefix used for your Bonfire site which is "bf_" by default. If your table does not have this prefix then please rename it to include the prefix. Then click on the "Build" button.

This then reads the structure of the database table and picks out the field characteristics including the primary field.

The page returned includes settings for each of the fields and sets up the field label for you.

For every field you can change this data but it is advisable to only change the Label and Validation settings as anything else may cause errors.


When you are finished editing the fields just click the "Build" button again.

Now you have a new module based on your previous database table with all files mentioned above as well as a new model file.  The migration file is not created in this case as the table already exists but there is a migration file for the permission settings which is processed automatically so you can use the module straight away.

<a name="options"></a>
## 3 Module Builder Options

There are lots of options which you can use when building your module.  These options are hidden by default but to see them you just click on the "Toggle Advanced Options" links.

Clicking on the link will display the options below the link.Then for every field you fill in the form Label, table field name, form input element, table field type, table field length and the form validation settings you would like.

There are two sets of options, Module Options and Table Options which we will describe below.

<a name="module-options"></a>
### 3.1 Module Options

The Module Options allow you to customize the module functionality.  The options are:

* Module Description - The module description is placed in the module config file and appears on the module list in the main Module Builder page.
* Contexts Required - Lists the available Bonfire contexts, you choose the contexts you would like the builder to create for your module. All contexts are selected by default.
* Controller Actions - Lists the CRUD actions which the builder can create for you. All actions are selected by default.
* Give Role Full Access - Choose which Bonfire role you would like to have full access to the module after creation.  The Administrator role is the default.


<a name="table-options"></a>
### 3.2 Table Options

The Table Options are related to the DB table if you chose to build the module with DB access. The optiosn are:

* Table Name - This is the name given to the table in the database. This is defaulted to the lowercase and underscored version of the module name.
* Form Input Delimiters
* Form Error Delimiters
* Textarea Editor - None
* Use "Soft" Deletes - FALSE
* Use "Created" Field - FALSE
* "Created" Field Name - created_on
* Use "Modified" Field - FALSE
* "Modified" Field Name - modified_on
* Primary Key - id

<a name="known-issues"></a>
## 4 Known Issues

The Module Builder is a great way to get started but there are a couple of issues which will be fixed in the next version:

* Error messaging is not the best
* Building from existing tables shows some fields in the form which are not applicable