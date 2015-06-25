<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 * @filesource
 */

/**
 * Language file for the Database Module (Persian)
 *
 * @package    Bonfire\Modules\Database\Language\Persian
 * @author     Sajjad Servatjoo <sajjad.servatjoo[at]gmail[dot]com>
 * @link       http://cibonfire.com/docs
 */

$lang['database_maintenance']       = 'نگهداری';
$lang['database_backups']           = 'پشتیبان ها';

$lang['database_backup_warning']    = 'هشدار: بدلیل محدودیت در زمان اجرا و استفاده از جافظه در php امکان پشتیبان گیری از پایگاه های داده بسیار بزرگ توسط php مقدور نمی باشد. اگر پایگاه داده شما بسیار بزرگ است این عمل را با استفاده از نرم افزار دیتابیس انجام دهید و یا از مدیریت سیستم خود درخواست کنید که این عمل را انجام دهد.';
$lang['database_filename']          = 'نام فایل';

$lang['database_drop_question']     = 'اقزودن دستور &lsquo;Drop Tables&rsquo; به SQL?';
$lang['database_drop_tables']       = 'حذف جداول';
$lang['database_compress_question'] = 'فشرده سازی خروجی؟';
$lang['database_compress_type']     = 'نوع فشرده سازی';
$lang['database_insert_question']   = 'افزودن دستور &lsquo;Inserts&rsquo; به SQL?';
$lang['database_add_inserts']       = 'افزودن ورود اطلاعات';

$lang['database_restore_note']      = 'عملیات بازگردانی فقط برای فایل های غیر فشرده می باشد. فایل های فشرده جهت بایگانی مورد استفاده قرار میگیرند.';

$lang['database_apply']             = 'اعمال';
$lang['database_gzip']              = 'gzip';
$lang['database_zip']               = 'zip';
$lang['database_backup']            = 'پستیبان';
$lang['database_tables']            = 'جداول';
$lang['database_restore']           = 'بازگردانی';
$lang['database_database']          = 'پایگاه داده';
$lang['database_drop']              = 'حذف';
$lang['database_repair']            = 'باز سازی';
$lang['database_optimize']          = 'بهینه سازی';
$lang['database_migrations']        = 'ارتقا';

$lang['database_delete_note']           = 'حذف فایل های پشتیبان انتخاب شده: ';
$lang['database_no_backups']            = 'فایل پشتیبانی یافت نشد.';
$lang['database_backup_delete_confirm'] = 'آیا از حذف فایل های پشتیبان انتخاب شده اطمینان دارید؟';
$lang['database_backup_delete_none']    = 'فایلی جهت حذف انتخاب نشده است';
$lang['database_drop_confirm']          = 'آیا از حذف جدول پایگاه داده انتخاب شده اطمینان دارید؟';
$lang['database_drop_none']             = 'جدولی جهت حذف موجود نمیباشد';
$lang['database_drop_attention']        = '<p>حذف جداول از پایگاه داده جهت از دست دادن اطلاعات می شود.</p><p><strong>این امر احتمال دارد باعث از کار افتادن برنامه شود.</strong></p>';
$lang['database_repair_none']           = 'جدولی جهت باز سازی انتخاب نشده است.';

$lang['database_table_name']            = 'نام جدول';
$lang['database_records']               = 'رکورد ها';
$lang['database_data_size']             = 'اندازه اطلاعات';
$lang['database_index_size']            = 'اندازه اندیس';
$lang['database_data_free']             = 'اطلاعات آزاد';
$lang['database_engine']                = 'انجاین';
$lang['database_no_tables']             = 'جدولی برای پایگاه داده یافت نشد.';

$lang['database_restore_results']       = 'نتایج بازیابی';
$lang['database_back_to_tools']         = 'بازگشت به ابزار پایگاه داده';
$lang['database_restore_file']          = 'بازگردانی پایگاه داده از فایل ها';
$lang['database_restore_attention']     = '<p>بازگردانی پایگاه داده بخش یا کل اطلاعات را پاک می کند.</p><p><strong>این عمل احتمال دارد باعث از دست رفتن اطلاعات شما باشد.</strong>.</p>';

$lang['database_database_settings']     = 'تنظیمات پایگاه داده';
$lang['database_server_type']           = 'نوع سرور';
$lang['database_hostname']              = 'نام سرور';
$lang['database_dbname']                = 'نام پایگاه داده';
$lang['database_advanced_options']      = 'تنظیمات پیشرفته';
$lang['database_persistent_connect']    = 'ازتباط پایدار';
$lang['database_display_errors']        = 'نمایش خطا های پایگاه داده';
$lang['database_enable_caching']        = 'فعال سازی کش در جستجو ها';
$lang['database_cache_dir']             = 'پوشه کش';
$lang['database_prefix']                = 'پیشوند';

$lang['database_servers']               = 'سرورها';
$lang['database_driver']                = 'درایور ها';
$lang['database_persistent']            = 'پایدار';
$lang['database_debug_on']              = 'دیباگ روشن';
$lang['database_strict_mode']           = 'حالت اکید';
$lang['database_running_on_1']          = 'اکنون بر روی سرور زیر اجرا میشود:';
$lang['database_running_on_2']          = 'سرور.';
$lang['database_serv_dev']              = 'گسترش';
$lang['database_serv_test']             = 'آزمایشی';
$lang['database_serv_prod']             = 'محصول نهایی';

$lang['database_successful_save']       = 'تنظیمات شما با موفقیت ذخیره شدند.';
$lang['database_erroneous_save']        = 'خطایی به هنگام ذخیره تنظیمات رخ داده است.';
$lang['database_successful_save_act']   = 'تنظیمات پایگاه داده با موفقیت ذخیره شدند.';
$lang['database_erroneous_save_act']    = 'خطایی به هنگام ذخیره تنظیمات رخ داده است.';

$lang['database_sql_query']             = 'پرس و جوی SQL';
$lang['database_total_results']         = 'نتایح';
$lang['database_no_rows']               = 'داده ای یافت نشد.';
$lang['database_browse']                = 'پیمایش';
