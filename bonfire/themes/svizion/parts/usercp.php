			<?php
				// acessing our userdata cookie
				$cookie = unserialize($this->input->cookie($this->config->item('sess_cookie_name')));
				$logged_in = isset ($cookie['logged_in']);
				unset ($cookie);

				if ($logged_in) : ?>
			<div class="profile">
				<a href="<?php echo site_url();?>">Home</a> |
				<a href="<?php echo site_url('users/profile');?>">Edit Profile</a> |
				<a href="<?php echo site_url('logout');?>">Logout</a>
			</div>
			<?php endif;?>
