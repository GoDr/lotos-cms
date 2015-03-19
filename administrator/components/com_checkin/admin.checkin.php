<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * Checkin - Компонент разблокировки объектов
 *
 * @package   Checkin
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2014 Gold Dragon
 * @date      01.07.2014
 * @see       http://wiki.lotos-cms.ru/index.php/XMap
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// Подключаем файлы компонента
LCore::requireFilesCom('checkin', true);

$my = LCore::getUser();

// Проверка доступа к компоненту
if (!($acl->acl_check('administration', 'config', 'users', $my->usertype)) || $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_gdfeedback')) {
    mosRedirect('index2.php', _NOT_AUTH);
}

$task = LSef::getTask();
switch ($task) {
    case 'cancel':
        cancelMyCheckin();
        break;

    case 'checkin':
        checkin();
        break;

    case 'mycheckin':
        showMyCheckin();
        break;

    default:
        checkall();
        break;
}

function checkall()
{
    $database = database::getInstance();
    $nullDate = $database->getNullDate();
    $cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';
    ?>
    <table class="adminheading">
        <tr>
            <th class="checkin"><?php echo _GLOBAL_CHECKIN ?></th>
        </tr>
    </table>
    <table class="adminform">
        <tr>
            <th class="title"><?php echo _TABLE_IN_DB ?></th>
            <th class="title"><?php echo _OBJECT_COUNT ?></th>
            <th class="title"><?php echo _UNBLOCKED ?></th>
            <th class="title">&nbsp;</th>
        </tr>
        <?php
        $tables = $database->getTableList();
        $k = 0;
        foreach ($tables as $tn) {
            // make sure we get the right tables based on prefix
            if (!preg_match("/^" . LCore::getCfg('dbprefix') . "/i", $tn)) {
                continue;
            }
            $fields = $database->getTableFields(array($tn));

            $foundCO = isset($fields[$tn]['checked_out']);
            $foundCOT = isset($fields[$tn]['checked_out_time']);
            $foundE = isset($fields[$tn]['editor']);

            if ($foundCO && $foundCOT) {
                if ($foundE) {
                    $query = "SELECT checked_out, editor FROM $tn WHERE checked_out > 0";
                } else {
                    $query = "SELECT checked_out FROM $tn WHERE checked_out > 0";
                }
                $database->setQuery($query);
                $res = $database->query();
                $num = $database->getNumRows($res);

                if ($foundE) {
                    $query = "UPDATE $tn SET checked_out = 0, checked_out_time = " . $database->Quote($nullDate) . ", editor = NULL WHERE checked_out > 0";
                } else {
                    $query = "UPDATE $tn SET checked_out = 0, checked_out_time = " . $database->Quote($nullDate) . " WHERE checked_out > 0";
                }
                $database->setQuery($query);
                $res = $database->query();

                if ($res == 1) {
                    if ($num > 0) {
                        echo "<tr class=\"row$k\">";
                        echo "\n<td width=\"350\">" . _CHECHKED_TABLE . " - $tn</td>";
                        echo "\n<td width=\"150\">" . _UNBLOCKED . " - <b>$num</b></td>";
                        echo "\n<td width=\"100\" align=\"center\"><img src=\"" . $cur_file_icons_path . "/tick.png\" border=\"0\" alt=\"tick\" /></td>";
                        echo "\n<td>&nbsp;</td>";
                        echo "\n</tr>";
                    } else {
                        echo "<tr class=\"row$k\">";
                        echo "\n<td width=\"350\">" . _CHECHKED_TABLE . " - $tn</td>";
                        echo "\n<td width=\"150\">" . _UNBLOCKED . " - <b>$num</b></td>";
                        echo "\n<td width=\"100\">&nbsp;</td>";
                        echo "\n<td>&nbsp;</td>";
                        echo "\n</tr>";
                    }
                    $k = 1 - $k;
                }
            }
        }
        ?>
        <tr>
            <td colspan="4">
                <strong><?php echo _ALL_BLOCKED_IS_UNBLOCKED ?></strong>
            </td>
        </tr>
    </table>
<?php
}

/**
 * List the records
 *
 * @param string The current GET/POST option
 *
 * @modification : 18.06.2014 Gold Dragon
 */
function showMyCheckin()
{
    $sql = "SHOW TABLES FROM " . LCore::getCfg('db');
    $_db = LCore::getDB();;
    $rows = $_db->selectCol($sql);
    $list = array();
    foreach ($rows as $table) {
        $lf = $_db->selectCol('SHOW COLUMNS FROM ' . $table);
        if (array_search('checked_out', $lf) !== false) {
            $sql = 'SELECT tt.*, u.name AS username
                    FROM `' . $table . '` AS tt
                    LEFT JOIN `#__users` AS u ON u.id = tt.checked_out
                    WHERE tt.checked_out > ?
                    ';
            $rows2 = $_db->select($sql, 0);

            if (sizeof($rows2)) {
                foreach ($rows2 as $value) {

                    if (isset($value['name'])) {
                        $title = $value['name'];
                    }elseif(isset($value['title'])){
                        $title = $value['title'];
                    }else{
                        $title = _COM_CHCKN_UNKNOWN;
                    }

                    $username = ($value['username']) ? $value['username'] : _COM_CHCKN_UNKNOWN;

                    $cotime = (isset($value['checked_out_time'])) ? LibDateTime::formatDate($value['checked_out_time']) : _COM_CHCKN_UNKNOWN;

                    $list[] = array(
                        "table"  => $table,
                        "title"  => $title,
                        "name"   => $username,
                        "cotime" => $cotime,
                        "id"     => $value['id']);
                }
            }
        }
    }

    HTML_checkin::showlist($list);
}

function checkin()
{
    $_db = LCore::getDB();
    $table = LCore::getParam($_GET, 'table', '', 'sn');
    $id = LCore::getParam($_GET, 'id', '', 'i');
    $sql = 'UPDATE `' . $table . '` SET checked_out= ?, checked_out_time= ? WHERE id = ? AND checked_out > ?';
    $_db->update($sql, 0, '0000-00-00 00:00:00', $id, 0);

    mosRedirect('index2.php?option=com_checkin&task=mycheckin', _UNBLOCKED);
}

/**
 * Cancels editing and checks in the record
 *
 * @int the contact id
 */
function cancelMyCheckin()
{
    mosRedirect('index2.php');
}