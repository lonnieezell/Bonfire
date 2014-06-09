<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Users language file (Russian).
 *
 * Localization strings used by Bonfire.
 *
 * @package    Bonfire\Modules\Users\Language\Russian
 * @author     Translator < https://github.com/cjmaxik >
 * @link       http://cibonfire.com/docs/guides
 */

$lang['us_account_deleted'] = 'К сожалению, ваш аккаунт был заблокирован. Вы всё еще можете попытаться <strong>восстановить</strong> его. Для этого обратитесь к администратору %s.';

$lang['us_bad_email_pass']  = 'Неверный Email и/или пароль';
$lang['us_must_login']      = 'Вы должны войти для просмотра данной страницы.';
$lang['us_no_permission']   = 'У вас нет прав доступа к данной странице.';
$lang['us_fields_required'] = 'Поля %s и Пароль должны быть заполнены.';

$lang['us_access_logs']       = 'Логи доступа';
$lang['us_logged_in_on']      = '<b>%s</b> вошел на %s';
$lang['us_no_access_message'] = '<p>Поздравляем!</p><p>У ваших пользователей отличная память!</p>';
$lang['us_log_create']        = 'создает новый %s';
$lang['us_log_edit']          = 'имененный пользователь';
$lang['us_log_delete']        = 'удаленный пользователь';
$lang['us_log_logged']        = 'зашел на';
$lang['us_log_logged_out']    = 'вышел из';
$lang['us_log_reset']         = 'сбросил свой пароль';
$lang['us_log_register']      = 'создал новый аккаунт';
$lang['us_log_edit_profile']  = 'обновил свой профиль';

$lang['us_purge_del_confirm']      = 'Вы действительно хотите удалить данных пользователей? Назад дороги нет!';
$lang['us_action_purged']          = 'Пользователи удалены.';
$lang['us_action_deleted']         = 'Пользователь был успешно удален.';
$lang['us_action_not_deleted']     = 'Невозможно удалить пользователя:';
$lang['us_delete_account']         = 'Удалить аккаунт';
$lang['us_delete_account_note']    = '<h3>Удаление данного аккунта</h3><p>Удаление данного аккаунта повлечет за собой лишение всех привилегий на этом сайте.</p>';
$lang['us_delete_account_confirm'] = 'Вы действительно хотите удалить аккаунт(ы)?';

$lang['us_restore_account']         = 'Восстановить аккаунт';
$lang['us_restore_account_note']    = '<h3>Восстановление аккаунта</h3><p>Вернуть данный аккаунт.</p>';
$lang['us_restore_account_confirm'] = 'Восстановить данный аккаунт?';

$lang['us_role']             = 'Роль';
$lang['us_role_lower']       = 'роль';
$lang['us_no_users']         = 'Пользователи не найдены.';
$lang['us_create_user']      = 'Создать нового пользователя';
$lang['us_create_user_note'] = '<h3>Создание нового пользователя</h3><p>Создание нового аккаунта для других пользователей.</p>';
$lang['us_edit_user']        = 'Редактировать пользователя';
$lang['us_restore_note']     = 'Восстановить пользователя и снова разрешить ему доступ к сайту.';
$lang['us_unban_note']       = 'Снять блокировку с пользователя и дать доступ к сайту.';
$lang['us_account_status']   = 'Статус аккаунта';

$lang['us_failed_login_attempts'] = 'Неудачные попытки входа';
$lang['us_failed_logins_note']    = '<p>Поздравляем!</p><p>У ваших пользователей отличная память!</p>';

$lang['us_banned_admin_note'] = 'Данный пользователь заблокирован на этом сайте.';
$lang['us_banned_msg']        = 'Данному пользователю запрещен доступ к сайту.';

$lang['us_first_name']   = 'Имя';
$lang['us_last_name']    = 'Фамилия';
$lang['us_address']      = 'Адрес';
$lang['us_street_1']     = 'Улица, строка 1';
$lang['us_street_2']     = 'Улица, строка 2';
$lang['us_city']         = 'Город';
$lang['us_state']        = 'Штат/регион';
$lang['us_no_states']    = 'Для данной страны нет списка штатов/регионов. Создайте список в файле настроек адресов.';
$lang['us_no_countries'] = 'Страны не найдены. Проверьте файл настроек адресов.';
$lang['us_country']      = 'Страна';
$lang['us_zipcode']      = 'Индекс';

