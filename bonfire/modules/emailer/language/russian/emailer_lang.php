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
 * Application language file (Russian)
 *
 * Localization strings used by Bonfire
 *
 * @package    Bonfire\Application\Language\Russian
 * @author     Translator < https://github.com/cjmaxik >
 * @link       http://cibonfire.com/docs/guides
 */

$lang['em_template']       = 'Шаблон';
$lang['em_email_template'] = 'Шаблон письма';
$lang['em_emailer_queue']  = 'Очередь писем';

$lang['em_system_email']      = 'Системный Email';
$lang['em_system_email_note'] = 'Email, с которого отправляются сгенерированные системой письма.';
$lang['em_email_server']      = 'Email-сервер';
$lang['em_settings']          = 'Настройки Email';
$lang['em_settings_note']     = '<b>Mail</b> использует стандартную функцию PHP и не требует настройки.';
$lang['em_location']          = 'местонахождение';
$lang['em_server_address']    = 'Адрес сервера';
$lang['em_port']              = 'Порт';
$lang['em_timeout_secs']      = 'Таймаут (в секундах)';
$lang['em_email_type']        = 'Тип Email';
$lang['em_save_settings']     = 'Сохранить настройки';
$lang['em_test_settings']     = 'Отправить тестовое письмо';
$lang['em_sendmail_path']     = 'Путь до Sendmail';
$lang['em_smtp_address']      = 'Адрес сервера SMTP';
$lang['em_smtp_username']     = 'Имя пользователя SMTP';
$lang['em_smtp_password']     = 'Пароль SMTP';
$lang['em_smtp_port']         = 'Порт SMTP';
$lang['em_smtp_timeout']      = 'Таймаут SMTP';

$lang['em_template_note'] = 'Письма отправляются в формате HTML. Вы можете изменить шапку и подвал письма ниже.';
$lang['em_header']        = 'Шапка';
$lang['em_footer']        = 'Подвал';
$lang['em_save_template'] = 'Сохранить шаблон';

$lang['em_test_header']        = 'Проверить настройки';
$lang['em_test_intro']         = 'Для проверки настроек выше введите Email-адрес, на который будет отправлено письмо.<br/>Перед тестированием сохраните измененные настройки.';
$lang['em_test_button']        = 'Отправить тестовое письмо';
$lang['em_test_result_header'] = 'Результаты теста';
$lang['em_test_debug_header']  = 'Отладочная информация';
$lang['em_test_success']       = 'Похоже, что все настройки корректны. Если вы не видите письмо в почтовом ящике, поищите его в папке Спам или в Корзине.';
$lang['em_test_error']         = 'Похоже, что настройки некорректны.';

$lang['em_test_mail_subject'] = 'Поздравляем! Почтовый робот Bonfire работает!';
$lang['em_test_mail_body']    = 'Если вы видите данное письмо, это значит, что почтовый робот Bonfire работает!';

$lang['em_stat_no_queue']  = 'В очереди нет ни одного письма.';
$lang['em_total_in_queue'] = 'Всего писем в очереди';
$lang['em_total_sent']     = 'Всего писем отправлено:';
$lang['em_force_process']  = 'Обработать сейчас';
$lang['em_insert_test']    = 'Вставить тестовое письмо';

$lang['em_sent']          = 'Отправить';
$lang['em_attempts']      = 'Попытки';
$lang['em_id']            = 'ID';
$lang['em_to']            = 'Кому';
$lang['em_subject']       = 'Тема';
$lang['em_email_subject'] = 'Тема письма';
$lang['em_email_content'] = 'Текст письма';

$lang['em_missing_data'] = 'Одно или более обязательных полей не заполнено.';
$lang['em_no_debug']     = 'Письмо было поставлено в очередь. Нет доступной информации для отладки.';

$lang['em_delete_success'] = '%d запись(ей) удалено.';
$lang['em_delete_failure'] = 'Невозможно удалить записи: %s';
$lang['em_delete_error']   = 'Ошибка при удалении записей: %s';
$lang['em_delete_confirm'] = 'Вы уверены, что хотите удалить данные письма?';

$lang['em_create_email']         = 'Отправить новое письмо';
$lang['em_create_setting']       = 'Настройка письма';
$lang['em_create_email_error']   = 'Ошибка при создании писем: %s';
$lang['em_create_email_success'] = 'Письмо(а) были добавлены в очередь.';
$lang['em_create_email_failure'] = 'Ошибка при создании писем: %s';

/* end of file /emailer/language/russian/emailer_lang.php */