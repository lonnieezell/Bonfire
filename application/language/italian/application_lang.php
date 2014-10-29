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
 * Application language file (Italian)
 *
 * Localization strings used by Bonfire
 *
 * @package    Bonfire\Application\Language\Italian
 * @author     Lorenzo Sanzari (ulisse73@quipo.it)
 * @link       http://cibonfire.com/docs/guides
 */


//--------------------------------------------------------------------
// ! GENERAL SETTINGS
//--------------------------------------------------------------------
$lang['bf_site_name'] = 'Nome sito';
$lang['bf_site_email'] = 'Email sito';
$lang['bf_site_email_help'] = 'L\'email di default dalla quale saranno inviate le emails di sistema.';
$lang['bf_site_status'] = 'Stato sito';
$lang['bf_online'] = 'Online';
$lang['bf_offline'] = 'Offline';
$lang['bf_top_number'] = 'Elementi <em>per</em> pagina:';
$lang['bf_top_number_help'] = 'Nella visualizzazione dei reports, quanti elementi per volta devono essere mostrati?';
$lang['bf_home'] = 'Home';
$lang['bf_site_information'] = 'Informazioni sito';
$lang['bf_timezone'] = 'Zona oraria';
$lang['bf_language'] = 'Lingua';
$lang['bf_language_help'] = 'Scegli le lingue disponibili per l\'utente.';

//--------------------------------------------------------------------
// ! AUTH SETTINGS
//--------------------------------------------------------------------
$lang['bf_security'] = 'Sicurezza';
$lang['bf_login_type'] = 'Tipo login';
$lang['bf_login_type_email'] = 'Solo email';
$lang['bf_login_type_username'] = 'Solo username';
$lang['bf_allow_register'] = 'Consenti registrazione utente?';
$lang['bf_login_type_both'] = 'Email o nome utente';
$lang['bf_use_usernames'] = 'Nome utente mostrato in bonfire:';
$lang['bf_use_own_name'] = 'Usa nome proprio';
$lang['bf_allow_remember'] = 'Consenti "Ricordami"?';
$lang['bf_remember_time'] = 'Ricorda utenti per';
$lang['bf_week'] = 'Settimana';
$lang['bf_weeks'] = 'Settimane';
$lang['bf_days'] = 'Giorni';
$lang['bf_username'] = 'Nome utente';
$lang['bf_password'] = 'Password';
$lang['bf_password_confirm'] = 'Ripeti password';
$lang['bf_display_name'] = 'Nome mostrato';

//--------------------------------------------------------------------
// ! CRUD SETTINGS
//--------------------------------------------------------------------
$lang['bf_home_page'] = 'Home page';
$lang['bf_pages'] = 'Pagine';
$lang['bf_enable_rte'] = 'Abilita RTE per le pagine?';
$lang['bf_rte_type'] = 'Tipo RTE';
$lang['bf_searchable_default'] = 'Ricercabile per default?';
$lang['bf_cacheable_default'] = 'In cache per default?';
$lang['bf_track_hits'] = 'Traccia richieste pagina';

$lang['bf_action_save'] = 'Salva';
$lang['bf_action_delete'] = 'Elimina';
$lang['bf_action_edit'] = 'Modifica';
$lang['bf_action_undo'] = 'Annulla';
$lang['bf_action_cancel'] = 'Cancella';
$lang['bf_action_download'] = 'Download';
$lang['bf_action_preview'] = 'Anteprima';
$lang['bf_action_search'] = 'Cerca';
$lang['bf_action_purge'] = 'Svuota';
$lang['bf_action_restore'] = 'Ripristina';
$lang['bf_action_show'] = 'Mostra';
$lang['bf_action_login'] = 'Entra';
$lang['bf_action_logout'] = 'Esci';
$lang['bf_actions'] = 'Azioni';
$lang['bf_clear'] = 'Pulisci';
$lang['bf_action_list'] = 'Elenca';
$lang['bf_action_create'] = 'Crea';
$lang['bf_action_ban'] = 'Blocca';

