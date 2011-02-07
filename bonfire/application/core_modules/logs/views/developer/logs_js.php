$.subscribe('list-view/list-item/click', function(log_file) {
	$('#content').load('<?php echo site_url('admin/developer/logs/view/') ?>/'+ log_file);
});
