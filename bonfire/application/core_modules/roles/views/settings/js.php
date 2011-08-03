$.subscribe('list-view/list-item/click', function(role_id) {
	$('#content').load('<?php echo site_url('admin/settings/roles/edit') ?>/'+ role_id);
});