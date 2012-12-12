<?php if (validation_errors()) : ?>
<div class="alert alert-error notification error fade in">
		<a class="close" data-dismiss="alert">&times;</a>
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>


<?php echo form_open($this->uri->uri_string()); ?>

	<!-- Forgot Password -->


<?php echo form_close(); ?>
