<?php echo $this->load->view('content/sub_nav', null, true); ?>

<?php if (isset($pages) && is_array($pages) && count($pages)) :?>

<?php else : ?>
<div class="notification information">
	<p>No pages found.</p>
</div>
<?php endif; ?>