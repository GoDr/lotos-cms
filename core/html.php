<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS CORE
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LHtml
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */
class LHtml
{
    /** @var array массив для исключения повторного подключения JS-файлов */
    private static $_js_replay = array();

    /** @var array массив для исключения повторного подключения CSS-файлов */
    private static $_css_replay = array();

    /**
     * Проверка подключин ли JS-файл
     *
     * @param $str : путь / имя файла
     *
     * @return bool : false - файл подключен, true - файл не подключен
     *
     * @modification 05.11.2013 Gold Dragon
     */
    public static function checkReplayJs($str)
    {
        // Удаляем избыточный код и нормализуем данные
        $str = str_replace(_LPATH_SITE, '', $str);
        $str = str_replace('\\', '/', $str);
        $str = preg_replace("#^/#", "", $str);

        $str = md5(mb_strtolower($str));
        if (in_array($str, self::$_js_replay)) {
            return false;
        } else {
            self::$_js_replay[] = $str;

            return true;
        }
    }

    /**
     * Проверка полкючин ли CSS-файл
     *
     * @param $str : путь / имя файла
     *
     * @return bool : false - файл подключен, true - файл не подключен
     *
     * @modification 05.11.2013 Gold Dragon
     */
    public static function checkReplayCss($str)
    {
        // Удаляем избыточный код и нормализуем данные
        $str = str_replace(_LPATH_SITE, '', $str);
        $str = str_replace('\\', '/', $str);
        $str = preg_replace("#^/#", "", $str);

        $str = md5(mb_strtolower($str));
        if (in_array($str, self::$_css_replay)) {
            return false;
        } else {
            self::$_css_replay[] = $str;

            return true;
        }
    }

    /**
     * Подключение библиотеки всплывающих подсказок
     *
     * @param bool $ret : true - загружается в BODY, false (или не задан) - в HEAD (по умолчанию)
     *
     * @modification 05.11.2013 Gold Dragon
     */
    public static function loadOverlib($ret = false)
    {
        if ($ret and self::checkReplayJs('/includes/js/overlib_full.js')) {
            echo '<script src="' . _LPATH_SITE . '/includes/js/overlib_full.js"></script>';
        } else {
            $mainframe = MainFrame::getInstance();
            $mainframe->addJS(_LPATH_SITE . '/includes/js/overlib_full.js');
        }
    }

    /**
     * Подключение библиотеки всплывающих подсказок
     *
     * @param bool $ret : true - загружается в BODY, false (или не задан) - в HEAD (по умолчанию)
     *
     * @modification 18.12.2014 Gold Dragon
     */
    public static function loadToolTip($ret = false)
    {
        self::loadJqueryPlugins('easytooltip/jquery.easytooltip', $ret, true);
    }

    /**
     * добавление js файлов в шапку или футер страницы (статаналог MainFrame->addJS)
     *
     * @param string $path   : путь до файла
     * @param string $footer :
     *                       'js' - скрипт будет добавлен в $mainfrane->_footer['js'] (первый этап вывода футера)
     *                       'custom' - скрипт будет добавлен в $mainfrane->_footer['custom'] (второй этап вывода футера)
     * @param bool   $ret    : true - загружается в BODY, false (или не задан) - в HEAD (по умолчанию)
     *
     * @modofication 27.02.2014 Gold Dragon
     */
    public static function addJS($path, $footer = '', $ret = false)
    {
        MainFrame::getInstance()->addJS($path, $footer, $ret);
    }

    /**
     * Добавляет пользовательский javascript-код
     *
     * @param string $str     : javascript-код
     * @param bool   $preload : выполняется, когда объектная модель готова к использованию
     */
    public static function addJSCustom($str, $preload = true)
    {
        $result = '<script>';
        $result .= ($preload) ? '$(function(){' . $str . '});' : '$str';
        $result .= '</script>';
        echo $result;
    }

    /**
     * Добавление css файлов в шапку или футер страницы (статаналог MainFrame->addCSS)
     *
     * @param string $path : путь до файла
     * @param bool   $ret  : true - загружается в BODY, false (или не задан) - в HEAD (по умолчанию)
     *
     * @modification 27.02.2014 Gold Dragon
     */
    public static function addCSS($path, $ret = false)
    {
        MainFrame::getInstance()->addCSS($path, $ret);
    }

