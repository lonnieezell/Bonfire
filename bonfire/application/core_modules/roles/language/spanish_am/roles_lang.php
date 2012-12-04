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

$lang['role_intro']					= 'Los roles le permiten definir las capacidades que puede tener un usuario.';
$lang['role_manage']				= 'Gestionar los reoles de usuario';
$lang['role_no_roles']				= 'No hay ningún rol en el sistema.';
$lang['role_create_button']			= 'Crear un nuevo rol.';
$lang['role_create_note']			= 'Cada ususario requiere un rol. Asegurate de tener todo lo necesario.';
$lang['role_account_type']			= 'Tipo de cuenta';
$lang['role_description']			= 'Descripción';
$lang['role_details']				= 'Detalles del rol';

$lang['role_name']					= 'Nombre del rol';
$lang['role_max_desc_length']		= 'Max. 255 carácteres.';
$lang['role_default_role']			= 'Rol por defecto';
$lang['role_default_note']			= 'Este rol debe ser asignado a todos los usuarios nuevos.';
$lang['role_permissions']			= 'Permisos';
$lang['role_permissions_check_note']= 'Revise todos los permisos que se aplican a este rol.';
$lang['role_save_role']				= 'Guardar el rol';
$lang['role_delete_role']			= 'Eliminar este rol';
$lang['role_delete_confirm']		= '¿Esta seguro de eliminar este rol?';
$lang['role_delete_note']			= 'Eliminar este rol convertirá a todos los usuarios que lo tengan asignado a el rol por defecto del sitio web.';
$lang['role_can_delete_role']   	= 'Borrable';
$lang['role_can_delete_note']    	= '¿Puede este rol ser eliminado?';

$lang['role_roles']					= 'Roles';
$lang['role_new_role']				= 'Nuevo Rol';
$lang['role_new_permission_message']	= 'Podrá modificar los permisos una vez el rol halla sido creado.';
$lang['role_not_used']				= 'No se usa';

$lang['role_login_destination']		= 'Destino del inicio de sesión';
$lang['role_destination_note']		= 'La URL del sitio para redirigir los inicios de sesión exitosos.';
$lang['role_default_context']		= 'Contexto por defecto Admin';
$lang['role_default_context_note']	= 'Se cargará el contexto admin cuando no se especifique uno (I.E. http://yoursite.com/admin/)';

$lang['matrix_header']				= 'Matriz de permisos';
$lang['matrix_permission']			= 'Permisos';
$lang['matrix_role']				= 'Rol';
$lang['matrix_note']				= 'Edición de autorización inmediata. Activa la casilla de verificación para agregar o quitar ese permiso para este rol.';
$lang['matrix_insert_success']		= 'Permisos agregados al rol.';
$lang['matrix_insert_fail']			= 'Hubo un problema al agregar los permisos al rol: ';
$lang['matrix_delete_success']		= 'Los permisos fueron revocados para el rol.';
$lang['matrix_delete_fail']			= 'Hubo un problema al eliminar el permiso para el rol: ';
$lang['matrix_auth_fail']			= 'Autorización: No tienes el permiso para gestionar el control de acceso para este rol.';
