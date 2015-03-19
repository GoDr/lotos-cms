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
 * @subpackage Polls
 */
class TOOLBAR_poll{
	/**
	 * Draws the menu for a New category
	 */
	public static function _NEW(){
		LibMenuBar::startTable();
		LibMenuBar::save();
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.polls.edit');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for Editing an existing category
	 */
	public static function _EDIT($pollid, $cur_template){
		$database = database::getInstance();
		global $id;

		$sql = "SELECT template FROM #__templates_menu WHERE client_id = 0 AND menuid = 0";
		$database->setQuery($sql);
		$cur_template = $database->loadResult();
		LibMenuBar::startTable();
		$popup = 'pollwindow';
		LibMenuBar::ext(_PREVIEW, '#', '-preview', " onclick=\"window.open('popups/$popup.php?pollid=$pollid&t=$cur_template', 'win1', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no');\"");
		LibMenuBar::spacer();
		LibMenuBar::save();
		LibMenuBar::spacer();
		if($id){
			// for existing content items the button is renamed `close`
			LibMenuBar::cancel('cancel', _CLOSE);
		} else{
			LibMenuBar::cancel();
		}
		LibMenuBar::spacer();
		LibMenuBar::help('screen.polls.edit');
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
		LibMenuBar::help('screen.polls');
		LibMenuBar::endTable();
	}
}