    /**
     * Подключение JS файлов Календаря
     *
     * @modification 14.11.2013 Gold Dragon
     */
    public static function loadCalendar()
    {
        $mainframe = MainFrame::getInstance();
        $mainframe->addCSS(_LPATH_SITE . '/includes/js/calendar/calendar.css');
        $mainframe->addJS(_LPATH_SITE . '/includes/js/calendar/calendar.js');
        $_lang_file = _LPATH_ROOT . '/includes/js/calendar/lang/calendar-' . _LANGUAGE . '.js';
        $_lang_file = (is_file($_lang_file)) ? _LPATH_SITE . '/includes/js/calendar/lang/calendar-' . _LANGUAGE . '.js' : _LPATH_SITE . '/includes/js/calendar/lang/calendar-ru.js';
        $mainframe->addJS($_lang_file);
    }

    /**
     * Подключение Fullajax
     *
     * @param bool $ret : true - загружается в BODY, false (или не задан) - в HEAD (по умолчанию)
     *
     * @modification 05.11.2013 Gold Dragon
     */
    public static function loadFullajax($ret = false)
    {
        if ($ret and self::checkReplayJs('/includes/js/fullajax/fullajax.js')) {
            echo '<script src="' . _LPATH_SITE . '/includes/js/fullajax/fullajax.js"></script>';
        } else {
            $mainframe = MainFrame::getInstance();
            $mainframe->addJS(_LPATH_SITE . '/includes/js/fullajax/fullajax.js');
        }
    }

    /**
     * Подключение Jquery
     *
     * @param bool $ret : true - загружается в BODY, false (или не задан) - в HEAD (по умолчанию)
     *
     * @modification 05.11.2013 Gold Dragon
     */
    public static function loadJquery($ret = false)
    {
        if ($ret and self::checkReplayJs('/includes/js/jquery/jquery.js')) {
            echo '<script src="' . _LPATH_SITE . '/includes/js/jquery/jquery.js"></script>';
        } else {
            $mainframe = MainFrame::getInstance();
            $mainframe->addJS(_LPATH_SITE . '/includes/js/jquery/jquery.js');
        }
    }

    /**
     * Подключение расширений Jquery
     *
     * @param string $name   : имя библиотеки
     * @param bool   $ret    : true - загружается в BODY, false - в HEAD (по умолчанию)
     * @param bool   $css    : true - загружается CSS-файл расширения, false - не загружать (по умолчанию)
     * @param string $footer : место загрузка скрипта
     * @param string $folder
     *
     * @example      LHtml::loadJqueryPlugins('fancybox/jquery.fancybox', false, true);
     *
     * @modification 05.11.2013 Gold Dragon
     *
     * @TODO         Gold Dragon: Необходимо пересмотреть пути при подключении, т.е. разделить ИМЯ на ПАПКА и ИМЯ и избавиться от $folder
     */
    public static function loadJqueryPlugins($name, $ret = false, $css = false, $footer = '', $folder = '')
    {
        self::loadJquery();
        $name = trim($name);
        $folder = (!empty($folder)) ? trim($folder) . '/' : '';

        $path = _LPATH_SITE . '/includes/js/jquery/plugins/' . $folder . $name;

        if ($ret and self::checkReplayJs($path . '.js')) {
            $GLOBALS['_MOS_OPTION']['jqueryplugins'] = $GLOBALS['_MOS_OPTION']['jqueryplugins'] . '<script src="' . $path . '.js"></script>';
            if ($css and self::checkReplayCss($path . '.css')) {
                $GLOBALS['_MOS_OPTION']['jqueryplugins'] = $GLOBALS['_MOS_OPTION']['jqueryplugins'] . '<link type="text/css" rel="stylesheet" href="' . $path . '.css" />';
            }
        } else {
            $mainframe = MainFrame::getInstance();
            $mainframe->addJS($path . '.js', $footer);
            if ($css) {
                $mainframe->addCSS($path . '.css');
            }
        }
    }

    /**
     * Подключение файла Jquery UI
     *
     * @param bool $ret : true - загружается в BODY, false - в HEAD (по умолчанию)
     */
    public static function loadJqueryUI($ret = false)
    {
        if ($ret and self::checkReplayJs('/includes/js/jquery/ui.js')) {
            echo '<script src="' . _LPATH_SITE . '/includes/js/jquery/ui.js"></script>';
            echo '<link type="text/css" rel="stylesheet" href="' . _LPATH_SITE . '/includes/js/jquery/ui/ui.css" />';
        } else {
            $mainframe = MainFrame::getInstance();
            $mainframe->addJS(_LPATH_SITE . '/includes/js/jquery/ui.js');
            $mainframe->addCSS(_LPATH_SITE . '/includes/js/jquery/ui/ui.css');
        }
    }

