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
 * @subpackage Massmail
 */
class TOOLBAR_massmail{
	/**
	 * Draws the menu for a New Contact
	 */
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::custom('send', '-publish', '', _SEND_BUTTON, false);
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.users.massmail');
		LibMenuBar::endTable();
	}
}