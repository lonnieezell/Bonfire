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
//Translator: Sajjad Servatjoo <sajjad.servatjoo[at]gmail[dot]com>

$lang['db_maintenance']			= 'نگهداری';
$lang['db_backups']				= 'پشتیبان ها';

$lang['db_backup_warning']		= 'هشدار: بدلیل محدودیت در زمان اجرا و استفاده از جافظه در php امکان پشتیبان گیری از پایگاه های داده بسیار بزرگ توسط php مقدور نمی باشد. اگر پایگاه داده شما بسیار بزرگ است این عمل را با استفاده از نرم افزار دیتابیس انجام دهید و یا از مدیریت سیستم خود درخواست کنید که این عمل را انجام دهد.';
$lang['db_filename']			= 'نام فایل';

$lang['db_drop_question']		= 'اقزودن دستور &lsquo;Drop Tables&rsquo; به SQL?';
$lang['db_drop_tables']			= 'حذف جداول';
$lang['db_compress_question']	= 'فشرده سازی خروجی؟';
$lang['db_compress_type']		= 'نوع فشرده سازی';
$lang['db_insert_question']		= 'افزودن دستور &lsquo;Inserts&rsquo; به SQL?';
$lang['db_add_inserts']			= 'افزودن ورود اطلاعات';

$lang['db_restore_note']		= 'عملیات بازگردانی فقط برای فایل های غیر فشرده می باشد. فایل های فشرده جهت بایگانی مورد استفاده قرار میگیرند.';

$lang['db_apply']				= 'اعمال';
$lang['db_gzip']				= 'gzip';
$lang['db_zip']					= 'zip';
$lang['db_backup']				= 'پستیبان';
$lang['db_tables']				= 'جداول';
$lang['db_restore']				= 'بازگردانی';
$lang['db_database']			= 'پایگاه داده';
$lang['db_drop']				= 'حذف';
$lang['db_repair']				= 'باز سازی';
$lang['db_optimize']			= 'بهینه سازی';
$lang['db_migrations']			= 'ارتقا';

$lang['db_delete_note']			= 'حذف فایل های پشتیبان انتخاب شده: ';
$lang['db_no_backups']			= 'فایل پشتیبانی یافت نشد.';
$lang['db_backup_delete_confirm']	= 'آیا از حذف فایل های پشتیبان انتخاب شده اطمینان دارید؟';
$lang['db_backup_delete_none']	= 'فایلی جهت حذف انتخاب نشده است';
$lang['db_drop_confirm']		= 'آیا از حذف جدول پایگاه داده انتخاب شده اطمینان دارید؟';
$lang['db_drop_none']			= 'جدولی جهت حذف موجود نمیباشد';
$lang['db_drop_attention']		= '<p>حذف جداول از پایگاه داده جهت از دست دادن اطلاعات می شود.</p><p><strong>این امر احتمال دارد باعث از کار افتادن برنامه شود.</strong></p>';
$lang['db_repair_none']			= 'جدولی جهت باز سازی انتخاب نشده است.';

$lang['db_table_name']			= 'نام جدول';
$lang['db_records']				= 'رکورد ها';
$lang['db_data_size']			= 'اندازه اطلاعات';
$lang['db_index_size']			= 'اندازه اندیس';
$lang['db_data_free']			= 'اطلاعات آزاد';
$lang['db_engine']				= 'انجاین';
$lang['db_no_tables']			= 'جدولی برای پایگاه داده یافت نشد.';

$lang['db_restore_results']		= 'نتایج بازیابی';
$lang['db_back_to_tools']		= 'بازگشت به ابزار پایگاه داده';
$lang['db_restore_file']		= 'بازگردانی پایگاه داده از فایل ها';
$lang['db_restore_attention']	= '<p>بازگردانی پایگاه داده بخش یا کل اطلاعات را پاک می کند.</p><p><strong>این عمل احتمال دارد باعث از دست رفتن اطلاعات شما باشد.</strong>.</p>';

$lang['db_database_settings']	= 'تنظیمات پایگاه داده';
$lang['db_server_type']			= 'نوع سرور';
$lang['db_hostname']			= 'نام سرور';
$lang['db_dbname']				= 'نام پایگاه داده';
$lang['db_advanced_options']	= 'تنظیمات پیشرفته';
$lang['db_persistant_connect']	= 'ازتباط پایدار';
$lang['db_display_errors']		= 'نمایش خطا های پایگاه داده';
$lang['db_enable_caching']		= 'فعال سازی کش در جستجو ها';
$lang['db_cache_dir']			= 'پوشه کش';
$lang['db_prefix']				= 'پیشوند';

$lang['db_servers']				= 'سرورها';
$lang['db_driver']				= 'درایور ها';
$lang['db_persistant']			= 'پایدار';
$lang['db_debug_on']			= 'دیباگ روشن';
$lang['db_strict_mode']			= 'حالت اکید';
$lang['db_running_on_1']		= 'اکنون بر روی سرور زیر اجرا میشود:';
$lang['db_running_on_2']		= 'سرور.';
$lang['db_serv_dev']			= 'گسترش';
$lang['db_serv_test']			= 'آزمایشی';
$lang['db_serv_prod']			= 'محصول نهایی';

$lang['db_successful_save']		= 'تنظیمات شما با موفقیت ذخیره شدند.';
$lang['db_erroneous_save']		= 'خطایی به هنگام ذخیره تنظیمات رخ داده است.';
$lang['db_successful_save_act']	= 'تنظیمات پایگاه داده با موفقیت ذخیره شدند.';
$lang['db_erroneous_save_act']	= 'خطایی به هنگام ذخیره تنظیمات رخ داده است.';

$lang['db_sql_query']			= 'پرس و جوی SQL';
$lang['db_total_results']		= 'نتایح';
$lang['db_no_rows']				= 'داده ای یافت نشد.';
$lang['db_browse']				= 'پیمایش';