    /**
     * Вывод иконок - описание статуса публикации материала
     */
    public static function ContentLegend()
    {
        $cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';
        ?>
        <table cellspacing="0" cellpadding="4" border="0" align="center">
            <tr align="center">
                <td><img src="<?php echo $cur_file_icons_path; ?>/publish_y.png" alt="<?php echo _PUBLISHED_VUT_NOT_ACTIVE ?>" border="0"/></td>
                <td><?php echo _PUBLISHED_VUT_NOT_ACTIVE ?> |</td>
                <td><img src="<?php echo $cur_file_icons_path; ?>/publish_g.png" alt="<?php echo _PUBLISHED_AND_ACTIVE ?>" border="0"/></td>
                <td><?php echo _PUBLISHED_AND_ACTIVE ?> |</td>
                <td><img src="<?php echo $cur_file_icons_path; ?>/publish_r.png" alt="<?php echo _PUBLISHED_BUT_DATE_EXPIRED ?>" border="0"/></td>
                <td><?php echo _PUBLISHED_BUT_DATE_EXPIRED ?> |</td>
                <td><img src="<?php echo $cur_file_icons_path; ?>/publish_x.png" alt="<?php echo _UNPUBLISHED ?>" border="0"/></td>
                <td><?php echo _UNPUBLISHED ?></td>
            </tr>
        </table>
    <?php
    }

    /**
     * Новая замена checkedOut
     *
     * @param     $row
     * @param int $overlib
     *
     * @return string
     */
    public static function checkedOutL($row, $overlib = 1)
    {
        $cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';
        $hover = '';
        if ($overlib) {
            $date = LibDateTime::formatDate($row['checked_out_time'], 'A, d B Y');
            $time = LibDateTime::formatDate($row['checked_out_time'], 'H:M');
            $editor = addslashes(htmlspecialchars(html_entity_decode($row['editor'], ENT_QUOTES, 'UTF-8')));
            $checked_out_text = '<table>';
            $checked_out_text .= '<tr><td>' . $editor . '</td></tr>';
            $checked_out_text .= '<tr><td>' . $date . '</td></tr>';
            $checked_out_text .= '<tr><td>' . $time . '</td></tr>';
            $checked_out_text .= '</table>';
            $hover = 'onMouseOver="return overlib(\'' . $checked_out_text . '\', CAPTION, \'' . _CHECKED_OUT . '\', BELOW, RIGHT);" onMouseOut="return nd();"';
        }
        $checked = '<img src="' . $cur_file_icons_path . '/checked_out.png" ' . $hover . '/>';

        return $checked;
    }

    /**
     * Добавлеет всплывающую подсказку
     *
     * @param string $id      : идентификатор тега, при наведении на который будет отображаться подсказка
     *                        если не пустой, то будет выводиться сведения из title
     *                        если '', то будет выводиться иконка или ссылка и сообщение $content
     * @param string $content : сообщение
     * @param string $image   : изображение кнопки
     * @param string $text    : тест ссылки
     * @param array  $params  : параметры настройки скрипта
     *                        xOffset: смещение по оси Х
     *                        yOffset: смещение по оси Y
     *                        tooltipId: альтернативный стиль (предустановленные: easyTooltip, easyTooltip2, easyTooltip3)
     *                        useElement: альтернативный блок с подсказкой
     *
     * @return string
     */
    public static function toolTip($id = '', $content = '', $image = 'tooltip.png', $text = '[?]', $params = array())
    {
        $params_js = '';
        if (sizeof($params)) {
            $tmp = array();
            if (isset($params['xoffset']) and !empty($params['xoffset'])) {
                $tmp[] = 'xOffset: ' . intval($params['xoffset']);
            }

            if (isset($params['yoffset']) and !empty($params['yoffset'])) {
                $tmp[] = 'yOffset: ' . intval($params['yoffset']);
            }

            if (isset($params['tooltipid']) and !empty($params['tooltipid'])) {
                $tmp[] = 'tooltipId: "' . trim($params['tooltipid']) . '"';
            }

            if (isset($params['useelement']) and !empty($params['useelement'])) {
                $tmp[] = 'useElement: "' . trim($params['useelement']) . '"';
            }
            $params_js = '{' . implode(',', $tmp) . '}';
        }

        if ($id == '') {
            $id = 'easytooltip' . mt_rand(10000, 99999);
            if ($image == '') {
                echo '<a id="' . $id . '" title="' . $content . '">' . $text . '</a>';
            } else {
                echo '<img id="' . $id . '" title="' . $content . '" src="' . _LPATH_SITE . '/includes/js/ThemeOffice/' . $image . '" />';
            }
        }
        $result = '$("#' . $id . '").easyTooltip(' . $params_js . ');';
        self::addJSCustom($result);
    }

