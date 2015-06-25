## The Template Parser

Bonfire ships with a powerful template parser called Lex, which was originally devleoper by Dan Horrigan and then taken over and maintained by the PyroCMS team. 

### Enabling the Parser

The parser can be enabled by setting the <tt>parse_views</tt> option of the Template library to TRUE. 

    Template::$parse_views = TRUE;

Now all views will be rendered using the template parser. Lex does allow for PHP to be parsed within the layouts at the same time as the Lex tags so you can keep the parser turned on at all times without any issues. 

## Tag Basics

To display data in Lex, you must use a tag. Tags are variables that are wrapped in double curly braces. 

    {{ name }}

Note that whitespace before or after the braces does not matter, though more advanced uses will require whitespace. 

To provide data for your tags, use the <tt>Template::set();</tt> method.

    // In the controller
    Template::set('name', 'Bonfire');
    
    // In the View
    {{ name }}    // Display: Bonfire

You can access elements of an array within a tag by using a period (.) and the array key name. 

    // In the controller
    $user = array(
        'name' = 'Darth Vader'
    );
    Template::set('user', $user);

    // In the View
    Your name is {{ user.name }}.

### Looping Tags

When tags contain an array of data, like blog posts, you can loop through the data by using opening and closing tags. Any array values will then be accessible by their key name.

    // In the controller
    $posts = array(
        array(
            'title' => 'Post 1',
            'url'   => 'blog/post-1'
        ),
        array(
            'title' => 'Post 2',
            'url'   => 'blog/post-2'
        )
    );
    Template::set('posts', $posts);
    
    // In the view
    {{ posts }}
        <h1><a href="{{ url }}">{{ title }}</a></h1>
    {{ /posts }}

### Conditional Tags

Conditionals in Lex are simple and easy to use, allowing for the standard <tt>if</tt>, <tt>elseif</tt> and <tt>else</tt>. It also adds <tt>unless</tt> and <tt>elseunless</tt>.

<tt>unless</tt> and <tt>elseunless</tt> are the EXACT same as using <tt>{{ if ! (expression) }}</tt> and <tt>{{ elseif ! (expression) }} respectively.</tt>

    {{ if show_name }}
        <p>My name is {{ real_name.first }}  {{ real_name.last }}.</p>
    {{ endif}}

    {{ if user.group == 'admin' }}
        <p>You are admin!</p>
    {{ elseif user.group == 'user' }}
        <p>You are a normal user.</p>
    {{ else }}
        <p>I don't know what you are.</p>
    {{ endif }}

Undefined variables in conditionals are translated to NULL. This means you can do things like <tt>{{ if foo }}</tt> and not worry about whether it exists or not.

## Modules, Libraries and Templates

The most useful part of using the template library is that we can can module methods and library methods directly from our views. This is done by calling the module name followed by a colon and the name of the method. 

    {{ settings_lib:item item="site.title" }} 
    {{ Assets:image path='/assets/images/bonfire_logo.png' }}
    {{ news:latest }}
        <li> {{ date }} - {{ name }} - {{ id }}</li>
    {{ /news:latest }}

Calling modules is still in its infancy and you might run into a few issues, but here are some guidelines to follow: 

* Only use a controller that extends from CI_Controller and perform any additional validation and security checks, loading libraries, etc, as normal.
* The controller should be of the same name as the module.
* The names of the parameters within the tag don't matter. They are mostly useful for you to remember what parameter is what. The values must be passed in the same order that the method requires them. 

## Full Docs

These documentations are just to get your feet wet in using the template parser in your projects. For full documentation, see the [Lex GitHub site](https://github.com/pyrocms/lex).
