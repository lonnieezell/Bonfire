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
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Logs language file (Russian).
 *
 * Localization strings used by Bonfire's Logs module
 *
 * @package Bonfire\Modules\Logs\Language\Russian
 * @author  Translator < https://github.com/cjmaxik >
 * @author  Translator < https://github.com/nkondrashov >
 * @link    http://cibonfire.com/docs/guides
 */

$lang['logs_no_logs']       = 'Нет логов.';
$lang['logs_not_enabled']   = 'Логирование еще не включено.';
$lang['logs_the_following'] = 'Степень логирования';
$lang['logs_what_0']        = '0 - ничего не логировать';
$lang['logs_what_1']        = '1 - логировать сообщения об ошибке (включая ошибки PHP)';
$lang['logs_what_2']        = '2 - логировать отладочные сообщения';
$lang['logs_what_3']        = '3 - логировать информационные сообщения';
$lang['logs_what_4']        = '4 - логировать все сообщения';
$lang['logs_what_note']     = 'Более высокие степени включают в себя все предыдущие. Так, степень 2 будет логировать и сообщения степени 1.';

$lang['logs_save_button']    = 'Сохранить настройки логирования';
$lang['logs_delete_button']  = 'Удалить файлы логов';
$lang['logs_delete1_button'] = 'Удалить данный файл лога?';

$lang['logs_big_file_note']  = 'Логирование использует очень много места. Для работающих сайтов рекомендуется ставить только логирование ошибок.';
$lang['logs_delete_note']    = 'Удаление лог файлов необратимо.';
$lang['logs_delete1_note']   = 'Удаление лог файлов необратимо, подумайте, вы уверены?';
$lang['logs_delete_confirm'] = 'Вы уверены что хотите удалить этот лог?';

$lang['logs_not_found']        = 'Лог файл не найден или пустой.';
$lang['logs_show_all_entries'] = 'Все строки';
$lang['logs_show_errors']      = 'Только ошибки';

$lang['logs_date']     = 'Дата';
$lang['logs_file']     = 'Имя файла';
$lang['logs_logs']     = 'Логи';
$lang['logs_settings'] = 'Настройки';

$lang['logs_title']          = 'Системные логи';
$lang['logs_title_settings'] = 'Настройки системных логов';
$lang['logs_deleted']        = '%d файлов удалено';
$lang['logs_filter_label']   = 'Показать:';

$lang['logs_delete_confirm']     = 'Вы уверены что хотите удалить этот лог?';
$lang['logs_delete_all_confirm'] = 'Вы уверены что хотите все логи?';

$lang['logs_act_deleted']               = 'Файло логов %s удален из: %s';
$lang['logs_act_deleted_all']           = 'Все лог файлы удалены из: %s';
$lang['logs_act_settings_modified']     = 'Настройки лона именены в: %s';

$lang['logs_deleted_all_success']       = 'Успешно удалены все файлы';
$lang['logs_settings_modified_success'] = 'Настройки логирования успешно сохранены.';
$lang['logs_settings_modified_failure'] = 'Невозможно сохранить настройки логирования. Дайте права на запись файла <strong>application/config/config.php</strong> и попробуйте снова.';
$lang['logs_view_empty']                = 'Файл пустой.';

$lang['logs_viewing']                   = 'Просмотр:';