$lang['us_user_management'] = 'User Management';
$lang['us_email_in_use']    = 'Адрес %s уже используется. Пожалуйста, выберите другой.';

$lang['us_edit_profile'] = 'Редактировать профиль';
$lang['us_edit_note']    = 'Введите информацию ниже и нажмите Сохранить.';

$lang['us_reset_password'] = 'Восстановить пароль';
$lang['us_reset_note']     = 'Введите свой Email и мы отправим вам временный пароль.';
$lang['us_send_password']  = 'Отправить пароль';

$lang['us_login']                = 'Пожалуйста, войдите';
$lang['us_remember_note']        = 'Запомнить меня';
$lang['us_sign_up']              = 'Создать аккаунт';
$lang['us_forgot_your_password'] = 'Забыли свой пароль?';
$lang['us_let_me_in']            = 'Вход';

$lang['us_password_mins'] = 'Не менее 8 символов.';
$lang['us_register'] = 'Регистрация';
$lang['us_already_registered'] = 'Уже зарегистрированы?';

$lang['us_action_save']  = 'Сохранить пользователя';
$lang['us_unauthorized'] = 'Неавторизован. Извините, но вы не имеете нужных прав для редактирования роли "%s".';
$lang['us_empty_id']     = 'Нет ID пользователя. Вы должны предоставить ID для выполнения данного действия.';
$lang['us_self_delete']  = 'Неавторизован. Извините, но вы не можете удалить себя.';

$lang['us_filter_first_letter'] = 'Имя пользователя начинается с:';
$lang['us_account_details']     = 'Детали аккаунта';
$lang['us_last_login']          = 'Последний вход';

$lang['us_account_created_success'] = 'Ваш аккаунт был создан. Можете войти.';

$lang['us_invalid_user_id'] = 'Неверный ID пользователя.';
$lang['us_invalid_email']   = 'Данный Email не найден в наших записях.';

$lang['us_reset_password_note'] = 'Введите новый пароль ниже.';
$lang['us_reset_invalid_email'] = 'Похоже, данный запрос на смену пароля неверен.';
$lang['us_reset_pass_subject']  = 'Ваш временный пароль';
$lang['us_reset_pass_message']  = 'Пожалуйста, проверьте ваш почтовый ящик на предмет инструкций по смене пароля.';
$lang['us_reset_pass_error']    = 'Невозможно отправить письмо:';

$lang['us_set_password']           = 'Сохранить новый пароль';
$lang['us_reset_password_success'] = 'Пожалуйста, войдите с использованием нового пароля.';
$lang['us_reset_password_error']   = 'Ошибка при смене пароля: %s';

$lang['us_profile_updated_success'] = 'Профиль успешно обновлен.';
$lang['us_profile_updated_error']   = 'Проблема при попытке обновить профиль';

$lang['us_register_disabled'] = 'Регистрация новых пользователей запрещена.';

$lang['us_user_created_success'] = 'Пользователь успешно создан.';
$lang['us_user_update_success']  = 'Пользователь успешно обновлен.';

$lang['us_user_restored_success'] = 'Пользователь успешно восстановлен.';
$lang['us_user_restored_error']   = 'Невозможно восстановить пользователя:';

