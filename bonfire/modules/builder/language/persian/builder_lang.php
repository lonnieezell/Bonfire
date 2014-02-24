<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Builder Language File (Persian)
 *
 * @package     Bonfire\Modules\Builder\Language\Persian
 * @author      Bonfire Dev Team (Translator: Sajjad Servatjoo <sajjad.servatjoo[at]gmail[dot]com>)
 * @copyright   Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license     http://opensource.org/licenses/MIT
 * @link        http://cibonfire.com/docs/builder
 */

// INDEX page
$lang['mb_create_button']		= 'ايجاد افزونه';
$lang['mb_create_link']			= 'ایجاد افزونه جدید';
$lang['mb_create_note']			= 'جهت ساخت افزونه ي خود ميتوانيد از افزونه ساز ما استفاده کنيد. تمام فايل هاي مورد نياز به صورت اتوماتيک توليد خواهند شد.';
$lang['mb_not_writable_note']	= 'خطا: پوشه ي application/modules قابل نوشتن نمي باشد.';
$lang['mb_generic_description']	= 'توضيحات را اينجا وارد کنيد.';
$lang['mb_installed_head']		= 'افزونه هاي نصب شده';
$lang['mb_module']				= 'افزونه';
$lang['mb_no_modules']			= 'هيچ افزونه اي نصب نشده است.';

$lang['mb_table_name']			= 'نام';
$lang['mb_table_version']		= 'نسخه';
$lang['mb_table_author']		= 'نويسنده';
$lang['mb_table_description']	= 'توضيحات';

// OUTPUT page
$lang['mb_out_success']	= 'ساخت افزونه موفقيت آميز بود. ليست فايل هاي ايجاد شده را در ادامه ميتوانيد ببينيد.';
$lang['mb_out_success_note']	= 'نکته: لطفا کنترلر هاي ورودي کاربر مورد نياز را استفداده کنيد. اين کد فقط جهت شروع مي باشد.';
$lang['mb_out_tables_success']	= 'جدول پايگاه داده به صورت اتوماتيک نصب شد. شما ميتوانيد جهت تاييد و يا حذف از قسمت %s استفاده کنيد.';
$lang['mb_out_tables_error']	= 'جداول پايگاه داده به صورت اتوماتيم نصب <strong>نشدند</strong>. نياز است که شما به قسمت %s جهت ارتقاي جداول مراجعه نمياييد.';
$lang['mb_out_acl'] 			= 'فايل کنترل سطح دسترسي';
$lang['mb_out_acl_path']        = 'migrations/001_Install_%s_permissions.php';
$lang['mb_out_config'] 			= 'فايل پيکر بندي';
$lang['mb_out_config_path'] 	= 'config/config.php';
$lang['mb_out_controller']		= 'Controller ها';
$lang['mb_out_controller_path']	= 'controllers/%s.php';
$lang['mb_out_model'] 			= 'Model ها';
$lang['mb_out_model_path']		= '%s_model.php';
$lang['mb_out_view']			= 'View ها';
$lang['mb_out_view_path']		= 'views/%s.php';
$lang['mb_out_lang']			= 'فايل زبان';
$lang['mb_out_lang_path']		= '%s_lang.php';
$lang['mb_out_migration']		= 'فالي (هاي) ارتقا';
$lang['mb_out_migration_path']	= 'migrations/002_Install_%s.php';
$lang['mb_new_module']			= 'افزونه جدید';
$lang['mb_exist_modules']		= 'افزونه های موجود';

// FORM page
$lang['mb_form_note'] = '<p><b>فيلد هاي مورد نياز افزونه را وارد کنيد (يک فيلد "id" به صورت خودکار ايجاد مي شود). اگر ميخواهيد SQL را جهت ايجاد پايگاه داده ايجاد نماييد "ايجاد جدول براي افزونه" را بررسي نماييد.</b></p><p>اين فرم جهت ايجاد يک افزونه براي CodeIgniter مي باشد (model, controller and views).</p>';

$lang['mb_table_note'] = '<p>جدول پايگاه داده حداقل بايد شامل يک فيلد باشد ، کليد اصلي جهت ايندکس گذاري استفاده مي شود و يکتا است. اگر فيلد هاي بيشتري نياز داريد ، بر روي تعداد مورد نياز کليک کنيد تا به فرم اضافه شوند.</p>';

$lang['mb_field_note'] = '<p><b>نکته :</b><br />اگر نوع فيلد "enum" و يا "set" باشد ، لطفا مقدار را با استفاده از اين فرمت وارد کنيد : \'a\',\'b\',\'c\'...<br />اگر به بک اسلش نياز بود ("\\") يا علامت نقل قول ("\'") از بک بک اسلش اضافي استفاده کنيد (براي مثال \'\\\\xyz\' يا \'a\\\'b\').</p>';

