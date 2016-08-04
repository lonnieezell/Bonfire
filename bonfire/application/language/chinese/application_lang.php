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
// ! GENERAL SETTINGS
//--------------------------------------------------------------------

$lang['bf_site_name']			= '网站名称';
$lang['bf_site_email']			= '网站邮箱';
$lang['bf_site_email_help']		= '系统默认使用该Email进行发送.';
$lang['bf_site_status']			= '网站状态';
$lang['bf_online']				= '在线';
$lang['bf_offline']				= '离线';
$lang['bf_top_number']			= '<em>每页</em> 内容数量:';
$lang['bf_top_number_help']		= '查看日志信息时, 每页显示多少条内容?';
$lang['bf_home']				= '首页';
$lang['bf_site_information']	= '站点信息';
$lang['bf_timezone']			= '时区';
$lang['bf_language']			= '语言';
$lang['bf_language_help']		= '选择用户可用的语言.';

//--------------------------------------------------------------------
// ! AUTH SETTINGS
//--------------------------------------------------------------------

$lang['bf_security']			= '安全';
$lang['bf_login_type']			= '登录类型';
$lang['bf_login_type_email']	= '仅邮箱';
$lang['bf_login_type_username']	= '仅用户名';
$lang['bf_allow_register']		= '允许用户注册?';
$lang['bf_login_type_both']		= '邮箱或用户名';
$lang['bf_use_usernames']		= '账号显示信息:';
$lang['bf_use_own_name']		= '使用自己的真实姓名';
$lang['bf_allow_remember']		= '允许 \'下次自动登录\'?';
$lang['bf_remember_time']		= '会话保存时间';
$lang['bf_week']				= '周';
$lang['bf_weeks']				= '周';
$lang['bf_days']				= '天';
$lang['bf_username']			= '用户名';
$lang['bf_password']			= '密码';
$lang['bf_password_confirm']	= '确认密码';
$lang['bf_display_name']		= '显示名称';

//--------------------------------------------------------------------
// ! CRUD SETTINGS
//--------------------------------------------------------------------

$lang['bf_home_page']			= '主页';
$lang['bf_pages']				= '页面';
$lang['bf_enable_rte']			= '使页面支持 RTE ?';
$lang['bf_rte_type']			= 'RTE 类型';
$lang['bf_searchable_default']	= '默认可搜索?';
$lang['bf_cacheable_default']	= '默认可缓存?';
$lang['bf_track_hits']			= '跟踪页面点击行为?';

$lang['bf_action_save']			= '保存';
$lang['bf_action_delete']		= '删除';
$lang['bf_action_cancel']		= '取消';
$lang['bf_action_download']		= '下载';
$lang['bf_action_preview']		= '预览';
$lang['bf_action_search']		= '搜索';
$lang['bf_action_purge']		= '完全删除';
$lang['bf_action_restore']		= '恢复';
$lang['bf_action_show']			= '显示';
$lang['bf_action_login']		= '登录';
$lang['bf_action_logout']		= '登出';
$lang['bf_actions']				= '操作';
$lang['bf_clear']				= '清除';
$lang['bf_action_list']			= '列表';
$lang['bf_action_create']		= '创建';
$lang['bf_action_ban']			= '冻结';

//--------------------------------------------------------------------
// ! SETTINGS LIB
//--------------------------------------------------------------------

$lang['bf_do_check']			= '检查更新 ?';
$lang['bf_do_check_edge']		= '必须能够产看技术更新信息.';

$lang['bf_update_show_edge']	= '查看技术更新?';
$lang['bf_update_info_edge']	= '仅检查官方版本库中提交并且已标记的更新.';

$lang['bf_ext_profile_show']	= '用户的账号有扩展信息么?';
$lang['bf_ext_profile_info']	= '是否扩展用户的基本信息, 默认不勾选为使用.';

