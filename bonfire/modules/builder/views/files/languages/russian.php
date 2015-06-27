<?php

/**
 * Application language file for generation (Russian).
 *
 * Localization strings used by Bonfire.
 *
 * @package Bonfire\Builder\Views\Language\Russian
 * @author  Translator < https://github.com/nkondrashov >
 */

$fieldEntries = '';
for ($counter = 1; $field_total >= $counter; $counter++) {
    if (set_value("view_field_label$counter") == null) {
        continue; // Move onto next iteration of the loop
    }

    $field_label = set_value("view_field_label$counter");
    $field_name  = set_value("view_field_name$counter");

    $fieldEntries .= "
\$lang['{$module_name_lower}_field_{$field_name}'] = '{$field_label}';";
}

echo $lang = "<?php defined('BASEPATH') || exit('No direct script access allowed');
" . PHP_EOL . "
\$lang['{$module_name_lower}_manage']            = 'Управление записями {$module_name}';
\$lang['{$module_name_lower}_edit']              = 'Рекдактировать';
\$lang['{$module_name_lower}_true']              = 'Да';
\$lang['{$module_name_lower}_false']             = 'Нет';
\$lang['{$module_name_lower}_create']            = 'Создать';
\$lang['{$module_name_lower}_list']              = 'Список';
\$lang['{$module_name_lower}_new']               = 'Новый';
\$lang['{$module_name_lower}_edit_text']         = 'Редактировать в соответствии с вашими потребностями';
\$lang['{$module_name_lower}_no_records']        = 'Нет такой записи в {$module_name_lower}.';
\$lang['{$module_name_lower}_create_new']        = 'Создать новую запись в {$module_name}.';
\$lang['{$module_name_lower}_create_success']    = 'Запись успешно создана в {$module_name}';
\$lang['{$module_name_lower}_create_failure']    = 'Возникли проблемы при создании записи в {$module_name_lower}: ';
\$lang['{$module_name_lower}_create_new_button'] = 'Создать';
\$lang['{$module_name_lower}_invalid_id']        = 'Не корректный ID для {$module_name}';
\$lang['{$module_name_lower}_edit_success']      = 'Изменения в {$module_name} успешно сохранены.';
\$lang['{$module_name_lower}_edit_failure']      = 'Возникли проблемы при редактировании записи из {$module_name_lower}: ';
\$lang['{$module_name_lower}_delete_success']    = 'Запись(и) успешно удалены.';
\$lang['{$module_name_lower}_delete_failure']    = 'Невозможно удалить запись: ';
\$lang['{$module_name_lower}_delete_error']      = 'Вы не выбрали что удалять.';
\$lang['{$module_name_lower}_actions']           = 'Действия';
\$lang['{$module_name_lower}_cancel']            = 'Отмена';
\$lang['{$module_name_lower}_delete_record']     = 'Удалить эту запись из {$module_name}';
\$lang['{$module_name_lower}_delete_confirm']    = 'Вы уверены что хотите удалить эту запись из {$module_name_lower}?';
\$lang['{$module_name_lower}_edit_heading']      = 'Редактировать {$module_name}';

// Create/Edit Buttons
\$lang['{$module_name_lower}_action_edit']   = 'Сохранить запись из {$module_name}';
\$lang['{$module_name_lower}_action_create'] = 'Создать новую запись в {$module_name}.';

// Activities
\$lang['{$module_name_lower}_act_create_record'] = 'Создана запись с ID';
\$lang['{$module_name_lower}_act_edit_record']   = 'Обновлена запись с ID';
\$lang['{$module_name_lower}_act_delete_record'] = 'Удалена запись с ID';

//Listing Specifics
\$lang['{$module_name_lower}_records_empty']  = 'Не найдено записей по вашему запросу.';
\$lang['{$module_name_lower}_errors_message'] = 'Пожалуйста, устраните следующие проблемы:';

// Column Headings
\$lang['{$module_name_lower}_column_created']     = 'Созданно';
\$lang['{$module_name_lower}_column_deleted']     = 'Удалено';
\$lang['{$module_name_lower}_column_modified']    = 'Изменено';
\$lang['{$module_name_lower}_column_deleted_by']  = 'Удалил';
\$lang['{$module_name_lower}_column_created_by']  = 'Создал';
\$lang['{$module_name_lower}_column_modified_by'] = 'Изменил';

// Module Details
\$lang['{$module_name_lower}_module_name']        = '{$module_name}';
\$lang['{$module_name_lower}_module_description'] = '{$module_description}';
\$lang['{$module_name_lower}_area_title']         = '{$module_name}';

// Fields{$fieldEntries}";
