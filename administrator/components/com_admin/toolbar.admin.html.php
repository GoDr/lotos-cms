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
 * @subpackage Admin
 */
class TOOLBAR_admin{
	public static function _SYSINFO(){
		LibMenuBar::startTable();
		LibMenuBar::help('screen.system.info');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for a New category
	 */
	public static function _CPANEL(){
		LibMenuBar::startTable();
		LibMenuBar::help('screen.cpanel');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for a New category
	 */
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		//LibMenuBar::help( 'screen.cpanel' );
		LibMenuBar::endTable();
	}
}