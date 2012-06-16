<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$lang['mig_database_migrations']			= 'Migrations de la base de données';
$lang['mig_intro']					= 'Les migrations vous aident à garder votre base de données à jour et synchronisée entre les serveurs de développement et de production en fournissant un moyen simple de garder &lsquo;un contrôle de version&rsquo; sur votre base de données.';
$lang['mig_not_enabled']			= 'Les migrations ne sont pas activées.';
$lang['mig_installed_version']		= 'Version installée :';
$lang['mig_latest_version']			= 'Dernière version disponible :';
$lang['mig_db_not_current']			= 'Votre base de données n\'est pas à jour.';
$lang['mig_no_migration']			= 'Il n\'y a aucune migration disponible.';
$lang['mig_mod_module']		= 'Module';
$lang['mig_mod_installed_version']		= 'Version installée';
$lang['mig_mod_latest_version']			= 'Dernière version';
$lang['mig_mod_no_migration']			= 'Il n\'y a aucun module avec des migrations disponibles.';
$lang['mig_mod_uninstall']			= 'Désinstaller';

$lang['mig_class_doesnt_exist']     = 'La classe de migration <em>%s</em> n\'a pas pu &ecirc;tre trouv&eacute;e.';

$lang['mig_migrate_note']			= 'L\'exécution des migrations <b>VA CHANGER</b> la structure de votre base de données, peut-être en occasionnant une catastrophe. Si vous n\'êtes pas à l\'aise avec vos migrations, veuillez, s\'il vous plaît les vérifier avant de continuer.';
$lang['mig_migrate_to']				= 'Migrate database to version';
$lang['mig_choose_migration']		= 'Migrer vers la version';
$lang['mig_action_migrate_database']			= 'Faire migrer la base de données';
$lang['mig_action_migrate_module']			= 'Faire migrer le module';
$lang['mig_migrate_database_success']			= 'La migration de la base de données vers la version %s a été exécutée avec succès.';
$lang['mig_migrate_database_failure']			= 'Il y a eu une erreur lors de la migration de la base de données.';
$lang['mig_migrate_module_success']			= 'La migration de désinstallation du module a été exécutée avec succès.';

$lang['mig_app_migrations']			= 'Migrations des applications';
$lang['mig_mod_migrations']			= 'Migrations des modules';
$lang['mig_core_migrations']		= 'Migrations du noyau Bonfire';

$lang['mig_t_application']			= 'Application';
$lang['mig_t_module']			= 'Modules';
$lang['mig_t_bonfire']		= 'Noyau Bonfire';

/* Sub nav */
$lang['db_s_maintenance']			= 'Maintenance';
$lang['db_s_backups']				= 'Sauvegardes';
$lang['db_s_migrations']				= 'Migrations';