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
 * @subpackage Modules
 */
class TOOLBAR_modules{
	/**
	 * Draws the menu for a New module
	 */
	public static function _NEW(){
		LibMenuBar::startTable();
		LibMenuBar::preview('modulewindow');
		LibMenuBar::spacer();
		LibMenuBar::save();
		LibMenuBar::spacer();
		LibMenuBar::apply();
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.modules.new');
		LibMenuBar::endTable();
	}

	/**
	 * Draws the menu for Editing an existing module
	 */
	public static function _EDIT($cur_template, $publish){
		global $id;
		LibMenuBar::startTable();
		LibMenuBar::ext(_PREVIEW, '#', '-preview', " onclick=\"if (typeof document.adminForm.content == 'undefined') { alert('" . _PREVIEW_ONLY_CREATED_MODULES . "');} else { var content = document.adminForm.content.value; content = content.replace('#', ''); var title = document.adminForm.title.value; title = title.replace('#', ''); window.open('popups/modulewindow.php?title=' + title + '&amp;content=' + content + '&amp;t=$cur_template', 'win1', 'status=no,toolbar=no,scrollbars=auto,titlebar=no,menubar=no,resizable=yes,width=200,height=400,directories=no,location=no');}\"");
		LibMenuBar::save();
		LibMenuBar::spacer();
		// кнопка "Применить" с Ajax
		LibMenuBar::ext(_APPLY, '#', '-apply', 'id="tb-apply" onclick="ch_apply();return;"');

		LibMenuBar::spacer();
		if($id){
			// for existing content items the button is renamed `close`
			LibMenuBar::cancel('cancel', _CLOSE);
		} else{
			LibMenuBar::cancel();
		}
		LibMenuBar::spacer();
		LibMenuBar::help('screen.modules.edit');
		LibMenuBar::endTable();
	}

	public static function _DEFAULT(){
		LibMenuBar::startTable();
		LibMenuBar::publishList();
		LibMenuBar::spacer();
		LibMenuBar::unpublishList();
		LibMenuBar::spacer();
		LibMenuBar::custom('copy', '-copy', '', _COPY, true);
		LibMenuBar::spacer();
		LibMenuBar::deleteList();
		LibMenuBar::spacer();
		LibMenuBar::editListX();
		LibMenuBar::spacer();
		LibMenuBar::addNewX();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.modules');
		LibMenuBar::endTable();
	}
}