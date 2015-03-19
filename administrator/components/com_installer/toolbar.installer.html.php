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
 * @subpackage Installer
 */
class TOOLBAR_installer{
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::help('screen.installer');
		LibMenuBar::endTable();
	}

	public static function _DEFAULT2(){
		LibMenuBar::startTable();
		LibMenuBar::deleteList('', 'remove', _DELETE);
		LibMenuBar::spacer();
		LibMenuBar::help('screen.installer2');
		LibMenuBar::endTable();
	}

	public static function _NEW(){
		LibMenuBar::startTable();
		LibMenuBar::save();
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}
}