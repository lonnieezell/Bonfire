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
 * @filesource
 */

/**
 * Language file for the Database Module (Portuguese)
 *
 * @package    Bonfire\Modules\Database\Language\Portuguese
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs
 */

$lang['database_maintenance']           = 'Manutenção';
$lang['database_database_maintenance']  = 'Manutenção de banco de dados';
$lang['database_backups']               = 'Backups';
$lang['database_database_backups']      = 'Backups de banco de dados';

$lang['database_backup_warning']        = 'Cuidado com a duração do Script PHP.';
$lang['database_filename']              = 'Node do Ficheiro';

$lang['database_drop_question']         = 'Incluir &lsquo;Drop Tables&rsquo; no comando SQL?';
$lang['database_compress_question']     = 'Tipo Compressão?';
$lang['database_insert_question']       = 'Incluir &lsquo;Inserts&rsquo; no comando SQL?';

$lang['database_restore_note']          = 'Atenção. O restore apenas lê un-compressed files. Usar Gzip and Zip compression se o objectivo é um backup para arquivo morto.';

$lang['database_gzip']                  = 'gzip';
$lang['database_zip']                   = 'zip';
$lang['database_backup']                = 'Backup';
$lang['database_tables']                = 'Tabelas';
$lang['database_restore']               = 'Restore';
$lang['database_database']              = 'Base de Dados';
$lang['database_drop']                  = 'Drop';
$lang['database_repair']                = 'Repair';
$lang['database_optimize']              = 'Optimize';
$lang['database_apply']                 = 'aplicar';

$lang['database_delete_note']           = 'Apagar os seguintes Backups: ';
$lang['database_no_backups']            = 'Não foram encontrados Backups prévios.';
$lang['database_backup_delete_confirm'] = 'Apagar mesmo mesmo mesmo estes backups? (benze-te)';
$lang['database_drop_confirm']          = 'Apagar de certeza certezinha as tabelas? (benze-te)';
$lang['database_drop_attention']        = '<p>A perda de dados é irreversível.</p><p><strong>Poderá tornar a aplicação instável.</strong></p>';

$lang['database_table_name']            = 'Nome da Tabela';
$lang['database_records']               = 'Registos';
$lang['database_data_size']             = 'Tamanho';
$lang['database_index_size']            = 'Index';
$lang['database_data_free']             = 'Data Free';
$lang['database_engine']                = 'Motor BD';
$lang['database_no_tables']             = 'Não existem tabelas nesta BD.';

$lang['database_restore_results']       = 'Restore Results';
$lang['database_back_to_tools']         = 'Voltar às Tools de BD';
$lang['database_restore_file']          = 'Restore database a partir de ficheiro';
$lang['database_restore_attention']     = '<p>Restore a partir de ficheiro implica que a BD seja eliminada antes de ser restaurada.</p><p><strong>Serão perdiso os dados</strong>.</p>';

$lang['database_database_settings']     = 'Definições da BD';
$lang['database_server_type']           = 'Server Type';
$lang['database_hostname']              = 'Hostname';
$lang['database_dbname']                = 'Database';
$lang['database_advanced_options']      = 'Opções Avançadas';
$lang['database_persistent_connect']    = 'Persistant Conn';
$lang['database_display_errors']        = 'Mostrar erros de BD';
$lang['database_enable_caching']        = 'Query Caching activo';
$lang['database_cache_dir']             = 'Dir Cache activo';
$lang['database_prefix']                = 'Prefixo';

$lang['database_servers']               = 'Server';
$lang['database_driver']                = 'Driver';
$lang['database_persistent']            = 'Persistant';
$lang['database_debug_on']              = 'Debug On';
$lang['database_strict_mode']           = 'Strict Mode';
$lang['database_running_on_1']          = '*';
$lang['database_running_on_2']          = '*';

$lang['database_successful_save']       = 'Your settings were successfully saved.';
$lang['database_erroneous_save']        = 'There was an error saving the settings.';
$lang['database_successful_save_act']   = 'Database settings were successfully saved';
$lang['database_erroneous_save_act']    = 'Database settings did not save correctly';
