<h3><?php echo lang('us_access_logs'); ?></h3>

{{ if activities }}
	<ul class="clean">
	{{ activities }}
		<li>
			<span class="small">{{created_on}}</span>
			<br/>
			<b>{{ if identity }} {{ email }} {{ else }} {{ username }} {{ endif }}</b> {{ activity }}
		</li>
	 {{ /activities }}
	</ul>
{{ else }}
	<?php echo lang('us_no_access_message'); ?>
{{ endif }}