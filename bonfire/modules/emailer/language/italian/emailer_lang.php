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
 * Emailer Language File (Italian)
 *
 * Localization strings used by Bonfire's Emailer module.
 *
 * @package Bonfire\Modules\Emailer\Language\Italian
 * @author  Lorenzo Sanzari <ulisse73@quipo.it>
 * @link    http://cibonfire.com/docs/developer
 */

$lang['emailer_template']             = 'Modello';
$lang['emailer_email_template']       = 'Modello Email';
$lang['emailer_emailer_queue']        = 'Coda Email';

$lang['emailer_system_email']         = 'Email di Sistema';
$lang['emailer_system_email_note']    = 'L\'indirizzo dal quale saranno inviate tutte le email generate dal sistema.';
$lang['emailer_email_server']         = 'Server Email';
$lang['emailer_settings']             = 'Impostazioni Email';
$lang['emailer_settings_note']        = '<b>Mail</b> usa la funzione mail standard del PHP, per cui non sono necessarie configurazioni.';
$lang['emailer_location']             = 'luogo';
$lang['emailer_server_address']       = 'Indirizzo Server';
$lang['emailer_port']                 = 'Porta';
$lang['emailer_timeout_secs']         = 'Timeout(secondi)';
$lang['emailer_email_type']           = 'Tipo Email';
$lang['emailer_save_settings']        = 'Salva Configurazioni';
$lang['emailer_test_settings']        = 'Prova Configurazioni Email';
$lang['emailer_sendmail_path']        = 'Percorso Sendmail';
$lang['emailer_smtp_address']         = 'Indirizzo Server SMTP';
$lang['emailer_smtp_username']        = 'Nome Utente SMTP';
$lang['emailer_smtp_password']        = 'Password SMTP';
$lang['emailer_smtp_port']            = 'Porta SMTP';
$lang['emailer_smtp_timeout']         = 'Timeout SMTP';

$lang['emailer_template_note']        = 'Le email sono inviate in formato HTML. Possono essere personalizzate modificando l\'header e il footer in basso.';
$lang['emailer_header']               = 'Header';
$lang['emailer_footer']               = 'Footer';
$lang['emailer_save_template']        = 'Salva modello';

$lang['emailer_test_header']          = 'Prova le tue impostazioni';
$lang['emailer_test_intro']           = 'Inserisci un indirizzo email in basso per verificare che le tue impostazioni email funziono.<br />Salva le impostazioni correnti prima del test.';
$lang['emailer_test_button']          = 'Invia Email di prova';
$lang['emailer_test_result_header']   = 'Risultati della prova';
$lang['emailer_test_debug_header']    = 'Informazioni di debug';
$lang['emailer_test_success']         = 'Pare che l\'email sia configurata correttamente. Se non visualizzi l\'email nella tua cartella di posta in arrivo, guarda nella cartella spam o junk.';
$lang['emailer_test_error']           = 'Pare che l\'email non sia configurata correttamente.';

$lang['emailer_test_mail_subject']    = 'Congratulazioni! Il tuo Emailer Bonfire funziona!';
$lang['emailer_test_mail_body']       = 'Se stai vedendo questa mail. vuol dire che il tuo emailer Bonfire funziona!';

$lang['emailer_stat_no_queue']        = 'Al momento non hai emails in coda.';
$lang['emailer_total_in_queue']       = 'Totale email in coda:';
$lang['emailer_total_sent']           = 'Totale emails inviate:';
$lang['emailer_force_process']        = 'Elabora ora';
$lang['emailer_insert_test']          = 'Inserisci email di prova';

$lang['emailer_sent']                 = 'Inviata';
$lang['emailer_attempts']             = 'Tentativi';
$lang['emailer_id']                   = 'ID';
$lang['emailer_to']                   = 'A';
$lang['emailer_subject']              = 'Oggetto';
$lang['emailer_email_subject']        = 'Oggetto email';
$lang['emailer_email_content']        = 'Contenuto email';

$lang['emailer_missing_data']         = 'Uno o più campi obbligatori sono vuoti.';
$lang['emailer_no_debug']             = 'L\'email è stata messa in coda. Nessun dato di debug disponibile.';

$lang['emailer_delete_success']       = '%d records eliminati.';
$lang['emailer_delete_failure']       = 'Impossibile eliminare i records: %s';
$lang['emailer_delete_error']         = 'Errore durante la cancellazione dei records: %s';
$lang['emailer_delete_confirm']       = 'Sei sicuro di voler cancellare queste emails?';

$lang['emailer_create_email']         = 'Invia nuova mail';
$lang['emailer_create_setting']       = 'Configura email';
$lang['emailer_create_email_error']   = 'Errore nella creazione dell\'email: $s';
$lang['emailer_create_email_success'] = 'Le email sono state inserite in coda.';
$lang['emailer_create_email_failure'] = 'Creazione emails fallita: %s';

$lang['form_validation_emailer_system_email']  = 'Email di Sistema';
$lang['form_validation_emailer_email_server']  = 'Server Email';
$lang['form_validation_emailer_sendmail_path'] = 'Percorso Sendmail';
$lang['form_validation_emailer_smtp_address']  = 'Indirizzo Server SMTP';
$lang['form_validation_emailer_smtp_username'] = 'Nome Utente SMTP';
$lang['form_validation_emailer_smtp_password'] = 'Password SMTP';
$lang['form_validation_emailer_smtp_port']     = 'Porta SMTP';
$lang['form_validation_emailer_smtp_timeout']  = 'Timeout SMTP';
