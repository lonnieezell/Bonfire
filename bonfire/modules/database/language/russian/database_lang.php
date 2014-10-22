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

$lang['database_maintenance'] = 'Обслуживание';
$lang['database_backups']     = 'Резервные копии';

// $lang['database_backup_warning'] = 'Note: Due to the limited execution time and memory available to PHP, backing up very large databases may not be possible. If your database is very large you might need to backup directly from your SQL server via the command line, or have your server admin do it for you if you do not have root privileges.';
$lang['database_filename']       = 'Имя файла';

$lang['database_drop_question']     = 'Добавить команду &lsquo;Drop Tables &rsquo; в SQL?';
$lang['database_drop_tables']       = 'Удалить таблицы';
$lang['database_compress_question'] = 'Тип сжатия?';
$lang['database_compress_type']     = 'Тип сжатия';
$lang['database_insert_question']   = 'Добавить &lsquo;Inserts&rsquo; для данных в SQL?';
$lang['database_add_inserts']       = 'Добавить Insert\'ы';

// $lang['database_restore_note'] = 'The Restore option is only capable of reading un-compressed files. Gzip and Zip compression is good if you just want a backup to download and store on your computer.';

$lang['database_apply']      = 'Применить';
$lang['database_gzip']       = 'gzip';
$lang['database_zip']        = 'zip';
$lang['database_backup']     = 'Резервная копия';
$lang['database_tables']     = 'Таблицы';
$lang['database_restore']    = 'Восстановить';
$lang['database_database']   = 'База данных';
$lang['database_drop']       = 'Удалить';
$lang['database_repair']     = 'Восстановить';
$lang['database_optimize']   = 'Оптимизировать';
$lang['database_migrations'] = 'Миграции';

$lang['database_delete_note']           = 'Удалить выбранные файлы резервной копии:';
$lang['database_no_backups']            = 'Предыдущих резервных копий не найдено.';
$lang['database_backup_delete_confirm'] = 'Действительной удалить данные файлы?';
$lang['database_backup_delete_none']    = 'Не выбраны файлы для удаления';
$lang['database_drop_confirm']          = 'Действительно удалить данные таблицы?';
$lang['database_drop_none']             = 'Не выбраны таблицы для удаления';
// $lang['database_drop_attention']         = '<p>Deleting tables from the database will result in loss of data.</p><p><strong>This may make your application non-functional.</strong></p>';
$lang['database_repair_none']           = 'Не выбраны таблицы для восстановления';

$lang['database_table_name'] = 'Имя таблицы';
$lang['database_records']    = 'Записи';
$lang['database_data_size']  = 'Размер данных';
$lang['database_index_size'] = 'Размер индекса';
$lang['database_data_free']  = 'Свободное место';
$lang['database_engine']     = 'Драйвер';
$lang['database_no_tables']  = 'В данной базе данных нет ни одной таблицы.';

$lang['database_restore_results']   = 'Восстановить результаты';
$lang['database_back_to_tools']     = 'Вернуться в инструменты баз данных';
$lang['database_restore_file']      = 'Восстановить базу данных из файла';
// $lang['database_restore_attention'] = '<p>Restoring a database from a backup file will result in some or all of your database being erased before restoring.</p><p><strong>This may result in a loss of data</strong>.</p>';

$lang['database_database_settings']  = 'Настройки';
$lang['database_server_type']        = 'Тип сервера';
$lang['database_hostname']           = 'Имя';
$lang['database_dbname']             = 'Название базы данных';
$lang['database_advanced_options']   = 'Дополнительные настройки';
$lang['database_persistent_connect'] = 'Постоянное подключение';
$lang['database_display_errors']     = 'Показывать ошибки базы данных';
$lang['database_enable_caching']     = 'Включить кеширование запросов';
$lang['database_cache_dir']          = 'Директория кэша';
$lang['database_prefix']             = 'Префикс';

$lang['database_servers']      = 'Сервера';
$lang['database_driver']       = 'Драйвер';
$lang['database_persistent']   = 'Постоянный';
$lang['database_debug_on']     = 'Дебаг включен';
$lang['database_strict_mode']  = 'Строгий режим';
$lang['database_running_on_1'] = 'Вы сейчас находитесь на';
$lang['database_running_on_2'] = 'сервере.';
$lang['database_serv_dev']     = 'Разработка';
$lang['database_serv_test']    = 'Тестирование';
$lang['database_serv_prod']    = 'Продакшн';

$lang['database_successful_save']     = 'Ваши настройки успешно сохранены.';
$lang['database_erroneous_save']      = 'Ошибка при сохранении настроек.';
$lang['database_successful_save_act'] = 'Настройки базы данных успешно сохранены';
$lang['database_erroneous_save_act']  = 'Настройки базы данных не были сохранены';

$lang['database_sql_query']     = 'SQL-запрос';
$lang['database_total_results'] = 'Всего';
$lang['database_no_rows']       = 'Нет данных в таблице.';
$lang['database_browse']        = 'Посмотреть';

/* /database/language/russian/database_lang.php */
