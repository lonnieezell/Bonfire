<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>


<?php echo form_open($this->uri->uri_string()); ?>

	<!-- Forgot Password -->
	

<?php echo form_close(); ?>