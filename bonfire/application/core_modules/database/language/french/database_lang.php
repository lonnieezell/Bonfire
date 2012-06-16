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

$lang['db_database_maintenance']			= 'Maintenance de la base de données';
$lang['db_database_backups']				= 'Sauvegardes de la base de données';
$lang['db_drop_database_tables']			= 'Suppression de tables de la base de données';
$lang['db_backup_database_tables']				= 'Table(s) sauvegardée(s) ';

$lang['db_backup_warning']		= 'Remarque: En raison de la durée d\'exécution limitée et de la mémoire disponible pour PHP, la sauvegarde de bases de données très volumineuse peut ne pas être possible. Si votre base de données est très grande, vous pourriez avoir besoin de la sauvegarder directement à partir de votre serveur SQL via la ligne de commande, ou demander à l\'administrateur de votre serveur de le faire pour vous si vous n\'avez pas les privilèges root.';
$lang['db_filename']			= 'Nom de fichier';

$lang['db_drop_question']		= 'Ajouter la commande &lsquo;DROP TABLE&rsquo;';
$lang['db_drop_tables']			= 'Drop Tables';
$lang['db_compresssion_type']	= 'Type de compression';
$lang['db_insert_question']		= 'Insérer les données';
$lang['db_add_inserts']			= 'Add Inserts';
$lang['db_backup_options']	= 'Options pour l\'export';

$lang['db_restore_note']		= 'L\'option de restauration est capable de lire seulement les fichiers non compressés. La compression Gzip et la compression Zip sont à privilégier si vous voulez juste une sauvegarde à télécharger et à stocker sur votre ordinateur.';

$lang['db_action_apply']				= 'Appliquer';
$lang['db_compresssion_none']				= 'Aucune';
$lang['db_compresssion_gzip']				= '"gzippé"';
$lang['db_compresssion_zip']					= '"zippé"';
$lang['db_action_backup']				= 'Sauvegarder';
$lang['db_action_restore']				= 'Restaurer';
$lang['db_action_drop']				= 'Supprimer';
$lang['db_action_repair']				= 'Réparer';
$lang['db_action_optimize']			= 'Optimiser';
$lang['db_action_delete_tables']			= 'Effacer la ou les table(s)';
$lang['db_action_browse']				= 'Parcourir';

$lang['db_backup_create_heading']	= 'Création d\'une sauvegarde';
$lang['db_database_restore_heading']	= 'Restauration de la base de données';
$lang['db_table_browse_heading']	= 'Parcours de la table :';

$lang['db_no_backups']			= 'Aucun fichier de sauvegarde n\'a été trouvé.';
$lang['db_backup_delete_confirm']	= '&Ecirc;tes-vous sûr de vouloir vraiment supprimer les fichiers de sauvegarde suivants ?';
$lang['db_backup_delete_none']	= 'Aucun fichier de sauvegarde n\'a été sélectionné pour être supprimé.';
$lang['db_drop_confirm']		= '&Ecirc;tes-vous vraiment sûr de vouloir supprimer les tables suivantes ?';
$lang['db_drop_none']			= 'Aucune table n\'a été sélectionnée pour être supprimée.';
$lang['db_drop_attention']		= '<p>La suppression de tables dans la base de données se traduira par une perte de données.</p><p><strong>Cela peut rendre votre application non-fonctionnelle.</strong></p>';
$lang['db_repair_none']			= 'Aucune table n\'a été sélectionnée pour être réparée.';
$lang['db_browse_none']			= 'Aucun nom de table n\'a été fourni.';

$lang['db_table_name']			= 'Nom de la table';
$lang['db_records']				= 'Enregistrements';
$lang['db_data_size']			= 'Taille des données';
$lang['db_index_size']			= 'Taille de l\'index';
$lang['db_data_free']			= 'Data Free';
$lang['db_engine']				= 'Moteur';
$lang['db_no_tables']			= 'No tables were found for the current database.';

$lang['db_successful_query']				= 'Requête réussie : ';
$lang['db_unsuccessful_query']				= 'Requête échouée : ';

$lang['db_restore_results']		= 'Restore Results';
$lang['db_back_to_tools']		= 'Back to Database Tools';
$lang['db_restore_file']		= 'Restaurer la base de données à partir du fichier %s ?';
$lang['db_restore_attention']	= '<p>La restauration d\'une base de données à partir d\'un fichier de sauvegarde peut entraîner la perte de tout ajout dans la base de données avant la restauration de celle-ci.</p><p><strong>Cette action peut entraîner une perte de données</strong>.</p>';

$lang['db_sql_query']			= 'Requête SQL :';
$lang['db_total_results']		= 'Nombre total de résultats :';
$lang['db_no_rows']				= 'Aucune donnée n\'a été trouvée dans la table.';

$lang['db_backup_file_delete_success']				= 'Le fichier de sauvegarde a été effacé.';
$lang['db_backup_files_delete_success']				= '%s fichiers de sauvegarde ont été effacés.';
$lang['db_backup_file_save_success']				= 'Le fichier de sauvegarde a été enregistré avec succès. Il peut être trouvé à l\'adresse %s.';
$lang['db_backup_file_save_failure']				= 'Il y a eu un problème lors de l\'enregistrement du fichier de sauvegarde.';
$lang['db_backup_file_not_found']				= 'Le fichier <em>%s</em> n\'a pas pu être trouvé.';
$lang['db_backup_file_read_failure']				= 'Impossible de lire le fichier <em>%s</em>.';
$lang['db_table_drop_success']				= 'La table a été supprimée avec succès.';
$lang['db_tables_drop_success']				= '%s tables ont été supprimées avec succès.';
$lang['db_table_repair_success']				= '%s table(s) réparée(s) avec succès sur %s table(s).';
$lang['db_database_optimize_success']				= 'La base de données a été optimisés avec succès.';
$lang['db_database_optimize_failure']				= 'Impossible d\'optimiser la base de données.';
$lang['db_database_update_success']				= 'La base de données a été mise à jour vers la dernière version.';
$lang['db_database_update_failure']				= 'Impossible de mettre à jour le schéma de la base de données : ';

/* Sub nav */
$lang['db_s_maintenance']			= 'Maintenance';
$lang['db_s_backups']				= 'Sauvegardes';
$lang['db_s_migrations']				= 'Migrations';