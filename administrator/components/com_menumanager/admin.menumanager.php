<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Component
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      14.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Com_menumanager
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// Права на доступ к компоненту
$my = LCore::getUser();
if (!$acl->acl_check('administration', 'manage', 'users', $my->usertype, 'components', 'com_menumanager')) {
    mosRedirect('index2.php', _NOT_AUTH);
}

// Подключаем файы компонента
LCore::requireFilesCom('menumanager', true);

$task = LSef::getTask();

switch ($task) {
    case 'new':
    case 'edit':
        MenuManager::editMenu();
        break;

    case 'save':
        MenuManager::saveMenu();
        break;

    case 'delete':
        MenuManager::deleteMenu();
        break;

    case 'copy':
        MenuManager::copyMenu();
        break;

    case 'copymenu':
        MenuManager::copyMenuSave();
        break;

    case 'cancel':
    default:
        MenuManager::showMenu();
        break;
}

class MenuManager
{

    /**
     * Выводит список меню
     *
     * @modification 27.12.2013 Gold Dragon
     */
    public static function showMenu()
    {
        $_db = LCore::getDB();
        $result = array();

        $rows = $_db->select('SELECT * FROM `#__menu_type`;');

        for ($i = 0; $i < sizeof($rows); $i++) {
            // ID
            $result[$i]['id'] = $rows[$i]['id'];

            // Тип меню
            $result[$i]['type'] = $rows[$i]['type'];

            // Описание меню
            $result[$i]['title'] = $rows[$i]['title'];

            // Количество модулей всего
            $result[$i]['modules_total'] = $_db->selectCell('SELECT count(*) FROM `#__modules` WHERE `params` LIKE ?', '%menutype=' . $rows[$i]['type'] . '%');

            // Количество активных модулей
            $result[$i]['modules_activ'] = $_db->selectCell('SELECT count(*) FROM `#__modules` WHERE `published` = ? AND `params` LIKE ?', 1, '%menutype=' . $rows[$i]['type'] . '%');

            // Опубликовано ссылок
            $result[$i]['link_publ'] = $_db->selectCell('SELECT count(*) FROM `#__menu` WHERE `published` = ? AND `menutype` = ?', 1, $rows[$i]['id']);

            // Скрыто ссылок
            $result[$i]['link_unpub'] = $_db->selectCell('SELECT count(*) FROM `#__menu` WHERE `published` = ? AND `menutype` = ?', 0, $rows[$i]['id']);

            // Ссылок в корзине
            $result[$i]['link_trash'] = $_db->selectCell('SELECT count(*) FROM `#__menu` WHERE `published` = ? AND `menutype` = ?', -2, $rows[$i]['id']);
        }
        HTML_menumanager::show($result);
    }

    /**
     * Создание/редактирование типов меню
     *
     * @modification 14.01.2014 Gold Dragon
     */
    public static function editMenu()
    {
        $_db = LCore::getDB();

        $menu_id = LCore::getParam($_REQUEST, 'id', 0, 'i');

        $row = $_db->selectRow('SELECT `type`, `title` FROM `#__menu_type` WHERE `id` = ?;', $menu_id);

        $menu_type = LCore::getParam($row, 'type', '', 'sn');
        $menu_title = LCore::getParam($row, 'title', '', 'sn');

        HTML_menumanager::edit($menu_id, $menu_type, $menu_title);
    }

    /**
     * Сохраняем меню
     *
     * @modification 14.01.2014 Gold Dragon
     */
    public static function saveMenu()
    {
        $_db = LCore::getDB();
        $_lang = LLang::getLang('com.menumanager');
        $error = array();
        $msg = '';

        $menu_id = LCore::getParam($_REQUEST, 'id', 0, 'i');
        $menu_type = LCore::getParam($_REQUEST, 'menutype', '', 'sn');
        $b = preg_match('#[^a-z_]#is', $menu_type, $_tmp);
        $menu_title = strip_tags(LCore::getParam($_REQUEST, 'menutitle', '', 'sn'));

        if (empty($menu_type)) {
            $error[] = $_lang['PLEASE_ENTER_MENU_NAME'];
        } elseif ($b) {
            $error[] = $_lang['NO_QUOTES_IN_NAME'];
        }

        if (empty($menu_title)) {
            $error[] = $_lang['PLEASE_ENTER_MENU_DESC_NAME'];
        }

        if (sizeof($error)) {
            $msg = implode('. ', $error);
            mosRedirect('index2.php?option=com_menumanager&amp;task=edit', $msg);
        }

        if ($menu_id) { // редактирование

            $_db->update('UPDATE `#__menu_type` SET `type`= ?, `title`= ? WHERE  `id`=?;', $menu_type, $menu_title, $menu_id);
            mosRedirect('index2.php?option=com_menumanager', $_lang['MENU_UPDATED']);

        } else { // создание

            // Существует ли такое меню
            $_tmp = $_db->selectCell('SELECT COUNT(*) FROM `#__menu_type` WHERE `type` = ?', $menu_type);
            if ($_tmp and empty($error)) {
                $error[] = $_lang['MENU_NAME_TIP'];
            }

            if (sizeof($error)) {
                $msg = implode('. ', $error);
                mosRedirect('index2.php?option=com_menumanager&amp;task=edit', $msg);
            } else {
                $_db->insert('INSERT INTO `#__menu_type` (`type`, `title`) VALUES (?, ?);', $menu_type, $menu_title);
                $msg = $_lang['NEW_MENU_CREATED'];
                mosRedirect('index2.php?option=com_menumanager', $msg);
            }
        }
    }

