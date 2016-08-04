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

$lang['us_account_deleted']			= '很抱歉, 您的账户已被删除. 但账户信息<strong>可能</strong>仍然存在. 请联系管理员:  %s.';

$lang['us_bad_email_pass']			= '邮箱或密码错误.';
$lang['us_must_login']				= '您必须登录才能查看该页面.';
$lang['us_no_permission']			= '很抱歉, 您没有查看该页面的权限.';

$lang['us_access_logs']				= '用户访问日志';
$lang['us_logged_in_on']			= '已登录';
$lang['us_no_access_message']		= '<p>恭喜!</p><p>所有用户数据保存完整!</p>';
$lang['us_log_create']				= '创建新用户';
$lang['us_log_edit']				= '修改用户';
$lang['us_log_delete']				= '删除用户';
$lang['us_log_logged']				= '已登录.';
$lang['us_log_reset']				= '重新设定密码.';
$lang['us_log_register']			= '注册新账号.';
$lang['us_log_edit_profile']		= '更新账户信息';


$lang['us_deleted_users']			= '已删除用户';
$lang['us_purge_del_accounts']		= '完全删除账户信息';
$lang['us_purge_del_note']			= '<h3>永久删除账号信息</h3><p>该操作一旦完成, 便不可撤销. 请谨慎操作.</p>';
$lang['us_purge_del_confirm']		= '你确定要彻底删除该账户信息吗 - 此操作不可恢复?';
$lang['us_action_purged']			= '用户已永久删除.';
$lang['us_action_deleted']			= '用户删除成功.';
$lang['us_action_not_deleted']		= '不能删除该帐号: ';
$lang['us_delete_account']			= '删除帐号';
$lang['us_delete_account_note']		= '<h3>删除该帐号</h3><p>删除该账号将撤销它在网站中的所有权限.</p>';
$lang['us_delete_account_confirm']	= '你确定要彻底删除该账户信息吗?';

$lang['us_restore_account']			= '帐号恢复';
$lang['us_restore_account_note']	= '<h3>恢复此帐号</h3><p>恢复帐号的信息.</p>';
$lang['us_restore_account_confirm']	= '要恢复这个帐号的信息吗?';

$lang['us_role']					= '角色';
$lang['us_role_lower']				= '角色';
$lang['us_no_users']				= '没有找到该用户.';
$lang['us_create_user']				= '创建新用户';
$lang['us_create_user_note']		= '<h3>创建新用户</h3><p>为其他用户创建账号信息.</p>';
$lang['us_edit_user']				= '编辑用户信息';
$lang['us_restore_note']			= '恢复后, 该用户即可再次访问网站.';
$lang['us_unban_note']				= '解冻用户, 并允许其访问网站内容.';
$lang['us_account_status']			= '账户状态';

$lang['us_failed_login_attempts']	= '登录失败记录';
$lang['us_failed_logins_note']		= '<p>恭喜!</p><p>所有用户数据保存完整!</p>';

$lang['us_banned_admin_note']		= '该用户已被隔离, 咱不能访问网站信息.';
$lang['us_banned_msg']				= '您的账户没有权限进入该页面.';

$lang['us_first_name']				= '名字';
$lang['us_last_name']				= '姓';
$lang['us_address']					= '地址';
$lang['us_street_1']				= '街道 1';
$lang['us_street_2']				= '街道 2';
$lang['us_city']					= '城市';
$lang['us_state']					= '省份';
$lang['us_no_states']				= '该国家没有这个省/市/县. 请在地址配置文件里边创建.';
$lang['us_country']					= '国家';
$lang['us_zipcode']					= '邮编';

$lang['us_user_management']			= '用户管理';
$lang['us_email_in_use']			= '邮箱地址已经注册. 请填写其他邮箱.';

$lang['us_edit_profile']			= '编辑账户信息';
$lang['us_edit_note']				= '请在下方填写您的账户信息, 并点击保存按钮.';

$lang['us_reset_password']			= '重设密码';
$lang['us_reset_note']				= '输入您的注册邮箱, 我们会发送重置密码链接到您的邮箱.';

$lang['us_login']					= '用户登录...';
$lang['us_remember_note']			= '下次自动登录';
$lang['us_no_account']				= '还没有账户?';
$lang['us_sign_up']					= '注册';
$lang['us_forgot_your_password']	= '忘记密码?';

$lang['us_password_mins']			= '密码长度大于8位.';
$lang['us_register']				= '用户注册...';
$lang['us_already_registered']		= '已经注册?';

$lang['us_action_save']				= '保存用户';
$lang['us_unauthorized']			= '未被授权的操作. 抱歉, 您没有适当的权限执行 "%s" 操作.';
$lang['us_empty_id']				= '未提供用户ID. 您必须提供用户ID来执行这个操作.';
$lang['us_self_delete']				= '未被授权的操作. 抱歉, 您不能够删除自己.';

$lang['us_filter_first_letter']		= '开头为【*】的用户名: ';
$lang['us_account_details']			= '帐号详情';
$lang['us_last_login']				= '最后登录';
