<?php
/**
 * @package   Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */

defined('_LINDEX') or die('STOP in file ' . __FILE__);
require_once(_LPATH_ROOT . DS . 'components' . DS . 'com_boss' . DS . 'boss.tools.php');
require_once($mainframe->getPath('front_html'));
require_once($mainframe->getPath('class'));

//if(!boss_helpers::is_ajax()){
//    die('Is Not Ajax Query');
//}

$act = mosGetParam($_REQUEST, 'act', '');
$task = mosGetParam($_REQUEST, 'task', '');
$directory = mosGetParam($_REQUEST, 'directory', 0);
boss_helpers::loadBossLang($directory);

switch ($act) {
    case 'calendar':
        calendar();
        break;
    case "upload_image":
        boss_helpers::upload_image($directory);
        break;

    case "plugins":
        switch ($task) {
            case "run_plugins_func":
                BossPlugins::run_plugins_func($directory);
                break;
            default :
                break;
        }
        break;

    case "upload_file":
        $folder = mosGetParam($_REQUEST, 'folder', '');
        boss_helpers::upload_file($directory, $folder);
        break;

    case "delete_file":
        boss_helpers::delete_file($directory);
        break;

    default :
        break;
}

function calendar()
{
    $_lang = LLang::getLang('mod.calendar');
    $_db = LCore::getDB();

    $id = LCore::getParam($_REQUEST, 'id', 0, 'i');
    $val = LCore::getParam($_REQUEST, 'val', '', 'sn');

    // получение настроек модуля
    $row = $_db->selectCell("SELECT `params` FROM `#__modules` WHERE `id` = ?", $id);
    $params = mosParameters::parse($row, false, true);

    $year = LibDateTime::formatDate($val, 'Y');
    $year_prev = (int)$year - 1;
    $year_next = (int)$year + 1;

    $month = LibDateTime::formatDate($val, 'm');
    $month_prev = (int)$month - 1;
    if ($month_prev < 1) {
        $month_prev = 12;
    }
    $month_next = (int)$month + 1;
    if ($month_next > 12) {
        $month_next = 1;
    }

    $today = date('d.m.Y');

    $count_day = (int)LibDateTime::formatDate($val, 't');
    $week_start = (int)LibDateTime::formatDate($year . '-' . $month . '-01', 'N');

    if (trim($params['catid'])) {
        $_tmp = preg_replace('#[,]+[\s]*[\d]+#', ' OR cch.category_id = ?', $params['catid']);
        $_tmp = preg_replace('#[\d]+#', '?', $_tmp);
        $catid = " AND ( cch.category_id = " . $_tmp . " )";
        $catval = explode(',', $params['catid']);
    } else {
        $catid = '';
        $catval = array();
    }

    $sql = 'SELECT cn.id, cn.name, DAY(cn.date_created) AS `day`
                FROM `#__boss_' . $params['directory'] . '_contents` AS cn
                LEFT JOIN `#__boss_' . $params['directory'] . '_content_category_href` AS cch ON cn.id = cch.content_id
                WHERE YEAR(cn.date_created) = ?
                    AND MONTH(cn.date_created) = ?
                    AND published = ?
                    ' . $catid . '
                ';
    $rows = $_db->select($sql, $year, $month, 1, $catval);
    $content = array();
    foreach ($rows as $value) {
        $content[$value['day']][] = "&bull;&nbsp;" . $value['name'];
    }

    // подключение SEF
    LSef::getInstance(LCore::getCfg('sef'), LCore::getCfg('com_frontpage_clear'));

    if ($params['form']) {
        $week_end = (int)LibDateTime::formatDate($year . '-' . $month . '-' . $count_day, 'N');

        $count = $count_day + ($week_start - 1) + (7 - $week_end);
        ?>
        <table class="clndr">
            <thead>
            <tr class="clndr_today">
                <th colspan="7"><?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month] . ', ' . $year; ?></th>
            </tr>
            <tr class="clndr_nav">
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month] . ', ' . $year_prev; ?>"><a
                        onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $year_prev . '-' . $month . '-01'; ?>')">&laquo;&laquo;</a></th>
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month_prev] . ', ' . $year; ?>"><a onclick="calendar_nav('<?php echo $id; ?> ', '<?php echo $year . '-' . $month_prev . '-01'; ?>')">&laquo;</a>
                </th>
                <th title="<?php echo LibDateTime::getDateName(null, 2); ?>" colspan="3"><a onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $today; ?>')"><?php echo $_lang['MOD_TODAY']; ?></a></th>
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month_next] . ', ' . $year; ?>"><a onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $year . '-' . $month_next . '-01'; ?>')">&raquo;</a></th>
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month] . ', ' . $year_next; ?>"><a
                        onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $year_next . '-' . $month . '-01'; ?>')">&raquo;&raquo;</a></th>
            </tr>
            <tr class="clnd_weeks">
                <th><?php echo $_lang['MOD_WEEK_S'][1]; ?></th>
                <th><?php echo $_lang['MOD_WEEK_S'][2]; ?></th>
                <th><?php echo $_lang['MOD_WEEK_S'][3]; ?></th>
                <th><?php echo $_lang['MOD_WEEK_S'][4]; ?></th>
                <th><?php echo $_lang['MOD_WEEK_S'][5]; ?></th>
                <th><?php echo $_lang['MOD_WEEK_S'][6]; ?></th>
                <th><?php echo $_lang['MOD_WEEK_S'][7]; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $a = 1;
            $b = 1;

            for ($i = 1; $i <= $count; $i++) {
                if ($a == 1) {
                    echo '<tr>';
                }

                if ($i < $week_start) {
                    echo '<td>&nbsp</td>';
                } elseif ($b <= $count_day) {
                    $class_a = ($a == 6 or $a == 7) ? 'free' : '';
                    if (isset($content[$b])) {
                        $title = 'title="' . LibText::quoteReplace(implode('<br>', $content[$b])) . '"';
                        $c = '<a href="' . LSef::getUrlToSef(_LPATH_SITE . '/index.php?option=com_boss&amp;task=calendar&amp;year=' . $year . '&amp;month=' . $month . '&amp;day=' . $b . '&amp;modid=' . $id) . '">' . $b
                            . '</a>';
                    } else {
                        $title = '';
                        $c = $b;
                    }
                    if ($today == $b . '.' . $month . '.' . $year) {
                        echo '<td ' . $title . ' class="clndr_day ' . $class_a . '">' . $c . '</td>';
                    } else {
                        echo '<td ' . $title . ' class="clndr_none ' . $class_a . '">' . $c . '</td>';
                    }
                    $b++;
                } else {
                    echo '<td>&nbsp</td>';
                }

                $a++;

                if ($a > 7) {
                    echo '</tr>';
                    $a = 1;
                }
            }
            ?>
            </tbody>
        </table>
    <?php
    } else {
        $th = array();
        $td = array();
        for ($i = 1; $i <= $count_day; $i++) {
            $class_a = ($week_start == 6 or $week_start == 7) ? ' class="free"' : '';

            if (isset($content[$i])) {
                $title = ' title="' . LibText::quoteReplace(implode('<br>', $content[$i])) . '"';
                $c = '<a href="' . LSef::getUrlToSef(_LPATH_SITE . '/index.php?option=com_boss&amp;task=calendar&amp;year=' . $year . '&amp;month=' . $month . '&amp;day=' . $i . '&amp;modid=' . $id) . '">' . $i . '</a>';
            } else {
                $title = '';
                $c = $i;
            }

            $th[] = '<th ' . $class_a . '>' . $_lang['MOD_WEEK_S'][$week_start] . '</th>';
            $td[] = '<td ' . $class_a . $title . '>' . $c . '</td>';
            $week_start++;
            if ($week_start > 7) {
                $week_start = 1;
            }
        }
        ?>
        <table class="clndr">
            <tr class="clndr_today">
                <th colspan="5"><?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month] . ', ' . $year; ?></th>
            </tr>
            <tr class="clndr_nav">
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month] . ', ' . $year_prev; ?>"><a
                        onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $year_prev . '-' . $month . '-01'; ?>')">&laquo;&laquo;</a></th>
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month_prev] . ', ' . $year; ?>"><a onclick="calendar_nav('<?php echo $id; ?> ', '<?php echo $year . '-' . $month_prev . '-01'; ?>')">&laquo;</a>
                </th>
                <th title="<?php echo LibDateTime::getDateName(null, 2); ?>" colspan="3"><a onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $today; ?>')"><?php echo $_lang['MOD_TODAY']; ?></a></th>
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month_next] . ', ' . $year; ?>"><a onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $year . '-' . $month_next . '-01'; ?>')">&raquo;</a></th>
                <th title="<?php echo $_lang['MOD_MONTH_NAME_IU'][(int)$month] . ', ' . $year_next; ?>"><a
                        onclick="calendar_nav('<?php echo $id; ?>', '<?php echo $year_next . '-' . $month . '-01'; ?>')">&raquo;&raquo;</a></th>
            </tr>
        </table>
        <table>
            <tr class="clnd_weeks">
                <?php echo implode($th); ?>
            </tr>
            <tr class="clnd_day">
                <?php echo implode($td); ?>
            </tr>
        </table>
    <?php
    }
    ?>
    <script>
        $("#clndr_<?php echo $id; ?> td, #clndr_<?php echo $id; ?> th").easyTooltip({
            xOffset: <?php echo $params['xoffset']; ?>,
            yOffset: <?php echo $params['yoffset']; ?>,
            tooltipId: "easy_tooltip_<?php echo $id; ?>"
        });
    </script>
<?php
}