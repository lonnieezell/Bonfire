/*
	Users.js
	
	Actions corresponding to register form
*/
$.strength("#username", "#password", function(username, password, strength){
    var txt = '', thisClass ='', icon = '';
	switch (strength.status) 
	{
		case "weak":
			icon = "exclamation";
			thisClass ="important";
			txt = "<?php echo lang('us_pass_weak') ?>";
			break;
		case "good":
			icon = "warning";
			thisClass = "warning";
			txt = "<?php echo lang('us_pass_good') ?>";
			break;
		case "strong":
			icon = "ok";
			thisClass = "success";
			txt = "<?php echo lang('us_pass_strong') ?>";
			break;
	}
	$('#strength .label').removeClass('label-success')
				.removeClass('label-warning')
				.removeClass('label-important')
				.addClass('label-'+thisClass);
	$('#strength .label .txt').html(txt);
	$('#strength .label .strength-icon')
				.removeClass('icon-exclamation-sign')
				.removeClass('icon-warning-sign')
				.removeClass('icon-ok-sign')
				.addClass('icon-'+icon+'-sign');
	$("#strength").css('display','inline-block');
	$("#strength").next('.help-block').css('display','none');
});
/**
 *	Test if entered passwords match.
 */
$('#pass_confirm').keyup(function()
{
	if ($('#pass_confirm').val() != '' && $('#password').val() != '') 
	{
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