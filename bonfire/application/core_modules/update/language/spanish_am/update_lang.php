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

$lang['up_toolbar_title']			= 'Gestor de actualizaciones';

$lang['up_update_off_message']		= 'La comprobaci&oacute;n de actualizaciones se desactiva en el archivo config/application.php.';
$lang['up_curl_disabled_message']	= 'cURL <strong>no est&aacute;</strong> activado como un extensi&oacute;n de PHP. Bonfire no podr&aacute; comprobar las actualizaciones hasta que este activo.';
$lang['up_edge_commits']			= 'New Bleeding Edge Commits';
$lang['up_branch']					= 'Rama: <b>develop</b>';

$lang['up_author']					= 'Autor';
$lang['up_committed']				= 'Committed';
$lang['up_message']					= 'Mensaje';

$lang['up_update_message_bleeding'] = 'A <b>bleeding edge</b> update to Bonfire is available.';
$lang['up_update_message_new']      = ' Versi&oacute;n %s de Bonfire est&aacute; disponible. Actualmente estas ejecutando  '; //requires the spaces at the start and end of the string
$lang['up_update_message_latest']   = 'Estas ejecutando la versi&oacute;n %s de Bonfire. Esta es la versi&oacute;n m&aacute;s reciente disponible.';
$lang['up_update_message_old']      = 'Estas ejecutando la versi&oacute;n %s de Bonfire. La &uacute;ltima versi&oacute;n <b>stable</b> disponible es %s.';
$lang['up_update_message_unable']   = 'Estas ejecutando la versi&oacute;n %s de Bonfire. <b>No se puede recuperar en este momento la versi&oacute;n m&aacute;s reciente.</b>';
