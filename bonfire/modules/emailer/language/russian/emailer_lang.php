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
 * Emailer language file (Russian)
 *
 * Localization strings used by Bonfire's Emailer module.
 *
 * @package Bonfire\Modules\Emailer\Language\Russian
 * @author  Translator < https://github.com/cjmaxik >
 * @link    http://cibonfire.com/docs/developer
 */

$lang['emailer_template']             = 'Шаблон';
$lang['emailer_email_template']       = 'Шаблон письма';
$lang['emailer_emailer_queue']        = 'Очередь писем';

$lang['emailer_system_email']         = 'Системный Email';
$lang['emailer_system_email_note']    = 'Email, с которого отправляются сгенерированные системой письма.';
$lang['emailer_email_server']         = 'Email-сервер';
$lang['emailer_settings']             = 'Настройки Email';
$lang['emailer_settings_note']        = '<b>Mail</b> использует стандартную функцию PHP и не требует настройки.';
$lang['emailer_location']             = 'местонахождение';
$lang['emailer_server_address']       = 'Адрес сервера';
$lang['emailer_port']                 = 'Порт';
$lang['emailer_timeout_secs']         = 'Таймаут (в секундах)';
$lang['emailer_email_type']           = 'Тип Email';
$lang['emailer_save_settings']        = 'Сохранить настройки';
$lang['emailer_test_settings']        = 'Отправить тестовое письмо';
$lang['emailer_sendmail_path']        = 'Путь до Sendmail';
$lang['emailer_smtp_address']         = 'Адрес сервера SMTP';
$lang['emailer_smtp_username']        = 'Имя пользователя SMTP';
$lang['emailer_smtp_password']        = 'Пароль SMTP';
$lang['emailer_smtp_port']            = 'Порт SMTP';
$lang['emailer_smtp_timeout']         = 'Таймаут SMTP';

$lang['emailer_template_note']        = 'Письма отправляются в формате HTML. Вы можете изменить шапку и подвал письма ниже.';
$lang['emailer_header']               = 'Шапка';
$lang['emailer_footer']               = 'Подвал';
$lang['emailer_save_template']        = 'Сохранить шаблон';

$lang['emailer_test_header']          = 'Проверить настройки';
$lang['emailer_test_intro']           = 'Для проверки настроек выше введите Email-адрес, на который будет отправлено письмо.<br/>Перед тестированием сохраните измененные настройки.';
$lang['emailer_test_button']          = 'Отправить тестовое письмо';
$lang['emailer_test_result_header']   = 'Результаты теста';
$lang['emailer_test_debug_header']    = 'Отладочная информация';
$lang['emailer_test_success']         = 'Похоже, что все настройки корректны. Если вы не видите письмо в почтовом ящике, поищите его в папке Спам или в Корзине.';
$lang['emailer_test_error']           = 'Похоже, что настройки некорректны.';

$lang['emailer_test_mail_subject']    = 'Поздравляем! Почтовый робот Bonfire работает!';
$lang['emailer_test_mail_body']       = 'Если вы видите данное письмо, это значит, что почтовый робот Bonfire работает!';

$lang['emailer_stat_no_queue']        = 'В очереди нет ни одного письма.';
$lang['emailer_total_in_queue']       = 'Всего писем в очереди';
$lang['emailer_total_sent']           = 'Всего писем отправлено:';
$lang['emailer_force_process']        = 'Обработать сейчас';
$lang['emailer_insert_test']          = 'Вставить тестовое письмо';

$lang['emailer_sent']                 = 'Отправить';
$lang['emailer_attempts']             = 'Попытки';
$lang['emailer_id']                   = 'ID';
$lang['emailer_to']                   = 'Кому';
$lang['emailer_subject']              = 'Тема';
$lang['emailer_email_subject']        = 'Тема письма';
$lang['emailer_email_content']        = 'Текст письма';

$lang['emailer_missing_data']         = 'Одно или более обязательных полей не заполнено.';
$lang['emailer_no_debug']             = 'Письмо было поставлено в очередь. Нет доступной информации для отладки.';

$lang['emailer_delete_success']       = '%d запись(ей) удалено.';
$lang['emailer_delete_failure']       = 'Невозможно удалить записи: %s';
$lang['emailer_delete_error']         = 'Ошибка при удалении записей: %s';
$lang['emailer_delete_confirm']       = 'Вы уверены, что хотите удалить данные письма?';

$lang['emailer_create_email']         = 'Отправить новое письмо';
$lang['emailer_create_setting']       = 'Настройка письма';
$lang['emailer_create_email_error']   = 'Ошибка при создании писем: %s';
$lang['emailer_create_email_success'] = 'Письмо(а) были добавлены в очередь.';
$lang['emailer_create_email_failure'] = 'Ошибка при создании писем: %s';

$lang['form_validation_emailer_system_email']  = 'Системный Email';
$lang['form_validation_emailer_email_server']  = 'Email-сервер';
$lang['form_validation_emailer_sendmail_path'] = 'Путь до Sendmail';
$lang['form_validation_emailer_smtp_address']  = 'Адрес сервера SMTP';
$lang['form_validation_emailer_smtp_username'] = 'Имя пользователя SMTP';
$lang['form_validation_emailer_smtp_password'] = 'Пароль SMTP';
$lang['form_validation_emailer_smtp_port']     = 'Порт SMTP';
$lang['form_validation_emailer_smtp_timeout']  = 'Таймаут SMTP';

/* end of file /emailer/language/russian/emailer_lang.php */
