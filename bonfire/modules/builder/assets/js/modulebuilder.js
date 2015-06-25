/** Global object to hold properties/methods used by Bonfire */
var bonfire = bonfire || {};

/** Holds properties/methods used by Bonfire's Builder module */
bonfire.builder = {
	/** Class names for the open/close icons used on the form's legends */
	legendIcon: {
		/**
		 * Class name for the open icon
		 * @type {string}
		 */
		open: 'icon-plus',
		/**
		 * Class name for the close icon
		 * @type {string}
		 */
		close: 'icon-minus'
	},
	/**
	 * Animation options used when opening/closing sections of the page
	 * @link http://api.jquery.com/animate/
	 */
	animation: {
		/**
		 * The easing function used for the transition
		 * @type {string}
		 */
		easing: 'swing',
		/**
		 * The duration of the animation
		 * @type {number|string}
		 */
		duration: 400
	},
	/**
	 * The default values used to create the table name from the module name
	 */
	prepTableDefaults: {
		/** The value to prepare for use as the table name */
		valueToPrep: '',
		/**
		 * Determines whether the function sets the table name in addition to
		 * returning the string (uses tableNameSelector to set the table name)
		 */
		setTableName: true,
		/** The selector for the field containing the module name */
		moduleNameSelector: '#module_name',
		/** The selector for the field containing the table name */
		tableNameSelector: '#table_name',
		/** The character to replace unwanted characters, such as spaces */
		tableNameReplacement: '_',
		/**
		 * The Regular Expression used to replace unwanted values
		 * @type {RegExp}
		 */
		tableNameRegEx: /[^A-Za-z0-9\\s]/g
	},
	/**
	 * Prepare the module name for use as a database table name
	 *
	 * @param   {Object} prepOptions Contains optional settings used to convert
	 * the module name into a suitable database table name
	 *
	 * @returns {string} The name to be used for the table
	 */
	prepTableName: function(prepOptions) {
		/** Settings used within the function */
		var prepSettings,
		/** The value being prepared for use as the table name */
			tableName,
		/** Stores event data if prepTableName is called as an event handler */
			event;

		/*
		 * When prepTableName is called as an event handler, the options may be
		 * passed in prepOptions.data instead of prepOptions itself, so this
		 * will ensure the options are retrieved properly
		 */
		if (prepOptions && prepOptions.data) {
			event = prepOptions;
			prepOptions = prepOptions.data;
		}

		prepSettings = $.extend({}, bonfire.builder.prepTableDefaults, prepOptions);
		tableName = prepSettings.valueToPrep
		if ('' == tableName) {
			tableName = $(prepSettings.tableNameSelector).val();
		}
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
 * Show/Hide the DB table properties
 *
 * Changes the visibility of the advanced table properties when the Module Table
 * radio buttons are selected (None/Create New Table/Build From Existing Table).
 * Also sets the name of the table as the lowercase name of the module, with
 * underscores replacing spaces, if the table name is not already set.
 *
 * @returns {void}
 */
function showTableProperties() {
	/** The name of the database table, based on user entry or the module name */
	var tblName,
	/** The animation options passed to show/hide methods */
		anim = bonfire.builder.animation;

	/* Display the fields used when creating a new table */
	if ($('#db_create').is(':checked')) {
		$('#db_details').show(anim);
		$('#db_details .mb_advanced').hide(anim);
		$('.match-existing-notes').hide(anim);
		$('.mb_new_table').show(anim);
		$('#field_numbers').show(anim);
		$('#all_fields').show(anim);

		$('#primary_key_field').val('' == $('#primary_key_field').val() ? 'id' : $('#primary_key_field').val());

		tblName = $('#table_name').val() == '' ? $('#module_name').val() : $('#table_name').val();
		tblName = bonfire.builder.prepTableName({valueToPrep: tblName, setTableName: true});
	}
	/* Display the fields used when building from an existing table */
	else if ($('#db_exists').is(':checked')) {
		$('#db_details').show(anim);
		$('#db_details .mb_advanced').show(anim);
		$('.match-existing-notes').show(anim);
		$('.mb_new_table').hide(anim);
		$('#field_numbers').hide(anim);
		$('#all_fields').hide(anim);

		tblName = $('#table_name').val() == '' ? $('#module_name').val() : $('#table_name').val();
		tblName = bonfire.builder.prepTableName({valueToPrep: tblName, setTableName: true});

		/* Are there any fields on the form? */
		if ($('#view_field_label1').val() != undefined && $('#view_field_label1').val() != '') {
			$('.mb_new_table').show(anim);
			$('#db_details .notification').hide(anim);
			$('#field_numbers').hide(anim);
			$('#all_fields').show(anim);
		} else {
			$('#primary_key_field').val('');
			$('#all_fields').empty();
		}
	}
	/* Hide the fields (module is to be created without a table/model */
	else {
		$('#db_details').hide(anim);
		$('#all_fields').hide(anim);
		$('.match-existing-notes').hide(anim);
	}
}

/**
 * Store form data in LocalStorage
 *
 * @todo Scope the data in localStorage so conflicts with other code are less likely.
 *
 * Uses LocalStorage to hold entered information for all of the fields, so the
 * user doesn't have to enter it again when the page is redirected (when
 * choosing the number of fields). Also clears a checkbox's value if it
 * shouldn't be checked on reload.
 *
 * @returns void
 */
function storeFormData() {
	/* Loop through all the inputs and get the data */
	$('#module_form :input').each(function() {
		/** The id for this input field */
		var fldId = $(this).attr('id'),
		/** The value for this input field */
			fldVal = $(this).val();

		if ($(this).is(':checkbox') && $(this).is(':not(:checked)')) {
			fldVal = 'uncheck';
		}

		if ($(this).is(':radio') && $(this).is(':not(:checked)')) {
			return;
		}

		if (fldId && fldVal) {
			localStorage[fldId] = fldVal;
		}
	});
}

/**
 * Retrieve form data from LocalStorage
 *
 * @todo Scope the data in localStorage so conflicts with other code are less likely.
 *
 * Re-populate the form fields if they have been stored in LocalStorage. Once
 * the fields are loaded, all LocalStorage information is cleared.
 *
 * @returns void
 */
function getFormData() {
	/** Loop counter */
	var i = 0,
	/** The key for the current LocalStorage entry */
		key,
	/** The value for the current LocalStorage entry */
		value;

	for (i = 0; i < localStorage.length; i++) {
		key = localStorage.key(i);
    	value = localStorage[key];

        // If the key contains a '/', it was most likely set by some other script.
        // (DataTables v1.10 raises this issue by using the page's URL as the key
        // when the stateSave option is enabled.)
        if (key.indexOf('/') > -1) {
            continue;
        }

		/* Restore the checked state of checkbox/radio buttons */
		if ($('#' + key).is(':checkbox, :radio')) {
			if ($('#' + key).val() == value) {
				$('#' + key).attr('checked', 'checked');
			} else {
				$('#' + key).removeAttr('checked');
				/* Restore the proper value */
				value = $('#' + key).val();
			}
		}

		$('#' + key).val(value);
	}

	/* Remove the data from localStorage when the form has been restored */
	localStorage.clear();
}

/*------------------------------------------------------------------------------
 * Checks whether LocalStorage holds any information, then load it.
 */
if (localStorage.length >= 1) {
	getFormData();
}

/*------------------------------------------------------------------------------
 * User is choosing # of fields, store all the data in LocalStorage
 */
$('#field_numbers a').click(function() {
	storeFormData();
});

/*------------------------------------------------------------------------------
 * User submitted form, store data in case of errors.
 */
$('#module_form').submit(function() {
	storeFormData();
});

/*------------------------------------------------------------------------------
 * Set the initial visibility of advanced options
 */
showTableProperties();

/*------------------------------------------------------------------------------
 * Toggle module table
 */
$('input[name=module_db]').click(function() {
	showTableProperties();
});

/*------------------------------------------------------------------------------
 * Toggle advanced options
 */
$('.mb_show_advanced').click(function(e) {
	/** The animation options passed into the toggle method */
	var anim = bonfire.builder.animation,
	/** The id value of the closest fieldset element */
		parent;

	e.preventDefault();

	parent = $(this).closest('fieldset').attr('id');
	$('#' + parent + ' .mb_advanced').toggle(anim);
});

/*------------------------------------------------------------------------------
 * Toggle Validation Rules "...toggle more rules..." section
 */
$('.mb_show_advanced_rules').click(function(e) {
	/** The animation options passed into the toggle method */
	var anim = bonfire.builder.animation;

	e.preventDefault();

	$(this).parent().parent().next('.mb_advanced').toggle(anim);
});

/*------------------------------------------------------------------------------
 * Toggle module/table advanced options by clicking on the fieldset legend.
 * Uses the div:not to not affect the visibility options of the "...toggle more
 * rules..." section of the Validation Rules
 */
$('.body legend').click(function() {
	/** The animation options passed into the toggle method */
	var anim = bonfire.builder.animation,
	/** The name of the "close" icon to display on legend elements */
		closeIcon = bonfire.builder.legendIcon.close,
	/** The name of the "open" icon to display on legend elements */
		openIcon = bonfire.builder.legendIcon.open;

	$(this).parent('fieldset').children('div:not(".mb_advanced:hidden")').toggle(anim);

	if ($(this).children('.' + closeIcon).length > 0) {
		$(this).children('.' + closeIcon).replaceWith('<span class="' + openIcon + '"></span>');
	} else {
		$(this).children('.' + openIcon).replaceWith('<span class="' + closeIcon + '"></span>');
	}
});

/* Add the Close icon to all of the fieldset legends */
$('.body legend').prepend('<span class="' + bonfire.builder.legendIcon.close + '">&nbsp;');

/*------------------------------------------------------------------------------
 * Highlight faded labels when the control is focused
 */
$('.faded input').on('focus', function() {
	$(this).closest('.faded').addClass('faded-focus');
});
$('.faded input').on('blur', function() {
	$(this).closest('.faded').removeClass('faded-focus');
});
$('.faded input:focus').closest('.faded').addClass('faded-focus');

/*------------------------------------------------------------------------------
 * Update the table name when changing the module name
 */
$('#module_name, #table_name').on('click focus blur', {valueToPrep: '', setTableName: true}, bonfire.builder.prepTableName);