/* Activations */
$lang['us_status']                    = 'Статус';
$lang['us_inactive_users']            = 'Неактивные пользователи';
$lang['us_activate']                  = 'Активация';
$lang['us_user_activate_note']        = 'Введите код активации для подтверждения вашего аккаунта.';
$lang['us_activate_note']             = 'Активировать пользователя и дать ему доступ к сайту';
$lang['us_deactivate_note']           = 'Деактивировать пользователя';
$lang['us_activate_enter']            = 'Для продолжения, пожалуйста, введите код активации.';
$lang['us_activate_code']             = 'Код активации';
$lang['us_activate_request']          = 'Запросить еще один';
$lang['us_activate_resend']           = 'Отправить код активации снова';
$lang['us_activate_resend_note']      = 'Введите свой Email и мы отправим код активации снова.';
$lang['us_confirm_activate_code']     = 'Подтвердите код активации';
$lang['us_activate_code_send']        = 'Отправить код активации';
$lang['bf_action_activate']           = 'Активировать';
$lang['bf_action_deactivate']         = 'Деактивировать';
$lang['us_account_activated']         = 'Активация аккаунта.';
$lang['us_account_deactivated']       = 'Деактивация аккаунта.';
$lang['us_account_activated_admin']   = 'Активация аккаунта администратора.';
$lang['us_account_deactivated_admin'] = 'Деактивация аккаунта администратора.';
$lang['us_active']                    = 'Активен';
$lang['us_inactive']                  = 'Неактивен';
//email subjects
$lang['us_email_subj_activate'] = 'Активировать членство';
$lang['us_email_subj_pending']  = 'Регистрация завершена. Запрошена активация.';
$lang['us_email_thank_you']     = 'Спасибо за регистрацию!';
// Activation Statuses
$lang['us_registration_fail']      = 'Регистрация не завершена.';
$lang['us_check_activate_email']   = 'Пожалуйста, проверьте свой почтовый ящик на предмет инструкции по активации вашего аккаунта.';
$lang['us_admin_approval_pending'] = 'Ваш аккаунт передан на рассмотрение администрации. Как только ваш аккаунт будет ободрен, мы пришлем вам письмо.';
$lang['us_account_not_active']     = 'Ваш аккаунт еще не активирован. Пожалуйста, введите код для активации аккаунта.';
$lang['us_account_active']         = 'Поздравляем! Ваш аккаунт успешно активирован!';
$lang['us_account_active_login']   = 'Ваш аккаунт активен и вы можете войти.';
$lang['us_account_reg_complete']   = 'Регистрация на сайте "[SITE_TITLE]" завершена!';
$lang['us_active_status_changed']  = 'Статус пользователя успешно изменен.';
$lang['us_active_email_sent']      = 'Письмо активации успешно отправлено.';
// Activation Errors
$lang['us_err_no_id']            = 'Нет User ID.';
$lang['us_err_status_error']     = 'Статус пользователя не был изменен:';
$lang['us_err_no_email']         = 'Невозможно отправить письмо:';
$lang['us_err_activate_fail']    = 'Ваша учетная запись была отклонена по следующей причине:';
$lang['us_err_activate_code']    = 'Пожалуйста, проверьте правильность кода и попробуйте снова, или обратитесь к администратору.';
$lang['us_err_no_matching_code'] = 'Данный код активации недействителен.';
$lang['us_err_no_matching_id']   = 'Данный User ID не найден в системе.';
$lang['us_err_user_is_active']   = 'Данный пользователь уже активен.';
$lang['us_err_user_is_inactive'] = 'Данный пользователь уже неактивен.';

/* Password strength/match */
$lang['us_pass_strength']      = 'Сила';
$lang['us_pass_match']         = 'Сравнение';
$lang['us_passwords_no_match'] = 'Не совпадают!';
$lang['us_passwords_match']    = 'Совпадают!';
$lang['us_pass_weak']          = 'Плохой';
$lang['us_pass_good']          = 'Хороший';
$lang['us_pass_strong']        = 'Отличный!';

$lang['us_tab_all']      = 'Все пользователи';
$lang['us_tab_inactive'] = 'Неактивные';
$lang['us_tab_banned']   = 'Заблокированные';
$lang['us_tab_deleted']  = 'Удаленные';
$lang['us_tab_roles']    = 'По роли';

$lang['us_forced_password_reset_note'] = 'Из соображений безопасности, вы должны выбрать новый пароль для своего аккаунта.';

$lang['us_back_to']              = 'Вернуться';
$lang['us_no_account']           = 'Нет аккаунта?';
$lang['us_force_password_reset'] = 'Сменить пароль при следующем входе';

/* /users/language/russian/users_lang.php */