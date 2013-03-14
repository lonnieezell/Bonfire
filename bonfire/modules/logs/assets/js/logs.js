(function(){

	var $filter = $('#filter')

	function filterChange(){
		// Are we filtering at all?
		var filter_val = $filter.val();

		$('#log div').each(function(){

			switch (filter_val)
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
	}

	$filter.change(filterChange);
	filterChange();
})();
