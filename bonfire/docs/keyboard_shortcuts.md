## 1 Terminology used

This guide will use certain terminology in an effort to make it easier to understand:

* Shortcut Action - this is the action performed by pressing the shortcut keys and is basically a small piece of javascript which is executed
* Shortcut Key - this refers to the keys which are used to execute the actions

Very simple, but worth noting.


<a name="base"></a>
## 2 Base Shortcuts

There are seven shortcut actions available in a base install of Bonfire of which two have shortcut keys assigned:

* Form Save - This will save any form in the admin area
* Create New - In a module this action brings you to the form to create a new record
* Select All - Selects all checkboxes on a page with a list of selectable records
* Delete - This action will delete all selected records or the current record you are editing
* Goto Content - Navigates to the Content context
* Goto Reports - Navigates to the Reports context
* Goto Settings - Navigates to the Settings context

The two actions which have keys assigned are:

* Form Save - ctrl+s/⌘+s
* Goto Content - alt+c

To view the Shortcuts which are active in your Bonfire installation click on the keyboard icon at the top right of the control panel.


<a name="assign"></a>
## 3 How to assign keys

You can assign shortcut keys to each of the actions available by simply visiting the Keyboard Shortcuts page under the Settings menu.

There you can choose the action in a dropdown list and assign keys.

Bonfire uses the [Jwerty](http://keithcirkel.co.uk/jwerty/) project to handle the shortcuts and you will find more examples of possible shortcut keys on the project site.

Note: Spaces are not allowed in your shortcut keys as this would break the javascript.

<a name="add"></a>
### 4 How to add actions

The shortcut actions are managed in the application config file <tt>/bonfire/application/config/application.php</tt> in the <tt>ui.current_shortcuts</tt> array element.

The array contains an array "key" for the shortcut action, e.g. 'delete'. The action itself contains a description and the action javascript.

	/*
		Array containing the currently available shortcuts
		- these are output in the /ui/views/shortcut_keys file
	*/
	$config['ui.current_shortcuts'] = array(
		'form_save'      => array(
				'description' => 'Save any form in the admin area.',
				'action' => '$("input[name=submit]").click();return false;'
		),
		'create_new'     => array(
				'description' => 'Create a new record in the module.',
				'action' => 'document.location=$("a#create_new").attr("href");'
		),
		'select_all'     => array(
				'description' => 'Select all records in an index page.',
				'action' => '$("table input[type=checkbox]").click();return false;'
		),
		'delete'         => array(
				'description' => 'Delete the record(s).',
				'action' => '$("#delete-me.btn-danger").click();'
		),
		'goto_content'   => array(
				'description' => 'Jump to the Content context.',
				'action' => "document.location='/" . SITE_AREA . "/content';"
		),
		'goto_reports'   => array(
				'description' => 'Jump to the Reports context.',
				'action' => "document.location='/" . SITE_AREA . "/reports';"
		),
		'goto_settings'  => array(
				'description' => 'Jump to the Settings context.',
				'action' => "document.location='/" . SITE_AREA . "/settings';"
		),
	);

You can add your own shortcut actions to this list by adding a new array element. It will then appear as a dropdown option on the Keyboard Shortcuts page under the Settings menu.

It works best when the HTML classes and ids are standardized.