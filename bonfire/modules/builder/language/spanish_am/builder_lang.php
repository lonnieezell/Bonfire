<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (American Spanish)
 *
 * @package     Bonfire\Modules\Builder\Language\Spanish_am
 * @author      Bonfire Dev Team
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

// INDEX page
$lang['mb_actions']				= 'Acciones';
$lang['mb_create_button']		= 'Crear módulo';
$lang['mb_create_link']			= 'Crear un nuevo módulo';
$lang['mb_create_note']			= 'Utiliza nuestro asistente wizbang para crear tu próximo módulo. Hacemos todo el trabajo pesado por ti mediante la generación de controladores, módelos, vistas y archivos de idioma.';
$lang['mb_not_writable_note']	= 'Error: la carpeta application/modules no tiene permisos de escritura por lo que no podrá crear un módulo en el servidor. Por favor asigne los permisos de escritura a la carpeta y actualiza esta página.';
$lang['mb_delete']				= 'Eliminar';
$lang['mb_generic_description']	= 'Su descripción aquí.';
$lang['mb_installed_head']		= 'Módulos de aplicación instaldos';
$lang['mb_module']				= 'Módulo';
$lang['mb_no_modules']			= 'No hay módulos instalados.';

$lang['mb_table_name']			= 'Nombre';
$lang['mb_table_version']		= 'Versión';
$lang['mb_table_author']		= 'Autor';
$lang['mb_table_description']	= 'Descripción';

// OUTPUT page
$lang['mb_out_success']			= 'La creación del módulo finalizo correctamente! Ahora puedes buscar la lista de archivos de controladores, Modelos, Idioma, Migración y Vistas que fueron creados durante el proceso. Los archivos de modelo y SQL serán incluidos si selecciono la opción de "Generar Migración" y el archivo de Javascript si fue requerido durante la creación.';
$lang['mb_out_success_note']	= 'NOTA: Por favor agrege validación adicional a los campos de usuario según requiera. Este código es usado como un punto de partida únicamente.';
$lang['mb_out_tables_success']	= 'Las tablas fueron creadas automáticamente en la base de datos. Puede verificarlas o eliminarlas, si desea desde esta sección %s.';
$lang['mb_out_tables_error']	= 'Las tablas <strong>NO</strong> fueron creadas automáticamente en la base de datos. Si aún lo requiere vaya a la sección %s y realice el proceso de migración en la base de datos antes de poder trabajar con ellas.';
$lang['mb_out_acl'] 			= 'Archivo de control de acceso';
$lang['mb_out_acl_path']        = 'migrations/001_Install_%s_permissions.php';
$lang['mb_out_config'] 			= 'Archivo de configuración';
$lang['mb_out_config_path'] 	= 'config/config.php';
$lang['mb_out_controller']		= 'Controlador';
$lang['mb_out_controller_path']	= 'controllers/%s.php';
$lang['mb_out_model'] 			= 'Modelos';
$lang['mb_out_model_path']		= '%s_model.php';
$lang['mb_out_view']			= 'Vistas';
$lang['mb_out_view_path']		= 'views/%s.php';
$lang['mb_out_lang']			= 'Archivo de Idioma';
$lang['mb_out_lang_path']		= '%s_lang.php';
$lang['mb_out_migration']		= 'Archivos de Migración';
$lang['mb_out_migration_path']	= 'migrations/002_Install_%s.php';
$lang['mb_new_module']			= 'Nuevo módulo';
$lang['mb_exist_modules']		= 'Módulos existentes';

// FORM page
$lang['mb_form_note'] 			= '<p><b>Llene todos los campos que le gustaría tener en su módulo (un campo "id" será creado automáticamente). Si desea crear el SQL para la tabla marque la casilla "Crear una nueva tabla".</b></p><p>Este formulario generará un módulo completo de CodeIgniter (modelo, controlador y vista) y si usted lo selecciona un archivo de Migración.</p>';

$lang['mb_table_note'] 			= '<p>Su tabla se creará con al menos un campo: de clave primaria que será usado como identificador único y como un índice. Si requiere campos adicionales, haga clic en el número que necesita para agregarlos a este formulario.</p>';

$lang['mb_field_note'] 			= '<p><b>NOTA : PARA TODOS LOS CAMPOS</b><br />Si su campo para la tabla es de tipo "enum" o "set", por favor ingrese los valores usando este formato: \'a\',\'b\',\'c\'...<br />Si alguna vez necesita poner una barra invertida ("\") o una sola comilla ("\'") entre esos valores, precedalos con una barra invertida (por ejemplo \'\\xyz\' or \'a\\\'b\').</p>';

