<p><b><?php echo lang('em_test_result_header'); ?></b></p>

<?php if (isset($results)) : ?>

	<?php if ($results['success'] !== false) :?>
		<div class="notification information">
			<p><?php echo lang('em_test_success'); ?></p>
		</div>
	<?php else : ?>
		<div class="notification attention">
			<p><?php echo lang('em_test_error'); ?></p>
		</div>
	<?php endif; ?>

	<div class="box">
		<p><b><?php echo lang('em_test_debug_header'); ?></b></p>
	
		<?php echo $results['debug']; ?>
	</div>

<?php else : ?>

	<div class="notification attention">
		<p><?php echo lang('em_test_no_results'); ?></p>
	</div>

<?php endif; ?>