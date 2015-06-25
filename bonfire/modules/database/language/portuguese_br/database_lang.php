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
 * Language file for the Database Module (Brazilian Portuguese)
 *
 * @package    Bonfire\Modules\Database\Language\Portuguese_br
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs
 */

$lang['database_maintenance']           = 'Manutenção';
$lang['database_backups']               = 'Backups';

$lang['database_backup_warning']        = 'Nota: Devido ao tempo de execução limitado e memória disponível para o PHP, não é possível realizar backup de bancos de dados muito grande. Se seu banco de dados é muito grande, você pode precisar fazer backup diretamente de seu servidor SQL via linha de comando, pelo PhpMyAdmin, ou contacte o administrador do seu servidor.';
$lang['database_filename']              = 'Nome do Arquivo';

$lang['database_drop_question']         = 'Add &lsquo;Drop Tablelas&rsquo; comando para SQL?';
$lang['database_drop_tables']           = 'Drop Tablelas';
$lang['database_compress_question']     = 'Timpo de Compressão?';
$lang['database_compress_type']         = 'Timpo de Compressão';
$lang['database_insert_question']       = 'Add &lsquo;Inserts&rsquo; de dados para SQL?';
$lang['database_add_inserts']           = 'Add Inserts';

$lang['database_restore_note']          = 'A opção de restauração só é capaz de ler arquivos descompactados. Compressão Gzip e Zip são bons se você quiser apenas um backup para baixar e armazenar no seu computador.';

$lang['database_apply']                 = 'Aplicar';
$lang['database_gzip']                  = 'gzip';
$lang['database_zip']                   = 'zip';
$lang['database_backup']                = 'Backup';
$lang['database_tables']                = 'Tablelas';
$lang['database_restore']               = 'Restaurar';
$lang['database_database']              = 'Database';
$lang['database_drop']                  = 'Drop';
$lang['database_repair']                = 'Reparar';
$lang['database_optimize']              = 'Optimizar';
$lang['database_migrations']            = 'Migrações';

$lang['database_delete_note']           = 'Deletar arquivos de backup selecionados: ';
$lang['database_no_backups']            = 'Não foram encontrados backups anteriores.';
$lang['database_backup_delete_confirm'] = 'Realmente apagar os arquivos de backup seguintes?';
$lang['database_backup_delete_none']    = 'Não há arquivos de backup selecionados para exclusão';
$lang['database_drop_confirm']          = 'Realmente excluir as seguites tabelas do banco de dados?';
$lang['database_drop_none']             = 'Não há tabelas selecionados para drop';
$lang['database_drop_attention']        = '<p>Excluir tabelas do banco de dados irá resultar em perda de dados.</p><p><strong>Isso pode gerar erros em sua aplicação.</strong> </p>';
$lang['database_repair_none']           = 'No tables were selected to repair';

$lang['database_table_name']            = 'Nome da Tabela';
$lang['database_records']               = 'Gravações';
$lang['database_data_size']             = 'Tamanho dos Dados';
$lang['database_index_size']            = 'Tamanho da Index';
$lang['database_data_free']             = 'Dados Livres';
$lang['database_engine']                = 'Motor';
$lang['database_no_tables']             = 'Tabelas não foram encontradas para o banco de dados atual.';

$lang['database_restore_results']       = 'Restaurar Resultados';
$lang['database_back_to_tools']         = 'Voltar para as Ferramentas de Banco de Dados';
$lang['database_restore_file']          = 'Restaurar arquivo de banco de dados de';
$lang['database_restore_attention']     = '<p>Restaurando um backup de arquivo de banco de dados irá apagar um ou todos banco de dados previamente existentes em seu SQL.';

$lang['database_database_settings']     = 'Configurações de banco de dados';
$lang['database_server_type']           = 'Tipo de Servidor';
$lang['database_hostname']              = 'Nome do Host';
$lang['database_dbname']                = 'Nome do Base de dados';
$lang['database_advanced_options']      = 'Opções Avançadas';
$lang['database_persistent_connect']    = 'Conecções Percistentes';
$lang['database_display_errors']        = 'Mostar erros de Base de dados';
$lang['database_enable_caching']        = 'Ativar cache de consulta';
$lang['database_cache_dir']             = 'Diretório de Cache';
$lang['database_prefix']                = 'Prefixo';

$lang['database_servers']               = 'Servidores';
$lang['database_driver']                = 'Driver';
$lang['database_persistent']            = 'Persistente';
$lang['database_debug_on']              = 'Debug On';
$lang['database_strict_mode']           = 'Modo Strict';
$lang['database_running_on_1']          = 'Você está atualmente em execução em';
$lang['database_running_on_2']          = 'Servidor.';
$lang['database_serv_dev']              = 'Desenvolvimento';
$lang['database_serv_test']             = 'Testando';
$lang['database_serv_prod']             = 'Produção';

$lang['database_successful_save']       = 'Suas configurações foram salvas com sucesso.';
$lang['database_erroneous_save']        = 'Houve um erro ao salvar as configurações.';
$lang['database_successful_save_act']   = 'Configurações de banco de dados foram gravados com êxito';
$lang['database_erroneous_save_act']    = 'Configurações de banco de dados não foram salvas corretamente';

$lang['database_sql_query']             = 'SQL Query';
$lang['database_total_results']         = 'Resultado Total';
$lang['database_no_rows']               = 'Não foram encontrados dados para a tabela.';
$lang['database_browse']                = 'Navegar';
