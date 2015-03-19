<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package Joostina
 * @subpackage Users
 */
class TOOLBAR_users{
	/**
	 * Draws the menu to edit a user
	 */
	public static function _EDIT(){
		global $id;

		LibMenuBar::startTable();
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
		LibMenuBar::help('screen.users');
		LibMenuBar::endTable();
	}

	public static function _CONFIG(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_config');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::custom('logout', '-cancel', '', _DISABLE);
		LibMenuBar::spacer();
		LibMenuBar::custom('block', '-cancel', '', _BLOCK_USER);
		LibMenuBar::spacer();
		LibMenuBar::custom('unblock', '-publish', '', _CHECKIN_OJECT);
		LibMenuBar::spacer();
		LibMenuBar::deleteList();
		LibMenuBar::spacer();
		LibMenuBar::editListX();
		LibMenuBar::spacer();
		LibMenuBar::addNewX();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.users');
		LibMenuBar::endTable();
	}
}
