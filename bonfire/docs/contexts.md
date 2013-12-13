# Contexts

A Context is an area of similar responsibility within the code and/or UI.  They appear as top-level navigation objects within the main menu, and should all cover a similar topic, or theme. The following are all good candidates for Contexts:

Context         | Description
----------------|---------------
Security        | Holds menu items from different modules that provide security tools and reports.
Forums          | You can group all of your forums admin panels in a single context.
Design          | You might provide methods for editing your templates or various error pages
Tools           | Might contain collections of utilities that don't belong elsewhere.
Role-Specific   | A single role might have access to a set of tools that no one else does. These could be grouped so they are easier to provide access to. |


<a name="controllers"></a>
## Context Controllers

Contexts map directly to controllers of the same name in your module. If you are creating a controller to handle your Settings Context for your module, the controller should be named `content.php` and the class should be named `Content`.


    class Content extends Admin_Controller {
        ...
    }

<a name="views"></a>
## Context Views

All views for your contexts should be stored within the views folder of your module, under a sub-folder named after the context. Continuing our example, the view for the index method would exist at `my_module/views/content/index.php`.


<a name="public"></a>
## Public Contexts

A Public Context is just a fancy name for what you would typically do in a CodeIgniter application. This controller is named the same as your module and can have as many methods that map directly to the URI as you need.

Views are stored directly in your views folder. For front-facing page, or Public Contexts, there's little-to-no magic going on behind the scenes other than what you're already used to working with. The file would then be available at:

    http://yoursite.com/module_name


<a name="required"></a>
## Required Contexts

Bonfire requires only two contexts to exist, and will create them if they don’t. They are the **Developer** and **Settings** Contexts.

Context     | Description
------------|------------------
Developer   | This context is meant for tools designed to make the application developer’s life easier and may include database tools, code generation, deploy helpers, etc. |
Settings    | Any settings related to your module.  These are typically items that are infrequently changed, like the number of events to show on each page, etc. |


<a name="custom"></a>
## Creating New Contexts

You can create any number of contexts to fit your application’s specific needs.  Doing so is a very simple task that only requires a single step.

<a name="array"></a>
###  Update the Application Config Array

To create a new context that will be displayed in the admin area, you only need to add the name of the context to the <td>contexts</td> array in the <td>config/application.php</td> file. The context name must not contain any spaces. If more than one word is needed, separate the words with an underscore.


<a name="name"></a>
### Localizing the Context Name

To make your context's name localized, you simply need to add a new entry in the <td>language/english/application_lang.php</td> file. The name should be preceded by <td>bf_context_</td>.


    $lang['bf_context_new'] = 'New Context';


The localized version will automatically be used if present, otherwise the name displayed will be the name of the context.

<a name="access"></a>
### Restricting Access to Contexts

You can restrict access to your contexts by creating a new permission. This permission should be named 'Site.{context}.View', where <td>{context}</td> is the name of your context. Once this permission is created in the system, no one will be able to view the context if they do not have permission.

In order to manage the permission, you will need to create another permission called 'Permissions.{context}.Manage'.