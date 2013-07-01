/**
 * @type {Object} Global object to hold properties/methods used by Bonfire
 */
var bonfire = bonfire || {};
/**
 * @type {Object} Holds properties/methods specific to Builder
 */
bonfire.builder = {
	/**
	 * @type {Object} Holds the class names for the open/close icons used on the page's legends
	 */
	legendIcon: {
		/** @type {String} The class name for the open icon */
		open: 'icon-plus',
		/** @type {String} The class name for the close icon */
		close: 'icon-minus'
	},
	/**
	 * @type {Object} Defines the animation options used when opening/closing sections of the page
	 * @link http://api.jquery.com/animate/
	 */
	animation: {
		/** @type {String} A string indicating which easing function to use for the transition */
		easing: 'swing',
		/** @type {Number|String} A string or number determining how long the animation will run */
		duration: 400
	},
	/**
	 * @type {Object} Holds the default values used to create the table name from the module name
	 */
	prepTableDefaults: {
		/** @type {String} The value to prepare for use as the table name */
		valueToPrep: '',
		/** @type {Boolean} Determines whether the function sets the table name (using tableNameSelector) in addition to returning the string */
		setTableName: true,
		/** @type {String} The selector for the field containing the name of the module */
		moduleNameSelector: '#module_name',
		/** @type {String} The selector for the field containing the name of the table */
		tableNameSelector: '#table_name',
		/** @type {String} The value to replace unwanted values in the module name, such as spaces */
		tableNameReplacement: '_',
		/** @type {String} The Regular Expression used to replace unwanted values in the module name */
		tableNameRegEx: /[^A-Za-z0-9\\s]/g
	},
	/**
	 * Prepares the module name to be used as a database table name
	 *
	 * @param   {Object} prepOptions Contains optional settings
	 * 			used to convert the module name into a suitable
	 * 			database table name
	 *
	 * @returns {String} The name to be used for the table
	 */
	prepTableName: function(prepOptions) {
		/** @type {Object} Settings object to hold the combination of defaults and options passed in the function argument */
		var prepSettings = $.extend({}, bonfire.builder.prepTableDefaults, prepOptions),
		/** @type {String} The value we are preparing to use as the table name */
			tableName = prepSettings.valueToPrep,
		/** @type {Object} If called as an event handler, we'll store the event data here */
			event;

		// If the function is used as an event handler, our normal options may be
		// passed in prepOptions.data instead of prepOptions itself, so we need to
		// make sure we are still retrieving our options
		if (prepOptions && prepOptions.data) {
			event = prepOptions;
			prepOptions = prepOptions.data;
		}
		prepSettings = $.extend({}, bonfire.builder.prepTableDefaults, prepOptions);

		if ('' == tableName) {
			tableName = $(prepSettings.moduleNameSelector).val();
		}
		tableName = tableName.replace(prepSettings.tableNameRegEx, prepSettings.tableNameReplacement).toLowerCase();

		if (prepSettings.setTableName === true) {
			$(prepSettings.tableNameSelector).val(tableName);
		}
		return tableName;
	}
};
/**
 * Toggles the visibility of the advanced table properties
 * when the Create Module Table checkbox is checked.
 * Also sets the name of the table as the lowercase name of
 * the module, with underscores replacing spaces, if the table
 * name is not already set.
 *
 * @returns {void}
 */
function show_table_props() {
	/**
	 * @type {String} The name of the database table, based on user entry or the module name
	 */
	var tblName,
		/**
		 * @type {Object} The animation options passed to show/hide methods
		 */
		anim = bonfire.builder.animation;

	if ($('#db_create').is(':checked')) {
		$('#db_details').show(anim);
		$('#db_details .mb_advanced').hide(anim);
		$('.match-existing-notes').hide(anim);
		$('.mb_new_table').show(anim);
		$('#field_numbers').show(anim);
		$('#all_fields').show(anim);

		$('#primary_key_field').val('' == $('#primary_key_field').val() ? 'id' : $('#primary_key_field').val());

		tblName = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tblName = bonfire.builder.prepTableName({valueToPrep: tblName, setTableName: true});
	} else if ($('#db_exists').is(':checked')) {
		$('#db_details').show(anim);
		$('#db_details .mb_advanced').show(anim);
		$('.match-existing-notes').show(anim);
		$('.mb_new_table').hide(anim);
		$('#field_numbers').hide(anim);
		$('#all_fields').hide(anim);

		tblName = ( ( $('#table_name').val() == '' ) ? $('#module_name').val() : $('#table_name').val() );
		tblName = bonfire.builder.prepTableName({valueToPrep: tblName, setTableName: true});

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
		$('.match-existing-notes').hide(anim);
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

/*
 * Update the table name when changing the module name
 */
$('#module_name').on('click focus blur', {valueToPrep: '', setTableName: true}, bonfire.builder.prepTableName);