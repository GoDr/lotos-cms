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
 * @subpackage Trash
 */
class TOOLBAR_Trash{
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::custom('restoreconfirm', '-check', '', _RESTORE, true);
		LibMenuBar::spacer();
		LibMenuBar::custom('deleteconfirm', '-delete', '', _DELETE, true);
		LibMenuBar::spacer();
		LibMenuBar::custom('deleteall', '-delete', '', _CLEAR_TRASH, false);
		LibMenuBar::spacer();
		LibMenuBar::help('screen.trashmanager');
		LibMenuBar::endTable();
	}

	public static function _DELETE(){
		LibMenuBar::startTable();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function _SETTINGS(){
		LibMenuBar::startTable();
		LibMenuBar::back();
		LibMenuBar::spacer();
		LibMenuBar::save();
		LibMenuBar::endTable();
	}
}