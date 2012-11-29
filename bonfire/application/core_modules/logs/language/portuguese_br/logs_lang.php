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

$lang['log_no_logs'] = 'Não há logs encontrados.';
$lang['log_not_enabled'] = 'O log in não está atualmente habilitada.';
$lang['log_the_following'] = 'Log o seguinte:';
$lang['log_what_0'] = '0 - Nada';
$lang['log_what_1'] = '1 - Mensagem de erro (incluir erros de PHP)';
$lang['log_what_2'] = '2 - Mensagens de Debug ';
$lang['log_what_3'] = '3 - Mensagens de informação';
$lang['log_what_4'] = '4 - Todas as mensagens';
$lang['log_what_note'] = 'Os logs mais elevados também inclui todas as mensagens dos números mais baixos. Assim, o Login 2 - Mensagens de depuração também registra 1 - Mensagens de erro.';

$lang['log_save_button'] = 'Salvar configurações de log';
$lang['log_delete_button'] = 'Excluir arquivos de log';
$lang['log_delete1_button'] = 'Excluir este arquivo de log?';
$lang['logs_delete_confirm'] = 'Tem certeza que deseja apagar estes logs?';

$lang['log_big_file_note'] = 'Os Logs podem criar rapidamente arquivos muito grandes, se houver muita atividade no site. Para sites de clientes, você deve registrar apenas logs de erro.';
$lang['log_delete_note'] = 'A exclusão de arquivos de log é permanente. Não há volta, por isso, certifique-se de que deseja realmente exluir este arquivo.';
$lang['log_delete1_note'] = 'Deleting log files is a permanent action. There is no going back, so please make sure you understand what you are doing.';
$lang['log_delete_confirm'] = 'Tem certeza de que deseja excluir este arquivo de log?';

$lang['log_not_found'] = 'Ou o arquivo de log não pôde ser localizado, ou ele estava vazio.';
$lang['log_show_all_entries'] = 'Todas as entradas';
$lang['log_show_errors'] = 'Somente Erros';

$lang['log_date'] = 'Data';
$lang['log_file'] = 'Nome do Arquivo';
$lang['log_logs'] = 'Logs';
$lang['log_settings'] = 'Configurações';

$lang['log_title'] = 'Logs do sistema';
$lang['log_title_settings'] = 'Configurações do log do sistema';
$lang['log_deleted'] = '%d arquivos de log excluído';
$lang['log_filter_label'] = 'Visualizar';
$lang['log_intro'] = 'Estes são seus erros e logs de debug....';
