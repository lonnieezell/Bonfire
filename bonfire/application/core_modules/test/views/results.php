<?php $this->load->helper('language'); ?>

<?php $i=0; foreach ($results as $result): ?>
	<li>
	<?php if ($result[lang('ut_result')] == lang('ut_passed')): ?>
		<div class="pas">

			[<?php echo strtoupper(lang('ut_passed')); ?>] <?php echo $result[lang('ut_test_name')]; ?>

			<?php if ( ! empty($messages[$i])): ?>
			<div class="detail">
				<?php echo $messages[$i]; ?>&nbsp;
			</div>
			<?php endif; ?>

		</div>
	<?php else: ?>
		<div class="err">

			[<?php echo strtoupper(lang('ut_failed')); ?>] <?php echo $result[lang('ut_test_name')]; ?>

			<?php if ( ! empty($messages[$i])): ?>
			<div class="detail">
				<?php echo $messages[$i]; ?>&nbsp;
			</div>
			<?php endif; ?>

		</div>
	<?php endif; ?>
	</li>
<?php $i++; endforeach; ?>
