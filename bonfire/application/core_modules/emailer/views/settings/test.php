<fieldset>
	<legend><?php echo lang('em_test_result_header'); ?></legend>
</fieldset>


<?php if (isset($results)) : ?>

	<?php if ($results['success'] !== false) :?>
		<div class="alert alert-info">
			<?php echo lang('em_test_success'); ?>
		</div>
	<?php else : ?>
		<div class="alert alert-warning">
			<?php echo lang('em_test_error'); ?>
		</div>
	<?php endif; ?>

	<fieldset>
		<legend><?php echo lang('em_test_debug_header'); ?></legend>

		<div style="padding: 10px"><?php echo $results['debug']; ?></div>
	</fieldset>

<?php else : ?>

	<div class="alert alert-error">
		<?php echo lang('em_test_no_results'); ?>
	</div>

<?php endif; ?>
