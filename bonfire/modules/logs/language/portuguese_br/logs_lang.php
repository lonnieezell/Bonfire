<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Logs module language file (English)
 *
 * Localization strings used by Bonfire's Logs module
 *
 * @package    Bonfire\Modules\Logs\Language\English
 * @author     Bonfire Dev Team
 * @link       http://cibonfire.com/docs/guides
 */

$lang['logs_no_logs'] = 'Não há logs encontrados.';
$lang['logs_not_enabled'] = 'O log in não está atualmente habilitada.';
$lang['logs_the_following'] = 'Log o seguinte:';
$lang['logs_what_0'] = '0 - Nada';
$lang['logs_what_1'] = '1 - Mensagem de erro (incluir erros de PHP)';
$lang['logs_what_2'] = '2 - Mensagens de Debug ';
$lang['logs_what_3'] = '3 - Mensagens de informação';
$lang['logs_what_4'] = '4 - Todas as mensagens';
$lang['logs_what_note'] = 'Os logs mais elevados também inclui todas as mensagens dos números mais baixos. Assim, o Login 2 - Mensagens de depuração também registra 1 - Mensagens de erro.';

$lang['logs_save_button'] = 'Salvar configurações de log';
$lang['logs_delete_button'] = 'Excluir arquivos de log';
$lang['logs_delete1_button'] = 'Excluir este arquivo de log?';
$lang['logs_delete_confirm'] = 'Tem certeza que deseja apagar estes logs?';

$lang['logs_big_file_note'] = 'Os Logs podem criar rapidamente arquivos muito grandes, se houver muita atividade no site. Para sites de clientes, você deve registrar apenas logs de erro.';
$lang['logs_delete_note'] = 'A exclusão de arquivos de log é permanente. Não há volta, por isso, certifique-se de que deseja realmente exluir este arquivo.';
$lang['logs_delete1_note'] = 'Deleting log files is a permanent action. There is no going back, so please make sure you understand what you are doing.';
$lang['logs_delete_confirm'] = 'Tem certeza de que deseja excluir este arquivo de log?';

$lang['logs_not_found'] = 'Ou o arquivo de log não pôde ser localizado, ou ele estava vazio.';
$lang['logs_show_all_entries'] = 'Todas as entradas';
$lang['logs_show_errors'] = 'Somente Erros';

$lang['logs_date'] = 'Data';
$lang['logs_file'] = 'Nome do Arquivo';
$lang['logs_logs'] = 'Logs';
$lang['logs_settings'] = 'Configurações';

$lang['logs_title'] = 'Logs do sistema';
$lang['logs_title_settings'] = 'Configurações do log do sistema';
$lang['logs_deleted'] = '%d arquivos de log excluído';
$lang['logs_filter_label'] = 'Visualizar';
$lang['logs_intro'] = 'Estes são seus erros e logs de debug....';