$lang['bf_yes']					= '是';
$lang['bf_no']					= '否';
$lang['bf_none']				= '无';
$lang['bf_id']					= 'ID';

$lang['bf_or']					= '或者';
$lang['bf_size']				= '大小';
$lang['bf_files']				= '文件';
$lang['bf_file']				= '文件';

$lang['bf_with_selected']		= '已选择';

$lang['bf_env_dev']				= '开发';
$lang['bf_env_test']			= '测试';
$lang['bf_env_prod']			= '产品';

$lang['bf_show_profiler']		= '显示管理员信息?';
$lang['bf_show_front_profiler']	= '显示前端页面属性?';

$lang['bf_cache_not_writable']  = '应用缓存文件夹不可写入';

$lang['bf_password_strength']			= '密码长度设置';
$lang['bf_password_length_help']		= '密码最小长度，例如 8';
$lang['bf_password_force_numbers']		= '密码强制为数字?';
$lang['bf_password_force_symbols']		= '密码强制为字符?';
$lang['bf_password_force_mixed_case']	= '密码强制为混合大小写?';

//--------------------------------------------------------------------
// ! USER/PROFILE
//--------------------------------------------------------------------

$lang['bf_user']				= '用户';
$lang['bf_users']				= '位用户';
$lang['bf_username']			= '用户名';
$lang['bf_description']			= '描述';
$lang['bf_email']				= '邮箱地址';
$lang['bf_user_settings']		= '我的账户信息';

//--------------------------------------------------------------------
// !
//--------------------------------------------------------------------

$lang['bf_both']				= '两者';
$lang['bf_go_back']				= '返回';
$lang['bf_new']					= '新建';
$lang['bf_required_note']		= '<b>加粗</b>部分为必填.';
$lang['bf_form_label_required'] = '<span class="required">*</span>';

//--------------------------------------------------------------------
// MY_Model
//--------------------------------------------------------------------
$lang['bf_model_no_data']		= '没有数据.';
$lang['bf_model_invalid_id']	= '对于该模型无效的ID.';
$lang['bf_model_no_table']		= '模型没有指定的数据库表.';
$lang['bf_model_fetch_error']	= '没有足够的信息来获取字段.';
$lang['bf_model_count_error']	= '没有足够的信息来计算结果.';
$lang['bf_model_unique_error']	= '没有足够的信息来检测唯一性.';
$lang['bf_model_find_error']	= '没有足够的信息来查找.';
$lang['bf_model_bad_select']	= '无效选择.';

//--------------------------------------------------------------------
// Contexts
//--------------------------------------------------------------------
$lang['bf_no_contexts']			= '该内容可能尚未安装, 请查看您的配置文件.';
$lang['bf_context_content']		= '内容管理';
$lang['bf_context_reports']		= '报告信息';
$lang['bf_context_settings']	= '系统设定';
$lang['bf_context_developer']	= '开发者';

//--------------------------------------------------------------------
// Activities
//--------------------------------------------------------------------
$lang['bf_act_settings_saved']	= '应用基本信息设定操作, 来自于';
$lang['bf_unauthorized_attempt']= '试图访问页面失败, 该页面要求用户具备 "%s" 权限, 访问源: ';

$lang['bf_keyboard_shortcuts']		= '可用的键盘快捷键:';
$lang['bf_keyboard_shortcuts_none']	= '没有可用的键盘快捷键.';
$lang['bf_keyboard_shortcuts_edit']	= '更新键盘快捷键';

//--------------------------------------------------------------------
// Common
//--------------------------------------------------------------------
$lang['bf_question_mark']	= '?';
$lang['bf_language_direction']	= 'ltr';
$lang['log_intro']              = '这是你的登录信息';

//--------------------------------------------------------------------
// Login
//--------------------------------------------------------------------
$lang['bf_action_register']		= '登录';
$lang['bf_forgot_password']		= '忘记密码?';
$lang['bf_remember_me']			= '保存登录状态';
