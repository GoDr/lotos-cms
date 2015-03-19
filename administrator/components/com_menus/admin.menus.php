<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Menu
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Menus
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */


// Подключаем файы компонента
LCore::requireFilesCom('menus', true);

$path = _LPATH_ADMINISTRATOR . '/components/com_menus/';

$task = LSef::getTask();

switch ($task) {
//
//
//    case 'qq_accesspublic':
//        accessMenu(intval($cid[0]), 0, $option, $menutype);
//        break;
//
//    case 'qq_accessregistered':
//        accessMenu(intval($cid[0]), 1, $option, $menutype);
//        break;
//
//    case 'qq_accessspecial':
//        accessMenu(intval($cid[0]), 2, $option, $menutype);
//        break;
//
//
    case 'saveorder':
        Menus::saveOrder();
        break;

    case 'orderup':
        Menus::orderMenu($task);
        break;

    case 'orderdown':
        Menus::orderMenu($task);
        break;

    case 'publish':
    case 'unpublish':
        Menus::publishMenu($task);
        break;

    case 'movemenusave':
        Menus::moveMenuSave();
        break;

    case 'movemenu':
        Menus::moveMenu();
        break;
//
    case 'copymenusave':
        Menus::copyMenuSave();
        break;

    case 'copymenu':
        Menus::copyMenu();
        break;

    case 'remove':
        Menus::removeMenu();
        break;

    case 'save':
    case 'apply':
    case 'save_and_new':
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $uid = LCore::getParam($_GET, 'id', 0, 'i');
        if (isset($cid[0]) and $cid[0]) {
            $uid = $cid[0];
        }
        $menu = Menus::getMenu($uid);
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $type = LCore::getParam($_REQUEST, 'type', '', 'sn');
        if (file_exists($path . $type . '/' . $type . '.menu.php')) {
            require_once($path . $type . '/' . $type . '.menu.php');
        }
        break;

    case 'edit':
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $uid = LCore::getParam($_GET, 'id', 0, 'i');
        if (isset($cid[0]) and !$uid) {
            $uid = $cid[0];
        }
        $menu = Menus::getMenu($uid);
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $type = LCore::getParam($_REQUEST, 'type', '', 'sn');
        if (empty($type)) {
            $_db = LCore::getDB();
            $type = $_db->selectCell('SELECT `type` FROM `#__menu` WHERE `id` = ?', $uid);
        }
        if (file_exists($path . $type . '/' . $type . '.menu.php')) {
            require_once($path . $type . '/' . $type . '.menu.php');
        }
        break;

    case 'new':
        Menus::addMenuItem();
        break;

    case 'cancel':
        Menus::cancelMenu();
        break;

    case 'cancelcopymenu':
    case 'cancelmovemenu':
    default:
        $type = LCore::getParam($_REQUEST, 'type', '', 'sn');
        if ($type) {
            require_once($path . $type . '/' . $type . '.menu.php');
        } else {
            Menus::viewMenuItems();
        }
}

class Menus
{
    ///////////////////////////////////////////////////////////
    // Private Functions
    ///////////////////////////////////////////////////////////

    private static function getMenuChildrenRecurse($mitems, $parents, $list, $maxlevel = 20, $level = 0)
    {
        // check to reduce recursive processing
        if ($level <= $maxlevel && count($parents)) {
            $children = array();
            foreach ($parents as $id) {
                foreach ($mitems as $item) {
                    if ($item['parent'] == $id) {
                        $children[] = $item['id'];
                    }
                }
            }

            if (count($children)) {
                $list = self::getMenuChildrenRecurse($mitems, $children, $list, $maxlevel, $level + 1);
                $list = array_merge($list, $children);
            }
        }
        return $list;
    }

