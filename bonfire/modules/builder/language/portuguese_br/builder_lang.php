<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (Brazilian Portuguese)
 *
 * @package     Bonfire\Modules\Builder\Language\Portuguese_br
 * @author      Bonfire Dev Team
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

// INDEX page
$lang['mb_create_button'] = 'Criar Módulo';
$lang['mb_create_link'] = 'Criar um novo Módulo';
$lang['mb_create_note'] = 'Utilize o nosso assistente wizbang de criaçõa de módulo para criar o seu próximo módulo. Nós fazemos todo o trabalho pesado, gerando todos os controladores, modelos, visualizações e arquivos de idioma que você precisa.';
$lang['mb_not_writable_note'] = 'Erro : A pasta application/modules não é gravável, então o módulo não podê ser gravado no servidor. Por favor dê permissão de gravação nesta pasta e atualize esta página.';
$lang['mb_generic_description'] = 'Sua descrição aqui.';
$lang['mb_installed_head'] = 'Instalados os módulos de aplicação';
$lang['mb_module'] = 'Módulos';
$lang['mb_no_modules'] = 'Módulo não intalado.';

$lang['mb_table_name'] = 'Nome';
$lang['mb_table_version'] = 'Versão';
$lang['mb_table_author'] = 'Autor';
$lang['mb_table_description'] = 'Descrição';

// OUTPUT page
$lang['mb_out_success'] = 'Módulo criado com sucesso! Abaixo você encontrará a lista dos Controladores, Modelo, Línguagens, Migração e visualizar os arquivos que foram criados durante esse processo. Javascript, Modelos e SQL serão incluídos caso você tenha selecionado a opção "Gerar Migração".';
$lang['mb_out_success_note'] = 'NOTA: Por favor, adicione validação de entrada de usuário como requirido. Este código é para ser usado como ponto de partida apenas.';
$lang['mb_out_tables_success'] = 'As tabelas de banco de dados foram instalados automaticamente para você. Você pode verificar ou desinstalar, se quiser, a partir da Sessão %s .';
$lang['mb_out_tables_error'] = 'As tabelas de banco de dados <strong>NÃO</strong> foram instalados automaticamente para você. Você ainda precisa ir para a seção %s e migrar as tabelas(s) do seu banco de dados, para que você possa trabalhar com eles.';
$lang['mb_out_acl'] = 'Acesse o Arquivo de Controle';
$lang['mb_out_acl_path'] = 'migrations/001_Install_%s_permissions.php';
$lang['mb_out_config'] = 'Arquivo de Configuração';
$lang['mb_out_config_path'] = 'config/config.php';
$lang['mb_out_controller'] = 'Controladores';
$lang['mb_out_controller_path'] = 'controllers/%s.php';
$lang['mb_out_model'] = 'Modelos';
$lang['mb_out_model_path'] = '%s_model.php';
$lang['mb_out_view'] = 'Visualizadores';
$lang['mb_out_view_path'] = 'views/%s.php';
$lang['mb_out_lang'] = 'Arquivo de Linguagem';
$lang['mb_out_lang_path'] = '%s_lang.php';
$lang['mb_out_migration'] = 'Arquivo de Migração';
$lang['mb_out_migration_path'] = 'migrations/002_Install_%s.php';
$lang['mb_new_module'] = 'Novo Módulo';
$lang['mb_exist_modules'] = 'Módulo existente';

// FORM page
$lang['mb_form_note'] = '<p><b>Preencha os campos que você gostaria em seu módulo (Um campo "id" é criado automaticamente). Se você deseja criar um aquivo migrations para a criação de uma tabela do banco de dados para este módulo marque a opção "Criar Tabela para o Módulo". </b></p><p>Será criado um módulo completo (modelo, controlador e visualizadores), se você escolher, arquivo de banco de Migrações(s).</p>';

$lang['mb_table_note'] = '<p>Sua tabela será criada com pelo menos um campo, o campo de chave primária que será utilizado como um identificador único e como um índice. Se você quiser campos adicionais, clique no número desejado para adicioná-los a este formulário.</p>';

