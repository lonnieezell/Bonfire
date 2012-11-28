<?php

$view = '';
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
					if( !(\''.$field_name.'\' in CKEDITOR.instances)) {
						CKEDITOR.replace( \''.$field_name.'\' );
					}
';
			}
			elseif ($textarea_editor == 'xinha') {
				//
				if ($xinha_names != '')
				{
					$xinha_names .= ', ';
				}
				$xinha_names .= '\''.$field_name.'\'';

			}
			elseif ($textarea_editor == 'markitup') {
				$view .= '$("#' . $field_name . '").markItUp(mySettings);' . PHP_EOL;
			}
		}
		break;

	case('input'):
	case('password'):
	default: // input.. added bit of error detection setting select as default

		$db_field_type = set_value("db_field_type$counter");
		if ($db_field_type != NULL)
		{
			if ($db_field_type == 'DATE')
			{
				$view .= '$(\'#'.$field_name.'\').datepicker({ dateFormat: \'yy-mm-dd\'});' . PHP_EOL;
			}
			elseif ($db_field_type == 'DATETIME')
			{
				$view .= '$(\'#'.$field_name.'\').datetimepicker({ dateFormat: \'yy-mm-dd\', timeFormat: \'hh:mm:ss\'});' . PHP_EOL;
			}
		}
		break;

	} // end switch


} // end for loop


if ($xinha_names != '')
{
	$view .= '
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
';
}

echo $view;
