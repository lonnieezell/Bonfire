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

$lang['in_intro']					= '<h2>Bienvenue</h2><p>Bienvenue dans le processus d\'installation de Bonfire ! Il suffit de remplir les champs ci-dessous, et avant que vous ne le sachiez, vous allez créer une applications Web basée sur CodeIgniter 2.0 plus rapidement que jamais.</p>';
$lang['in_not_writeable_heading']	= 'Les fichiers/dossiers ne sont pas inscriptibles';

$lang['in_writeable_directories_message'] = 'Veuillez, s\'il vous plaît, faire en sorte que les dossiers suivants soient inscriptibles, et essayez à nouveau ';
$lang['in_writeable_files_message']       = 'Veuillez, s\'il vous plaît, faire en sorte que les fichiers suivants soient inscriptibles, et essayez à nouveau ';

$lang['in_db_settings']				= 'Paramètres de la base de données';
$lang['in_db_settings_note']		= '<p>Veuillez, s\'il vous plaît, remplir les informations de base de données ci-dessous.</p><p class="small">Ces réglages seront sauvegardés à la fois dans le fichier princiapl <b>config/database.php</b> et celuis de l\'environnement de développement (qui se trouve à <b>config/development/database.php</b>).</p>';
$lang['in_db_no_connect']           = 'Le programme d\'installation n\'a pas pu se connecter au serveur MySQL ou à la base de données, veillez à entrer les informations correctes.';
$lang['in_db_setup_error']          = 'Il y a eu une erreur lors de la configuration de votre base de données';
$lang['in_db_settings_error']       = 'Il y a eu une erreur lors de l\'insertoin des paramètres dans votre base de données';
$lang['in_db_account_error']        = 'Il y a eu une erreur lors de la création de votre compte dans votre base de données';
$lang['in_settings_save_error']     = 'There was an error saving the settings. Please verify that your database and %s/database config files are writeable.';

$lang['in_environment']				= 'Environment';
$lang['in_host']					= 'Hôte';
$lang['in_database']				= 'Base de données';
$lang['in_prefix']					= 'Préfixe';
$lang['in_test_db']					= 'Tester la base de données';

$lang['in_account_heading']			= '<h2>Informations requises</h2><p>Veuillez, s\'il vous plaît, fournir les informations suivantes.</p>';
$lang['in_site_title']				= 'Titre du site';
$lang['in_username']			    = 'Nom d\'utilisateur';
$lang['in_password']			    = 'Mot de passe';
$lang['in_password_note']			= 'Longueur minimum : 8 caractères.';
$lang['in_password_again']			= 'Mot de passe<br/><em>(pour confirmation)</em>';
$lang['in_email']					= 'Adresse de courriel';
$lang['in_email_note']				= 'Veuillez vérifier votre adresse de courriel avant de continuer.';
$lang['in_install_button']			= 'Installer Bonfire';
$lang['in_reload_page']			= 'Recharger la page';

$lang['in_curl_disabled']			= '<p class="error">L\'extension de PHP cURL <strong>n\'est actuellement pas</strong> activée. Bonfire ne sera pas en mesure de vérifier les mises à jour jusqu\'à ce qu\'elle soit activée.</p>';

$lang['in_success_notification']    = 'Vous êtes prêt à y aller ! Bonne programmation !';
$lang['in_success_rebase_msg']		= 'Veuillez, s\'il vous plaît, fixer le paramètre <em>RewriteBase</em> dans le ficiher <em>.htaccess</em> à : RewriteBase ';
$lang['in_success_msg']				= 'Veuillez, s\'il vous plaît, supprimer le dossier <em>install</em> et retourner à ';

$lang['no_migrations_found']			= 'Pas de migrations trouv&eacute;es.';
$lang['multiple_migrations_version']	= 'Il y a plusieurs migrations avec le m&ecirc;me num&eacute;ro de version : <em>%d</em>.';
$lang['multiple_migrations_name']		= 'Il y a plusieurs migrations avec le m&ecirc;me nom : <em>%s</em>.';
$lang['migration_class_doesnt_exist']	= 'La classe de migration <em>%s</em> n\'a pas pu &ecirc;tre trouv&eacute;e.';
$lang['wrong_migration_interface']		= 'L\'interface <em>%s</em> de la migration n\'est pas valide.';
$lang['invalid_migration_filename']		= 'Le nom de fichier <em>%s</em> de la migration <em>%s</em> n\'est pas valide.';
