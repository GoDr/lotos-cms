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
 * @package Custom QuickIcons
 */
class QI_Toolbar{

	public static function _edit(){
		LibMenuBar::startTable();
		LibMenuBar::save('save');
		LibMenuBar::spacer();
		LibMenuBar::apply('apply');
		LibMenuBar::spacer();
		LibMenuBar::cancel('');
		LibMenuBar::endTable();
	}

	public static function _show(){
		LibMenuBar::startTable();
		LibMenuBar::publishList('publish');
		LibMenuBar::spacer();
		LibMenuBar::unpublishList('unpublish');
		LibMenuBar::spacer();
		LibMenuBar::addNew('new');
		LibMenuBar::spacer();
		LibMenuBar::editListX('editA');
		LibMenuBar::spacer();
		LibMenuBar::deleteList('', 'delete');
		LibMenuBar::endTable();
	}

	public static function _chooseIcon(){
		LibMenuBar::startTable();
		LibMenuBar::endTable();
	}
}