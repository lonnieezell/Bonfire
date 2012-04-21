<?php

$lang['email_must_be_array'] = '电子邮件地址验证必须传入一个数组.';
$lang['email_invalid_address'] = '无效的电子邮件地址: %s';
$lang['email_attachment_missing'] = '无法定位此电子邮件附件: %s';
$lang['email_attachment_unreadable'] = '无法打开此附件: %s';
$lang['email_no_recipients'] = '必须包含收件人(To)、抄送人(Cc)或暗送人(Bcc)';
$lang['email_send_failure_phpmail'] = '无法使用PHP mail()发送电子邮件. 您的服务器可能没有配置用此方法发邮件.';
$lang['email_send_failure_sendmail'] = '无法使用PHP Sendmail发送邮件. 您的服务器可能没有配置用此方法发邮件.';
$lang['email_send_failure_smtp'] = '无法用PHP SMTP发送邮件. 您的服务器可能没有配置用此方法发邮件.';
$lang['email_sent'] = '您的信件已经被成功的发送了,所使用的协议是: %s';
$lang['email_no_socket'] = '无法打开套接字(socket)以使用Sendmail. 请检查设置.';
$lang['email_no_hostname'] = '您没有指定SMTP主机名.';
$lang['email_smtp_error'] = '出现SMTP错误: %s';
$lang['email_no_smtp_unpw'] = '错误: 您必须指定SMTP用户名和密码.';
$lang['email_failed_smtp_login'] = '发送AUTH LOGIN命令失败. 错误为: %s';
$lang['email_smtp_auth_un'] = '验证用户名失败. 错误为: %s';
$lang['email_smtp_auth_pw'] = '验证密码失败. 错误为: %s';
$lang['email_smtp_data_failure'] = '无法发送数据: %s';
$lang['email_exit_status'] = '结果标识: %s';
?>