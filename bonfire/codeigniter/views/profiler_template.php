<style type="text/css">
	#codeigniter-profiler { clear: both; background: #fff; padding: 10px; }
	#codeigniter-profiler legend { padding: 0 0.5em; color: #000; }
	.ci-profiler-box { border: 1px solid #000; padding: 6px 10px 10px 10px; margin: 20px 0; background-color: #eee; }
	
	#ci-profiler-benchmarks, #ci-profiler-benchmarks legend { border-color: #900; color: #900; }
	#ci-profiler-get, #ci-profiler-get legend { border-color: #cd6e00; color: #cd6e00; }
	#ci-profiler-memory_usage, #ci-profiler-memory_usage legend { border-color: #5a0099; color: #5a0099; }
	#ci-profiler-post, #ci-profiler-post legend { border-color: #009900; color: #009900; }
	#ci-profiler-uri_string, #ci-profiler-uri_string legend { border-color: #000; color: #000; }
	#ci-profiler-controller_info, #ci-profiler-controller_info legend { border-color: #995300; color: #995300; }
	#ci-profiler-http_headers, #ci-profiler-http_headers legend { border-color: #000; color: #000; }
	#ci-profiler-queries, #ci-profiler-queries legend { border-color: #0000ff; color: #0000ff; }
	#ci-profiler-config, #ci-profiler-config legend { border-color: #000; color: #000; }
	
	#codeigniter-profiler table { width: 100%; }
	#codeigniter-profiler tr td { padding: 5px; vertical-align: top; color: #900; background-color: #ddd; }
	#codeigniter-profiler tr td:last-child { color: #000; width: 60%; }
	#ci-profiler-queries tr td:first-child { width: 1%; }
</style>

<div id="codeigniter-profiler">

<?php if (count($sections) > 0) : ?>

	<?php foreach ($sections as $section => $section_body) : ?>
		<fieldset id="ci-profiler-<?php echo $section ?>" class="ci-profiler-box">
			<legend><?php echo lang('profiler_'. $section) ?></legend>
			
			<?php if (is_array($section_body)) : ?>
				
				<table>
				<?php foreach ($section_body as $key => $val) : ?>
					<tr><td><?php echo $key ?></td><td><?php echo $val ?></td></tr>
				<?php endforeach; ?>
				</table>

			<?php else : ?>

				<?php echo $section_body; ?>

			<?php endif; ?>
		</fieldset>
	<?php endforeach; ?>
	
<?php else: ?>

	<p class="ci-profiler-box"><?php echo lang('profiler_no_profiles') ?></p>

<?php endif; ?>

</div>	<!-- /codeigniter_profiler -->

<pre>
<?php //print_r($sections); ?>
</pre>