    /*************************************************************************************/
    /**        Необходимо проверить и модернизировать                                   **/
    /*************************************************************************************/

    /* подключение codepress */

    public static function loadCodepress()
    {
        if (!defined('_CODEPRESS_LOADED')) {
            define('_CODEPRESS_LOADED', 1);
            $mainframe = MainFrame::getInstance();
            $mainframe->addJS(_LPATH_SITE . '/includes/js/codepress/codepress.js');
            ?>
            <script>
                CodePress.run = function () {
                    CodePress.path = '<?php echo _LPATH_SITE ?>/includes/js/codepress/';
                    t = document.getElementsByTagName('textarea');
                    for (var i = 0, n = t.length; i < n; i++) {
                        if (t[i].className.match('codepress')) {
                            id = t[i].id;
                            t[i].id = id + '_cp';
                            eval(id + ' = new CodePress(t[i])');
                            t[i].parentNode.insertBefore(eval(id), t[i]);
                        }
                    }
                }
                if (window.attachEvent) {
                    window.attachEvent('onload', CodePress.run);
                } else {
                    window.addEventListener('DOMContentLoaded', CodePress.run, false);
                }</script>
        <?php
        }
    }

    /**
     * Новый метод AccessProcessing: вместо объектов массив
     *
     * @param      $row
     * @param      $i
     * @param null $ajax
     *
     * @return string
     */
    public static function AccessProcessingL($row, $i, $ajax = null)
    {
        $option = strval(mosGetParam($_REQUEST, 'option', ''));
        if (!$row['access']) {
            $color_access = 'style="color: green;"';
            $task_access = 'accessregistered';
        } elseif ($row['access'] == 1) {
            $color_access = 'style="color: red;"';
            $task_access = 'accessspecial';
        } else {
            $color_access = 'style="color: black;"';
            $task_access = 'accesspublic';
        }
        if (!$ajax) {
            $href = '<a href="javascript: void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task_access . '\')" ' . $color_access . '>' . $row['groupname'] . '</a>';
        } else {
            $href = '<a href="#" onclick="ch_access(' . $row['id'] . ',\'' . $task_access . '\',\'' . $option . '\');" ' . $color_access . '>' . $row['groupname'] . '</a>';
        }

        return $href;
    }

    /**
     *
     *
     * @param $row
     * @param $i
     *
     * @return string
     */
    public static function CheckedOutProcessingL($row, $i)
    {
        $my = LCore::getUser();
        if ($row['checked_out']) {
            $checked = self::checkedOutL($row);
        } else {
            $checked = LHtml::idBox($i, $row['id'], ($row['checked_out'] && $row['checked_out'] != $my->id));
        }

        return $checked;
    }

    /*
	 * Special handling for newfeed encoding and possible conflicts with page encoding and PHP version
	 * Added 1.0.8
	 * Static Function
	 */

    public static function newsfeedEncoding($rssDoc, $text, $utf8enc = null)
    {

        if (!defined('_JOS_FEED_ENCODING')) {
            // determine encoding of feed
            $feed = $rssDoc->toNormalizedString(true);
            $feed = strtolower(substr($feed, 0, 150));
            $feedEncoding = strpos($feed, 'encoding=&quot;utf-8&quot;');

            if ($feedEncoding !== false) {
                // utf-8 feed
                $utf8 = 1;
            } else {
                // non utf-8 page
                $utf8 = 0;
            }

            define('_JOS_FEED_ENCODING', $utf8);
        }

        if (!defined('_JOS_SITE_ENCODING')) {
            // determine encoding of page
            if (strpos(strtolower(_ISO), 'utf') !== false) {
                // utf-8 page
                $utf8 = 1;
            } else {
                // non utf-8 page
                $utf8 = 0;
            }

            define('_JOS_SITE_ENCODING', $utf8);
        }
        if (phpversion() >= 5) {
            // handling for PHP 5
            if (_JOS_FEED_ENCODING) {
                // handling for utf-8 feed
                if (_JOS_SITE_ENCODING) {
                    // utf-8 page
                    $encoding = 'html_entity_decode';
                } else {
                    // non utf-8 page
                    $encoding = 'utf8_decode';
                }
            } else {
                // handling for non utf-8 feed
                if (_JOS_SITE_ENCODING) {
                    // utf-8 page
                    $encoding = '';
                } else {
                    // non utf-8 page
                    $encoding = 'utf8_decode';
                }
            }
        } else {
            // handling for PHP 4
            if (_JOS_FEED_ENCODING) {
                // handling for utf-8 feed
                if (_JOS_SITE_ENCODING) {
                    // utf-8 page
                    $encoding = '';
                } else {
                    // non utf-8 page
                    $encoding = 'utf8_decode';
                }
            } else {
                // handling for non utf-8 feed
                if (_JOS_SITE_ENCODING) {
                    // utf-8 page
                    $encoding = 'utf8_encode';
                } else {
                    // non utf-8 page
                    $encoding = 'html_entity_decode';
                }
            }
        }

        if ($encoding && !$utf8enc) {
            $text = $encoding($text);
        } elseif ($utf8enc) {
            $text = joostina_api::convert($text);
        }

        $text = str_replace('&apos;', "'", $text);

        return $text;
    }