    /**
     * Удаление меню
     *
     * @modification 15.01.2014 Gold Dragon
     */
    public static function deleteMenu()
    {
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $msg = '';
        if ($cid[0]) {
            $_db = LCore::getDB();
            $links = $_db->selectCell('SELECT COUNT(*) FROM `#__menu` WHERE `menutype` = ?', $cid[0]);
            $_lang = LLang::getLang('com.menumanager');
            if ($links) {
                $msg = $_lang['MENU_NOT_DELETE'];
            } else {
                $_db->delete('DELETE FROM `#__menu_type` WHERE  `id`=?;', $cid[0]);
                $msg = $_lang['MENU_DELETE'];
            }
        }
        mosRedirect('index2.php?option=com_menumanager', $msg);
    }

    /**
     * Копирование меню
     *
     * @modification 16.01.2014 Gold Dragon
     */
    public static function copyMenu()
    {
        $_db = LCore::getDB();
        $cid = mosGetParam($_REQUEST, 'cid', '');
        $id = LCore::getParam($_GET, 'id', 0, 'i');

        if (isset($cid[0]) and $cid[0]) {
            $id = $cid[0];
        }

        $msg = '';
        if ($id and $_db->selectCell('SELECT `id` FROM `#__menu_type` WHERE `id` = ?', $id)) {
            $sql
                = 'SELECT m.id, m.name, m.link
                    FROM `#__menu` AS m
                    WHERE m.menutype = ?';
            $items = $_db->select($sql, $id);
            $menu_type = $_db->selectCell('SELECT `type` FROM `#__menu_type` WHERE `id` = ?', $id);
            HTML_menumanager::copy($id, $menu_type, $items);
        } else {
            mosRedirect('index2.php?option=com_menumanager', $msg);
        }
    }

    /**
     * Создание копии меню и пунктов меню
     */
    public static function copyMenuSave()
    {
        $_db = LCore::getDB();
        $menu_id = LCore::getParam($_REQUEST, 'menuid', 0, 'i');

        if ($menu_id and $_db->selectCell('SELECT `id` FROM `#__menu_type` WHERE `id` = ?', $menu_id)) {
            $_lang = LLang::getLang('com.menumanager');
            $error = array();

            $menu_name = LCore::getParam($_REQUEST, 'menu_name', '', 'sn');
            $b = preg_match('#[^a-z_]#is', $menu_name, $_tmp);

            if (empty($menu_name)) {
                $error[] = $_lang['PLEASE_ENTER_MENU_NAME'];
            } elseif ($b) {
                $error[] = $_lang['NO_QUOTES_IN_NAME'];
            }

            if (sizeof($error)) {
                $msg = implode('. ', $error);
                mosRedirect('index2.php?option=com_menumanager&amp;task=copy&amp;id=' . $menu_id, $msg);
            } else {
                // создаём новое меню
                $menu_tytle = $_db->selectCell('SELECT `title` FROM `#__menu_type` WHERE `id` = ?;', $menu_id);
                $_db->insert('INSERT INTO `#__menu_type` (`type`, `title`) VALUES (?, ?)', $menu_name, $menu_tytle);
                $new_id = $_db->getQueryInfo('insert_id');

                // копинуем пункт меню
                $mids = (isset($_REQUEST['mids'])) ? $_REQUEST['mids'] : null;
                if ($mids and is_array($mids)) {
                    foreach ($mids as $value) {
                        $sql
                            = "SELECT `name`, `link`, `type`, `published`, `parent`, `componentid`, `sublevel`, `ordering`,
                                       `checked_out`, `checked_out_time`, `pollid`, `browserNav`, `access`, `utaccess`, `params`
                                FROM `#__menu`
                                WHERE `menutype` = ?;";
                        $rows = $_db->select($sql, $value);
                        foreach ($rows as $row) {
                            $sql
                                = "INSERT INTO `#__menu` (`menutype`, `name`, `link`, `type`, `published`, `parent`, `componentid`,
                                            `sublevel`, `ordering`, `checked_out`, `checked_out_time`, `pollid`, `browserNav`,
                                            `access`, `utaccess`, `params`)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                            $_db->insert(
                                $sql,
                                $new_id,
                                $row['name'],
                                $row['link'],
                                $row['type'],
                                $row['published'],
                                $row['parent'],
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
                }
                mosRedirect('index2.php?option=com_menumanager', $_lang['MENU_COPY_OK']);
            }
        } else {
            mosRedirect('index2.php?option=com_menumanager&amp;task=copy');
        }
    }
}
