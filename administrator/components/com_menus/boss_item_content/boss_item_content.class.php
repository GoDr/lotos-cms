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

class boss_item_content_menu{
	public static function editCategory($uid, $menutype, $menu, $directory){

        $mainframe = MainFrame::getInstance();
        $my = LCore::getUser();

		if($menu->checked_out && $menu->checked_out != $my->id){
			mosErrorAlert($menu->title . " " . _MODULE_IS_EDITING_MY_ADMIN);
		}

		if($uid){
            Menus::checkOut($my->id, $uid);
		} else{
			$menu->type = 'boss_item_content';
			$menu->menutype = $menutype;
			$menu->ordering = 9999;
			$menu->parent = intval(mosGetParam($_POST, 'parent', 0));
			$menu->published = 1;
		}

        require_once(_LPATH_COM . '/com_boss/boss.class.php');
		$directoryconf = jDirectoryConf::getConfig($directory);
		$lists['categories'] = jDirectoryCategory::getAllCategories($directory);
		$lists['selected_categ'] = array(Menus::getBossSelectedCat($menu));
		$lists['selected_content'] = Menus::getBossSelectedContent($menu);
		$lists['ordering'] = LAdminMenu::Ordering($menu, $uid);
		$lists['access'] = LAdminMenu::Access($menu);
		$lists['parent'] = LAdminMenu::Parent($menu);
		$lists['published'] = LAdminMenu::Published($menu);
		$lists['link'] = LAdminMenu::Link($menu, $uid);
		$lists['directoryconf'] = $directoryconf;

		$params = new mosParameters($menu->params, $mainframe->getPath('menu_xml', $menu->type), 'menu');

		boss_item_content_menu_html::editCategory($menu, $lists, $params);
	}
}
