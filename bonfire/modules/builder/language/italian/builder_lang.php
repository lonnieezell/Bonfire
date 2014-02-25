<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (Italian)
 *
 * @package     Bonfire\Modules\Builder\Language\Italian
 * @author      Lorenzo Sanzari (ulisse73@quipo.it)
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

// INDEX page
$lang['mb_delete_confirm'] = 'Vuoi davvero cancellare questo modulo e tutti i suoi files?';
$lang['mb_create_button'] = 'Crea modulo';
$lang['mb_create_link'] = 'Crea un nuovo modulo';
$lang['mb_create_note'] = 'Usa il nostro potente wizard nella generazione del tuo prossimo modulo. Noi facciamo il grosso del lavoro generando tutti i controllers, models, views e file lingua di cui hai bisogno.';
$lang['mb_not_writeable_note'] = 'Errore: La directory application/modules non è scrivibile per cui non è possibile scrivere il modulo sul server. Cortesemente imposta la directory come scrivibile e aggiorna questa pagina.';
$lang['mb_generic_description'] = 'Inserisci una descrizione';
$lang['mb_installed_head'] = 'Moduli applicativi installati';
$lang['mb_module'] = 'Modulo';
$lang['mb_no_modules'] = 'Nessun modulo installato.';
$lang['mb_toolbar_title_index'] = 'Gestisci moduli';

$lang['mb_table_name'] = 'Nome';
$lang['mb_table_version'] = 'Versione';
$lang['mb_table_author'] = 'Autore';
$lang['mb_table_description'] = 'Descrizione';

// OUTPUT page
$lang['mb_out_success'] = 'Il modulo è stato creato correttamente. Di seguito troverai la lista dei files controllers, models, language, migrations e view che sono stati creati durante il processo. Se selezionerai l\'opzione "Genera migration" saranno inclusi  I files model e SQL e un file Javascript durante la creazione.';
$lang['mb_out_success_note'] = 'NOTA: per favore aggiungi la validazione di input extra da te richiesti. Questo codice deve essere usato solo come punto di partenza.';
$lang['mb_out_tables_success'] = 'Le tabelle del database saranno generate automaticamente per te. Puoi verificarle o disinstallarle, se lo vorrai, dalla sezione %s.';
$lang['mb_out_tables_error'] = 'Le tabelle del database <strong>NON</strong> sono state installate automaticamente per te. Hai ancora bisogno di andare alla sezione %s e migrare le tue tabelle di database prima di poterle usare.';
$lang['mb_out_acl'] = 'File di Controllo Accessi';
$lang['mb_out_acl_path'] = 'migrations/001_Install_%s_permissions.php';
$lang['mb_out_config'] = 'File di configurazione';
$lang['mb_out_config_path'] = 'config/config.php';
$lang['mb_out_controller'] = 'Controllers';
$lang['mb_out_controller_path'] = 'controllers/%s.php';
$lang['mb_out_model'] = 'Models';
$lang['mb_out_model_path'] = '%s_model.php';
$lang['mb_out_view'] = 'Views';
$lang['mb_out_view_path'] = 'views/%s.php';
$lang['mb_out_lang'] = 'File Lingua';
$lang['mb_out_lang_path'] = '%s_lang.php';
$lang['mb_out_migration'] = 'File di Migration';
$lang['mb_out_migration_path'] = 'migrations/002_Install_%s.php';
$lang['mb_new_module'] = 'Nuovo Modulo';
$lang['mb_exist_modules'] = 'Moduli Esistenti';

// FORM page
$lang['mb_form_note'] = '<p><b>Compila i campi che vorresti nel tuo modulo (un campo "id" sarà creato automaticamente). Se vuoi creare l\'SQl per una tabella, seleziona la checkbox "Crea tabella modulo".</b></p><p>Questo form genereà un modulo Codeigniter completo (model, controller e views) e, se lo sceglierai, i file di migrazione del database.</p>';
$lang['mb_table_note'] = '<p>La tua tabella sarà creata con almeno un campo, il campo chiave primaria che sarà usato come identificatore unico e come indice. Se hai bisogno di più campi, clicca il numero di campi che desideri per aggiungerli a questo form.</p>';
$lang['mb_field_note'] = '<p><b>NOTA : PER TUTTI I CAMPI</b><br />se il tipo di campo DB è "enum" o "set", si prega di inserire i valori usando questo formato: \'a\',\'b\',\'c\'...<br />Se hai bisogno di inserire un backslash ("\\") o un apice singolo ("\'") all\'interno di questi valori, anteponi ad esso un backslash (ad esempio \'\\\\xyz\' or \'a\\\'b\').</p>';

