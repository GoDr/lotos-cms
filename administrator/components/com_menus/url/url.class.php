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
 * @subpackage Menus
 */
class url_menu{

	public static function edit($id, $menutype, $menu){
		$mainframe = MainFrame::getInstance();
        $my = LCore::getUser();

		if($menu->checked_out && $menu->checked_out != $my->id){
			mosErrorAlert($menu->title . " " . _MODULE_IS_EDITING_MY_ADMIN);
		}

		if($id){
            Menus::checkOut($my->id, $id);
		} else{
			$menu->type = 'url';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent = intval(mosGetParam($_POST, 'parent', 0));
			$menu->published = 1;
		}

		// build html select list for target window
		$lists['target'] = LAdminMenu::Target($menu);

		// build the html select list for ordering
		$lists['ordering'] = LAdminMenu::Ordering($menu, $id);
		// build the html select list for the group access
		$lists['access'] = LAdminMenu::Access($menu);
		// build the html select list for paraent item
		$lists['parent'] = LAdminMenu::Parent($menu);
		// build published button option
		$lists['published'] = LAdminMenu::Published($menu);
		// build the url link output
		$lists['link'] = LAdminMenu::Link($menu, $id);

		// get params definitions
		$params = new mosParameters($menu->params, $mainframe->getPath('menu_xml', $menu->type),
			'menu');

		url_menu_html::edit($menu, $lists, $params);
	}
}