//--------------------------------------------------------------------
// ! SETTINGS LIB
//--------------------------------------------------------------------
$lang['bf_ext_profile_show'] = 'Gli accounts utente hanno un profilo esteso?';
$lang['bf_ext_profile_info'] = 'Seleziona "Profili estesi" per avere la disponibilità di meta-dati aggiuntivi nel profilo (WIP), omettendo alcuni campi bonfire di default (ad esempio: indirizzo).';

$lang['bf_yes'] = 'Si';
$lang['bf_no'] = 'No';
$lang['bf_none'] = 'Nessuno';
$lang['bf_id'] = 'ID';

$lang['bf_or'] = 'o';
$lang['bf_size'] = 'Dimensione';
$lang['bf_files'] = 'Files';
$lang['bf_file'] = 'File';

$lang['bf_with_selected'] = 'Con selezionato';

$lang['bf_env_dev'] = 'Sviluppo';
$lang['bf_env_test'] = 'Testing';
$lang['bf_env_prod'] = 'Produzione';

$lang['bf_show_profiler'] = 'Mostra profiler Admin?';
$lang['bf_show_front_profiler'] = 'Mostra profiler di front end?';

$lang['bf_cache_not_writable'] = 'La directory di cache non è scrivibile';

$lang['bf_password_strength'] = 'Impostazioni robustezza password';
$lang['bf_password_length_help'] = 'Lunghezza minima password es. 8';
$lang['bf_password_force_numbers'] = 'Forzare l\'uso di numeri nella password?';
$lang['bf_password_force_symbols'] = 'Forzare l\'uso di simboli nella password?';
$lang['bf_password_force_mixed_case'] = 'Forzare l\'uso di maiuscole/minuscole nella password?';
$lang['bf_password_show_labels'] = 'Mostra etichette di validazione password';
$lang['bf_password_iterations_note'] = 'Valori più alti incrementano la sicurezza e il tempo per violare le password.<br/>Vedi la <a href="http://www.openwall.com/phpass/" target="blank">pagina phpass</a> per maggiori informazioni. In caso di dubbi, lascia il valore 8.';

//--------------------------------------------------------------------
// ! USER/PROFILE
//--------------------------------------------------------------------
$lang['bf_user'] = 'Utente';
$lang['bf_users'] = 'Utenti';
$lang['bf_description'] = 'Descrizione';
$lang['bf_email'] = 'Email';
$lang['bf_user_settings'] = 'Profilo personale';

//--------------------------------------------------------------------
// !
//--------------------------------------------------------------------
$lang['bf_both'] = 'entrambi';
$lang['bf_go_back'] = 'Indietro';
$lang['bf_new'] = 'Nuovo';
$lang['bf_required_note'] = 'I campi richiesti sono in <b>grassetto</b>.';
$lang['bf_form_label_required'] = '<span class="required">*</span>';

//--------------------------------------------------------------------
// BF_Model
//--------------------------------------------------------------------
$lang['bf_model_db_error'] = 'Errore database: %s';
$lang['bf_model_no_data'] = 'Nessun dato disponibile.';
$lang['bf_model_invalid_id'] = 'ID non valido per il model.';
$lang['bf_model_no_table'] = 'Il model ha una tabella non specificata.';
$lang['bf_model_fetch_error'] = 'Informazione insufficiente per estrarre il campo.';
$lang['bf_model_count_error'] = 'Informazione insufficiente per contare i risultati.';
$lang['bf_model_unique_error'] = 'Informazione insufficiente per verificare l\'unicità.';
$lang['bf_model_find_error'] = 'Informazione insufficiente per la ricerca selettiva.';

//--------------------------------------------------------------------
// Contexts
//--------------------------------------------------------------------
$lang['bf_no_contexts'] = 'L\'array dei contexts non è configurato correttamente. Verifica il file config della tua applicazione.';
$lang['bf_context_content'] = 'Contenuto';
$lang['bf_context_reports'] = 'Reports';
$lang['bf_context_settings'] = 'Impostazioni';
$lang['bf_context_developer'] = 'Sviluppatore';

