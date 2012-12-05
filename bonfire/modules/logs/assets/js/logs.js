// Filter Hook
$('#filter').change(function(){
	// Are we filtering at all? 
	var filter = $(this).val();

	$('#log div').each(function(){
	
		switch (filter)
		{
			case 'all':
				$(this).css('display', 'block');
				break;
			case 'error':
				if ($(this).hasClass('alert-error'))
				{
					$(this).css('display', 'block');
				} else
				{
					$(this).css('display', 'none');
				}
		}
	});
});