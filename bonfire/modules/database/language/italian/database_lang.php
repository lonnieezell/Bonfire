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
 * Database Language File (Italian)
 *
 * @package     Bonfire\Modules\Database\Language\Italian
 * @author      Lorenzo Sanzari (ulisse73@quipo.it)
 * @link    http://cibonfire.com/docs
 */

$lang['database_maintenance']           = 'Manutenzione';
$lang['database_backups']               = 'Backups';

$lang['database_backup_warning']        = 'Nota: a causa del tempo di esecuzione e della memoria limitati disponibili in PHP, il salvataggio di database molto grandi non è consentito. Se il tuo database è molto grande potresti avere bisogno di salvarlo direttamente dal tuo server SQL tramite linea di comando, o richiedere all\'amministratore del server di farlo per voi, se non avete i privilegi di root.';
$lang['database_filename']              = 'Nome file';

$lang['database_drop_question']         = 'Aggiungere il comando &lsquo;Drop Tables&rsquo; all\'SQL?';
$lang['database_drop_tables']           = 'Drop Tables';
$lang['database_compress_question']     = 'Tipo di compressione?';
$lang['database_compress_type']         = 'Tipo di compressione';
$lang['database_insert_question']       = 'Aggiungere gli &lsquo;Inserts&rsquo; dati all\'SQL?';
$lang['database_add_inserts']           = 'Aggiungi Inserts';

$lang['database_restore_note']          = 'L\'opzione Ripristina è capace di leggere solo files non compressi. Le compressioni Gzip e Zip vanno bene per te se vuoi un backup da scaricare e conservare sul tuo computer.';

$lang['database_apply']                 = 'Applica';
$lang['database_gzip']                  = 'gzip';
$lang['database_zip']                   = 'zip';
$lang['database_backup']                = 'Backup';
$lang['database_tables']                = 'Tabelle';
$lang['database_restore']               = 'Ripristina';
$lang['database_database']              = 'Database';
$lang['database_drop']                  = 'Taglia';
$lang['database_repair']                = 'Ripara';
$lang['database_optimize']              = 'Ottimizza';
$lang['database_migrations']            = 'Migrations';

$lang['database_delete_note']           = 'Elimina i file di backup selezionati:';
$lang['database_no_backups']            = 'Nessun backup precedente trovato.';
$lang['database_backup_delete_confirm'] = 'Davvero vuoi eliminare i file di backup seguenti?';
$lang['database_backup_delete_none']    = 'Nessun file di backup selezionato per l\'eliminazione';
$lang['database_drop_confirm']          = 'Davvero vuoi eliminare le seguenti tabelle?';
$lang['database_drop_none']             = 'Nessuna tabella selezionata per l\'eliminazione';
$lang['database_drop_attention']        = '<p>Eliminando tabelle dal database darà luogo a perdita di dati.</p><p><strong>Questo potrebbe rendere la tua applicazione inutilizzabile</strong></p>';
$lang['database_repair_none']           = 'Nessuna tabella selezionata per il ripristino';

$lang['database_table_name']            = 'Nome tabella';
$lang['database_records']               = 'Records';
$lang['database_data_size']             = 'Dimensione dati';
$lang['database_index_size']            = 'Dimensione indice';
$lang['database_data_free']             = 'Dati liberi';
$lang['database_engine']                = 'Motore';
$lang['database_no_tables']             = 'Nessuna tabella trovata nel database corrente';

$lang['database_restore_results']       = 'Ripristina risultati';
$lang['database_back_to_tools']         = 'Torna agli strumenti Database';
$lang['database_restore_file']          = 'Ripristina database da file';
$lang['database_restore_attention']     = 'Il ripristino del database da un file di backup causerà una cancellazione parziale o totale del database esistente prima del ripristino. </p><p><strong>Questo potrebbe causare perdita di dati</strong>.</p>';

$lang['database_database_settings']     = 'Impostazioni database';
$lang['database_server_type']           = 'Tipo di Server';
$lang['database_hostname']              = 'Nome Host';
$lang['database_dbname']                = 'Nome Database';
$lang['database_advanced_options']      = 'Opzioni avanzate';
$lang['database_persistent_connect']    = 'Connessione persistente';
$lang['database_display_errors']        = 'Mostra errori database';
$lang['database_enable_caching']        = 'Abilita caching delle queries';
$lang['database_cache_dir']             = 'Directory di cache';
$lang['database_prefix']                = 'Prefisso';

$lang['database_servers']               = 'Servers';
$lang['database_driver']                = 'Driver';
$lang['database_persistent']            = 'Peristente';
$lang['database_debug_on']              = 'Attiva debug';
$lang['database_strict_mode']           = 'Modalità Strict';
$lang['database_running_on_1']          = 'Al momento stai eseguendo su';
$lang['database_running_on_2']          = 'server';
$lang['database_serv_dev']              = 'Development';
$lang['database_serv_test']             = 'Testing';
$lang['database_serv_prod']             = 'Production';

$lang['database_successful_save']       = 'Le tue impostazioni sono state salvate correttamente.';
$lang['database_erroneous_save']        = 'C\'è stato un errore nel salvataggio delle impostazioni.';
$lang['database_successful_save_act']   = 'Impostazioni del database salvate correttamente';
$lang['database_erroneous_save_act']    = 'Impostazioni del database non salvate correttamente';

$lang['database_sql_query']             = 'Query SQL';
$lang['database_total_results']         = 'Totale Risultati';
$lang['database_no_rows']               = 'Nessun dato trovato in tabella';
$lang['database_browse']                = 'Naviga';
