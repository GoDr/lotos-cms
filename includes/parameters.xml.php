<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 * Модификация 04.05.2012 Gold Dragon (http://gd.lotos-cms.ru)
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

// TODO : Передалать класс на современный
/**
 * Parameters handler
 *
 * @package Joostina
 */
class mosParameters
{
    /**
     * @var null|object
     */
    var $_params = null;
    /**
     * Сырая строка с параметрами
     * @var null|string
     */
    var $_raw = null;
    /**
     * Путь до XML-фала
     *
     * @var null|string
     */
    var $_path = null;
    /**
     * Тип параметров
     * @var null|string
     */
    var $_type = null;
    /**
     * Объект с XML-элементами
     *
     * @var null|object
     */
    var $_xmlElem = null;

    /**
     * Конструктор
     * @param        $text
     * @param string $path
     * @param string $type
     */
    function mosParameters($text, $path = '', $type = 'component')
    {
        //jd_inc('mosParameters');
        $this->_params = $this->parse($text);
        $this->_raw = $text;
        $this->_path = $path;
        $this->_type = $type;
    }

    /**
     * Возвращает массив параметров
     * @return object
     */
    function toObject()
    {
        return $this->_params;
    }

    /**
     * Возвращает поименованный массив параметров
     * @return object
     */
    function toArray()
    {
        return mosObjectToArray($this->_params);
    }

    /**
     * Создаёт Параметр->Значение
     * @param string Имя параметра
     * @param string Значение параметра
     *
     * @return string Значение параметра
     */
    function set($key, $value = '')
    {
        $this->_params->$key = $value;
        return $value;
    }

    /**
     * Устанавливает значение по умолчанию если ещё не назначено
     * @param string Имя параметра
     * @param string Значение параметра
     *
     * @return string Значение параметра
     */
    function def($key, $value = '')
    {
        return $this->set($key, $this->get($key, $value));
    }

    /**
     * Возвращает значение параметра
     * @param string Имя параметра
     * @param string Значение параметра
     *
     * @return string Значение параметра
     */
    function get($key, $default = '')
    {
        if (isset($this->_params->$key)) {
            return $this->_params->$key === '' ? $default : $this->_params->$key;
        } else {
            return $default;
        }
    }

    /**
     * Парсинг получаемых данных
     * @param      $txt     Строка или массив с данными
     * @param bool $process_sections
     * @param bool $asArray TRUE - возвращать массив, FALSE - возвращать класс
     *
     * @return object
     */
    public static function parse($txt, $process_sections = false, $asArray = false)
    {
        if (trim($txt) == '') {
            return $asArray ? array() : new stdClass();
        }

        if (is_string($txt)) {
            $lines = explode("\n", $txt);
        } elseif (is_array($txt)) {
            $lines = $txt;
        } else {
            $lines = array();
        }


        if ((false == $process_sections) && (false == $asArray) && (is_string($txt)) && (false === strpos($txt, '[')) && (false === strpos($txt, '\\')) && (false === strpos($txt, '"')) && (false === strpos($txt, ';'))) {
            $obj = new stdClass();
            foreach ($lines as $line) {
                $vars = explode('=', $line, 2);
                if (count($vars) == 2) {
                    $property = trim($vars[0]);
                    $value = trim($vars[1]);
                    if ($value) {
                        if ($value == 'false') {
                            $value = false;
                        } elseif ($value == 'true') {
                            $value = true;
                        }
                    }
                    $obj->$property = $value;
                }
            }
            return $obj;
        }

        $obj = $asArray ? array() : new stdClass();

        $sec_name = '';
        $unparsed = 0;
        if (!$lines) {
            return $obj;
        }
        foreach ($lines as $line) {
            // ignore comments
            if ($line && $line[0] == ';') {
                continue;
            }
            $line = trim($line);

            if ($line == '') {
                continue;
            }
            if ($line && $line[0] == '[' && $line[strlen($line) - 1] == ']') {
                $sec_name = substr($line, 1, strlen($line) - 2);
                if ($process_sections) {
                    if ($asArray) {
                        $obj[$sec_name] = array();
                    } else {
                        $obj->$sec_name = new stdClass();
                    }
                }
            } else {
                if ($pos = strpos($line, '=')) {
                    $property = trim(substr($line, 0, $pos));

                    if (substr($property, 0, 1) == '"' && substr($property, -1) == '"') {
                        $property = stripcslashes(substr($property, 1, count($property) - 2));
                    }
                    $value = trim(substr($line, $pos + 1));
                    if ($value == 'false') {
                        $value = false;
                    }
                    if ($value == 'true') {
                        $value = true;
                    }
                    if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
                        $value = stripcslashes(substr($value, 1, count($value) - 2));
                    }

                    if ($process_sections) {
                        $value = str_replace('\n', "\n", $value);
                        if ($sec_name != '') {
                            if ($asArray) {
                                $obj[$sec_name][$property] = $value;
                            } else {
                                $obj->$sec_name->$property = $value;
                            }
                        } else {
                            if ($asArray) {
                                $obj[$property] = $value;
                            } else {
                                $obj->$property = $value;
                            }
                        }
                    } else {
                        $value = str_replace('\n', "\n", $value);
                        if ($asArray) {
                            $obj[$property] = $value;
                        } else {
                            $obj->$property = $value;
                        }
                    }
                } else {
                    if ($line && trim($line[0]) == ';') {
                        continue;
                    }
                    if ($process_sections) {
                        $property = '__invalid' . $unparsed++ . '__';
                        if ($process_sections) {
                            if ($sec_name != '') {
                                if ($asArray) {
                                    $obj[$sec_name][$property] = trim($line);
                                } else {
                                    $obj->$sec_name->$property = trim($line);
                                }
                            } else {
                                if ($asArray) {
                                    $obj[$property] = trim($line);
                                } else {
                                    $obj->$property = trim($line);
                                }
                            }
                        } else {
                            if ($asArray) {
                                $obj[$property] = trim($line);
                            } else {
                                $obj->$property = trim($line);
                            }
                        }
                    }
                }
            }
        }
        return $obj;
    }

    /**
     * @param string The name of the control, or the default text area if a setup file is not found
     *
     * @return string HTML
     */
    function render($name = 'params')
    {
        if ($this->_path) {
            if (!is_object($this->_xmlElem)) {
                require_once(_LPATH_ROOT . '/includes/domit/xml_domit_lite_include.php');
                $xmlDoc = new DOMIT_Lite_Document();
                $xmlDoc->resolveErrors(true);
                if ($xmlDoc->loadXML($this->_path, false, true)) {
                    $root = $xmlDoc->documentElement;
                    $tagName = $root->getTagName();
                    $isParamsFile = ($tagName == 'mosinstall' || $tagName == 'mosparams');
                    if ($isParamsFile && $root->getAttribute('type') == $this->_type) {
                        if ($params = $root->getElementsByPath('params', 1)) {
                            $this->_xmlElem = $params;
                        }
                    }
                }
            }
        }
        if (is_object($this->_xmlElem)) {
            $html = array();
            $element = $this->_xmlElem;
            $html[] = '<table width="100%" class="paramlist">';

            if ($description = $element->getAttribute('description')) {
                $html[] = '<tr><td colspan="2">' . $description . '</td></tr>';
            }
            $this->_methods = get_class_methods(get_class($this));

            foreach ($element->childNodes as $param) {
                $result = $this->renderParam($param, $name);

                switch ($result[5]) {
                    case 'newtable':
                        $html[] = '</table>';
                        $html[] = '<table width="100%" class="paramlist">';
                        break;

                    case 'tabs':
                        $html[] = $result[1];
                        break;

                    default:
                        $html[] = '<tr>';
                        $html[] = '<td width="40%" align="right" valign="top" class="pkey"><span class="editlinktip">' . $result[0] . '</span></td>';
                        $html[] = '<td>' . $result[1] . '</td>';
                        $html[] = '</tr>';
                        break;
                }

            }
            if (count($element->childNodes) < 1) {
                $html[] = "<tr><td colspan=\"2\"><i>" . _NO_PARAMS . "</i></td></tr>";
            }
            $html[] = '</table>';

            return implode("\n", $html);
        } else {
            return "<textarea name=\"$name\" cols=\"40\" rows=\"10\" class=\"text_area\">$this->_raw</textarea>";
        }
    }

    /**
     * @param object A param tag node
     * @param string The control name
     *
     * @return array Any array of the label, the form element and the tooltip
     */
    function renderParam($param, $control_name = 'params')
    {
        $result = array();
        $name = $param->getAttribute('name');
        $label = $param->getAttribute('label');
        $value = $this->get($name, $param->getAttribute('default'));
        $description = $param->getAttribute('description');

        // если "человеческого" названия нет, то оно берётся из названия параметра
        $result[0] = $label ? $label : $name;

        // формируем "всплывающую" подсказку для параметра
        if ($result[0] == '@spacer') {
            $result[0] = '&nbsp;';
        } else {
            $result[0] = mosToolTip(addslashes($description), addslashes($result[0]), '', '', $result[0], '#', 0);
        }
        // определяем тип параметра и вызываем соответствующую функцию
        $type = $param->getAttribute('type');
        // проверяем существует ли метод для обработки этого типа
        if (in_array('_form_' . $type, $this->_methods)) {
            $result[1] = call_user_func(array($this, '_form_' . $type), $name, $value, $param, $control_name, $label);
        } else { // пытаемся добавить обработчик неизвестного поля из модуля
            if (mosGetParam($_REQUEST, 'option', '') == 'com_modules') {
                $id = mosGetParam($_REQUEST, 'id', 0);
                if ($id > 0) {
                    $database = database::getInstance();
                    $query = "SELECT module" . "\n FROM #__modules" . "\n WHERE id = " . $id;
                    $database->setQuery($query);
                    $module = $database->loadResult();
                    if (is_file(_LPATH_ROOT . '/modules/' . $module . '/elements.php')) {
                        require_once(_LPATH_ROOT . '/modules/' . $module . '/elements.php');
                        $className = $module . '_elements';
                        $methodName = 'load_' . $type;
                        if (method_exists($className, $methodName)) {
                            //$result[1] = $className::$methodName($name);
                            $result[1] = call_user_func_array(array($className, $methodName), array($name));
                        } else {
                            $result[1] = _HANDLER . ' = ' . $type;
                        }
                    } else {
                        $result[1] = _HANDLER . ' = ' . $type;
                    }
                } else {
                    $result[1] = _HANDLER . ' = ' . $type;
                }
            } else {
                $result[1] = _HANDLER . ' = ' . $type;
            }
        }

        if ($description) {
            $result[2] = mosToolTip($description, $result[0]);
            $result[2] = '';
        } else {
            $result[2] = '';
        }
        $result[3] = $description;
        $result[4] = $label;
        $result[5] = $type;
        return $result;
    }

    /**
     * Однострочное текстовое поле
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_text($name, $value, $node, $control_name)
    {
        $type_array = array('color', 'date', 'datetime', 'month', 'week', 'time', 'email', 'number', 'password', 'search', 'tel', 'url');
        $details = $node->getAttribute('details');
        $type = (in_array($details, $type_array)) ? $details : 'text';
        $size = $node->getAttribute('size');
        return '<input type="' . $type . '" name="' . $control_name . '[' . $name . ']" value="' . htmlspecialchars($value) . '" class="text_area" size="' . $size . '" />';
    }

    /**
     * Выпадающий список
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_list($name, $value, $node, $control_name)
    {
        $options = array();
        foreach ($node->childNodes as $option) {
            $val = $option->getAttribute('value');
            $text = $option->gettext();
            $options[] = LHtml::makeOption($val, $text);
        }
        return LHtml::selectList($options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'value', 'text', $value);
    }

    /**
     * Определяет кнопку-переключатель
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_radio($name, $value, $node, $control_name)
    {
        $options = array();
        foreach ($node->childNodes as $option) {
            $val = $option->getAttribute('value');
            $text = $option->gettext();
            $options[] = LHtml::makeOption($val, $text);
        }

        return LHtml::radioList($options, '' . $control_name . '[' . $name . ']', '', $value);
    }

    /**
     * Выпадающий список каталогов
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_directory($name, $value, $node, $control_name)
    {
        $database = database::getInstance();
        $sql = "SELECT id, name FROM #__boss_config";
        $database->setQuery($sql);
        $options = $database->loadObjectList();
        array_unshift($options, LHtml::makeOption('0', _SELECT_DIRECTORY, 'id', 'name'));

        return LHtml::selectList($options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'id', 'name', $value);
    }

    /**
     * Выпадающий список категорий
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_category($name, $value, $node, $control_name)
    {
        $html = false;
        $published = intval($node->getAttribute('published'));
        $database = database::getInstance();
        $sql = "SELECT id, name FROM #__boss_config";
        $database->setQuery($sql);
        $rows = $database->loadObjectList();
        foreach ($rows as $directory) {
            $q = "SELECT * FROM #__boss_" . $directory->id . "_categories ";
            if ($published == 1) {
                $q .= " WHERE published=1 ";
            } elseif ($published == 2) {
                $q .= " WHERE published=0 ";
            }
            $q .= "ORDER BY parent,ordering";
            $rows = $database->setQuery($q)->loadObjectList();
            if ($database->getErrorNum()) {
                echo $database->stderr();
            }
            $children = array();
            foreach ($rows as $v) {
                $pt = $v->parent;
                $list = isset($children[$pt]) ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
            $html .= '<optgroup label="' . $directory->name . '">';
            ob_start();
            $this->selectCategories($directory->id, 0, " &#187; ", $children, $value);
            $html .= ob_get_contents();
            ob_end_clean();
            $html .= '</optgroup>';
        }
        if ($html) {
            $html = '<option value="0">' . _SEL_CATEGORY . '</option>' . $html;
            $html = '<select name="' . $control_name . '[' . $name . ']" class="inputbox">' . $html . "</select>";
        }
        return $html;
    }

    /**
     * Вспомогательная функция для _form_category()
     *
     * @param      $directory
     * @param      $id
     * @param      $level
     * @param      $children
     * @param null $catid
     */
    private function selectCategories($directory, $id, $level, $children, $catid = null)
    {
        if (isset($children[$id])) {
            foreach ($children[$id] as $row) {
                if ($directory . '-' . $row->id == $catid) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                echo '<option value="' . $directory . '-' . $row->id . '"' . $selected . '>' . $level . $row->name . '</option>';
                $this->selectCategories($directory, $row->id, $level . $row->name . " &#187; ", $children, $catid);
            }
        }
    }

    /**
     * Выпадающий список полей
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_field($name, $value, $node, $control_name)
    {
        $html = false;
        $published = intval($node->getAttribute('published'));
        $database = database::getInstance();
        $sql = "SELECT id, name FROM #__boss_config";
        $database->setQuery($sql);
        $rows = $database->loadObjectList();
        foreach ($rows as $directory) {
            $q = "SELECT fieldid AS id, name FROM #__boss_" . $directory->id . "_fields ";
            if ($published == 1) {
                $q .= " WHERE published=1 ";
            } elseif ($published == 2) {
                $q .= " WHERE published=0 ";
            }
            $q .= "ORDER BY name";
            $rows = $database->setQuery($q)->loadObjectList();
            if ($database->getErrorNum()) {
                echo $database->stderr();
            }

            $html .= '<optgroup label="' . $directory->name . '">';
            foreach ($rows as $row) {
                if ($value == $directory->id . '-' . $row->name) {
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }
                $html .= '<option value="' . $directory->id . '-' . $row->name . '"' . $selected . '>' . $row->name . '</option>';
            }
            $html .= '</optgroup>';
        }
        if ($html) {
            $html = '<option value="0">' . _SEL_FIELD . '</option>' . $html;
            $html = '<select name="' . $control_name . '[' . $name . ']" class="inputbox">' . $html . "</select>";
        }
        return $html;
    }

    /**
     * Выпадающий список меню
     * @param string Название элемента
     * @param string Значение элемента
     * @param object XML-элементы
     * @param string Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_menu($name, $value, $node, $control_name)
    {
        $menuTypes = LAdminMenu::getMenuTypes();

        foreach ($menuTypes as $menutype) {
            $options[] = LHtml::makeOption($menutype['id'], $menutype['type']);
        }
        array_unshift($options, LHtml::makeOption('', _ET_MENU));

        return LHtml::selectList($options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'value', 'text', $value);
    }

    /**
     * Выпадающий список файлов
     * @param        $name         Название элемента
     * @param        $value        Значение элемента
     * @param        $node         XML-элементы
     * @param        $control_name Управляющее имя
     * @param        $label        Заголовок элемента
     * @param string $const1       - языковая константа
     * @param string $const2       - языковая константа
     *
     * @return string HTML-форма
     */
    private function _form_filelist($name, $value, $node, $control_name, $label, $const1 = _DONT_USE_FILE, $const2 = _DEFAULT_FILE)
    {
        $path = _LPATH_ROOT . DS . $node->getAttribute('directory');
        $filter = $node->getAttribute('filter');
        $files = mosReadDirectory($path, $filter);

        $options = array();
        foreach ($files as $file) {
            $options[] = LHtml::makeOption($file, $file);
        }
        if (intval($node->getAttribute('hide_none'))) {
            array_unshift($options, LHtml::makeOption('-1', $const1));
        }
        if (intval($node->getAttribute('hide_default'))) {
            array_unshift($options, LHtml::makeOption('', $const2));
        }
        return LHtml::selectList($options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'value', 'text', $value);
    }

    /**
     * Выпадающий список файлов
     * @param        $name         Название элемента
     * @param        $value        Значение элемента
     * @param        $node         XML-элементы
     * @param        $control_name Управляющее имя
     * @param        $label        Заголовок элемента
     *
     * @return string HTML-форма
     */
    private function _form_imagelist($name, $value, $node, $control_name, $label)
    {
        $filter = $node->getAttribute('filter');
        if ($filter == '') {
            $node->setAttribute('filter', '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$');
        }
        return $this->_form_filelist($name, $value, $node, $control_name, $label, _DONT_USE_IMAGE, _DEFAULT_IMAGE);
    }

    /**
     * Выпадающий список ДА/НЕТ
     * @param        $name         Название элемента
     * @param        $value        Значение элемента
     * @param        $node         XML-элементы
     * @param        $control_name Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_yesno($name, $value, $node, $control_name)
    {
        $no = $node->getAttribute('no');
        if (!$no) {
            $no = _NO;
        }
        $yes = $node->getAttribute('yes');
        if (!$yes) {
            $yes = _YES;
        }
        $options = array(LHtml::makeOption('0', $no), LHtml::makeOption('1', $yes),);
        return LHtml::selectList($options, '' . $control_name . '[' . $name . ']', 'class="inputbox"', 'value', 'text', $value);
    }

    /**
     * Многострочное текстовое поле
     * @param        $name         Название элемента
     * @param        $value        Значение элемента
     * @param        $node         XML-элементы
     * @param        $control_name Управляющее имя
     *
     * @return string HTML-форма
     */
    private function _form_textarea($name, $value, $node, $control_name)
    {
        $rows = $node->getAttribute('rows');
        $cols = $node->getAttribute('cols');
        $value = str_replace('<br />', "\n", $value);

        return '<textarea name="' . $control_name . '[' . $name . ']" cols="' . $cols . '" rows="' . $rows . '" class="text_area">' . htmlspecialchars($value) . '</textarea>';
    }

    /**
     * Визуальный элемент - разделитель
     * @param $name  Название элемента
     * @param $value Значение элемента
     *
     * @return string HTML-форма
     */
    private function _form_spacer($name, $value)
    {
        if ($value) {
            return $value;
        } else {
            return '<hr />';
        }
    }

    function _form_tabs($name, $value, $param, $control_name, $label)
    {
        $return = '';
        switch ($value) {
            case 'startPane':
                $return .= '<tr><td></td></tr></table>';
                $mainframe = MainFrame::getInstance();
                $mainframe->addCSS(_LPATH_SITE . '/includes/js/tabs/tabpane.css');
                $mainframe->addJS(_LPATH_SITE . '/includes/js/tabs/tabpane.js');
                $return .= '<div class="tab-page" id="' . $name . '">';
                $return .= '<script>var tabPane1 = new WebFXTabPane( document.getElementById( "' . $name . '" ),0)</script>';
                break;

            case 'endPane':
                $return .= '</div><table>';
                break;

            case 'startTab':
                $return .= '<div class="tab-page" id="' . $name . '">';
                $return .= '<h2 class="tab">' . $label . '</h2>';
                $return .= '<script>tabPane1.addTabPage( document.getElementById( "' . $name . '" ) );</script>';
                $return .= '<table width="100%" class="paramlist">';
                break;

            case 'endTab':
                $return .= '</table></div>';
                break;

            default:
                break;
        }

        return $return;
    }

    /**
     * @static       Замена переноса строки на соответсвующий тэг
     *
     * @param array $txt : данные настройки
     * @param bool  $flag: флаг, экранировать ли <br>
     *
     * @return string
     *
     * @modification 12.08.2013
     */
    public static function textareaHandling($txt, $flag = false)
    {
        $total = count($txt);
        for ($i = 0; $i < $total; $i++) {
            if (strstr($txt[$i], "\n")) {
                //экранируем <br>
                if ($flag) {
                    $txt[$i] = str_replace("<br", '&lt;br', $txt[$i]);
                }
                $txt[$i] = str_replace("\n", '<br />', $txt[$i]);
            }
        }
        $txt = implode("\n", $txt);
        return $txt;
    }

    /**
     * Выпадающий список для выбора времени кэиширования
     * @param $name
     * @param $value
     * @param $param
     * @param $control_name
     *
     * @return string
     */
    private function _form_cachelist($name, $value, $param, $control_name)
    {
        $options[] = LHtml::makeOption('0', _M_CACHE_0);
        $options[] = LHtml::makeOption('60', _M_CACHE_60);
        $options[] = LHtml::makeOption('300', _M_CACHE_300);
        $options[] = LHtml::makeOption('600', _M_CACHE_600);
        $options[] = LHtml::makeOption('900', _M_CACHE_900);
        $options[] = LHtml::makeOption('1200', _M_CACHE_1200);
        $options[] = LHtml::makeOption('1800', _M_CACHE_1800);
        $options[] = LHtml::makeOption('3600', _M_CACHE_3600);
        $options[] = LHtml::makeOption('7200', _M_CACHE_7200);
        $options[] = LHtml::makeOption('9000', _M_CACHE_9000);
        $options[] = LHtml::makeOption('7200', _M_CACHE_7200);
        $options[] = LHtml::makeOption('18000', _M_CACHE_18000);
        $options[] = LHtml::makeOption('43200', _M_CACHE_43200);
        $options[] = LHtml::makeOption('86400', _M_CACHE_86400);
        $options[] = LHtml::makeOption('172800', _M_CACHE_172800);
        $options[] = LHtml::makeOption('604800', _M_CACHE_604800);

        return LHtml::selectList($options, $control_name . '[' . $name . ']', 'class="inputbox"', 'value', 'text', $value);
    }

    /**
     * Создание выпадающего списка из произвольного SQL запроса
     *
     * @param string $name - название элемента
     * @param <type> $value - значение
     * @param <type> $node - активная нода
     * @param <type> $control_name - название управляющего элемента
     *
     * @return html - код выпадающего списка
     */
    function _form_selectlist($name, $value, $node, $control_name)
    {
        $sql = $node->getAttribute('sql');

        $options = database::getInstance()->setQuery($sql)->loadObjectList();
        return LHtml::selectList($options, '' . $control_name . '[' . $name . ']', 'class="inputbox" id="selectlist_' . $name . '"', 'id', 'title', $value);
    }
}

/**
 * @param      string
 * @param bool $process_sections
 * @param bool $asArray
 *
 * @return string
 */
function mosParseParams($txt, $process_sections = false, $asArray = false)
{
    return mosParameters::parse($txt, $process_sections = false, $asArray = false);
}
