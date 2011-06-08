<br />
<?php

$view = '<?php // Change the css classes to suit your needs    

$attributes = array("class" => "", "id" => "'.$controller_name.'_'.$action_name.'");
echo form_open("admin/'.$controller_name.'/'.$module_name_lower.'/'.$action_name.''.$id_val.'", $attributes);
';

$view .= '
echo form_hidden("id", $id);';

$view .= '?>';

$view .= <<<EOT
<div class="box create rounded">
	<h3>Delete this {$module_name}</h3>

	<?php echo form_submit( 'submit', '$action_label'); ?> or <a href="/admin/{$controller_name}/{$module_name_lower}">Cancel</a>
</div>


<?php echo form_close(); ?>

EOT;

echo $view;
?>
