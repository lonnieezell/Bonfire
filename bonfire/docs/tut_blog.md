# A Simple Blog Tutorial

This tutorial will teach you the basics of how to work with Bonfire. Along the way, you will learn the basic concepts you need to know to best work within the system. You will learn the details and the code. We believe it's best to learn how to do things the long way, before using the power of the [Module Builder](using_module_builder).

<a name="newmodule"></a>
## Creating A Module

While modules are not required to use Bonfire, it makes extensive use of modules to encourage code reuse and organization. Whithin this tutorial we will create a new blog module.

The first step is to get the folder structure created and make sure the settings are correct for it to show up in the admin menu.

### Folder Structure

Within the `application/modules` folder, create a new folder named `blog`, and create the following subfolders: assets, config, controllers, migrations, models, views. When you're done your module should look like:

    bonfire/
        modules/
            blog/
                assets/
                config/
                controllers/
                migrations/
                models/
                views/

Not every module will require every folder, but these are the basic folders every module can use.

### Basic Configuration

To make sure that your blog shows up in the admin area, we need to create a config file that holds various settings that let Bonfire know about your module. The config file is not required, but allows more control over how your module appears throughout the system.

Create a new file in your new `config` folder, called `config.php`.

```
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    $config['module_config'] = array(
        'name'          => 'Blog',
        'description'   => 'A Simple Blog Example',
        'author'        => 'Your Name',
        'homepage'      => 'http://...',
        'version'       => '1.0.1',
        'menu'          => array(
            'context'   => 'path/to/view'
        ),
        'weights'       => array(
            'context'   => 0
        )
    );
```


Not all of these settings will be used for the Blog module, but you should understand how they work.

**Name** is the human-readable name of your module.

**Description** is a short description of your module and will appear in a list of installed modules.

**Author** is your name or the name of your organization/team.

**Homepage** is the URL to the homepage of your module. Will appear as a link in the installed modules page.

**Version** is a simple version string.

**Menu** is an array that lets you specify sub-menus that will appear under each context. It points to a view file within your module that contains a &lt;ul&gt; of your options.

**Weights** is an array that allows you to order the menu items within the main context navigation. Think of it like the menu is a bowl of water. The heavier the weight (or the larger the value), the farther down the menu it will sink.

### Setup the Database

To allow for simple versioning of your database we will use Bonfire's migrations. This is great for working in teams where modifications to the database might be made by several members on the development team. It also makes it fairly simple to bring your changes to the production database. In the case of a module like our blog module, it allows us to easily re-use the module in another application and quickly get the database setup.

Migrations are simply a set of commands that are run to make changes to the database or remove changes. They are stored in the module's migrations folder, using sequentially numbered files.

Create a new file at `blog/migrations/001_Initial_tables.php`.

```php
class Migration_Initial_tables extends Migration
{

    public function up()
    {
        $this->load->dbforge();

        $this->dbforge->add_field('post_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT');
        $this->dbforge->add_field('title VARCHAR(255) NOT NULL');
        $this->dbforge->add_field('slug VARCHAR(255) NOT NULL');
        $this->dbforge->add_field('body TEXT NULL');
        $this->dbforge->add_field('created_on DATETIME NOT NULL');
        $this->dbforge->add_field('modified_on DATETIME NULL');
        $this->dbforge->add_field('deleted TINYINT(1) NOT NULL DEFAULT 0');
        $this->dbforge->add_key('post_id', TRUE);

        $this->dbforge->create_table('posts');

        // Create the Permissions
        $this->load->model('permission_model');
        $this->permission_model->insert(array(
            'name'          => 'Bonfire.Blog.View',
            'description'   => 'To view the blog menu.',
            'status'        => 'active'
        ));

        // Assign them to the admin role
        $this->load->model('role_permission_model');
        $this->role_permission_model->assign_to_role('Administrator', 'Bonfire.Blog.View');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        $this->load->dbforge();

        $this->dbforge->drop_table('posts');
    }

    //--------------------------------------------------------------------

}
```

The `up()` method is ran whenever this migration is 'installed'. The `down()` method is ran whenever the migration is 'un-installed'.

We use CodeIgniter's `dbforge` class here to create the table, but you could use the `database` class to run raw queries, if you wanted to. We also load up the `permissions_model` and `role_permission_model` and create a new permission, `Bonfire.Blog.View` and assigns the Admin role that permission so you can actually see the blog page in the Content menu.

