$.subscribe('list-view/list-item/click', function(id) {
	$('#content').load('<?php echo site_url(SITE_AREA .'/settings/permissions/edit') ?>/'+ id);
});

$(".permission_set").click( function() {	
	if ($(this).next('div').is(':visible')) {
		$(this).children('img').attr("src","<?php echo Template::theme_url('images/plus.png') ?>" );
	} else {
		$(this).children('img').attr("src","<?php echo Template::theme_url('images/minus.png') ?>" );
	}
	$(this).nextUntil('.permission_set').slideToggle();
});

$('.permission_set').parent().children('div').hide();