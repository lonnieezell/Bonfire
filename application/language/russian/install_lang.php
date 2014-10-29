<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2014, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Application/Install language file (Russian).
 *
 * Localization strings used by Bonfire.
 *
 * @package Bonfire\Application\Language\Russian
 * @author  Translator < https://github.com/modgahead >
 * @link    http://cibonfire.com/docs/guides
 */

$lang['in_need_db_settings'] = 'Невозможно найти правильные настройки БД. Пожалуйста, проверьте настройки и обновите страницу.';
$lang['in_need_database']	 = 'Видимо, такая БД не существует. Пожалуйста, создайте БД и обновите страницу.';

$lang['in_intro']				  = '<h2>Добро пожаловать в Bonfire</h2><p>Пожалуйста, проверьте системные требования внизу, потом нажмите "Далее" чтобы начать установку.</p>';
$lang['in_not_writeable_heading'] = 'Запись в файлы/директории не разрешена';

$lang['in_php_version']		= 'Версия PHP';
$lang['in_curl_enabled']	= 'Включено ли расширение cURL?';
$lang['in_enabled']			= 'Включено';
$lang['in_disabled']		= 'Выключено';
$lang['in_folders']			= 'Директории с правами на запись';
$lang['in_files']			= 'Файлы с правами на запись';
$lang['in_writeable']		= 'Есть права на запись';
$lang['in_not_writeable']	= 'Нет прав на запись';
$lang['in_bad_permissions']	= 'Пожалуйста, решите следущие проблемы и обновите эту страницу для продолжения.';

$lang['in_writeable_directories_message'] = 'Пожалуйста, убедитесь в том, что указанные директории имеют права на запись и попробуйте еще раз';
$lang['in_writeable_files_message']       = 'Пожалуйста, убедитесь в том, что указанные файлы имеют права на запись и попробуйте еще раз';

$lang['in_db_settings']			= 'Настройки Базы Данных';
$lang['in_db_settings_note']	= '<p>Пожалуйста, заполните информацию о базе данных.</p>';
$lang['in_environment_note']	= '<p class="small">Эти настройки будут сохранены и в основной файл <b>config/database.php</b> и в общее окружение (напр. <b>config/development/database.php)</b>. </p>';
$lang['in_db_not_available']	= 'База данных не найдена.';
$lang['in_db_connect']			= 'Настройки БД в порядке!';
$lang['in_db_no_connect']       = 'Неправильные настройки БД.';
$lang['in_db_setup_error']      = 'Обнаружена ошибка при настройке вашей БД';
$lang['in_db_settings_error']   = 'Обнаружена ошибка при записи настроек в БД';
$lang['in_db_account_error']    = 'Обнаружена ошибка при создании вашего аккаунта в БД';
$lang['in_settings_save_error'] = 'Обнаружена ошибка сохранения настроек. Пожалуйста, проверьте чтобы ваша БД и %s/файлы конфигурации БД были доступны для записи.';
$lang['in_db_no_session']		= 'Невозможно получить информацию о БД из сессии.';
$lang['in_user_no_session']		= 'Невозможно получить информацию о вашем аккаунте из сессии.';
$lang['in_db_config_error']		= 'Обнаружена ошибка записи конфигурации БД в {file}.';

$lang['in_environment']		 = 'Окружение';
$lang['in_environment_dev']	 = 'Разработка';
$lang['in_environment_test'] = 'Тестирование';
$lang['in_environment_prod'] = 'Продакшн';
$lang['in_host']			 = 'Хост';
$lang['in_database']		 = 'База Данных';
$lang['in_prefix']			 = 'Префикс';
$lang['in_db_driver']		 = 'Драйвер';
$lang['in_port']			 = 'Порт';

$lang['in_account_heading']	= '<h2>Аккаунт администратора</h2><p>Пожалуйста, введите следующую информацию.</p>';
$lang['in_site_title']		= 'Название сайта';
$lang['in_username']		= 'Юзернейм админа';
$lang['in_password']		= 'Пароль';
$lang['in_password_note']	= 'Минимальная длина: 8 символов.';
$lang['in_password_again']	= 'Пароль (еще раз)';
$lang['in_email']			= 'Ваш Email';
$lang['in_email_note']		= 'Пожалуйста, проверьте ваш Email дважды прежде чем продолжить!';
$lang['in_install_button']	= 'Установить Bonfire';

$lang['in_curl_disabled']	= '<p class="error">Расширение cURL <strong>НЕ</strong> доступно на данный момент в PHP. Bonfire не сможет проверять свои обновления до тех пор, пока cURL не будет установлен.</p>';

$lang['in_success_notification'] = 'Все прошло отлично! Удачного кодинга!';
$lang['in_success_rebase_msg']	 = 'Пожалуйста, установите в .htaccess настройку RewriteBase';
$lang['in_success_msg']			 = 'Пожалуйста, удалите или переименуйте директорию install и возвращайтесь ';

$lang['in_installed']  = 'Bonfire уже установлен. Пожалуйста, удалите либо переименуйте директорию install';
$lang['in_rename_msg'] = 'Чтобы вас не утруждать столь неинтересным занятием, система может переименовать директорию сама.';
$lang['in_continue']   = 'Продолжить';
$lang['in_click']	   = 'Нажмите тут';

$lang['in_requirements']	 = 'Системные требования';
$lang['in_account']			 = 'Аккаунт';
$lang['in_complete']		 = 'Установка завершена';
$lang['in_complete_heading'] = 'Настало время высвободить свои потрясающие кодинг-скилы, камрад!';
$lang['in_complete_intro']	 = 'Bonfire установлен, создан аккаунт администратора для вас. <br/><br/>Создан файл <b>installed.txt</b> в директории config. Оставьте его там, дабы установщик вас больше не беспокоил.';
$lang['in_complete_next']	 = 'Что дальше-то?';
$lang['in_complete_visit']	 = 'Можете посмотреть на';
$lang['in_admin_area']		 = 'Админ-панель';
$lang['in_site_front']		 = 'Паблик сайта';
$lang['in_read']			 = 'Можно также проштудировать';
$lang['in_bf_docs']			 = 'документацию Bonfire';
$lang['in_ci_docs']			 = 'документацию CodeIgniter';
$lang['in_happy_coding']	 = 'Команда Bonfire желает вам веселого и успешного кодинга!';

/* End of file ./application/language/russian/install_lang.php */