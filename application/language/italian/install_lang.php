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

$lang['in_need_db_settings'] = 'Impossibile trovare impostazioni database corrette. Si prega di verificare le impostazioni e ricaricare la pagina.';
$lang['in_need_database'] = 'Pare che il database non esista. Si prega di creare il database e ricaricare la pagina.';

$lang['in_intro'] = '<h2>Benvenuto in Bonfire</h2><p>Per favore verifica i seguenti requisiti di sistema, poi clicca su "Successivo" per iniziare.</p>';
$lang['in_not_writeable_heading'] = 'Files/Directories non scrivibili';

$lang['in_php_version'] = 'Versione PHP';
$lang['in_curl_enabled'] = 'cURL abilitata?';
$lang['in_enabled'] = 'Abilitato';
$lang['in_disabled'] = 'Disabilitato';
$lang['in_folders'] = 'Directories scrivibili';
$lang['in_files'] = 'Files scrivibili';
$lang['in_writeable'] = 'Scrivibile';
$lang['in_not_writeable'] = 'Non scrivibile';
$lang['in_bad_permissions'] = 'Si prega di correggere i problemi di cui sopra e aggiornare questa pagina per continuare.';

$lang['in_writeable_directories_message'] = 'Assicurarsi che le seguenti directory sono scrivibili e riprovare';
$lang['in_writeable_files_message'] = 'Assicurarsi che i seguenti file sono scrivibili, e riprovare';

$lang['in_db_settings'] = 'Impostazioni database';
$lang['in_db_settings_note'] = '<p> Si prega di compilare le informazioni del database sottostante. </ p>';
$lang['in_environment_note'] = '<p class="small"> Queste impostazioni verranno salvate sia nel file principale config / database.php </ b> che nell\' <b>ambiente adatto (es. <b> config / development / database.php) </ b>. </ p>';
$lang['in_db_not_available'] = 'Impossibile trovare il database.';
$lang['in_db_connect'] = 'Impostazioni database OK';
$lang['in_db_no_connect'] = 'Impostazioni database non valide.';
$lang['in_db_setup_error'] = 'C\'è stato un errore nella configurazione del database';
$lang['in_db_settings_error'] = 'C\'è stato un errore nell\'inserimento delle impostazioni nel database';
$lang['in_db_account_error'] = 'C\'è stato un errore nella creazione del tuo account nel database';
$lang['in_settings_save_error'] = 'C\'è stato un errore nel salvataggio delle impostazioni. Per favore verifica che il tuo database e il file %s/database config sono scrivibili.';
$lang['in_db_no_session'] = 'Impossibile recuperare le informazioni del database dalla sessione.';
$lang['in_user_no_session'] = 'Impossibile recuperare le informazioni del tuo account dalla sessione.';
$lang['in_db_config_error'] = 'Si è verificato un errore durante il tentativo di scrivere le impostazioni di configurazione del database per {file}.';

$lang['in_environment'] = 'Ambiente';
$lang['in_environment_dev'] = 'Development';
$lang['in_environment_test'] = 'Testing';
$lang['in_environment_prod'] = 'Production';
$lang['in_host'] = 'Host';
$lang['in_database'] = 'Database';
$lang['in_prefix'] = 'Prefisso';
$lang['in_db_driver'] = 'Driver';
$lang['in_port'] = 'Porta';

$lang['in_account_heading'] = '<h2> account amministratore </ h2> <p>Si prega di fornire le seguenti informazioni. </ p>';
$lang['in_site_title'] = 'Titolo sito';
$lang['in_username'] = 'Nome utente';
$lang['in_password'] = 'Password';
$lang['in_password_note'] = 'Lunghezza minima: 8 caratteri.';
$lang['in_password_again'] = 'Ripeti password';
$lang['in_email'] = 'Tua email';
$lang['in_email_note'] = 'Per favore controlla due volte la tua email prima di continuare.';
$lang['in_install_button'] = 'Istalla Bonfire';

$lang['in_curl_disabled'] = '<p class="error"> <strong> cURL non è </ strong> attualmente abilitato come estensione di PHP. Bonfire non sarà in grado di controllare gli aggiornamenti finché non viene attivata. </p>';

$lang['in_success_notification'] = 'Sei pronto per iniziare! Felice programmazione!';
$lang['in_success_rebase_msg'] = 'Si prega di impostare l\'impostazione RewriteBase htaccess a: RewriteBase';
$lang['in_success_msg'] = 'Per favore rimuovi la directory install e ritorna a';

$lang['in_installed'] = 'Bonfire è già installato. Si prega di cancellare o rinominare la directory install come';
$lang['in_rename_msg'] = 'Se preferisci, possiamo rinominarla al posto tuo.';
$lang['in_continue'] = 'Continua';
$lang['in_click'] = 'Clicca qui';

$lang['in_requirements'] = 'Requisiti';
$lang['in_account'] = 'Account';
$lang['in_complete'] = 'Installazione completata';
$lang['in_complete_heading'] = 'E\' tempo di scatenare le vostre abilità di codifica ninja!';
$lang['in_complete_intro'] = 'Bonfire è stato installato, e il tuo account utente è configurato. <br/> Un file chiamato <b> installed.txt </b> è stato creato nella cartella config. Lascialo lì e non ti verrà chiesto più di installare.';
$lang['in_complete_next'] = 'Cosa c\'è dopo?';
$lang['in_complete_visit'] = 'Vedi la tua';
$lang['in_admin_area'] = 'Area amministrazione';
$lang['in_site_front'] = 'Frontpage';
$lang['in_read'] = 'Leggi la';
$lang['in_bf_docs'] = 'Documentazione Bonfire';
$lang['in_ci_docs'] = 'Documentazione Codeigniter';
$lang['in_happy_coding'] = 'Felice programmazione!';
