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

/**
 * Emailer Language File (Italian)
 *
 * @package     Bonfire\Modules\Database\Emailer\Italian
 * @author      Lorenzo Sanzari (ulisse73@quipo.it)
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

$lang['em_template'] = 'Modello';
$lang['em_email_template'] = 'Modello Email';
$lang['em_emailer_queue'] = 'Coda Email';

$lang['em_system_email'] = 'Email di Sistema';
$lang['em_system_email_note'] = 'L\'indirizzo dal quale saranno inviate tutte le email generate dal sistema.';
$lang['em_email_server'] = 'Server Email';
$lang['em_settings'] = 'Impostazioni Email';
$lang['em_settings_note'] = '<b>Mail</b> usa la funzione mail standard del PHP, per cui non sono necessarie configurazioni.';
$lang['em_location'] = 'luogo';
$lang['em_server_address'] = 'Indirizzo Server';
$lang['em_port'] = 'Porta';
$lang['em_timeout_secs'] = 'Timeout(secondi)';
$lang['em_email_type'] = 'Tipo Email';
$lang['em_save_settings'] = 'Salva Configurazioni';
$lang['em_test_settings'] = 'Prova Configurazioni Email';
$lang['em_sendmail_path'] = 'Percorso Sendmail';
$lang['em_smtp_address'] = 'Indirizzo Server SMTP';
$lang['em_smtp_username'] = 'Nome Utente SMTP';
$lang['em_smtp_password'] = 'Password SMTP';
$lang['em_smtp_port'] = 'Porta SMTP';
$lang['em_smtp_timeout'] = 'Timeout SMTP';

$lang['em_template_note'] = 'Le email sono inviate in formato HTML. Possono essere personalizzate modificando l\'header e il footer in basso.';
$lang['em_header'] = 'Header';
$lang['em_footer'] = 'Footer';
$lang['em_save_template'] = 'Salva modello';

$lang['em_test_header'] = 'Prova le tue impostazioni';
$lang['em_test_intro'] = 'Inserisci un indirizzo email in basso per verificare che le tue impostazioni email funziono.<br />Salva le impostazioni correnti prima del test.';
$lang['em_test_button'] = 'Invia Email di prova';
$lang['em_test_result_header'] = 'Risultati della prova';
$lang['em_test_debug_header'] = 'Informazioni di debug';
$lang['em_test_success'] = 'Pare che l\'email sia configurata correttamente. Se non visualizzi l\'email nella tua cartella di posta in arrivo, guarda nella cartella spam o junk.';
$lang['em_test_error'] = 'Pare che l\'email non sia configurata correttamente.';

$lang['em_test_mail_subject'] = 'Congratulazioni! Il tuo Emailer Bonfire funziona!';
$lang['em_test_mail_body'] = 'Se stai vedendo questa mail. vuol dire che il tuo emailer Bonfire funziona!';

$lang['em_stat_no_queue'] = 'Al momento non hai emails in coda.';
$lang['em_total_in_queue'] = 'Totale email in coda:';
$lang['em_total_sent'] = 'Totale emails inviate:';
$lang['em_force_process'] = 'Elabora ora';
$lang['em_insert_test'] = 'Inserisci email di prova';

$lang['em_sent'] = 'Inviata';
$lang['em_attempts'] = 'Tentativi';
$lang['em_id'] = 'ID';
$lang['em_to'] = 'A';
$lang['em_subject'] = 'Oggetto';
$lang['em_email_subject'] = 'Oggetto email';
$lang['em_email_content'] = 'Contenuto email';

$lang['em_missing_data'] = 'Uno o più campi obbligatori sono vuoti.';
$lang['em_no_debug'] = 'L\'email è stata messa in coda. Nessun dato di debug disponibile.';

$lang['em_delete_success'] = '%d records eliminati.';
$lang['em_delete_failure'] = 'Impossibile eliminare i records: %s';
$lang['em_delete_error'] = 'Errore durante la cancellazione dei records: %s';
$lang['em_delete_confirm'] = 'Sei sicuro di voler cancellare queste emails?';

$lang['em_create_email'] = 'Invia nuova mail';
$lang['em_create_setting'] = 'Configura email';
$lang['em_create_email_error'] = 'Errore nella creazione dell\'email: $s';
$lang['em_create_email_success'] = 'Le email sono state inserite in coda.';
$lang['em_create_email_failure'] = 'Creazione emails fallita: %s';
