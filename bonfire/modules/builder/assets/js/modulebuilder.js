/**
 * @type Object Global bonfire object to hold options
 */
var bonfire = bonfire || {};
bonfire.builder = {
	legendIcon: {
		open: 'icon-plus',
		close: 'icon-minus'
	},
	animation: {
		easing: 'swing',
		duration: 400
	}
};
/**
 * Toggles the visibility of the advanced table properties
 * when the Create Module Table checkbox is checked.
 * Also copies the name of the module as the lowercase name of
 * the table, with underscores replacing spaces.
 *
 * @returns void
 */
function show_table_props() {
	/**
	 * @type String The name of the database table, based on user entry or the module name
	 */
	var tblName,
		/**
		 * @type Object The animation options passed to show/hide methods
		 */
		anim = bonfire.builder.animation;

	if ($('#db_create').is(':checked')) {
		$('#db_details').show(anim);
		$('#db_details .mb_advanced').hide(anim);
		$('.mb_new_table').show(anim);
		$('#field_numbers').show(anim);
		$('#all_fields').show(anim);

		$('#primary_key_field').val('' == $('#primary_key_field').val() ? 'id' : $('#primary_key_field').val());
		tblName = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tblName = tblName.replace(/[^A-Za-z0-9\\s]/g, "_").toLowerCase();
		$('#table_name').val( tblName );
	} else if ($('#db_exists').is(':checked')) {
		$('#db_details').show(anim);
		$('#db_details .mb_advanced').show(anim);
		$('.mb_new_table').hide(anim);
		$('#field_numbers').hide(anim);
		$('#all_fields').hide(anim);

		tblName = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tblName = tblName.replace(/[^A-Za-z0-9\\s]/g, "_").toLowerCase();
		$('#table_name').val( tblName );

		if ($('#view_field_label1').val() != undefined && $('#view_field_label1').val() != '') {
			$('.mb_new_table').show(anim);
			$('#db_details .notification').hide(anim);
			$('#field_numbers').hide(anim);
			$('#all_fields').show(anim);
		} else {
			$('#primary_key_field').val('');
			$('#all_fields').empty();
		}
	} else {
		$('#db_details').hide(anim);
		$('#all_fields').hide(anim);
	}
}

/**
 * Use LocalStorage to hold entered information for all of the fields,
 * so the user doesn't have to enter them again when the page is redirected
 * (when choosing the number of fields). Also clears a checkbox's value if
 * it shouldn't be checked on reload.
 *
 * @returns void
 */
function store_form_data() {
	// loop through all the inputs and get the data
	$('#module_form :input').each( function() {
		/**
		 * @type String The id for this input field
		 */
		var fld_id = $(this).attr('id'),
			/**
			 * @type String The value for this input field
			 */
			fld_val = $(this).val();

		if ($(this).is(':checkbox') && $(this).is(':not(:checked)')) {
			fld_val = 'uncheck';
		}

		if ($(this).is(':radio') && $(this).is(':not(:checked)')) {
			return;
		}

		if (fld_id && fld_val) {
			localStorage[fld_id] = fld_val;
		}
	});
}

/**
 * Re-populate the form fields if they have been stored in LocalStorage.
 * Once the fields are loaded, all LocalStorage information is cleared
 *
 * @returns void
 */
function get_form_data() {
	/**
	 * @type Int Loop counter
	 */
	var i = 0,
		/**
		 * @type String The key for the current LocalStorage entry
		 */
		key,
		/**
		 * @type String The value for the current LocalStorage entry
		 */
		value;

	for (i = 0; i < localStorage.length; i++) {
		key = localStorage.key(i);
    	value = localStorage[key];

		if ($('#' + key).is(':checkbox, :radio')) {
			if ($('#' + key).val() == value) {
				$('#' + key).attr('checked', 'checked');
			} else {
				$('#' + key).removeAttr('checked');
				//restore the proper value
				value = $('#' + key).val();
			}
		}

		$('#' + key).val(value);
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
	/**
	 * @type Object The animation options passed into the toggle method
	 */
	var anim = bonfire.builder.animation,
		/**
		 * @type String The id value of the closest fieldset element
		 */
		parent;

	e.preventDefault();

	parent = $(this).closest('fieldset').attr('id');
	$('#' + parent + ' .mb_advanced').toggle(anim);
});
/*-----------------------------------------------------------
Toggle "more validation rules"
------------------------------------------------------------*/
$('.mb_show_advanced_rules').click( function(e) {
	/**
	 * @type Object The animation options passed into the toggle method
	 */
	var anim = bonfire.builder.animation;

	e.preventDefault();

	$(this).parent().parent().next('.mb_advanced').toggle(anim);
});

/*-----------------------------------------------------------
Toggle module/table advanced options by clicking on the
fieldset legend. Uses the div:not to not affect the visibility
options of the "more validation rules"
------------------------------------------------------------*/
$('.body legend').click( function() {
	/**
	 * @type Object The animation options passed into the toggle method
	 */
	var anim = bonfire.builder.animation,
		/**
		 * @type Object The name of the "close" icon to display on legend elements
		 */
		closeIcon = bonfire.builder.legendIcon.close,
		/**
		 * @type Object The name of the "open" icon to display on legend elements
		 */
		openIcon = bonfire.builder.legendIcon.open;

	$(this).parent('fieldset').children('div:not(".mb_advanced:hidden")').toggle(anim);

	if ($(this).children('.' + closeIcon).length > 0) {
		$(this).children('.' + closeIcon).replaceWith('<span class="' + openIcon + '"></span>');
	} else {
		$(this).children('.' + openIcon).replaceWith('<span class="' + closeIcon + '"></span>');
	}
});

$('.body legend').prepend('<span class="' + bonfire.builder.legendIcon.close + '">&nbsp;');

/*-----------------------------------------------------------
Highlight faded labels when the control is focused
------------------------------------------------------------*/
$('.faded input').on('focus', function() {
	$(this).closest('.faded').addClass('faded-focus');
});
$('.faded input').on('blur', function() {
	$(this).closest('.faded').removeClass('faded-focus');
});
$('.faded input:focus').closest('.faded').addClass('faded-focus');