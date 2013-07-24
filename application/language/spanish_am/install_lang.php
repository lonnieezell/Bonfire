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

$lang['in_intro']					= '<h2>Bienvenido</h2><p>¡Bienvenido al proceso de instalación de Bonfire-coffee!<br /> Con Bonfire-coffee creará aplicaciones web basadas en Codeigniter 2.0 más rápido que nunca.<br /><br /> Para iniciar el proceso de instalación, siga los pasos indicados por el asistente.</p>';
$lang['in_not_writeable_heading']	= 'Carpetas/Archivos sin permisos de escritura';

$lang['in_writeable_directories_message'] = 'Asegurese de que las siguientes carpetas tengan permisos de escritura';
$lang['in_writeable_files_message']       = 'Asegurese de que los siguientes archivos tengan permisos de escritura';

$lang['in_db_settings']				= 'Configuración de la Base de Datos';
$lang['in_db_settings_note']		= '<p>Llene la siguiente información.</p><p class="small">Esta configuración será guardada en el archivo principal <b>config/database.php</b> y en el archivo del entorno que seleccione (que se encuentra en <b>config/entorno/database.php)</b>. </p>';
$lang['in_db_no_connect']           = 'El instalador no se pudo conectar al servidor MySQL o la base de datos, asegurese de ingresar la información correcta.';
$lang['in_db_setup_error']          = 'Hubo un error al cargar la configuración a la base de datos';
$lang['in_db_settings_error']       = 'Hubo un error al insertar la configuración en la base de datos';
$lang['in_db_account_error']        = 'Hubo un error al crear su cuenta en la base de datos';
$lang['in_settings_save_error']     = 'Hubo un error al guardar las configuraciones. Por favor verifique que su base de datos y el archivo de configuracion %s/database tienen permisos de escritura.';

$lang['in_environment']				= 'Entorno';
$lang['in_environment_dev']			= 'Desarrollo';
$lang['in_environment_test']		= 'Pruebas';
$lang['in_environment_prod']		= 'Producción';
$lang['in_host']					= 'Servidor';
$lang['in_database']				= 'Base de datos';
$lang['in_prefix']					= 'Prefijo';
$lang['in_test_db']					= 'Probar la conexión a la base de datos';

$lang['in_account_heading']			= '<h2>Cuenta para el Administrador</h2><p>Ingrese la siguiente información.</p>';
$lang['in_site_title']				= 'Título para el sitio';
$lang['in_username']			    = 'Usuario';
$lang['in_password']			    = 'Contraseña';
$lang['in_password_note']			= 'Mínimo 8 caracteres.';
$lang['in_password_again']			= 'Contraseña (repetir)';
$lang['in_email']					= 'Su correo electrónico';
$lang['in_email_note']				= 'Por favor vuelva a verificar su correo electrónico antes de continuar.';
$lang['in_install_button']			= 'Instalar Bonfire';

$lang['in_curl_disabled']			= '<p class="error">cURL <strong>is not</strong> presently enabled as a PHP extension. Bonfire will not be able to check for updates until it is enabled.</p>';

$lang['in_success']    				= '<h2>La instalación ha finalizado</h2><p>Sólo completa las siguientes tareas:</p>';
$lang['in_success_notification']    = 'Todo esta listo para iniciar!';
$lang['in_success_rebase_msg']		= 'Configura la opción RewriteBase en el archivo .htaccess ';
$lang['in_success_msg']				= 'Remueve la carpeta install y has clic en ';
