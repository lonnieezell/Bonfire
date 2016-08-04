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

$lang['log_no_logs'] 			= '没有日志记录.';
$lang['log_not_enabled']		= '日志功能当前不可用.';
$lang['log_the_following']		= '日志如下:';
$lang['log_what_0']				= '0 - 无';
$lang['log_what_1']				= '1 - 错误信息 (包含PHP错误信息)';
$lang['log_what_2']				= '2 - Debug错误信息';
$lang['log_what_3']				= '3 - 正常记录信息';
$lang['log_what_4']				= '4 - 所有信息';
$lang['log_what_note']			= '数字越大, 日志记录信息越多 .  例如, "2 - Debug错误信息" 会包含 "1 - 错误信息"的所有日志信息.';

$lang['log_save_button']		= '保存日志设定信息';
$lang['log_delete_button']		= '删除日志文件';
$lang['log_delete1_button']		= '删除这个日志文件吗?';
$lang['logs_delete_confirm']	= '你确定删除这些日志信息么?';

$lang['log_big_file_note']		= '如果您记录了太多的日志信息, 文件增长速度会非常快. 对于正常的站点, 建议您仅记录错误日志信息.';
$lang['log_delete_note']		= '<h3>删除所有日志文件么?</h3><p>删除日志文件是不可恢复的, 请谨慎操作.</p>';
$lang['log_delete1_note']		= '<h3>删除日志文件 "%s"?</h3><p>删除日志文件是不可恢复的, 请谨慎操作.</p>';
$lang['log_delete_confirm']     = '你确定删除这个日志文件么?';

$lang['log_not_found']			= '日志不能被加载, 或者日志为空.';
$lang['log_show_all_entries']	= '所有日志';
$lang['log_show_errors']		= '仅显示错误';

$lang['log_date']				= '日期';
$lang['log_file']				= '文件名';
$lang['log_logs']				= '日志';
$lang['log_settings']			= '设置';

$lang['log_title']				= '系统日志';
$lang['log_title_settings']		= '系统日志设置';
$lang['log_deleted']			= '日志已删除';
$lang['log_filter_label']       = '查看';
$lang['log_intro']              = '这是你的错误和debug日志....';
