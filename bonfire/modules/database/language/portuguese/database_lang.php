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

$lang['db_maintenance']			= 'Manutenção';
$lang['db_database_maintenance']			= 'Manutenção de banco de dados';
$lang['db_backups']				= 'Backups';
$lang['db_database_backups']				= 'Backups de banco de dados';

$lang['db_backup_warning']		= 'Cuidado com a duração do Script PHP.';
$lang['db_filename']			= 'Node do Ficheiro';

$lang['db_drop_question']		= 'Incluir &lsquo;Drop Tables&rsquo; no comando SQL?';
$lang['db_compress_question']	= 'Tipo Compressão?';
$lang['db_insert_question']		= 'Incluir &lsquo;Inserts&rsquo; no comando SQL?';

$lang['db_restore_note']		= 'Atenção. O restore apenas lê un-compressed files. Usar Gzip and Zip compression se o objectivo é um backup para arquivo morto.';

$lang['db_gzip']				= 'gzip';
$lang['db_zip']					= 'zip';
$lang['db_backup']				= 'Backup';
$lang['db_tables']				= 'Tabelas';
$lang['db_restore']				= 'Restore';
$lang['db_database']			= 'Base de Dados';
$lang['db_drop']				= 'Drop';
$lang['db_repair']				= 'Repair';
$lang['db_optimize']			= 'Optimize';
$lang['db_apply']			= 'aplicar';

$lang['db_delete_note']			= 'Apagar os seguintes Backups: ';
$lang['db_no_backups']			= 'Não foram encontrados Backups prévios.';
$lang['db_backup_delete_confirm']	= 'Apagar mesmo mesmo mesmo estes backups? (benze-te)';
$lang['db_drop_confirm']		= 'Apagar de certeza certezinha as tabelas? (benze-te)';
$lang['db_drop_attention']		= '<p>A perda de dados é irreversível.</p><p><strong>Poderá tornar a aplicação instável.</strong></p>';

$lang['db_table_name']			= 'Nome da Tabela';
$lang['db_records']				= 'Registos';
$lang['db_data_size']			= 'Tamanho';
$lang['db_index_size']			= 'Index';
$lang['db_data_free']			= 'Data Free';
$lang['db_engine']				= 'Motor BD';
$lang['db_no_tables']			= 'Não existem tabelas nesta BD.';

$lang['db_restore_results']		= 'Restore Results';
$lang['db_back_to_tools']		= 'Voltar às Tools de BD';
$lang['db_restore_file']		= 'Restore database a partir de ficheiro';
$lang['db_restore_attention']	= '<p>Restore a partir de ficheiro implica que a BD seja eliminada antes de ser restaurada.</p><p><strong>Serão perdiso os dados</strong>.</p>';

$lang['db_database_settings']	= 'Definições da BD';
$lang['db_server_type']			= 'Server Type';
$lang['db_hostname']			= 'Hostname';
$lang['db_dbname']				= 'Database';
$lang['db_advanced_options']	= 'Opções Avançadas';
$lang['db_persistant_connect']	= 'Persistant Conn';
$lang['db_display_errors']		= 'Mostrar erros de BD';
$lang['db_enable_caching']		= 'Query Caching activo';
$lang['db_cache_dir']			= 'Dir Cache activo';
$lang['db_prefix']				= 'Prefixo';

$lang['db_servers']				= 'Server';
$lang['db_driver']				= 'Driver';
$lang['db_persistant']			= 'Persistant';
$lang['db_debug_on']			= 'Debug On';
$lang['db_strict_mode']			= 'Strict Mode';
$lang['db_running_on_1']		= '*';
$lang['db_running_on_2']		= '*';

$lang['db_successful_save']		= 'Your settings were successfully saved.';
$lang['db_erroneous_save']		= 'There was an error saving the settings.';
$lang['db_successful_save_act']	= 'Database settings were successfully saved';
$lang['db_erroneous_save_act']	= 'Database settings did not save correctly';