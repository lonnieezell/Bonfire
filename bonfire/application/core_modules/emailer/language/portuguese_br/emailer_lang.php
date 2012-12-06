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

$lang['em_template'] = 'Template';
$lang['em_email_template'] = 'Template de Email';
$lang['em_emailer_queue'] = 'Fila de Email ';

$lang['em_system_email'] = 'Email do sistema';
$lang['em_system_email_note'] = 'Todos os e-mail gerados pelo sistema são enviados a partir de.';
$lang['em_email_server'] = 'Email Server';
$lang['em_settings'] = 'Configurações de e-mail';
$lang['em_settings_note'] = '<b>Mail</b> usa as funções padrões PHP de email, por isso não são necessários ajustes.';
$lang['em_location'] = 'localização';
$lang['em_server_address'] = 'Endereço do Servidor';
$lang['em_port'] = 'Porta';
$lang['em_timeout_secs'] = 'Timeout (segundos)';
$lang['em_email_type'] = 'Tipo de e-mail';
$lang['em_test_settings'] = 'E-mail Configurações de teste';

$lang['em_template_note'] = 'Os e-mails são enviados em formato HTML. Eles podem ser personalizados editando o cabeçalho e rodapé, abaixo.';
$lang['em_header'] = 'Cabeçalho';
$lang['em_footer'] = 'Rodapé';

$lang['em_test_header'] = 'Teste suas definições';
$lang['em_test_intro'] = 'Insira um endereço de e-mail abaixo para verificar se as configurações de e-mail estão funcionando. <br/> Salvar as configurações atuais antes dos testes.';
$lang['em_test_button'] = 'Enviar e-mail de teste';
$lang['em_test_result_header'] = 'Resultados do Teste';
$lang['em_test_debug_header'] = 'Debug Information';
$lang['em_test_success'] = 'The email appears to be set correctly. If you do not see the email in your inbox, try looking in your Spam box or Junk mail.';
$lang['em_test_error'] = 'The email looks like it is not set correctly.';

$lang['em_test_mail_subject'] = 'Parabéns! O sistema de email (Emailer) do seu Bonfire está funcionando!';
$lang['em_test_mail_body'] = 'Se você estiver vendo este e-mail, então provavelmente o sistema de emails (Emailer) do seu Bonfire está funcionando!';

$lang['em_stat_no_queue'] = 'Você não tem nenhum e-mails na fila.';
$lang['em_total_in_queue'] = 'Total de Emails na fila:';
$lang['em_total_sent'] = 'Total de Emails enviados:';

$lang['em_sent'] = 'Enviados';
$lang['em_attempts'] = 'tentativas';
$lang['em_id'] = 'ID';
$lang['em_to'] = 'Para';
$lang['em_subject'] = 'Assunto';

$lang['em_missing_data'] = 'Um ou mais campos obrigatórios não foram preenchidos.';
$lang['em_no_debug'] = 'O E-mail foi enfileirado. Não há dados de depuração (debug) disponível.';

$lang['em_delete_success'] = '%d registros deletados.';
$lang['em_delete_failure'] = 'Não foi possível excluir os registros: %s';
$lang['em_delete_error'] = 'Erro ao deletar os registros: %s';
$lang['em_delete_confirm'] = 'Tem certeza de que deseja excluir estes e-mails?';

// $lang['em_create_email']		= 'Send New Email';
// $lang['em_create_setting']		= 'Email Configure';
// $lang['em_create_email_error']	= 'Error in creating emails: %s';
// $lang['em_create_email_success']= 'Email(s) are inserted into email queue.';
// $lang['em_create_email_failure']= 'Fail in creating emails: %s';
