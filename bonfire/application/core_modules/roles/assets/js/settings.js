$.subscribe('list-view/list-item/click', function(role_id) {
	$('#content').load('<?php echo site_url(SITE_AREA .'/settings/roles/edit') ?>/'+ role_id);
});

$('#permission_table').tableHover({colClass: 'hover', ignoreCols: [1]}); 
	
$('input:checkbox').change(function() {
	$('#permission_table_result').removeClass();
	$('#permission_table_result').addClass('notification');
	$('#permission_table_result').addClass('information');
	var val = $(this).attr('value');
	var what = $(this).is(':checked');
	var r_name = $(this).closest('td').attr('title');
	var p_name = $(this).closest('tr').attr('title');
	$.post("<?php echo site_url(SITE_AREA .'/settings/roles/matrix_update') ?>/",
			{ "role_perm": val, "action": what }, function(data) {
				var newtext = data.replace(/<?php echo lang('matrix_permission');?>/i, '"'+p_name+'"');
				newtext = newtext.replace(/<?php echo lang('matrix_role');?>/i, '"'+r_name+'" <?php echo strtolower(lang('matrix_role'));?>');
				if (newtext.search(':') >= 1) {
					$('#permission_table_result').removeClass('information');
					$('#permission_table_result').addClass('attention');
				} else {
					$('#permission_table_result').addClass('success');
				}
				$('#permission_table_result').text(newtext);
			});
});
