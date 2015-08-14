<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Bonfire
 *
 * An open source project to allow developers to jumpstart their development of
 * CodeIgniter applications.
 *
 * @package   Bonfire
 * @author    Bonfire Dev Team
 * @copyright Copyright (c) 2011 - 2015, Bonfire Dev Team
 * @license   http://opensource.org/licenses/MIT The MIT License.
 * @link      http://cibonfire.com
 * @since     Version 1.0
 */

/**
 * Application language file (Russian).
 *
 * Localization strings used by Bonfire.
 *
 * @package Bonfire\Application\Language\Russian
 * @author  Translator < https://github.com/modgahead >
 * @author  Translator < https://github.com/cjmaxik >
 * @author  Translator < https://github.com/nkondrashov >
 * @link    http://cibonfire.com/docs/guides
 */


//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------

$lang['bf_site_name']        = 'Название сайта';
$lang['bf_site_email']       = 'Email сайта';
$lang['bf_site_email_help']  = 'Email по умолчанию, с которого будут отсылаться все сгенерированные системой сообщения.';
$lang['bf_site_status']      = 'Статус сайта';
$lang['bf_online']           = 'Включен';
$lang['bf_offline']          = 'Выключен';
$lang['bf_top_number']       = 'Элементов <em>на</em> страницу:';
$lang['bf_top_number_help']  = 'Сколько эл-тов стоит отображать на каждой странице при просмотре отчетов?';
$lang['bf_home']             = 'Главная';
$lang['bf_site_information'] = 'Информация о сайте';
$lang['bf_timezone']         = 'Часовой пояс';
$lang['bf_language']         = 'Язык';
$lang['bf_language_help']    = 'Выберите языки, доступные пользователям.';

//------------------------------------------------------------------------------
// ! AUTH SETTINGS
//------------------------------------------------------------------------------

$lang['bf_security']            = 'Безопасность';
$lang['bf_login_type']          = 'Тип аутентификации';
$lang['bf_login_type_email']    = 'Только по Email';
$lang['bf_login_type_username'] = 'Только по имени пользователя';
$lang['bf_allow_register']      = 'Разрешить регистрацию?';
$lang['bf_login_type_both']     = 'По Email ил по имени пользователя';
$lang['bf_use_usernames']       = 'Пользователь отображается в системе:';
$lang['bf_use_own_name']        = 'Использовать свое имя';
$lang['bf_allow_remember']      = 'Разрешить функцию \'Запомнить меня\'?';
$lang['bf_remember_time']       = 'Запоминать пользователя на';
$lang['bf_week']                = 'Неделю';
$lang['bf_weeks']               = 'Недели';
$lang['bf_days']                = 'Дней';
$lang['bf_username']            = 'Юзернейм';
$lang['bf_password']            = 'Пароль';
$lang['bf_password_confirm']    = 'Пароль (снова)';
$lang['bf_display_name']        = 'Отображаемое имя';

//------------------------------------------------------------------------------
// ! CRUD SETTINGS
//------------------------------------------------------------------------------

$lang['bf_home_page']          = 'Главная страница';
$lang['bf_pages']              = 'Страницы';
$lang['bf_enable_rte']         = 'Включить RTE для страниц?';
$lang['bf_rte_type']           = 'Тип RTE';
$lang['bf_searchable_default'] = 'Доступно для поиска по умолчанию?';
$lang['bf_cacheable_default']  = 'Кешируется по умолчанию?';
$lang['bf_track_hits']         = 'Отслеживать нажатия страниц?';

$lang['bf_action_save']     = 'Сохранить';
$lang['bf_action_delete']   = 'Удалить';
$lang['bf_action_edit']     = 'Редактировать';
$lang['bf_action_undo']     = 'Отменить';
$lang['bf_action_cancel']   = 'Отменить';
$lang['bf_action_download'] = 'Скачать';
$lang['bf_action_preview']  = 'Предпросмотр';
$lang['bf_action_search']   = 'Искать';
$lang['bf_action_purge']    = 'Очистить';
$lang['bf_action_restore']  = 'Восстановить';
$lang['bf_action_show']     = 'Показать';
$lang['bf_action_login']    = 'Войти';
$lang['bf_action_logout']   = 'Выйти';
$lang['bf_actions']         = 'Действия';
$lang['bf_clear']           = 'Очистить';
$lang['bf_action_list']     = 'Список';
$lang['bf_action_create']   = 'Создать';
$lang['bf_action_ban']      = 'Заблокировать';

