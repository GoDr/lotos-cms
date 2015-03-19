<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

$mainframe = MainFrame::getInstance();

if(!$acl->acl_check('administration', 'config', 'users', $my->usertype)){
	mosRedirect('index2.php?', _NOT_AUTH);
}

require_once ($mainframe->getPath('admin_html'));

$task = LSef::getTask();

switch($task){

	case 'apply':
	case 'save':
		js_menu_cache_clear();
		saveconfig($task);
		break;

	case 'cancel':
		mosRedirect('index2.php');
		break;

	default:
		showconfig($option);
		break;
}

/**
 * Show the configuration edit form
 * @param string The URL option
 */
function showconfig($option){
	$database = database::getInstance();

	$row = new JConfig();
	$row->bindGlobals();

	// compile list of the languages
	$langs = array();
	$lists = array();

	// PRE-PROCESS SOME LISTS

	// -- Языки --
	if($handle = opendir(_LPATH_ROOT . '/language/')){
		while(false !== ($file = readdir($handle))){
			if(!strcasecmp(substr($file, -4), ".xml") && $file != "." && $file != ".."){
				$langs[] = LHtml::makeOption(substr($file, 0, -4));
			}
		}
	}

	// сортировка списка языков
	sort($langs);
	reset($langs);

	// -- Редакторы --
	$query = "SELECT element AS value, name AS text"
		. "\n FROM #__plugins"
		. "\n WHERE folder = 'editors'"
		. "\n AND published = 1"
		. "\n ORDER BY ordering, name";
	$database->setQuery($query);
	$edits = $database->loadObjectList();

	if(!$row->config_editor){
		$row->config_editor = '';
	}
	// build the html select list
	$lists['editor'] = LHtml::selectList($edits, 'config_editor', 'class="inputbox" size="1"', 'value', 'text', $row->config_editor);


	// НАСТРОЙКИ САЙТА
	$lists['offline'] = LHtml::yesnoRadioList('config_offline', 'class="inputbox"', $row->config_offline);


	$listLimit = array(
		LHtml::makeOption(5, 5),
		LHtml::makeOption(10, 10),
		LHtml::makeOption(15, 15),
		LHtml::makeOption(20, 20),
		LHtml::makeOption(25, 25),
		LHtml::makeOption(30, 30),
		LHtml::makeOption(50, 50),
		LHtml::makeOption(100, 100),
		LHtml::makeOption(150, 150),
	);

	$lists['list_limit'] = LHtml::selectList($listLimit, 'config_list_limit', 'class="inputbox" size="1"', 'value', 'text', ($row->config_list_limit ? $row->config_list_limit : 50));

	$lists['frontend_login'] = LHtml::yesnoRadioList('config_frontend_login', 'class="inputbox"', $row->config_frontend_login);

	// отключение ведения сессий подсчета числа пользователей на сайте
	$lists['session_front'] = LHtml::yesnoRadioList('config_no_session_front', 'class="inputbox"', $row->config_no_session_front);
	// отключение syndicate
	$lists['syndicate_off'] = LHtml::yesnoRadioList('config_syndicate_off', 'class="inputbox"', $row->config_syndicate_off);
	// отключение тега Generator
	$lists['generator_off'] = LHtml::yesnoRadioList('config_generator_off', 'class="inputbox"', $row->config_generator_off);
	// отключение плагинов группы system
	$lists['mmb_system_off'] = LHtml::yesnoRadioList('config_mmb_system_off', 'class="inputbox"', $row->config_mmb_system_off);
	// получаем список шаблонов. Код получен из модуля выбора шаблона
	$titlelength = 20;
	$template_path = _LPATH_ROOT . DS . 'templates';
	$templatefolder = @dir($template_path);
	$darray = array();
	$darray[] = LHtml::makeOption('...', _O_OTHER); // параметр по умолчанию - позволяет использовать стандартный способ определения шаблона
	if($templatefolder){
		while($templatefile = $templatefolder->read()){
			if($templatefile != 'system' && $templatefile != "." && $templatefile != ".." && $templatefile != ".svn" && $templatefile != "css" && is_dir("$template_path/$templatefile")){
				if(strlen($templatefile) > $titlelength){
					$templatename = substr($templatefile, 0, $titlelength - 3);
					$templatename .= "...";
				} else{
					$templatename = $templatefile;
				}
				$darray[] = LHtml::makeOption($templatefile, $templatename);
			}
		}
		$templatefolder->close();
	}
	sort($darray);
	$lists['one_template'] = LHtml::selectList($darray, 'config_one_template', "class=\"inputbox\" ", 'value', 'text', $row->config_one_template);
	// время генерации страницы
	$lists['config_time_generate'] = LHtml::yesnoRadioList('config_time_generate', 'class="inputbox"', $row->config_time_generate);
	//индексация страницы печати
	$lists['index_print'] = LHtml::yesnoRadioList('config_index_print', 'class="inputbox"', $row->config_index_print);
	// расширенные теги индексации
	$lists['index_tag'] = LHtml::yesnoRadioList('config_index_tag', 'class="inputbox"', $row->config_index_tag);
	// ежесуточная оптимизация таблиц бд
	$lists['optimizetables'] = LHtml::yesnoRadioList('config_optimizetables', 'class="inputbox"', $row->config_optimizetables);
	// отключение плагинов группы content
	$lists['mmb_content_off'] = LHtml::yesnoRadioList('config_mmb_content_off', 'class="inputbox"', $row->config_mmb_content_off);
	// кеширование меню панели управления
	$lists['adm_menu_cache'] = LHtml::yesnoRadioList('config_adm_menu_cache', 'class="inputbox"', $row->config_adm_menu_cache);
	// управление captcha
	$lists['captcha'] = LHtml::yesnoRadioList('config_captcha', 'class="inputbox"', $row->config_captcha);
	// управление captcha
	$lists['com_frontpage_clear'] = LHtml::yesnoRadioList('config_com_frontpage_clear', 'class="inputbox"', $row->config_com_frontpage_clear);
	// корень файлового менеджера

    $row->config_dir_edit = $row->config_dir_edit;

	// автоматическая установка чекбокса "Публиковать на главной"
	$lists['auto_frontpage'] = LHtml::yesnoRadioList('config_auto_frontpage', 'class="inputbox"', $row->config_auto_frontpage);
	// уникальные идентификаторы новостей
	$lists['config_uid_news'] = LHtml::yesnoRadioList('config_uid_news', 'class="inputbox"', $row->config_uid_news);
	// подсчет прочтений содержимого
	$lists['config_content_hits'] = LHtml::yesnoRadioList('config_content_hits', 'class="inputbox"', $row->config_content_hits);
	// формат времени
	$date_help = array(
		LHtml::makeOption('%d.%m.%Y ' . _COM_CONFIG_YEAR . ' %H:%M', strftime('%d.%m.%Y ' . _COM_CONFIG_YEAR . ' %H:%M')),
		LHtml::makeOption('%d:%m:%Y ' . _COM_CONFIG_YEAR . ' %H:%M', strftime('%d:%m:%Y ' . _COM_CONFIG_YEAR . ' %H:%M')),
		LHtml::makeOption('%d-%m-%Y ' . _COM_CONFIG_YEAR . ' %H-%M', strftime('%d-%m-%Y ' . _COM_CONFIG_YEAR . ' %H-%M')),
		LHtml::makeOption('%d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M', strftime('%d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M')),
		LHtml::makeOption('%d/%m/%Y %H/%M', strftime('%d/%m/%Y %H/%M')),
		LHtml::makeOption('%d/%m/%Y', strftime('%d/%m/%Y')),
		LHtml::makeOption('%d:%m:%Y', strftime('%d:%m:%Y')),
		LHtml::makeOption('%d.%m.%Y', strftime('%d.%m.%Y')),
		LHtml::makeOption('%d/%m/%Y ' . _COM_CONFIG_YEAR, strftime('%d/%m/%Y ' . _COM_CONFIG_YEAR)),
		LHtml::makeOption('%d:%m:%Y ' . _COM_CONFIG_YEAR, strftime('%d:%m:%Y ' . _COM_CONFIG_YEAR)),
		LHtml::makeOption('%d.%m.%Y ' . _COM_CONFIG_YEAR, strftime('%d.%m.%Y ' . _COM_CONFIG_YEAR)),
		LHtml::makeOption('%H/%M', strftime('%H/%M')),
		LHtml::makeOption('%H:%M', strftime('%H:%M')),
		LHtml::makeOption('%H ' . _COM_CONFIG_HOURS . '%M ' . _COM_CONFIG_MONTH, strftime('%H ' . _COM_CONFIG_HOURS . ' %M ' . _COM_CONFIG_MONTH)),
		LHtml::makeOption('%A %d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M', Jstring::to_utf8(strftime('%A %d/%m/%Y ' . _COM_CONFIG_YEAR . ' %H/%M'))),
		LHtml::makeOption('%d %B %Y', Jstring::to_utf8(strftime('%d %B %Y')))
	);
	$lists['form_date_help'] = LHtml::selectList($date_help, 'config_form_date_h', 'class="inputbox" size="1" onchange="adminForm.config_form_date.value=this.value;"', 'value', 'text', $row->config_form_date);
	// полный формат даты и времени
	$lists['form_date_full_help'] = LHtml::selectList($date_help, 'config_form_date_full_h', 'class="inputbox" size="1" onchange="adminForm.config_form_date_full.value=this.value;"', 'value', 'text', $row->config_form_date_full);
	// поддержка работы на младших версиях MySQL
	$lists['config_pathway_clean'] = LHtml::yesnoRadioList('config_pathway_clean', 'class="inputbox"', $row->config_pathway_clean);
	// отключение удаления сессий в панели управления
	$lists['config_admin_autologout'] = LHtml::yesnoRadioList('config_admin_autologout', 'class="inputbox"', $row->config_admin_autologout);
	// отключение кнопки "Помощь"
	$lists['config_disable_button_help'] = LHtml::yesnoRadioList('config_disable_button_help', 'class="inputbox"', $row->config_disable_button_help);
	// отключение блокировок объектов
	$lists['config_disable_checked_out'] = LHtml::yesnoRadioList('config_disable_checked_out', 'class="inputbox"', $row->config_disable_checked_out);
	// отключение favicon
	$lists['config_disable_favicon'] = LHtml::yesnoRadioList('config_disable_favicon', 'class="inputbox"', $row->config_disable_favicon);
	// использование расширенного отладчика на фронте
	$lists['config_front_debug'] = LHtml::yesnoRadioList('config_front_debug', 'class="inputbox"', $row->config_front_debug);
	// использование плагинов группы mainbody
	$lists['config_mmb_mainbody_off'] = LHtml::yesnoRadioList('config_mmb_mainbody_off', 'class="inputbox"', $row->config_mmb_mainbody_off);
	// автоматическая авторизация после подтверждения регистрации
	$lists['config_auto_activ_login'] = LHtml::yesnoRadioList('config_auto_activ_login', 'class="inputbox"', $row->config_auto_activ_login);
	// отключение вкладки 'Изображения'
	$lists['config_disable_image_tab'] = LHtml::yesnoRadioList('config_disable_image_tab', 'class="inputbox"', $row->config_disable_image_tab);
	// отключить проверки публикаций по датам
	$lists['config_disable_date_state'] = LHtml::yesnoRadioList('config_disable_date_state', 'class="inputbox"', $row->config_disable_date_state);
	// отключить проверку доступа к содержимому
	$lists['config_disable_access_control'] = LHtml::yesnoRadioList('config_disable_access_control', 'class="inputbox"', $row->config_disable_access_control);
	// оптимизация функции кеширования
	$lists['config_cache_opt'] = LHtml::yesnoRadioList('config_cache_opt', 'class="inputbox"', $row->config_cache_opt);
	// включение сжатия css и js файлов
	$lists['config_gz_js_css'] = LHtml::yesnoRadioList('config_gz_js_css', 'class="inputbox"', $row->config_gz_js_css);
	// captcha для регистрации
	$lists['config_captcha_reg'] = LHtml::yesnoRadioList('config_captcha_reg', 'class="inputbox"', $row->config_captcha_reg);
	// captcha для формы контактов
	$lists['config_captcha_cont'] = LHtml::yesnoRadioList('config_captcha_cont', 'class="inputbox"', $row->config_captcha_cont);
	// визуальный редактор для html и css - codepress
	$lists['config_codepress'] = LHtml::yesnoRadioList('config_codepress', 'class="inputbox"', $row->config_codepress);

	// DEBUG - ОТЛАДКА
	$lists['debug'] = LHtml::yesnoRadioList('config_debug', 'class="inputbox"', $row->config_debug);

	// НАСТРОЙКИ СЕРВЕРА
	$lists['gzip'] = LHtml::yesnoRadioList('config_gzip', 'class="inputbox"', $row->config_gzip);

	$session = array(
		LHtml::makeOption(0, _SECURITY_LEVEL3),
		LHtml::makeOption(1, _SECURITY_LEVEL2),
		LHtml::makeOption(2, _SECURITY_LEVEL1)
	);
	$lists['session_type'] = LHtml::selectList($session, 'config_session_type', 'class="inputbox" size="1"', 'value', 'text', $row->config_session_type);

	$errors = array(
		LHtml::makeOption(E_ALL, _COM_CONFIG_ERROR_ALL),
		LHtml::makeOption(-1, _COM_CONFIG_ERROR_SYSTEM),
		LHtml::makeOption(0, _COM_CONFIG_ERROR_HIDE),
		LHtml::makeOption(E_ERROR | E_WARNING | E_PARSE, _COM_CONFIG_ERROR_TINY),
		LHtml::makeOption(E_ALL & ~E_NOTICE, _COM_CONFIG_ERROR_PARANOIDAL),
	);

	$lists['error_reporting'] = LHtml::selectList($errors, 'config_error_reporting', 'class="inputbox" size="1"', 'value', 'text', $row->config_error_reporting);

	$lists['admin_expired'] = LHtml::yesnoRadioList('config_admin_expired', 'class="inputbox"', $row->config_admin_expired);

	// НАСТРОЙКИ ЛОКАЛИ СТРАНЫ
	$lists['lang'] = LHtml::selectList($langs, 'config_lang', 'class="inputbox" size="1"', 'value', 'text', $row->config_lang);

	$timeoffset = array(
		LHtml::makeOption(-12, _TIME_OFFSET_M_12),
		LHtml::makeOption(-11, _TIME_OFFSET_M_11),
		LHtml::makeOption(-10, _TIME_OFFSET_M_10),
		LHtml::makeOption(-9.5, _TIME_OFFSET_M_9_5),
		LHtml::makeOption(-9, _TIME_OFFSET_M_9),
		LHtml::makeOption(-8, _TIME_OFFSET_M_8),
		LHtml::makeOption(-7, _TIME_OFFSET_M_7),
		LHtml::makeOption(-6, _TIME_OFFSET_M_6),
		LHtml::makeOption(-5, _TIME_OFFSET_M_5),
		LHtml::makeOption(-4, _TIME_OFFSET_M_4),
		LHtml::makeOption(-3.5, _TIME_OFFSET_M_3_5),
		LHtml::makeOption(-3, _TIME_OFFSET_M_3),
		LHtml::makeOption(-2, _TIME_OFFSET_M_2),
		LHtml::makeOption(-1, _TIME_OFFSET_M_1),
		LHtml::makeOption(0, _TIME_OFFSET_M_0),
		LHtml::makeOption(1, _TIME_OFFSET_P_1),
		LHtml::makeOption(2, _TIME_OFFSET_P_2),
		LHtml::makeOption(3, _TIME_OFFSET_P_3),
		LHtml::makeOption(3.5, _TIME_OFFSET_P_3_5),
		LHtml::makeOption(4, _TIME_OFFSET_P_4),
		LHtml::makeOption(4.5, _TIME_OFFSET_P_4_5),
		LHtml::makeOption(5, _TIME_OFFSET_P_5),
		LHtml::makeOption(5.5, _TIME_OFFSET_P_5_5),
		LHtml::makeOption(5.75, _TIME_OFFSET_P_5_75),
		LHtml::makeOption(6, _TIME_OFFSET_P_6),
		LHtml::makeOption(6.30, _TIME_OFFSET_P_6_5),
		LHtml::makeOption(7, _TIME_OFFSET_P_7),
		LHtml::makeOption(8, _TIME_OFFSET_P_8),
		LHtml::makeOption(8.75, _TIME_OFFSET_P_8_75),
		LHtml::makeOption(9, _TIME_OFFSET_P_9),
		LHtml::makeOption(9.5, _TIME_OFFSET_P_9_5),
		LHtml::makeOption(10, _TIME_OFFSET_P_10),
		LHtml::makeOption(10.5, _TIME_OFFSET_P_10_5),
		LHtml::makeOption(11, _TIME_OFFSET_P_11),
		LHtml::makeOption(11.30, _TIME_OFFSET_P_11_5),
		LHtml::makeOption(12, _TIME_OFFSET_P_12),
		LHtml::makeOption(12.75, _TIME_OFFSET_P_12_75),
		LHtml::makeOption(13, _TIME_OFFSET_P_13),
		LHtml::makeOption(14, _TIME_OFFSET_P_14),);

	$lists['offset'] = LHtml::selectList($timeoffset, 'config_offset_user', 'class="inputbox" size="1"', 'value', 'text', $row->config_offset_user);

	$feed_timeoffset = array(
		LHtml::makeOption('-12:00', _TIME_OFFSET_M_12),
		LHtml::makeOption('-11:00', _TIME_OFFSET_M_11),
		LHtml::makeOption('-10:00', _TIME_OFFSET_M_10),
		LHtml::makeOption('-09:30', _TIME_OFFSET_M_9_5),
		LHtml::makeOption('-09:00', _TIME_OFFSET_M_9),
		LHtml::makeOption('-08:00', _TIME_OFFSET_M_8),
		LHtml::makeOption('-07:00', _TIME_OFFSET_M_7),
		LHtml::makeOption('-06:00', _TIME_OFFSET_M_6),
		LHtml::makeOption('-05:00', _TIME_OFFSET_M_5),
		LHtml::makeOption('-04:00', _TIME_OFFSET_M_4),
		LHtml::makeOption('-03:30', _TIME_OFFSET_M_3_5),
		LHtml::makeOption('-03:00', _TIME_OFFSET_M_3),
		LHtml::makeOption('-02:00', _TIME_OFFSET_M_2),
		LHtml::makeOption('-01:00', _TIME_OFFSET_M_1),
		LHtml::makeOption('00:00', _TIME_OFFSET_M_0),
		LHtml::makeOption('+01:00', _TIME_OFFSET_P_1),
		LHtml::makeOption('+02:00', _TIME_OFFSET_P_2),
		LHtml::makeOption('+03:00', _TIME_OFFSET_P_3),
		LHtml::makeOption('+03:30', _TIME_OFFSET_P_3_5),
		LHtml::makeOption('+04:00', _TIME_OFFSET_P_4),
		LHtml::makeOption('+04:30', _TIME_OFFSET_P_4_5),
		LHtml::makeOption('+05:00', _TIME_OFFSET_P_5),
		LHtml::makeOption('+05:30', _TIME_OFFSET_P_5_5),
		LHtml::makeOption('+05:45', _TIME_OFFSET_P_5_75),
		LHtml::makeOption('+06:00', _TIME_OFFSET_P_6),
		LHtml::makeOption('+06:30', _TIME_OFFSET_P_6_5),
		LHtml::makeOption('+07:00', _TIME_OFFSET_P_7),
		LHtml::makeOption('+08:00', _TIME_OFFSET_P_8),
		LHtml::makeOption('+08:45', _TIME_OFFSET_P_8_75),
		LHtml::makeOption('+09:00', _TIME_OFFSET_P_9),
		LHtml::makeOption('+09:30', _TIME_OFFSET_P_9_5),
		LHtml::makeOption('+10:00', _TIME_OFFSET_P_10),
		LHtml::makeOption('+10:30', _TIME_OFFSET_P_10_5),
		LHtml::makeOption('+11:00', _TIME_OFFSET_P_11),
		LHtml::makeOption('+11:30', _TIME_OFFSET_P_11_5),
		LHtml::makeOption('+12:00', _TIME_OFFSET_P_12),
		LHtml::makeOption('+12:45', _TIME_OFFSET_P_12_75),
		LHtml::makeOption('+13:00', _TIME_OFFSET_P_13),
		LHtml::makeOption('+14:00', _TIME_OFFSET_P_14)
	);
	$lists['feed_timeoffset'] = LHtml::selectList($feed_timeoffset, 'config_feed_timeoffset', 'class="inputbox" size="1"', 'value', 'text', $row->config_feed_timeoffset);

// НАСТРОЙКИ ПОЧТЫ
	$mailer = array(
		LHtml::makeOption('mail', _PHP_MAIL_FUNCTION),
		LHtml::makeOption('sendmail', 'Sendmail'),
		LHtml::makeOption('smtp', _SMTP_SERVER)
	);
	$lists['mailer'] = LHtml::selectList($mailer, 'config_mailer', 'class="inputbox" size="1"', 'value', 'text', $row->config_mailer);
	$lists['smtpauth'] = LHtml::yesnoRadioList('config_smtpauth', 'class="inputbox"', $row->config_smtpauth);


	// НАСТРОЙКИ КЕША
	$lists['caching'] = LHtml::yesnoRadioList('config_caching', 'class="inputbox"', $row->config_caching);

// НАСТРОЙКИ ПОЛЬЗОВАТЕЛЕЙ

	$lists['useractivation'] = LHtml::yesnoRadioList('config_useractivation', 'class="inputbox"', $row->config_useractivation);
	$lists['uniquemail'] = LHtml::yesnoRadioList('config_uniquemail', 'class="inputbox"', $row->config_uniquemail);
	$lists['frontend_userparams'] = LHtml::yesnoRadioList('config_frontend_userparams', 'class="inputbox"', $row->config_frontend_userparams);
	$lists['allowUserRegistration'] = LHtml::yesnoRadioList('config_allowUserRegistration', 'class="inputbox"', $row->config_allowUserRegistration);

// НАСТРОЙКИ META-ДАННЫХ
	$lists['MetaAuthor'] = LHtml::yesnoRadioList('config_MetaAuthor', 'class="inputbox"', $row->config_MetaAuthor);
	$lists['MetaTitle'] = LHtml::yesnoRadioList('config_MetaTitle', 'class="inputbox"', $row->config_MetaTitle);

// НАСТРОЙКИ СТАТИСТИКИ
	$lists['log_searches'] = LHtml::yesnoRadioList('config_enable_log_searches', 'class="inputbox"', $row->config_enable_log_searches);
	$lists['enable_stats'] = LHtml::yesnoRadioList('config_enable_stats', 'class="inputbox"', $row->config_enable_stats);
	$lists['log_items'] = LHtml::yesnoRadioList('config_enable_log_items', 'class="inputbox"', $row->config_enable_log_items);

// НАСТРОЙКИ SEO
	$lists['sef'] = LHtml::yesnoRadioList('config_sef', 'class="inputbox" onclick="javascript: if (document.adminForm.config_sef[1].checked) { alert(\'' . _C_CONFIG_HTACCESS_RENAME . '\') }"', $row->config_sef);
	$lists['pagetitles'] = LHtml::yesnoRadioList('config_pagetitles', 'class="inputbox"', $row->config_pagetitles);

	$pagetitles_first = array(
		LHtml::makeOption(0, _COM_CONFIG_SEO_TYPE_1),
		LHtml::makeOption(1, _COM_CONFIG_SEO_TYPE_2),
		LHtml::makeOption(2, _COM_CONFIG_SEO_TYPE_3),
		LHtml::makeOption(3, _COM_CONFIG_SEO_TYPE_4),
	);
	$lists['pagetitles_first'] = LHtml::selectList($pagetitles_first, 'config_pagetitles_first', 'class="inputbox" size="1"', 'value', 'text', $row->config_pagetitles_first);

// НАСТРОЙКИ СОДЕРЖИМОГО
	$author_name_type = array(
		LHtml::makeOption(1, _COM_CONFIG_CC_NAME_TEXT),
		LHtml::makeOption(2, _COM_CONFIG_CC_LOGIN_TEXT),
		LHtml::makeOption(3, _COM_CONFIG_CC_NAME_LINK),
		LHtml::makeOption(4, _COM_CONFIG_CC_LIGIN_LINK),
	);
	$lists['authorName'] = LHtml::selectList($author_name_type, 'config_author_name', 'class="inputbox" size="1"', 'value', 'text', $row->config_author_name);

	$lists['link_titles'] = LHtml::yesnoRadioList('config_link_titles', 'class="inputbox"', $row->config_link_titles);
	$lists['readmore'] = LHtml::yesnoRadioList('config_readmore', 'class="inputbox"', $row->config_readmore);
	$lists['vote'] = LHtml::yesnoRadioList('config_vote', 'class="inputbox"', $row->config_vote);
	$lists['showAuthor'] = LHtml::yesnoRadioList('config_showAuthor', 'class="inputbox"', $row->config_showAuthor);
	$lists['showCreateDate'] = LHtml::yesnoRadioList('config_showCreateDate', 'class="inputbox"', $row->config_showCreateDate);
	$lists['showModifyDate'] = LHtml::yesnoRadioList('config_showModifyDate', 'class="inputbox"', $row->config_showModifyDate);
	$lists['hits'] = LHtml::yesnoRadioList('config_hits', 'class="inputbox"', $row->config_hits);
	$lists['tags'] = LHtml::yesnoRadioList('config_tags', 'class="inputbox"', $row->config_tags);
	$lists['back_button'] = LHtml::yesnoRadioList('config_back_button', 'class="inputbox"', $row->config_back_button);
	$lists['item_navigation'] = LHtml::yesnoRadioList('config_item_navigation', 'class="inputbox"', $row->config_item_navigation);
	$lists['multipage_toc'] = LHtml::yesnoRadioList('config_multipage_toc', 'class="inputbox"', $row->config_multipage_toc);
	$lists['showPrint'] = LHtml::yesnoRadioList('config_showPrint', 'class="inputbox"', $row->config_showPrint);
	$lists['showEmail'] = LHtml::yesnoRadioList('config_showEmail', 'class="inputbox"', $row->config_showEmail);
	$lists['icons'] = LHtml::yesnoRadioList('config_icons', 'class="inputbox"', $row->config_icons);
	$lists['mtage_base'] = LHtml::yesnoRadioList('config_mtage_base', 'class="inputbox"', $row->config_mtage_base);
	$lists['config_custom_print'] = LHtml::yesnoRadioList('config_custom_print', 'class="inputbox"', $row->config_custom_print);
	$global_templates = array(
		LHtml::makeOption(0, _GLOBAL_TEMPLATES_SYSTEMDIR),
		LHtml::makeOption(1, _GLOBAL_TEMPLATES_CURTEMPLATE),
	);
	$lists['global_templates'] = LHtml::selectList($global_templates, 'config_global_templates', 'class="inputbox" size="1"', 'value', 'text', $row->config_global_templates);

	$lists['tpreview'] = LHtml::yesnoRadioList('config_disable_tpreview', 'class="inputbox"', $row->config_disable_tpreview);

    $lists['mainbody'] = LHtml::yesnoRadioList('config_mainbody', 'class="inputbox"', $row->config_mainbody);

	$locales = array(
		LHtml::makeOption('ru_RU.utf8', 'ru_RU.utf8'),
		LHtml::makeOption('russian', 'russian (windows)'),
		LHtml::makeOption('english', 'english (for windows)'),
		LHtml::makeOption('az_AZ.utf8', 'az_AZ.utf8'),
		LHtml::makeOption('ar_EG.utf8', 'ar_EG.utf8'),
		LHtml::makeOption('ar_LB.utf8', 'ar_LB.utf8'),
		LHtml::makeOption('eu_ES.utf8', 'eu_ES.utf8'),
		LHtml::makeOption('bg_BG.utf8', 'bg_BG.utf8'),
		LHtml::makeOption('ca_ES.utf8', 'ca_ES.utf8'),
		LHtml::makeOption('zh_CN.utf8', 'zh_CN.utf8'),
		LHtml::makeOption('zh_TW.utf8', 'zh_TW.utf8'),
		LHtml::makeOption('hr_HR.utf8', 'hr_HR.utf8'),
		LHtml::makeOption('cs_CZ.utf8', 'cs_CZ.utf8'),
		LHtml::makeOption('da_DK.utf8', 'da_DK.utf8'),
		LHtml::makeOption('nl_NL.utf8', 'nl_NL.utf8'),
		LHtml::makeOption('et_EE.utf8', 'et_EE.utf8'),
		LHtml::makeOption('en_GB.utf8', 'en_GB.utf8'),
		LHtml::makeOption('en_US.utf8', 'en_US.utf8'),
		LHtml::makeOption('en_AU.utf8', 'en_AU.utf8'),
		LHtml::makeOption('en_IE.utf8', 'en_IE.utf8'),
		LHtml::makeOption('fa_IR.utf8', 'fa_IR.utf8'),
		LHtml::makeOption('fi_FI.utf8', 'fi_FI.utf8'),
		LHtml::makeOption('fr_FR.utf8', 'fr_FR.utf8'),
		LHtml::makeOption('gl_ES.utf8', 'gl_ES.utf8'),
		LHtml::makeOption('de_DE.utf8', 'de_DE.utf8'),
		LHtml::makeOption('el_GR.utf8', 'el_GR.utf8'),
		LHtml::makeOption('he_IL.utf8', 'he_IL.utf8'),
		LHtml::makeOption('hu_HU.utf8', 'hu_HU.utf8'),
		LHtml::makeOption('is_IS.utf8', 'is_IS.utf8'),
		LHtml::makeOption('ga_IE.utf8', 'ga_IE.utf8'),
		LHtml::makeOption('it_IT.utf8', 'it_IT.utf8'),
		LHtml::makeOption('ja_JP.utf8', 'ja_JP.utf8'),
		LHtml::makeOption('ko_KR.utf8', 'ko_KR.utf8'),
		LHtml::makeOption('lv_LV.utf8', 'lv_LV.utf8'),
		LHtml::makeOption('lt_LT.utf8', 'lt_LT.utf8'),
		LHtml::makeOption('mk_MK.utf8', 'mk_MK.utf8'),
		LHtml::makeOption('ms_MY.utf8', 'ms_MY.utf8'),
		LHtml::makeOption('no_NO.utf8', 'no_NO.utf8'),
		LHtml::makeOption('nn_NO.utf8', 'nn_NO.utf8'),
		LHtml::makeOption('pl_PL.utf8', 'pl_PL.utf8'),
		LHtml::makeOption('pt_PT.utf8', 'pt_PT.utf8'),
		LHtml::makeOption('pt_BR.utf8', 'pt_BR.utf8'),
		LHtml::makeOption('ro_RO.utf8', 'ro_RO.utf8'),
		LHtml::makeOption('sk_SK.utf8', 'sk_SK.utf8'),
		LHtml::makeOption('sl_SI.utf8', 'sl_SI.utf8'),
		LHtml::makeOption('sr_CS.utf8', 'sr_CS.utf8'),
		LHtml::makeOption('rs_SR.utf8', 'rs_SR.utf8'),
		LHtml::makeOption('es_ES.utf8', 'es_ES.utf8'),
		LHtml::makeOption('es_MX.utf8', 'es_MX.utf8'),
		LHtml::makeOption('sv_SE.utf8', 'sv_SE.utf8'),
		LHtml::makeOption('sv_FI.utf8', 'sv_FI.utf8'),
		LHtml::makeOption('ta_IN.utf8', 'ta_IN.utf8'),
		LHtml::makeOption('tr_TR.utf8', 'tr_TR.utf8'),
		LHtml::makeOption('uk_UA.utf8', 'uk_UA.utf8'),
		LHtml::makeOption('vi_VN.utf8', 'vi_VN.utf8'),
		LHtml::makeOption('wa_BE.utf8', 'wa_BE.utf8')
	);
	$lists['locale'] = LHtml::selectList($locales, 'config_locale', 'class="selectbox" size="1" dir="ltr"', 'value', 'text', $row->config_locale);

	// включение кода безопасности для доступа к панели управления
	$lists['config_enable_admin_secure_code'] = LHtml::yesnoRadioList('config_enable_admin_secure_code', 'class="inputbox"', $row->config_enable_admin_secure_code);

	// режим редиректа при включенном коде безопасноти
	$redirect_r = array(
		LHtml::makeOption(0, 'index.php'),
		LHtml::makeOption(1, _ADMIN_REDIRECT_PAGE)
	);
	$lists['config_admin_redirect_options'] = LHtml::RadioList($redirect_r, 'config_admin_redirect_options', 'class="inputbox"', $row->config_admin_redirect_options, 'value', 'text');

	// обработчики кеширования
	$cache_handler = array();
	$cache_handler[] = LHtml::makeOption('file', 'file');
	if(function_exists('eaccelerator_get')) $cache_handler[] = LHtml::makeOption('eaccelerator', 'eAccelerator');
	if(extension_loaded('apc')) $cache_handler[] = LHtml::makeOption('apc', 'APC');
	if(class_exists('Memcache')) $cache_handler[] = LHtml::makeOption('memcache', 'Memcache');
	if(function_exists('xcache_set')) $cache_handler[] = LHtml::makeOption('xcache', 'Xcache');

	?>
<script>
	function showHideMemCacheSettings() {
		if (document.getElementById("config_cache_handler").value != "memcache") {
			document.getElementById("memcache_persist").style.display = "none";
			document.getElementById("memcache_compress").style.display = "none";
			document.getElementById("memcache_server").style.display = "none";
		}
		else {
			document.getElementById("memcache_persist").style.display = "";
			document.getElementById("memcache_compress").style.display = "";
			document.getElementById("memcache_server").style.display = "";
		}
	}
</script>

<?php
	// оработчик кеширования
	$lists['cache_handler'] = LHtml::selectList($cache_handler, 'config_cache_handler', 'class="inputbox" id="config_cache_handler" onchange="showHideMemCacheSettings();" ', 'value', 'text', $row->config_cache_handler);

	if(!empty($row->config_memcache_settings) && !is_array($row->config_memcache_settings)){
		$row->config_memcache_settings = unserialize(stripslashes($row->config_memcache_settings));
	}
	$lists['memcache_persist'] = LHtml::yesnoRadioList('config_memcache_persistent', 'class="inputbox"', $row->config_memcache_persistent);
	$lists['memcache_compress'] = LHtml::yesnoRadioList('config_memcache_compression', 'class="inputbox"', $row->config_memcache_compression);

	// использование неопубликованных плагинов
	$lists['config_use_unpublished_plugins'] = LHtml::yesnoRadioList('config_use_unpublished_plugins', 'class="inputbox"', $row->config_use_unpublished_plugins);

	// отключение syndicate
	$lists['syndicate_off'] = LHtml::yesnoRadioList('config_syndicate_off', 'class="inputbox"', $row->config_syndicate_off);

	// список шаблонов панели управления
	$titlelength = 20;
	$admin_template_path = _LPATH_ROOT . DS . 'administrator' . DS . 'templates';
	$templatefolder = @dir($admin_template_path);

	$admin_templates = array();
	$admin_templates[] = LHtml::makeOption('...', _O_OTHER); // параметр по умолчанию - позволяет использовать стандартный способ определения шаблона
	if($templatefolder){
		while($templatefile = $templatefolder->read()){
			if($templatefile != "." && $templatefile != ".." && $templatefile != ".svn" && is_dir($admin_template_path . DS . $templatefile)){
				if(strlen($templatefile) > $titlelength){
					$templatename = substr($templatefile, 0, $titlelength - 3);
					$templatename .= "...";
				} else{
					$templatename = $templatefile;
				}
				$admin_templates[] = LHtml::makeOption($templatefile, $templatename);
			}
		}
		$templatefolder->close();
	}
	sort($admin_templates);
	$lists['config_admin_template'] = LHtml::selectList($admin_templates, 'config_admin_template', 'class="inputbox" ', 'value', 'text', $row->config_admin_template);

	// режим сортировки содержимого в панели управления
	$order_list = array(
		LHtml::makeOption(0, _ORDER_BY_NAME),
		LHtml::makeOption(1, _ORDER_BY_HEADERS),
		LHtml::makeOption(2, _ORDER_BY_DATE_CR),
		LHtml::makeOption(3, _ORDER_BY_DATE_MOD),
		LHtml::makeOption(4, _ORDER_BY_ID),
		LHtml::makeOption(5, _ORDER_BY_HITS)
	);
	$lists['admin_content_order_by'] = LHtml::selectList($order_list, 'config_admin_content_order_by', 'class="inputbox" size="1"', 'value', 'text', $row->config_admin_content_order_by);

	$order_sort_list = array(
		LHtml::makeOption(1, _SORT_ASC),
		LHtml::makeOption(0, _SORT_DESC)
	);
	$lists['admin_content_order_sort'] = LHtml::selectList($order_sort_list, 'config_admin_content_order_sort', 'class="inputbox" size="1"', 'value', 'text', $row->config_admin_content_order_sort);

	// блокировка компонентов
	$lists['components_access'] = LHtml::yesnoRadioList('config_components_access', 'class="inputbox"', $row->config_components_access);

	// использование плагинов удаления содержимого
	$lists['config_use_content_delete_plugins'] = LHtml::yesnoRadioList('config_use_content_delete_plugins', 'class="inputbox"', $row->config_use_content_delete_plugins);
	// использование редактирования смодержимого
	$lists['config_use_content_edit_plugins'] = LHtml::yesnoRadioList('config_use_content_edit_plugins', 'class="inputbox"', $row->config_use_content_edit_plugins);
	// использование плагинов сохранения содержимого
	$lists['config_use_content_save_plugins'] = LHtml::yesnoRadioList('config_use_content_save_plugins', 'class="inputbox"', $row->config_use_content_save_plugins);

	HTML_config::showconfig($row, $lists, $option);
}

