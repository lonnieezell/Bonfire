<style type="text/css">
#codeigniter-profiler{background:#141722;clear:both;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;position:fixed;bottom:0;left:0;width:100%;z-index:1000;}
#profiler-panel{background:#1f2332;padding:0 5px;}
.ci-profiler-box{padding:10px;margin:0 0 10px 0;max-height:300px;overflow:auto;color:#fff;font-family:Monaco,'Lucida Console','Courier New',monospace;font-size:11px!important;}
.ci-profiler-box h2{color:#ffffff;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:200;font-size:16px!important;padding:0;line-height:2.0;}
#ci-profiler-menu-open{background:#141722;position:fixed;left:0;bottom:0;padding:5px 7px;}
#ci-profiler-menu-open img{display:block;}
#ci-profiler-menu-open:hover{background:#5a8616;}
#ci-profiler-menu a{display:inline-block;font-size:13px;font-weight:200;line-height:25px;padding:3px 7px;color:#ffffff;text-decoration:none;cursor:pointer;}
#ci-profiler-menu a:first-child img{vertical-align:middle;margin-bottom:2px;}
#ci-profiler-menu a:first-child:hover,#ci-profiler-menu a:last-child:hover{background-color:transparent!important;}
#ci-profiler-menu a span{background:#ffffff;border-radius:4px;font-size:11px;font-weight:600;padding:2px 5px;vertical-align:bottom}
#ci-profiler-menu-time:hover,#ci-profiler-menu-time:focus,#ci-profiler-menu-time.current{background:#B72F09;}
#ci-profiler-menu-memory:hover,#ci-profiler-menu-memory.current{background:#953FA1;}
#ci-profiler-menu-queries:hover,#ci-profiler-menu-queries.current{background:#3769A0;}
#ci-profiler-menu-vars:hover,#ci-profiler-menu-vars.current{background:#D28C00;}
#ci-profiler-menu-files:hover,#ci-profiler-menu-files.current{background:#5a8616;}
#ci-profiler-menu-console:hover,#ci-profiler-menu-console.current{background:#5a8616;}
#ci-profiler-menu-time span,#ci-profiler-benchmarks h2{color:#B72F09;}
#ci-profiler-menu-memory span,#ci-profiler-memory h2{color:#953FA1;}
#ci-profiler-menu-queries span,#ci-profiler-queries h2{color:#3769A0;}
#ci-profiler-menu-vars span,#ci-profiler-vars h2{color:#D28C00;}
#ci-profiler-menu-files span,#ci-profiler-files h2{color:#5a8616;}
#ci-profiler-menu-console span,#ci-profiler-console h2{color:#5a8616;}
#codeigniter-profiler table{width:100%;}
#codeigniter-profiler table.main td{padding:7px 15px;text-align:left;vertical-align:top;color:#000;line-height:1;background:#F0F0F0!important;font-size:12px!important;}
#codeigniter-profiler table.main tr:hover td{background:#cdd1d4!important;}
#codeigniter-profiler table.main code{font-family:inherit;padding:0;background:transparent;border:0;color:#fff;}
#codeigniter-profiler table .hilight{color:#000D70!important;}
#codeigniter-profiler table .faded{color:#aaa!important;}
#codeigniter-profiler table .small{font-size:10px;letter-spacing:1px;font-weight:lighter;}
#ci-profiler-menu-exit{background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAPCAYAAAA71pVKAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyNpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOkQwQUYxNjIzQkNGOTExRTM4OTY3QzU4NjQ2QzdDQkMzIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOkQwQUYxNjI0QkNGOTExRTM4OTY3QzU4NjQ2QzdDQkMzIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6RDBBRjE2MjFCQ0Y5MTFFMzg5NjdDNTg2NDZDN0NCQzMiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6RDBBRjE2MjJCQ0Y5MTFFMzg5NjdDNTg2NDZDN0NCQzMiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6BRWmSAAABa0lEQVR42lzTzyuEURTG8TtjzDSKjEayUBbU/AWymZ0s/KghG2xkwcZCrKSklGzskGJrR6MsZEGRiIUNC0tRo8mGIUS8vrd5bl3vrU/dc885t/ed804kCIKEMSaKH3yZ8upBB+rwjCPsKhdHBX4NzUnEYfddeMQHbnCOa7zjCTnVVdo+4zVOB+W1ibTOnBRWlZ91F7hkTomBUFM8FHerbtjG7tCuDe1rsYaM4mbFDYqXVR81uiXwbj9QfIt2XCk+9WrsGrebHVzqMIJeFIL/q4g+5W3dCfbtiNIoemPYQ7/5v4aQR0JxAfW2+RU1OvxGCxZCzXPIeN9BCiX7CFOaq3ufQz1qCWOar10XXs0L5uymSslJJdpwjE7FWb1jVvGo6lPuphkdtIbmmgjFTapbdHOOKbGlxEiowRlUPq845r5tN4J5FdxrhOvYxp3Ol7zLkhHvX/UJnsU0YgJZVOMNZ1jBg37tpP1X/QkwAM/DSXbJEwZhAAAAAElFTkSuQmCC) 0% 0% no-repeat;position:absolute;right:5px;top:9px;height:15px;width:15px;}
</style>
<?php
$profiler_logo = '<img width="22" height="22" title="" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAYAAADEtGw7AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA3NpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNS1jMDE0IDc5LjE1MTQ4MSwgMjAxMy8wMy8xMy0xMjowOToxNSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDo5YmMwMzhkZi0xZGNjLTRhNGYtYjI0ZC0zN2RhYjAzZGNiMjYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NkJFRjQxMDhCQ0Y4MTFFMzg5NjdDNTg2NDZDN0NCQzMiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NkJFRjQxMDdCQ0Y4MTFFMzg5NjdDNTg2NDZDN0NCQzMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChNYWNpbnRvc2gpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6OWJjMDM4ZGYtMWRjYy00YTRmLWIyNGQtMzdkYWIwM2RjYjI2IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjliYzAzOGRmLTFkY2MtNGE0Zi1iMjRkLTM3ZGFiMDNkY2IyNiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgSqUnYAAAHjSURBVHjarJRLKEVBGMe/43G7XQt5lM2VkhARFhaUkpVHibIXSSIWFrKyoaxYKBsLwsZCFmJjjY2FEll4LAhJHuV1cc74z5w5t3HOnOvKnfqdme873/znO9/MHIMxRqJZH/RLC4MgOIkZlRSwO4q/5YHReIP/IhwBXaAh0cJdsu9NpHAT6JPjokQJ14ANxTYTIdwCtl2+8/8Kt4N1jX/3P8LFYNXnnZNxRqx6+wm7M31Watsv+3xwDNq0CvzmCcyIwyRgLjrAg2JHJI7dHZ0v9QzXlc4BN8RdRnTtV5AGOsG8PjsRX4nnvvdKM8oCA2JsKBMsFiKT5WK8AKucmdYpfCRwkrLjp0C1t8YGNYJWO3t8zpdlY/IysWE+hv+ALCqAPSP9JP08iXrMbPYKM6pCQAn75ILMzpboDXxhwSG+SVxIZGfQoMCZKhbhSVhhjzBWDiAg+UcpiOqU41UWLY/dZsCSq3QvuuN2CM4UexzsgRGwA+Y02zatjK9kvEd4BSwrtnNB1kAtuHZ9DW+3yngWLOqE78EYuCDSSOhboeyPwIT6InqOWeTd8aWDR7Cp7rLPUpcgE2TL805GIOh7pZ8A38RU0BMj2y1wB0KOaDz/ChxOcaZ5XVM070vlP7rCb9VvAQYARSbvijTk29UAAAAASUVORK5CYII=" />';
?>
<script type="text/javascript">
var ci_profiler_bar = {

	// current toolbar section thats open
	current: null,

	// current vars and config section open
	currentvar: null,

	// current config section open
	currentli: null,

	// toggle a toolbar section
	show : function(obj, el) {
		if (obj == ci_profiler_bar.current) {
			ci_profiler_bar.off(obj);
			ci_profiler_bar.current = null;
		} else {
			ci_profiler_bar.off(ci_profiler_bar.current);
			ci_profiler_bar.on(obj);
			ci_profiler_bar.remove_class(ci_profiler_bar.current, 'current');
			ci_profiler_bar.current = obj;
			//ci_profiler_bar.add_class(el, 'current');
		}
	},

	// turn an element on
	on : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = '';
	},

	// turn an element off
	off : function(obj) {
		if (document.getElementById(obj) != null)
			document.getElementById(obj).style.display = 'none';
	},

	// toggle an element
	toggle : function(obj) {
		if (typeof obj == 'string')
			obj = document.getElementById(obj);

		if (obj)
			obj.style.display = obj.style.display == 'none' ? '' : 'none';
	},

	// open the toolbar
	open : function() {
		document.getElementById('ci-profiler-menu-open').style.display = 'none';
		document.getElementById('codeigniter-profiler').style.display = 'block';
		this.set_cookie('open');
	},

	// close the toolbar
	close : function() {
		document.getElementById('codeigniter-profiler').style.display = 'none';
		document.getElementById('ci-profiler-menu-open').style.display = 'block';
		this.set_cookie('closed');
	},

	// Add class to element
	add_class : function(obj, a_class) {
		alert(obj);
		document.getElementById(obj).className += " "+ a_class;
	},

	// Remove class from element
	remove_class : function(obj, r_class) {
		if (obj != undefined) {
			document.getElementById(obj).className = document.getElementById(obj).className.replace(/\bclass\b/, '');
		}
	},

	read_cookie : function() {
		var nameEQ = "Profiler=";
		var ca = document.cookie.split(';');
		for (var i=0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') c = c.substring(1, c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
		}
		return null;
	},

	set_cookie : function(value) {
		var date = new Date();
		date.setTime(date.getTime() + (365*24*60*60*1000));
		var expires = "; expires=" + date.toGMTString();

		document.cookie = "Profiler=" + value + expires + "; path=/";
	},

	set_load_state : function() {
		var cookie_state = this.read_cookie();

		if (cookie_state == 'open') {
			this.open();
		} else {
			this.close();
		}
	},

	toggle_data_table : function(obj) {
		if (typeof obj == 'string') {
			obj = document.getElementById(obj + '_table');
		}

		if (obj) {
			obj.style.display = obj.style.display == 'none' ? '' : 'none';
		}
	}
};

window.onload = function() {
	ci_profiler_bar.set_load_state();
}
</script>

<a href="#" id="ci-profiler-menu-open" onclick="ci_profiler_bar.open(); return false;"><?php echo $profiler_logo; ?></a>

<div id="codeigniter-profiler">

	<div id="ci-profiler-menu">

    <!-- CI Logo -->
    <a><?php echo $profiler_logo; ?></a>

		<!-- Console -->
		<?php if (isset($sections['console'])) : ?>
		<a href="#" id="ci-profiler-menu-console" onclick="ci_profiler_bar.show('ci-profiler-console', 'ci-profiler-menu-console'); return false;">
			<?php echo lang('bf_profiler_menu_console'); ?>
			<span><?php echo is_array($sections['console']) ? $sections['console']['log_count'] + $sections['console']['memory_count'] : 0 ?></span>
		</a>
		<?php endif; ?>

		<!-- Benchmarks -->
		<?php if (isset($sections['benchmarks'])) :?>
		<a href="#" id="ci-profiler-menu-time" onclick="ci_profiler_bar.show('ci-profiler-benchmarks', 'ci-profiler-menu-time'); return false;">
			<?php echo lang('bf_profiler_menu_time'); ?>
			<?php if ($cip_time_format == 'ms') :?>
			<span><?php echo $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end') * 1000 ?>
				<?php echo lang('bf_profiler_menu_time_ms'); ?>
			</span>
			<?php else: ?>
			<span><?php echo $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end') ?>
				<?php echo lang('bf_profiler_menu_time_s'); ?>
			</span>
			<?php endif; ?>
		</a>
		<a href="#" id="ci-profiler-menu-memory" onclick="ci_profiler_bar.show('ci-profiler-memory', 'ci-profiler-menu-memory'); return false;">
			<?php echo lang('bf_profiler_menu_memory'); ?>
			<span><?php echo ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . ' ' . lang('bf_profiler_menu_memory_mb'); ?></span>
		</a>
		<?php endif; ?>

		<!-- Queries -->
		<?php if (isset($sections['queries'])) : ?>
		<a href="#" id="ci-profiler-menu-queries" onclick="ci_profiler_bar.show('ci-profiler-queries', 'ci-profiler-menu-queries'); return false;">
			<?php echo lang('bf_profiler_menu_queries_db'); ?>
			<span><?php echo is_array($sections['queries']) ? (count($sections['queries']) - 1) : 0 ?> <?php echo lang('bf_profiler_menu_queries'); ?></span>
		</a>
		<?php endif; ?>

		<!-- Vars and Config -->
		<?php if (isset($sections['http_headers']) || isset($sections['get']) || isset($sections['config']) || isset($sections['post']) || isset($sections['uri_string']) || isset($sections['controller_info'])) : ?>
		<a href="#" id="ci-profiler-menu-vars" onclick="ci_profiler_bar.show('ci-profiler-vars', 'ci-profiler-menu-vars'); return false;">
			<?php echo lang('bf_profiler_menu_vars'); ?>
		</a>
		<?php endif; ?>

		<!-- Files -->
		<?php if (isset($sections['files'])) : ?>
		<a href="#" id="ci-profiler-menu-files" onclick="ci_profiler_bar.show('ci-profiler-files', 'ci-profiler-menu-files'); return false;">
			<?php echo lang('bf_profiler_menu_files'); ?>
			<span><?php echo is_array($sections['files']) ? count($sections['files']) : 0; ?></span>
		</a>
		<?php endif; ?>

		<a href="#" id="ci-profiler-menu-exit" onclick="ci_profiler_bar.close(); return false;" style="width: 2em; height: 2.1em"></a>
	</div>


	<!-- Profiler Panel -->
	<div id="profiler-panel">
	<?php
	if (count($sections) > 0) :
		if (isset($sections['console'])) :
			$console_is_array = is_array($sections['console']);
	?>
		<!-- Console -->
		<div id="ci-profiler-console" class="ci-profiler-box" style="display:none">
			<h2><?php echo lang('bf_profiler_box_console'); ?></h2>
			<?php
			if ($console_is_array) :
			?>
			<table class="main">
				<?php
				foreach ($sections['console']['console'] as $log) :
					if ($log['type'] == 'log') :
				?>
				<tr>
					<td><?php echo $log['type'] ?></td>
					<td class="faded"><pre><?php echo $log['data'] ?></pre></td>
					<td></td>
				</tr>
				<?php
					elseif ($log['type'] == 'memory') :
				?>
				<tr>
					<td><?php echo $log['type'] ?></td>
					<td><em><?php echo $log['data_type'] ?></em>:
						<?php echo $log['name']; ?>
					</td>
					<td class="hilight" style="width: 9em"><?php echo $log['data'] ?></td>
				</tr>
				<?php
					endif;
				endforeach;
				?>
			</table>
			<?php
			else :
				echo $sections['console'];
			endif;
			?>
		</div>
		<!-- Memory -->
		<div id="ci-profiler-memory" class="ci-profiler-box" style="display:none">
			<h2><?php echo lang('bf_profiler_box_memory'); ?></h2>
			<?php
			if ($console_is_array) :
			?>
			<table class="main">
				<?php
				foreach ($sections['console']['console'] as $log) :
					if ($log['type'] == 'memory') :
				?>
				<tr>
					<td><?php echo $log['type'] ?></td>
					<td><em><?php echo $log['data_type'] ?></em>:
						<?php echo $log['name']; ?>
					</td>
					<td class="hilight" style="width: 9em"><?php echo $log['data'] ?></td>
				</tr>
				<?php
					endif;
				endforeach;
				?>
			</table>
			<?php
			else :
				echo $sections['console'];
			endif;
			?>
		</div>
	<?php
		endif; // isset($sections['console'])

		if (isset($sections['benchmarks'])) :
	?>
		<!-- Benchmarks -->
		<div id="ci-profiler-benchmarks" class="ci-profiler-box" style="display:none">
			<h2><?php echo lang('bf_profiler_box_benchmarks'); ?></h2>
			<?php
			if (is_array($sections['benchmarks'])) :
			?>
			<table class="main">
				<?php foreach ($sections['benchmarks'] as $key => $val) : ?>
				<tr>
					<td><?php echo $key; ?></td>
					<td class="hilight"><?php echo $val; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php
			else :
				echo $sections['benchmarks'];
			endif;
			?>
		</div>
	<?php
		endif;

		if (isset($sections['queries'])) :
	?>
		<!-- Queries -->
		<div id="ci-profiler-queries" class="ci-profiler-box" style="display:none">
			<h2><?php echo lang('bf_profiler_box_queries'); ?></h2>
			<?php
			if (is_array($sections['queries'])) :
			?>
			<table class="main">
				<?php
				foreach ($sections['queries'] as $key => $query) :
					if (isset($query['time'])) :
				?>
				<tr>
					<td class="hilight"><?php echo $query['time'] ?></td>
					<td><?php echo $query['query']; ?></td>
				</tr>
				<?php
					else :
						foreach ($query as $time => $val) :
				?>
				<tr>
					<td class="hilight"><?php echo $time; ?></td>
					<td><?php echo $val; ?></td>
				</tr>
				<?php
						endforeach;
					endif;
				endforeach;
				?>
			</table>
			<?php
			else :
				echo $sections['queries'];
			endif;
			?>
		</div>
	<?php
		endif;

		if (isset($sections['http_headers']) || isset($sections['get']) || isset($sections['config']) || isset($sections['post']) || isset($sections['uri_string']) || isset($sections['controller_info']) || isset($sections['userdata'])) :
	?>
		<!-- Vars and Config -->
		<div id="ci-profiler-vars" class="ci-profiler-box" style="display:none">
			<?php
			if (isset($sections['userdata'])) :
			?>
			<!-- User Data -->
			<a href="#" onclick="ci_profiler_bar.toggle_data_table('userdata'); return false;">
				<h2><?php echo lang('bf_profiler_box_session'); ?></h2>
			</a>
			<?php
				if (is_array($sections['userdata'])) :
			?>
			<table class="main" id="userdata_table">
				<?php foreach ($sections['userdata'] as $key => $val) : ?>
				<tr>
					<td class="hilight"><?php echo $key; ?></td>
					<td><?php e($val); ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php
				endif;
			endif; // isset($sections['userdata'])
			?>
			<!-- The Rest -->
			<?php
			foreach (array('get', 'post', 'uri_string', 'controller_info', 'headers', 'config') as $section) :

				if (isset($sections[$section])) :
					$append = ($section == 'get' || $section == 'post') ? '_data' : '';
			?>
			<a href="#" onclick="ci_profiler_bar.toggle_data_table('<?php echo $section; ?>'); return false;">
				<h2><?php echo lang('profiler_' . $section . $append); ?></h2>
			</a>
			<table class="main" id="<?php echo $section; ?>_table">
				<?php
					if (is_array($sections[$section])) :
						foreach ($sections[$section] as $key => $val) :
				?>
				<tr>
					<td class="hilight"><?php echo $key; ?></td>
					<td><?php e($val); ?></td>
				</tr>
				<?php
						endforeach;
					else :
				?>
				<tr>
					<td><?php echo $sections[$section]; ?></td>
				</tr>
				<?php
					endif;
				?>
			</table>
			<?php
				endif; // isset($sections[$section])
			endforeach;
			?>
		</div>
		<?php
		endif; // isset($sections['http_headers']) || isset($sections['get']) || isset($sections['config']) || isset($sections['post']) || isset($sections['uri_string']) || isset($sections['controller_info']) || isset($sections['userdata'])

		if (isset($sections['files'])) :
		?>
		<!-- Files -->
		<div id="ci-profiler-files" class="ci-profiler-box" style="display:none">
			<h2><?php echo lang('bf_profiler_box_files'); ?></h2>
			<?php
			if (is_array($sections['files'])) :
			?>
			<table class="main">
				<?php foreach ($sections['files'] as $key => $val) : ?>
				<tr>
					<td class="hilight"><?php echo preg_replace("/\/.*\//", "", $val); ?><br/>
						<span class="faded small"><?php echo str_replace(FCPATH, '', $val); ?></span>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php
			else :
				echo $sections['files'];
			endif;
			?>
		</div>
	<?php
		endif;
	else:
	?>
		<p class="ci-profiler-box"><?php echo lang('profiler_no_profiles'); ?></p>
	<?php
	endif;
	?>

	</div><!-- /profiler_panel -->
</div><!-- /codeigniter_profiler -->