    public static function makeOption($value, $text = '', $value_name = 'value', $text_name = 'text')
    {
        $obj = new stdClass;
        $obj->$value_name = $value;
        $obj->$text_name = trim($text) ? $text : $value;

        return $obj;
    }

    /**
     * Замена метода makeOption (класс заменяем на массив)
     *
     * @param        $value
     * @param string $text
     * @param string $value_name
     * @param string $text_name
     *
     * @return array
     */
    public static function makeOptionL($value, $text = '', $value_name = 'value', $text_name = 'text')
    {
        $obj = array();
        $obj[$value_name] = $value;
        $obj[$text_name] = trim($text) ? $text : $value;

        return $obj;
    }

    public static function writableCell($folder, $relative = 1, $text = '', $visible = 1)
    {

        $writeable = '<b><span style="color:green">' . _WRITEABLE . '</span></b>';
        $unwriteable = '<b><span style="color:#ff0000">' . _UNWRITEABLE . '</span></b>';

        echo '<tr>';
        echo '<td class="item">';
        echo $text;
        if ($visible) {
            echo $folder . '/';
        }
        echo '</td>';
        echo '<td align="left">';
        if ($relative) {
            echo is_writable("../$folder") ? $writeable : $unwriteable;
        } else {
            echo is_writable($folder) ? $writeable : $unwriteable;
        }
        echo '</td>';
        echo '</tr>';
    }

    /**
     * Генерирует HTML-код тега INPUT
     *
     * @param        $tag_name
     * @param string $type
     * @param string $value
     * @param string $tag_attribs
     *
     * @return string
     *
     * $modification 13.01.2015
     */
    public static function inputType($tag_name, $type = 'text', $value = '', $tag_attribs = '')
    {
        $result = '<input name="' . $tag_name . '" type="' . $type . '" value="' . $value . '" ' . $tag_attribs . '>';
        return $result;
    }

    /**
     * Generates an HTML select list
     *
     * @param array  An array of objects
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param string The name of the object variable for the option value
     * @param string The name of the object variable for the option text
     * @param mixed  The key that is selected
     *
     * @returns string HTML for the select list
     */
    public static function selectList($arr, $tag_name, $tag_attribs, $key, $text, $selected = null, $first_el_key = '*000', $first_el_text = '*000')
    {
        // check if array
        if (is_array($arr)) {
            reset($arr);
        }

        $html = "<select name=\"$tag_name\" $tag_attribs>";
        $count = count($arr);

        if ($first_el_key != '*000' && $first_el_text != '*000') {
            $html .= "<option value=\"$first_el_key\">$first_el_text</option>";
        }
        for ($i = 0, $n = $count; $i < $n; $i++) {
            $k = $arr[$i]->$key;
            $t = $arr[$i]->$text;
            $id = (isset($arr[$i]->id) ? @$arr[$i]->id : null);

            $extra = '';
            $extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
            if (is_array($selected)) {

                foreach ($selected as $obj) {
                    $k2 = $obj->$key;
                    if ($k == $k2) {
                        $extra .= ' selected="selected"';
                        break;
                    }
                }
            } else {
                $extra .= ($k == $selected ? " selected=\"selected\"" : '');
            }
            $html .= "<option value=\"" . $k . "\"$extra>" . $t . "</option>";
        }
        $html .= "</select>";

        return $html;
    }

