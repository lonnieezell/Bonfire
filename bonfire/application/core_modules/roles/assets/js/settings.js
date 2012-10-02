$('#permission_table').tableHover({colClass: 'hover', ignoreCols: [1]}); 


$('#permission_table input:checkbox').change(function() {
	$('#permission_table_result').removeClass();
	$('#permission_table_result').addClass('alert');
	$('#permission_table_result').addClass('alert-info');
	var val = $(this).attr('value');
	var what = $(this).is(':checked');
	var r_name = $(this).closest('td').attr('title');
	var p_name = $(this).closest('tr').attr('title');
	$.ajax({
			url: window.g_url +"/",
			type: 'POST',
			data: {
				"role_perm": val,
				"action": what,
				"ci_csrf_token": ci_csrf_token()
			},
			success: function(data) {
				var newtext = data.replace(/g_permission/i, '"'+p_name+'"');
				newtext = newtext.replace(/g_role/i, '"'+r_name+'" role');
				if (newtext.search(':') >= 1) {
					$('#permission_table_result').removeClass('alert-info');
					$('#permission_table_result').addClass('alert-warning');
				} else {
					$('#permission_table_result').removeClass('alert-info');
					$('#permission_table_result').addClass('alert-success');
				}
				$('#permission_table_result').text(newtext);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				// This callback was added as part of an urgent fix.
				// I expect some better solutions are possible.

				// E.g. this type of error can occur if you click a checkbox
				// then immediately navigate to another page - before the XHR
				// had time to complete.
				// In general, I get the impression we didn't want _A_JAX;
				// we wanted to use a fake-synchronous XHR technique
				// (real synchronous XHR can have the disadvantage of hanging
				// the entire browser).

				// This could be a network timeout.   So it's possible that
				// the user toggled _several_ permissions in that time,
				// and none of them have been applied.  The state of the checkboxes
				// would be nicely misleading now.  We need to warn about this.
				alert('An error occurred.  Some or all of your changes have failed.');

				// Refresh the page.
				// This should either get us back in sync, or bring up an error message.

				// Make sure we kill the page *immediately*, so the user doesn't
				// continue interacting with a doomed page.
				// We can't rely on them noticing that we've started to reload the page.
				// E.g. modern Firefox has de-emphasized its loading indicators to
				// near-invisibility.
				$('#permission_table').html('');

				// Use location.replace() to break the back button.
				// Not completely essential, but - I can imagine
				// seeing an error page, then hitting back and trying again.
				// We don't want people to do that, because we know
				// at _least_ one of the checkboxes is showing a wrong value.
				window.location.replace(window.location.href);
			}
		});
});

// Horizontal Check-all
$('.matrix-title a').click(function(){
	var rows 		= $(this).parents('tr');
	var checked 	= false;
	var found		= false;
	var checkbox	= false;
	
	for (i=0; i < rows.length; i++)
	{
	
		checkbox = $(rows[i]).find(":checkbox");
		
		if(!found && checkbox.length > 0){
			found=true;
			checked = $(checkbox).attr('checked');
		}
		if(checked) {
			checkbox.removeAttr('checked');
		}
		else
		{
			checkbox.attr('checked','checked');
		}
	}
	
	return false;
});

//--------------------------------------------------------------------

// Vertical Check All
$('.matrix th a').click(function(){
	var rows 		= $(this).parents('table').find('tbody tr');;
	var checked 	= false;
	var found		= false;
	var checkbox	= false;
	var columnIndex	= $(this).parent('th').index();
	for (i=0; i < rows.length; i++)
	{
		checkbox = $(rows[i]).find('td:eq('+(columnIndex)+')').find(":checkbox");
		
		if(!found && checkbox.length > 0){
			found=true;
			checked = $(checkbox).attr('checked');
		}
		if(checked) {
			checkbox.removeAttr('checked');
		}
		else
		{
			checkbox.attr('checked','checked');
		}
	}
	
	return false;
});

//--------------------------------------------------------------------
	
