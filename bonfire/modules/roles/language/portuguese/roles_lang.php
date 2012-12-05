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

$lang['role_manage']				= 'Gerenciar Papeis de Usuário';
$lang['role_no_roles']				= 'Não estão definidos Papeis';
$lang['role_create_button']			= 'Criar um novo Papel';
$lang['role_create_note']			= 'Todos os utilizadores devem ter um Papel (utilizador, editor, administrador,etc)';
$lang['role_distribution']			= 'Distribuição';
$lang['role_account_type']			= 'Tipo de Conta de Utilizador';

$lang['role_name']					= 'Nome do Papel';
$lang['role_max_desc_length']		= 'Max. 255 caracteres.';
$lang['role_default_role']			= 'Papel por defeito';
$lang['role_default_note']			= 'Verificar se deve ser aplicado a todos';
$lang['role_permissions']			= 'Permissões';
$lang['role_permissions_check_note']	= 'Verificar as permissões que se aplicamm a este grupo';
$lang['role_save_role']				= 'Gravar';
$lang['role_delete_role']			= 'DApagar este Papel';
$lang['role_delete_note']			= 'Ao apagar este papel, todos os utilizadores serão convertidos para o Papel por defeito';

$lang['role_roles']					= 'Papeis';
$lang['role_new_role']				= 'Novo Papel';

$lang['role_login_destination']		= 'Destino de login';
$lang['role_destination_note']		= 'A URL do site para redirecionar para a login bem-sucedido.';

$lang['matrix_header']				= 'Permissão Matriz';
$lang['matrix_permission']			= 'Permissão';
$lang['matrix_role']				= 'Papel';
$lang['matrix_note']				= 'Edição permissão instantâneas. Alternar uma checkbox para adicionar ou remover essa permissão para esse papel.';
$lang['matrix_insert_success']		= 'Permissão adicional para o papel.';
$lang['matrix_insert_fail']			= 'Houve um problema adicionando a permissão para o papel:';
$lang['matrix_delete_success']		= 'Permissão removido da papel.';
$lang['matrix_delete_fail']			= 'Houve um problema ao excluir a permissão para o papel: ';
$lang['matrix_auth_fail']			= 'Autenticação: Você não tem a capacidade de gerenciar o controle de acesso para o papel.';