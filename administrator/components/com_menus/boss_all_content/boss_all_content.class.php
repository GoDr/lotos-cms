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
class boss_all_content_menu
{
    public static function editCategory($uid, $menutype, $menu, $directory)
    {
        $mainframe = MainFrame::getInstance();
        $my = LCore::getUser();

        if ($menu->checked_out && $menu->checked_out != $my->id) {
            mosErrorAlert($menu->title . " " . _MODULE_IS_EDITING_MY_ADMIN);
        }
        $link = 'index.php?option=com_boss&task=show_all&directory=' . $directory;
        if ($uid) {
            Menus::checkOut($my->id, $uid);
        } else {
            $menu->type = 'boss_all_content';
            $menu->menutype = $menutype;
            $menu->ordering = 9999;
            $menu->parent = intval(mosGetParam($_POST, 'parent', 0));
            $menu->published = 1;
        }
        $menu->link = $link;

        require_once(_LPATH_COM . '/com_boss/boss.class.php');
        $directoryconf = jDirectoryConf::getConfig($directory);

        // build the html select list for ordering
        $lists['ordering'] = LAdminMenu::Ordering($menu, $uid);
        // build the html select list for the group access
        $lists['access'] = LAdminMenu::Access($menu);
        // build the html select list for paraent item
        $lists['parent'] = LAdminMenu::Parent($menu);
        // build published button option
        $lists['published'] = LAdminMenu::Published($menu);
        // build the url link output
        $lists['link'] = $link;
        //название каталога
        $lists['directoryconf'] = $directoryconf;
        //ид каталога
        $lists['directory'] = $directory;

        $params = new mosParameters($menu->params, $mainframe->getPath('menu_xml', $menu->type), 'menu');

        boss_all_content_menu_html::editCategory($menu, $lists, $params);
    }
}


