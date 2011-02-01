<h1>Creating A Module Tutorial</h1>

<p>For the sake of a quick tutorial, we will take the tried-and-true route and create a quick article module.</p>

<h2>Getting Ready</h2>

<p>To get things started, you will need to create the folder structure for your module. Create the following new folders in bonfire path:</p>

<pre>/bonfire
   /modules
      /articles
         /controllers
         /models
         /views
</pre>

<p>For this tutorial, there is no need for a helper or any config or language files, so these are the only folders that we need.</p>

<h3>The Database Structure</h3>

<p>Prepare your database by creating the following table:</p>

<pre>CREATE TABLE `articles` (
	`article_id` INT (10) unsigned NOT NULL auto_increment,
	`user_id` BIGINT (20) unsigned NOT NULL default 0,
	`title` VARCHAR (255) NOT NULL default '',
	`alias` VARCHAR (255) NOT NULL default '',
	`content` TEXT NOT NULL,
	`created_on` DATETIME NOT NULL default '0000-00-00 00:00:00',
	`modified_on` DATETIME NOT NULL default '0000-00-00 00:00:00',
	`published` TINYINT (1) default 0,
	PRIMARY KEY (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET =utf8 COLLATE=utf8_general_ci;
</pre>

<h3>Adding Permissions</h3>

<p>To allow permissions to be set within the Roles editor, you currently must add a row to the bf_permissions table manually. This will be fixed in later versions to be part of the module install/upgrade feature.</p>

<p>For our module, we need to be able to control who can view articles and who can manage them (add/edit/delete). By creating a View permissions, we can restrict articles to certain roles, say for a membership site.</p>

<p>Modify the permissions table by running the following SQL: </p>

<pre>ALTER TABLE  `bf_permissions` 
ADD  `Bonfire.Articles.View` TINYINT( 1 ) NOT NULL DEFAULT  '0',
ADD  `Bonfire.Articles.Manage` TINYINT( 1 ) NOT NULL DEFAULT  '0'
</pre>

<p>For a more in-depth overview of how Roles and Permissions work, read the <?php echo anchor('general/roles', 'Roles and Permissions'); ?> topic.</p>

<h2>Let's Get Coding</h2>

