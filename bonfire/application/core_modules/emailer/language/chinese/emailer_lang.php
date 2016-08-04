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

$lang['em_template']			= '模板';
$lang['em_email_template']		= '邮件模板';
$lang['em_emailer_queue']		= '邮件队列';

$lang['em_system_email']		= '系统邮件地址';
$lang['em_system_email_note']	= '系统生成的邮件将由此邮箱发送.';
$lang['em_email_server']		= 'Email 服务';
$lang['em_settings']			= 'Email 设定';
$lang['em_settings_note']		= '<b>Email</b> 使用标准的PHP mail函数, 不需设定.';
$lang['em_location']			= '本地';
$lang['em_server_address']		= '服务地址';
$lang['em_port']				= '端口';
$lang['em_timeout_secs']		= '超时(秒)';
$lang['em_email_type']			= '邮件类型(html/text)';
$lang['em_test_settings']		= '测试邮件设置';

$lang['em_template_note']		= '邮件使用HTML格式发送. 可以自定义页眉和页脚.';
$lang['em_header']				= '页眉';
$lang['em_footer']				= '页脚';

$lang['em_test_header']			= '测试您的设定';
$lang['em_test_intro']			= '输入一个邮件地址, 来验证您的邮件设置是否正确. 在测试之前, 请先保存当前的设置.';
$lang['em_test_button']			= '发送测试邮件';
$lang['em_test_result_header']	= '邮件测试结构';
$lang['em_test_no_results']		= '测试未成功, 或者未返回任何数据.';
$lang['em_test_debug_header']	= '邮件 debug 信息';
$lang['em_test_success']		= '邮件显示设置正确. 如果您在收件箱中未看到这封邮件, 请查看您的垃圾箱.';
$lang['em_test_error']			= '邮箱设置不正确.';

$lang['em_test_mail_subject']	= '恭喜! 您的邮箱设置工作良好!';
$lang['em_test_mail_body']		= '如果您看到这封邮件, 那说明系统邮件功能良好!';

$lang['em_stat_no_queue']		= '邮件队列中没有待发邮件.';
$lang['em_total_in_queue']		= '待发送邮件数:';
$lang['em_total_sent']			= '已发送邮件数:';

$lang['em_sent']				= '发送';
$lang['em_attempts']			= '尝试';
$lang['em_id']					= 'ID';
$lang['em_to']					= '收件人';
$lang['em_subject']				= '主题';

$lang['em_missing_data']		= '多个字段未填写.';
$lang['em_no_debug']			= '邮件已加入发送队列中, 无跟踪数据.';
