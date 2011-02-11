$.subscribe('list-view/list-item/click', function(page_id) {
	$('#content').load('<?php echo site_url('admin/content/pages/view/') ?>/'+ page_id);
	
	
});

/*
	Page Filter
*/
$('#page-filter').change(function(){
	
	var status = $(this).val();
	
	$('#page-list .list-item').css('display', 'block');
	
	if (status != '0')
	{
		$('#page-list .list-item[data-status!="'+ status +'"]').css('display', 'none');
	} 
});
