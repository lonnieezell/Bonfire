<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Roles Language File (Italian)
 *
 * @package Bonfire\Modules\Roles\Language\Italian
 * @author  Lorenzo Sanzari (ulisse73@quipo.it)
 * @author  Bonfire Dev Team
 * @link    http://cibonfire.com/docs/bonfire/roles_and_permissions
 */

$lang['role_intro']         = 'I ruoli ti consentono di definire tutte le capacità che un utente può avere.';
$lang['role_manage']        = 'Gestisci i ruoli utente';
$lang['role_no_roles']      = 'Non c\'è nessun ruolo nel sistema.';
$lang['role_create_button'] = 'Crea un nuovo ruolo.';
$lang['role_create_note']   = 'Ogni utente ha bisogno di un ruolo. Assicurati di avere tutto ciò di cui hai bisogno.';
$lang['role_account_type']  = 'Tipo di account';
$lang['role_description']   = 'Descrizione';
$lang['role_details']       = 'Dettagli ruolo';

$lang['role_name']                   = 'Nome ruolo';
$lang['role_max_desc_length']        = 'Massimo 255 caratteri';
$lang['role_default_role']           = 'Ruolo di default';
$lang['role_default_note']           = 'Questo ruolo dovrebbe essere assegnato a tutti i nuovi utenti';
$lang['role_permissions']            = 'Permessi';
$lang['role_permissions_check_note'] = 'Verifica tutti i permessi che applichi a questo ruolo.';
$lang['role_save_role']              = 'Salva ruolo';
$lang['role_delete_role']            = 'Elimina questo ruolo';
$lang['role_delete_confirm']         = 'Sei davvero sicuro di voler eliminare questo ruolo?';
$lang['role_delete_note']            = 'L\'eliminazione di questo ruolo convertirà tutti gli utenti al momento assegnati ad esso in utenti con ruolo di default.';
$lang['role_can_delete_role']        = 'Rimovibile';
$lang['role_can_delete_note']        = 'Questo ruolo può essere cancellato?';

$lang['role_roles']                  = 'Ruoli';
$lang['role_new_role']               = 'Nuovo ruolo';
$lang['role_new_permission_message'] = 'Potrai modificare i permessi del ruolo una volta che il ruolo sarà stato creati.';
$lang['role_not_used']               = 'Non usato';

$lang['role_login_destination']    = 'Destinazione login';
$lang['role_destination_note']     = 'La URL alla quale reindirizzare dopo un corretto login.';
$lang['role_default_context']      = 'Context di Admin di default';
$lang['role_default_context_note'] = 'Il context di default per l\'admin da caricare quando nessun context è specificato (es. http://yoursite.com/admin/)';

$lang['matrix_header']         = 'Matrice dei permessi';
$lang['matrix_permission']     = 'Permesso';
$lang['matrix_role']           = 'Ruolo';
$lang['matrix_note']           = 'Modifica veloce dei permessi. Seleziona/deseleziona una checkbox per aggiungere o rimuovere un certo permesso per un certo ruolo.';
$lang['matrix_insert_success'] = 'Permesso aggiunto al ruolo.';
$lang['matrix_insert_fail']    = 'C\'è stato un problema nell\'aggiungere il permesso al ruolo:';
$lang['matrix_delete_success'] = 'Permesso rimosso dal ruolo.';
$lang['matrix_delete_fail']    = 'C\'è stato un problema nell\'eliminare il permesso dal ruolo:';
$lang['matrix_auth_fail']      = 'Autenticazione: non hai l\'autorizzazione a gestire il controllo di accesso per questo ruolo.';

$lang['role_create_success'] = 'Il ruolo è stato creato con successo.';
$lang['role_create_error']   = 'C\'è stato un problema nella creazione del ruolo:';
$lang['role_delete_success'] = 'Il ruolo è stato creato con successo.';
$lang['role_delete_error']   = 'Il ruolo non può essere cancellato:';
$lang['role_edit_success']   = 'Il ruolo è stato salvato correttamente.';
$lang['role_edit_error']     = 'C\'è stato un problema nel salvataggio del ruolo:';
$lang['role_invalid_id']     = 'ID ruolo non valido.';

$lang['form_validation_role_name'] = 'Nome ruolo';
$lang['form_validation_role_login_destination'] = 'Destinazione login';
$lang['form_validation_role_default_context']   = 'Context di Admin di default';
$lang['form_validation_role_default_role']      = 'Ruolo di default';
$lang['form_validation_role_can_delete_role']   = 'Rimovibile';
