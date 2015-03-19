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
 * @subpackage Content
 */
class TOOLBAR_FrontPage{
	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::publishList();
		LibMenuBar::spacer();
		LibMenuBar::unpublishList();
		LibMenuBar::spacer();
		LibMenuBar::custom('remove', '-delete', '', _DELETE, true);
		LibMenuBar::spacer();
		LibMenuBar::custom('settings', '-check', '', _SETTINGS, false);
		LibMenuBar::spacer();
		LibMenuBar::help('screen.frontpage');
		LibMenuBar::endTable();
	}

	public static function _SETTINGS(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_settings');
		LibMenuBar::apply('apply_settings');
		LibMenuBar::cancel('cancel');
		LibMenuBar::endTable();
	}
}