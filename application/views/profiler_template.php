<style type="text/css">
	#codeigniter-profiler { clear: both; background: #222; padding: 0 5px; font-family: Helvetica, sans-serif; font-size: 10px !important; line-height: 12px; position: absolute; width: auto; min-width: 55em; max-width: 90%; z-index: 1000; display: none; }
	#codeigniter-profiler:hover { background: #101010; opacity: 1.0; }
	
	#codeigniter-profiler.bottom-right { position: fixed; bottom:0; right: 0; -webkit-border-top-left-radius: 7px; -moz-border-radius-topleft: 7px; border-top-left-radius: 7px; -webkit-box-shadow: -1px -1px 10px #222; -moz-box-shadow: -1px -1px 10px #222; box-shadow: -1px -1px 10px #222; }	
	#codeigniter-profiler.bottom-left { position: fixed; bottom:0; left: 0; -webkit-border-top-right-radius: 7px; -moz-border-radius-topright: 7px; border-top-right-radius: 7px; -webkit-box-shadow: 1px -1px 10px #222; -moz-box-shadow: 1px -1px 10px #222; box-shadow: 1px -1px 10px #222; }
	#codeigniter-profiler.top-left { position: fixed; top:0; left: 0; -webkit-border-bottom-right-radius: 7px; -moz-border-radius-bottomright: 7px; border-bottom-right-radius: 7px;-webkit-box-shadow: 1px 1px 10px #222; -moz-box-shadow: 1px 1px 10px #222; box-shadow: 1px 1px 10px #222; }	
	#codeigniter-profiler.top-right { position: fixed; top: 0; right: 0; -webkit-border-bottom-left-radius: 7px; -moz-border-radius-bottomleft: 7px; border-bottom-left-radius: 7px; -webkit-box-shadow: -1px 1px 10px #222; -moz-box-shadow: -1px 1px 10px #222; box-shadow: -1px 1px 10px #222; }	
	
	.ci-profiler-box { padding: 10px; margin: 0 0 10px 0; max-height: 400px; overflow: auto; color: #fff; font-family: Monaco, 'Lucida Console', 'Courier New', monospace; font-size: 11px !important; }
	.ci-profiler-box h2 { font-family: Helvetica, sans-serif; font-weight: bold; font-size: 16px !important; padding: 0; line-height: 2.0; }
	
	#ci-profiler-vars a { text-decoration: none; }
	
	#ci-profiler-menu a:link, #ci-profiler-menu a:visited { display: inline-block; padding: 7px 0; margin: 0; color: #ccc; text-decoration: none; font-weight: lighter; cursor: pointer; text-align: center; width: 15.5%; border-bottom: 4px solid #444; }
	#ci-profiler-menu a:hover, #ci-profiler-menu a.current { background-color: #222; border-color: #999; }
	#ci-profiler-menu a span { display: block; font-weight: bold; font-size: 15px !important; line-height: 1.2; }
	
	#ci-profiler-menu-time span, #ci-profiler-benchmarks h2 { color: #B72F09; }
	#ci-profiler-menu-memory span, #ci-profiler-memory h2 { color: #953FA1; }
	#ci-profiler-menu-queries span, #ci-profiler-queries h2 { color: #3769A0; }
	#ci-profiler-menu-vars span, #ci-profiler-vars h2 { color: #D28C00; }
	#ci-profiler-menu-files span, #ci-profiler-files h2 { color: #5a8616; }
	#ci-profiler-menu-console span, #ci-profiler-console h2 { color: #5a8616; }
	
	#codeigniter-profiler table { width: 100%; border-spacing: 0; border-collapse: collapse; }
	#codeigniter-profiler table.main td { padding: 7px 15px; text-align: left; vertical-align: top; color: #aaa; border-bottom: 1px dotted #444; line-height: 1.5; background: #101010 !important; font-size: 12px !important; }
	#codeigniter-profiler table.main tr:hover td { background: #292929 !important; }
	#codeigniter-profiler table.main code { font-family: inherit; padding: 0; background: transparent; border: 0; color: #fff; }
	
	#codeigniter-profiler table .hilight { color: #FFFD70 !important; }
	#codeigniter-profiler table .faded { color: #aaa !important; }
	#codeigniter-profiler table .small { font-size: 10px; letter-spacing: 1px; font-weight: lighter; }
	
	#ci-profiler-queries b { color: white; }
	.ci-profiler-duplicate { background: #36363f; padding: 4px 0; }
	.ci-profiler-db-explain { display: block; color: #999; }
	.ci-profiler-db-explain em { font-style: normal; color: #fffd70; }

	
	#ci-profiler-menu-exit { background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIhSURBVDjLlZPrThNRFIWJicmJz6BWiYbIkYDEG0JbBiitDQgm0PuFXqSAtKXtpE2hNuoPTXwSnwtExd6w0pl2OtPlrphKLSXhx07OZM769qy19wwAGLhM1ddC184+d18QMzoq3lfsD3LZ7Y3XbE5DL6Atzuyilc5Ciyd7IHVfgNcDYTQ2tvDr5crn6uLSvX+Av2Lk36FFpSVENDe3OxDZu8apO5rROJDLo30+Nlvj5RnTlVNAKs1aCVFr7b4BPn6Cls21AWgEQlz2+Dl1h7IdA+i97A/geP65WhbmrnZZ0GIJpr6OqZqYAd5/gJpKox4Mg7pD2YoC2b0/54rJQuJZdm6Izcgma4TW1WZ0h+y8BfbyJMwBmSxkjw+VObNanp5h/adwGhaTXF4NWbLj9gEONyCmUZmd10pGgf1/vwcgOT3tUQE0DdicwIod2EmSbwsKE1P8QoDkcHPJ5YESjgBJkYQpIEZ2KEB51Y6y3ojvY+P8XEDN7uKS0w0ltA7QGCWHCxSWWpwyaCeLy0BkA7UXyyg8fIzDoWHeBaDN4tQdSvAVdU1Aok+nsNTipIEVnkywo/FHatVkBoIhnFisOBoZxcGtQd4B0GYJNZsDSiAEadUBCkstPtN3Avs2Msa+Dt9XfxoFSNYF/Bh9gP0bOqHLAm2WUF1YQskwrVFYPWkf3h1iXwbvqGfFPSGW9Eah8HSS9fuZDnS32f71m8KFY7xs/QZyu6TH2+2+FAAAAABJRU5ErkJggg==) 0% 0% no-repeat; padding-left: 20px; position: absolute; right: 5px; top: 10px; display:none; }
	#ci-profiler-menu-open { background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAc9JREFUeNp8kr9O21AUxr9j06iqhGSBiqKypAMrIjtLBuaWNzBPEPIEJEvHInZEeAMyVJU6VMnUsY3asamULk1L/mAHSGzg3sO5l7hyAsmRvjj2Pb/vu+faYGbMq5sfL+oi76k1w1l2noEBRSyqLjJwML8O7K8mf0EPyLgQ0Wy6L2AVty4QPUNmu09P7cDU0qOtf132EQksIEeu1aKaGuHmy4qP60yVg+fQQQbKKFxqmLXw/Wtv4Qjx55c+lFPlyIGWVB07kk7g2GmrIRWgUNdjqq2++1VKj2AN4g/rOdb4Js2eFgM2cEyBjuBZEyvYqx7hdO2ktTd1BurKLfIteTY9ngB32OVrOhNQTOV+LAYjK7+zs/FbsPL/M1BD960KXZlXDAJJCUU92tJXyKuAGrovb7Mn6srzf2LWRXHqEHXo5JQBJ1IXVoeqQ1g7bhV4gIr+a0FgZAB4UwZKEjkBQ6oliXz50Jj91CpjjAp4zmvUFxSogaQP0JbEXR4iz5eUz35sNZPGV99/llNcLfljD1HSauZweExtm5gCk/qzuZFL3R7N7AAlfU5N7mFrpjFdh5Prnuym8ehDEtDMuy96M2lqptINbNYr8ryd/pDuBRgABwcgCJ3Gp98AAAAASUVORK5CYII%3D) 0% 0% no-repeat; z-index: 10000; }
	
	#ci-profiler-menu-open.bottom-right { position: fixed; right: -2px; bottom: 22px; }
	#ci-profiler-menu-open.bottom-left { position: fixed; left: 10px; bottom: 22px; }
	#ci-profiler-menu-open.top-left { position: fixed; left: 10px; top: 22px; }
	#ci-profiler-menu-open.top-right { position: fixed; right: -2px; top: 22px; }
</style>

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

<a href="#" id="ci-profiler-menu-open" class="bottom-left" onclick="ci_profiler_bar.open(); return false;" style="width: 2em">&nbsp;</a>

<div id="codeigniter-profiler" class="bottom-left">
	
	<div id="ci-profiler-menu">
		
		<!-- Console -->
		<?php if (isset($sections['console'])) : ?>
			<a href="#" id="ci-profiler-menu-console" onclick="ci_profiler_bar.show('ci-profiler-console', 'ci-profiler-menu-console'); return false;">
				<span><?php echo is_array($sections['console']) ? $sections['console']['log_count'] + $sections['console']['memory_count'] : 0 ?></span>
				Console
			</a>
		<?php endif; ?>
		
		<!-- Benchmarks -->
		<?php if (isset($sections['benchmarks'])) :?>
			<a href="#" id="ci-profiler-menu-time" onclick="ci_profiler_bar.show('ci-profiler-benchmarks', 'ci-profiler-menu-time'); return false;">
				<?php if ($cip_time_format == 'ms') :?>
					<span><?php echo $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end') * 1000 ?> ms</span>
					Load Time
				<?php else: ?>
					<span><?php echo $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end') ?> s</span>
					Load Time
				<?php endif; ?>
			</a>
			<a href="#" id="ci-profiler-menu-memory" onclick="ci_profiler_bar.show('ci-profiler-memory', 'ci-profiler-menu-memory'); return false;">
				<span><?php echo (! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).' MB' ?></span>
				Memory Used
			</a>
		<?php endif; ?>
		
		<!-- Queries -->
		<?php if (isset($sections['queries'])) : ?>
			<a href="#" id="ci-profiler-menu-queries" onclick="ci_profiler_bar.show('ci-profiler-queries', 'ci-profiler-menu-queries'); return false;">
				<span><?php echo is_array($sections['queries']) ? (count($sections['queries']) - 1) : 0 ?> Queries</span>
				Database
			</a>
		<?php endif; ?>
		
		<!-- Vars and Config -->
		<?php if (isset($sections['http_headers']) || isset($sections['get']) || isset($sections['config']) || isset($sections['post']) || isset($sections['uri_string']) || isset($sections['controller_info'])) : ?>
			<a href="#" id="ci-profiler-menu-vars" onclick="ci_profiler_bar.show('ci-profiler-vars', 'ci-profiler-menu-vars'); return false;">
				<span>vars</span> &amp; Config
			</a>
		<?php endif; ?>
		
		<!-- Files -->
		<?php if (isset($sections['files'])) : ?>
			<a href="#" id="ci-profiler-menu-files" onclick="ci_profiler_bar.show('ci-profiler-files', 'ci-profiler-menu-files'); return false;">
				<span><?php echo is_array($sections['files']) ? count($sections['files']) : 0 ?></span> Files
			</a>
		<?php endif; ?>
		
		<a href="#" id="ci-profiler-menu-exit" onclick="ci_profiler_bar.close(); return false;" style="width: 2em; height: 2.1em"></a>
	</div>

<?php if (count($sections) > 0) : ?>

	<!-- Console -->
	<?php if (isset($sections['console'])) :?>
		<div id="ci-profiler-console" class="ci-profiler-box" style="display: none">
			<h2>Console</h2>
			
			<?php if (is_array($sections['console'])) : ?>
				
				<table class="main">
				<?php foreach ($sections['console']['console'] as $log) : ?>
					
					<?php if ($log['type'] == 'log') : ?>
						<tr>
							<td><?php echo $log['type'] ?></td>
							<td class="faded"><pre><?php echo $log['data'] ?></pre></td>
							<td></td>
						</tr>
					<?php elseif ($log['type'] == 'memory')  :?>
						<tr>
							<td><?php echo $log['type'] ?></td>
							<td>
								<em><?php echo $log['data_type'] ?></em>: 
								<?php echo $log['name']; ?>
							</td>
							<td class="hilight" style="width: 9em"><?php echo $log['data'] ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				</table>

			<?php else : ?>

				<?php echo $sections['console']; ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<!-- Memory -->
	<?php if (isset($sections['console'])) :?>
		<div id="ci-profiler-memory" class="ci-profiler-box" style="display: none">
			<h2>Memory Usage</h2>
			
			<?php if (is_array($sections['console'])) : ?>
				
				<table class="main">
				<?php foreach ($sections['console']['console'] as $log) : ?>
				
					<?php if ($log['type'] == 'memory')  :?>
						<tr>
							<td><?php echo $log['type'] ?></td>
							<td>
								<em><?php echo $log['data_type'] ?></em>: 
								<?php echo $log['name']; ?>
							</td>
							<td class="hilight" style="width: 9em"><?php echo $log['data'] ?></td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>
				</table>

			<?php else : ?>

				<?php echo $sections['console']; ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- Benchmarks -->
	<?php if (isset($sections['benchmarks'])) :?>
		<div id="ci-profiler-benchmarks" class="ci-profiler-box" style="display: none">
			<h2>Benchmarks</h2>
			
			<?php if (is_array($sections['benchmarks'])) : ?>
				
				<table class="main">
				<?php foreach ($sections['benchmarks'] as $key => $val) : ?>
					<tr><td><?php echo $key ?></td><td class="hilight"><?php echo $val ?></td></tr>
				<?php endforeach; ?>
				</table>

			<?php else : ?>

				<?php echo $sections['benchmarks']; ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<!-- Queries -->
	<?php if (isset($sections['queries'])) :?>
		<div id="ci-profiler-queries" class="ci-profiler-box" style="display: none">
			<h2>Queries</h2>
			
			<?php if (is_array($sections['queries'])) : ?>
				
				<table class="main">
				<?php foreach ($sections['queries'] as $key => $query) : ?>
					<?php if (isset($query['time'])) :?>
						<tr><td class="hilight"><?php echo $query['time'] ?></td><td><?php echo $query['query'] ?></td></tr> 
					<?php else: ?>
						<?php foreach ($query as $time => $val) :?>
						<tr><td class="hilight"><?php echo $time ?></td><td><?php echo $val ?></td></tr> 
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				</table>

			<?php else : ?>

				<?php echo $sections['queries']; ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<!-- Vars and Config -->
	<?php if (isset($sections['http_headers']) || isset($sections['get']) || isset($sections['config']) || isset($sections['post']) || isset($sections['uri_string']) || isset($sections['controller_info']) || isset($sections['userdata'])) :?>
		<div id="ci-profiler-vars" class="ci-profiler-box" style="display: none">
			
			<!-- User Data -->
			<?php if (isset($sections['userdata'])) :?>
					<a href="#" onclick="ci_profiler_bar.toggle_data_table('userdata'); return false;">
						<h2>Session User Data</h2>
					</a>
					
					<?php if (is_array($sections['userdata'])) : ?>
						
						<table class="main" id="userdata_table">
						<?php foreach ($sections['userdata'] as $key => $val) : ?>
							<tr><td class="hilight"><?php echo $key ?></td><td><?php echo htmlspecialchars($val) ?></td></tr>
						<?php endforeach; ?>
						</table>
		
					<?php endif; ?>
				<?php endif; ?>
			
			<!-- The Rest -->
			<?php foreach (array('get', 'post', 'uri_string', 'controller_info', 'headers', 'config') as $section) : ?>
				
				<?php if (isset($sections[$section])) :?>
					
					<?php $append = ($section == 'get' || $section == 'post') ? '_data' : '' ?>
					<a href="#" onclick="ci_profiler_bar.toggle_data_table('<?php echo $section ?>'); return false;">
						<h2><?php echo lang('profiler_' . $section . $append) ?></h2>
					</a>
					
					
					
						<table class="main" id="<?php echo $section ?>_table">
						<?php if (is_array($sections[$section])) : ?>
						<?php foreach ($sections[$section] as $key => $val) : ?>
							<tr><td class="hilight"><?php echo $key ?></td><td><?php echo htmlspecialchars($val) ?></td></tr>
						<?php endforeach; ?>
						<?php else : ?>
							<tr><td><?php echo $sections[$section]; ?></td></tr>
						<?php endif; ?>
						</table>
				<?php endif; ?>
				
			<?php endforeach; ?>
		</div>		
	<?php endif; ?>
	
	<!-- Files -->
	<?php if (isset($sections['files'])) :?>
		<div id="ci-profiler-files" class="ci-profiler-box" style="display: none">
			<h2>Loaded Files</h2>
			
			<?php if (is_array($sections['files'])) : ?>
				
				<table class="main">
				<?php foreach ($sections['files'] as $key => $val) : ?>
					<tr>
						<td class="hilight">
							<?php echo preg_replace("/\/.*\//", "", $val) ?>
							<br/><span class="faded small"><?php echo str_replace(FCPATH, '', $val) ?></span>
						</td>
					</tr>
				<?php endforeach; ?>
				</table>

			<?php else : ?>

				<?php echo $sections['files']; ?>

			<?php endif; ?>
		</div>
	<?php endif; ?>

	
<?php else: ?>

	<p class="ci-profiler-box"><?php echo lang('profiler_no_profiles') ?></p>

<?php endif; ?>

</div>	<!-- /codeigniter_profiler -->