//--------------------------------------------------------------------
// Activities
//--------------------------------------------------------------------
$lang['bf_act_settings_saved'] = 'Impostazioni applicazione salvate da';
$lang['bf_unauthorized_attempt'] = 'Tentativo fallito di accesso ad una pagina che ha richiesto i seguenti permessi "%s" da';

$lang['bf_keyboard_shortcuts'] = 'Scorciatoie da tastiera disponibili:';
$lang['bf_keyboard_shortcuts_none'] = 'Non ci sono scorciatoie da tastiera assegnate.';
$lang['bf_keyboard_shortcuts_edit'] = 'Aggiorna le scorciatoie da tastiera';

//--------------------------------------------------------------------
// Common
//--------------------------------------------------------------------
$lang['bf_question_mark'] = '?';
$lang['bf_language_direction'] = 'ltr';
$lang['bf_name'] = 'Nome';
$lang['bf_status'] = 'Stato';

//--------------------------------------------------------------------
// Login
//--------------------------------------------------------------------
$lang['bf_action_register'] = 'Iscriviti';
$lang['bf_forgot_password'] = 'Password dimenticata?';
$lang['bf_remember_me'] = 'Ricordami';

//--------------------------------------------------------------------
// Password Help Fields to be used as a warning on register
//--------------------------------------------------------------------
$lang['bf_password_number_required_help'] = 'La password deve contenere almeno un numero.';
$lang['bf_password_caps_required_help'] = 'La password deve contenere almeno una maiuscola.';
$lang['bf_password_symbols_required_help'] = 'La password deve contenere almeno un simbolo.';

$lang['bf_password_min_length_help'] = 'La password deve essere lunga almeno %s caratteri.';
$lang['bf_password_length'] = 'Lunghezza password';

//--------------------------------------------------------------------
// Activation
//--------------------------------------------------------------------
$lang['bf_activate_method'] = 'Metodo di attivazione';
$lang['bf_activate_none'] = 'Nessuno';
$lang['bf_activate_email'] = 'Email';
$lang['bf_activate_admin'] = 'Admin';
$lang['bf_activate'] = 'Attiva';
$lang['bf_activate_resend'] = 'Reinvia attivazione';

$lang['bf_reg_complete_error'] = 'Si è verificato un errore durante il completamento della tua registrazione. Per favore riprova ancora oppure contatta l\'amministratore del sito per un aiuto.';
$lang['bf_reg_activate_email'] = 'Una mail contenente il codice di attivazione è stata inviata all\'indirizzo [EMAIL].';
$lang['bf_reg_activate_admin'] = 'Riceverai una notifica quando l\'amministratore del sito avrà approvato la tua iscrizione.';
$lang['bf_reg_activate_none'] = 'Per favore accedi per iniziare ad usare il sito.';
$lang['bf_user_not_active'] = 'L\'account utente non è attivo.';
$lang['bf_login_activate_title'] = 'Hai bisogno di attivare il tuo account utente?';
$lang['bf_login_activate_email'] = '<b>Hai un codice di attivazione da inserire per attivare la tua iscrizione?</b> Inseriscilo nella pagina [ACCOUNT_ACTIVATE_URL].<br /><br /> <b>Hai ancora bisogno del tuo codice?</b> Richiedilo ancora alla pagina [ACTIVATE_RESEND_URL].';

//--------------------------------------------------------------------
// Profiler Template
//--------------------------------------------------------------------
$lang['bf_profiler_menu_console'] = 'Console';
$lang['bf_profiler_menu_time'] = 'Tempo di caricamento';
$lang['bf_profiler_menu_time_ms'] = 'ms';
$lang['bf_profiler_menu_time_s'] = 's';
$lang['bf_profiler_menu_memory'] = 'Memoria utilizzata';
$lang['bf_profiler_menu_memory_mb'] = 'MB';
$lang['bf_profiler_menu_queries'] = 'Queries';
$lang['bf_profiler_menu_queries_db'] = 'Database';
$lang['bf_profiler_menu_vars'] = '<span>variabili</span> &amp; Configurazioni';
$lang['bf_profiler_menu_files'] = 'Files';
$lang['bf_profiler_box_console'] = 'Console';
$lang['bf_profiler_box_memory'] = 'Utilizzo Memoria';
$lang['bf_profiler_box_benchmarks'] = 'Benchmarks';
$lang['bf_profiler_box_queries'] = 'Queries';
$lang['bf_profiler_box_session'] = 'Dati utente di sessione';
$lang['bf_profiler_box_files'] = 'Files';

