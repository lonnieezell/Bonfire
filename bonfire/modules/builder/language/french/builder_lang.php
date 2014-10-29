<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (French)
 *
 * @package     Bonfire\Modules\Builder\Language\French
 * @author      Bonfire Dev Team
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

// INDEX page
$lang['mb_create_button']		= 'Créer un Module';
$lang['mb_create_link']			= 'Créer un nouveau module';
$lang['mb_create_note']			= 'Utilisez notre assistant pour créer votre prochain module. Nous faisons tout le travail lourd en générant tous les contrôleurs, modèles, vues et les fichiers de langue dont vous aurez besoin.';
$lang['mb_not_writable_note']	= 'Erreur: Le répertoire application/modules n\'est pas accessible en écriture donc les modules ne peuvent pas êtres écrits sur le serveur. Merci de vous assurez que le répertoire est accessible en écriture et rafraîchissez cette page.';
$lang['mb_generic_description']	= 'Votre description ici.';
$lang['mb_installed_head']		= 'Module de l\'application installés';
$lang['mb_module']				= 'Module';
$lang['mb_no_modules']			= 'Aucun module installé.';

$lang['mb_table_name']			= 'Nom';
$lang['mb_table_version']		= 'Version';
$lang['mb_table_author']		= 'Auteur';
$lang['mb_table_description']	= 'Description';

// OUTPUT page
$lang['mb_out_success']	= 'La création du module a été un succès ! Vous trouvez ci-dessous la liste des fichiers des contrôleurs, modèles et vues qui ont été créeés au cours de ce processus. Les fichiers des modèles et SQL seront inclus si vous avez sélectionné la fonction "Générer les migrations" et un fichier Javascript si il est nécéssaire lors de la création.';
$lang['mb_out_success_note']	= 'NOTE: Merci d\'ajouter la validations d\'entrées des utilisateurs que vous désirez. Ce code doit petre utilisé comme un point de départ seuleuement.';
$lang['mb_out_tables_success']	= 'Les tables de la base de données ont été installés automatiquement pour vous. Vous pouvez les vérifier ou les désinstaller si vous le souhaitez, à partir de la section %s.';
$lang['mb_out_tables_error']	= 'Les tables de la base de données <strong>n\'ont pas été</strong> installé automatiquement pour vous. Vous avez encore besoin d\'aller à la section %s et migrer votre(vos) base(s) de données avant de pouvoir travailler avec.';
$lang['mb_out_acl'] 			= 'Liste de Contrôle d\'Accès';
$lang['mb_out_acl_path']        = 'migrations/001_Installation_%s_permissions.php';
$lang['mb_out_config'] 			= 'Fichier de Config';
$lang['mb_out_config_path'] 	= 'config/config.php';
$lang['mb_out_controller']		= 'Contrôlleurs';
$lang['mb_out_controller_path']	= 'controllers/%s.php';
$lang['mb_out_model'] 			= 'Modèles';
$lang['mb_out_model_path']		= '%s_model.php';
$lang['mb_out_view']			= 'Vues';
$lang['mb_out_view_path']		= 'views/%s.php';
$lang['mb_out_lang']			= 'Fichier de Langue';
$lang['mb_out_lang_path']		= '%s_lang.php';
$lang['mb_out_migration']		= 'Fichier(s) de Migration';
$lang['mb_out_migration_path']	= 'migrations/002_Install_%s.php';
$lang['mb_new_module']			= 'Nouveau Module';
$lang['mb_exist_modules']		= 'Modules existants';

// FORM page
$lang['mb_form_note'] = '<p><b>Remplissez les champs que vous souhaitez dans votre module (un champs "id" est créeé automatiquement).  Si vous voulez créer le SQL de la BDD pour une table de la base de donnée, cocher la case "Créer la Table du Module".</b></p><p>Ce formulaire va générer un module Codeigniter complet (modèle, contrôlleur, vues) et, si vous le choisissez, les fichiers de migrations de base de données.</p>';

$lang['mb_table_note'] = '<p>Votre table sera créée avec au moins un champ, le champ clé primaire qui sera utilisé comme identifiant unique et comme un index. Si vous avez besoin de champ supplémentaire, cliquez sur le nombre de champs dont vous aurez besoin afin de les ajouter à ce formulaire.</p>';

$lang['mb_field_note'] = '<p><b>NOTE : POUR TOUS LES CHAMPS</b><br />Si le champs de la BDD est "enum" ou "set", merci d\'entrer la valeur en utilisant ce format : \'a\',\'b\',\'c\'...<br />Si jamais vous avez besoin de mettre une barre oblique ("\\") ou un simple apostrophe ("\'") parmsi ces valeurs, faites précéder d\'une barre oblique inverse (par exemple \'\\\\xyz\' ou \'a\\\'b\').</p>';

