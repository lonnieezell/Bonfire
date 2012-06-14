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

$lang['roles_intro']					= 'Les rôles vous permettent de définir les autorisations qu\'un utilisateur peut avoir.';
$lang['roles_manage']				= 'Gestion des rôles d\'utilisateur';
$lang['roles_list']				= 'Liste des rôles';
$lang['roles_no_role']			= 'Il n\'y a aucun rôle dans le système.';
$lang['roles_create_success']		= 'Le rôle a été créé avec succès.';
$lang['roles_create_failure']		= 'Il y a eu un problème lors de la création du rôle : ';
$lang['roles_create_heading']	= 'Création d\'un rôle';
$lang['roles_create_note']			= 'Chaque utilisateur a besoin d\'un rôle. Assurez-vous que vous avez tout ce que vous avez besoin.';
$lang['roles_invalid_id']			= 'Identifiant de rôle non valide.';
$lang['roles_edit_success']		= 'Le rôle a été enregistré avec succès.';
$lang['roles_edit_failure']		= 'Il y a eu un problème lors de l\'enregistrement du rôle : ';
$lang['roles_delete_success']		= 'Le rôle a été supprimé avec succès.';
$lang['roles_delete_failure']		= 'Nous ne pouvons pas supprimer le rôle : ';
$lang['roles_edit_heading']		= '&Eacute;dition d\'un rôle';
$lang['roles_details']				= 'Détails du rôle';

$lang['roles_max_desc_length']		= 'Max. 255 caractères.';
$lang['roles_default_role']			= 'Rôle par défaut';
$lang['roles_default_note']			= 'Ce rôle doit être attribué à tous les nouveaux utilisateurs.';
$lang['roles_permissions']			= 'Autorisations';
$lang['roles_permissions_check_note']= 'Vérifiez toutes les autorisations qui s\'appliquent à ce rôle.';
$lang['roles_action_delete_role']			= 'Supprmier ce rôle';
$lang['roles_delete_confirm']		= '&Ecirc;tes-vous sûr de vouloir supprimer ce rôle ?';
$lang['roles_delete_note']			= 'La suppression de ce rôle basculera tous les utilisateurs actuellement affectés à ce rôle vers le rôle par défaut du site.';
$lang['roles_can_delete_role']   	= 'Supprimable';
$lang['roles_can_delete_note']    	= 'Est-ce que ce rôle peut être supprimé ?';

$lang['roles_new_permission_message']	= 'Vous serez en mesure de modifier les autorisations une fois que le rôle aura été créé.';
$lang['roles_not_used']				= 'Inutilisé';

$lang['roles_login_destination']		= 'Redirection à la connexion';
$lang['roles_destination_note']		= 'L\'URL de redirection après une connexion réussie.';

$lang['roles_permission_matrix']				= 'Matrice des autorisations';
$lang['roles_permission']			= 'Autorisation';
$lang['roles_role']				= 'Rôle';
$lang['roles_matrix_note']				= '&Eacute;dition instantanée des autorisations. Cocher ou décocher une case à cocher pour ajouter ou supprimer cette autorisation pour ce rôle.';
$lang['matrix_insert_success']		= 'L\'autorisation a été ajoutée au rôle.';
$lang['roles_matrix_insert_fail']			= 'Il y a eu un problème lors de l\'ajout de l\'autorisation au rôle : ';
$lang['roles_matrix_delete_success']		= 'L\'autorisation a été supprimée du rôle.';
$lang['roles_matrix_delete_fail']			= 'Il y a eu un problème lors de la suppression de l\'autorisation au rôle : ';
$lang['roles_matrix_auth_fail']			= 'Authentification : Vous n\'avez pas la capacité de gérer le contrôle d\'accès pour ce rôle.';

$lang['roles_email_in_use'] = 'L\'adresse de courriel %s est déjà en cours d\'utilisation. Veuillez, s\'il vous plaît, en choisir une autre.';
$lang['roles_role_in_use'] = 'Le rôlel %s est déjà en cours d\'utilisation. Veuillez, s\'il vous plaît, en choisir un autre.';

/* Sub nav */
$lang['roles_s_roles']					= 'Rôles';
$lang['roles_s_new_role']				= 'Créer un nouveau rôle';
$lang['roles_s_matrix']				= 'Matrice des autorisations';

$lang['roles_matrix_site_signin']		= 'Connexion';

$lang['roles_matrix_activities_own']	= 'Sa propre activité';
$lang['roles_matrix_activities_user']	= 'L\'activité des utilisateurs';
$lang['roles_matrix_activities_module']	= 'L\'activité par modules';
$lang['roles_matrix_activities_date']	= 'L\'activité par dates';