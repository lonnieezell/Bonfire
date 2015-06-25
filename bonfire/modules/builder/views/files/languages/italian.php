<?php

/**
 * Italian language for generated module.
 *
 * @author Lorenzo Sanzari (ulisse73@quipo.it)
 */
$lang = '<?php defined(\'BASEPATH\') || exit(\'No direct script access allowed\');' .
PHP_EOL . '
$lang[\'' . $module_name_lower . '_manage\']      = \'Gestisci ' . $module_name . '\';
$lang[\'' . $module_name_lower . '_edit\']        = \'Modifica\';
$lang[\'' . $module_name_lower . '_true\']        = \'Vero\';
$lang[\'' . $module_name_lower . '_false\']       = \'Falso\';
$lang[\'' . $module_name_lower . '_create\']      = \'Crea\';
$lang[\'' . $module_name_lower . '_list\']        = \'Elenca\';
$lang[\'' . $module_name_lower . '_new\']       = \'Nuovo\';
$lang[\'' . $module_name_lower . '_edit_text\']     = \'Modifica questo secondo le tue necessità\';
$lang[\'' . $module_name_lower . '_no_records\']    = \'Non ci sono ' . $module_name_lower . ' nel sistema.\';
$lang[\'' . $module_name_lower . '_create_new\']    = \'Crea un nuovo ' . $module_name . '.\';
$lang[\'' . $module_name_lower . '_create_success\']  = \'' . $module_name . ' creato con successo.\';
$lang[\'' . $module_name_lower . '_create_failure\']  = \'C\\\'è stato un problema nella creazione di ' . $module_name_lower . ': \';
$lang[\'' . $module_name_lower . '_create_new_button\'] = \'Crea nuovo ' . $module_name . '\';
$lang[\'' . $module_name_lower . '_invalid_id\']    = \'ID ' . $module_name . ' non valido.\';
$lang[\'' . $module_name_lower . '_edit_success\']    = \'' . $module_name . ' creato con successo.\';
$lang[\'' . $module_name_lower . '_edit_failure\']    = \'C\\\'è stato un errore nel salvataggio di ' . $module_name_lower . ': \';
$lang[\'' . $module_name_lower . '_delete_success\']  = \'record(s) creati con successo.\';
$lang[\'' . $module_name_lower . '_delete_failure\']  = \'Non possiamo eliminare il record: \';
$lang[\'' . $module_name_lower . '_delete_error\']    = \'Non hai selezionato alcun record da eliminare.\';
$lang[\'' . $module_name_lower . '_actions\']     = \'Azioni\';
$lang[\'' . $module_name_lower . '_cancel\']      = \'Cancella\';
$lang[\'' . $module_name_lower . '_delete_record\']   = \'Elimina questo ' . $module_name . '\';
$lang[\'' . $module_name_lower . '_delete_confirm\']  = \'Sei sicuro di voler eliminare questo ' . $module_name_lower . '?\';
$lang[\'' . $module_name_lower . '_edit_heading\']    = \'Modifica ' . $module_name . '\';

// Create/Edit Buttons
$lang[\'' . $module_name_lower . '_action_edit\']   = \'Salva ' . $module_name . '\';
$lang[\'' . $module_name_lower . '_action_create\']   = \'Crea ' . $module_name . '\';

// Activities
$lang[\'' . $module_name_lower . '_act_create_record\'] = \'Creato il record con ID\';
$lang[\'' . $module_name_lower . '_act_edit_record\'] = \'Aggiornato il record con ID\';
$lang[\'' . $module_name_lower . '_act_delete_record\'] = \'Eliminato il record con ID\';

// Listing Specifics
$lang[\'' . $module_name_lower . '_records_empty\']    = \'Nessun record trovato che corrisponda alla tua selezione.\';
$lang[\'' . $module_name_lower . '_errors_message\']    = \'Per favore risolvi i seguenti errori:\';

// Column Headings
$lang[\'' . $module_name_lower . '_column_created\']  = \'Creato\';
$lang[\'' . $module_name_lower . '_column_deleted\']  = \'Eliminato\';
$lang[\'' . $module_name_lower . '_column_modified\'] = \'Modificato\';

// Module Details
$lang[\'' . $module_name_lower . '_module_name\'] = \'' . $module_name . '\';
$lang[\'' . $module_name_lower . '_module_description\'] = \'' . $module_description . '\';
$lang[\'' . $module_name_lower . '_area_title\'] = \'' . $module_name . '\';

// Fields
';

for ($counter = 1; $field_total >= $counter; $counter++) {
  if (set_value("view_field_label$counter") == null) {
    continue;   // move onto next iteration of the loop
  }

  $field_label = set_value("view_field_label$counter");
  $field_name  = set_value("view_field_name$counter");

  $lang .= '$lang[\'' . $module_name_lower . '_field_' . $field_name . '\'] = \'' . $field_label . '\';
';
}

echo $lang;