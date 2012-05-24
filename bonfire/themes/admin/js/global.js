/* Nofication Close Buttons */
$('.notification a.close').click(function(e){
	e.preventDefault();

	$(this).parent('.notification').fadeOut();
});

/*
	Check All Feature
*/
$(".check-all").click(function(){
	$("table input[type=checkbox]").attr('checked', $(this).is(':checked'));
});

/*
	Dropdowns
*/
$('.dropdown-toggle').dropdown();

/*
	Set focus on the first form field
*/
$(":input:visible:first").focus();

/*
	Responsive Navigation
*/
$('.collapse').collapse();

/*
 Prevent elements classed with "no-link" from linking
*/
//$(".no-link").click(function(e){ e.preventDefault();	});
