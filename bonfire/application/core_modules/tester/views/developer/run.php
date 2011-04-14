<style>
	span.success,
	span.error {
		padding: 0.25em 0.5em;
		color: #fff;
	}
	span.success { background: green; }
	span.error { background: red; }
	h4.notification {
		font-size: 14px;
		text-transform: none;
		margin-top: 1em;
		padding: 0 1em;
	}
	div.result {
		padding: 7px 18px;
		border-bottom: 1px solid #ddd;
	}
	div.result p {
		padding-left: 0;
		margin-bottom: 0;
	}
</style>

<br/>
<h3 style="font-weight: normal">Accumulative Test Results - 
	Passed <span class="success"><?php echo $total_passed ?></span> 
	Failed: <span class="error"><?php echo $total_failed ?></span>
</h3>

<?php if (isset($results) && is_array($results) && count($results)) : ?>

	<?php foreach ($results as $module => $result) : ?>
	
	<div class="module-results">
		<div class="notification <?php echo $result['failed'] > 0 ? 'error' : 'success' ?>">
			<?php echo $module ?> - 
			Passed: <span class="success"><?php echo $result['passed'] ?></span>
			Failed: <span class="error"><?php echo $result['failed'] ?></span>
			
			<span style="float: right">
				<a href="#" class="result-detail-toggle">Show/Hide Details</a>
			</span>
		</div>
		
		<div class="result-list" style="display: <?php echo $result['failed'] > 0 ? 'block' : 'none' ?>">
			<?php foreach ($result['raw'] as $test) :?>
				<?php if ($test['Result'] == 'Passed') : ?>
				
					<div class="result">
						[Passed] <b><?php echo $test['Test Name'] ?></b>
						<?php if (!empty($test['Notes'])) :?>
							<p class="small"><?php echo $test['Notes'] ?></p>
						<?php endif; ?>
					</div>
				
				<?php else : ?>
				
					<div class="result">
						[<span style="color: red">Failed</span>] <b><?php echo $test['Test Name'] ?></b>
						<?php if (!empty($test['Notes'])) :?>
							<p><?php echo $test['Notes'] ?></p>
						<?php endif; ?>
					</div>
				
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endforeach; ?>

<?php endif; ?>

<script>
head.ready(function(){
	$('a.result-detail-toggle').click(function(e){
		e.preventDefault();
		
		$(this).parents('.module-results').children('.result-list').slideToggle();
	});
});
</script>