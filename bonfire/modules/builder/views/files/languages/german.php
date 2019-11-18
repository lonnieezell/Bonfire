<?php

/**
 * German Lanmguange for generated module
 *
 * @author Benedikt Wallmeyer (ben@wallmeyer.io)
 */
$lang = '<?php defined(\'BASEPATH\') || exit(\'No direct script access allowed\');' .
PHP_EOL . '
$lang[\'' . $module_name_lower . '_manage\']      = \'' . $module_name . ' verwalten\';
$lang[\'' . $module_name_lower . '_edit\']        = \'Bearbeiten\';
$lang[\'' . $module_name_lower . '_true\']        = \'Wahr\';
$lang[\'' . $module_name_lower . '_false\']       = \'Falsch\';
$lang[\'' . $module_name_lower . '_create\']      = \'Erstellen\';
$lang[\'' . $module_name_lower . '_list\']        = \'Liste\';
$lang[\'' . $module_name_lower . '_new\']       = \'Neu\';
$lang[\'' . $module_name_lower . '_edit_text\']     = \'An Ihre Bedürfnisse Anpassen.\';
$lang[\'' . $module_name_lower . '_no_records\']    = \'Es gibt keine ' . $module_name_lower . ' im System.\';
$lang[\'' . $module_name_lower . '_create_new\']    = \'Erstelle ein neues ' . $module_name .'\';
$lang[\'' . $module_name_lower . '_create_success\']  = \'' . $module_name . ' erfolgreich erstellt.\';
$lang[\'' . $module_name_lower . '_create_failure\']  = \'Es gab ein Problem beim erstellen des ' .$module_name_lower .'\';
$lang[\'' . $module_name_lower . '_create_new_button\'] = \'Erstelle ein neues ' . $module_name . '\';
$lang[\'' . $module_name_lower . '_invalid_id\']    = \'ID ' . $module_name . ' ungültig.\';
$lang[\'' . $module_name_lower . '_edit_success\']    =  \'' . $module_name . ' erfolgreich gespeichert.\';
$lang[\'' . $module_name_lower . '_edit_failure\']    = \'Es gab ein Problem beim speichern des ' .$module_name_lower . ': \';
$lang[\'' . $module_name_lower . '_delete_success\']  = \'Eintrag(Einträge) erfolgreich gelöscht.\';
$lang[\'' . $module_name_lower . '_delete_failure\']  = \'Der Eintrag konnte nicht gelöscht werden: \';
$lang[\'' . $module_name_lower . '_delete_error\']    = \'Sie haben keine Einträge zum löschen ausgewählt.\';
$lang[\'' . $module_name_lower . '_actions\']     = \'Aktionen\';
$lang[\'' . $module_name_lower . '_cancel\']      = \'Abbrechen\';
$lang[\'' . $module_name_lower . '_delete_record\']   = \'Lösche ' . $module_name .'\';
$lang[\'' . $module_name_lower . '_delete_confirm\']  = \'Sind sie sich das sie dieses ' .$module_name_lower . ' löschen möchten?.\';
$lang[\'' . $module_name_lower . '_edit_heading\']    = \'' . $module_name . ' bearbeiten\';

// Create/Edit Buttons
$lang[\'' . $module_name_lower . '_action_edit\']   = \'' . $module_name . ' speichern\';
$lang[\'' . $module_name_lower . '_action_create\']   = \'' . $module_name . ' erstellen\';

// Activities
$lang[\'' . $module_name_lower . '_act_create_record\'] = \'Eintrag erstellt mit ID\';
$lang[\'' . $module_name_lower . '_act_edit_record\'] = \'Eintrag geändert mit ID\';
$lang[\'' . $module_name_lower . '_act_delete_record\'] = \'Eintrag gelöscht mit ID\';

//Listing Specifics
$lang[\'' . $module_name_lower . '_records_empty\']    = \'Es wurden keine Datensätze gefunden, die Ihrer Auswahl entsprechen.\';
$lang[\'' . $module_name_lower . '_errors_message\']    = \'Bitte beheben Sie folgende Fehler:\';

// Column Headings
$lang[\'' . $module_name_lower . '_column_created\']  = \'Erstellt\';
$lang[\'' . $module_name_lower . '_column_deleted\']  = \'Gelöscht\';
$lang[\'' . $module_name_lower . '_column_modified\'] = \'Geändert\';
$lang[\'' . $module_name_lower . '_column_deleted_by\'] = \'Gelöscht von\';
$lang[\'' . $module_name_lower . '_column_created_by\'] = \'Erstellt von\';
$lang[\'' . $module_name_lower . '_column_modified_by\'] = \'Geändert von\';

// Module Details
$lang[\'' . $module_name_lower . '_module_name\'] = \'' . $module_name . '\';
$lang[\'' . $module_name_lower . '_module_description\'] = \'' .$module_description.'\';
$lang[\'' . $module_name_lower . '_area_title\'] = \'' . $module_name .'\';

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