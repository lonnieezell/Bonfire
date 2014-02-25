

# System Events?

Event hooks allow you to integrate your custom programming into various places of Bonfire’s execution, without having to modify the core code. Because this is aimed squarely at the developer, and not the end user, there is no GUI to manage this functionality. Instead, registering events is done by modifying a config file.

## Why Not Use Hooks?

CodeIgniter’s [hooks](http://codeigniter.com/user_guide/general/hooks.html) are a great feature, but they don’t always provide enough flexibility. There is no documentation on how to create your own hooks (though it’s not that tough), and it lacks the ability to pass any dynamic parameters to the hook. For a modular system like Bonfire, hooks don’t provide any transparent method to support multiple modules.

That’s what System Events aim to solve.

## Extending The Core

Bonfire's core provides a number of hooks integrated into the system that you can tap into to modify the behavior or data without modifying the core code itself. You tell Bonfire that you want to respond to certain events by creating a class/method and editing the `config/events.php` file with the name of the hook and the information where to find the code to run. The code can live in any module's library or controller that you choose.

### 2.1 Registering An Event

To take an action whenever a new user is created in the system, like creating some new data specific to your module, you would first create a method in your class' controller to handle this. For this example, we have chosen to add a `setup_defaults()` method in our `my_module` controller, but it could have been in a library just as easily.

```php
	public function setup_defaults($user_id)
	{
		...
	}
```

We know that the *payload* the event provides is the id of the user, so we capture that in the first parameter. At this point, we could use the `user_model` to retrieve the user, or save some new settings just for this user, etc.

The next step is to register our event in the configuration file. Open up `application/config/events.php` and add a new `$config` array.

```php
	$config['after_create_user'][] = array(
		'module'	 => 'my_module',
		'filepath'	 => 'controllers',
		'filename'	 => 'my_module.php',
		'class'	 => 'My_module',
		'method'	 => 'setup_defaults'
	);
```

The following parameters are all required within the config array:

* `module` - The name of the module to find it it. Must be the same as the folder name of the module.
* `filepath` - The path to the file within the module’s folder. This allows you to use any controller, library, or helper file for your event.
* `filename` - The name of the file to load. Must include the file extension.
* `class` -The name of the class to create an instance of.
* `method` - The name of the method within the class to call.

Most system events will deliver a payload. This will typically be an array of data that the event allows you to operate on. Each payload will be described in the event descriptions below.

### Core Events

The following table lists all events within the core of Bonfire that are available for you to access.

**Controllers**

Event	 | Description
---------------------------------|-------------
before_controller	 | Called prior to the controller constructor being ran. Payload is the name of the current controller.	|
after_controller_constructor	| Called just after the controller constructor is ran, but prior to the method being ran. Payload is the name of the current controller.	|

**Templates and Pages**

Event	 | Descrpription
-----------------------------|-----------------
after_page_render	 | Called just after the main view is rendered. Payload is the view’s output.	|
after_layout_render	 | Called just after the entire page is rendered. Payload is the current output.	|
after_block_render	 | Called just after the block is rendered. Payload is an array with ‘block’ being the name of the block and ‘output’ being the rendered block’s output.	|

**Users**

Event	 | Description
-----------------------------|---------------
after_login	 | Called after successful login. Payload is an array of ‘user_id’ and ‘role_id’.	|
before_logout	 | Called just before logging the user out. Payload is an array of ‘user_id’ and ‘role_id’.	|
after_create_user	 | Called after a user is created. Payload is the new user’s id.	|
before_user_update	 | Called just prior to updating a user. Payload is an array of ‘user_id’ and ‘data’, where data is all of the update information passed into the method.	|
after_user_update	 | Called just after updating a user. Payload is an array of ‘user_id’ and ‘data’, where data is all of the update information passed into the method.	|

## Using Events In Your Modules

You can use events in your own modules to provide places for other modules to hook into. It is very simple and only requires that you use a single line of code in your library, model, or controller wherever you would like the hook to fire.

```php
	Events::trigger('event_name', $payload);
```

The function takes two parameters.

The first parameter is the name of the event to call. You may use any name that you want, so long as it doesn't conflict with an existing event name. To avoid collisions, it is recommended that you prefix your event name with the name of your module, like `forums.after_comment`.

The second parameter is a single variable that contains the payload that you wish to provide to the event responders. This could be an ID, an array with multiple pieces of data, or anything else that is appropriate to your needs.
