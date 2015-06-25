$(document).ready(function(){

	// Mobile Nav 'Tab Bar' interface.
	$('.menu-link a').click(function(e){
		e.preventDefault();
		
		var id = $(this).attr('data-id');
		
		// Close all open tabs
		$('.mobile_nav').css('display', 'none');

		// Show ours
		$('ul#'+ id).css('display', 'block');
		
		return false;
	});

});