/**
 * Сохранение конфигурации
 */
function saveconfig($task){
	$database = database::getInstance();
	josSpoofCheck();

	$row = new JConfig();

    if(!$row->bind($_POST, 'config_tseparator')){
        mosRedirect('index2.php', $row->getError());
    }

	// if Session Authentication Type changed, delete all old Frontend sessions only - which used old Authentication Type
	if(LCore::getCfg('session_type') != $row->config_session_type){
		$past = time();
		$query = "DELETE FROM #__session WHERE time < " . $database->Quote($past) . " AND ( ( guest = 1 AND userid = 0 ) OR ( guest = 0 AND gid > 0 ) )";
		$database->setQuery($query);
		$database->query();
	}

	$server_time = date('O') / 100;
	$offset = $_POST['config_offset_user'] - $server_time;
	$row->config_offset = $offset;

	//override any possible database password change
	$row->config_password = LCore::getCfg('password');

	// handling of special characters
	$row->config_sitename = htmlspecialchars($row->config_sitename, ENT_QUOTES);

	// handling of quotes (double and single) and amp characters
	// htmlspecialchars not used to preserve ability to insert other html characters
	$row->config_offline_message = ampReplace($row->config_offline_message);
	$row->config_offline_message = str_replace('"', '&quot;', $row->config_offline_message);
	$row->config_offline_message = str_replace("'", '&#039;', $row->config_offline_message);

	// handling of quotes (double and single) and amp characters
	// htmlspecialchars not used to preserve ability to insert other html characters
	$row->config_error_message = ampReplace($row->config_error_message);
	$row->config_error_message = str_replace('"', '&quot;', $row->config_error_message);
	$row->config_error_message = str_replace("'", '&#039;', $row->config_error_message);

	// ключ кеша
	$row->config_cache_key = time();

    $row->config_dir_edit = trim($row->config_dir_edit, '\\/');

	$config = "<?php \n";

	$config .= $row->getVarText();
	$config .= "setlocale (LC_TIME, \$mosConfig_locale);\n";

	$fname = _LPATH_ROOT . '/configuration.php';

	$enable_write = intval(mosGetParam($_POST, 'enable_write', 0));
	$oldperms = fileperms($fname);
	if($enable_write){
		@chmod($fname, $oldperms | 0222);
	}

	if($fp = fopen($fname, 'w')){
		fputs($fp, $config, strlen($config));
		fclose($fp);
		if($enable_write){
			@chmod($fname, $oldperms);
		} else{
			if(mosGetParam($_POST, 'disable_write', 0)) @chmod($fname, $oldperms & 0777555);
		} // if

		$msg = _CONFIGURATION_IS_UPDATED;

		// apply file and directory permissions if requested by user
		$applyFilePerms = mosGetParam($_POST, 'applyFilePerms', 0) && $row->config_fileperms != '';
		$applyDirPerms = mosGetParam($_POST, 'applyDirPerms', 0) && $row->config_dirperms != '';
		if($applyFilePerms || $applyDirPerms){
			$mosrootfiles = array(JADMIN_BASE, 'cache', 'components', 'images', 'language', 'plugins', 'media', 'modules', 'templates', 'configuration.php');
			$filemode = null;
			if($applyFilePerms){
				$filemode = octdec($row->config_fileperms);
			}
			$dirmode = null;
			if($applyDirPerms){
				$dirmode = octdec($row->config_dirperms);
			}
			foreach($mosrootfiles as $file){
				mosChmodRecursive(_LPATH_ROOT . '/' . $file, $filemode, $dirmode);
			}
		} // if

		switch($task){
			case 'apply':
				mosRedirect('index2.php?option=com_config&hidemainmenu=1', $msg);
				break;
			case 'save':
			default:
				mosRedirect('index2.php', $msg);
				break;
		}
	} else{
		if($enable_write){
			@chmod($fname, $oldperms);
		}
		mosRedirect('index2.php', _CANNOT_OPEN_CONF_FILE);
	}
}