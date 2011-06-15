<?php

$view = '$.subscribe(\'list-view/list-item/click\', function(id) {
	$(\'#content\').load(\'<?php echo site_url(\'admin/'.$controller_name.'/'.$module_name_lower.'/edit\') ?>/\'+ id);
});';
echo $view;