//------------------------------------------------------------------------------
// ! SETTINGS LIB
//------------------------------------------------------------------------------

$lang['bf_do_check']         = 'Проверять ли обновления?';
$lang['bf_do_check_edge']    = 'Включите, чтобы видеть нестабильные обновления.';

$lang['bf_update_show_edge'] = 'Видеть нестабильные обновления?';
$lang['bf_update_info_edge'] = 'Оставьте выключенной, чтобы проверять только обновления. Включите, чтобы видеть каждый коммит в официальном репозитории.';

$lang['bf_ext_profile_show'] = 'Имеет ли пользовательский аккаунт расширенный профиль?';
$lang['bf_ext_profile_info'] = 'Включите, если хотите иметь дополнительные поля для пользователей (meta-data).';

$lang['bf_yes']  = 'Да';
$lang['bf_no']   = 'Нет';
$lang['bf_none'] = 'Ни одного';
$lang['bf_id']   = 'ID';

$lang['bf_or']    = 'или';
$lang['bf_size']  = 'Размер';
$lang['bf_files'] = 'Файлы';
$lang['bf_file']  = 'Файл';

$lang['bf_with_selected'] = 'С отмеченными';

$lang['bf_env_dev']  = 'Разработка';
$lang['bf_env_test'] = 'Тестирование';
$lang['bf_env_prod'] = 'Продакшн';

$lang['bf_show_profiler']       = 'Показывать профайлер в админке?';
$lang['bf_show_front_profiler'] = 'Показывать профайлер на публичных страницах?';

$lang['bf_cache_not_writable']  = 'Директория для кеша приложения закрыта для записи.';

$lang['bf_password_strength']         = 'Настройки безопасности пароля';
$lang['bf_password_length_help']      = 'Минимальная длина пароля (например 8)';
$lang['bf_password_force_numbers']    = 'Обязан ли пароль содержать цифры?';
$lang['bf_password_force_symbols']    = 'Обязан ли пароль содержать символы?';
$lang['bf_password_force_mixed_case'] = 'Обязан ли пароль содержать символы в разном регистре?';
$lang['bf_password_show_labels']      = 'Отображать ли валидацию пароля?';
$lang['bf_password_iterations_note']  = 'Чем выше значение - тем безопаснее пароль и больше времени требуется на хеширование всех паролей.<br/>Прочтите <a href="http://www.openwall.com/phpass/" target="blank">официальную документацию phpass</a> для изучения вопроса. Если сомневаетесь - ставьте 8.';

//------------------------------------------------------------------------------
// ! USER/PROFILE
//------------------------------------------------------------------------------

$lang['bf_user']          = 'Пользователь';
$lang['bf_users']         = 'Пользователи';
$lang['bf_description']   = 'Описание';
$lang['bf_email']         = 'Email';
$lang['bf_user_settings'] = 'Мой профиль';

//------------------------------------------------------------------------------
// !
//------------------------------------------------------------------------------

$lang['bf_both']                = 'оба';
$lang['bf_go_back']             = 'Назад';
$lang['bf_new']                 = 'Новый';
$lang['bf_required_note']       = 'Обязательные поля отмечены <b>жирным</b> шрифтом.';
$lang['bf_form_label_required'] = '<span class="required">*</span>';

//------------------------------------------------------------------------------
// MY_Model
//------------------------------------------------------------------------------
$lang['bf_model_db_error']     = 'Ошибка БД: %s';
$lang['bf_model_no_data']      = 'Данные не найдены.';
$lang['bf_model_invalid_id']   = 'Модели передан неверный ID.';
$lang['bf_model_no_table']     = 'В модели не определена таблица БД.';
$lang['bf_model_fetch_error']  = 'Недостаточно информации для определения полей.';
$lang['bf_model_count_error']  = 'Недостаточно информации для подсчета результатов.';
$lang['bf_model_unique_error'] = 'Недостаточно информации для проверки на уникальность.';
$lang['bf_model_find_error']   = 'Недостаточно информации для поиска по значению.';

