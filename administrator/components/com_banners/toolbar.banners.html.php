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


class menubanners{
	/**
	 * Draws the menu for a New banner
	 */
	public static function NEW_EDIT_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::save('savebanner');
		LibMenuBar::apply('applybanner');
		LibMenuBar::cancel('cancelbanner');
		LibMenuBar::endTable();
	}

	public static function DEFAULT_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::ext(_TASK_UPLOAD, '#', '-media-manager', 'id="tb-media-manager" onclick="popupWindow(\'components/com_banners/uploadbanners.php\',\'win1\',250,100,\'no\');"');
		LibMenuBar::publishList('publishbanner');
		LibMenuBar::unpublishList('unpublishbanner');
		LibMenuBar::addNew('newbanner');
		LibMenuBar::editList('editbanner');
		LibMenuBar::deleteList('', 'removebanners');
		LibMenuBar::back(_BANNERS_PANEL, 'index2.php?option=com_banners');
		LibMenuBar::endTable();
	}

	public static function MAIN_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::back(_BANNERS_PANEL, 'index2.php?option=com_banners');
		LibMenuBar::endTable();
	}
}

class menubannerClient{

	/**
	 * Draws the menu for a New client
	 */
	public static function NEW_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::save('saveclient');
		LibMenuBar::cancel('cancelclient');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for a client
	 */
	public static function EDIT_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::save('saveclient');
		LibMenuBar::cancel('cancelclient');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the default menu
	 */
	public static function DEFAULT_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::publishList('publishclient');
		LibMenuBar::unpublishList('unpublishclient');
		LibMenuBar::addNew('newclient');
		LibMenuBar::editList('editclient');
		LibMenuBar::deleteList('', 'removeclients');
		LibMenuBar::back(_BANNERS_PANEL, 'index2.php?option=com_banners');
		LibMenuBar::endTable();
	}
}

class menubannerCategory{
	/**
	 * Draws the menu for a New category
	 */
	public static function NEW_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::save('savecategory');
		LibMenuBar::cancel('cancelcategory');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for Editting an existing category
	 * @param int The published state (to display the inverse button)
	 */
	public static function EDIT_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::save('savecategory');
		LibMenuBar::cancel('cancelcategory');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for Editting an existing category
	 */
	public static function DEFAULT_MENU(){
		LibMenuBar::startTable();
		LibMenuBar::publishList('publishcategory');
		LibMenuBar::unpublishList('unpublishcategory');
		LibMenuBar::addNew('newcategory');
		LibMenuBar::editList('editcategory');
		LibMenuBar::deleteList('', 'removecategory');
		LibMenuBar::back(_BANNERS_PANEL, 'index2.php?option=com_banners');
		LibMenuBar::endTable();
	}
}