To install this migration, navigate to Developer / Database / Migrations. Click on the Modules tab. You will see your Blog module shows up there. Select the `001_Initial_tables.php` from the dropdown, and click 'Migrate Module'. Your `up()` method will run, installing the posts table into your database. Until we create the controller, though, it will not show up in the menu.

### Content Context

In order to manage your blog, we will create a new entry under the Content menu that takes us to all of the blog management features. To get started we will need one controller and one view.

Create a new controller, `blog/controllers/content.php`. Each context uses a controller of the same name. In this case we want to create some actions for the Content Context, so we create a controller named `content.php`.

```php
    class Content extends Admin_Controller
    {

        public function __construct()
        {
            parent::__construct();

            Template::set('toolbar_title', 'Manage Your Blog');
        }

        //--------------------------------------------------------------------

        public function index()
        {
            Template::render();
        }

        //--------------------------------------------------------------------

    }
```

Notice that the class is named the same as the Context and it extends `Admin_Controller`. The Admin_Controller is one of several controllers provided by Bonfire to take care of a few functions for you. In this case, the controller makes sure that we are logged in, sets up pagination defaults, sets the theme to the admin theme, and gets our form_validation library loaded and setup so that it works properly with HMVC.

In the `__construct()` method we are doing one thing currently, and that is to set the text that shows up in the sub-navigation bar just below the main menu in the admin area. We'll make more use of this bar in the future.

The `index()` method is the method that will be called by default when you click on the `Blog` menu item. We use the Template library's `render()` method to display the view for this page. By default, this will look for a view in the module's views folder under the context name and method name. In this case, it would be searching for `blog/views/content/index.php`. Create that file now.


```
    <h1>Blog Index</h1>
```

We are keeping it simple just to make sure everything is in working order for now.

Save the files, then navigate to **http://yoursite.com/admin/content/blog** to view your new page. You can also refresh the screen now, and a link will show up in the Content menu that you can use.

### The Blog Model

In order to view any posts, we will need to create a model that interacts with the database for us. Thanks to Bonfire's `MY_Model` base class, this is a very simple task.

Create a new model file at `blog/models/post_model.php`.

```php
    class Post_model extends MY_Model
    {

        protected $table_name   = 'posts';
        protected $key          = 'post_id';
        protected $set_created  = true;
        protected $set_modified = true;
        protected $soft_deletes = true;
        protected $date_format  = 'datetime';

        //---------------------------------------------------------------

    }
```

This is everything needed to get some pretty flexible CRUD setup and running for your post model. There are a lot more options available in the full [model file](bonfire_models) but this is all we need for now.

**$table_name** is the name of the database table that the data is stored in.

**$key** is the name of the primary key the table uses.

**$set_created** tells the system whether it should automatically store the date the object was created. In order for this to work, you must have a `created_on` datetime field in your table.

**$set_modified** tells the system whether it should automatically store the date the object was last modified. In order for this to work, you must have a `modified_on` datetime field in your table.

**$soft_deletes** tells the system whether a delete function should permanently delete the row (a 'hard' delete), or simply set a deleted flag in the table. This requires that you have a `deleted` tinyint(1) field in your table.

With the settings above, our post_model will:

* Store our data in the `posts` table in our database.
* Each row will have a primary key called `post_id`.
* Store the date the row was created in the `created_on` field.
* Store the date the row was last modified in the `modified_on` field.
* Set the `deleted` field to 1 when a row is deleted, instead of permanently removing it.

Since the post_model will be used in nearly every method in our content controller, we will autoload it in the controller's `__construct()` method.

```php
    public function __construct()
    {
        parent::__construct();

        $this->load->model('post_model');

        Template::set('toolbar_title', 'Manage Your Blog');
    }
```


### Listing Posts in Admin

It's now time to expand our blog's content index method to show us a list of all posts in the system. Add the following lines to the `index()` method of the `content` controller, before the `Template::render()` method.

```php
    $posts = $this->post_model->where('deleted', 0)->find_all();

    Template::set('posts', $posts);
```

This calls our `post_model`'s `find_all()` method to retrieve all posts in the system. However, we don't want any deleted posts, so we filter those out with the `where()` method.

We then use the Template class' `set()` method to make the data available to our view. In the view, this can be accessed as the `$posts` variable.

Edit your index.php view file to reflect the following:

