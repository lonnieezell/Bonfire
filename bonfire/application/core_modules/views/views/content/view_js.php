$.subscribe('list-view/list-item/click', function(id_str) {
	var parts = id_str.split(':');
	
	var url = '<?php echo site_url('admin/content/views/edit/') ?>/'+ parts[0] +'/'+ parts[1];
	
	$('#content').load(url);
});