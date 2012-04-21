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

$lang['role_intro']					= '角色系统用于定义每个用户所具备的操作权限.';
$lang['role_manage']				= '用户角色管理';
$lang['role_no_roles']				= '暂无用户角色信息.';
$lang['role_create_button']			= '创建一个新的角色.';
$lang['role_create_note']			= '每个用户都需要一个角色. 如没有您需要的角色, 请创建新的角色.';
$lang['role_account_type']			= '角色类型';
$lang['role_description']			= '描述';
$lang['role_details']				= '角色细节';

$lang['role_name']					= '角色名称';
$lang['role_max_desc_length']		= '最多255个字节.';
$lang['role_default_role']			= '默认角色';
$lang['role_default_note']			= '勾选, 则用户注册时默认指定为该角色.';
$lang['role_permissions']			= '权限分配';
$lang['role_permissions_check_note']= '为角色分配权限.';
$lang['role_save_role']				= '保存角色信息';
$lang['role_delete_role']			= '删除该角色';
$lang['role_delete_note']			= '删除该角色, 会将当前属于该角色的所有用户分配到系统默认的角色.';
$lang['role_can_delete_role']   	= '可删除的';	
$lang['role_can_delete_note']    	= '这个角色可以删除么?';

$lang['role_roles']					= '角色';
$lang['role_new_role']				= '新的角色';
$lang['role_new_permission_message']	= '角色一旦创建便可以立即编辑权限.';
$lang['role_not_used']				= '不可用';

$lang['role_login_destination']		= '登录目的地';
$lang['role_destination_note']		= '将登录成功的用户重定向到该地址.';

$lang['matrix_header']				= '列表展现模式';
$lang['matrix_permission']			= '权限';
$lang['matrix_role']				= '角色';
$lang['matrix_note']				= '快速编辑权限. 勾选/取消勾选复选框来为该角色添加或移除权限.';
$lang['matrix_insert_success']		= '已为该角色添加了权限.';
$lang['matrix_insert_fail']			= '为角色分配权限发生异常 : ';
$lang['matrix_delete_success']		= '该角色的权限被移除.';
$lang['matrix_delete_fail']			= '删除权限异常 : ';
$lang['matrix_auth_fail']			= '身份验证: 您没有权限管理该角色的访问控制.';