```
    <div class="admin-box">
        <h3>Blog Posts</h3>

        <?php echo form_open(); ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="column-check"><input class="check-all" type="checkbox" /></th>
                        <th>Title</th>
                        <th style="width: 10em">Date</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            With selected:
                            <input type="submit" name="submit" class="btn" value="Delete">
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                <?php if (isset($posts) && is_array($posts)) :?>
                    <?php foreach ($posts as $post) : ?>
                    <tr>
                        <td><input type="checkbox" name="checked[]" value="<?php echo $post->post_id ?>" /></td>
                        <td>
                            <a href="<?php echo site_url(SITE_AREA .'/content/blog/edit_post/'. $post->post_id) ?>">
                                <?php e($post->title); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo date('M j, Y g:ia'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">
                            <br/>
                            <div class="alert alert-warning">
                                No Posts found.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

        <?php echo form_close(); ?>
    </div>
```

This creates a table that will list each blog post, if any exist. If they don't then a notice will be displayed.

Most of this should be self-explanatory, but there is one new function nestled in there, `e()`. This method is a convenience method that you should consider using wherever you are displaying user-entered data. It simply echos out the string, using the `htmlentities()` function to help protect against XSS and CSRF attacks.

### Module Sub-Menus

Now we just need a way to create new posts. Let's start by creating a new sub-menu that allows us to access other pages. This is not intended for long menus, but to provide a short list of major areas within your module. You will see this used throughout Bonfire, and it appears on the right side of the page, just under the main menu. This is the same bar that holds your `$toolbar_title`.

First, we create a view file holds the menu itself. Create a new file at `blog/views/content/sub_nav.php`.

```php
    <ul class="nav nav-pills">
        <li <?php echo $this->uri->segment(4) == '' ? 'class="active"' : '' ?>>
            <a href="<?php echo site_url(SITE_AREA .'/content/blog') ?>">Posts</a>
        </li>
        <li <?php echo $this->uri->segment(4) == 'create' ? 'class="active"' : '' ?>>
            <a href="<?php echo site_url(SITE_AREA .'/content/blog/create') ?>">New Post</a>
        </li>
    </ul>
```

Each link in this list will take us to a method within our content controller. We also check the url to see if this is an active link or not.

To make this menu show up, we need to add it to our content controller's `__construct()` method.

```php
    public function __construct()
    {
        parent::__construct();

        $this->load->model('post_model');

        Template::set('toolbar_title', 'Manage Your Blog');
        Template::set_block('sub_nav', 'content/sub_nav');
    }
```

Reload your page in the admin area, and you will see the new menu appear. Clicking on 'New Post' throws an error since we haven't created that method yet. We will do that next.

### Create A Post

We will start things simple by just displaying the form to create a new post, then deal with saving the post later.

Create a new `create()` method in your content controller.

```php
    public function create()
    {
        Template::set('toolbar_title', 'Create New Post');
        Template::set_view('content/post_form');
        Template::render();
    }
```

This sets the `toolbar_title` of the page, says that we want to use the view named `views/content/post_form.php`, and renders the form.

Now we need create the form itself. We're using a file called `post_form` because we want to be able to use the form for both the create and edit pages.

```
    <div class="admin-box">
        <h3>New Post</h3>

        <?php echo form_open(current_url(), 'class="form-horizontal"'); ?>

            <div class="control-group <?php if (form_error('title')) echo 'error'; ?>">
                <label for="title">Title</label>
                <div class="controls">
                    <input type="text" name="title" class="input-xxlarge" value="<?php echo isset($post) ? $post->title : set_value('title'); ?>" />
                    <?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
                </div>
            </div>

            <div class="control-group <?php if (form_error('slug')) echo 'error'; ?>">
                <label for="slug">Slug</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on"><?php echo site_url() .'/blog/' ?></span>
                        <input type="text" name="slug" class="input-xlarge" value="<?php echo isset($post) ? $post->slug : set_value('slug'); ?>" />
                    </div>
                    <?php if (form_error('slug')) echo '<span class="help-inline">'. form_error('slug') .'</span>'; ?>
                    <p class="help-block">The unique URL that this post can be viewed at.</p>
                </div>
            </div>

            <div class="control-group <?php if (form_error('body')) echo 'error'; ?>">
                <label for="body">Content</label>
                <div class="controls">
                    <?php if (form_error('body')) echo '<span class="help-inline">'. form_error('body') .'</span>'; ?>
                    <textarea name="body" class="input-xxlarge" rows="15"><?php echo isset($post) ? $post->body : set_value('body') ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <input type="submit" name="submit" class="btn btn-primary" value="Save Post" />
                or <a href="<?php echo site_url(SITE_AREA .'/content/blog') ?>">Cancel</a>
            </div>

        <?php echo form_close(); ?>
    </div>
```

