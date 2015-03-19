<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Component
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      14.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Com_menumanager
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package Joostina
 * @subpackage Menus
 */
class TOOLBAR_menumanager{
	/**
	 * Draws the menu for the Menu Manager
	 */
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::customX('copy', '-copy', '', _COPY, true);
		LibMenuBar::spacer();
		LibMenuBar::customX('delete', '-delete', '', _DELETE, true);
		LibMenuBar::spacer();
		LibMenuBar::editListX();
		LibMenuBar::spacer();
		LibMenuBar::addNewX();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menumanager');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu to delete a menu
	 */
	public static function _DELETE(){
		LibMenuBar::startTable();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu to create a New menu
	 */
	public static function _NEWMENU(){
		LibMenuBar::startTable();
		LibMenuBar::custom('save', '-save', '', _SAVE, false);
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menumanager.new');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu to create a New menu
	 */
	public static function _COPY(){
		LibMenuBar::startTable();
		LibMenuBar::custom('copymenu', '-copy', '', _COPY, false);
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menumanager.copy');
		LibMenuBar::endTable();
	}
}