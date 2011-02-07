$.subscribe('list-view/list-item/click', function(server_type) {
	var url = '<?php echo site_url('admin/settings/database/edit/') ?>/'+ server_type;
	
	$('#content').load(url);
});