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
 * Database language file (Russian).
 *
 * Localization strings used by Bonfire.
 *
 * @package    Bonfire\Modules\Database\Language\Russian
 * @author     Translator < https://github.com/cjmaxik >
 * @link       http://cibonfire.com/docs/guides
 */

$lang['db_maintenance'] = 'Обслуживание';
$lang['db_backups']     = 'Резервные копии';

// $lang['db_backup_warning'] = 'Note: Due to the limited execution time and memory available to PHP, backing up very large databases may not be possible. If your database is very large you might need to backup directly from your SQL server via the command line, or have your server admin do it for you if you do not have root privileges.';
$lang['db_filename']       = 'Имя файла';

$lang['db_drop_question']     = 'Добавить команду &lsquo;Drop Tables &rsquo; в SQL?';
$lang['db_drop_tables']       = 'Удалить таблицы';
$lang['db_compress_question'] = 'Тип сжатия?';
$lang['db_compress_type']     = 'Тип сжатия';
$lang['db_insert_question']   = 'Добавить &lsquo;Inserts&rsquo; для данных в SQL?';
$lang['db_add_inserts']       = 'Добавить Insert\'ы';

// $lang['db_restore_note'] = 'The Restore option is only capable of reading un-compressed files. Gzip and Zip compression is good if you just want a backup to download and store on your computer.';

$lang['db_apply']      = 'Применить';
$lang['db_gzip']       = 'gzip';
$lang['db_zip']        = 'zip';
$lang['db_backup']     = 'Резервная копия';
$lang['db_tables']     = 'Таблицы';
$lang['db_restore']    = 'Восстановить';
$lang['db_database']   = 'База данных';
$lang['db_drop']       = 'Удалить';
$lang['db_repair']     = 'Восстановить';
$lang['db_optimize']   = 'Оптимизировать';
$lang['db_migrations'] = 'Миграции';

$lang['db_delete_note']           = 'Удалить выбранные файлы резервной копии:';
$lang['db_no_backups']            = 'Предыдущих резервных копий не найдено.';
$lang['db_backup_delete_confirm'] = 'Действительной удалить данные файлы?';
$lang['db_backup_delete_none']    = 'Не выбраны файлы для удаления';
$lang['db_drop_confirm']          = 'Действительно удалить данные таблицы?';
$lang['db_drop_none']             = 'Не выбраны таблицы для удаления';
// $lang['db_drop_attention']		  = '<p>Deleting tables from the database will result in loss of data.</p><p><strong>This may make your application non-functional.</strong></p>';
$lang['db_repair_none']           = 'Не выбраны таблицы для восстановления';

$lang['db_table_name'] = 'Имя таблицы';
$lang['db_records']    = 'Записи';
$lang['db_data_size']  = 'Размер данных';
$lang['db_index_size'] = 'Размер индекса';
$lang['db_data_free']  = 'Свободное место';
$lang['db_engine']     = 'Драйвер';
$lang['db_no_tables']  = 'В данной базе данных нет ни одной таблицы.';

$lang['db_restore_results']   = 'Восстановить результаты';
$lang['db_back_to_tools']     = 'Вернуться в инструменты баз данных';
$lang['db_restore_file']      = 'Восстановить базу данных из файла';
// $lang['db_restore_attention'] = '<p>Restoring a database from a backup file will result in some or all of your database being erased before restoring.</p><p><strong>This may result in a loss of data</strong>.</p>';

$lang['db_database_settings']  = 'Настройки';
$lang['db_server_type']        = 'Тип сервера';
$lang['db_hostname']           = 'Имя';
$lang['db_dbname']             = 'Название базы данных';
$lang['db_advanced_options']   = 'Дополнительные настройки';
$lang['db_persistant_connect'] = 'Постоянное подключение';
$lang['db_display_errors']     = 'Показывать ошибки базы данных';
$lang['db_enable_caching']     = 'Включить кеширование запросов';
$lang['db_cache_dir']          = 'Директория кэша';
$lang['db_prefix']             = 'Префикс';

$lang['db_servers']      = 'Сервера';
$lang['db_driver']       = 'Драйвер';
$lang['db_persistant']   = 'Постоянный';
$lang['db_debug_on']     = 'Дебаг включен';
$lang['db_strict_mode']  = 'Строгий режим';
$lang['db_running_on_1'] = 'Вы сейчас находитесь на';
$lang['db_running_on_2'] = 'сервере.';
$lang['db_serv_dev']     = 'Разработка';
$lang['db_serv_test']    = 'Тестирование';
$lang['db_serv_prod']    = 'Продакшн';

$lang['db_successful_save']     = 'Ваши настройки успешно сохранены.';
$lang['db_erroneous_save']      = 'Ошибка при сохранении настроек.';
$lang['db_successful_save_act'] = 'Настройки базы данных успешно сохранены';
$lang['db_erroneous_save_act']  = 'Настройки базы данных не были сохранены';

$lang['db_sql_query']     = 'SQL-запрос';
$lang['db_total_results'] = 'Всего';
$lang['db_no_rows']       = 'Нет данных в таблице.';
$lang['db_browse']        = 'Посмотреть';

/* /database/language/russian/database_lang.php */