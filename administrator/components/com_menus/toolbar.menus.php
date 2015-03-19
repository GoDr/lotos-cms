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
require_once ($mainframe->getPath('toolbar_html'));
require_once ($mainframe->getPath('toolbar_default'));
$task = LSef::getTask();

switch($task){
	case 'new':
		TOOLBAR_menus::_NEW();
		break;

	case 'movemenu':
		TOOLBAR_menus::_MOVEMENU();
		break;

	case 'copymenu':
		TOOLBAR_menus::_COPYMENU();
		break;

	case 'edit':
		$cid = josGetArrayInts('cid');
		$path = _LPATH_ADMINISTRATOR . '/components/com_menus/';

		if($cid[0]){
            $_db = LCore::getDB();
            $type = $_db->selectCell("SELECT `type` FROM `#__menu` WHERE `id` = ?", (int)$cid[0]);
  			TOOLBAR_menus::_EDIT();
		} else{
            $type = strval(mosGetParam($_REQUEST, 'type', null));
				TOOLBAR_menus::_EDIT();
		}
		break;

	default:
		$type = strval(mosGetParam($_REQUEST, 'type'));
		TOOLBAR_menus::_DEFAULT();
}

