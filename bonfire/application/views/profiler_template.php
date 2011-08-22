<style type="text/css">
	#debug { clear: both; position: fixed;  bottom: 0; left: 0; right: 0; z-index: 1000; opacity: 0.85; }
	#codeigniter-profiler { position: relative; clear: both; background: #101010; padding: 0 5px; font-family: Helvetica, sans-serif; font-size: 10px !important; line-height: 12px; }
	#debug:hover { opacity: 1.0; }	
	
	.ci-profiler-box { padding: 10px; margin: 0 0 10px 0; max-height: 400px; overflow: auto; color: #fff; font-family: Monaco, 'Lucida Console', 'Courier New', monospace; font-size: 11px !important; }
	.ci-profiler-box h2 { font-family: Helvetica, sans-serif; font-weight: normal; }
	
	#ci-profiler-menu a:link, #ci-profiler-menu a:visited { display: inline-block; padding: 7px 0; margin: 0; color: #ccc; text-decoration: none; font-weight: lighter; cursor: pointer; text-align: center; width: 15.5%; border-bottom: 4px solid #444; }
	#ci-profiler-menu a:hover, #ci-profiler-menu a.current { background-color: #222; border-color: #999; }
	#ci-profiler-menu a span { display: block; font-weight: bold; font-size: 16px !important; line-height: 1.2; }
	
	#ci-profiler-menu-time span, #ci-profiler-benchmarks h2 { color: #B72F09; }
	#ci-profiler-menu-memory span, #ci-profiler-memory h2 { color: #953FA1; }
	#ci-profiler-menu-queries span, #ci-profiler-queries h2 { color: #3769A0; }
	#ci-profiler-menu-vars span, #ci-profiler-vars h2 { color: #D28C00; }
	#ci-profiler-menu-files span, #ci-profiler-files h2 { color: #5a8616; }
	#ci-profiler-menu-console span, #ci-profiler-console h2 { color: #5a8616; }
	
	#codeigniter-profiler table { width: 100%; }
	#codeigniter-profiler table.main td { padding: 7px 15px; text-align: left; vertical-align: top; color: #fff; border-bottom: 1px dotted #444; line-height: 1.5; background: #101010 !important; }
	#codeigniter-profiler table.main tr:hover td { background: #292929 !important; }
	#codeigniter-profiler table.main code { font-family: inherit; padding: 0; background: transparent; border: 0; color: #fff; }
	
	#codeigniter-profiler table td .hilight, #codeigniter-profiler .hilight { color: #FFFD70 !important; }
	#codeigniter-profiler table td .faded { color: #aaa !important; }
	
	.ci-profiler-duplicate { background: #36363f; padding: 4px 0; }
	.ci-profiler-db-explain { display: block; color: #999; }
	.ci-profiler-db-explain em { font-style: normal; color: #fffd70; }
	
	#ci-profiler-menu-exit { background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIhSURBVDjLlZPrThNRFIWJicmJz6BWiYbIkYDEG0JbBiitDQgm0PuFXqSAtKXtpE2hNuoPTXwSnwtExd6w0pl2OtPlrphKLSXhx07OZM769qy19wwAGLhM1ddC184+d18QMzoq3lfsD3LZ7Y3XbE5DL6Atzuyilc5Ciyd7IHVfgNcDYTQ2tvDr5crn6uLSvX+Av2Lk36FFpSVENDe3OxDZu8apO5rROJDLo30+Nlvj5RnTlVNAKs1aCVFr7b4BPn6Cls21AWgEQlz2+Dl1h7IdA+i97A/geP65WhbmrnZZ0GIJpr6OqZqYAd5/gJpKox4Mg7pD2YoC2b0/54rJQuJZdm6Izcgma4TW1WZ0h+y8BfbyJMwBmSxkjw+VObNanp5h/adwGhaTXF4NWbLj9gEONyCmUZmd10pGgf1/vwcgOT3tUQE0DdicwIod2EmSbwsKE1P8QoDkcHPJ5YESjgBJkYQpIEZ2KEB51Y6y3ojvY+P8XEDN7uKS0w0ltA7QGCWHCxSWWpwyaCeLy0BkA7UXyyg8fIzDoWHeBaDN4tQdSvAVdU1Aok+nsNTipIEVnkywo/FHatVkBoIhnFisOBoZxcGtQd4B0GYJNZsDSiAEadUBCkstPtN3Avs2Msa+Dt9XfxoFSNYF/Bh9gP0bOqHLAm2WUF1YQskwrVFYPWkf3h1iXwbvqGfFPSGW9Eah8HSS9fuZDnS32f71m8KFY7xs/QZyu6TH2+2+FAAAAABJRU5ErkJggg==) 0% 0% no-repeat; padding-left: 20px; position: absolute; right: 5px; top: 10px; }
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
	
	// close the toolbar
	close : function() {
		document.getElementById('codeigniter-profiler').style.display = 'none';
	},
	
	// Add class to element
	add_class : function(obj, my_class) {
		alert(obj);
		document.getElementById(obj).className += " "+ my_class;
	},
	
	// Remove class from element
	remove_class : function(obj, my_class) {
		if (obj != undefined) {
			document.getElementById(obj).className = document.getElementById(obj).className.replace(/\bmy_class\b/, '');
		}
	}
};

// Replace any existing profile data
if (this['head']) { // IE 7 check
	head.ready(function(){
		var html = $('#codeigniter-profiler').clone();
		$('#codeigniter-profiler').remove();
		$('#debug').hide().empty().append(html).fadeIn('fast');
	});
}
</script>

<div id="codeigniter-profiler">
	
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
				<span><?php echo $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end') ?> s</span>
				Load Time
			</a>
			<a href="#" id="ci-profiler-menu-memory" onclick="ci_profiler_bar.show('ci-profiler-memory', 'ci-profiler-menu-memory'); return false;">
				<span><?php echo (! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).' MB' ?></span>
				Memory Used
			</a>
		<?php endif; ?>
		
		<!-- Queries -->
		<?php if (isset($sections['queries'])) : ?>
			<a href="#" id="ci-profiler-menu-queries" onclick="ci_profiler_bar.show('ci-profiler-queries', 'ci-profiler-menu-queries'); return false;">
				<span><?php echo is_array($sections['queries']) ? count($sections['queries']) : 0 ?> Queries</span>
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
		
		<a href="#" id="ci-profiler-menu-exit" onclick="ci_profiler_bar.close(); return false;" style="width: 2em"></a>
	</div>

<?php if (count($sections) > 0) : ?>

	<!-- Console -->
	<?php if (isset($sections['console'])) :?>
		<div id="ci-profiler-console" class="ci-profiler-box" style="display: none">
			<h2>Console</h2>
			
			<?php if (is_array($sections['console']) && is_array($sections['console']['console']) && count($sections['console']['console'])) : ?>
				
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
				<p>No Console messages.</p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<!-- Memory -->
	<?php if (isset($sections['console'])) :?>
		<div id="ci-profiler-memory" class="ci-profiler-box" style="display: none">
			<h2>Memory Usage</h2>
			
			<?php if (is_array($sections['console']) && is_array($sections['console']['console']) && count($sections['console']['console'])) : ?>
				
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
				<p>No Memory logs.</p>
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
				
				<?php if ($sections['queries']['duplicates']) : ?>
					<p class="hilight"><?php echo $sections['queries']['duplicates'] ?> DUPLICATE QUERIES.</p>
					<?php unset($sections['queries']['duplicates']); ?>
				<?php endif; ?>
				
				<table class="main" cellspacing="0">
				<?php foreach ($sections['queries'] as $key => $val) : ?>
					<tr><td><?php echo $val ?></td></tr>
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
					
					<h2>Session User Data</h2>
					
					<?php if (is_array($sections['userdata'])) : ?>
						
						<table class="main">
						<?php foreach ($sections['userdata'] as $key => $val) : ?>
							<tr><td class="hilight"><?php echo $key ?></td><td><?php echo htmlspecialchars($val) ?></td></tr>
						<?php endforeach; ?>
						</table>
		
					<?php endif; ?>
				<?php endif; ?>
			
			<!-- The Rest -->
			<?php foreach (array('get', 'post', 'uri_string', 'controller_info', 'http_headers', 'config') as $section) : ?>
				
				<?php if (isset($sections[$section])) :?>
					
					<h2><?php echo lang('profiler_'. $section) ?></h2>
					
					<?php if (is_array($sections[$section])) : ?>
						
						<table class="main">
						<?php foreach ($sections[$section] as $key => $val) : ?>
							<tr><td class="hilight"><?php echo $key ?></td><td><?php echo htmlspecialchars($val) ?></td></tr>
						<?php endforeach; ?>
						</table>
		
					<?php else : ?>
		
						<?php echo $sections[$section]; ?>
		
					<?php endif; ?>
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
							<br/><span class="small"><?php echo str_replace(FCPATH, '', $val) ?></span>
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