//------------------------------------------------------------------------------
// Contexts
//------------------------------------------------------------------------------
$lang['bf_no_contexts']       = 'Массив контекстов неправильно настроен. Проверьте файл конфигурации приложения.';
$lang['bf_context_content']   = 'Содержание';
$lang['bf_context_reports']   = 'Отчеты';
$lang['bf_context_settings']  = 'Настройки';
$lang['bf_context_developer'] = 'Разработчику';

//------------------------------------------------------------------------------
// Activities
//------------------------------------------------------------------------------
$lang['bf_act_settings_saved']   = 'Настройки приложения сохранены из';
$lang['bf_unauthorized_attempt'] = 'неудачная попытка доступа к странице, которая требует права доступа "%s" из ';

$lang['bf_keyboard_shortcuts']      = 'Доступные горячие клавиши:';
$lang['bf_keyboard_shortcuts_none'] = 'Нет установленных горячих клавиш.';
$lang['bf_keyboard_shortcuts_edit'] = 'Обновить горячие клавиши';

//------------------------------------------------------------------------------
// Common
//------------------------------------------------------------------------------
$lang['bf_question_mark']      = '?';
$lang['bf_language_direction'] = 'ltr';
$lang['log_intro']             = 'Это ваши логи';
$lang['bf_name']               = 'Имя';
$lang['bf_status']             = 'Статус';

//------------------------------------------------------------------------------
// Login
//------------------------------------------------------------------------------
$lang['bf_action_register'] = 'Регистрация';
$lang['bf_forgot_password'] = 'Забыли свой пароль?';
$lang['bf_remember_me']     = 'Запомнить меня';

//------------------------------------------------------------------------------
// Password Help Fields to be used as a warning on register
//------------------------------------------------------------------------------
$lang['bf_password_number_required_help']  = 'Пароль обязан содержать хотя бы 1 цифру.';
$lang['bf_password_caps_required_help']    = 'Пароль обязан содержать хотя бы одну большую букву.';
$lang['bf_password_symbols_required_help'] = 'Пароль обязан содержать хотя бы один символ.';

$lang['bf_password_min_length_help'] = 'Пароль обязан содержать минимум %s символов.';
$lang['bf_password_length']          = 'Длина пароля';

//------------------------------------------------------------------------------
// User Meta examples
//------------------------------------------------------------------------------

$lang['user_meta_street_name'] = 'Улица';
$lang['user_meta_type']        = 'Тип';
$lang['user_meta_country']     = 'Страна';
$lang['user_meta_state']       = 'Штат/Область';

//------------------------------------------------------------------------------
// Activation
//------------------------------------------------------------------------------
$lang['bf_activate_method'] = 'Метод активации';
$lang['bf_activate_none']   = 'Не установлено';
$lang['bf_activate_email']  = 'По Email';
$lang['bf_activate_admin']  = 'Вручную администратором';
$lang['bf_activate']        = 'Активировать';
$lang['bf_activate_resend'] = 'Повторная активация';

$lang['bf_reg_complete_error']   = 'Произошла ошибка при завершении регистрации. Пожалуйста, попробуйте еще раз либо обратитесь к администрации сайта за помощью.';
$lang['bf_reg_activate_email']   = 'Письмо с кодом активации было отправлено на [EMAIL].';
$lang['bf_reg_activate_admin']   = 'Как только администратор сайта подтвердит вашу регистрацию, вы обязательно будете проинформированы ';
$lang['bf_reg_activate_none']    = 'Чтобы пользоваться сайтом, войдите под своей учетной записью.';
$lang['bf_user_not_active']      = 'Аккаунт пользователя неактивен.';
$lang['bf_login_activate_title'] = 'Активировать ли ваш аккаунт?';
$lang['bf_login_activate_email'] = '<b>У вас есть код активации?</b> Введите его на этой странице: [ACCOUNT_ACTIVATE_URL].<br /><br />    <b>Нужен код?</b> Запросите его еще раз на этой странице: [ACTIVATE_RESEND_URL].';

//------------------------------------------------------------------------------
// Migrations lib
//------------------------------------------------------------------------------
$lang['no_migrations_found']          = 'Не найдены файлы миграции';
$lang['multiple_migrations_version']  = 'Версия множественных миграций: %d';
$lang['multiple_migrations_name']     = 'Имя множественных миграций: %s';
$lang['migration_class_doesnt_exist'] = 'Класс миграций не найден: %s';
$lang['wrong_migration_interface']    = 'Неправильный интерфейс миграции: %s';
$lang['invalid_migration_filename']   = 'Неправильное название файла миграций: %s - %s';