Most of this is straight-forward. I do want to point out that for the values, we are checking whether a `$post` value is set or not, and then uses the form helper's `set_value()` method to set the value in the case of errors on the form.

We also use the form_validation library's `form_error()` function to setup individual errors for each field.

<b>Saving the Post</b>

Now, let's actually make it functional. In your post_model, we need to let it know what validation rules to use during both inserts or updates. Add the following class variable to the post_model:

```
    protected $validation_rules = array(
        array(
            'field' => 'title',
            'label' => 'Title',
            'rules' => 'trim|strip_tags|xss_clean'
        ),
        array(
            'field' => 'slug',
            'label' => 'Slug',
            'rules' => 'trim|strip_tags|xss_clean'
        ),
        array(
            'field' => 'body',
            'label' => 'Body',
            'rules' => 'trim|strip_tags|xss_clean'
        )
    );
```

These rules follow the same format as the [form validation library](http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html#validationrulesasarray). The one thing to be aware of is that these rules are used for both inserts and updates. This can cause a problem with required fields on inserts. We'll add the `insert_validation_rules` class variable to the pose_model to provide any additional rules we want applied during an insert only.

```php
    protected $insert_validation_rules = array(
        'title' => 'required',
        'body'  => 'required'
    );
```

Now, whenever you do an insert or an update, the data is run through the form validation library. If it fails validation, the model will return FALSE and your controller can respond accordingly.

Now then modify the controller's `create()` method to actually save the data:

```php
    public function create()
    {
        if ($this->input->post('submit'))
        {
            $data = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->input->post('slug'),
                'body'  => $this->input->post('body')
            );

            if ($this->post_model->insert($data))
            {
                Template::set_message('You post was successfully saved.', 'success');
                redirect(SITE_AREA .'/content/blog');
            }
        }

        Template::set('toolbar_title', 'Create New Post');
        Template::set_view('content/post_form');
        Template::render();
    }
```

### Editing Posts

Editing our posts is very simple to do now. Simply add the following `edit_post()` method to your controller and you're up and running:

```php
    public function edit_post($id=null)
    {
        if ($this->input->post('submit'))
        {
            $data = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->input->post('slug'),
                'body'  => $this->input->post('body')
            );

            if ($this->post_model->update($id, $data))
            {
                Template::set_message('You post was successfully saved.', 'success');
                redirect(SITE_AREA .'/content/blog');
            }
        }

        Template::set('post', $this->post_model->find($id));

        Template::set('toolbar_title', 'Edit Post');
        Template::set_view('content/post_form');
        Template::render();
    }
```

## The Public Context

Now that we have basic administration pages in place, it's time to actually let the users view your awesome blog posts. This requires that we create a new controller, in the same blog module, called `blog`. This will handle what we call your <em>Public Context</em> and is simply a front-facing controller that will directly map to the URI. In this case, you can view this controller at `http://yoursite.com/blog`.

Create a new file, `modules/blog/controllers/blog.php`

```php
    class Blog extends Front_Controller
    {

        public function __construct()
        {
            parent::__construct();

            $this->load->model('post_model');
        }

        //--------------------------------------------------------------------

        public function index()
        {
            $this->load->helper('typography');

            $posts = $this->post_model->order_by('created_on', 'asc')
                                      ->limit(5)
                                      ->find_all();

            Template::set('posts', $posts);

            Template::render();
        }

        //--------------------------------------------------------------------

    }
```

This is just a typical CodeIgniter controller, which means that anything you can do in straight CodeIgniter, you can do here.

In this case, we're loading our model in the constructor, since we know that we'll be using it in every method. Then we create an index method to list the 5 most recent posts.

Then, it looks for a view file at `blog/views/index.php`. Create that file now.

```php
        <?php foreach ($posts as $post) :?>
        <div class="post">
            <h2><?php e($post->title) ?></h2>

            <?php echo auto_typography($post->body) ?>
        </div>
        <?php endforeach; ?>

    <?php else : ?>
        <div class="alert alert-info">
            No Posts were found.
        </div>
    <?php endif; ?>
```

This is a very simple view, but should be enough to give you an understanding.

Navigate to `http://yoursite.com/index.php/blog` and you should see the 5 most recent blog posts for you.

## Conclusion

If this were a real application, there would be much left to add, but this is enough to get you started. At this point, you should have enough understanding of how to create modules and code for Bonfire to get you well on your way to creating the next killer app.