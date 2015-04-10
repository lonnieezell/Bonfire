<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (Russian)
 *
 * @package   Bonfire\Modules\Builder\Language\Russian
 * @author    Bonfire Dev Team
 * @author    Translator < https://github.com/nkondrashov >
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com/docs/builder
*/

// INDEX page
$lang['mb_delete_confirm']      = 'Точно удалить модуль и все его файлы?';
$lang['mb_create_button']       = 'Создать модуль';
$lang['mb_create_link']         = 'Создать новый модуль';
$lang['mb_create_note']         = 'Используйте наш генератор модулей что бы создавать свои модули. Мы делаем всю нудную работу генерируя все необходимые файлы (Контролеры, папки, модели, представления и языковые файлы).';
$lang['mb_not_writable_note']   = 'Ошибка: папка "application/modules" не доступна для записи. Пожалуйста, дайте папке права на запись и обновите страницу.';
$lang['mb_generic_description'] = 'Ваше описание тут';
$lang['mb_installed_head']      = 'Установленные модули';
$lang['mb_module']              = 'Модуль';
$lang['mb_no_modules']          = 'Модулей не установленно.';
$lang['mb_toolbar_title_index'] = 'Управление модулями';

$lang['mb_table_name']          = 'Название';
$lang['mb_table_version']       = 'Версия';
$lang['mb_table_author']        = 'Автор';
$lang['mb_table_description']   = 'Описание';

// OUTPUT page
$lang['mb_out_success']         = 'Создание модуля завершено! Ниже вы можете увидеть все созданные файлы (Controller, Model, Language, Migration и View). Модель и SQL файлы были включены если вы выбрали опцию "Создать миграции" и Javascript файл если он требовался во время создания миграции.';
$lang['mb_out_success_note']    = 'ПРИМЕЧАНИЕ: Пожалуйста, добавьте дополнительные проверки для полей этот код только для начала.';
$lang['mb_out_tables_success']  = 'Таблицы автоматически созданы в базе данных. Вы можете их проверить или удалить из раздела %s .';
$lang['mb_out_tables_error']    = 'Таблицы автоматически <strong>НЕ</strong> созданы в базе данных. Вы можете пройти в раздел %s и выполнить миграции перед начало работы с ними.';
$lang['mb_out_acl']             = 'Файл котнроля доступа';
$lang['mb_out_acl_path']        = 'migrations/001_Install_%s_permissions.php';
$lang['mb_out_config']          = 'Файл конфигурации';
$lang['mb_out_config_path']     = 'config/config.php';
$lang['mb_out_controller']      = 'Файлы контроллеров';
$lang['mb_out_controller_path'] = 'controllers/%s.php';
$lang['mb_out_model']           = 'Файлы модели';
$lang['mb_out_model_path']      = '%s_model.php';
$lang['mb_out_view']            = 'Файлы представлений';
$lang['mb_out_view_path']       = 'views/%s.php';
$lang['mb_out_lang']            = 'Языковые файлы';
$lang['mb_out_lang_path']       = '%s_lang.php';
$lang['mb_out_migration']       = 'Файлы миграций';
$lang['mb_out_migration_path']  = 'migrations/002_Install_%s.php';
$lang['mb_new_module']          = 'Новый модуль';
$lang['mb_exist_modules']       = 'Созданные модули';

// FORM page
$lang['mb_form_note']                  = '<p><b>Создайте и заполните все поля, которые необходимы для вашего модуля (поле "id" создается автоматически).  Если вы хотите создать SQL для БД установите флаг на "Создать таблицу для модуля" </b></p><p>Эта форма создаст полноценный модуль CodeIgniter (model, controller and views) и, если выберете, миграции для базы данных.</p>';
$lang['mb_table_note']                 = '<p>Ваш таблица будет создана, по крайней мере из одного поля, поля первичного ключа, который будет использоваться в качестве уникального идентификатора и в виде индекса. Если вам требуется дополнительные поля, нажмите "Добавить поля", чтобы добавить их.</p>';
$lang['mb_field_note']                 = '<p><b>ПРИМЕЧАНИЕ: ДЛЯ ВСЕХ ПОЛЕЙ</b><br />Если тип полей БД "enum" или "set", пожалуйста вводите значения в формате: \'a\',\'b\',\'c\'...<br />Если надо бэкфлеш ("\\") или одиночную кавычку ("\'") поставьте перед ними обратный слеш, например так: ( \'\\\\xyz\' or \'a\\\'b\').</p>';

