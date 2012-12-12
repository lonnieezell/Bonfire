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

$lang['role_intro'] = 'Funções permitem que você defina as habilidades que um usuário pode ter.';
$lang['role_manage'] = 'Gerenciar Funções de Usuário';
$lang['role_no_roles'] = 'Não existem quaisquer funções no sistema.';
$lang['role_create_button'] = 'Criar uma nova função.';
$lang['role_create_note'] = 'Cada usuário precisa de uma função. Verifique se você tem tudo que você precisa.';
$lang['role_account_type'] = 'Tipo de conta';
$lang['role_description'] = 'Descrição';
$lang['role_details'] = 'Detalhes da Função';

$lang['role_name'] = 'Nome da Função';
$lang['role_max_desc_length'] = 'Máx. 255 caracteres.';
$lang['role_default_role'] = 'Função padrão';
$lang['role_default_note'] = 'Este papel deve ser atribuído a todos os novos usuários.';
$lang['role_permissions'] = 'Permisões';
$lang['role_permissions_check_note'] = 'Confira todas as permissões que se aplicam a esse função.';
$lang['role_save_role'] = 'Salvar função';
$lang['role_delete_role'] = 'Deletar esta função';
$lang['role_delete_confirm'] = 'Tem certeza que deseja apagar estes logs?';
$lang['role_delete_note'] = 'No caso de exclusão esta função deixará de existir em todos os usuários que estão atualmente atribuídos a esta função padrão.';
$lang['role_can_delete_role'] = 'Removível';
$lang['role_can_delete_note'] = 'Pode esta função ser excluída?';

$lang['role_roles'] = 'Funções';
$lang['role_new_role'] = 'Nova Função';
$lang['role_new_permission_message'] = 'Você será capaz de editar permissões uma vez que o papel foi criado.';
$lang['role_not_used'] = 'Não usado';

$lang['role_login_destination'] = 'Destino do login';
$lang['role_destination_note'] = 'Redirecionar para a URL do site após a login.';
// $lang['role_default_context']		= 'Default Admin Context';
// $lang['role_default_context_note']	= 'The admin context to load when no context is specified (I.E. http://yoursite.com/admin/)';

$lang['matrix_header'] = 'Permissão Matrix';
$lang['matrix_permission'] = 'Permissão';
$lang['matrix_role'] = 'Função';
$lang['matrix_note'] = 'Permissão de edição instantânea. Alternar uma checkbox para adicionar ou remover a permissão para esta função.';
$lang['matrix_insert_success'] = 'Permissão acicionada para a função.';
$lang['matrix_insert_fail'] = 'Houve um problema ao adicionar a permissão para a função: ';
$lang['matrix_delete_success'] = 'Permissão removido para a função.';
$lang['matrix_delete_fail'] = 'Houve um problema ao excluir a permissão para a função: ';
$lang['matrix_auth_fail'] = 'Autenticação: Você não tem a capacidade de gerenciar o controle de acesso para esta função.';
