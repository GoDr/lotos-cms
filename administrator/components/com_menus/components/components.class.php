<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Menu
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LMenus
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

class components_menu{

	public static function edit($id, $menutype, $menu){
		$mainframe = MainFrame::getInstance();
        $my = LCore::getUser();
		$database = database::getInstance();

		$row = new mosComponent($database);
		// load the row from the db table
		$row->load((int)$menu->componentid);

		// fail if checked out not by 'me'
		if($menu->checked_out && $menu->checked_out != $my->id){
			mosErrorAlert($menu->title . " " . _MODULE_IS_EDITING_MY_ADMIN);
		}

		if($id){
			Menus::checkOut($my->id, $id);
		} else{
			$menu->type = 'components';
			$menu->menutype = $menutype;
			$menu->browserNav = 0;
			$menu->ordering = 9999;
			$menu->parent = intval(mosGetParam($_POST, 'parent', 0));
			$menu->published = 1;
		}

		$sql = "SELECT c.id AS value, c.name AS text, c.link
		        FROM `#__components` AS c
		        WHERE c.link != ''
		        ORDER BY c.name";
		$database->setQuery($sql);
		$components = $database->loadObjectList();
		$lists['componentid'] = LAdminMenu::Component($menu, $id, $components);
		$lists['componentname'] = LAdminMenu::ComponentName($menu, $components);
		$lists['ordering'] = LAdminMenu::Ordering($menu, $id);
		$lists['access'] = LAdminMenu::Access($menu);
		$lists['parent'] = LAdminMenu::Parent($menu);
		$lists['published'] = LAdminMenu::Published($menu);
		$lists['link'] = LAdminMenu::Link($menu, $id);

		// get params definitions
		$params = new mosParameters($menu->params, $mainframe->getPath('com_xml', $row->option), 'component');

		components_menu_html::edit($menu, $components, $lists, $params);
	}
}