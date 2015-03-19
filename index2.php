<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
ini_set('display_errors' , 1);
error_reporting(E_ALL);

// Установка флага родительского файла
define('_LINDEX', 1);

define('_LPATH_ROOT', __DIR__);

// подключение основных глобальных переменных
require_once _LPATH_ROOT . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'defines.php';

// проверка конфигурационного файла, если не обнаружен, то загружается страница установки
if(!file_exists(_LPATH_ROOT . DS . 'configuration.php')){
    header('Location: ../installation/index.php');
    exit();
}

// подключение файла конфигурации
require_once (_LPATH_ROOT . DS . 'configuration.php');

// считаем время за которое сгенерирована страница
$mosConfig_time_generate ? $sysstart = microtime(true) : null;

// подключение главного файла - ядра системы
require_once (_LPATH_ROOT . DS . 'core' . DS . 'core.php');

// подключение главного файла - ядра системы
// TODO GoDr: заменить со временем на core.php
require_once (_LPATH_ROOT . DS . 'includes' . DS . 'joostina.php');

// подключение SEF
LSef::getInstance(LCore::getCfg('sef'), LCore::getCfg('com_frontpage_clear'));

// отображение состояния выключенного сайта
if(LCore::getCfg('offline') == 1) {
	require (_LPATH_TPL_SYS . '/offline.php');
}

// проверяем, разрешено ли использование системных плагинов
if(LCore::getCfg('mmb_system_off') == 0) {
	$_PLUGINS->loadBotGroup('system');
	// триггер событий onStart
	$_PLUGINS->trigger('onStart');
}

require_once (_LPATH_ROOT.DS.'includes'.DS.'frontend.php');

// запрос ожидаемых аргументов url (или формы)
$option		= strtolower(strval(mosGetParam($_REQUEST,'option')));
$no_html	= intval(mosGetParam($_REQUEST,'no_html',0));
$act		= strval(mosGetParam($_REQUEST,'act',''));
$pop		= intval(mosGetParam($_GET,'pop'));
$page		= intval(mosGetParam($_GET,'page'));

$print = false;
if($pop=='1' && $page==0) $print = true;

// главное окно рабочего компонента API, для взаимодействия многих 'ядер'
//$mainframe = new MainFrame($database,$option,'.');
$mainframe = MainFrame::getInstance();

if(LCore::getCfg('no_session_front') == 0) {
	$mainframe->initSession();
}

// триггер событий onAfterStart
if(LCore::getCfg('mmb_system_off') == 0) {
	$_PLUGINS->trigger('onAfterStart');
}

$my = LCore::getUser();

$gid = intval($my->gid);
// patch to lessen the impact on templates
if($option == 'search') {
	$option = 'com_search';
}

// загрузка файла русского языка по умолчанию
$mainframe->set('lang', LCore::getCfg('lang'));
include_once($mainframe->getLangFile());

if($option == 'login') {
	$mainframe->login();
	mosRedirect('index.php');
} elseif($option == 'logout') {
	$mainframe->logout();
	mosRedirect('index.php');
}

$cur_template = $mainframe->getTemplate();
define('TEMPLATE', $cur_template );

// подключаем визуальный редактор
LCore::connectionEditor();

// Содершит подключаемые скрипты в тело BODY
$_MOS_OPTION['jqueryplugins'] = '';

ob_start();

if($path = $mainframe->getPath('front')) {
	$task = strval(mosGetParam($_REQUEST,'task',''));
	$ret = mosMenuCheck($option,$task,$gid,$mainframe);
	if($ret) {
		//Подключаем язык компонента
		if($mainframe->getLangFile($option)) {
			include_once($mainframe->getLangFile($option));
		}
		//$mainframe->addLib('mylib');
		require_once ($path);
	} else {
		mosNotAuth();
	}
} else {
	header("HTTP/1.0 404 Not Found");
	echo _NOT_EXIST;
}
$_MOS_OPTION['buffer'] = ob_get_contents();

ob_end_clean();

// печать страницы
if($print) {
	$cpex = 0;
	if(LCore::getCfg('custom_print')) {
		$cust_print_file = _LPATH_TPL_FRONT . '/'.$cur_template.'/html/print.php';
		if(file_exists($cust_print_file)) {
			ob_start();
			include($cust_print_file);
			$_MOS_OPTION['buffer'] = ob_get_contents();
			ob_end_clean();
			$cpex = 1;
		}
	}
	if(!$cpex) {
		$mainframe->addCSS(_LPATH_TPL_SYS_S . '/css/print.css');
		$mainframe->addJS(_LPATH_SITE.'/includes/js/print/print.js');

		$pg_link	= str_replace(array('&pop=1','&page=0'),'',$_SERVER['REQUEST_URI']);
		$pg_link	= str_replace('index2.php','index.php',$pg_link);
		$pg_link = ltrim($pg_link,'/');
		$_MOS_OPTION['buffer'] = '<div class="logo">'. LCore::getCfg('sitename') .'</div><div id="main">'.$_MOS_OPTION['buffer']."\n</div>\n<div id=\"ju_foo\">"._PRINT_PAGE_LINK." :<br /><i>".LSef::getUrlToSef($pg_link)."</i><br /><br />&copy; ".LCore::getCfg('sitename').",&nbsp;".date('Y').'</div>';
	}
}else {
	$mainframe->addCSS(_LPATH_TPL_FRONT_S . '/'.$cur_template.'/css/template_css.css');
}

// подключение js библиотеки системы
if($my->id || $mainframe->get('joomlaJavascript')) {
	$mainframe->addJS(_LPATH_SITE.'/includes/js/joomla.javascript.js');
}

initGzip();
header('Content-type: text/html; charset=UTF-8');
/*$mosConfig_
// при активном кешировании отправим браузеру более "правильные" заголовки
if(!LCore::getCfg('caching')) { // не кешируется
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0',false);
	header('Pragma: no-cache');
} else { // кешируется
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	// 60*60=3600 - использования кеширования на 1 час
	header('Expires: '.gmdate('D, d M Y H:i:s',time() + 3600).' GMT');
	header('Cache-Control: max-age=3600');
}*/

// отображение состояния выключенного сайта при входе админа
if(defined('_ADMIN_OFFLINE')) {
	include (_LPATH_TPL_SYS . '/offlinebar.php');
}

// старт основного HTML
if($no_html == 0) {
	$customIndex2 = _LPATH_TPL_FRONT . '/' . TEMPLATE.'/index2.php';
	if(file_exists($customIndex2)) {
		require ($customIndex2);
	} else {
		// требуется для отделения номера ISO от константы  _ISO языкового файла языка
		$iso = explode('=',_ISO);
		// пролог xml
		echo '<?xml version="1.0" encoding="'.$iso[1].'"?'.'>';
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="shortcut icon" href="<?php echo _LPATH_SITE; ?>/images/favicon.ico" />
		<meta http-equiv="Content-Type" content="text/html; <?php echo _ISO; ?>" />
				<?php echo $mainframe->getHead(); ?>
	</head>
	<body class="contentpane">
				<?php mosMainBody(); ?>
	</body>
</html>
		<?php
	}
} else {
	mosMainBody();
}
doGzip();