//------------------------------------------------------------------------------
// Profiler Template
//------------------------------------------------------------------------------
$lang['bf_profiler_menu_console']    = 'Консоль';
$lang['bf_profiler_menu_time']       = 'Время загрузки';
$lang['bf_profiler_menu_time_ms']    = 'мс';
$lang['bf_profiler_menu_time_s']     = 'с';
$lang['bf_profiler_menu_memory']     = 'Использовано памяти';
$lang['bf_profiler_menu_memory_mb']  = 'МБ';
$lang['bf_profiler_menu_queries']    = 'Запросов';
$lang['bf_profiler_menu_queries_db'] = 'База Данных';
$lang['bf_profiler_menu_vars']       = '<span>переменные</span> &amp; Настройка';
$lang['bf_profiler_menu_files']      = 'Файлы';
$lang['bf_profiler_box_console']     = 'Консоль';
$lang['bf_profiler_box_memory']      = 'Использование памяти';
$lang['bf_profiler_box_benchmarks']  = 'Тесты производительности';
$lang['bf_profiler_box_queries']     = 'Запросы';
$lang['bf_profiler_box_session']     = 'Данные сессии пользователя';
$lang['bf_profiler_box_files']       = 'Файлы';

//------------------------------------------------------------------------------
// Form Validation
//------------------------------------------------------------------------------
$lang['bf_form_unique']                 = 'Значение поля &quot;%s&quot; уже используется.';
$lang['bf_form_alpha_extra']            = 'Поле %s должно содержать буквы, цифры, пробелы, точки, нижние подчеркивания и тире.';
$lang['bf_form_matches_pattern']        = 'Поле %s не соответствует необходимому шаблону.';
$lang['bf_form_valid_password']         = 'Поле %s должно иметь минимум {min_length} символов.';
$lang['bf_form_valid_password_nums']    = 'Поле %s должно содержать минимум одну цифру.';
$lang['bf_form_valid_password_syms']    = 'Поле %s должно содержать минимум 1 знак пунктуации.';
$lang['bf_form_valid_password_mixed_1'] = 'Поле %s должно содержать минимум 1 символ верхнего регистра.';
$lang['bf_form_valid_password_mixed_2'] = 'Поле %s должно содержать минимум 1 символ нижнего регистра.';
$lang['bf_form_allowed_types']          = 'Поле %s должно содержать один из разрешенных элементов для выбора.';
$lang['bf_form_one_of']                 = 'Поле %s должно содержать один из доступных для выбора элементов.';


//--------------------------------------------------------------------
// Menu Strings - feel free to add your own custom modules here
// if you want to localize your menus
//--------------------------------------------------------------------
$lang['bf_menu_activities']     = 'Активности';
$lang['bf_menu_code_builder']   = 'Генератор кода';
$lang['bf_menu_db_tools']       = 'База данных';
$lang['bf_menu_db_maintenance'] = 'Обслуживание';
$lang['bf_menu_db_backup']      = 'Бекапы';
$lang['bf_menu_emailer']        = 'Очердь писем';
$lang['bf_menu_email_settings'] = 'Настройки';
$lang['bf_menu_email_template'] = 'Шаблоны';
$lang['bf_menu_email_queue']    = 'Посмотреть очередь';
$lang['bf_menu_kb_shortcuts']   = 'Горячие клавиши';
$lang['bf_menu_logs']           = 'Логи';
$lang['bf_menu_migrations']     = 'Миграции';
$lang['bf_menu_permissions']    = 'Права';
$lang['bf_menu_queue']          = 'Очередь';
$lang['bf_menu_roles']          = 'Роли';
$lang['bf_menu_settings']       = 'Настройки';
$lang['bf_menu_sysinfo']        = 'Информация о системе';
$lang['bf_menu_template']       = 'Шаблон';
$lang['bf_menu_translate']      = 'Перевод';
$lang['bf_menu_users']          = 'Пользователи';

//------------------------------------------------------------------------------
// Form validation labels (for CI 3.0, should be fixed in 3.0.1)
//------------------------------------------------------------------------------
$lang['form_validation_bf_users'] = 'Пользователи';