$lang['mb_field_note'] = '<p><b>NOTA: Para todos os campos</b><br />Se o tipo de campo do banco de dados é "Enum" ou "Set", por favor insira os valores usando este formato: \'a\',\'b\',\'c\'...<br /> Se você precisa colocar uma barra invertida ("\\") ou um apóstrofo entre esses valores, precedê-lo com uma barra invertida (por exemplo \'\\\\xyz\' or \'a\\\'b\').</p>';

$lang['mb_form_errors'] = 'Por favor, corrija os erros abaixo.';
$lang['mb_form_mod_details'] = 'Detalhes do módulo';
$lang['mb_form_mod_name'] = 'Nome do Módulo';
$lang['mb_form_mod_name_ph'] = 'Fóruns, Blogs, ToDo';
$lang['mb_form_mod_desc'] = 'Descrição do Módulo';
$lang['mb_form_mod_desc_ph'] = 'A descrição do módulo';
$lang['mb_form_contexts'] = 'contextos Obrigatório';
$lang['mb_form_public'] = 'Público';
$lang['mb_form_table_details'] = 'Detalhes da tabela';
$lang['mb_form_actions'] = 'Ações do Controlador';
$lang['mb_form_primarykey'] = 'Chave Primária';
$lang['mb_form_delims'] = 'Delimitadores de inputs de formulários';
$lang['mb_form_err_delims'] = 'Delimitadores de Formulários de Erro';
$lang['mb_form_text_ed'] = 'editor de textarea';
$lang['mb_form_soft_deletes'] = 'Usar exclusão organizada?';
$lang['mb_form_use_created'] = 'Usar campo "Criado"?';
$lang['mb_form_use_modified'] = 'Usar campo "Modificado"?';
$lang['mb_form_created_field'] = 'Nome do campo "Criado"?';
$lang['mb_form_created_field_ph']	= 'created_on';
$lang['mb_form_modified_field'] = 'Nome do campo "Modificado"?';
$lang['mb_form_modified_field_ph']	= 'modified_on';
$lang['mb_form_generate'] = 'Criar tablela para o Módulo';
$lang['mb_form_role_id'] = 'Dê à função acesso completo';
$lang['mb_form_fieldnum'] = 'Campos da tabela adicional';
$lang['mb_form_field_details'] = 'Detalhes do campo';
$lang['mb_form_table_name'] = 'Nome da tabela';
$lang['mb_form_table_name_ph'] = 'Minúsculas, sem espaços';
$lang['mb_form_table_as_field_prefix'] = 'Use table name as field prefix';
$lang['mb_form_label'] = 'Rótulo';
$lang['mb_form_label_ph'] = 'O nome que será utilizado no site';
$lang['mb_form_fieldname'] = 'Nome (sem espaços)';
$lang['mb_form_fieldname_ph'] = 'O nome do campo para o banco de dados. use letras Minúsculas!';
$lang['mb_form_type'] = 'Tipo de Entrada d site';
$lang['mb_form_length'] = 'Comprimento máximo<b>-ou-</b> Valores';
$lang['mb_form_length_ph'] = '30, 255, 1000, etc...';
$lang['mb_form_dbtype'] = 'Tipo de Banco de dados';
$lang['mb_form_rules'] = 'Regras de Validação';
$lang['mb_form_rules_limits'] = 'Limitações de Input';
$lang['mb_form_required'] = 'Requirido';
$lang['mb_form_unique'] = 'Unico';
$lang['mb_form_trim'] = 'aparar/podar';
$lang['mb_form_valid_email'] = 'E-mail válido';
$lang['mb_form_is_numeric'] = '0-9';
$lang['mb_form_alpha'] = 'a-Z';
$lang['mb_form_alpha_dash'] = 'a-Z, 0-9, e _-';
$lang['mb_form_alpha_numeric'] = 'a-Z e 0-9';
$lang['mb_form_add_fld_button'] = 'Adicione um outro campo';
$lang['mb_form_show_advanced'] = '(Alterne) Opções Avançadas';
$lang['mb_form_show_more'] = '...Alterne mais regras...';
$lang['mb_form_integer'] = 'Inteiros';
$lang['mb_form_is_decimal'] = 'Números Decimais';
$lang['mb_form_is_natural'] = 'Numeros Naturais';
$lang['mb_form_is_natural_no_zero'] = 'Natural, sem zeros';
$lang['mb_form_valid_ip'] = 'Válido IP';
$lang['mb_form_valid_base64'] = 'Base64 Válido';
$lang['mb_form_alpha_extra'] = 'AlphaNuméricos, sublinhado, traço, períodos e espaços.';
$lang['mb_form_match_existing']	= 'Certifique-se de preencher com o nome do campo existente!';
$lang['mb_form_module_db_no']	= 'Nenhuma';
$lang['mb_form_module_db_create']	= 'Criar Nova Tabela';
$lang['mb_form_module_db_exists']	= 'Construir a partir de Tabela Existente';
$lang['mb_form_build']			= 'Criar módulo';

