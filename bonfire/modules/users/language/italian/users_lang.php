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
 * Users Language File (Italian)
 *
 * @package     Bonfire\Modules\Users\Language\Italian
 * @author      Lorenzo Sanzari (ulisse73@quipo.it)
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

$lang['us_account_deleted'] = 'Sfortunatamente il tuo account è stato rimosso. Esso non è stato già distrutto definitivamente e <strong>può ancora</strong> essere ripristinato. Contatta l\'amministratore su %s.';

$lang['us_bad_email_pass'] = 'Email o password non corretta.';
$lang['us_must_login'] = 'Devi essere loggato per vedere questa pagina.';
$lang['us_no_permission'] = 'Non hai i permessi per accedere a questa pagina.';
$lang['us_fields_required'] = 'I campi %s e Password devono essere compilati.';

$lang['us_access_logs'] = 'Logs degli accessi';
$lang['us_logged_in_on'] = '<b>%s</b> loggato su %s';
$lang['us_no_access_message'] = '<p>Congratulazioni!</p><p>Tutti i vostri utenti hanno bei ricordi!</p>';
$lang['us_log_create'] = 'nuovo %s creato';
$lang['us_log_edit'] = 'utente modificato';
$lang['us_log_delete'] = 'utente eliminato';
$lang['us_log_logged'] = 'loggato da';
$lang['us_log_logged_out'] = 'disconnesso da';
$lang['us_log_reset'] = 'resetta password.';
$lang['us_log_register'] = 'nuovo account registrato.';
$lang['us_log_edit_profile'] = 'profilo aggiornato';


$lang['us_purge_del_confirm'] = 'Sei davvero sicuro di rimuovere completamente l\'account(s) utente - non c\'è modo di tornare indietro?';
$lang['us_action_purged'] = 'Utenti distrutti.';
$lang['us_action_deleted'] = 'L\'utente è stato eliminato con successo.';
$lang['us_action_not_deleted'] = 'Impossibile eliminare l\'utente:';
$lang['us_delete_account'] = 'Elimina account';
$lang['us_delete_account_note'] = '<h3>Elimina questo account</h3><p>Eliminando questo account revocherai tutti i suoi privilegi sul sito.</p>';
$lang['us_delete_account_confirm'] = 'Sei sicuro di voler eliminare l\'account(s) utente?';

$lang['us_restore_account'] = 'Ripristina account';
$lang['us_restore_account_note'] = '<h3>Ripristina questo account</h3><p>Ripristina l\'account di questo utente</p>';
$lang['us_restore_account_confirm'] = 'Ripristina l\'account di questi utenti?';

$lang['us_role'] = 'Ruolo';
$lang['us_role_lower'] = 'ruolo';
$lang['us_no_users'] = 'Nessun utente trovato.';
$lang['us_create_user'] = 'Crea nuovo utente';
$lang['us_create_user_note'] = '<h3>Crea un nuovo utente</h3><p>Crea un nuovi accountper altri utenti nel tuo ambito.</p>';
$lang['us_edit_user'] = 'Modifica utente';
$lang['us_restore_note'] = 'Ripristina l\'utente e consentigli di nuovo l\'accesso al sito.';
$lang['us_unban_note'] = 'Sblocca l\'utente e consentigli di nuovo l\'accesso al sito.';
$lang['us_account_status'] = 'Stato account';

$lang['us_failed_login_attempts'] = 'Tentativi di accesso fallito';
$lang['us_failed_logins_note'] = '<p>Congratulazioni!</p><p>Tutti i vostri utenti hanno bei ricordi!</p>';

$lang['us_banned_admin_note'] = 'Questo utente è stato bloccato dal sito.';
$lang['us_banned_msg'] = 'Questo account non ha i permessi per accedere al sito.';

$lang['us_first_name'] = 'Nome';
$lang['us_last_name'] = 'Cognome';
$lang['us_address'] = 'Indirizzo';
$lang['us_street_1'] = 'Via1';
$lang['us_street_2'] = 'Via2';
$lang['us_city'] = 'Città';
$lang['us_state'] = 'Stato/Regione';
$lang['us_no_states'] = 'Non ci sono stati/province/contee/regioni per questa nazione. Creala nel file di configurazione degli address';
$lang['us_no_countries'] = 'Impossibile trovare una nazione. Creala nel file di configurazione degli address.';
$lang['us_country'] = 'Nazione';
$lang['us_zipcode'] = 'Codice postale';

