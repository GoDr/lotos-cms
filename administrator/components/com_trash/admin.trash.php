<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Menu
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Com_trash
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// Подключаем файы компонента
LCore::requireFilesCom('trash', true);

$my = LCore::getUser();
// ensure user has access to this function
if (!($acl->acl_check('administration', 'manage', 'users', $my->usertype, 'components', 'com_trash'))) {
    mosRedirect('index2.php', _NOT_AUTH);
}

$task = LSef::getTask();

switch ($task) {
    case 'deleteconfirm':
        $mid = josGetArrayInts('mid');
        Trash::viewdeleteTrash($mid);
        break;

    case 'delete':
        $cid = josGetArrayInts('cid');
        Trash::deleteTrash($cid);
        break;

    case 'deleteall':
        Trash::clearTrash();
        break;

    case 'restoreconfirm':
        $mid = josGetArrayInts('mid');
        Trash::viewrestoreTrash($mid);
        break;

    case 'restore':
        $cid = josGetArrayInts('cid');
        Trash::restoreTrash($cid);
        break;

    default:
        Trash::viewTrash();
        break;
}

class Trash
{
    public static function viewTrash()
    {

        $database = database::getInstance();
        $mainframe = MainFrame::getInstance();

        $limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', LCore::getCfg('list_limit')));
        $limitstart = intval($mainframe->getUserStateFromRequest("viewcom_trashlimitstart", 'limitstart', 0));

        MainFrame::addLib('pagenavigation');

        $sql
            = "SELECT count(*)
                FROM `#__menu` AS m
                LEFT JOIN `#__users` AS u ON u.id = m.checked_out
                WHERE m.published = -2";
        $database->setQuery($sql);
        $total = $database->loadResult();

        $pageNav = new mosPageNav($total, $limitstart, $limit);

        $query = "SELECT m.name AS title, m.menutype AS sectname, m.type AS catname, m.id AS id"
            . "\n FROM #__menu AS m"
            . "\n LEFT JOIN #__users AS u ON u.id = m.checked_out"
            . "\n WHERE m.published = -2"
            . "\n ORDER BY m.menutype, m.ordering, m.ordering, m.name";
        $database->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $content = $database->loadObjectList();

        HTML_trash::showList($content, $pageNav);
    }

    public static function viewdeleteTrash($mid)
    {

        $database = database::getInstance();
        if (!in_array(0, $mid)) {
            mosArrayToInts($mid);
            $mids = 'a.id=' . implode(' OR a.id=', $mid);
            $query = "SELECT a.name FROM `#__menu` AS a WHERE ( " . $mids . " ) ORDER BY a.name";
            $database->setQuery($query);
            $items = $database->loadObjectList();
            $id = $mid;
            $type = 'menu';
        }
        HTML_trash::showDelete($id, $items, $type);
    }


    public static function deleteTrash($cid)
    {
        josSpoofCheck();

        $total = count($cid);
        $_db = LCore::getDB();
        foreach ($cid as $id) {
            $_db->delete('DELETE FROM `#__menu` WHERE  `id`= ?;', $id);
        }

        $msg = $total . " " . _OBJECTS_DELETED;
        mosRedirect('index2.php?option=com_trash', $msg);
    }

    public static function clearTrash()
    {
        $_db = LCore::getDB();
        $cid = $_db->selectCol("SELECT `id` FROM `#__menu` WHERE `published` = ?;", -2);
        self::deleteTrash($cid);
    }

    public static function viewrestoreTrash($mid)
    {
        $database = database::getInstance();

        if (!in_array(0, $mid)) {
            mosArrayToInts($mid);
            $mids = 'a.id=' . implode(' OR a.id=', $mid);
            $query = "SELECT a.name" . "\n FROM #__menu AS a" . "\n WHERE ( $mids )" . "\n ORDER BY a.name";
            $database->setQuery($query);
            $items = $database->loadObjectList();
            $id = $mid;
            $type = "menu";
        }

        HTML_trash::showRestore($id, $items, $type);
    }


    public static function restoreTrash($cid)
    {
        $total = count($cid);
        $_db = LCore::getDB();

        foreach ($cid as $id) {
            $parent = $_db->selectCell("SELECT `parent` FROM `#__menu` WHERE `id` = ?;", $id);
            $check =  $_db->selectCell("SELECT `id` FROM `#__menu` WHERE `id` = ? AND (published = ? OR published = ?);", $parent, 0, 1);

            if(empty($check)){
                $_db->update("UPDATE `#__menu` SET parent = ?, published = ?, ordering = ? WHERE id = ?;", 0, 0, 9999, $id);
            }else{
                $_db->update("UPDATE `#__menu` SET published = ?, ordering = ? WHERE id = ?;", 0, 9999, $id);
            }
        }

        $msg = $total . " " . _OBJECTS_RESTORED;
        mosRedirect("index2.php?option=com_trash", $msg);
    }
}

