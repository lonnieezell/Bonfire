<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Emailer language file (Brazilian Portuguese)
 *
 * Localization strings used by Bonfire's Emailer module.
 *
 * @package Bonfire\Modules\Emailer\Language\Portuguese_Br
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/developer
 */

$lang['emailer_template']                = 'Template';
$lang['emailer_email_template']          = 'Template de Email';
$lang['emailer_emailer_queue']           = 'Fila de Email ';

$lang['emailer_system_email']            = 'Email do sistema';
$lang['emailer_system_email_note']       = 'Todos os e-mail gerados pelo sistema são enviados a partir de.';
$lang['emailer_email_server']            = 'Email Server';
$lang['emailer_settings']                = 'Configurações de e-mail';
$lang['emailer_settings_note']           = '<b>Mail</b> usa as funções padrões PHP de email, por isso não são necessários ajustes.';
$lang['emailer_location']                = 'localização';
$lang['emailer_server_address']          = 'Endereço do Servidor';
$lang['emailer_port']                    = 'Porta';
$lang['emailer_timeout_secs']            = 'Timeout (segundos)';
$lang['emailer_email_type']              = 'Tipo de e-mail';
$lang['emailer_test_settings']           = 'E-mail Configurações de teste';

$lang['emailer_template_note']           = 'Os e-mails são enviados em formato HTML. Eles podem ser personalizados editando o cabeçalho e rodapé, abaixo.';
$lang['emailer_header']                  = 'Cabeçalho';
$lang['emailer_footer']                  = 'Rodapé';

$lang['emailer_test_header']             = 'Teste suas definições';
$lang['emailer_test_intro']              = 'Insira um endereço de e-mail abaixo para verificar se as configurações de e-mail estão funcionando. <br/> Salvar as configurações atuais antes dos testes.';
$lang['emailer_test_button']             = 'Enviar e-mail de teste';
$lang['emailer_test_result_header']      = 'Resultados do Teste';
$lang['emailer_test_debug_header']       = 'Debug Information';
$lang['emailer_test_success']            = 'The email appears to be set correctly. If you do not see the email in your inbox, try looking in your Spam box or Junk mail.';
$lang['emailer_test_error']              = 'The email looks like it is not set correctly.';

$lang['emailer_test_mail_subject']       = 'Parabéns! O sistema de email (Emailer) do seu Bonfire está funcionando!';
$lang['emailer_test_mail_body']          = 'Se você estiver vendo este e-mail, então provavelmente o sistema de emails (Emailer) do seu Bonfire está funcionando!';

$lang['emailer_stat_no_queue']           = 'Você não tem nenhum e-mails na fila.';
$lang['emailer_total_in_queue']          = 'Total de Emails na fila:';
$lang['emailer_total_sent']              = 'Total de Emails enviados:';

$lang['emailer_sent']                    = 'Enviados';
$lang['emailer_attempts']                = 'tentativas';
$lang['emailer_id']                      = 'ID';
$lang['emailer_to']                      = 'Para';
$lang['emailer_subject']                 = 'Assunto';

$lang['emailer_missing_data']            = 'Um ou mais campos obrigatórios não foram preenchidos.';
$lang['emailer_no_debug']                = 'O E-mail foi enfileirado. Não há dados de depuração (debug) disponível.';

$lang['emailer_delete_success']          = '%d registros deletados.';
$lang['emailer_delete_failure']          = 'Não foi possível excluir os registros: %s';
$lang['emailer_delete_error']            = 'Erro ao deletar os registros: %s';
$lang['emailer_delete_confirm']          = 'Tem certeza de que deseja excluir estes e-mails?';

// $lang['emailer_create_email']         = 'Send New Email';
// $lang['emailer_create_setting']       = 'Email Configure';
// $lang['emailer_create_email_error']   = 'Error in creating emails: %s';
// $lang['emailer_create_email_success'] = 'Email(s) are inserted into email queue.';
// $lang['emailer_create_email_failure'] = 'Fail in creating emails: %s';

$lang['form_validation_emailer_system_email']  = 'Email do sistema';
$lang['form_validation_emailer_email_server']  = 'Email Server';
$lang['form_validation_emailer_sendmail_path'] = 'Sendmail Path';
// $lang['form_validation_emailer_smtp_address']  = 'SMTP Server Address';
// $lang['form_validation_emailer_smtp_username'] = 'SMTP Username';
// $lang['form_validation_emailer_smtp_password'] = 'SMTP Password';
// $lang['form_validation_emailer_smtp_port']     = 'SMTP Port';
// $lang['form_validation_emailer_smtp_timeout']  = 'SMTP timeout';