$lang['us_user_management'] = 'Gestione utente';
$lang['us_email_in_use'] = 'L\'indirizzo %s è già in uso. Per favore scegline un altro.';

$lang['us_edit_profile'] = 'Modifica profilo';
$lang['us_edit_note'] = 'Inserisci i tuoi dettagli e clicca su Salva.';

$lang['us_reset_password'] = 'Azzera password';
$lang['us_reset_note'] = 'Inserisci la tua email e ti invieremo una password provvisoria.';
$lang['us_send_password'] = 'Invia password';

$lang['us_login'] = 'Per favore accedi';
$lang['us_remember_note'] = 'Ricordami';
$lang['us_sign_up'] = 'Crea un account';
$lang['us_forgot_your_password'] = 'Hai dimenticato la tua password?';
$lang['us_let_me_in'] = 'Accedi';

$lang['us_password_mins'] = 'Minimo 8 caratteri';
$lang['us_register'] = 'Registrati';
$lang['us_already_registered'] = 'Sei già registrato?';

$lang['us_action_save'] = 'Salva utente';
$lang['us_unauthorized'] = 'Non autorizzato. Spiacenti, ma non hai i permessi necessari per gestire il ruolo "%s".';
$lang['us_empty_id'] = 'Nessuno userid fornito. Devi fornire uno userid per eseguire questa azione.';
$lang['us_self_delete'] = 'Non autorizzato. Spiacente, non puoi eliminare te stesso.';

$lang['us_filter_first_letter'] = 'Il nome utente inizia con:';
$lang['us_account_details'] = 'Dettagli account';
$lang['us_last_login'] = 'Ultimo accesso';


$lang['us_account_created_success'] = 'Il tuo account è stato creato. Si prega di accedere.';

$lang['us_invalid_user_id'] = 'Id utente non valido.';
$lang['us_invalid_email'] = 'Impossibile trovare questa email nei nostri records.';

$lang['us_reset_password_note'] = 'Inserisci la tua nuova password in basso per azzerare la tua password.';
$lang['us_reset_invalid_email'] = 'Pare che questa non sia una richiesta di azzeramento password valida.';
$lang['us_reset_pass_subject'] = 'La tua password provvisoria';
$lang['us_reset_pass_message'] = 'Per favore verifica la tua email per avere istruzioni su come azzerare la tua password.';
$lang['us_reset_pass_error'] = 'Impossibile inviare una mail:';

$lang['us_set_password'] = 'Salva nuova password';
$lang['us_reset_password_success'] = 'Per favore accedi usando la tua nuova password.';
$lang['us_reset_password_error'] = 'C\'è stato un errore di azzeramento della tua password: %s';


$lang['us_profile_updated_success'] = 'Profilo aggiornato correttamente.';
$lang['us_profile_updated_error'] = 'C\'è stato un errore nell\'aggiornamento del tuo profilo';

$lang['us_register_disabled'] = 'Non sono consentite registrazioni di nuovi account.';


$lang['us_user_created_success'] = 'Utente creato con successo.';
$lang['us_user_update_success'] = 'Utente aggiornato correttamente.';

$lang['us_user_restored_success'] = 'Utente ripristinato correttamente';
$lang['us_user_restored_error'] = 'Impossibile ripristinare l\'utente:';


