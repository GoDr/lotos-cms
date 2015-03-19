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

LAdminMenu::menuItem($type);
$task = LSef::getTask();
if (LCore::getParam($_REQUEST, 'type', '', 'sn') and $task == 'edit') {
    $task = $type;
}

$directory = Menus::getDirectory($menu);

switch($task){
    case 'boss_show_profile':
        boss_show_profile_menu::editCategory(0,$menutype, $menu, $directory);
        break;

    case 'edit':
		boss_show_profile_menu::editCategory($uid, $menutype, $menu, $directory);
		break;

	case 'save':
	case 'apply':
	case 'save_and_new':
		Menus::saveMenu($task);
		break;
}