$lang['mb_form_errors'] = 'Per favore correggi i seguenti errori.';
$lang['mb_form_mod_details'] = 'Dettagli modulo';
$lang['mb_form_mod_name'] = 'Nome Modulo';
$lang['mb_form_mod_name_ph'] = 'Forums, Blog, ToDo';
$lang['mb_form_mod_desc'] = 'Descrizione modulo';
$lang['mb_form_mod_desc_ph'] = 'La tua descrizione del modulo';
$lang['mb_form_contexts'] = 'Contexts richiesti';
$lang['mb_form_public'] = 'Public';
$lang['mb_form_table_details'] = 'Dettagli tabella';
$lang['mb_form_actions'] = 'Metodi del Controller';
$lang['mb_form_actions_index'] = 'Elenca';
$lang['mb_form_actions_create'] = 'Crea';
$lang['mb_form_actions_edit'] = 'Modifica';
$lang['mb_form_actions_delete'] = 'Elimina';
$lang['mb_form_primarykey'] = 'Chiave primaria';
$lang['mb_form_delims'] = 'Delimitatori degli input del form';
$lang['mb_form_err_delims'] = 'Delimitatori degli errori del form';
$lang['mb_form_text_ed'] = 'Editor per le textarea';
$lang['mb_form_soft_deletes'] = 'Usa eliminazione "soft"?';
$lang['mb_form_use_created'] = 'Usa campo "Creato"?';
$lang['mb_form_use_modified'] = 'Usa campo "Modificato"?';
$lang['mb_form_created_field'] = 'Nome campo "Creato"';
$lang['mb_form_created_field_ph'] = 'creato_il';
$lang['mb_form_modified_field'] = 'Nome campo "Modificato"';
$lang['mb_form_modified_field_ph'] = 'modificato_il';
$lang['mb_form_generate'] = 'Crea tabella modulo';
$lang['mb_form_role_id'] = 'Dai accesso completo al ruolo';
$lang['mb_form_fieldnum'] = 'Campi tabella addizionali';
$lang['mb_form_field_details'] = 'Dettagli campo';
$lang['mb_form_table_name'] = 'Nome Tabella';
$lang['mb_form_table_name_ph'] = 'Minuscolo, senza spazi';
$lang['mb_form_table_as_field_prefix'] = 'Usa nome tabella come prefisso campi';
$lang['mb_form_label'] = 'Etichetta';
$lang['mb_form_label_ph'] = 'Il nome che sarà usato nelle pagine web';
$lang['mb_form_fieldname'] = 'Nome';
$lang['mb_form_fieldname_ph'] = 'Il nome del campo in database';
$lang['mb_form_fieldname_help'] = 'Meglio minuscolo, senza spazi.';
$lang['mb_form_type'] = 'Tipo input nella pagina web';
$lang['mb_form_length'] = 'Lunghezza massima <b>-o-</b> Valori';
$lang['mb_form_length_ph'] = '30, 255, 1000, etc...';
$lang['mb_form_dbtype'] = 'Tipo del database';
$lang['mb_form_rules'] = 'Regole di validazione';
$lang['mb_form_rules_limits'] = 'Limitazioni di input';
$lang['mb_form_required'] = 'Richiesto';
$lang['mb_form_unique'] = 'Unico';
$lang['mb_form_trim'] = 'Trim';
$lang['mb_form_valid_email'] = 'Email valida';
$lang['mb_form_is_numeric'] = '0-9';
$lang['mb_form_alpha'] = 'a-Z';
$lang['mb_form_alpha_dash'] = 'a-Z, 0-9, e _-';
$lang['mb_form_alpha_numeric'] = 'a-Z e 0-9';
$lang['mb_form_add_fld_button'] = 'Aggiungi un altro campo';
$lang['mb_form_show_advanced'] = 'Mostra opzioni avanzate';
$lang['mb_form_show_more'] = '...mostra più regole...';
$lang['mb_form_integer'] = 'Interi';
$lang['mb_form_is_decimal'] = 'Numeri decimali';
$lang['mb_form_is_natural'] = 'Numeri naturali';
$lang['mb_form_is_natural_no_zero'] = 'Naturali, senza zeri';
$lang['mb_form_valid_ip'] = 'IP valido';
$lang['mb_form_valid_base64'] = 'Base64 valido';
$lang['mb_form_alpha_extra'] = 'Alfanumerico, underscore, trattino, punti e spazi.';
$lang['mb_form_match_existing'] = 'Assicurati che questo corrisponda al nome di campo esistente!';
$lang['mb_form_module_db_no'] = 'Nessuno';
$lang['mb_form_module_db_create'] = 'Crea nuova tabella';
$lang['mb_form_module_db_exists'] = 'Genera da tabella esistente';
$lang['mb_form_build'] = 'Genera il modulo';

