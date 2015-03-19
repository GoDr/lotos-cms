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

/**
 * @package Joostina
 * @subpackage Menus
 */
class TOOLBAR_menus{
	/**
	 * Draws the menu for a New top menu item
	 */
	public static function _NEW(){
		LibMenuBar::startTable();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menus.new');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu to Move Menut Items
	 */
	public static function _MOVEMENU(){
		LibMenuBar::startTable();
		LibMenuBar::custom('movemenusave', '-x-move', '', _MOVE, false);
		LibMenuBar::spacer();
		LibMenuBar::cancel('cancelmovemenu');
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menus.move');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu to Move Menut Items
	 */
	public static function _COPYMENU(){
		LibMenuBar::startTable();
		LibMenuBar::custom('copymenusave', '-copy', '', _COPY, false);
		LibMenuBar::spacer();
		LibMenuBar::cancel('cancelcopymenu');
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menus.copy');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu to edit a menu item
	 */
	public static function _EDIT(){
		global $id;

		if(!$id){
			$cid = josGetArrayInts('cid');
			$id = $cid[0];
		}
		$menutype = strval(mosGetParam($_REQUEST, 'menutype', 'mainmenu'));

		LibMenuBar::startTable();
		if(!$id){
			$link = 'index2.php?option=com_menus&menutype=' . $menutype . '&task=new&hidemainmenu=1';
			LibMenuBar::back(_MENU_BACK, $link);
			LibMenuBar::spacer();
		}
		LibMenuBar::custom('save_and_new', '-save-and-new', '', _SAVE_AND_ADD, false);
		LibMenuBar::save();
		LibMenuBar::spacer();
		LibMenuBar::apply();
		LibMenuBar::spacer();
		if($id){
			// for existing content items the button is renamed `close`
			LibMenuBar::cancel('cancel', _CLOSE);
		} else{
			LibMenuBar::cancel();
		}
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menus.edit');
		LibMenuBar::endTable();
	}

	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::publishList();
		LibMenuBar::spacer();
		LibMenuBar::unpublishList();
		LibMenuBar::spacer();
		LibMenuBar::customX('movemenu', '-move', '', _MOVE, true);
		LibMenuBar::spacer();
		LibMenuBar::customX('copymenu', '-copy', '', _COPY, true);
		LibMenuBar::spacer();
		LibMenuBar::trash();
		LibMenuBar::spacer();
		LibMenuBar::editListX();
		LibMenuBar::spacer();
		LibMenuBar::addNewX();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.menus');
		LibMenuBar::endTable();
	}
}

