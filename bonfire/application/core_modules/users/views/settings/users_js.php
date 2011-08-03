/*
	Users.js
	
	Most of the functions in this file respond to actions published 
	through the UI.js functions.
*/
$.subscribe('list-view/list-item/click', function(user_id) {
	$('#content').load('<?php echo site_url(SITE_AREA .'/settings/users/edit/') ?>/'+ user_id, function(response, status, xhr){
		if (status != 'error')
		{
			
		}
	});
	
	
});

/*
	Role Filter
*/
$('#role-filter').change(function(){
	
	var role = $(this).val();
	
	$('#user-list .list-item').css('display', 'block');
	
	if (role != '0')
	{
		$('#user-list .list-item[data-role!="'+ role +'"]').css('display', 'none');
	} 
});