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

$lang['log_no_logs'] = 'Nessun log trovato.';
$lang['log_not_enabled'] = 'Al momento il logging non è abilitato.';
$lang['log_the_following'] = 'Logga i seguenti:';
$lang['log_what_0'] = '0 - Nulla';
$lang['log_what_1'] = '1 - Messaggio di errore (incluso errori PHP)';
$lang['log_what_2'] = '2 - Messaggi di debug';
$lang['log_what_3'] = '3 - Messaggi informativi';
$lang['log_what_4'] = '4 - Tutti i messaggi';
$lang['log_what_note'] = 'I valori di log più alti includono anche tutti i messaggi dei valori più bassi. Così un logging 2 - Messaggi di debug include anche 1 - Messaggi di errore.';

$lang['log_save_button'] = 'Salva impostazioni di log';
$lang['log_delete_button'] = 'Elimina files di log';
$lang['log_delete1_button'] = 'Eliminare questo file di log?';
$lang['logs_delete_confirm'] = 'Sei sicuro di voler eliminare questi logs?';
$lang['logs_delete_all_confirm'] = 'Sei sicuro di voler eliminare tutti i files di log?';

$lang['log_big_file_note'] = 'Il logging  può dare luogo in breve tempo a file di grandi dimensioni, se imposti il logging  di troppe informazioni. Per siti online, potresti loggare solo gli errori.';
$lang['log_delete_note'] = 'L\'eliminazione dei files di log è irreversibile. Non c\'è modo di tornare indietro, per cui assicurarsi bene.';
$lang['log_delete1_note'] = 'L\'eliminazione dei files di log è irreversibile. Non c\'è modo di tornare indietro, per cui assicurati bene di capire cosa stai facendo.';
$lang['log_delete_confirm'] = 'Sei sicuro di voler eliminare questo file di log?';

$lang['log_not_found'] = 'Impossibile trovare il file di log, o il file è vuoto.';
$lang['log_show_all_entries'] = 'Tutti i records';
$lang['log_show_errors'] = 'Solo errori';

$lang['log_date'] = 'Data';
$lang['log_file'] = 'Nome file';
$lang['log_logs'] = 'Logs';
$lang['log_settings'] = 'Impostazioni';

$lang['log_title'] = 'Logs di sistema';
$lang['log_title_settings'] = 'Impostazioni dei log di sistema';
$lang['log_deleted'] = 'Eliminate %d files di log';
$lang['log_filter_label'] = 'Vedi';