$lang['mb_form_errors']			= 'Merci de corriger les erreurs ci-dessous.';
$lang['mb_form_mod_details']	= 'Détails du Module ';
$lang['mb_form_mod_name']		= 'Nom du Module';
$lang['mb_form_mod_name_ph']	= 'Forums, Blog, ToDo';
$lang['mb_form_mod_desc']		= 'Description du Module';
$lang['mb_form_mod_desc_ph']	= 'Une liste des éléments "ToDo"';
$lang['mb_form_contexts']		= 'Contextes requis';
$lang['mb_form_public']			= 'Public';
$lang['mb_form_table_details']	= 'Détails de la table';
$lang['mb_form_actions']		= 'Action du Contrôlleur';
$lang['mb_form_primarykey']		= 'Clé Primaire';
$lang['mb_form_delims']			= 'Séparateurs des entrée de fomulaire';
$lang['mb_form_err_delims']		= 'Séparateurs des erreurs de fomulaire';
$lang['mb_form_text_ed']		= 'Éditeur des Textarea';
$lang['mb_form_soft_deletes']	= 'utiliser Suppression "Légère" ?';
$lang['mb_form_use_created']	= 'utiliser "Créer" le champ ?';
$lang['mb_form_use_modified']	= 'utiliser "Modifier" le champ ?';
$lang['mb_form_created_field']	= '"Created" nom de champ ?';
$lang['mb_form_modified_field']	= '"Modifier" nom de champ ?';
$lang['mb_form_generate']		= 'Créer Table du Module';
$lang['mb_form_role_id']		= 'Donner le rôle d\'accès complet';
$lang['mb_form_fieldnum']		= 'D\'autres champs de table';
$lang['mb_form_field_details']	= 'Détails du champ';
$lang['mb_form_table_name']		= 'Nom de la Table';
$lang['mb_form_table_name_ph']	= 'Minuscules, sans espace';
$lang['mb_form_table_as_field_prefix']		= 'Utiliser le nom de la table comme préfixe des champs';
$lang['mb_form_label']			= 'Label';
$lang['mb_form_label_ph']		= 'le nom qui sera utilisé sur les pages web';
$lang['mb_form_fieldname']		= 'Nom (sans espaces)';
$lang['mb_form_fieldname_ph']	= 'Le nom du champ pour la base de données. Minuscule est préférable.';
$lang['mb_form_type']			= 'Webpage Input Type';
$lang['mb_form_length']			= 'Maximum Length <b>-or-</b> Values';
$lang['mb_form_length_ph']		= '30, 255, 1000, etc...';
$lang['mb_form_dbtype']			= 'Database Type';
$lang['mb_form_rules']			= 'Validation Rules';
$lang['mb_form_rules_limits']	= 'Input Limitations';
$lang['mb_form_required']		= 'Required';
$lang['mb_form_unique']			= 'Unique';
$lang['mb_form_trim']			= 'Trim';
$lang['mb_form_valid_email']	= 'Valid Email';
$lang['mb_form_is_numeric']		= '0-9';
$lang['mb_form_alpha']			= 'a-Z';
$lang['mb_form_alpha_dash']		= 'a-Z, 0-9, and _-';
$lang['mb_form_alpha_numeric']	= 'a-Z and 0-9';
$lang['mb_form_add_fld_button'] = 'Add another field';
$lang['mb_form_show_advanced']	= 'Toggle Advanced Options';
$lang['mb_form_show_more']		= '...toggle more rules...';
$lang['mb_form_integer']		= 'Integers';
$lang['mb_form_is_decimal']		= 'Decimal Numbers';
$lang['mb_form_is_natural']		= 'Natural Numbers';
$lang['mb_form_is_natural_no_zero']	= 'Natural, no zeroes';
$lang['mb_form_valid_ip']		= 'Valid IP';
$lang['mb_form_valid_base64']	= 'Valid Base64';
$lang['mb_form_alpha_extra']	= 'AlphaNumerics, underscore, dash, periods and spaces.';
$lang['mb_form_match_existing']	= 'Ensure this matches the existing fieldname!';

// Activities
$lang['mb_act_create']	= 'Module crée';
$lang['mb_act_delete']	= 'Module supprimé';

$lang['mb_create_a_context']	= 'Créé un Contexte';
$lang['mb_tools']				= 'Outils';
$lang['mb_mod_builder']			= 'Constructeur de Module';
$lang['mb_new_context']			= 'Nouveau Contexte';
$lang['mb_no_context_name']		= 'Nom de Contexte invalide.';
$lang['mb_cant_write_config']	= 'Impossible d\'écrire dans le fichier de configuration.';
$lang['mb_context_exists']		= 'Le Contexte existe déjà dans le fichier de configuration.';