(function(){
	var $filter = $('#filter'),
		filterChange = function() {
			var filterVal = $filter.val();

			$('#log div').each(function() {
				switch (filterVal) {
					case 'all':
						$(this).css('display', 'inherit');
						break;

					case 'error':
						if ($(this).hasClass('alert-error')) {
							$(this).css('display', 'inherit');
						} else {
							$(this).css('display', 'none');
						}
						break;
				}
			});
		};

	$filter.change(filterChange);
	filterChange();
})();