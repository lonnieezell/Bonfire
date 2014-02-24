<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
	Copyright (c) 2011 Lonnie Ezell

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

/**
 * Database Language File (Italian)
 *
 * @package     Bonfire\Modules\Database\Language\Italian
 * @author      Lorenzo Sanzari (ulisse73@quipo.it)
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

$lang['db_maintenance'] = 'Manutenzione';
$lang['db_backups'] = 'Backups';

$lang['db_backup_warning'] = 'Nota: a causa del tempo di esecuzione e della memoria limitati disponibili in PHP, il salvataggio di database molto grandi non è consentito. Se il tuo database è molto grande potresti avere bisogno di salvarlo direttamente dal tuo server SQL tramite linea di comando, o richiedere all\'amministratore del server di farlo per voi, se non avete i privilegi di root.';
$lang['db_filename'] = 'Nome file';

$lang['db_drop_question'] = 'Aggiungere il comando &lsquo;Drop Tables&rsquo; all\'SQL?';
$lang['db_drop_tables'] = 'Drop Tables';
$lang['db_compress_question'] = 'Tipo di compressione?';
$lang['db_compress_type'] = 'Tipo di compressione';
$lang['db_insert_question'] = 'Aggiungere gli &lsquo;Inserts&rsquo; dati all\'SQL?';
$lang['db_add_inserts'] = 'Aggiungi Inserts';

$lang['db_restore_note'] = 'L\'opzione Ripristina è capace di leggere solo files non compressi. Le compressioni Gzip e Zip vanno bene per te se vuoi un backup da scaricare e conservare sul tuo computer.';

$lang['db_apply'] = 'Applica';
$lang['db_gzip'] = 'gzip';
$lang['db_zip'] = 'zip';
$lang['db_backup'] = 'Backup';
$lang['db_tables'] = 'Tabelle';
$lang['db_restore'] = 'Ripristina';
$lang['db_database'] = 'Database';
$lang['db_drop'] = 'Taglia';
$lang['db_repair'] = 'Ripara';
$lang['db_optimize'] = 'Ottimizza';
$lang['db_migrations'] = 'Migrations';

$lang['db_delete_note'] = 'Elimina i file di backup selezionati:';
$lang['db_no_backups'] = 'Nessun backup precedente trovato.';
$lang['db_backup_delete_confirm'] = 'Davvero vuoi eliminare i file di backup seguenti?';
$lang['db_backup_delete_none'] = 'Nessun file di backup selezionato per l\'eliminazione';
$lang['db_drop_confirm'] = 'Davvero vuoi eliminare le seguenti tabelle?';
$lang['db_drop_none'] = 'Nessuna tabella selezionata per l\'eliminazione';
$lang['db_drop_attention'] = '<p>Eliminando tabelle dal database darà luogo a perdita di dati.</p><p><strong>Questo potrebbe rendere la tua applicazione inutilizzabile</strong></p>';
$lang['db_repair_none'] = 'Nessuna tabella selezionata per il ripristino';

$lang['db_table_name'] = 'Nome tabella';
$lang['db_records'] = 'Records';
$lang['db_data_size'] = 'Dimensione dati';
$lang['db_index_size'] = 'Dimensione indice';
$lang['db_data_free'] = 'Dati liberi';
$lang['db_engine'] = 'Motore';
$lang['db_no_tables'] = 'Nessuna tabella trovata nel database corrente';

$lang['db_restore_results'] = 'Ripristina risultati';
$lang['db_back_to_tools'] = 'Torna agli strumenti Database';
$lang['db_restore_file'] = 'Ripristina database da file';
$lang['db_restore_attention'] = 'Il ripristino del database da un file di backup causerà una cancellazione parziale o totale del database esistente prima del ripristino. </p><p><strong>Questo potrebbe causare perdita di dati</strong>.</p>';

$lang['db_database_settings'] = 'Impostazioni database';
$lang['db_server_type'] = 'Tipo di Server';
$lang['db_hostname'] = 'Nome Host';
$lang['db_dbname'] = 'Nome Database';
$lang['db_advanced_options'] = 'Opzioni avanzate';
$lang['db_persistant_connect'] = 'Connessione persistente';
$lang['db_display_errors'] = 'Mostra errori database';
$lang['db_enable_caching'] = 'Abilita caching delle queries';
$lang['db_cache_dir'] = 'Directory di cache';
$lang['db_prefix'] = 'Prefisso';

$lang['db_servers'] = 'Servers';
$lang['db_driver'] = 'Driver';
$lang['db_persistant'] = 'Peristente';
$lang['db_debug_on'] = 'Attiva debug';
$lang['db_strict_mode'] = 'Modalità Strict';
$lang['db_running_on_1'] = 'Al momento stai eseguendo su';
$lang['db_running_on_2'] = 'server';
$lang['db_serv_dev'] = 'Development';
$lang['db_serv_test'] = 'Testing';
$lang['db_serv_prod'] = 'Production';

$lang['db_successful_save'] = 'Le tue impostazioni sono state salvate correttamente.';
$lang['db_erroneous_save'] = 'C\'è stato un errore nel salvataggio delle impostazioni.';
$lang['db_successful_save_act'] = 'Impostazioni del database salvate correttamente';
$lang['db_erroneous_save_act'] = 'Impostazioni del database non salvate correttamente';

$lang['db_sql_query'] = 'Query SQL';
$lang['db_total_results'] = 'Totale Risultati';
$lang['db_no_rows'] = 'Nessun dato trovato in tabella';
$lang['db_browse'] = 'Naviga';