    /**
     * Получение данных о пункте меню
     *
     * @param $type : тип пункта меню
     * @param $component
     *
     * @return mixed
     *
     * @modifocation 22.01.2014 Gold Dragon
     */
    private static function ReadMenuXML($type, $component = -1)
    {
        require_once(_LPATH_ROOT . '/includes/domit/xml_domit_lite_include.php');
        $xmlfile = _LPATH_ADMINISTRATOR . '/components/com_menus/' . $type . DS . $type . '.xml';
        $xmlDoc = new DOMIT_Lite_Document();

        $xmlDoc->resolveErrors(true);

        $name = $descrip = $group = '';

        if ($xmlDoc->loadXML($xmlfile, false, true)) {

            $root = $xmlDoc->documentElement;

            if ($root->getTagName() == 'mosinstall' && ($root->getAttribute('type') == 'component' || $root->getAttribute('type') == 'menu')) {
                // Menu Type Name
                $element = $root->getElementsByPath('name', 1);
                $name = $element ? trim($element->getText()) : '';
                // Menu Type Description
                $element = $root->getElementsByPath('description', 1);
                $descrip = $element ? trim($element->getText()) : '';
                // Menu Type Group
                $element = $root->getElementsByPath('group', 1);
                $group = $element ? trim($element->getText()) : '';
            }
        }

        if (($component != -1) && ($name == 'Component')) {
            $name .= ' - ' . $component;
        }

        $row[0] = $name;
        $row[1] = $descrip;
        $row[2] = $group;
        return $row;
    }

    ///////////////////////////////////////////////////////////
    // Protected Functions
    ///////////////////////////////////////////////////////////

    /**
     * Кнопка "Передвинуть позицию вверх"
     *
     * @param int    $i    : порядковый номер
     * @param string $task : страница обработки
     * @param string $alt  : описание кнопки
     *
     * @return string
     *
     * @modification 23.01.2014 Gold Dragon
     */
    protected static function orderUpIcon($i, $task = 'orderup', $alt = _PN_MOVE_TOP)
    {
        $cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';
        if ($i > 0) {
            return '<a href="#reorder" onClick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '"><img src="' . $cur_file_icons_path . '/uparrow.png" width="12" height="12" border="0" alt="'
            . $alt . '" /></a>';
        } else {
            return '&nbsp;';
        }
    }

    /**
     * Кнопка "Передвинуть позицию dybp"
     *
     * @param int    $i    : порядковый номер
     * @param int    $n    : всего количество  пунктов
     * @param string $task : страница обработки
     * @param string $alt  : описание кнопки
     *
     * @return string
     *
     * @modification 23.01.2014 Gold Dragon
     */
    protected static function orderDownIcon($i, $n, $task = 'orderdown', $alt = _PN_MOVE_DOWN)
    {
        $cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';
        if ($i < $n - 1) {
            return '<a href="#reorder" onClick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')" title="' . $alt . '"><img src="' . $cur_file_icons_path . '/downarrow.png" width="12" height="12" border="0" alt="'
            . $alt . '" /></a>';
        } else {
            return '&nbsp;';
        }
    }

    ///////////////////////////////////////////////////////////
    // Public Functions
    ///////////////////////////////////////////////////////////