$lang['mb_form_errors']                = 'Необходимо исправить ошибки.';
$lang['mb_form_mod_details']           = 'Детали модуля';
$lang['mb_form_mod_name']              = 'Название модуля';
$lang['mb_form_mod_name_ph']           = 'Форум, Блог, Тикеты';
$lang['mb_form_mod_desc']              = 'Описание модуля';
$lang['mb_form_mod_desc_ph']           = 'Описание вашего модуля';
$lang['mb_form_contexts']              = 'Включить в контексты';
$lang['mb_form_public']                = 'Публичный';
$lang['mb_form_table_details']         = 'Детали таблицы';
$lang['mb_form_actions']               = 'Controller Actions';
$lang['mb_form_actions_index']         = 'Список (List)';
$lang['mb_form_actions_create']        = 'Создать (Create)';
$lang['mb_form_actions_edit']          = 'Редактировать (Edit)';
$lang['mb_form_actions_delete']        = 'Удалить (Delete)';
$lang['mb_form_primarykey']            = 'Primary Key';
$lang['mb_form_delims']                = 'Разделители формы ввода';
$lang['mb_form_err_delims']            = 'Разделители ошибок формы';
$lang['mb_form_text_ed']               = 'Визуальный редактор для textarea';
$lang['mb_form_soft_deletes']          = 'Использовать "Soft" удаление?';
$lang['mb_form_use_created']           = 'Использовать поле "Created"?';
$lang['mb_form_use_modified']          = 'Использовать поле "Modified"?';
$lang['mb_form_created_field']         = 'Имя поля "Created"';
$lang['mb_form_created_field_ph']      = 'created_on';
$lang['mb_form_modified_field']        = 'Имя поля "Modified"';
$lang['mb_form_modified_field_ph']     = 'modified_on';
$lang['mb_form_generate']              = 'Создать таблицу для модуля';
$lang['mb_form_role_id']               = 'Роль с полным доступом';
$lang['mb_form_fieldnum']              = 'Добавить поля';
$lang['mb_form_field_details']         = 'Детали поля';
$lang['mb_form_table_name']            = 'Название талицы';
$lang['mb_form_table_name_ph']         = 'В нижнем регистре, без пробелов';
$lang['mb_form_table_as_field_prefix'] = 'Использовать имя талицы как префикс для имен полей';
$lang['mb_form_label']                 = 'Название';
$lang['mb_form_label_ph']              = 'Для таблиц, форм и т.д.';
$lang['mb_form_fieldname']             = 'Имя';
$lang['mb_form_fieldname_ph']          = 'Имя поля для бд';
$lang['mb_form_fieldname_help']        = 'В нижнем регистре, без пробелов';
$lang['mb_form_type']                  = 'Тип поля (в форме HTML)';
$lang['mb_form_length']                = 'Максимальная длина <b>-или-</b> Значения';
$lang['mb_form_length_ph']             = '30, 255, 1000, и т.д...';
$lang['mb_form_dbtype']                = 'Тип поля в БД';
$lang['mb_form_rules']                 = 'Правила для прверки';
$lang['mb_form_rules_limits']          = 'Ограничения поля';
$lang['mb_form_required']              = 'Обязательное';
$lang['mb_form_unique']                = 'Уникальное';
$lang['mb_form_trim']                  = 'Trim';
$lang['mb_form_valid_email']           = 'Проверять на соответствие E-Mail адресу';
$lang['mb_form_is_numeric']            = '0-9';
$lang['mb_form_alpha']                 = 'a-Z';
$lang['mb_form_alpha_dash']            = 'a-Z, 0-9, and _-';
$lang['mb_form_alpha_numeric']         = 'a-Z and 0-9';
$lang['mb_form_add_fld_button']        = 'Добавить другое поле';
$lang['mb_form_show_advanced']         = 'Развернуть больше опций';
$lang['mb_form_show_more']             = '...развернуть больше правил...';
$lang['mb_form_integer']               = 'Цифры';
$lang['mb_form_is_decimal']            = 'Десятичные дроби';
$lang['mb_form_is_natural']            = 'Множество натуральных чисел';
$lang['mb_form_is_natural_no_zero']    = 'Натуральные, без нулей';
$lang['mb_form_valid_ip']              = 'Проверять как IP';
$lang['mb_form_valid_base64']          = 'Проверять как Base64';
$lang['mb_form_alpha_extra']           = 'Латинские буквы и цифры, подчеркивание, тире, точки и пробелы.';
$lang['mb_form_match_existing']        = 'Введите значения!';
$lang['mb_form_module_db_no']          = 'Нет';
$lang['mb_form_module_db_create']      = 'Создать новую таблицу';
$lang['mb_form_module_db_exists']      = 'Создать на основе существующей таблицы';
$lang['mb_form_build']                 = 'Создать модуль';
$lang['mb_form_none_of_the_above']     = 'Ничего из этого';

