<?php

$lang = '<?php defined(\'BASEPATH\') || exit(\'No direct script access allowed\');';

$lang .= PHP_EOL . '
$lang[\''.$module_name_lower.'_manage\']      = \'Gestionar '.$module_name.'\';
$lang[\''.$module_name_lower.'_edit\']        = \'Editar\';
$lang[\''.$module_name_lower.'_true\']        = \'Verdadero\';
$lang[\''.$module_name_lower.'_false\']       = \'Falso\';
$lang[\''.$module_name_lower.'_create\']      = \'Crear\';
$lang[\''.$module_name_lower.'_list\']        = \'Listar\';
$lang[\''.$module_name_lower.'_new\']       = \'Nuevo\';
$lang[\''.$module_name_lower.'_edit_text\']     = \'Editar esto para satisfacer sus necesidades\';
$lang[\''.$module_name_lower.'_no_records\']    = \'Hay ninguna '.$module_name_lower.' en la sistema.\';
$lang[\''.$module_name_lower.'_create_new\']    = \'Crear nuevo(a) '.$module_name.'.\';
$lang[\''.$module_name_lower.'_create_success\']  = \''.$module_name.' creado(a) con éxito.\';
$lang[\''.$module_name_lower.'_create_failure\']  = \'Hubo un problema al crear el(la) '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_create_new_button\'] = \'Crear nuevo(a) '.$module_name.'\';
$lang[\''.$module_name_lower.'_invalid_id\']    = \'ID de '.$module_name.' inválido(a).\';
$lang[\''.$module_name_lower.'_edit_success\']    = \''.$module_name.' guardado correctamente.\';
$lang[\''.$module_name_lower.'_edit_failure\']    = \'Hubo un problema guardando el(la) '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_delete_success\']  = \'Registro(s) eliminado con éxito.\';
$lang[\''.$module_name_lower.'_delete_failure\']  = \'No hemos podido eliminar el registro: \';
$lang[\''.$module_name_lower.'_delete_error\']    = \'No ha seleccionado ning&#250;n registro que desea eliminar.\';
$lang[\''.$module_name_lower.'_actions\']     = \'Açciones\';
$lang[\''.$module_name_lower.'_cancel\']      = \'Cancelar\';
$lang[\''.$module_name_lower.'_delete_record\']   = \'Eliminar este(a) '.$module_name.'\';
$lang[\''.$module_name_lower.'_delete_confirm\']  = \'¿Esta seguro de que desea eliminar este(a) '.$module_name_lower.'?\';
$lang[\''.$module_name_lower.'_edit_heading\']    = \'Editar '.$module_name.'\';

// Create/Edit Buttons
$lang[\''.$module_name_lower.'_action_edit\']   = \'Guardar '.$module_name.'\';
$lang[\''.$module_name_lower.'_action_create\']   = \'Crear '.$module_name.'\';

// Activities
$lang[\''.$module_name_lower.'_act_create_record\'] = \'Creado registro con ID\';
$lang[\''.$module_name_lower.'_act_edit_record\'] = \'Actualizado registro con ID\';
$lang[\''.$module_name_lower.'_act_delete_record\'] = \'Eliminado registro con ID\';

//Listing Specifics
$lang[\''.$module_name_lower.'_records_empty\']    = \'No hay registros encontrados para su selección.\';
$lang[\''.$module_name_lower.'_errors_message\']    = \'Por favor corrija los siguientes errores:\';

// Column Headings
$lang[\''.$module_name_lower.'_column_created\']  = \'Creado\';
$lang[\''.$module_name_lower.'_column_deleted\']  = \'Elíminado\';
$lang[\''.$module_name_lower.'_column_modified\'] = \'Modificado\';

// Module Details
$lang[\''.$module_name_lower.'_module_name\'] = \''.$module_name.'\';
$lang[\''.$module_name_lower.'_module_description\'] = \''.$module_description.'\';
$lang[\''.$module_name_lower.'_area_title\'] = \''.$module_name.'\';

// Fields
';

for ($counter = 1; $field_total >= $counter; $counter++)
{
  if (set_value("view_field_label$counter") == NULL)
  {
    continue;   // move onto next iteration of the loop
  }

  $field_label = set_value("view_field_label$counter");
  $field_name  = set_value("view_field_name$counter");

  $lang .= '$lang[\''.$module_name_lower.'_field_'.$field_name.'\'] = \''.$field_label.'\';
';
}

echo $lang;
