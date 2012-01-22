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

$lang['db_maintenance']			= 'نگهداري';
$lang['db_database_maintenance']			= 'نگهداری پایگاه داده';
$lang['db_backups']				= 'پشتيبان ها';
$lang['db_database_backups']				= 'پشتیبانی پایگاه داده';

$lang['db_backup_warning']		= 'هشدار : به دليل محدوديت هاي مربوط به حافظه در php امکان پشتيان گيري از پايگاه هاي داده هاي بسيار حجيم وجود ندارد. جهت انجام اين کار بايد مستقيما عمل نماييد.';
$lang['db_filename']			= 'نام فايل';

$lang['db_drop_question']		= 'افزودن دستور &lsquo;Drop Tables&rsquo; به SQL؟';
$lang['db_compress_question']	= 'نحوه فشرده سازي؟';
$lang['db_insert_question']		= 'افزودن &lsquo;Inserts&rsquo; جهت داده ها به SQL؟';

$lang['db_restore_note']		= 'عمل بازيابي براي فايل هاي غير فشرده امکان پذير مي باشد. فايل هاي Gzip و Zip جهت دانلود شما و نگهداري آنها بر روي کامپيوتر مناسب هستند.';

$lang['db_gzip']				= 'gzip';
$lang['db_zip']					= 'zip';
$lang['db_backup']				= 'پشتبان گيري';
$lang['db_tables']				= 'جداول';
$lang['db_restore']				= 'بازيابي';
$lang['db_database']			= 'پايگاه داده';
$lang['db_drop']				= 'حذف';
$lang['db_repair']				= 'باز سازي';
$lang['db_optimize']			= 'بهينه سازي';
$lang['db_apply']			= 'اعمال';

$lang['db_delete_note']			= 'حذف فايلهاي پشتيبان انتخاب شده: ';
$lang['db_no_backups']			= 'هيچ فايل پشتيباني يافت نشد.';
$lang['db_backup_delete_confirm']	= 'آيا از حذف فايلهاي پشتيبان انتخاب شده اطمينان داريد؟';
$lang['db_drop_confirm']		= 'آيا از حذف جداول پايگاه داده ي انتخاب شده اطمينان داريد؟';
$lang['db_drop_attention']		= '<p>حذف جدول از پايگاه داده منجر به از دست دادن اطلاعات ميشود.</p><p><strong>اين عمل احتمال دارد برنامه ي شما را از کار بياندازد.</strong></p>';

$lang['db_table_name']			= 'نام جدول';
$lang['db_records']				= 'رکورد ها';
$lang['db_data_size']			= 'اندازه داده';
$lang['db_index_size']			= 'اندازه ايندکس';
$lang['db_data_free']			= 'داده خالي';
$lang['db_engine']				= 'انجاين';
$lang['db_no_tables']			= 'جدولي براي پايگاه داده ي کنوني يافت نشد.';

$lang['db_restore_results']		= 'نتايج بازيابي';
$lang['db_back_to_tools']		= 'بازگشت به ابزار پايگاه داده';
$lang['db_restore_file']		= 'بازيابي پايگاه داده از فايل';
$lang['db_restore_attention']	= '<p>نتيجه باز يابي پايگاه داده از فايل ممکن است تمام و يا بخشي از اطلاعات شما را حذف نمايد.</p><p><strong>ممکن است با اين عمل بخشي از اطلاعات از بين برود</strong>.</p>';

$lang['db_database_settings']	= 'تنظيمات پايگاه داده';
$lang['db_server_type']			= 'نوع سرور';
$lang['db_hostname']			= 'ميزبان';
$lang['db_dbname']				= 'نام پايگاه داده';
$lang['db_advanced_options']	= 'گزينه هاي پيشرفته';
$lang['db_persistant_connect']	= 'اتصال مداوم';
$lang['db_display_errors']		= 'نمايش خطا هاي پايگاه داده';
$lang['db_enable_caching']		= 'فعال سازي کش در جستجو ها';
$lang['db_cache_dir']			= 'پوشه کش';
$lang['db_prefix']				= 'پيشوند';

$lang['db_servers']				= 'سرورها';
$lang['db_driver']				= 'درايور';
$lang['db_persistant']			= 'مداوم';
$lang['db_debug_on']			= 'اشکال زدايي در';
$lang['db_strict_mode']			= 'حالت موکد';
$lang['db_running_on_1']		= 'هم اکنون اجرا ميشود در: ';
$lang['db_running_on_2']		= 'سرور.';

$lang['db_successful_save']		= 'تنظيمات با موفقيت ذخيره شدند.';
$lang['db_erroneous_save']		= 'خطايي به هنگام ذخيره تنظيمات رخ داده است.';
$lang['db_successful_save_act']	= 'تنظيمات پايگاه داده با موفقيت ذخيره شد.';
$lang['db_erroneous_save_act']	= 'خطا به هنگام ذخيره ي تنظيمات پايگاه داده';