// Activities
$lang['mb_act_create'] = 'Созданные модули';
$lang['mb_act_delete'] = 'Удаленные модули';

// Create Context
$lang['mb_create_a_context']       = 'Создать контекст';
$lang['mb_tools']                  = 'Инструменты';
$lang['mb_mod_builder']            = 'Генератор модулей';
$lang['mb_new_context']            = 'Новый контекст';
$lang['mb_no_context_name']        = 'Не коректное имя для контекста.';
$lang['mb_cant_write_config']      = 'Невозможна запись в файл конфигурации.';
$lang['mb_context_exists']         = 'Сонтекст уже существует.';
$lang['mb_context_name']           = 'Имя контекста';
$lang['mb_context_name_help']      = 'Без пробелов.';
$lang['mb_context_create_success'] = 'Сонтекст успешно создан.';
$lang['mb_context_create_error']   = 'Ошибка создания контекста: ';
$lang['mb_context_create_intro']   = 'Создание и настройка нового контекста.';
$lang['mb_roles_label']            = 'Доступен для ролей:';
$lang['mb_context_migrate']        = 'Создать как миграцию для приложения?';
$lang['mb_context_submit']         = 'Создать';

// Create Module
$lang['mb_module_table_not_exist'] = 'Таблица не найдена';
$lang['mb_toolbar_title_create']   = 'Генератор модулей';

// Delete Module
$lang['mb_delete_trans_false']     = 'Мы не можем удалить этот модуль.';
$lang['mb_delete_success']         = 'Модуль и связанные с ним данные в БД успешно удалены.';
$lang['mb_delete_success_db_only'] = ' не могут быть удалены автоматически, удалите руками.';

// Validate Form
$lang['mb_contexts_content']              = 'Contexts :: Content';
$lang['mb_contexts_developer']            = 'Contexts :: Developer';
$lang['mb_contexts_public']               = 'Contexts :: Public';
$lang['mb_contexts_reports']              = 'Contexts :: Reports';
$lang['mb_contexts_settings']             = 'Contexts :: Settings';
$lang['mb_module_db']                     = 'Таблица модуля';
$lang['mb_form_action_create']            = 'Form Actions :: Create';
$lang['mb_form_action_delete']            = 'Form Actions :: Delete';
$lang['mb_form_action_edit']              = 'Form Actions :: Edit';
$lang['mb_form_action_view']              = 'Form Actions :: List';
$lang['mb_soft_delete_field']             = 'Имя поля "Soft" удаления';
$lang['mb_soft_delete_field_ph']          = 'deleted';
$lang['mb_validation_no_match']           = '%s %ss (%s & %s) должны быть уникальными!';
$lang['mb_modulename_check']              = 'Поля %s не корректны';
$lang['mb_modulename_check_class_exists'] = 'Поле %s не корректно: имя модуля совпадает с именем существующего класса.';

$lang['mb_form_log_user']                 = 'Логирование';
$lang['mb_deleted_by_field']              = 'Имя поля "Deleted By"';
$lang['mb_deleted_by_field_ph']           = 'deleted_by';
$lang['mb_form_created_by_field']         = 'Имя поля "Created By"';
$lang['mb_form_created_by_field_ph']      = 'created_by';
$lang['mb_form_modified_by_field']        = 'Имя поля "Modified By"';
$lang['mb_form_modified_by_field_ph']     = 'modified_by';
$lang['mb_form_use_pagination']           = 'Использовать библиотеку для создания пагинации';
