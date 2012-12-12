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

$lang['db_maintenance'] = 'Manutenção';
$lang['db_backups'] = 'Backups';

$lang['db_backup_warning'] = 'Nota: Devido ao tempo de execução limitado e memória disponível para o PHP, não é possível realizar backup de bancos de dados muito grande. Se seu banco de dados é muito grande, você pode precisar fazer backup diretamente de seu servidor SQL via linha de comando, pelo PhpMyAdmin, ou contacte o administrador do seu servidor.';
$lang['db_filename'] = 'Nome do Arquivo';

$lang['db_drop_question'] = 'Add &lsquo;Drop Tablelas&rsquo; comando para SQL?';
$lang['db_drop_tables'] = 'Drop Tablelas';
$lang['db_compress_question'] = 'Timpo de Compressão?';
$lang['db_compress_type'] = 'Timpo de Compressão';
$lang['db_insert_question'] = 'Add &lsquo;Inserts&rsquo; de dados para SQL?';
$lang['db_add_inserts'] = 'Add Inserts';

$lang['db_restore_note'] = 'A opção de restauração só é capaz de ler arquivos descompactados. Compressão Gzip e Zip são bons se você quiser apenas um backup para baixar e armazenar no seu computador.';

$lang['db_apply'] = 'Aplicar';
$lang['db_gzip'] = 'gzip';
$lang['db_zip'] = 'zip';
$lang['db_backup'] = 'Backup';
$lang['db_tables'] = 'Tablelas';
$lang['db_restore'] = 'Restaurar';
$lang['db_database'] = 'Database';
$lang['db_drop'] = 'Drop';
$lang['db_repair'] = 'Reparar';
$lang['db_optimize'] = 'Optimizar';
$lang['db_migrations'] = 'Migrações';

$lang['db_delete_note'] = 'Deletar arquivos de backup selecionados: ';
$lang['db_no_backups'] = 'Não foram encontrados backups anteriores.';
$lang['db_backup_delete_confirm'] = 'Realmente apagar os arquivos de backup seguintes?';
$lang['db_backup_delete_none'] = 'Não há arquivos de backup selecionados para exclusão';
$lang['db_drop_confirm'] = 'Realmente excluir as seguites tabelas do banco de dados?';
$lang['db_drop_none'] = 'Não há tabelas selecionados para drop';
$lang['db_drop_attention'] = '<p>Excluir tabelas do banco de dados irá resultar em perda de dados.</p><p><strong>Isso pode gerar erros em sua aplicação.</strong> </p>';
$lang['db_repair_none'] = 'No tables were selected to repair';

$lang['db_table_name'] = 'Nome da Tabela';
$lang['db_records'] = 'Gravações';
$lang['db_data_size'] = 'Tamanho dos Dados';
$lang['db_index_size'] = 'Tamanho da Index';
$lang['db_data_free'] = 'Dados Livres';
$lang['db_engine'] = 'Motor';
$lang['db_no_tables'] = 'Tabelas não foram encontradas para o banco de dados atual.';

$lang['db_restore_results'] = 'Restaurar Resultados';
$lang['db_back_to_tools'] = 'Voltar para as Ferramentas de Banco de Dados';
$lang['db_restore_file'] = 'Restaurar arquivo de banco de dados de';
$lang['db_restore_attention'] = '<p>Restaurando um backup de arquivo de banco de dados irá apagar um ou todos banco de dados previamente existentes em seu SQL.';

$lang['db_database_settings'] = 'Configurações de banco de dados';
$lang['db_server_type'] = 'Tipo de Servidor';
$lang['db_hostname'] = 'Nome do Host';
$lang['db_dbname'] = 'Nome do Base de dados';
$lang['db_advanced_options'] = 'Opções Avançadas';
$lang['db_persistant_connect'] = 'Conecções Percistentes';
$lang['db_display_errors'] = 'Mostar erros de Base de dados';
$lang['db_enable_caching'] = 'Ativar cache de consulta';
$lang['db_cache_dir'] = 'Diretório de Cache';
$lang['db_prefix'] = 'Prefixo';

$lang['db_servers'] = 'Servidores';
$lang['db_driver'] = 'Driver';
$lang['db_persistant'] = 'Persistente';
$lang['db_debug_on'] = 'Debug On';
$lang['db_strict_mode'] = 'Modo Strict';
$lang['db_running_on_1'] = 'Você está atualmente em execução em';
$lang['db_running_on_2'] = 'Servidor.';
$lang['db_serv_dev'] = 'Desenvolvimento';
$lang['db_serv_test'] = 'Testando';
$lang['db_serv_prod'] = 'Produção';

$lang['db_successful_save'] = 'Suas configurações foram salvas com sucesso.';
$lang['db_erroneous_save'] = 'Houve um erro ao salvar as configurações.';
$lang['db_successful_save_act'] = 'Configurações de banco de dados foram gravados com êxito';
$lang['db_erroneous_save_act'] = 'Configurações de banco de dados não foram salvas corretamente';

$lang['db_sql_query'] = 'SQL Query';
$lang['db_total_results'] = 'Resultado Total';
$lang['db_no_rows'] = 'Não foram encontrados dados para a tabela.';
$lang['db_browse'] = 'Navegar';
