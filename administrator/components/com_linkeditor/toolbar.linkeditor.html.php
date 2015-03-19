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

class TOOLBAR_linkeditor{
	public static function _EDIT(){
		LibMenuBar::startTable();
		LibMenuBar::save("savelink");
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::endTable();
	}

	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::addNewX();
		LibMenuBar::spacer();
		LibMenuBar::deleteList();
		LibMenuBar::spacer();
		LibMenuBar::endTable();
	}
}