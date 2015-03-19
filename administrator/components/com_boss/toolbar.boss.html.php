<?php
/**
 * @package Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */
defined('_LINDEX') or die('STOP in file ' . __FILE__);

class menuBOSS{
	public static function backSave(){
		LibMenuBar::startTable();
		LibMenuBar::back();
		LibMenuBar::apply();
		LibMenuBar::save();
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function newTmplField(){
		LibMenuBar::startTable();
		LibMenuBar::back();
		LibMenuBar::spacer();
		LibMenuBar::addNew('new_tmpl_field');
		LibMenuBar::endTable();
	}

	public static function editTmplSource(){
		LibMenuBar::startTable();
		LibMenuBar::back();
		LibMenuBar::spacer();
		LibMenuBar::save('save_tmpl_source');
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function addEditDelete(){
		LibMenuBar::startTable();
		LibMenuBar::addNew('new');
		LibMenuBar::editList('edit', _EDIT);
		LibMenuBar::deleteList(' ', 'delete', _DELETE);
		LibMenuBar::endTable();
	}

	public static function delete(){
		LibMenuBar::startTable();
		LibMenuBar::deleteList(' ', 'delete', _DELETE);
		LibMenuBar::endTable();
	}

	public static function edit_tmpl_field(){
		LibMenuBar::startTable();
		LibMenuBar::back();
		LibMenuBar::spacer();
		LibMenuBar::save('save_tmpl_field');
		LibMenuBar::cancel();
		LibMenuBar::endTable();
	}

	public static function addEditDeleteCopy(){
		LibMenuBar::startTable();
		LibMenuBar::addNew('new');
		LibMenuBar::editList('copy', _COPY);
		LibMenuBar::editList('edit', _EDIT);
		LibMenuBar::deleteList(' ', 'delete', _DELETE);
		LibMenuBar::endTable();
	}
}