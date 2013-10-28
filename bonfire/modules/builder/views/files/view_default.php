<?php

$view = '<?php

$validation_errors = validation_errors();

if ($validation_errors) :
?>
<div class="alert alert-block alert-error fade in">
	<a class="close" data-dismiss="alert">&times;</a>
	<h4 class="alert-heading">Please fix the following errors:</h4>
	<?php echo $validation_errors; ?>
</div>
<?php
endif;

if (isset($' . $module_name_lower . '))
{
	$' . $module_name_lower . ' = (array) $' . $module_name_lower . ';
}
$id = isset($' . $module_name_lower . '[\'' . $primary_key_field . '\']) ? $' . $module_name_lower . '[\'' . $primary_key_field . '\'] : \'\';

?>
<div class="admin-box">
	<h3>' . $module_name . '</h3>
	<?php echo form_open($this->uri->uri_string(), \'class="form-horizontal"\'); ?>
		<fieldset>';

$on_click = '';
$xinha_names = '';

for ($counter = 1; $field_total >= $counter; $counter++)
{
	$maxlength = NULL; // reset this variable

	// only build on fields that have data entered.
	//Due to the requiredif rule if the first field is set the the others must be

	if (set_value("view_field_label$counter") == NULL)
	{
		continue;   // move onto next iteration of the loop
	}

	$field_label = set_value("view_field_label$counter");
	$form_name  = $module_name_lower . '_' . set_value("view_field_name$counter");
	$field_name = set_value("view_field_name$counter");
	$field_type = set_value("view_field_type$counter");

	$validation_rules = $this->input->post('validation_rules'.$counter);

	$required = '';
	if (is_array($validation_rules))
	{
		// rules have been selected for this fieldset

		foreach ($validation_rules as $key => $value)
		{
			if ($value == 'required')
			{
				$required = ". lang('bf_form_label_required')"; //' <span class="required">*</span>';
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

			if ( ! empty($textarea_editor))
			{
				// Setup the editor for textareas
				if ($textarea_editor == 'xinha')
				{
					if ($xinha_names != '')
					{
						$xinha_names .= ', ';
					}
					$xinha_names .= '\'' . $field_name . '\'';
				}
			}

			$view .= PHP_EOL . "
			<div class=\"control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<?php echo form_textarea( array( 'name' => '{$form_name}', 'id' => '{$form_name}', 'rows' => '5', 'cols' => '80', 'value' => set_value('$form_name', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : '') ) ); ?>
					<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
				</div>
			</div>";

			break;

		case('radio'):

			$view .= PHP_EOL . "
			<div class=\"control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '', array('class' => 'control-label', 'id' => '{$form_name}_label') ); ?>
				<div class='controls' aria-labelled-by='{$form_name}_label'>
					<label class='radio' for='{$form_name}_option1'>
						<input id='{$form_name}_option1' name='{$form_name}' type='radio' class='' value='option1' <?php echo set_radio('{$form_name}', 'option1', TRUE); ?> />
						Radio option 1
					</label>
					<label class='radio' for='{$form_name}_option2'>
						<input id='{$form_name}_option2' name='{$form_name}' type='radio' class='' value='option2' <?php echo set_radio('{$form_name}', 'option2'); ?> />
						Radio option 2
					</label>
					<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
				</div>
			</div>";

			break;

		case('select'):

			// decided to use ci form helper here as I think it makes selects/dropdowns a lot easier
			$select_options = array();
			if (set_value("db_field_length_value$counter") != NULL)
			{
				$select_options = explode(',', set_value("db_field_length_value$counter"));
			}
			$view .= PHP_EOL . '
			<?php // Change the values in this array to populate your dropdown as required
				$options = array(';

			foreach ($select_options as $key => $option)
			{
				$view .= '
					' . strip_slashes($option) . ' => ' . strip_slashes($option) . ',';
			}

			$view .= "
				);

				echo form_dropdown('{$form_name}', \$options, set_value('{$form_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''), '{$field_label}'{$required});
			?>";

			break;

		case('checkbox'):

			$view .= PHP_EOL . "
			<div class=\"control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<label class='checkbox' for='{$form_name}'>
						<input type='checkbox' id='{$form_name}' name='{$form_name}' value='1' <?php echo (isset(\${$module_name_lower}['{$field_name}']) && \${$module_name_lower}['{$field_name}'] == 1) ? 'checked=\"checked\"' : set_checkbox('{$form_name}', 1); ?>>
						<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
					</label>
				</div>
			</div>";

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
				$maxlength = 'maxlength="' . set_value("db_field_length_value$counter") . '"';

				if (set_value("db_field_type$counter") == 'DECIMAL' || set_value("db_field_type$counter") == 'FLOAT' || set_value("db_field_type$counter") == 'DOUBLE')
				{
					list($len, $decimal) = explode(",", set_value("db_field_length_value$counter"));
					$max = $len;

					if (isset($decimal) && $decimal != 0)
					{
						$max = $len + 1; // Add 1 to allow for the decimal
					}

					$maxlength = 'maxlength="' . $max . '"';
				}
			}
			$db_field_type = set_value("db_field_type$counter");

			$view .= PHP_EOL . "
			<div class=\"control-group <?php echo form_error('{$field_name}') ? 'error' : ''; ?>\">
				<?php echo form_label('{$field_label}'{$required}, '{$form_name}', array('class' => 'control-label') ); ?>
				<div class='controls'>
					<input id='{$form_name}' type='{$type}' name='{$form_name}' {$maxlength} value=\"<?php echo set_value('{$form_name}', isset(\${$module_name_lower}['{$field_name}']) ? \${$module_name_lower}['{$field_name}'] : ''); ?>\" />
					<span class='help-inline'><?php echo form_error('{$field_name}'); ?></span>
				</div>
			</div>";

			break;

	} // end switch
} // end for loop

if ( ! empty($on_click))
{
	$on_click .= '"';
}

$delete = '';

if ($action_name != 'create')
{
	$delete_permission = preg_replace("/[ -]/", "_", ucfirst($module_name)).'.'.ucfirst($controller_name).'.Delete';

	$delete = '
			<?php if ($this->auth->has_permission(\'' . $delete_permission . '\')) : ?>
				or
				<button type="submit" name="delete" class="btn btn-danger" id="delete-me" onclick="return confirm(\'<?php e(js_escape(lang(\''.$module_name_lower.'_delete_confirm\'))); ?>\'); ">
					<span class="icon-trash icon-white"></span>&nbsp;<?php echo lang(\'' . $module_name_lower . '_delete_record\'); ?>
				</button>
			<?php endif; ?>';

}

$view .= PHP_EOL . '
			<div class="form-actions">
				<input type="submit" name="save" class="btn btn-primary" value="<?php echo lang(\''.$module_name_lower.'_action_'.$action_name.'\'); ?>" '.$on_click.' />
				<?php echo lang(\'bf_or\'); ?>
				<?php echo anchor(SITE_AREA .\'/' . $controller_name . '/' . $module_name_lower . '\', lang(\'' . $module_name_lower . '_cancel\'), \'class="btn btn-warning"\'); ?>
				' . $delete . '
			</div>
		</fieldset>
    <?php echo form_close(); ?>';

if ($xinha_names != '')
{
	$view .= PHP_EOL . '
	<script type="text/javascript">
		var xinha_plugins = [ \'Linker\' ],
			xinha_editors = [ ' . $xinha_names . ' ];

		function xinha_init() {
			if ( ! Xinha.loadPlugins(xinha_plugins, xinha_init)) {
				return;
			}

			var xinha_config = new Xinha.Config();

			xinha_editors = Xinha.makeEditors(xinha_editors, xinha_config, xinha_plugins);
			Xinha.startEditors(xinha_editors);
		}
		xinha_init();
	</script>';

}

$view .= '
</div>';

echo $view;