$lang['mb_form_errors']			= 'لطفا خطا هاي مقابل را تصحيح نماييد.';
$lang['mb_form_mod_details']	= 'توضيحات افزونه ';
$lang['mb_form_mod_name']		= 'نام افزونه';
$lang['mb_form_mod_name_ph']	= 'Forums, Blog, ToDo';
$lang['mb_form_mod_desc']		= 'توضيحات افزونه';
$lang['mb_form_mod_desc_ph']	= 'ليستي از آيتم هاي Todo';
$lang['mb_form_contexts']		= 'بستر هاي مورد نياز';
$lang['mb_form_public']			= 'عمومي';
$lang['mb_form_table_details']	= 'توضيحات جدول';
$lang['mb_form_actions']		= 'عمليات هاي Controller';
$lang['mb_form_primarykey']		= 'کليد داخلي';
$lang['mb_form_delims']			= 'جدا کننده ي ورودي هاي فرم';
$lang['mb_form_err_delims']		= 'جدا کننده ي خطا هاي فرم';
$lang['mb_form_text_ed']		= 'ويرايش گر Textarea';
$lang['mb_form_soft_deletes']	= 'استفاده از حذف "نرم"؟';
$lang['mb_form_use_created']	= 'استفاده از فيلد "زمان ايجاد"؟';
$lang['mb_form_use_modified']	= 'استفاده از فيلد "زمان اصلاح"؟';
$lang['mb_form_created_field']	= 'نام فيلد "زمان ايجاد"؟';
$lang['mb_form_modified_field']	= 'نام فيلد "زمان اصلاح"؟';
$lang['mb_form_generate']		= 'ايجاد جدول افزونه';
$lang['mb_form_role_id']		= 'نقشي که دسترسي کامل داشته باشد';
$lang['mb_form_fieldnum']		= 'تعداد فيلد هاي جدول';
$lang['mb_form_field_details']	= 'مشخصات فيلد ها';
$lang['mb_form_table_name']		= 'نام جدول';
$lang['mb_form_table_name_ph']	= 'حروف کوچک و بدون فاصله';
$lang['mb_form_label']			= 'برچسب';
$lang['mb_form_label_ph']		= 'نامي که در صفحه وب مورد استفاده قرار مي گيرد';
$lang['mb_form_fieldname']		= 'نام (بدون فاصله)';
$lang['mb_form_fieldname_ph']	= 'نام فيلد براي پايگاه داده. بهترين حالت استفاده از حروف کوچک.';
$lang['mb_form_type']			= 'نوع ورودي';
$lang['mb_form_length']			= 'حد اکثر طول <b>-يا-</b> مقادير';
$lang['mb_form_length_ph']		= '30، 255، 1000 و غيره...';
$lang['mb_form_dbtype']			= 'نوع پايگاه داده';
$lang['mb_form_rules']			= 'قوانين اعتبار سنجي';
$lang['mb_form_rules_limits']	= 'محدوديت ورودي';
$lang['mb_form_required']		= 'اجباري';
$lang['mb_form_unique']			= 'يکتا';
$lang['mb_form_trim']			= 'بدون فاصله در ابتدا و انتها';
$lang['mb_form_valid_email']	= 'Email معتبر';
$lang['mb_form_is_numeric']		= '0-9';
$lang['mb_form_alpha']			= 'a-Z';
$lang['mb_form_alpha_dash']		= 'a-Z، 0-9, و _-';
$lang['mb_form_alpha_numeric']	= 'a-Z و 0-9';
$lang['mb_form_add_fld_button'] = 'ايجاد فيلد جديد';
$lang['mb_form_show_advanced']	= '(ديدن گزينه هاي بيشتر)';
$lang['mb_form_show_more']		= '...ديدن قوانين بيشتر...';
$lang['mb_form_integer']		= 'عدد صحيح';
$lang['mb_form_is_decimal']		= 'اعداد ده دهي';
$lang['mb_form_is_natural']		= 'اعداد طبيعي';
$lang['mb_form_is_natural_no_zero']	= 'طبيعي بدون صفر';
$lang['mb_form_valid_ip']		= 'IP معتبر';
$lang['mb_form_valid_base64']	= 'Base64 معتبر';
$lang['mb_form_alpha_extra']	= 'حروف الفبا, خط زير, خط تيره, نقطه و خط فاصله.';
$lang['mb_form_match_existing']	= 'Ensure this matches the existing fieldname!';

// Activities
$lang['mb_act_create']	= 'ايجاد افزونه';
$lang['mb_act_delete']	= 'حذف افزونه';