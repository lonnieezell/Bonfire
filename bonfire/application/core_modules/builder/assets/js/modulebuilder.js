/*-----------------------------------------------------------
This toggles the visibility of the advanced table properties
when the Create Module Table checkbox is checked. It also
copies down the name of the module as the lowercase name of
the table with underscores replacing the spaces.
------------------------------------------------------------*/
function show_table_props() {
	if ($('#db_create').is(':checked')) {
		$('#db_details').show(0);
		$('#db_details .mb_advanced').hide();
		$('.mb_new_table').show(0);
		$('#field_numbers').show(0);
		$('#all_fields').show(0);
		$('#primary_key_field').val('' == $('#primary_key_field').val() ? 'id' : $('#primary_key_field').val());
		var tbl_name = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tbl_name = tbl_name.replace(/[^A-Za-z0-9\\s]/g, "_").toLowerCase();
		$('#table_name').val( tbl_name );
	} else if ($('#db_exists').is(':checked')) {
		$('#db_details').show(0);
		$('#db_details .mb_advanced').show();
		$('.mb_new_table').hide(0);
		$('#field_numbers').hide(0);
		$('#all_fields').hide(0);
		var tbl_name = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tbl_name = tbl_name.replace(/[^A-Za-z0-9\\s]/g, "_").toLowerCase();
		$('#table_name').val( tbl_name );

		if ($('#view_field_label1').val() != undefined && $('#view_field_label1').val() != '')
		{
			$('.mb_new_table').show(0);
			$('#db_details .notification').hide(0);
			$('#field_numbers').hide(0);
			$('#all_fields').show(0);
		}
		else
		{
			$('#primary_key_field').val('');
			$('#all_fields').empty();
		}
	} else {
		$('#db_details').hide(0);
		$('#all_fields').hide(0);
	}
}

/*-----------------------------------------------------------
When choosing the # of fields, the page is redirected and
information entered coudl be lost. SO Using LocalStorage we
hold the entered information for all the fields so that the
user does not have to enter them all over again. It also sets
a checkbox's value to "uncheck" if it should not be checked
when the page is reloaded.
------------------------------------------------------------*/
function store_form_data() {
	// loop through all the inputs and get the data
	$('#module_form :input').each( function() {
		var fld_id = $(this).attr('id');
		var fld_val = $(this).val();

		if ( $(this).is(':checkbox') && $(this).is(':not(:checked)') ) {
			fld_val = 'uncheck';
		}
		if ( $(this).is(':radio') && $(this).is(':not(:checked)') ) {
			return;
		}

		if (fld_id && fld_val) {
			localStorage[fld_id] = fld_val;
		}
	});
}


/*-----------------------------------------------------------
Pulling the information out of the LocalStorage, the fields
are re-populated if any information exists in LocalStorage.
Once loaded, all information in LocalStorage is cleared.
------------------------------------------------------------*/
function get_form_data() {
	for (var i = 0; i < localStorage.length; i++){
		var key = localStorage.key(i);
    	var value = localStorage[key];

		if ( $('#'+key).is(':checkbox, :radio') ) {
			if ( $('#'+key).val() == value ) {
				$('#'+key).attr('checked','checked');
			} else {
				$('#'+key).removeAttr('checked');
				//restore the proper value
				value = $('#'+key).val();
			}
		}

		$('#'+key).val(value);
	}

	//now that it is loaded, remove localStorage
	localStorage.clear();
}

/*-----------------------------------------------------------
Checks if LocalStorage holds any information, then loads it.
------------------------------------------------------------*/
if (localStorage.length >= 1) {
	get_form_data();
}

/*-----------------------------------------------------------
User is choosing # of fields, store all the data in LocalStorage
------------------------------------------------------------*/
$('#field_numbers a').click( function() {
	store_form_data();
});

/*-----------------------------------------------------------
User submitted form, store data in case of errors.
------------------------------------------------------------*/
$('#module_form').submit( function() {
	store_form_data();
});

/*-----------------------------------------------------------
Initial visibility toggle of advanced options
------------------------------------------------------------*/
show_table_props();

/*-----------------------------------------------------------
Toggle module table
------------------------------------------------------------*/
$('input[name=module_db]').click( function() {
	show_table_props();
});

/*-----------------------------------------------------------
Toggle advanced options
------------------------------------------------------------*/
$('.mb_show_advanced').click( function(e) {
	e.preventDefault();

	var parent = $(this).closest('fieldset').attr('id');
	$('#'+parent+' .mb_advanced').toggle();
});
/*-----------------------------------------------------------
Toggle "more validation rules"
------------------------------------------------------------*/
$('.mb_show_advanced_rules').click( function(e) {
	e.preventDefault();

	$(this).parent().parent().next('.mb_advanced').toggle();
});

/*-----------------------------------------------------------
Toggle module/table advanced options by clicking on the
fieldset legend. Uses the div:not to not affect the visibility
options of the "more validation rules"
------------------------------------------------------------*/
$('.body legend').click( function() {
	$(this).parent('fieldset').children('div:not(".mb_advanced:hidden")').toggle();
});

/*-----------------------------------------------------------
Highlight faded labels when the control is focused
------------------------------------------------------------*/
$('.faded input').on('focus', function() {
	$(this).closest('.faded').addClass("faded-focus");
});
$('.faded input').on('blur', function() {
	$(this).closest('.faded').removeClass("faded-focus");
});
$('.faded input:focus').closest('.faded').addClass("faded-focus");

