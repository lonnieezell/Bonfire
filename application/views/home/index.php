<div class="jumbotron" text-align="center">
	<h1>Welcome to Bonfire</h1>

	<p class="lead">Kickstart your CodeIgniter applications and save yourself 100s of hours of development time.<br/>That means you make more money.</p>

	<?php if (isset($current_user->email)) : ?>
		<a href="<?php echo site_url(SITE_AREA) ?>" class="btn btn-large btn-success">Go to the Admin area</a>
	<?php else :?>
		<a href="<?php echo site_url(LOGIN_URL); ?>" class="btn btn-large btn-primary"><?php echo lang('bf_action_login'); ?></a>
	<?php endif;?>

	<br/><br/><a href="<?php echo site_url('/docs') ?>" class="btn btn-large btn-info">Browse the Docs</a>
</div>

<hr />

<div class="row-fluid">

	<div class="span6">
		<h4>A Solid Base</h4>

		<p>Bonfire is based on <a href="http://ellislab.com/codeigniter" target="_blank">CodeIgniter <?php echo CI_VERSION; ?></a>, a proven PHP framework. In order to make the best use of it, you should be comfortable with CodeIgniter and its <a href="http://ellislab.com/codeigniter/user-guide/" target="_blank">documentation</a> first.</p>

		<p>We use Twitter's <a href="">Bootstrap</a> front-end framework and <a href="http://jquery.com/">jQuery</a> as the basis of the CSS and Javascript.</p>
	</div>

	<div class="span6">
		<h4>A Growing Community</h4>

		<p>Bonfire has an ever-growing <a href="http://forums.cibonfire.com">community</a> of users that are there to help you get unstuck, or make the best use of this powerful system.</p>

		<p>Bugs and feature discussion also happen on GitHub's <a href="https://github.com/ci-bonfire/Bonfire/issues?direction=desc&labels=0.7&sort=created&state=open">issue tracker</a>. This is the best place to report bugs and discuss new features.</p>
	</div>
</div>

<div class="row-fluid">

	<div class="span6">
		<h4>Built-in Flexibility</h4>

		<p>A <a href="https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc">modular system</a> that allows code re-use, and overriding core modules with custom modules.</p>

		<p>A <i>template system</i> that allows parent-child themes, and overriding controller views in the template.</p>

		<p><i>Role-Based Access Control</i> that provides as much fine-grained control as your modules need.</p>
	</div>

</div>