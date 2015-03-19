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
 * @subpackage Plugins
 */
class TOOLBAR_modules{
	/**
	 * Draws the menu for Editing an existing module
	 */
	public static function _EDIT(){
		global $id;

		LibMenuBar::startTable();
		LibMenuBar::save();
		LibMenuBar::spacer();
		// кнопка "Применить" с Ajax
		LibMenuBar::ext(_APPLY, '#', '-apply', 'id="tb-apply" onclick="ch_apply();return;"');

		LibMenuBar::spacer();
		if($id){
			// for existing content items the button is renamed `close`
			LibMenuBar::cancel('cancel', _CLOSE);
		} else{
			LibMenuBar::cancel();
		}
		LibMenuBar::spacer();
		LibMenuBar::help('screen.plugins.edit');
		LibMenuBar::endTable();
	}

	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::publishList();
		LibMenuBar::spacer();
		LibMenuBar::unpublishList();
		LibMenuBar::spacer();
		LibMenuBar::deleteList();
		LibMenuBar::spacer();
		LibMenuBar::editListX();
		LibMenuBar::spacer();
		LibMenuBar::addNewX();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.plugins');
		LibMenuBar::endTable();
	}
}