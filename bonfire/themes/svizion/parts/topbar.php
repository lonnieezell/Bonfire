<?php
	// acessing our userdata cookie
	$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
	$logged_in = isset ($cookie['logged_in']);

  unset ($cookie);

	if ( $logged_in == true )
	{

				$username = $current_user->email;
    $user_img = '<i class="icon-user" >&nbsp;</i>'; //
    $user_img = gravatar_link($username, 22, $username, "{$username} Profile", ' ', ' ' );

	}
?>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <?php echo anchor( '/', $this->settings_lib->item('site.title'), 'class="brand"' ); ?>
          <div class="nav-collapse">
            <ul class="nav nav-pills">
              <li class="divider-vertical"></li>
              <li><a href="<?php echo site_url('about');?>">About</a></li>
              <li class="divider-vertical"></li>
            </ul>
            <ul class="nav pull-right">
              <li class="divider-vertical"></li>
<?php if ($logged_in) : ?>
            <li class="dropdown" style="height:40px">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo $user_img; ?>
                <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo site_url(); ?>">Home</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo site_url('users/profile');?>">Edit Profile</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo site_url('logout');?>">Logout</a></li>

              </ul>
            </li>
<?php else :  ?>
  <li><a href="<?php echo site_url('login');?>">Login</a></li>
<?php endif; ?>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<!-- End of Navbar Template -->
