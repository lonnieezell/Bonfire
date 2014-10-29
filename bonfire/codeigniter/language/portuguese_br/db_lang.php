<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = 'Não foi possível determinar as configurações da base de dados por meio da string de conexão especificada.';
$lang['db_unable_to_connect'] = 'Não foi possível conectar ao servidor da base de dados utilizando os dados fornecidos.';
$lang['db_unable_to_select'] = 'Não foi possível encontrar a base de dados: %s';
$lang['db_unable_to_create'] = 'Não foi possível criar a base de dados: %s';
$lang['db_invalid_query'] = 'Query inválida.';
$lang['db_must_set_table'] = 'Especifique a tabela a ser usada na query.';
$lang['db_must_use_set'] = 'Para actualizar um registro, deve utilizar o método "SET".';
$lang['db_must_use_index'] = 'Deve especificar um índice para coincidir com as actualizações em bloco.';
$lang['db_batch_missing_index'] = 'Uma ou mais linhas apresentadas para actualização em bloco está faltando o índice especificado.';
$lang['db_must_use_where'] = 'Não são permitidos updates que não contêm a cláusula "where".';
$lang['db_del_must_use_where'] = 'Não são permitidos deletes que não contêm as cláusulas "where" ou "like".';
$lang['db_field_param_missing'] = 'Para buscar campos, é necessário inserir o nome da tabela como parâmetro.';
$lang['db_unsupported_function'] = 'Este recurso não está disponível para a base de dados que está em uso.';
$lang['db_transaction_failure'] = 'Falha na transacção: Rollback executado.';
$lang['db_unable_to_drop'] = 'Não foi possível apagar a base de dados especificada.';
$lang['db_unsuported_feature'] = 'Recurso não suportado pela plataforma da base de dados que está em uso.';
$lang['db_unsuported_compression'] = 'Formato de compressão de ficheiros escolhido não suportado pelo servidor.';
$lang['db_filepath_error'] = 'Não foi possível escrever dados no caminho especificado.';
$lang['db_invalid_cache_path'] = 'O caminho especificado para cache é inválido ou não há permissão de escrita.';
$lang['db_table_name_required'] = 'Para essa operação, é necessário inserir o nome da tabela.';
$lang['db_column_name_required'] = 'Para essa operação, é necessário inserir o nome da coluna.';
$lang['db_column_definition_required'] = 'Para essa operação, é necessário definir a coluna.';
$lang['db_unable_to_set_charset'] = 'Não foi possível definir o charset da conexão do client: %s';
$lang['db_error_heading'] = 'Ocorreu um erro de base de dados';
$lang['db_must_set_database'] = 'Especifique o nome da base de dados no seu ficheiro de configuração de base de dados.';

/* End of file db_lang.php */
/* Location: ./system/language/portuguese/db_lang.php */