/* Activations */
$lang['us_status'] = 'Stato';
$lang['us_inactive_users'] = 'Utenti inattivi';
$lang['us_activate'] = 'Attivazione';
$lang['us_user_activate_note'] = 'Inserisci in tuo codice di attivazione per confermare il tuo indirizzo email e attivare la tua iscrizione.';
$lang['us_activate_note'] = 'Attiva l\'utente e consentigli di accedere al sito.';
$lang['us_deactivate_note'] = 'Disattiva l\'utente per impedire l\'accesso al sito';
$lang['us_activate_enter'] = 'Per favore inserisci il tuo codice di attivazione per continuare.';
$lang['us_activate_code'] = 'Codice di attivazione';
$lang['us_activate_request'] = 'Richiedine uno nuovo';
$lang['us_activate_resend'] = 'Reinvia il codice di attivazione';
$lang['us_activate_resend_note'] = 'Inserisci la tua email e ti reinvieremo il tuo codice di attivazione.';
$lang['us_confirm_activate_code'] = 'Conferma codice di attivazione';
$lang['us_activate_code_send'] = 'Invia codice di attivazione';
$lang['bf_action_activate'] = 'Attivato';
$lang['bf_action_deactivate'] = 'Deattivato';
$lang['us_account_activated'] = 'Attivazione account utente.';
$lang['us_account_deactivated'] = 'Disattivazione account utente.';
$lang['us_account_activated_admin'] = 'Attivazione account amministrativo.';
$lang['us_account_deactivated_admin'] = 'Disattivazione account amministrativo.';
$lang['us_active'] = 'Attivo';
$lang['us_inactive'] = 'Inattivo';
//email subjects
$lang['us_email_subj_activate'] = 'Attiva la tua iscrizione';
$lang['us_email_subj_pending'] = 'Registrazione completata. Attivazione in attesa';
$lang['us_email_thank_you'] = 'Grazie per esserti registrato/a!';
// Activation Statuses
$lang['us_registration_fail'] = 'Registrazione non completata correttamente.';
$lang['us_check_activate_email'] = 'Per favore controlla la tua email per avere istruzioni su come attivare il tuo account.';
$lang['us_admin_approval_pending'] = 'Il tuo account è in attesa di approvazione da parte dell\'amministratore. Riceverai una notifica email se il tuo account sarà attivato.';
$lang['us_account_not_active'] = 'Il tuo account ancora non è attivo, inserisci il tuo codice di attivazione per attivarlo.';
$lang['us_account_active'] = 'Congratulazioni. Il tuo account ora è attivo!..';
$lang['us_account_active_login'] = 'Il tuo account è attivo ed ora puoi accedere.';
$lang['us_account_reg_complete'] = 'Registrazione al sito [SITE_TITLE] completata!';
$lang['us_active_status_changed'] = 'Lo stato utente è stato cambiato con successo.';
$lang['us_active_email_sent'] = 'Email di attivazione inviata.';
// Activation Errors
$lang['us_err_no_id'] = 'Nessun ID utente ricevuto.';
$lang['us_err_status_error'] = 'Lo stato degli utenti non è cambiato:';
$lang['us_err_no_email'] = 'Impossibile inviare una email:';
$lang['us_err_activate_fail'] = 'Per il seguente motivo la tua iscrizione al momento non può essere attivata:';
$lang['us_err_activate_code'] = 'Per favore controlla il tuo codice di attivazione e prova ancora o contatta l\'amministratore del sito per un aiuto.';
$lang['us_err_no_matching_code'] = 'Nessun codice di attivazione corrispondente nel sistema.';
$lang['us_err_no_matching_id'] = 'Nessun user id corrispondente nel sistema.';
$lang['us_err_user_is_active'] = 'L\'utente è già attivo.';
$lang['us_err_user_is_inactive'] = 'L\'utente è già non attivo.';

/* Password strength/match */
$lang['us_pass_strength'] = 'Robustezza';
$lang['us_pass_match'] = 'Confronto';
$lang['us_passwords_no_match'] = 'Non corrisponde!';
$lang['us_passwords_match'] = 'Corrisponde!';
$lang['us_pass_weak'] = 'Debole';
$lang['us_pass_good'] = 'Buona';
$lang['us_pass_strong'] = 'Forte';

$lang['us_tab_all'] = 'Tutti gli utenti';
$lang['us_tab_inactive'] = 'Inattivo';
$lang['us_tab_banned'] = 'Bloccato';
$lang['us_tab_deleted'] = 'Eliminato';
$lang['us_tab_roles'] = 'Per ruolo';

$lang['us_forced_password_reset_note'] = 'Per ragioni di sicurezza, devi scegliere una nuova password per il tuo account.';

$lang['us_back_to'] = 'Torna a';
$lang['us_no_account'] = 'Non hai un account?';
$lang['us_force_password_reset'] = 'Forza azzeramento password al prossimo accesso';
