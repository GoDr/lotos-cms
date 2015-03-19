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
 * @subpackage Languages
 */
class TOOLBAR_languages{
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::publishList();
		LibMenuBar::spacer();
		LibMenuBar::deleteList();
		//LibMenuBar::spacer();
		//LibMenuBar::editListX('edit_source');
		LibMenuBar::spacer();
		LibMenuBar::addNew();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.languages');
		LibMenuBar::endTable();
	}

	public static function _NEW(){
		LibMenuBar::startTable();
		LibMenuBar::save();
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function _EDIT_SOURCE(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_source');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}
}