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
 * Logs language file (Russian).
 *
 * Localization strings used by Bonfire.
 *
 * @package    Bonfire\Modules\Logs\Language\Russian
 * @author     Translator < https://github.com/cjmaxik >
 * @link       http://cibonfire.com/docs/guides
 */

$lang['log_no_logs']       = 'Нет логов.';
$lang['log_not_enabled']   = 'Логирование еще не включено.';
$lang['log_the_following'] = 'Степень логирования';
$lang['log_what_0']        = '0 - ничего не логировать';
$lang['log_what_1']        = '1 - логировать сообщения об ошибке (включая ошибки PHP)';
$lang['log_what_2']        = '2 - логировать отладочные сообщения';
$lang['log_what_3']        = '3 - логировать информационные сообщения';
$lang['log_what_4']        = '4 - логировать все сообщения';
$lang['log_what_note']     = 'Более высокие степени включают в себя все предыдущие. Так, степень 2 будет логировать и сообщения степени 1.';

$lang['log_save_button']         = 'Сохранить настройки логирования';
$lang['log_delete_button']       = 'Удалить файлы логов';
$lang['log_delete1_button']      = 'Удалить данный файл лога?';
$lang['logs_delete_confirm']     = 'Are you sure you want to delete these logs?';
$lang['logs_delete_all_confirm'] = 'Are you sure you want to delete all log files?';

$lang['log_big_file_note']	= 'Logging can rapidly create very large files, if you log too much information. For live sites, you should probably log only Errors.';
$lang['log_delete_note']	= 'Deleting log files is permanent. There is no going back, so please make sure.';
$lang['log_delete1_note']	= 'Deleting log files is a permanent action. There is no going back, so please make sure you understand what you are doing.';
$lang['log_delete_confirm'] = 'Are you sure you want to delete this log file?';

$lang['log_not_found']		  = 'Either the log file could not be located, or it was empty.';
$lang['log_show_all_entries'] = 'Все строки';
$lang['log_show_errors']      = 'Только ошибки';

$lang['log_date']     = 'Дата';
$lang['log_file']     = 'Имя файла';
$lang['log_logs']     = 'Логи';
$lang['log_settings'] = 'Настройки';

$lang['log_title']          = 'Системные логи';
$lang['log_title_settings'] = 'Настройки системных логов';
$lang['log_deleted']        = '%d файлов удалено';
$lang['log_filter_label']   = 'Показать:';

/* /logs/language/russian/logs_lang.php */