// Activities
$lang['mb_act_create'] = 'Modulo Criado';
$lang['mb_act_delete'] = 'Módulo excluído';

// Create Context
$lang['mb_create_a_context']	= 'Criar um Contexto';
$lang['mb_tools']				= 'Ferramentas';
$lang['mb_mod_builder']			= 'Construtor de Módulos';
$lang['mb_new_context']			= 'Novo Contexto';
$lang['mb_no_context_name']		= 'Nome de contexto inválido.';
$lang['mb_cant_write_config']	= 'Não foi possível gravar o arquivo de configuração.';
$lang['mb_context_exists']		= 'Contexto já existe no arquivo de configuração da aplicação.';
$lang['mb_context_name']        = 'Nome do Contexto';
$lang['mb_context_name_help']   = 'Não pode conter espaços.';
$lang['mb_context_create_success']  = 'Contexto criado com sucesso.';
$lang['mb_context_create_error']    = 'Erro criado Contexto: ';
$lang['mb_context_create_intro']    = 'Cria e define um novo Context.';
$lang['mb_roles_label']         = 'Permitir para os papéis:';
$lang['mb_context_migrate']     = 'Criar uma Migração da Aplicação?';
$lang['mb_context_submit']      = 'Criar';

// Create Module
$lang['mb_module_table_not_exist']  = 'A tabela com o nome especificado não existe';
$lang['mb_toolbar_title_create'] = 'Construtor de Módulos';

// Delete Module
$lang['mb_delete_trans_false']  = 'Não foi possível excluir este módulo.';
$lang['mb_delete_success']      = 'O módilo e as entradas associadas no Banco de Dados foram excluídos com sucesso.';
$lang['mb_delete_success_db_only']  = ' PORÉM, a pasta e os arquivos do módulo não foram excluídos. Eles devem ser excluídos manualmente.';

// Validate Form
$lang['mb_contexts_content']    = 'Contexts :: Content';
$lang['mb_contexts_developer']  = 'Contexts :: Developer';
$lang['mb_contexts_public']     = 'Contexts :: Public';
$lang['mb_contexts_reports']    = 'Contexts :: Reports';
$lang['mb_contexts_settings']   = 'Contexts :: Settings';
$lang['mb_module_db']           = 'Tabela do Módulo';
$lang['mb_form_action_create']  = 'Form Actions :: Criar';
$lang['mb_form_action_delete']  = 'Form Actions :: Excluir';
$lang['mb_form_action_edit']    = 'Form Actions :: Editar';
$lang['mb_form_action_view']    = 'Form Actions :: Listar';
$lang['mb_soft_delete_field']   = 'Nome do campo de "Soft" Delete';
$lang['mb_soft_delete_field_ph']    = 'deleted';
$lang['mb_validation_no_match'] = '%s %ss (%s & %s) must be unique!';
$lang['mb_modulename_check']    = 'The %s field is not valid';