//вычисляет ид кщнтента из ссылки меню
    public static function getBossSelectedContent($menu){
        $content_id = 0;
        if (isset($menu->link)) {
            $parse_url = parse_url($menu->link, PHP_URL_QUERY);
            parse_str($parse_url, $parse_str);
            if (array_key_exists('content_id', $parse_str)) {
                $content_id = $parse_str['content_id'];
            }
        }
        return $content_id;
    }

    public static function getBossSelectedCat($menu){
        $catid = 0;
        if (isset($menu->link)) {
            $parse_url = parse_url($menu->link, PHP_URL_QUERY);
            parse_str($parse_url, $parse_str);
            if (array_key_exists('catid', $parse_str)) {
                $catid = $parse_str['catid'];
            }
        }
        return $catid;
    }

    //вычисляет ид каталога из ссылки меню
    public static function getDirectory($menu)
    {
        $directory = LCore::getParam($_REQUEST, 'directory', 0, 'i');
        if (!$directory and isset($menu->link)) {
            $parse_url = parse_url($menu->link, PHP_URL_QUERY);
            parse_str($parse_url, $parse_str);
            if (array_key_exists('directory', $parse_str)) {
                $directory = $parse_str['directory'];
            }
        }
        return $directory;
    }

    public static function saveOrder()
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $order = mosGetParam($_REQUEST, 'order', '');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        if (!empty($cid) and !empty($order)) {
            $_db = LCore::getDB();
            foreach ($cid as $key => $value) {
                $_db->update("UPDATE `#__menu` SET `ordering`= ? WHERE  `id` = ?;", $order[$key], $cid[$key]);
            }
        }
        $msg = _NEW_ORDER_SAVED;
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype, $msg);
    }

    public static function orderMenu($task)
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        if (!empty($cid[0])) {
            $_db = LCore::getDB();
            $inc = ($task == 'orderdown') ? 1 : -1;
            $parent = $_db->select("SELECT `parent` FROM `#__menu` WHERE `id` = ?", $cid[0]);
            $rows = $_db->select("SELECT `id`, `ordering` FROM `#__menu` WHERE `parent` = ? AND `published` != ? AND `menutype` = ? ORDER BY `ordering`", $parent, -2, $menutype);

            $res = 0;
            foreach ($rows as $key => $value) {
                $rows[$key]['ordering'] = $key;
                if ($rows[$key]['id'] == $cid[0]) {
                    $res = $key;
                }
            }

            $rows[$res]['ordering'] = $rows[$res]['ordering'] + $inc;
            $rows[$res + $inc]['ordering'] = $rows[$res]['ordering'] - $inc;

            foreach ($rows as $value) {
                $_db->update("UPDATE `#__menu` SET `ordering`= ? WHERE  `id` = ?;", $value['ordering'], $value['id']);
            }
        }
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype);
    }

    public static function publishMenu($task)
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        if (sizeof($cid[0])) {
            $pbl = ($task == 'publish') ? 1 : 0;
            $_db = LCore::getDB();
            $ids = 'id=' . implode(' OR id=', $cid);
            $ids = preg_replace('#[\d]+#', '?', $ids);
            $sql = "UPDATE `#__menu` SET `published`= ? WHERE ($ids);";
            $_db->update($sql, $pbl, $cid);
        }
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype);
    }

    public static function moveMenuSave()
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $menu = LCore::getParam($_REQUEST, 'menu', 0, 'i');
        if (sizeof($cid[0])) {
            $_db = LCore::getDB();
            $ids = 'id=' . implode(' OR id=', $cid);
            $ids = preg_replace('#[\d]+#', '?', $ids);
            $sql = "UPDATE `#__menu` SET `menutype`= ? WHERE ($ids);";
            $_db->update($sql, $menu, $cid);
        }
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype);
    }

    public static function moveMenu()
    {
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $database = database::getInstance();

        $cids = 'a.id=' . implode(' OR a.id=', $cid);
        $query = "SELECT a.name FROM `#__menu` AS a WHERE ( $cids )";
        $database->setQuery($query);
        $items = $database->loadObjectList();

        $menuTypes = LAdminMenu::getMenuTypes();

        $menu = array();
        foreach ($menuTypes as $menuType) {
            if ($menuType['id'] != $menutype) {
                $menu[] = LHtml::makeOption($menuType['id'], $menuType['type']);
            }
        }
        $MenuList = LHtml::selectList($menu, 'menu', 'class="inputbox" size="10"', 'value', 'text', null);

        HTML_menusections::moveMenuHtml($cid, $MenuList, $items, $menutype);
    }

    public static function copyMenuSave()
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $menu = LCore::getParam($_REQUEST, 'menu', 0, 'i');
        if (sizeof($cid[0]) and $menu != $menutype) {
            $_db = LCore::getDB();
            $ids = 'id=' . implode(' OR id=', $cid);
            $ids = preg_replace('#[\d]+#', '?', $ids);
            $sql = "SELECT `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`,
                            `checked_out`,  `checked_out_time`,  `pollid`,  `browserNav`,  `access`,  `utaccess`, `params`
                    FROM `#__menu`
                    WHERE ( $ids )";
            $rows = $_db->select($sql, $cid);
            foreach ($rows as $row) {
                $sql
                    = "INSERT INTO `#__menu` (`menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`,
                            `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $_db->insert(
                    $sql,
                    $menu,
                    $row['name'],
                    $row['link'],
                    $row['type'],
                    $row['published'],
                    0,
                    $row['componentid'],
                    $row['sublevel'],
                    $row['ordering'],
                    $row['checked_out'],
                    $row['checked_out_time'],
                    $row['pollid'],
                    $row['browserNav'],
                    $row['access'],
                    $row['utaccess'],
                    $row['params']
                );
            }
        }
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype);
    }

    public static function copyMenu()
    {
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $database = database::getInstance();

        $cids = 'a.id=' . implode(' OR a.id=', $cid);
        $query = "SELECT a.name FROM `#__menu` AS a WHERE ( $cids )";
        $database->setQuery($query);
        $items = $database->loadObjectList();

        $menuTypes = LAdminMenu::getMenuTypes();

        $menu = array();
        foreach ($menuTypes as $menuType) {
            $menu[] = LHtml::makeOption($menuType['id'], $menuType['type']);
        }
        $MenuList = LHtml::selectList($menu, 'menu', 'class="inputbox" size="10"', 'value', 'text', null);

        HTML_menusections::copyMenuHtml($cid, $MenuList, $items, $menutype);
    }

    /**
     * Перемещение пунктов меню в корзину
     */
    public static function removeMenu()
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');

        $_db = LCore::getDB();
        $sql
            = "SELECT *
                FROM `#__menu`
                WHERE `menutype` = ?
                    AND `published` != ?
                ORDER BY `menutype`, `parent`, `ordering`";
        $mitems = $_db->select($sql, $menutype, -2);

        $children = array();
        foreach ($cid as $id) {
            foreach ($mitems as $item) {
                if ($item['parent'] == $id) {
                    $children[] = $item['id'];
                }
            }
        }
        $list = self::getMenuChildrenRecurse($mitems, $children, $children);
        $list = array_merge($cid, $list);

        $ids = 'id=' . implode(' OR id=', $list);
        $ids = preg_replace('#[\d]+#', '?', $ids);
        $sql = "UPDATE `#__menu`
                SET `published` = ?,
                    `ordering` = ?,
                    `checked_out` = ?,
                    `checked_out_time` = ?
                WHERE ( $ids )";
        $_db->update($sql, -2, 0, 0, '0000-00-00 00:00:00', $list);

        $msg = _MOVED_TO_TRASH . ': ' . sizeof($list);
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype, $msg);
    }

    public static function saveMenu($task = 'save')
    {
        $row = new stdClass();
        $row->name = LCore::getParam($_REQUEST, 'name', '', 'sn');
        $row->componentid = LCore::getParam($_REQUEST, 'componentid', 0, 'i');
        $row->parent = LCore::getParam($_REQUEST, 'parent', 0, 'i');
        $row->ordering = LCore::getParam($_REQUEST, 'ordering', 0, 'i');
        $row->access = LCore::getParam($_REQUEST, 'access', 0, 'i');
        $row->published = LCore::getParam($_REQUEST, 'published', 0, 'i');
        $row->id = LCore::getParam($_REQUEST, 'id', 0, 'i');
        $row->type = LCore::getParam($_REQUEST, 'type', '', 'sn');
        $row->link = LCore::getParam($_REQUEST, 'link', '', 'sn');
        $row->menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $row->params = mosGetParam($_POST, 'params', '');
        if (isset($_POST['gid'])) {
            $row->params['group'] = LCore::getParam($_REQUEST, 'gid', 0, 'i');
        }
        if (is_array($row->params)) {
            $txt = array();
            foreach ($row->params as $k => $v) {
                $txt[] = "$k=$v";
            }
            $row->params = mosParameters::textareaHandling($txt);
        }

        $_db = LCore::getDB();
        if (!$row->id) {
            if (!$row->ordering or $row->ordering = 9999) {
                $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
                $sql
                    = "SELECT MAX(`ordering`)
                        FROM `#__menu`
                        WHERE `published` != ?
                            AND `menutype` = ?
                            AND `parent` = ?";
                $row->ordering = $_db->selectCell($sql, -2, $menutype, $row->parent) + 1;
            }
            $sql
                = "INSERT INTO `#__menu` (`menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `ordering`,
                                `access`, `params`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
            $_db->insert(
                $sql,
                $row->menutype,
                $row->name,
                $row->link,
                $row->type,
                $row->published,
                $row->parent,
                $row->componentid,
                $row->ordering,
                $row->access,
                $row->params
            );
            $row->id = $_db->getQueryInfo('insert_id');
        } else {
            $sql
                = 'UPDATE `#__menu`
                    SET `menutype` = ?,
                        `name` = ?,
                        `link` = ?,
                        `type` = ?,
                        `published` = ?,
                        `parent` = ?,
                        `componentid` = ?,
                        `ordering` = ?,
                        `access` = ?,
                        `params` =?
                    WHERE  `id`= ?;';
            $_db->update(
                $sql,
                $row->menutype,
                $row->name,
                $row->link,
                $row->type,
                $row->published,
                $row->parent,
                $row->componentid,
                $row->ordering,
                $row->access,
                $row->params,
                $row->id
            );
            Menus::checkIn($row->id);
        }

        $msg = _MENU_ITEM_SAVED;
        switch ($task) {
            case 'apply':
                mosRedirect('index2.php?option=com_menus&menutype=' . $row->menutype . '&task=edit&id=' . $row->id . '&hidemainmenu=1', $msg);
                break;

            case 'save':
                mosRedirect('index2.php?option=com_menus&menutype=' . $row->menutype, $msg);
                break;

            case 'save_and_new':
            default:
                mosRedirect('index2.php?option=com_menus&task=new&menutype=' . $row->menutype);
        }
    }

    public static function getMenu($id = 0)
    {
        // TODO Gold Dragon: необходимо объект перевести на массив
        $menu = new stdClass();
        $menu->id = null;
        $menu->menutype = null;
        $menu->name = null;
        $menu->link = null;
        $menu->type = null;
        $menu->published = null;
        $menu->parent = null;
        $menu->componentid = null;
        $menu->sublevel = null;
        $menu->ordering = null;
        $menu->checked_out = null;
        $menu->checked_out_time = null;
        $menu->pollid = null;
        $menu->browserNav = null;
        $menu->access = null;
        $menu->utaccess = null;
        $menu->params = null;
        if ($id) {
            $_db = LCore::getDB();
            $sql
                = "SELECT  `id`, `menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`,
                            `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`
                    FROM `#__menu`
                    WHERE `id` = ?";
            $menu = (object)$_db->selectRow($sql, $id);
        }
        return $menu;
    }

    public static function addMenuItem()
    {
        $types = array();

        //вычисляем каталоги
        require_once(_LPATH_COM . '/com_boss/boss.class.php');
        $directories = BossDirectory::getDirectories();

        // Список директорий
        $dirs = mosReadDirectory(_LPATH_ADMINISTRATOR . DS . 'components/com_menus');

        // load files for menu types
        foreach ($dirs as $dir) {
            // needed within menu type .php files
            $type = $dir;
            $dir = _LPATH_ADMINISTRATOR . DS . 'components/com_menus/' . $dir;
            if (is_dir($dir) and file_exists($dir . '/' . $type . '.menu.php')) {
                $types[]['type'] = $type;
            }
        }

        $i = 0;
        foreach ($types as $type) {
            // pulls name and description from menu type xml
            $row = self::ReadMenuXML($type['type']);
            $types[$i]['name'] = $row[0];
            $types[$i]['descrip'] = $row[1];
            $types[$i]['group'] = $row[2];
            $i++;
            unset($row);
        }

        $types_content = array();
        $types_other = array();

        $i = 0;
        foreach ($types as $type) {
            // Группа для БОССа
            if (strstr($type['group'], 'Boss')) {
                $types_content[] = $types[$i];
            }

            // Основная группа
            if (strstr($type['group'], 'Total')) {
                $types_total[] = $types[$i];
            }

            // Остальная группа
            if (strstr($type['group'], 'Other') || !$type['group']) {
                $types_other[] = $types[$i];
            }

            $i++;
        }
        $menutype = LCore::getParam($_REQUEST, 'menutype', '', 'sn');
        HTML_menusections::addMenuItemHtml($menutype, $types_content, $types_total, $types_other, $directories);
    }

    /**
     * Просмотр списка пунктов меню
     *
     * @modifocation 22.01.2014 Gold Dragon
     */
    public static function viewMenuItems()
    {
        $_db = LCore::getDB();
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        $search = LCore::getParam($_REQUEST, 'search', '', 'sn');
        $search_rows = array();
        if ($search) {
            $sql
                = "SELECT m.id
                    FROM `#__menu` AS m
                    WHERE m.menutype = ?
                        AND LOWER(m.name) LIKE ?";
            $search_rows = $_db->selectCol($sql, $menutype, "%" . trim(mb_strtolower($search)) . "%");
        }

        $sql
            = "SELECT m.*, u.name AS editor, g.name AS groupname, com.name AS com_name
                FROM `#__menu` AS m
                LEFT JOIN `#__users` AS u ON u.id = m.checked_out
                LEFT JOIN `#__groups` AS g ON g.id = m.access
                LEFT JOIN `#__components` AS com ON com.id = m.componentid
                    AND m.type = ?
                WHERE m.menutype = ?
                    AND m.published != ?
                ORDER BY parent, ordering";
        $rows = $_db->select($sql, 'components', $menutype, -2);

        // создание иерархии меню
        $children = array();
        // first pass - collect children
        foreach ($rows as $v) {
            $pt = $v['parent'];
            $list = (isset($children[$pt])) ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }

        // second pass - get an indent list of the items
        $list = TreeRecurse(0, '', array(), $children);
        // eventually only pick out the searched items.
        if ($search) {
            $list1 = array();

            foreach ($search_rows as $sid) {
                foreach ($list as $item) {
                    if ($item['id'] == $sid) {
                        $list1[] = $item;
                    }
                }
            }
            // replace full list with found items
            $list = $list1;
        }

        foreach ($list as $value) {
            switch ($value['type']) {
                case 'separator':
                case 'component_item_link':
                    break;

                case 'url':
                    if (preg_match('/index.php\?/i', $value['link'])) {
                    }
                    break;
                default:
                    break;
            }
            $list[$value['id']]['edit'] = '';
            $row = self::ReadMenuXML($value['type'], $value['com_name']);
            $list[$value['id']]['type'] = $row[0];
            if (!isset($list[$value['id']]['descrip'])) {
                $list[$value['id']]['descrip'] = $row[1];
            }
        }
        $row = $_db->selectRow("SELECT `type`, `title` FROM `#__menu_type` WHERE `id` = ?", $menutype);
        $menu_name = $row['type'] . ' : ' . $row['title'];
        HTML_menusections::showMenusections($list, $search, $menutype, $menu_name);
    }

    public static function cancelMenu()
    {
        $id = LCore::getParam($_REQUEST, 'id', 0, 'i');
        $menutype = LCore::getParam($_REQUEST, 'menutype', 0, 'i');
        self::checkIn($id);
        mosRedirect('index2.php?option=com_menus&menutype=' . $menutype);
    }

    public static function checkOut($user_id, $id)
    {
        // отключение блокировок
        if (LCore::getCfg('disable_checked_out')) {
            return true;
        }

        $time = date('Y-m-d H:i:s');

        $sql
            = "UPDATE `#__menu` SET `checked_out` = ?, `checked_out_time` = ?
                WHERE `id` = ?";
        LCore::getDB()->update($sql, $user_id, $time, $id);
    }

    public static function checkIn($id)
    {
        // отключение блокировок
        if (LCore::getCfg('disable_checked_out')) {
            return true;
        }

        $sql
            = "UPDATE `#__menu` SET `checked_out` = ?, `checked_out_time` = ?
                WHERE `id` = ?";
        LCore::getDB()->update($sql, 0, '0000-00-00 00:00:00', $id);
    }
}

