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

// INDEX page
$lang['mb_create_button'] = 'Criar Módulo';
$lang['mb_create_link'] = 'Criar um novo Módulo';
$lang['mb_create_note'] = 'Utilize o nosso assistente wizbang de criaçõa de módulo para criar o seu próximo módulo. Nós fazemos todo o trabalho pesado, gerando todos os controladores, modelos, visualizações e arquivos de idioma que você precisa.';
$lang['mb_not_writeable_note'] = 'Erro : A pasta application/modules não é gravável, então o módulo não podê ser gravado no servidor. Por favor dê permissão de gravação nesta pasta e atualize esta página.';
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
$lang['mb_form_mod_desc_ph'] = 'A lista de itens para fazer';
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
$lang['mb_form_modified_field'] = '"Nome do campo "Modificado?';
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
$lang['mb_form_match_existing']	= 'Ensure this matches the existing fieldname!';

// Activities
$lang['mb_act_create'] = 'Modulo Criado';
$lang['mb_act_delete'] = 'Módulo excluído';

// $lang['mb_create_a_context']	= 'Create A Context';
// $lang['mb_tools']				= 'Tools';
// $lang['mb_mod_builder']			= 'Module Builder';
// $lang['mb_new_context']			= 'New Context';
// $lang['mb_no_context_name']		= 'Invalid Context name.';
// $lang['mb_cant_write_config']	= 'Unable to write to config file.';
// $lang['mb_context_exists']		= 'Context already exists in application config file.';