// Activities
$lang['mb_act_create'] = 'Modulo creato';
$lang['mb_act_delete'] = 'Modulo eliminato';

// Create Context
$lang['mb_create_a_context'] = 'Crea un Context';
$lang['mb_tools'] = 'Strumenti';
$lang['mb_mod_builder'] = 'Generatore moduli';
$lang['mb_new_context'] = 'Nuovo Context';
$lang['mb_no_context_name'] = 'Nome Context non valido';
$lang['mb_cant_write_config'] = 'Impossibile scrivere nel file config.';
$lang['mb_context_exists'] = 'Il Context già esiste nel file config dell\'applicazione.';
$lang['mb_context_name'] = 'Nome Context';
$lang['mb_context_name_help'] = 'Non può contenere spazi';
$lang['mb_context_create_success'] = 'Context creato con successo.';
$lang['mb_context_create_error'] = 'Errore nella creazione del Context:';
$lang['mb_context_create_intro'] = 'Crea e configura un nuovo Context.';
$lang['mb_roles_label'] = 'Consenti per i Ruoli:';
$lang['mb_context_migrate'] = 'Creare una Migration per l\'applicazione?';
$lang['mb_context_submit'] = 'Creala';

// Create Module
$lang['mb_module_table_not_exist'] = 'Il nome tabella specificato non esiste';
$lang['mb_toolbar_title_create'] = 'Generatore moduli';

// Delete Module
$lang['mb_delete_trans_false'] = 'Impossibile eliminare questo modulo.';
$lang['mb_delete_success'] = 'Il modulo e tutti i records di database ad esso associati sono stati correttamente eliminati.';
$lang['mb_delete_success_db_only'] = 'TUTTAVIA, la directory e i files del modulo non sono stati rimossi. Bisogna rimuoverli manualmente.';

// Validate Form
$lang['mb_contexts_content'] = 'Contexts :: Content';
$lang['mb_contexts_developer'] = 'Contexts :: Developer';
$lang['mb_contexts_public'] = 'Contexts :: Public';
$lang['mb_contexts_reports'] = 'Contexts :: Reports';
$lang['mb_contexts_settings'] = 'Contexts :: Settings';
$lang['mb_module_db'] = 'Tabella modulo';
$lang['mb_form_action_create'] = 'Form Actions :: Crea';
$lang['mb_form_action_delete'] = 'Form Actions :: Elimina';
$lang['mb_form_action_edit'] = 'Form Actions :: Modifica';
$lang['mb_form_action_view'] = 'Form Actions :: Elenca';
$lang['mb_soft_delete_field'] = 'Nome campo eliminazione "soft"';
$lang['mb_soft_delete_field_ph'] = 'eliminato';
$lang['mb_validation_no_match'] = '%s %ss (%s & %s) deve essere unico!';
$lang['mb_modulename_check'] = 'Il campo %s non è valido';
$lang['mb_modulename_check_class_exists'] = 'Il campo %s non è valido: il nome del modulo corrisponde al nome di una classe esistente.';
