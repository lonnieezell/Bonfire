<script type="text/javascript">
// Replace any existing profile data
head.ready(function(){
	var ci_profiler = $('#codeigniter-profiler');
	var html = ci_profiler.clone();
	var show = function(a,b){
		return function(){
			ci_profiler_bar.show(a,b);
			return false;
		};
	};
	ci_profiler.remove();
	$('#debug').hide().empty().append(html).fadeIn('fast');

	// Attach click handlers to the profiler
	$('#ci-profiler-menu-console').click(show('ci-profiler-console','ci-profiler-menu-console'));
	$('#ci-profiler-menu-time').click(show('ci-profiler-benchmarks','ci-profiler-menu-time'));
	$('#ci-profiler-menu-memory').click(show('ci-profiler-memory','ci-profiler-menu-memory'));
	$('#ci-profiler-menu-queries').click(show('ci-profiler-queries','ci-profiler-menu-queries'));
	$('#ci-profiler-menu-vars').click(show('ci-profiler-vars','ci-profiler-menu-vars'));
	$('#ci-profiler-menu-files').click(show('ci-profiler-files','ci-profiler-menu-files'));
	$('#ci-profiler-menu-exit').click(function(){ ci_profiler_bar.close(); 	});
});
</script>

<div id="codeigniter-profiler">
	
	<div id="ci-profiler-menu">
		
		<!-- Console -->
		<?php if (isset($sections['console'])) : ?>
			<a href="#" id="ci-profiler-menu-console">
				<span><?php echo is_array($sections['console']) ? $sections['console']['log_count'] + $sections['console']['memory_count'] : 0 ?></span>
				Console
			</a>
		<?php endif; ?>
		
		<!-- Benchmarks -->
		<?php if (isset($sections['benchmarks'])) :?>
			<a href="#" id="ci-profiler-menu-time">
				<span><?php echo $this->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end') ?> s</span>
				Load Time
			</a>
			<a href="#" id="ci-profiler-menu-memory">
				<span><?php echo (! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).' MB' ?></span>
				Memory Used
			</a>
		<?php endif; ?>
		
		<!-- Queries -->
		<?php if (isset($sections['queries'])) : ?>
			<a href="#" id="ci-profiler-menu-queries">
				<span><?php echo is_array($sections['queries']) ? count($sections['queries']) : 0 ?> Queries</span>
				Database
			</a>
		<?php endif; ?>
		
		<!-- Vars and Config -->
		<?php if (isset($sections['http_headers']) || isset($sections['get']) || isset($sections['config']) || isset($sections['post']) || isset($sections['uri_string']) || isset($sections['controller_info'])) : ?>
			<a href="#" id="ci-profiler-menu-vars">
				<span>vars</span> &amp; Config
			</a>
		<?php endif; ?>
		
		<!-- Files -->
		<?php if (isset($sections['files'])) : ?>
			<a href="#" id="ci-profiler-menu-files">
				<span><?php echo is_array($sections['files']) ? count($sections['files']) : 0 ?></span> Files
			</a>
		<?php endif; ?>
		
		<a href="#" id="ci-profiler-menu-exit" style="width: 2em"></a>
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
					<tr><td class="hilight"><?php echo $key ?></td><td><?php echo $val ?></td></tr>
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