    /**
     * Замена selectList (объект на массив)
     *
     * @param array  An array of objects
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param string The name of the object variable for the option value
     * @param string The name of the object variable for the option text
     * @param mixed  The key that is selected
     *
     * @returns string HTML for the select list
     */
    public static function selectListL($arr, $tag_name, $tag_attribs, $key, $text, $selected = null, $first_el_key = '*000', $first_el_text = '*000')
    {
        // check if array
        if (is_array($arr)) {
            reset($arr);
        }

        $html = '<select name="' . $tag_name . '" ' . $tag_attribs . '>';
        $count = count($arr);

        if ($first_el_key != '*000' && $first_el_text != '*000') {
            $html .= "<option value=\"$first_el_key\">$first_el_text</option>";
        }
        for ($i = 0, $n = $count; $i < $n; $i++) {
            $k = $arr[$i][$key];
            $t = $arr[$i][$text];
            $id = (isset($arr[$i]['id']) ? @$arr[$i]['id'] : null);

            $extra = '';
            $extra .= $id ? " id=\"" . $arr[$i]['id'] . "\"" : '';
            if (is_array($selected)) {

                foreach ($selected as $obj) {
                    $k2 = $obj[$key];
                    if ($k == $k2) {
                        $extra .= ' selected="selected"';
                        break;
                    }
                }
            } else {
                $extra .= ($k == $selected ? " selected=\"selected\"" : '');
            }
            $html .= "<option value=\"" . $k . "\"$extra>" . $t . "</option>";
        }
        $html .= "</select>";

        return $html;
    }

