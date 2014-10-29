<?php

$lang = '<?php defined(\'BASEPATH\') || exit(\'No direct script access allowed\');';

$lang .= PHP_EOL . '
$lang[\''.$module_name_lower.'_manage\']      = \'Gerenciar '.$module_name.'\';
$lang[\''.$module_name_lower.'_edit\']        = \'Editar\';
$lang[\''.$module_name_lower.'_true\']        = \'Verdadeiro\';
$lang[\''.$module_name_lower.'_false\']       = \'Falso\';
$lang[\''.$module_name_lower.'_create\']      = \'Criar\';
$lang[\''.$module_name_lower.'_list\']        = \'Listar\';
$lang[\''.$module_name_lower.'_new\']       = \'Novo\';
$lang[\''.$module_name_lower.'_edit_text\']     = \'Edite isto conforme sua necessidade\';
$lang[\''.$module_name_lower.'_no_records\']    = \'Não há '.$module_name_lower.' no sistema.\';
$lang[\''.$module_name_lower.'_create_new\']    = \'Criar novo(a) '.$module_name.'.\';
$lang[\''.$module_name_lower.'_create_success\']  = \''.$module_name.' Criado(a) com sucesso.\';
$lang[\''.$module_name_lower.'_create_failure\']  = \'Ocorreu um problema criando o(a) '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_create_new_button\'] = \'Criar novo(a) '.$module_name.'\';
$lang[\''.$module_name_lower.'_invalid_id\']    = \'ID de '.$module_name.' inválida.\';
$lang[\''.$module_name_lower.'_edit_success\']    = \''.$module_name.' salvo(a) com sucesso.\';
$lang[\''.$module_name_lower.'_edit_failure\']    = \'Ocorreu um problema salvando o(a) '.$module_name_lower.': \';
$lang[\''.$module_name_lower.'_delete_success\']  = \'Registro(s) excluído(s) com sucesso.\';
$lang[\''.$module_name_lower.'_delete_failure\']  = \'Não foi possível excluir o registro: \';
$lang[\''.$module_name_lower.'_delete_error\']    = \'Voc6e não selecionou nenhum registro para excluir.\';
$lang[\''.$module_name_lower.'_actions\']     = \'Ações\';
$lang[\''.$module_name_lower.'_cancel\']      = \'Cancelar\';
$lang[\''.$module_name_lower.'_delete_record\']   = \'Excluir este(a) '.$module_name.'\';
$lang[\''.$module_name_lower.'_delete_confirm\']  = \'Você tem certeza que deseja excluir este(a) '.$module_name_lower.'?\';
$lang[\''.$module_name_lower.'_edit_heading\']    = \'Editar '.$module_name.'\';

// Create/Edit Buttons
$lang[\''.$module_name_lower.'_action_edit\']   = \'Salvar '.$module_name.'\';
$lang[\''.$module_name_lower.'_action_create\']   = \'Criar '.$module_name.'\';

// Activities
$lang[\''.$module_name_lower.'_act_create_record\'] = \'Criado registro com ID\';
$lang[\''.$module_name_lower.'_act_edit_record\'] = \'Atualizado registro com ID\';
$lang[\''.$module_name_lower.'_act_delete_record\'] = \'Excluído registro com ID\';

//Listing Specifics
$lang[\''.$module_name_lower.'_records_empty\']    = \'Nenhum registro encontrado.\';
$lang[\''.$module_name_lower.'_errors_message\']    = \'Por favor corrija os erros a seguir:\';

// Column Headings
$lang[\''.$module_name_lower.'_column_created\']  = \'Criado\';
$lang[\''.$module_name_lower.'_column_deleted\']  = \'Excluído\';
$lang[\''.$module_name_lower.'_column_modified\'] = \'Atualizado\';

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