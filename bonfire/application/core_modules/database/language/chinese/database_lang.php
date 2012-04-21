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

$lang['db_maintenance']			= '维护';
$lang['db_backups']				= '备份';

$lang['db_backup_warning']		= '注意: 为了限定执行时间和不占用PHP过多的内存, 不支持备份非常大的数据. 如果您的数据库非常庞大, 建议直接通过命令行模式进行备份.';
$lang['db_filename']			= '文件名';

$lang['db_drop_question']		= '添加 &lsquo;删除 表&rsquo; SQL命令 to ?';
$lang['db_compress_question']	= 'Compression type?';
$lang['db_insert_question']		= '新增 &lsquo;插入&rsquo; 数据 SQL?';

$lang['db_restore_note']		= '恢复选项仅适用于读取未压缩文件. Gzip和Zip压缩文件仅支持备份下载使用.';

$lang['db_gzip']				= 'gzip';
$lang['db_zip']					= 'zip';
$lang['db_backup']				= '备份';
$lang['db_tables']				= '表';
$lang['db_restore']				= '回复';
$lang['db_database']			= '数据库';
$lang['db_drop']				= '删除';
$lang['db_repair']				= '修复';
$lang['db_optimize']			= '优化';
$lang['db_apply']			= '应用';

$lang['db_delete_note']			= '删除已选的备份文件: ';
$lang['db_no_backups']			= '暂无备份文件.';
$lang['db_backup_delete_confirm']	= '确定删除以下备份文件?';
$lang['db_drop_confirm']		= '确定删除以下数据库中的表么?';
$lang['db_drop_attention']		= '<p>删除数据库的表将引起数据丢失.</p><p><strong>这可能会引起您的程序运行失败.</strong></p>';

$lang['db_table_name']			= '表名';
$lang['db_records']				= '记录';
$lang['db_data_size']			= '数据占用空间';
$lang['db_index_size']			= '索引占用空间';
$lang['db_data_free']			= '剩余空间';
$lang['db_engine']				= '引擎';
$lang['db_no_tables']			= '当前数据库中没有表单数据.';

$lang['db_restore_results']		= '恢复数据';
$lang['db_back_to_tools']		= '返回到数据库工具界面';
$lang['db_restore_file']		= '使用文件恢复数据库数据';
$lang['db_restore_attention']	= '<p>从备份文件中恢复数据库将可能引起您之前的所有数据库信息被删除.</p><p><strong>该操作可能引发数据丢失</strong>.</p>';

$lang['db_database_settings']	= '数据库设置';
$lang['db_server_type']			= '服务类型';
$lang['db_hostname']			= '主机名称';
$lang['db_dbname']				= '数据库名称';
$lang['db_advanced_options']	= '扩展选项';
$lang['db_persistant_connect']	= '持久连接';
$lang['db_display_errors']		= '显示数据库错误';
$lang['db_enable_caching']		= '使用查询缓存';
$lang['db_cache_dir']			= '缓存路径';
$lang['db_prefix']				= '前缀';

$lang['db_servers']				= '服务器设置';
$lang['db_driver']				= '驱动';
$lang['db_persistant']			= '持久化';
$lang['db_debug_on']			= '开启Debug';
$lang['db_strict_mode']			= '严格模式';
$lang['db_running_on_1']		= '您当前运行的是';
$lang['db_running_on_2']		= '服务.';
$lang['db_serv_dev']			= '开发';
$lang['db_serv_test']			= '测试';
$lang['db_serv_prod']			= '产品';

$lang['db_successful_save']		= '设置信息保存成功.';
$lang['db_erroneous_save']		= '保存设置出错.';
$lang['db_successful_save_act']	= '数据库设置保存成功';
$lang['db_erroneous_save_act']	= '数据库设置保存失败';

$lang['db_sql_query']			= 'SQL 查询';
$lang['db_total_results']		= '所有结果';
$lang['db_no_rows']				= '该表没有对应数据.';
$lang['db_browse']				= '浏览';
