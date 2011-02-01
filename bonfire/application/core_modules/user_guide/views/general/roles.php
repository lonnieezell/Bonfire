<h1>Roles and Permissions</h1>

<p>Bonfire provides a Role-Based Access Control (RBAC) system that is granular enough to work for most web applications. In an RBAC, every user is assigned a Role. Every role, then, has permissions to perform various actions on each module, like viewing the content of that module, or managing it's settings.</p>

<h2>Roles</h2>

<p>Out of the box, you are given four different roles:</p>

<ul>
	<li><b>Administrator</b> - are typically the owners of the site, and handle the day to day operations of the site. They can add/delete any content, install modules, etc.</li>
	<li><b>Developer</b> - by default, the Developer role is identical to the Administrator, with the exception that they are the only ones that can access the developer tools pages. This access can be provided to any role, however, by checking the <var>Site.Developer.View</var> permission.</li>
	<li><b>Editor</b> - a role that is limited compared to an Administrator, but can still manage several types of content. However, they are not allowed to make changes to the site structure itself, by adding modules, etc. Administrators can modify this role however they see fit.</li>
	<li><b>Banned</b> - users that are unfortunate to have this role can not do anything within the site, except for view the public-facing pages.</li>
</ul>

<p>New Roles can be created by the Administrator, or any role that has the <var>Bonfire.Roles.Manage</var> permission.</p>

<h2>Permissions</h2>