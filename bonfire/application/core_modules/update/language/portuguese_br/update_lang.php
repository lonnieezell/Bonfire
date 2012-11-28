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

$lang['up_toolbar_title'] = 'Gerenciador de atualização';

$lang['up_update_off_message'] = 'Verificações de atualização estão desativadas no arquivo config / application.php.';
$lang['up_curl_disabled_message'] = 'cURL <strong>não esta</strong> atualmente ativado nas configurações do PHP extension. Bonfire não será capaz de verificar se há atualizações até que o cURL seja ativado.';
$lang['up_edge_commits'] = 'New Bleeding Edge Commits';
$lang['up_branch'] = 'Ramo: <b>desenvolver</b>';

$lang['up_author'] = 'Autor';
$lang['up_committed'] = 'Cometido';
$lang['up_message'] = 'Mensagem';

$lang['up_update_message_bleeding'] = 'A <b>bleeding edge</b> update to Bonfire is available.';
$lang['up_update_message_new'] = 'Versão %s do Bonfire está disponível. Você atualmente está rodando %s.';
$lang['up_update_message_latest'] = 'Você está rodando a versão do Bonfire %s. Esta é a última versão disponível do Bonfire.';
$lang['up_update_message_old'] = 'Você está rodando a versão do Bonfire %s. Esta é a última versão <b>estavél</b> disponível, versão %s.';
$lang['up_update_message_unable'] = 'Você está rodando a versão do Bonfire %s. <b>Não é possível visualizar a versão mais recente neste momento.</b>';
// $lang['up_update_message_view']     = 'View Updates';
