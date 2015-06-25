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

$lang['logs_no_logs'] 			= 'Não existem logs.';
$lang['logs_not_enabled']		= 'Logging não está activo.';
$lang['logs_the_following']		= 'Logs disponíveis:';
$lang['logs_what_0']			= '0 - Sem log';
$lang['logs_what_1']			= '1 - Msg Error(incluindo PHP Errors)';
$lang['logs_what_2']			= '2 - Msg Debug';
$lang['logs_what_3']			= '3 - Msg Information';
$lang['logs_what_4']			= '4 - Todas as Msgs';
$lang['logs_what_note']			= 'O Tipo de Log escolhido inclui o log anterior, ie, log do tipo 3 inclui 1 e 2.';

$lang['logs_save_button']		= 'Gravar definições de LOG';
$lang['logs_delete_button']		= 'Apagar ficheiros de LOG';

$lang['logs_big_file_note']		= 'Atenção ao tamanho dos ficheiros de LOG quando são escolhidos valores elevados.';
$lang['logs_delete_note']		= '<h3>Apagar os ficheiros de LOG?</h3><p>Opção não reversível.</p>';

$lang['logs_not_found']			= 'Log não encontrado ou vazio.';
$lang['logs_show_all_entries']	= 'Mostrar todas as entradas';
$lang['logs_show_errors']		= 'Apenas errors';