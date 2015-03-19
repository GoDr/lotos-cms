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
class userlist_menu
{
    public static function edit($uid, $menutype, $menu)
    {
        $mainframe = MainFrame::getInstance();
        $my = LCore::getUser();

        // fail if checked out not by 'me'
        if ($menu->checked_out && $menu->checked_out != $my->id) {
            mosErrorAlert($menu->title . " " . _MODULE_IS_EDITING_MY_ADMIN);
        }

        if ($uid) {
            Menus::checkOut($my->id, $uid);
        } else {
            $menu->type = 'userlist';
            $menu->menutype = $menutype;
            $menu->browserNav = 0;
            $menu->ordering = 9999;
            $menu->parent = intval(mosGetParam($_POST, 'parent', 0));
            $menu->published = 1;
        }

        // build html select list for target window
        $lists['target'] = LAdminMenu::Target($menu);

        // build the html select list for ordering
        $lists['ordering'] = LAdminMenu::Ordering($menu, $uid);
        // build the html select list for the group access
        $lists['access'] = LAdminMenu::Access($menu);
        // build the html select list for paraent item
        $lists['parent'] = LAdminMenu::Parent($menu);
        // build published button option
        $lists['published'] = LAdminMenu::Published($menu);
        // build the url link output
        $lists['link'] = LAdminMenu::Link($menu, $uid);

        // get params definitions
        $params = new mosParameters($menu->params, $mainframe->getPath('menu_xml', $menu->type), 'menu');

        userlist_menu_html::edit($menu, $lists, $params);
    }
}

