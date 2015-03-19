<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * Lotos CMS - Компонент ядра
 *
 * @package   Lotos CMS
 * @subpackage Admin
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2014 Gold Dragon
 * @date      01.07.2014
 * @see       http://wiki.lotos-cms.ru/index.php/XMap
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// Подключаем файлы компонента
LCore::requireFilesCom('admin', true);

// Проверка доступа к компоненту
if (!($acl->acl_check('administration', 'login', 'users', $my->usertype)) || $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_gdfeedback')) {
    mosRedirect('index2.php', _NOT_AUTH);
}

$task = LSef::getTask();

switch($task){

	// очистка кеша содержимого
	case 'clean_cache':
		mosCache::cleanCache('com_boss');
		mosRedirect('index2.php', _CACHE_CLEAR_CONTENT);
		break;

	// очистка всего кеша
	case 'clean_all_cache':
		mosCache::cleanCache();
		mosCache::cleanCache('page');
		mosRedirect('index2.php', _CACHE_CLEAR_ALL);
		break;

	case 'redirect':
		$goto = strval(strtolower(mosGetParam($_REQUEST, 'link')));
		if($goto == 'null'){
			$msg = _COM_ADMIN_NON_LINK_OBJ;
			mosRedirect('index2.php?option=com_admin&task=listcomponents', $msg);
			exit();
		}
		$goto = str_replace("'", '', $goto);
		mosRedirect($goto);
		break;

	case 'listcomponents':
		HTML_admin_misc::ListComponents();
		break;

	case 'sysinfo':
		$version = new LVersion();
		HTML_admin_misc::system_info($version, $option);
		break;

	case 'changelog':
		HTML_admin_misc::changelog();
		break;

	case 'help':
		HTML_admin_misc::help();
		break;

	case 'version':
		HTML_admin_misc::version();
		break;

	case 'preview':
		HTML_admin_misc::preview();
		break;

	case 'preview2':
		HTML_admin_misc::preview(1);
		break;

	case 'cpanel':
	default:
		HTML_admin_misc::controlPanel();
		break;
}