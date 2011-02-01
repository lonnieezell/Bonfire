<h1>Understanding Modules</h1>

<p>Bonfire is built around the concept of modules--collections of small (or sometimes not-so-small) applications designed to fulfill a specific purpose and be re-usable. At its core, Bonfire is simply a collection of modules, itself, that handle user management, roles, pages, and more. To add functionality to your application, you will be creating new modules that work within the Bonfire ecosystem. </p>

<p>Creating new modules requires a basic understanding of how to program with <a href="http://codeigniter.com" target="_blank">CodeIgniter</a>.</p>

<h2>Types of Modules</h2>

<p>Modules will use several different controllers to create the user-interface you see. There is one module for each major area of the admin interface, including: </p>

<ul>
	<li><b>Content</b> - is intended for any task that requires creating content. This might be creating new web pages, blog posts or products. The controller should be located at <dfn>modules/module-name/controllers/content.php</dfn></li>
	<li><b>Statistics</b> - is when any reporting information should go. This might be sales reports, traffic analysis, or SEO reports. The controller should be located at <dfn>modules/module-name/controllers/statistics.php</dfn></li>
	<li><b>Appearance</b> - is where anything related to the visual look of your website should go. By default, only the template manager is here, but could include things like a UI for managing a slider on your home page, etc. The controller should be located at <dfn>modules/module-name/controllers/appearance.php</dfn></li>
	<li><b>Settings</b> - is where any settings for your module should go. The controller should be located at <dfn>modules/module-name/controllers/settings.php</dfn></li>
	<li><b>Developer</b> - is for any applications that are intended at making the developers job easier. It is not necessarily intended for general site admins or content managers, as the tools are often dangerous to use if you you're not familiar with how they should work. The controller should be located at <dfn>modules/module-name/controllers/developer.php</dfn></li>
	<li><b>Front-end</b> - A front-facing module is what is shown to site visitors and typically has a URI that shares the same name as the module. For example, when a user visits http://yoursite.com/blog, the blog module would pick up this request and then show the blog overview page. The controller should be located at <dfn>modules/module-name/controllers/module-name.php</dfn></li>
</ul>

<h2>Module Location</h2>

<p>All third-party modules should be stored within <dfn>bonfire/modules</dfn>, inside of a folder that is named after the module. For example, if we are creating an articles module, the folder structure would look like:</p>

<pre>/bonfire
   /modules
      /articles
         /config
         /controllers
         /helpers
         /libraries
         /models
         /views
</pre>

<p class="important"><b>Module Names</b> The name that shows up within the admin UI is determined by the name of the module folder. The <b>articles</b> module would appear as <var>Articles</var>, while a module with a folder named <b>client_tasks</b> would show up as <var>Client Tasks</var>.</p>