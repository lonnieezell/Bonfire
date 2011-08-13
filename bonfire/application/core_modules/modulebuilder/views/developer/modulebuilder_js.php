function verify_delete(module_name) {
	
    var verify = confirm('Are you sure you wish to delete module "'+module_name+'"?')
    
    if (verify) {
        var url = '<?php echo site_url(SITE_AREA .'/developer/modulebuilder/delete/') ?>/'+ module_name;
        window.location.href = url
    }
    
    // this is for the icon click delete, doesn't like event.preventDefault();
    return false;
}

function show_table_props() {
	if ($('#db_required').is(':checked')) {
		$('#db_details').show(0);
		$('#all_fields').show(0);
		var tbl_name = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tbl_name = tbl_name.replace(/[^A-Za-z0-9\\s]/g, "_").toLowerCase();
		$('#table_name').val( tbl_name );
	} else {
		$('#db_details').hide(0);
		$('#all_fields').hide(0);
	}
}

function store_form_data() {
	// loop through all the inputs and get the data
	$('#module_form :input').each( function() {
		var fld_id = $(this).attr('id');
		var fld_val = $(this).val();
		
		if ( $(this).is(':checkbox') && $(this).is(':not(:checked)') ) {
			fld_val = 'uncheck';
		}

		if (fld_id && fld_val) {
			localStorage[fld_id] = fld_val;
		}
	});
}

function get_form_data() {
	for (var i = 0; i < localStorage.length; i++){
		var key = localStorage.key(i);
    	var value = localStorage[key];
		
		if ( $('#'+key).is(':checkbox') ) {
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

if (localStorage.length >= 1) {
	get_form_data();
}

$.subscribe('list-view/list-item/click', function(module_name) {
    verify_delete(module_name);
});

$('.confirm_delete').click(function(event) {
	event.stopImmediatePropagation();
	event.preventDefault();
    verify_delete($(this).attr('title'));    
});

$('#field_numbers a').click( function() {
	// need to grab all the information to reload it. since db_details already has an id, we can store the data there.
	store_form_data();
});

$('#module_form').submit( function() {
	// need to grab all the information to reload it. since db_details already has an id, we can store the data there.
	store_form_data();
});

show_table_props();
$('#db_required').click( function() {
	show_table_props();
});

$('.mb_show_advanced').click( function() {
	var parent = $(this).closest('fieldset').attr('id');
	$('#'+parent+' .mb_advanced').toggle();
});

$('.mb_show_advanced_rules').click( function() {
	$(this).parent().next('.mb_advanced').toggle();
});

$('.container legend').click( function() {
	$(this).parent('fieldset').children('div:not(".mb_advanced:hidden")').toggle();
});