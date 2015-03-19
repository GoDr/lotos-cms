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
 * @subpackage Config
 */
class TOOLBAR_config{

	/**
	 * Меню для сохранялки параметров отдельных компонентов
	 */
	public static function _SAVE_EXT_CONFIG(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_component_config');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::save();
		LibMenuBar::spacer();
		LibMenuBar::ext(_APPLY, '#', '-apply', 'id="tb-apply" onclick="ch_apply();return;"');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.config');
		LibMenuBar::endTable();
	}
}