//--------------------------------------------------------------------
// Form Validation
//--------------------------------------------------------------------
$lang['bf_form_unique'] = 'Il valore in &quot;%s&quot; è già stato usato.';
$lang['bf_form_alpha_extra'] = 'Il campo %s può contenere soltanto caratteri alfanumerici, spazi, punti, underscores e trattini.';
$lang['bf_form_matches_pattern'] = 'Il campo %s non rispetta il formato richiesto.';
$lang['bf_form_valid_password'] = 'Il campo %s deve essere lungo almeno {min_length} caratteri.';
$lang['bf_form_valid_password_nums'] = '%s deve contenere almeno 1 numero.';
$lang['bf_form_valid_password_syms'] = '%s deve contenere almeno 1 segno di punteggiatura.';
$lang['bf_form_valid_password_mixed_1'] = '%s deve contenere almeno 1 carattere maiuscolo.';
$lang['bf_form_valid_password_mixed_2'] = '%s deve contenere almeno 1 carattere minuscolo.';
$lang['bf_form_allowed_types'] = '%s deve contenere almeno una delle selezioni consentite.';
$lang['bf_form_one_of'] = '%s deve contenere almeno una delle selezioni disponibili.';

//--------------------------------------------------------------------
// Menu Strings - feel free to add your own custom modules here
// if you want to localize your menus
//--------------------------------------------------------------------
$lang['bf_menu_activities'] = 'Attività';
$lang['bf_menu_code_builder'] = 'Generatore di codice';
$lang['bf_menu_db_tools'] = 'Strumenti database';
$lang['bf_menu_db_maintenance'] = 'Manutenzione';
$lang['bf_menu_db_backup'] = 'Backups';
$lang['bf_menu_emailer'] = 'Coda email';
$lang['bf_menu_email_settings'] = 'Impostazioni';
$lang['bf_menu_email_template'] = 'Template';
$lang['bf_menu_email_queue'] = 'Coda view';
$lang['bf_menu_kb_shortcuts'] = 'Scorciatoie tastiera';
$lang['bf_menu_logs'] = 'Logs';
$lang['bf_menu_migrations'] = 'Migrazioni';
$lang['bf_menu_permissions'] = 'Permessi';
$lang['bf_menu_queue'] = 'Coda';
$lang['bf_menu_roles'] = 'Ruoli';
$lang['bf_menu_settings'] = 'Impostazioni';
$lang['bf_menu_sysinfo'] = 'Informazioni di sistema';
$lang['bf_menu_template'] = 'Template';
$lang['bf_menu_translate'] = 'Traduci';
$lang['bf_menu_users'] = 'Utenti';

//--------------------------------------------------------------------
// Anything that doesn't follow the 'bf_*' convention:
//--------------------------------------------------------------------
$lang['log_intro'] = 'Questi sono i tuoi messaggi di log';

//--------------------------------------------------------------------
// User Meta examples
//--------------------------------------------------------------------

$lang['user_meta_street_name'] = 'Nome strada';
$lang['user_meta_type'] = 'Tipo';
$lang['user_meta_country'] = 'Nazione';
$lang['user_meta_state'] = 'Stato';

//--------------------------------------------------------------------
// Migrations lib
//--------------------------------------------------------------------
$lang['no_migrations_found'] = 'Nessun file di migrazione trovato.';
$lang['multiple_migrations_version'] = 'Diverse versioni di migrazioni: %d';
$lang['multiple_migrations_name'] = 'Diversi nomi di migrazioni: %s';
$lang['migration_class_doesnt_exist'] = 'La classe migrazione non esiste: %s';
$lang['wrong_migration_interface'] = 'Interfaccia di migrazione difettosa: %s';
$lang['invalid_migration_filename'] = 'Nome file di migrazione scorretto: %s - %s';
