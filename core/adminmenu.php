<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS CORE
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LAdminMenu
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */
class LAdminMenu
{
    /**
     * build the select list for Menu Ordering
     */
    public static function Ordering(&$row, $id)
    {
        $database = database::getInstance();

        if ($id) {
            $query = "SELECT ordering AS value, name AS text" . " FROM #__menu" . "\n WHERE menutype = " . $database->Quote($row->menutype) . "\n AND parent = " . (int)$row->parent . "\n AND published != -2"
                . "\n ORDER BY ordering";
            $order = mosGetOrderingList($query);
            $ordering = LHtml::selectList($order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval($row->ordering));
        } else {
            $ordering = '<input type="hidden" name="ordering" value="' . $row->ordering . '" />' . _NEW_ITEM_LAST;
        }
        return $ordering;
    }

    /**
     * @static Создание списка прав доступа для групп
     *
     * @param      $row   - ($row->access) ID группы
     * @param bool $guest - Добавлять ли гостевой вход
     *
     * @return string - HTML-выпадающий список
     */
    public static function Access($row, $guest = false)
    {
        $database = database::getInstance();

        $query = "SELECT id AS `value`, `name` AS text FROM `#__groups` ORDER BY id";
        $database->setQuery($query);
        $groups = $database->loadObjectList();
        $guest ? $groups[] = LHtml::makeOption(3, _COM_MODULES_GUEST) : null;
        $access_tmp = (!isset($row->access)) ? 0 : intval($row->access);
        $access = LHtml::selectList($groups, 'access', 'class="inputbox" size="4"', 'value', 'text', $access_tmp);
        return $access;
    }

