<?php

$view = '
<?php if (validation_errors()) : ?>
<div class="notification error">
	<?php echo validation_errors(); ?>
</div>
<?php endif; ?>
<?php // Change the css classes to suit your needs    
if( isset($'.$module_name_lower.') ) {
	$'.$module_name_lower.' = (array)$'.$module_name_lower.';
}
$id = isset($'.$module_name_lower.'[\''.$primary_key_field.'\']) ? "/".$'.$module_name_lower.'[\''.$primary_key_field.'\'] : \'\';
';
$view .= '?>';
$view .= '
<?php echo form_open($this->uri->uri_string(), \'class="constrained ajax-form"\'); ?>';
$on_click = '';
$xinha_names = '';
for($counter=1; $field_total >= $counter; $counter++)
{
	$maxlength = NULL; // reset this variable

	// only build on fields that have data entered. 
	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_label$counter") == NULL)
	{
		continue; 	// move onto next iteration of the loop
	}

	$field_label = set_value("view_field_label$counter");
	$field_name = $module_name_lower . '_' . set_value("view_field_name$counter");
	$field_type = set_value("view_field_type$counter");

	if ($field_type != 'checkbox') // checkbox appears to the left of the checkbox so I can't add now for a checkbox
	{
		
$view .= <<<EOT

{$form_input_delimiters[0]}
        <?php echo form_label('{$field_label}', '{$field_name}'); ?>
EOT;
		
	} else {
$view .= <<<EOT

{$form_input_delimiters[0]}
	
EOT;
	}

	// set a friendly variable name
	$validation_rules = $this->input->post('validation_rules'.$counter);

	if (is_array($validation_rules))
	{       
		// rules have been selected for this fieldset

		foreach($validation_rules as $key => $value)
		{
			if($value == 'required')
			{
				$view .= ' <span class="required">*</span>';
			}
		}
	}
                

	// field type
	switch($field_type)
	{

	// Some consideration has gone into how these should be implemented
	// I came to the conclusion that it should just setup a mere framework
	// and leave helpful comments for the developer
	// Modulebuilder is meant to have a minimium amount of features. 
	// It sets up the parts of the form that are repitive then gets the hell out
	// of the way.

	// This approach maintains these aims/goals

	case('textarea'):
		
		if (!empty($textarea_editor) )
		{
			// if a date field hasn't been included already then add in the jquery ui files
			if ($textarea_editor == 'ckeditor') {
				$view .= '
			<script type="text/javascript">
				head.ready(function(){
					//<![CDATA[
					if( !(\''.$field_name.'\' in CKEDITOR.instances)) {
						CKEDITOR.replace( \''.$field_name.'\' );
					}
					//]]>
				});
			</script>';
				if (empty($on_click))
				{
					$on_click .= ' onclick="javascript:';
				}
				$on_click .= 'CKEDITOR.instances.'.$field_name.'.destroy();';
			}
			elseif ($textarea_editor == 'xinha') {
				//
				if ($xinha_names != '')
				{
					$xinha_names .= ', ';
				}
				$xinha_names .= '\''.$field_name.'\'';

			}
		}
		$view .= "
	<?php echo form_textarea( array( 'name' => '$field_name', 'id' => '$field_name', 'rows' => '5', 'cols' => '80', 'value' => set_value('$field_name', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : '') ) )?>";
		$view .= "
".$form_input_delimiters[1];
		break;
						
	case('radio'):
                        
		$view .= '
		<?php // Change or Add the radio values/labels/css classes to suit your needs ?>
		<input id="'.$field_name.'" name="'.$field_name.'" type="radio" class="" value="option1" <?php echo set_radio(\''.$field_name.'\', \'option1\', TRUE); ?> />
		'. form_label('Radio option 1', $field_name) .'

		<input id="'.$field_name.'" name="'.$field_name.'" type="radio" class="" value="option2" <?php echo set_radio(\''.$field_name.'\', \'option2\'); ?> />
		'. form_label('Radio option 2', $field_name) .'
'.$form_input_delimiters[1].'

';
		break;                        

	case('select'):
	// decided to use ci form helper here as I think it makes selects/dropdowns a lot easier
		$select_options = array();
		if (set_value("db_field_length_value$counter") != NULL)
		{
			$select_options = explode(',', set_value("db_field_length_value$counter"));
		}
		$view .= '

        <?php // Change the values in this array to populate your dropdown as required ?>
        
';
	 $view .= '<?php $options = array(';
		foreach( $select_options as $key => $option)
		{
			$view .= '
				'.strip_slashes($option).' => '.strip_slashes($option).',';
		}
	 $view .= '
); ?>

        <?php echo form_dropdown(\''.$field_name.'\', $options, set_value(\''.$field_name.'\', isset($'.$module_name_lower.'[\''.$field_name.'\']) ? $'.$module_name_lower.'[\''.$field_name.'\'] : \'\'))?>
'.$form_input_delimiters[1].'                                             
                        ';
		break;
                        
	case('checkbox'):

	$view .= <<<EOT

        <?php // Change the values/css classes to suit your needs ?>
	    <?php echo form_label('{$field_label}', '{$field_name}'); ?>               
        <input type="checkbox" id="{$field_name}" name="{$field_name}" value="1" <?php echo (isset(\${$module_name_lower}['{$field_name}']) && \${$module_name_lower}['{$field_name}'] == 1) ? 'checked="checked"' : set_checkbox('{$field_name}', 1); ?>> 
	
{$form_input_delimiters[1]} 
EOT;
		break;

	case('input'):
	case('password'):
	default: // input.. added bit of error detection setting select as default
						
		if ($field_type == 'input')
		{
			$type = 'text';
		}
		else
		{
			$type = 'password';
		}
		if (set_value("db_field_length_value$counter") != NULL)
		{
			$maxlength = 'maxlength="'.set_value("db_field_length_value$counter").'"';
		}
		$db_field_type = set_value("db_field_type$counter");
		if ($db_field_type != NULL)
		{
			if ($db_field_type == 'DATE')
			{
				$view .= '
			<script>head.ready(function(){$(\'#'.$field_name.'\').datepicker({ dateFormat: \'yy-mm-dd\'});});</script>';
			}
			elseif ($db_field_type == 'DATETIME')
			{
				$view .= '
			<script>head.ready(function(){$(\'#'.$field_name.'\').datetimepicker({ dateFormat: \'yy-mm-dd\', timeFormat: \'hh:mm:ss\'});});</script>';
			}
		}

		$view .= <<<EOT

        <input id="{$field_name}" type="{$type}" name="{$field_name}" {$maxlength} value="<?php echo set_value('{$field_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''); ?>"  />
{$form_input_delimiters[1]}

EOT;

			break;

	} // end switch


} // end for loop
if (!empty($on_click))
{
	$on_click .= '"';
}
$view .= '


	<div class="text-right">
		<br/>
		<input type="submit" name="submit" value="'.$action_label.' '.$module_name.'"'.$on_click.' /> or <?php echo anchor(SITE_AREA .\'/'.$controller_name.'/'.$module_name_lower.'\', lang(\''.$module_name_lower.'_cancel\')); ?>
	</div>
	<?php echo form_close(); ?>
';
if ($xinha_names != '')
{
	$view .= '				
				<script type="text/javascript"> 

				var xinha_plugins =
				[
				 \'Linker\'
				];
				var xinha_editors =
				[
				  '.$xinha_names.'
				];

				function xinha_init()
				{
				  if(!Xinha.loadPlugins(xinha_plugins, xinha_init)) return;

				  var xinha_config = new Xinha.Config();

				  xinha_editors = Xinha.makeEditors(xinha_editors, xinha_config, xinha_plugins);

				  Xinha.startEditors(xinha_editors);
				}
				xinha_init();
				</script>';
}
if($action_name != 'create') {
$view .= '
	<div class="box delete rounded">
		<a class="button" id="delete-me" href="<?php echo site_url(SITE_AREA .\'/'.$controller_name.'/'.$module_name_lower.'/delete/\'. $id); ?>" onclick="return confirm(\'<?php echo lang(\''.$module_name_lower.'_delete_confirm\'); ?>\')"><?php echo lang(\''.$module_name_lower.'_delete_record\'); ?></a>
		
		<h3><?php echo lang(\''.$module_name_lower.'_delete_record\'); ?></h3>
		
		<p><?php echo lang(\''.$module_name_lower.'_edit_text\'); ?></p>
	</div>
';
}


echo $view;
?>
