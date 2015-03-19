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
 * @subpackage Templates
 */
class TOOLBAR_templates{
	public static function _DEFAULT($client){
		LibMenuBar::startTable();
		if($client == "admin"){
			LibMenuBar::makeDefault();
			LibMenuBar::spacer();
		} else{
			LibMenuBar::makeDefault();
			LibMenuBar::spacer();
			LibMenuBar::assign();
			LibMenuBar::spacer();
		}
		LibMenuBar::deleteList();
		LibMenuBar::spacer();
		LibMenuBar::editHtmlX('edit_source');
		LibMenuBar::spacer();
		LibMenuBar::editCssX('edit_css');
		LibMenuBar::spacer();
		LibMenuBar::addNew();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.templates');
		LibMenuBar::endTable();
	}

	public static function _VIEW(){
		LibMenuBar::startTable();
		LibMenuBar::back();
		LibMenuBar::endTable();
	}

	public static function _EDIT_SOURCE(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_source');
		LibMenuBar::ext(_APPLY, '#', '-apply', 'id="tb-apply" onclick="ch_apply();return;"');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function _EDIT_CSS(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_css');
		LibMenuBar::ext(_APPLY, '#', '-apply', 'id="tb-apply" onclick="ch_apply();return;"');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function _ASSIGN(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_assign', _SAVE);
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.templates.assign');
		LibMenuBar::endTable();
	}

	public static function _POSITIONS(){
		LibMenuBar::startTable();
		LibMenuBar::save('save_positions');
		LibMenuBar::spacer();
		LibMenuBar::cancel();
		LibMenuBar::spacer();
		LibMenuBar::help('screen.templates.modules');
		LibMenuBar::endTable();
	}
}