    /**
     * build the select list for parent item
     */
    public static function Parent($row)
    {
        $database = database::getInstance();

        $id = '';
        if (isset($row->id)) {
            $id = "\n AND id != " . (int)$row->id;
        }
        // get a list of the menu items
        // excluding the current menu item and its child elements
        $query = "SELECT m.* FROM #__menu m WHERE menutype = " . $database->Quote($row->menutype) . " AND published != -2" . $id . " ORDER BY parent, ordering";
        $database->setQuery($query);
        $mitems = $database->loadObjectList();
        // establish the hierarchy of the menu
        $children = array();
        if ($mitems) {
            // first pass - collect children
            foreach ($mitems as $v) {
                $pt = $v->parent;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        // second pass - get an indent list of the items
        $list = mosTreeRecurse(0, '', array(), $children, 20, 0, 0);
        // assemble menu items to the array
        $mitems = array();
        $mitems[] = LHtml::makeOption('0', 'Top');
        foreach ($list as $item) {
            $mitems[] = LHtml::makeOption($item->id, '&nbsp;&nbsp;&nbsp;' . $item->treename);
        }
        $output = LHtml::selectList($mitems, 'parent', 'class="inputbox" size="10"', 'value', 'text', $row->parent);
        return $output;
    }

    /**
     * build a radio button option for published state
     */
    public static function Published(&$row)
    {
        return LHtml::yesnoRadioList('published', 'class="inputbox"', $row->published);
    }

    /**
     * build the link/url of a menu item
     */
    public static function Link(&$row, $id, $link = null)
    {
        $mainframe = MainFrame::getInstance();;

        if ($id) {
            switch ($row->type) {
                case 'content_item_link':
                case 'content_typed':
                    // load menu params
                    $params = new mosParameters($row->params, $mainframe->getPath('menu_xml', $row->type), 'menu');

                    $temp = explode('&task=view&id=', $row->link);

                    $link = $row->link;
                    break;

                default:
                    $link = $row->link;
                    break;
            }
        } else {
            $link = null;
        }

        return $link;
    }

    /**
     * build the select list for target window
     */
    public static function Target(&$row)
    {
        $click[] = LHtml::makeOption('0', _ADM_MENUS_TARGET_CUR_WINDOW);
        $click[] = LHtml::makeOption('1', _ADM_MENUS_TARGET_NEW_WINDOW_WITH_PANEL);
        $click[] = LHtml::makeOption('2', _ADM_MENUS_TARGET_NEW_WINDOW_WITHOUT_PANEL);
        return LHtml::selectList($click, 'browserNav', 'class="inputbox" size="4"', 'value', 'text', intval($row->browserNav));
    }

    /**
     * @static       Создание списка привязки модулей
     *
     * @param array $lookup - данные о привязке модуля
     * @param int   $all    - "Все"
     * @param int   $none   - "Отсутствует"
     *
     * @return string - HTML список
     * @modification : 08.10.2013 Gold Dragon
     */
    public static function MenuLinks($lookup, $all = 0, $none = 0)
    {
        // Создание выделенных строк
        $lookup_tmp = array();
        if (is_array($lookup) and count($lookup)) {
            foreach ($lookup as $value) {
                $lookup_tmp[] = array(
                    'option'    => $value->option,
                    'directory' => $value->directory,
                    'category'  => $value->category,
                    'task'      => $value->task
                );
            }
        } else {
            // если данныех нет
            $lookup_tmp[] = array(
                'option'    => '0',
                'directory' => 0,
                'category'  => 0,
                'task'      => ''
            );
        }
        $lookup = $lookup_tmp;

        $database = database::getInstance();

        // подготовить список ядра (BOSS)
        $sql = "SELECT  id,  `name` FROM `#__boss_config`";
        $database->setQuery($sql);
        $rows = $database->loadObjectList();

        $task = '';
        $_lang = LLang::getLang('sys.core');

        // TODO Gold Dragon : необходимо сделать проверку корректности INI-файла
        $task_lnk = LibIni::getInstance(_LPATH_LNK . '/com_boss.lnk.ini');
        $lnk_key = $task_lnk->getValue('key');
        $lnk_value = $task_lnk->getValue('value');

        foreach ($rows as $directory) {
            $boss_temp[] = LHtml::makeOption(-999, '------------');
            $boss_temp[] = LHtml::makeOption('com_boss' . '-' . $directory->id . '-0-', $directory->name . $_lang['ALL']);
            // получаем список категорий в каталоге BOSS
            $sql = "SELECT id, name, parent FROM `#__boss_" . $directory->id . "_categories` ORDER BY parent,ordering";

            $database->setQuery($sql);
            $mitems = $database->loadObjectList();
            if (count($mitems)) {
                $mitems_temp = $mitems;

                // establish the hierarchy of the menu
                $children = array();
                // first pass - collect children
                foreach ($mitems as $v) {
                    $pt = $v->parent;
                    $boss = isset($children[$pt]) ? $children[$pt] : array();
                    array_push($boss, $v);
                    $children[$pt] = $boss;
                }
                // second pass - get an indent list of the items
                $boss = mosTreeRecurse(intval($mitems[0]->parent), '', array(), $children, 20, 0, 0);

                // Code that adds menu name to Display of Page(s)
                $text_count = 0;
                $mitems_spacer = $directory->name;
                foreach ($boss as $boss_a) {
                    foreach ($mitems_temp as $mitems_a) {
                        if ($mitems_a->id == $boss_a->id) {
                            // Code that inserts the blank line that seperates different menus
                            if ($directory->name != $mitems_spacer) {
                                $boss_temp[] = LHtml::makeOption(-999, '------------');
                                $mitems_spacer = $directory->name;
                            }

                            $text = $directory->name . ' : ' . $boss_a->treename;
                            $boss_temp[] = LHtml::makeOption('com_boss' . '-' . $directory->id . '-' . $boss_a->id . '-' . $task, $text . $_lang['ALL']);
                            if (sizeof($lnk_key)) {
                                foreach ($lnk_key as $key => $value) {
                                    $boss_temp[] = LHtml::makeOption('com_boss' . '-' . $directory->id . '-' . $boss_a->id . '-' . $value, $text . ' : ' . $lnk_value[$key]);
                                }
                            }
                            if (strlen($text) > $text_count) {
                                $text_count = strlen($text);
                            }
                        }
                    }
                }
            }
        }
        // BOSS конец

        // Получение списка компонентов
        $sql = "SELECT `name`, `option` FROM `#__components` WHERE `parent`=0 AND `option` != 'com_boss' AND `option` != 'com_frontpage'";
        $database->setQuery($sql);
        $rows = $database->loadObjectList();
        foreach ($rows as $row) {
            $com_temp[] = LHtml::makeOption(-999, '------------');
            $task = '';
            // TODO Gold Dragon : необходимо сделать проверку корректности INI-файла
            $task_lnk = LibIni::getParseIni(_LPATH_LNK . '/'.$row->option.'.lnk.ini');
            $lnk_key = (isset($task_lnk['key'])) ? $task_lnk['key'] : array();
            $lnk_value = (isset($task_lnk['value'])) ? $task_lnk['value'] : array();

            $com_temp[] = LHtml::makeOption($row->option . '-0-0-' . $task, $row->name . $_lang['ALL']);
            if (sizeof($lnk_key)) {
                foreach ($lnk_key as $key => $value) {
                    $com_temp[] = LHtml::makeOption($row->option . '-0-0-' . $value, $row->name . ' : ' . $lnk_value[$key]);
                }
            }
        }

        // массив для списка
        $mitems = array();

        // пункт "Все"
        if ($all) {
            // сам пункт
            $mitems[] = LHtml::makeOption('', _ALL);
            // разделитель
            $mitems[] = LHtml::makeOption(-999, '------------');
        }

        // пункт "Отсутствует"
        if ($none) {
            // prepare an array with 'all' as the first item
            $mitems[] = LHtml::makeOption(0, _NOT_EXISTS);
            // adds space, in select box which is not saved
            $mitems[] = LHtml::makeOption(-999, '------------');
        }

        // пункт "Главная страница"
        $mitems[] = LHtml::makeOption('com_frontpage-0-0-', _FRONTPAGE_NAME);

        // добавление ссылок для BOSS
        if (isset($boss_temp)) {
            foreach ($boss_temp as $item) {
                $mitems[] = LHtml::makeOption($item->value, $item->text);
            }
        }

        // добавление ссылок для других компонентов
        foreach ($com_temp as $item) {
            $mitems[] = LHtml::makeOption($item->value, $item->text);
        }

        return self::MenuLinksSelect($mitems, $lookup);
    }


    /**
     * @static формирование HTML-списка с SELECTED
     *
     * @param $arr      - список компонентов
     * @param $selected - список выборки
     *
     * @return string - HTML список
     */
    public static function MenuLinksSelect($arr, $selected)
    {
        $html = '<select name="selections[]" class="inputbox" size="26" multiple="multiple">';
        $tmp_ind = true;
        foreach ($arr as $key) {
            if ($key->value === '') {
                $key->value = '-0-0-';
            }
            if ($key->value == '0') {
                $key->value = '0-0-0-';
            }
            $select = '';
            if ($tmp_ind) {
                foreach ($selected as $sel) {
                    $str = $sel['option'] . '-' . $sel['directory'] . '-' . $sel['category'] . '-' . $sel['task'];
                    if ($str === '' and $key->value === '') {
                        $select = 'selected="selected">';
                        $tmp_ind = false;
                    } elseif ($str == '0' and (string)$key->value == '0') {
                        $select = 'selected="selected">';
                        $tmp_ind = false;
                    } elseif ($tmp_ind and (string)$key->value == (string)$str) {
                        $select = 'selected="selected"';
                    }
                }
            }
            $html .= '<option value="' . $key->value . '" ' . $select . '>' . $key->text . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    /**
     * build the select list to choose a section
     */
    public static function Section($menu, $id, $all = 0)
    {
        $database = database::getInstance();

        $query = "SELECT s.id AS `value`, s.id AS `id`, s.title AS `text` FROM `#__sections` AS s WHERE s.scope = 'content' ORDER BY s.name";
        $database->setQuery($query);
        if ($all) {
            $rows[] = LHtml::makeOption(0, '- Все разделы -');
            $rows = array_merge($rows, $database->loadObjectList());
        } else {
            $rows = $database->loadObjectList();
        }

        if ($id) {
            foreach ($rows as $row) {
                if ($row->value == $menu->componentid) {
                    $section = $row->text;
                }
            }
            $section .= '<input type="hidden" name="componentid" value="' . $menu->componentid . '" />';
            $section .= '<input type="hidden" name="link" value="' . $menu->link . '" />';
        } else {
            $section = LHtml::selectList($rows, 'componentid', 'class="inputbox" size="10"', 'value', 'text');
            $section .= '<input type="hidden" name="link" value="" />';
        }
        return $section;
    }

    /**
     * build the select list to choose a component
     */
    public static function Component($menu, $id, $rows = null)
    {
        $database = database::getInstance();
        if (!$rows) {
            $query = "SELECT c.id AS `value`, c.name AS text, c.link FROM `#__components` AS c WHERE c.link != '' ORDER BY c.name";
            $database->setQuery($query);
            $rows = $database->loadObjectList();
        }
        if ($id) {
            // existing component, just show name
            foreach ($rows as $row) {
                if ($row->value == $menu->componentid) {
                    $component = $row->text;
                    break;
                } else {
                    $component = $menu->name;
                }
            }
            $component .= '<input type="hidden" name="componentid" value="' . $menu->componentid . '" />';
        } else {
            $component = LHtml::selectList($rows, 'componentid', 'class="inputbox" size="10"', 'value', 'text');
        }

        return $component;
    }

    /**
     * build the select list to choose a component
     */
    public static function ComponentName(&$menu, $rows = null)
    {
        $database = database::getInstance();

        if (!$rows) {
            $query = "SELECT c.id AS `value`, c.name AS text, c.link FROM `#__components` AS c WHERE c.link != '' ORDER BY c.name";
            $database->setQuery($query);
            $rows = $database->loadObjectList();
        }

        $component = 'Component';
        foreach ($rows as $row) {
            if ($row->value == $menu->componentid) {
                $component = $row->text;
            }
        }

        return $component;
    }

    /**
     * build the select list to choose an image
     */
    public static function Images($name, $active, $javascript = null, $directory = null)
    {

        if (!$directory) {
            $directory = '/images/stories';
        }

        if (!$javascript) {
            $javascript
                = "onchange=\"javascript:if (document.forms[0].image.options[selectedIndex].value!='') {document.imagelib.src='..$directory/' + document.forms[0].image.options[selectedIndex].value} else {document.imagelib.src='../images/blank.png'}\"";
        }

        $imageFiles = mosReadDirectory(_LPATH_ROOT . $directory);
        $images = array(LHtml::makeOption('', '- ' . _CHOOSE_IMAGE . ' -'));
        foreach ($imageFiles as $file) {
            if (preg_match("/bmp|gif|jpg|png/i", $file)) {
                $images[] = LHtml::makeOption($file);
            }
        }
        $images = LHtml::selectList($images, $name, 'class="inputbox" size="1" ' . $javascript, 'value', 'text', $active);

        return $images;
    }

    /**
     * build the select list for Ordering of a specified Table
     */
    public static function SpecificOrdering(&$row, $id, $query, $neworder = 0, $limit = 30)
    {
        if ($neworder) {
            $text = _NEW_ITEM_FIRST;
        } else {
            $text = _NEW_ITEM_LAST;
        }

        if ($id) {
            $order = mosGetOrderingList($query, $limit);
            $ordering = LHtml::selectList($order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval($row->ordering));
        } else {
            $ordering = '<input type="hidden" name="ordering" value="' . $row->ordering . '" />' . $text;
        }
        return $ordering;
    }

    /**
     * Select list of active users
     */
    public static function UserSelect($name, $active, $nouser = 0, $javascript = null, $order = 'name', $reg = 1)
    {
        $database = database::getInstance();

        $and = '';
        if ($reg) {
            // does not include registered users in the list
            $and = "\n AND gid > 18";
        }

        $query = "SELECT id AS `value`, `name` AS text FROM #__users WHERE block = 0 " . $and . " ORDER BY " . $order;
        $database->setQuery($query);
        if ($nouser) {
            $users[] = LHtml::makeOption('0', '- ' . _NO_USER . ' -');
            $users = array_merge($users, $database->loadObjectList());
        } else {
            $users = $database->loadObjectList();
        }

        $users = LHtml::selectList($users, $name, 'class="inputbox" size="1" ' . $javascript, 'value', 'text', $active);

        return $users;
    }

    /**
     * Select list of positions - generally used for location of images
     */
    public static function Positions($name, $active = null, $javascript = null, $none = 1, $center = 1, $left = 1, $right = 1)
    {
        if ($none) {
            $pos[] = LHtml::makeOption('', _NONE);
        }
        if ($center) {
            $pos[] = LHtml::makeOption('center', _CENTER);
        }
        if ($left) {
            $pos[] = LHtml::makeOption('left', _LEFT);
        }
        if ($right) {
            $pos[] = LHtml::makeOption('right', _RIGHT);
        }

        $positions = LHtml::selectList($pos, $name, 'class="inputbox" size="1"' . $javascript, 'value', 'text', $active);

        return $positions;
    }

    /**
     * Select list of active sections
     */
    public static function SelectSection($name, $active = null, $javascript = null, $order = 'ordering', $scope = 'content')
    {
        $database = database::getInstance();

        $categories[] = LHtml::makeOption('0', _SEL_SECTION);
        $query = "SELECT id AS value, title AS text" . "\n FROM #__sections" . "\n WHERE published = 1 AND scope='$scope'" . "\n ORDER BY $order";
        $database->setQuery($query);
        $sections = array_merge($categories, $database->loadObjectList());

        $category = LHtml::selectList($sections, $name, 'class="inputbox" size="1" ' . $javascript, 'value', 'text', $active);

        return $category;
    }

    /**
     * Select list of menu items for a specific menu
     */
    public static function Links2Menu($type, $and)
    {
        $database = database::getInstance();

        $query = "SELECT* FROM #__menu WHERE type = " . $database->Quote($type) . " AND published = 1" . $and;
        $database->setQuery($query);
        $menus = $database->loadObjectList();
        return $menus;
    }

    /**
     * Select list of menus
     *
     * @param string The control name
     * @param string Additional javascript
     *
     * @return string A select list
     */
    public static function MenuSelect($name = 'menuselect', $javascript = null)
    {
        $database = database::getInstance();

        $query = "SELECT params FROM `#__modules` WHERE `module` = 'mod_mainmenu' OR `module` = 'mod_mljoostinamenu'";
        $database->setQuery($query);
        $menus = $database->loadObjectList();
        $i = 0;
        $menuselect = array();
        $menus_arr = array();
        foreach ($menus as $menu) {
            $params = mosParseParams($menu->params);
            if (!in_array($params->menutype, $menus_arr)) {
                $menuselect[$i] = new stdClass();
                $menuselect[$i]->value = $params->menutype;
                $menuselect[$i]->text = $params->menutype;
                $menus_arr[$i] = $params->menutype;
                $i++;
            }
        }
        SortArrayObjects($menuselect, 'text', 1);
        $menus = LHtml::selectList($menuselect, $name, 'class="inputbox" size="10" ' . $javascript, 'value', 'text');
        return $menus;
    }

    /**
     * Internal function to recursive scan the media manager directories
     *
     * @param string Path to scan
     * @param string root path of this folder
     * @param array  Value array of all existing folders
     * @param array  Value array of all existing images
     */
    public static function ReadImages($imagePath, $folderPath, &$folders, &$images)
    {
        $imgFiles = mosReadDirectory($imagePath);

        foreach ($imgFiles as $file) {
            $ff_ = $folderPath . $file . '/';
            $ff = $folderPath . $file;
            $i_f = $imagePath . '/' . $file;

            if (is_dir($i_f) && $file != 'CVS' && $file != '.svn') {
                $folders[] = LHtml::makeOption($ff_);
                LAdminMenu::ReadImages($i_f, $ff_, $folders, $images);
            } else {
                if (preg_match("/bmp|gif|jpg|png/", $file) && is_file($i_f)) {
                    // leading / we don't need
                    $imageFile = substr($ff, 1);
                    $images[$folderPath][] = LHtml::makeOption($imageFile, $file);
                }
            }
        }
    }

    /**
     * Internal function to recursive scan the media manager directories
     *
     * @param string Path to scan
     * @param string root path of this folder
     * @param array  Value array of all existing folders
     * @param array  Value array of all existing images
     */
    public static function ReadImagesX(&$folders, &$images)
    {

        if ($folders[0]->value != '*0*') {
            foreach ($folders as $folder) {
                $imagePath = _LPATH_ROOT . '/images/stories' . $folder->value;
                $imgFiles = mosReadDirectory($imagePath);
                $folderPath = $folder->value . '/';

                foreach ($imgFiles as $file) {
                    $ff = $folderPath . $file;
                    $i_f = $imagePath . '/' . $file;

                    if (preg_match("/bmp|gif|jpg|png/i", $file) && is_file($i_f)) {
                        // leading / we don't need
                        $imageFile = substr($ff, 1);
                        $images[$folderPath][] = LHtml::makeOption($imageFile, $file);
                    }
                }
            }
        } else {
            $folders = array();
            $folders[] = LHtml::makeOption('None');
        }
    }

    public static function GetImageFolders(&$temps)
    {
        if ($temps[0]->value != 'None') {
            foreach ($temps as $temp) {
                if (substr($temp->value, -1, 1) != '/') {
                    $temp = $temp->value . '/';
                    $folders[] = LHtml::makeOption($temp, $temp);
                } else {
                    $temp = $temp->value;
                    $temp = ampReplace($temp);
                    $folders[] = LHtml::makeOption($temp, $temp);
                }
            }
        } else {
            $folders[] = LHtml::makeOption(_NOT_CHOOSED);
        }

        $javascript = "onchange=\"changeDynaList( 'imagefiles', folderimages, document.adminForm.folders.options[document.adminForm.folders.selectedIndex].value, 0, 0);\"";
        $getfolders = LHtml::selectList($folders, 'folders', 'class="inputbox" size="1" ' . $javascript, 'value', 'text', '/');

        return $getfolders;
    }

    public static function GetImages(&$images, $path, $base = '/')
    {
        if (is_array($base) && count($base) > 0) {
            if ($base[0]->value != '/') {
                $base = $base[0]->value . '/';
            } else {
                $base = $base[0]->value;
            }
        } else {
            $base = '/';
        }

        if (!isset($images[$base])) {
            $images[$base][] = LHtml::makeOption('');
        }

        $javascript = "onchange=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\" onfocus=\"previewImage( 'imagefiles', 'view_imagefiles', '$path/' )\"";
        $getimages = LHtml::selectList($images[$base], 'imagefiles', 'class="inputbox" size="10" multiple="multiple" ' . $javascript, 'value', 'text', null);

        return $getimages;
    }

    public static function GetSavedImages(&$row, $path)
    {
        $images2 = array();
        foreach ($row->images as $file) {
            $temp = explode('|', $file);
            if (strrchr($temp[0], '/')) {
                $filename = substr(strrchr($temp[0], '/'), 1);
            } else {
                $filename = $temp[0];
            }
            $images2[] = LHtml::makeOption($file, $filename);
        }
        $javascript = "onchange=\"previewImage( 'imagelist', 'view_imagelist', '$path/' ); showImageProps( '$path/' ); \"";
        $imagelist = LHtml::selectList($images2, 'imagelist', 'class="inputbox" size="10" ' . $javascript, 'value', 'text');
        return $imagelist;
    }

    /**
     * Checks to see if an image exists in the current templates image directory
     * if it does it loads this image.  Otherwise the default image is loaded.
     * Also can be used in conjunction with the menulist param to create the chosen image
     * load the default or use no image
     */
    public static function ImageCheck($file, $directory = '/images/system/', $param = null, $param_directory = '/images/system/', $alt = null, $name = null, $type = 1, $align = 'middle', $title = null, $admin = null)
    {

        $id = $name ? ' id="' . $name . '"' : '';
        $name = $name ? ' name="' . $name . '"' : '';
        $title = $title ? ' title="' . $title . '"' : '';
        $alt = $alt ? ' alt="' . $alt . '"' : ' alt=""';
        $align = $align ? ' align="' . $align . '"' : '';
        // change directory path from frontend or backend
        if ($admin) {
            $path = '/templates/admin/' . TEMPLATE . '/images/ico/';
        } else {
            $path = '/templates/front/' . TEMPLATE . '/images/ico/';
        }
        if ($param) {
            $image = _LPATH_SITE . $param_directory . $param;
            if ($type) {
                $image = '<img src="' . $image . '" ' . $alt . $id . $name . $align . ' border="0" />';
            }
        } else {
            if ($param == -1) {
                $image = '';
            } else {
                if (file_exists(_LPATH_ROOT . $path . $file)) {
                    $image = _LPATH_SITE . $path . $file;
                } else {
                    $image = $directory . $file;
                }
                if ($type) {
                    $image = '<img src="' . $image . '" ' . $alt . $id . $name . $title . $align . ' border="0" />';
                }
            }
        }
        return $image;
    }

    /**
     * Checks to see if an image exists in the current templates image directory
     * if it does it loads this image.  Otherwise the default image is loaded.
     * Also can be used in conjunction with the menulist param to create the chosen image
     * load the default or use no image
     */
    public static function ImageCheckAdmin($file, $directory = '/administrator/images/', $param = null, $param_directory = '/administrator/images/', $alt = null, $name = null, $type = 1, $align = 'middle', $title = null)
    {
        $image = LAdminMenu::ImageCheck($file, $directory, $param, $param_directory, $alt, $name, $type, $align, $title, 1);
        return $image;
    }

    /**
     * Получение меню
     *
     * @return array
     *
     * @modification 22.01.2014 Gold dragon
     */
    public static function getMenuTypes()
    {
        $_db = LCore::getDB();
        $menuTypes = $_db->select("SELECT `id`, `type` FROM `#__menu_type` ORDER BY `type`");
        return $menuTypes;
    }

    /*
	 * loads files required for menu items
	 */

    public static function menuItem($item)
    {

        $path = _LPATH_ADM_COM . '/com_menus/' . $item . '/';
        include_once($path . $item . '.class.php');
        include_once($path . $item . '.menu.html.php');
    }

}