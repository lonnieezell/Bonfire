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

//--------------------------------------------------------------------
// !SETTINGS
//--------------------------------------------------------------------

$lang['bf_site_name']			= 'نام سايت';
$lang['bf_site_email']			= 'پست الکترونيکي';
$lang['bf_site_email_help']		= 'ايميل پيش فرض جهت ارسال ايميل هاي ايجاد شده توسط سيستم.';
$lang['bf_site_status']			= 'وضعيت سايت';
$lang['bf_online']				= 'آنلاين';
$lang['bf_offline']				= 'آفلاين';
$lang['bf_top_number']			= 'تعداد موارد <em> در هر </em> صفحه:';
$lang['bf_top_number_help']		= 'به هنگام نمايش گزارشات چند مورد در هر صفحه نمايش داده شود؟';

$lang['bf_security']			= 'امنيت';
$lang['bf_login_type']			= 'نوع ورود:';
$lang['bf_login_type_email']	= 'فقط ايميل';
$lang['bf_login_type_username']	= 'فقط نام کاربري';
$lang['bf_allow_register']		= 'فعال سازي ثبت نام توسط کاربران؟';
$lang['bf_login_type_both']		= 'ايميل و يا نام کاربري';
$lang['bf_use_usernames']		= 'نمايش کاربران در محيط سايت:';
$lang['bf_use_own_name']		= 'از نام شخصي استفاده شود';
$lang['bf_allow_remember']		= 'اجازه \'به خاطر سپردن\' کاربرات؟';
$lang['bf_remember_time']		= 'به خاطر سپردن کاربران به مدت:';
$lang['bf_week']				= 'هفته';
$lang['bf_weeks']				= 'هفته';
$lang['bf_days']				= 'روز';
$lang['bf_username']			= 'نام کاربري';
$lang['bf_password']			= 'کلمه عبور';
$lang['bf_password_confirm']	= 'تکرار کلمه عبور';

$lang['bf_home_page']			= 'صفحه خانگي';
$lang['bf_pages']				= 'صفحات';
$lang['bf_enable_rte']			= 'فعال سازي اديتور براي صفحات؟';
$lang['bf_rte_type']			= 'نوع اديتور';
$lang['bf_searchable_default']	= 'به طور پيش فرض قابل جستجو باشد؟';
$lang['bf_cacheable_default']	= 'به طور پيش فرض قابل کش باشد؟';
$lang['bf_track_hits']			= 'پيگيري بازديد از صفحات؟';

$lang['bf_action_save']			= 'ذخيره';
$lang['bf_action_delete']		= 'حذف';
$lang['bf_action_cancel']		= 'انصراف';
$lang['bf_action_download']		= 'دانلود';
$lang['bf_action_preview']		= 'پيش نمايش';
$lang['bf_action_search']		= 'جستجو';
$lang['bf_action_purge']		= 'پاکسازي';
$lang['bf_action_restore']		= 'بازيابي';
$lang['bf_action_show']			= 'نمايش';
$lang['bf_action_login']		= 'ورود';
$lang['bf_actions']				= 'عمليات';

$lang['bf_do_check']			= 'بررسي جهت به روز رساني؟';
$lang['bf_do_check_edge']		= 'جهت ديدن آخرين به روز رساني ها اين گزينه را فعال نماييد.';

$lang['bf_update_show_edge']	= 'نمايش تمامي به روز رساني ها؟';
$lang['bf_update_info_edge']	= 'جهت نمايش به  روز رساني هاي تاييد شده اين گزينه را فعال نکنيد. چنانچه ميخواهيد تمامي به روز رساني ها را مشاهده کنيد اين گزينه را فعال نماييد.';

$lang['bf_ext_profile_show']	= 'آيا کاربران داراي صفحه مشخصات پيشرفته باشند؟';
$lang['bf_ext_profile_info']	= 'جهت استفاده از صفحه مشخصات پيشرفته براي کاربران گزينه مشخصات پيشرفته را فعال نماييد.';

$lang['bf_yes']					= 'بله';
$lang['bf_no']					= 'خير';
$lang['bf_none']				= 'هيچ';

$lang['bf_or']					= 'يا';
$lang['bf_size']				= 'اندازه';
$lang['bf_files']				= 'فايل ها';
$lang['bf_file']				= 'فايل';

$lang['bf_with_selected']		= 'انتخاب شده ';

$lang['bf_env_dev']				= 'توسعه';
$lang['bf_env_test']			= 'آزمايش';
$lang['bf_env_prod']			= 'محصول نهايي';

$lang['bf_user']				= 'کاربر';
$lang['bf_users']				= 'کاربران';
$lang['bf_username']			= 'نام کاربري';
$lang['bf_description']			= 'توضيحات';
$lang['bf_email']				= 'ايميل';
$lang['bf_user_settings']		= 'مشخصات من';

$lang['bf_both']				= 'هر دو';
$lang['bf_go_back']				= 'بازگشت';
$lang['bf_new']					= 'جديد';
$lang['bf_required_note']		= 'گزينه هاي اجباري <b>ضخيم تر</b> هستند.';

$lang['bf_show_profiler']		= 'نمايش اطلاعات اجرا در بخش مديريت';
$lang['bf_show_front_profiler']	= 'نمايش اطلاعات اجرا در بخش کاربري';

//--------------------------------------------------------------------
// MY_Model
//--------------------------------------------------------------------
$lang['bf_model_no_data']		= 'چيزي يافت نشد.';
$lang['bf_model_invalid_id']	= 'مشخصه ارسال شده صحيح نمي باشد.';
$lang['bf_model_no_table']		= 'جدول پايگاه داده نامشخص است.';
$lang['bf_model_fetch_error']	= 'اطلاعات کافي جهت واکشي موجود نيست.';
$lang['bf_model_count_error']	= 'اطلاعات کافي جهت شمارش نتايج موجود نيست.';
$lang['bf_model_unique_error']	= 'اطلاعات کافي جهت بررسي يکتا بودن موجود نيست.';
$lang['bf_model_find_error']	= 'اطلاعات کافي جهت جستجو موجود نيست.';
$lang['bf_model_bad_select']	= 'انتخاب نادرست.';

//--------------------------------------------------------------------
// Contexts
//--------------------------------------------------------------------
$lang['bf_no_contexts']			= 'آرايه ي contexts به صورت صحيح پيکر بندي نشده است. فايل کانفيگ برنامه را بررسي نماييد.';
$lang['bf_context_content']		= 'محتوي';
$lang['bf_context_reports']		= 'گزارشات';
$lang['bf_context_settings']	= 'تنظيمات';
$lang['bf_context_developer']	= 'توسعه';

//--------------------------------------------------------------------
// Activities
//--------------------------------------------------------------------
$lang['bf_act_settings_saved']	= 'تنظيمان برنامه ذخيره شدند توسط ';

//--------------------------------------------------------------------
// Common
//--------------------------------------------------------------------
$lang['bf_question_mark']	= '؟';
$lang['bf_language_direction']	= 'rtl';