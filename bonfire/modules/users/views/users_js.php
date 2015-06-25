/**
 * users_js.php
 *
 * Actions corresponding to drawing password strength and password matching indicators on all forms
 * with password fields.
 */
var force_numbers = <?php echo ($settings['auth.password_force_numbers'] == 1 ? 'true' : 'false'); ?>,
	force_symbols = <?php echo ($settings['auth.password_force_symbols'] == 1 ? 'true' : 'false'); ?>,
	force_mixed_case = <?php echo ($settings['auth.password_force_mixed_case'] == 1 ? 'true' : 'false'); ?>,
	min_password_len = <?php echo (isset($settings['auth.password_min_length']) ? $settings['auth.password_min_length']: 8); ?>,
	use_username = <?php echo ($settings['auth.use_usernames'] == 1 ? 'true' : 'false'); ?>,
	use_email = <?php echo ($settings['auth.use_usernames'] == 0 ? 'true' : 'false'); ?>;

$.strength("#username", "#password", {
		force_numbers: force_numbers,
		force_symbols: force_symbols,
		force_mixed_case: force_mixed_case,
		min_password_len: min_password_len,
		use_username: use_username,
		use_email: use_email
	}, function(username, password, strength) {
		var span = $("#strength"),
			classes = {
				"weak": "important",
				"good": "warning",
				"strong": "success"
			},
			icons = {
				"weak": "exclamation",
				"good": "warning",
				"strong": "ok"
			},
			textStrings = {
				"weak": "<?php echo lang('us_pass_weak') ?>",
				"good": "<?php echo lang('us_pass_good') ?>",
				"strong":"<?php echo lang('us_pass_strong') ?>"
			};

		if ($(password).val() != '') {
			if (!span.length) {
				$(password).after('<span class="help-inline" id="strength" style="display: inline-block;"><span class="label"><i class="strength-icon icon-white"></i> <span class="txt"></span></span></span>');
			}
			$('#strength .label')
				.removeClass('label-important')
				.removeClass('label-warning')
				.removeClass('label-success')
				.addClass('label-'+classes[strength.status]);

			$('#strength .label .txt').html(textStrings[strength.status]);
			$('#strength .label .strength-icon')
				.removeClass('icon-exclamation-sign')
				.removeClass('icon-ok-sign')
				.removeClass('icon-warning-sign')
				.addClass('icon-'+icons[strength.status]+'-sign');
		}
	}, "#email");

/**
 *	Test if entered passwords match.
 */
$('#pass_confirm').blur(function() {
	if ($('#pass_confirm').val() != '' && $('#password').val() != '')
	{
        var span = $("#match");
        if (!span.length) {
            $('#pass_confirm').after('<span class="help-inline" id="match" style="display: inline-block;"><span class="label"><i class="match-icon icon-white"></i> <span class="txt"></span></span></span>');
        }
        var thisClass ='', txt = '', icon = '';
		if ($('#pass_confirm').val() != $('#password').val())
		{
			thisClass = 'important';
			icon = "exclamation";
			txt = '<?php echo lang('us_passwords_no_match') ?>';
		} else {
			thisClass = 'success';
			icon = "ok";
			txt = '<?php echo lang('us_passwords_match') ?>';
		}
		$("#match .label").removeClass('label-success')
				.removeClass('label-important')
				.addClass('label-'+thisClass);
		$("#match .label .txt").html(txt);
		$("#match .label .match-icon")
				.removeClass('icon-exclamation-sign')
				.removeClass('icon-ok-sign')
				.addClass('icon-'+icon+'-sign');
		$("#match").css('display','inline-block');
	}
});