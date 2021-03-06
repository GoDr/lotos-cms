<?php

/**
 * @package Joostina
 * @copyright Авторские права (C) 2009 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */
// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

/*
 * Make sure the user is authorized to view this page
 */

$mainframe = MainFrame::getInstance();

// ensure user has access to this function
if(!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all') | $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_cache'))){
	mosRedirect('index2.php', _NOT_AUTH);
}

// Load the html output class and the model class
require_once ($mainframe->getPath('admin_html'));
require_once ($mainframe->getPath('class'));

$cid = mosGetParam($_REQUEST, 'cid', 0);

$task = LSef::getTask();
/*
 * This is our main control structure for the component
 *
 * Each view is determined by the $task variable
 */
switch($task){
	case 'delete':
		CacheController::deleteCache($cid);
		CacheController::showCache();
		break;

	default :
		CacheController::showCache();
		break;
}

/**
 * Static class to hold controller functions for the Cache component
 * @static
 * @package        Joostina
 * @subpackage    Cache
 * @since        1.3
 */
class CacheController{

	/**
	 * Show the cache
	 * @since    1.3
	 */
	public static function showCache(){
		$option = LSef::getOption();
		$mainframe = MainFrame::getInstance();

		$client = intval(mosGetParam($_REQUEST, 'client', 0));

		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'));
		$limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0);

		$cmData = new CacheData(_LPATH_ROOT . '/cache');

		MainFrame::addLib('pagenavigation');
		$pageNav = new mosPageNav($cmData->getGroupCount(), $limitstart, $limit);
		CacheView::displayCache($cmData->getRows($limitstart, $limit), $client, $pageNav);
	}

	public static function deleteCache($cid){

		$cmData = new CacheData(_LPATH_ROOT . '/cache');
		$cmData->cleanCacheList($cid);
	}

}