    /**
     * Writes a select list of integers
     *
     * @param int    The start integer
     * @param int    The end integer
     * @param int    The increment
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed  The key that is selected
     * @param string The printf format to be applied to the number
     *
     * @returns string HTML for the select list
     */
    public static function integerSelectList($start, $end, $inc, $tag_name, $tag_attribs, $selected, $format = "")
    {
        $start = intval($start);
        $end = intval($end);
        $inc = intval($inc);
        $arr = array();

        for ($i = $start; $i <= $end; $i += $inc) {
            $fi = $format ? sprintf("$format", $i) : "$i";
            $arr[] = LHtml::makeOption($fi, $fi);
        }

        return LHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    /**
     * Writes a select list of month names based on Language settings
     *
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed  The key that is selected
     *
     * @returns string HTML for the select list values
     */
    public static function monthSelectList($tag_name, $tag_attribs, $selected, $type = 0)
    {
        // месяца для выбора
        $arr_1 = array(
            LHtml::makeOption('01', _JAN), LHtml::makeOption('02', _FEB), LHtml::makeOption('03', _MAR), LHtml::makeOption('04', _APR), LHtml::makeOption('05', _MAY), LHtml::makeOption('06', _JUN),
            LHtml::makeOption('07', _JUL), LHtml::makeOption('08', _AUG), LHtml::makeOption('09', _SEP), LHtml::makeOption('10', _OCT), LHtml::makeOption('11', _NOV),
            LHtml::makeOption('12', _DEC)
        );
        // месяца с правильным склонением
        $arr_2 = array(
            LHtml::makeOption('01', _JAN_2), LHtml::makeOption('02', _FEB_2), LHtml::makeOption('03', _MAR_2), LHtml::makeOption('04', _APR_2), LHtml::makeOption('05', _MAY_2),
            LHtml::makeOption('06', _JUN_2), LHtml::makeOption('07', _JUL_2), LHtml::makeOption('08', _AUG_2), LHtml::makeOption('09', _SEP_2), LHtml::makeOption('10', _OCT_2),
            LHtml::makeOption('11', _NOV_2), LHtml::makeOption('12', _DEC_2)
        );
        $arr = $type ? $arr_2 : $arr_1;

        return LHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function daySelectList($tag_name, $tag_attribs, $selected)
    {
        $arr = array();

        for ($i = 1; $i <= 31; $i++) {
            $pref = '';
            if ($i <= 9) {
                $pref = '0';
            }
            $arr[] = LHtml::makeOption($pref . $i, $pref . $i);
        }

        return LHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function yearSelectList($tag_name, $tag_attribs, $selected, $min = 1900, $max = null)
    {

        $max = ($max == null) ? date('Y', time()) : $max;

        $arr = array();
        for ($i = $min; $i <= $max; $i++) {
            $arr[] = LHtml::makeOption($i, $i);
        }

        return LHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    public static function genderSelectList($tag_name, $tag_attribs, $selected)
    {
        $arr = array(LHtml::makeOption('no_gender', _GENDER_NONE), LHtml::makeOption('male', _MALE), LHtml::makeOption('female', _FEMALE));

        return LHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    /**
     * Generates an HTML select list from a tree based query list
     *
     * @param array  Source array with id and parent fields
     * @param array  The id of the current list item
     * @param array  Target array.  May be an empty array.
     * @param array  An array of objects
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param string The name of the object variable for the option value
     * @param string The name of the object variable for the option text
     * @param mixed  The key that is selected
     *
     * @returns string HTML for the select list
     */
    public static function treeSelectList(&$src_list, $src_id, $tgt_list, $tag_name, $tag_attribs, $key, $text, $selected)
    {
        // establish the hierarchy of the menu
        $children = array();
        // first pass - collect children
        foreach ($src_list as $v) {
            $pt = $v->parent;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }
        // second pass - get an indent list of the items
        $ilist = mosTreeRecurse(0, '', array(), $children);

        // assemble menu items to the array
        $this_treename = '';
        foreach ($ilist as $item) {
            if ($this_treename) {
                if ($item->id != $src_id && strpos($item->treename, $this_treename) === false) {
                    $tgt_list[] = LHtml::makeOption($item->id, $item->treename);
                }
            } else {
                if ($item->id != $src_id) {
                    $tgt_list[] = LHtml::makeOption($item->id, $item->treename);
                } else {
                    $this_treename = "$item->treename/";
                }
            }
        }

        // build the html select list
        return LHtml::selectList($tgt_list, $tag_name, $tag_attribs, $key, $text, $selected);
    }

    /**
     * Генерирует HTML-код : выпадающий список ДА/НЕТ
     *
     * @param string $tag_name    : имя элемента
     * @param string $tag_attribs : дополнительные атрибуты элемента
     * @param string $selected    : значение
     * @param string $yes         : текстовое описание ДА
     * @param string $no          : текстовое описание НЕТ
     *
     * @return string : HTML-код select>opion
     *
     * @modification 13.01.2015 Gold dragon
     */
    public static function yesnoSelectList($tag_name, $tag_attribs, $selected, $yes = _YES, $no = _NO)
    {
        $arr = array(LHtml::makeOption('0', $no), LHtml::makeOption('1', $yes),);

        return LHtml::selectList($arr, $tag_name, $tag_attribs, 'value', 'text', $selected);
    }

    /**
     * Generates an HTML radio list
     *
     * @param array  An array of objects
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed  The key that is selected
     * @param string The name of the object variable for the option value
     * @param string The name of the object variable for the option text
     *
     * @returns string HTML for the select list
     */
    public static function radioList(&$arr, $tag_name, $tag_attribs, $selected = null, $key = 'value', $text = 'text')
    {
        reset($arr);
        $html = '';
        for ($i = 0, $n = count($arr); $i < $n; $i++) {
            $k = $arr[$i]->$key;
            $t = $arr[$i]->$text;
            $id = (isset($arr[$i]->id) ? @$arr[$i]->id : null);

            $extra = '';
            $extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
            if (is_array($selected)) {
                foreach ($selected as $obj) {
                    $k2 = $obj->$key;
                    if ($k == $k2) {
                        $extra .= " selected=\"selected\"";
                        break;
                    }
                }
            } else {
                $extra .= ($k == $selected ? " checked=\"checked\"" : '');
            }
            $html .= "\n\t<input type=\"radio\" name=\"$tag_name\" id=\"$tag_name$k\" value=\"" . $k . "\"$extra $tag_attribs />";
            $html .= "\n\t<label for=\"$tag_name$k\">$t</label>";
        }
        $html .= "\n";

        return $html;
    }

    /**
     * Writes a yes/no radio list
     *
     * @param string The value of the HTML name attribute
     * @param string Additional HTML attributes for the <select> tag
     * @param mixed  The key that is selected
     *
     * @returns string HTML for the radio list
     */
    public static function yesnoRadioList($tag_name, $tag_attribs, $selected, $yes = _YES, $no = _NO)
    {
        $arr = array(LHtml::makeOption('0', $no), LHtml::makeOption('1', $yes));

        return LHtml::radioList($arr, $tag_name, $tag_attribs, $selected);
    }

    /**
     * @param int    The row index
     * @param int    The record id
     * @param boolean
     * @param string The name of the form element
     *
     * @return string
     */
    public static function idBox($rowNum, $recId, $checkedOut = false, $name = 'cid')
    {
        if ($checkedOut) {
            return '';
        } else {
            return '<input type="checkbox" id="cb' . $rowNum . '" name="' . $name . '[]" value="' . $recId . '" onclick="isChecked(this.checked);" />';
        }
    }

    public static function sortIcon($base_href, $field, $state = 'none')
    {
        $alts = array('none' => _SORT_NONE, 'asc' => _SORT_ASC, 'desc' => _SORT_DESC,);
        $next_state = 'asc';
        if ($state == 'asc') {
            $next_state = 'desc';
        } else {
            if ($state == 'desc') {
                $next_state = 'none';
            }
        }

        $html = '<a href="' . $base_href . '&field=' . $field . '&order=' . $next_state . '"><img src="' . _LPATH_SITE . '/' . JADMIN_BASE . '/images/sort_' . $state . '.png" width="12" height="12" border="0" alt="'
            . $alts[$next_state] . '" /></a>';

        return $html;
    }

    /**
     * Writes Close Button
     */
    public static function CloseButton(&$params, $hide_js = null)
    {
        // displays close button in Pop-up window
        if ($params->get('popup') && !$hide_js) {
            ?>
            <script>
                <!--
                document.write('<div align="center" style="margin-top: 30px; margin-bottom: 30px;">');
                document.write('<a class="print_button" href="#" onclick="javascript:window.close();"><span class="small"><?php echo _PROMPT_CLOSE; ?></span></a>');
                document.write('</div>');
                //-->
            </script>
        <?php
        }
    }

    /**
     * Writes Back Button
     * Сыылка "Вернуться" отображается в следующих случаях:
     * - не переданы параметры (если, например, нет необходимости проверять значения настроек, а нужно принудительно вывести ссылку);
     * - параметры переданы и имеют соответствующие значения (используется в com_content)
     * - параметры переданы, но настройка `back_button` не задана (т.е. должно использоваться глобальное значение параметра)
     *     и в глобальных настройках включено отображение ссылки

     */
    public static function BackButton(&$params = null, $hide_js = null)
    {
        $config = Jconfig::getInstance();

        if (!$params || ($params->get('back_button') == 1 && !$params->get('popup') && !$hide_js) || ($params->get('back_button') == -1 && $config->config_back_button == 1)) {
            include_once(_LPATH_ROOT . '/templates/system/back_button.php');
        } else {
            return false;
        }
    }

    /**
     * Cleans text of all formating and scripting code
     */
    public static function cleanText($text)
    {
        $text = preg_replace("'<script[^>]*>.*?</script>'si", '', $text);
        //$text = preg_replace('/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is','\2 (\1)',$text);
        $text = preg_replace('/<!--.+?-->/', '', $text);
        $text = preg_replace('#\{+.*\}+#', '', $text);
        //$text = preg_replace('/({.+?})/', '', $text);
        $text = preg_replace('/&nbsp;/', ' ', $text);
        $text = preg_replace('/&amp;/', ' ', $text);
        $text = preg_replace('/&quot;/', ' ', $text);
        $text = strip_tags($text);
        $text = htmlspecialchars($text, null, 'UTF-8');

        return $text;
    }

    /**
     * Вывод значка печати, встроен хак индексации печатной версии
     */
    public static function PrintIcon($row, &$params, $hide_js, $link, $status = null)
    {
        global $cpr_i;

        if (!isset($cpr_i)) {
            $cpr_i = '';
        }

        if ($params->get('print') && !$hide_js) {
            // use default settings if none declared
            if (!$status) {
                $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';
            }
            // checks template image directory for image, if non found default are loaded
            if ($params->get('icons')) {
                $image = LAdminMenu::ImageCheck('printButton.png', '/images/system/', null, null, _PRINT, 'print' . $cpr_i);
                $cpr_i++;
            } else {
                $image = _ICON_SEP . '&nbsp;' . _PRINT . '&nbsp;' . _ICON_SEP;
            }
            if ($params->get('popup') && !$hide_js) {
                ?>
                <script>
                    <!--
                    document.write('<a href="#" class="print_button" onclick="javascript:window.print(); return false;" title="<?php echo _PRINT; ?>">');
                    document.write('<?php echo $image; ?>');
                    document.write('</a>');
                    //-->
                </script>
            <?php
            } else {
                ?>
                <?php if (!Jconfig::getInstance()->config_index_print) { ?>
                    <span style="display:none"><![CDATA[<noindex>]]></span><a href="#" rel="nofollow" target="_blank"
                                                                              onclick="window.open('<?php echo $link; ?>','win2','<?php echo $status; ?>'); return false;"
                                                                              title="<?php echo _PRINT; ?>"><?php echo $image; ?></a><span
                        style="display:none"><![CDATA[</noindex>]]></span>
                <?php } else { ?>
                    <a href="<?php echo $link; ?>" target="_blank" title="<?php echo _PRINT; ?>"><?php echo $image; ?></a>
                <?php
                }; ?>

            <?php
            }
        }
    }


}