$lang['mb_form_errors']			= 'Por favor corrija los siguientes errores.';
$lang['mb_form_mod_details']	= 'Detalles del módulo';
$lang['mb_form_mod_name']		= 'Nombre del módulo';
$lang['mb_form_mod_name_ph']	= 'Foro, Blog, Tareas';
$lang['mb_form_mod_desc']		= 'Descripción del módulo';
$lang['mb_form_mod_desc_ph']	= 'Una lista de todos los ítems';
$lang['mb_form_contexts']		= 'Entornos de trabajo';
$lang['mb_form_public']			= 'Publico';
$lang['mb_form_table_details']	= 'Detalles de la Tabla';
$lang['mb_form_actions']		= 'Acciones del controlador';
$lang['mb_form_primarykey']		= 'Clave primaria';
$lang['mb_form_delims']			= 'Delimitadores del formulario de entrada';
$lang['mb_form_err_delims']		= 'Form Error Delimiters';
$lang['mb_form_text_ed']		= 'Editor del área de texto';
$lang['mb_form_soft_deletes']	= '¡Usar el campo "Deleted"?';
$lang['mb_form_use_created']	= '¡Usar el campo "Created"?';
$lang['mb_form_use_modified']	= '¡Usar el campo "Modified"?';
$lang['mb_form_created_field']	= '"Created" field name?';
$lang['mb_form_modified_field']	= '"Modified" field name?';
$lang['mb_form_generate']		= 'Crear una tabla para el módulo';
$lang['mb_form_role_id']		= 'Otorgar el rol de acceso completo';
$lang['mb_form_fieldnum']		= 'Campos adicionales para la tabla';
$lang['mb_form_field_details']	= 'Detalles del campo';
$lang['mb_form_table_name']		= 'Nombre de la Tabla';
$lang['mb_form_table_name_ph']	= 'Minúsculas, sin espacios';
$lang['mb_form_table_as_field_prefix']		= 'Usar el nombre de la tabla como prefijo para el campo';
$lang['mb_form_label']			= 'Etiqueta';
$lang['mb_form_label_ph']		= 'El nombre que se usará en la página web';
$lang['mb_form_fieldname']		= 'Nombre (sin espacios)';
$lang['mb_form_fieldname_ph']	= 'El nombre del campo en la tabla. Usar minúsculas es lo recomendado.';
$lang['mb_form_type']			= 'Tipo de entrada';
$lang['mb_form_length']			= 'longitud máxima <b>-o-</b> Valores';
$lang['mb_form_length_ph']		= '30, 255, 1000, etc...';
$lang['mb_form_dbtype']			= 'Tipo de datos';
$lang['mb_form_rules']			= 'Reglas de validación';
$lang['mb_form_rules_limits']	= 'Reglas adicionales';
$lang['mb_form_required']		= 'Requerido';
$lang['mb_form_unique']			= 'Unico';
$lang['mb_form_trim']			= 'Trim';
$lang['mb_form_xss_clean']		= 'Sanitize';
$lang['mb_form_valid_email']	= 'Correo electrónico válido';
$lang['mb_form_is_numeric']		= '0-9';
$lang['mb_form_alpha']			= 'a-Z';
$lang['mb_form_alpha_dash']		= 'a-Z, 0-9, and _-';
$lang['mb_form_alpha_numeric']	= 'a-Z and 0-9';
$lang['mb_form_add_fld_button'] = 'Agregar otro campo';
$lang['mb_form_show_advanced']	= 'Activar las opciones avanzadas';
$lang['mb_form_show_more']		= '...active para más reglas...';
$lang['mb_form_integer']		= 'Entero';
$lang['mb_form_is_decimal']		= 'Numero decimal';
$lang['mb_form_is_natural']		= 'Numero natural';
$lang['mb_form_is_natural_no_zero']	= 'Natural, sin ceros';
$lang['mb_form_valid_ip']		= 'IP válida';
$lang['mb_form_valid_base64']	= 'Base64 válido';
$lang['mb_form_alpha_extra']	= 'Alfanumerico, subrayado, guiones, periodos y espacios.';
$lang['mb_form_match_existing']	= 'Ensure this matches the existing fieldname!';

// Activities
$lang['mb_act_create']	= 'Módulo creado';
$lang['mb_act_delete']	= 'Módulo eliminado';
