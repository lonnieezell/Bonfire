<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Roles language file (Russian)
 *
 * Localization strings used by Bonfire
 *
 * @package Bonfire\Modules\Roles\Language\Russian
 * @author  Translator < https://github.com/cjmaxik >
 * @link    http://cibonfire.com/docs/bonfire/roles_and_permissions
 */

$lang['role_intro']         = 'Роли позволяют распределить права пользователей.';
$lang['role_manage']        = 'Менеджер ролей';
$lang['role_no_roles']      = 'В системе нет ролей.';
$lang['role_create_button'] = 'Создать новую роль.';
$lang['role_create_note']   = 'Каждый пользователь нуждается в роли. Убедитесь, что у вас есть все, что вам нужно.';
$lang['role_account_type']  = 'Тип аккаунта';
$lang['role_description']   = 'Описание';
$lang['role_details']       = 'Детали роли';

$lang['role_name']                   = 'Имя роли';
$lang['role_max_desc_length']        = 'до 255 символов';
$lang['role_default_role']           = 'Роль по умолчанию';
$lang['role_default_note']           = 'Данная роль должна быть применена ко всем новым пользователям';
$lang['role_permissions']            = 'Разрешения';
$lang['role_permissions_check_note'] = 'Выберете нужные данной роли разрешения';
$lang['role_save_role']              = 'Сохранить роль';
$lang['role_delete_role']            = 'Удалить данную роль';
$lang['role_delete_confirm']         = 'Вы действительно хотите удалить данную роль?';
$lang['role_delete_note']            = 'Удаление данной роли автоматически переведет пользователей с этой ролью на роль по умолчанию.';
$lang['role_can_delete_role']        = 'Удаляемая';
$lang['role_can_delete_note']        = 'Можно ли эту роль удалить?';

$lang['role_roles']                  = 'Роли';
$lang['role_new_role']               = 'Новая роль';
$lang['role_new_permission_message'] = 'Вы сможете выбрать права после создания данной роли.';
$lang['role_not_used']               = 'Не используется';

$lang['role_login_destination']    = 'Страница после входа';
$lang['role_destination_note']     = 'URL, на который будет произведен переход после успешного входа.';
$lang['role_default_context']      = 'Контекст по умолчанию';
$lang['role_default_context_note'] = 'Какой контекст загружать, когда нет искомого контекста';

$lang['matrix_header']         = 'Матрица прав';
$lang['matrix_permission']     = 'Права';
$lang['matrix_role']           = 'Роль';
$lang['matrix_note']           = 'Права изменяются мгновенно. Переключите чекбокс, чтобы добавить или удалить нужные разрешения данной роли.';
$lang['matrix_insert_success'] = 'Права были добавлены к данной роли.';
$lang['matrix_insert_fail']    = 'Возникла проблема при добавлении данных прав к роли:';
$lang['matrix_delete_success'] = 'Данные права были удалены.';
$lang['matrix_delete_fail']    = 'Возникла проблема при удалении прав:';
$lang['matrix_auth_fail']      = 'Аутентификация: У вас нет прав контролировать доступ данной роли.';

$lang['form_validation_role_name'] = 'Имя роли';
$lang['form_validation_role_login_destination'] = 'Страница после входа';
$lang['form_validation_role_default_context']   = 'Контекст по умолчанию';
$lang['form_validation_role_default_role']      = 'Роль по умолчанию';
$lang['form_validation_role_can_delete_role']   = 'Удаляемая';
