<?php

/**
 * @package   Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 *            Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 *            Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */

defined('_LINDEX') or die('STOP in file ' . __FILE__);

//подгружаем языковой файл плагина
boss_helpers::loadBossPluginLang('fields', 'BossImageGalleryPlugin');

class BossImageGalleryPlugin
{
    //имя типа поля в выпадающем списке в настройках поля
    public $name = 'Image Gallery';

    //тип плагина для записи в таблицы
    public $type = __CLASS__;

    // получение файла инициализации
    function getEngine($engine)
    {
        require_once (dirname(__FILE__) . '/engines/' . $engine . '/' . $engine . '.php');
    }

    //отображение поля в категории
    function getListDisplay($directory, $content, $field, $field_values, $conf)
    {
        $conf_fields = self::fvalues($field_values);
        $engine = (!empty($conf_fields['galleryScript'])) ? $conf_fields['galleryScript'] : 'mbGallery';
        $fieldname = $field->name;
        $value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
        $images = (!empty($value)) ? json_decode($value, 1) : '';

        //если нет изображений - выходим
        if (!is_array($images) || count($images) == 0) {
            return '';
        }

        //загружаем файл инциализации скрипта галереи
        self::getEngine($engine);
        galleryListDisplay($directory, $content, $images, $conf_fields, $conf);
        return null;
    }

    //отображение поля в содержимом
    function getDetailsDisplay($directory, $content, $field, $field_values, $conf)
    {
        $conf_fields = self::fvalues($field_values);
        $engine = (!empty($conf_fields['galleryScript'])) ? $conf_fields['galleryScript'] : 'mbGallery';
        $fieldname = $field->name;
        $value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
        $images = (!empty($value)) ? json_decode($value, 1) : '';

        if (!is_array($images) || count($images) == 0) {
            return '';
        }

        //загружаем файл инциализации скрипта галереи
        self::getEngine($engine);
        galleryDetailsDisplay($directory, $content, $images, $conf_fields, $conf);

        return null;
    }

    //функция вставки фрагмента ява-скрипта в скрипт
    //сохранения формы при редактировании контента с фронта.
    function addInWriteScript($field)
    {
    }

    //отображение поля в админке в редактировании контента
    function getFormDisplay($directory, $content, $field, $field_values, $nameform = 'adminForm', $mode = "write")
    {
        LHtml::loadJquery();

        //создаем файловую структуру для галереи
        $path = '/images/boss/' . $directory . '/contents/gallery/';

        if (!is_dir(_LPATH_ROOT . $path . 'origin')) {
            self::makePath(_LPATH_ROOT, $path . 'origin');
        }

        if (!is_dir(_LPATH_ROOT . $path . 'full')) {
            self::makePath(_LPATH_ROOT, $path . 'full');
        }

        if (!is_dir(_LPATH_ROOT . $path . 'thumb')) {
            self::makePath(_LPATH_ROOT, $path . 'thumb');
        }

        $mainframe = MainFrame::getInstance();
        $mainframe->addJS(_LPATH_SITE . '/administrator/components/com_boss/js/upload.js');
        $mainframe->addJS(_LPATH_SITE . '/components/com_boss/plugins/fields/BossImageGalleryPlugin/js/script.js');

        $fieldname = $field->name;

        $isAdmin = ($mainframe->isAdmin() == 1) ? 1 : 0;

        $fValuers = array();
        foreach ($field_values[$field->fieldid] as $field_value) {
            $fValuers[$field_value->fieldtitle] = $field_value->fieldvalue;
        }

        $value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
        $value = (!empty($value)) ? json_decode($value, 1) : '';
        $strtitle = htmlentities(jdGetLangDefinition($field->title), ENT_QUOTES, 'utf-8');

        $mosReq = (($mode == "write") && ($field->required == 1)) ? " mosReq='1' " : '';
        $read_only = (($mode == "write") && ($field->editable == 0)) ? " readonly=true " : '';
        $class = (($mode == "write") && ($field->required == 1)) ? "boss_required" : 'boss';

        $nb_files = (!empty($fValuers['nb_images'])) ? (int)$fValuers['nb_images'] : 0;
        $max_image_size = (!empty($fValuers['max_image_size'])) ? (int)$fValuers['max_image_size'] : 0;

        $return = '';
        $return
            .= '
                <script type="text/javascript">
		            var boss_nb_images = ' . $nb_files . ';
		            var boss_max_imgsize = ' . $max_image_size . ';
		            var boss_enable_images = new Array("jpg", "png", "gif");
		            var boss_isadmin = ' . $isAdmin . ';
                </script>
                ';

        $return .= "<div id='gallery_images'>";
        if (!empty($value)) {
            $i = 0;
            foreach ($value as $row) {
                $return
                    .= "
                        <div id='gallery_image_" . $i . "' class='gallery_image_div'>
                        <label>" . BOSS_PLG_DESC . " </label>
                        <input type='text' size='40'
                            name='boss_img_gallery[" . $i . "][signature]' class='inputbox boss_img_gallery' value='" . urldecode($row['signature']) . "' />
                        <input type='hidden' name='boss_img_gallery[" . $i . "][file]' value='" . $row['file'] . "' />"
                    . self::displayFileLink($directory, $row['file'])
                    . "<input type='button' value='X' class='button' onclick='bossDeleteImage(\"" . $row['file'] . "\", \"gallery_image_" . $i . "\")' />
						</div>";
                $i++;
            }
        }
        $return .= "</div>";
        $return
            .= "
				<div id='boss_plugin_image'>
                    <input id='upload_image' type='button' class='button' value='" . BOSS_PLG_FM_UPLOAD . "' />
			        <span id='status_image'></span>
				</div>";

        return $return;
    }

    function onFormSave($directory, $contentid, $field, $isUpdateMode)
    {
        MainFrame::addLib('easythumb');
        $database = database::getInstance();
        $conf = $database->setQuery("SELECT `fieldtitle`, `fieldvalue` FROM #__boss_" . $directory . "_field_values WHERE fieldid = '$field->fieldid'")->loadObjectList('fieldtitle');

        //общие настройки эскизов
        $thumb = new easyphpthumbnail;
        $thumb->Chmodlevel = '0644';
        $thumb->Quality = 90;

        if (!empty($conf['tag']->fieldvalue)) {
            $thumb->Copyrighttext = $conf['tag']->fieldvalue;
            $thumb->Copyrightposition = (!empty($conf['tag_position']->fieldvalue)) ? $conf['tag_position']->fieldvalue : '50% 90%';
            $thumb->Copyrightfontsize = ((int)$conf['tag_fontsize']->fieldvalue > 0) ? (int)$conf['tag_fontsize']->fieldvalue : 8;
            $thumb->Copyrightfonttype = _LPATH_ROOT . '/components/com_boss/font/verdana.ttf';
            $thumb->Copyrighttextcolor = (!empty($conf['tag_color']->fieldvalue)) ? $conf['tag_color']->fieldvalue : '#FFFFFF';
        }

        //разрешенное количество изображений
        $nbImages = ((int)$conf['nb_images']->fieldvalue > 0) ? (int)$conf['nb_images']->fieldvalue : 1;

        //массив изображений
        $boss_img_gallery = mosGetParam($_POST, "boss_img_gallery", array());

        //подрезаем массив изображений до разрешенного количества
        $boss_img_gallery = array_slice($boss_img_gallery, 0, $nbImages);

        //переводим в транслит названия файлов если был загружен файл с кириллическим названием
        for ($i = 0; $i < count($boss_img_gallery); $i++) {
            $boss_img_gallery[$i]['file'] = russian_transliterate($boss_img_gallery[$i]['file']);
        }

        //возвращаем json с изображениями
        $return = boss_helpers::json_encode_cyr($boss_img_gallery);

        //создаем эскизы изображения
        foreach ($boss_img_gallery as $boss_img) {
            $filename = $boss_img['file'];

            // image1 upload
            $origin = _LPATH_ROOT . "/images/boss/" . $directory . "/contents/gallery/origin/" . $filename;

            //если есть оригинальный файл
            if (is_file($origin)) {
                //если нет эскиза
                if (!is_file(_LPATH_ROOT . "/images/boss/" . $directory . "/contents/gallery/full/" . $filename)) {
                    $thumb->Thumbsize = $conf['max_size']->fieldvalue;
                    $thumb->Thumblocation = _LPATH_ROOT . "/images/boss/$directory/contents/gallery/full/";
                    $thumb->Createthumb($origin, 'file');
                }

                //если нет миниатюры
                if (!is_file(_LPATH_ROOT . "/images/boss/" . $directory . "/contents/gallery/thumb/" . $filename)) {
                    $thumb->Thumbsize = $conf['max_size_t']->fieldvalue;
                    $thumb->Thumblocation = _LPATH_ROOT . "/images/boss/" . $directory . "/contents/gallery/thumb/";
                    $thumb->Createthumb($origin, 'file');
                }
            }
        }
        return $return;
    }

    function onDelete($directory, $content)
    {
        $database = database::getInstance();
        $contents = null;
        $database->setQuery("SELECT * FROM #__boss_" . $directory . "_contents WHERE `id` = '" . $content->id . "'");
        $database->loadObject($contents);
        $database->setQuery("SELECT name FROM #__boss_" . $directory . "_fields WHERE `type` = '" . $this->type . "'");
        $file_fields = $database->loadObjectList();
        if (is_array($file_fields) && count($file_fields) > 0) {
            foreach ($file_fields as $file_field) {
                $fileFieldName = $file_field->name;
                $files = json_decode($contents->$fileFieldName);
                if (is_array($files) && count($files) > 0) {
                    foreach ($files as $file) {
                        @unlink(_LPATH_ROOT . "/images/boss/$directory/contents/gallery/origin/" . $file->file);
                        @unlink(_LPATH_ROOT . "/images/boss/$directory/contents/gallery/full/" . $file->file);
                        @unlink(_LPATH_ROOT . "/images/boss/$directory/contents/gallery/thumb/" . $file->file);
                    }
                }
            }
        }
    }

    //отображение поля в админке в настройках поля
    function getEditFieldOptions($row, $directory, $fieldimages, $fieldvalues)
    {
        $path = dirname(__FILE__) . DS . 'engines' . DS;
        $files = mosReadDirectory($path, '');
        $options = array();
        foreach ($files as $file) { // формируем выпадающий список
            if (is_dir($path . $file)) { // добавляем только директории
                $options[] = LHtml::makeOption($file, $file);
            }
        }

        if(!isset($fieldvalues['galleryScript']->fieldvalue)){
            $fieldvalues['galleryScript'] = new stdClass();
            $fieldvalues['galleryScript']->fieldvalue = '';
        }

        if(!isset($fieldvalues['nb_images']->fieldvalue)){
            $fieldvalues['nb_images'] = new stdClass();
            $fieldvalues['nb_images']->fieldvalue = '';
        }

        if(!isset($fieldvalues['max_image_size']->fieldvalue)){
            $fieldvalues['max_image_size'] = new stdClass();
            $fieldvalues['max_image_size']->fieldvalue = '';
        }

        if(!isset($fieldvalues['max_size']->fieldvalue)){
            $fieldvalues['max_size'] = new stdClass();
            $fieldvalues['max_size']->fieldvalue = '';
        }

        if(!isset($fieldvalues['max_size_t']->fieldvalue)){
            $fieldvalues['max_size_t'] = new stdClass();
            $fieldvalues['max_size_t']->fieldvalue = '';
        }

        if(!isset($fieldvalues['tag']->fieldvalue)){
            $fieldvalues['tag'] = new stdClass();
            $fieldvalues['tag']->fieldvalue = '';
        }

        if(!isset($fieldvalues['tag_position']->fieldvalue)){
            $fieldvalues['tag_position'] = new stdClass();
            $fieldvalues['tag_position']->fieldvalue = '';
        }

        if(!isset($fieldvalues['tag_fontsize']->fieldvalue)){
            $fieldvalues['tag_fontsize'] = new stdClass();
            $fieldvalues['tag_fontsize']->fieldvalue = '';
        }

        if(!isset($fieldvalues['tag_color']->fieldvalue)){
            $fieldvalues['tag_color'] = new stdClass();
            $fieldvalues['tag_color']->fieldvalue = '';
        }

        if(!isset($fieldvalues['galleryTitle']->fieldvalue)){
            $fieldvalues['galleryTitle'] = new stdClass();
            $fieldvalues['galleryTitle']->fieldvalue = '';
        }

        if(!isset($fieldvalues['overlayBackground']->fieldvalue)){
            $fieldvalues['overlayBackground'] = new stdClass();
            $fieldvalues['overlayBackground']->fieldvalue = '';
        }

        if(!isset($fieldvalues['overlayOpacity']->fieldvalue)){
            $fieldvalues['overlayOpacity'] = new stdClass();
            $fieldvalues['overlayOpacity']->fieldvalue = '';
        }

        if(!isset($fieldvalues['minWidth']->fieldvalue)){
            $fieldvalues['minWidth'] = new stdClass();
            $fieldvalues['minWidth']->fieldvalue = '';
        }

        if(!isset($fieldvalues['minHeight']->fieldvalue)){
            $fieldvalues['minHeight'] = new stdClass();
            $fieldvalues['minHeight']->fieldvalue = '';
        }

        if(!isset($fieldvalues['maxWidth']->fieldvalue)){
            $fieldvalues['maxWidth'] = new stdClass();
            $fieldvalues['maxWidth']->fieldvalue = '';
        }

        if(!isset($fieldvalues['slideTimer']->fieldvalue)){
            $fieldvalues['slideTimer'] = new stdClass();
            $fieldvalues['slideTimer']->fieldvalue = '';
        }

        if(!isset($fieldvalues['autoSlide']->fieldvalue)){
            $fieldvalues['autoSlide'] = new stdClass();
            $fieldvalues['autoSlide']->fieldvalue = '';
        }

        $engine_select = LHtml::selectList($options, 'galleryScript', 'id="galleryScript" class="inputbox" style="width:140px"', 'value', 'text', $fieldvalues['galleryScript']->fieldvalue, "");

        $return
            = '<div id="divImageOptions">
            <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
                <tr>
                    <td colspan="3"><strong>' . BOSS_PLG_GALLERY_IMAGE_SETTINGS . '</strong></td>
                </tr>
                <tr>
                    <td>' . BOSS_NB_IMAGES . '</td>
                    <td><input type="text" name="nb_images" id="nb_images" value="' . $fieldvalues['nb_images']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_NB_IMAGES_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_MAX_IMAGE_SIZE . '</td>
                    <td><input type="text" name="max_image_size" id="max_image_size" value="' . $fieldvalues['max_image_size']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_MAX_IMAGE_SIZE_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_MAX_SIZE . '</td>
                    <td><input type="text" name="max_size" id="max_size" value="' . $fieldvalues['max_size']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_MAX_SIZE_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_MAX_SIZE_T . '</td>
                    <td><input type="text" name="max_size_t" id="max_size_t" value="' . $fieldvalues['max_size_t']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_MAX_SIZE_T_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_IMAGE_TAG . '</td>
                    <td><input type="text" name="tag" id="tag" value="' . $fieldvalues['tag']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_IMAGE_TAG_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_TAG_POSITION . '</td>
                    <td><input type="text" name="tag_position" id="tag_position" value="' . $fieldvalues['tag_position']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_TAG_POSITION_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_TAG_FONTSIZE . '</td>
                    <td><input type="text" name="tag_fontsize" id="tag_fontsize" value="' . $fieldvalues['tag_fontsize']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_TAG_FONTSIZE_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_TAG_COLOR . '</td>
                    <td><input type="text" name="tag_color" id="tag_color" value="' . $fieldvalues['tag_color']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_TAG_COLOR_LONG) . '</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>' . BOSS_PLG_GALLERY_GALLERY_SETTINGS . '</strong></td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_GAL_NAME . '</td>
                    <td><input type="text" name="galleryTitle" id="galleryTitle" value="' . $fieldvalues['galleryTitle']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_GAL_NAME_LONG) . '</td>
                </tr>               
                <tr>
                    <td>' . BOSS_PLG_GALLERY_OVER_BG . '</td>
                    <td><input type="text" name="overlayBackground" id="overlayBackground" value="' . $fieldvalues['overlayBackground']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_OVER_BG_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_OVER_OPAC . '</td>
                    <td><input type="text" name="overlayOpacity" id="overlayOpacity" value="' . $fieldvalues['overlayOpacity']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_OVER_OPAC_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_MIN_WIDTH . '</td>
                    <td><input type="text" name="minWidth" id="minWidth" value="' . $fieldvalues['minWidth']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_MIN_WIDTH_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_MIN_HEIGHT . '</td>
                    <td><input type="text" name="minHeight" id="minHeight" value="' . $fieldvalues['minHeight']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_MIN_HEIGHT_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_MAX_WIDTH . '</td>
                    <td><input type="text" name="maxWidth" id="maxWidth" value="' . $fieldvalues['maxWidth']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_MAX_WIDTH_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_SLIDE_TIMER . '</td>
                    <td><input type="text" name="slideTimer" id="slideTimer" value="' . $fieldvalues['slideTimer']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_SLIDE_TIMER_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_AUROSLIDE . '</td>
                    <td>
                        <select id="autoSlide" name="autoSlide" style="width: 140px">
                            <option value="1"';
        $return .= ($fieldvalues['autoSlide']->fieldvalue == '1') ? 'selected="selected"' : '';
        $return .= '>' . BOSS_YES . '</option>
                            <option value="0"';
        $return .= ($fieldvalues['autoSlide']->fieldvalue == '0') ? 'selected="selected"' : '';


        $return .= '>' . BOSS_NO . '</option>
                        </select>
                    </td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_AUROSLIDE_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_PLG_GALLERY_SCRIPT . '</td>
                    <td>' . $engine_select . ' </td>
                    <td>' . boss_helpers::bossToolTip(BOSS_PLG_GALLERY_SCRIPT_LONG) . '</td>
                </tr>
            </table>
            </div>';

        return $return;
    }

    //действия при сохранении настроек поля
    function saveFieldOptions($directory, $field)
    {
        $fieldid = $field->fieldid;
        $fieldname = $field->name;
        $database = database::getInstance();

        $nb_images = mosGetParam($_POST, "nb_images", 0);
        $max_image_size = mosGetParam($_POST, "max_image_size", 0);
        $max_size = mosGetParam($_POST, "max_size", 0);
        $max_size_t = mosGetParam($_POST, "max_size_t", 0);

        $tag = mosGetParam($_POST, "tag", '');
        $tag_fontsize = mosGetParam($_POST, "tag_fontsize", '');
        $tag_position = mosGetParam($_POST, "tag_position", '');
        $tag_color = mosGetParam($_POST, "tag_color", '');

        $image_display = mosGetParam($_POST, "image_display", '');
        $cat_max_width = mosGetParam($_POST, "cat_max_width", 0);
        $cat_max_height = mosGetParam($_POST, "cat_max_height", 0);
        $cat_max_width_t = mosGetParam($_POST, "cat_max_width_t", 0);
        $cat_max_height_t = mosGetParam($_POST, "cat_max_height_t", 0);

        $galleryTitle = mosGetParam($_POST, "galleryTitle", '');
        $overlayBackground = mosGetParam($_POST, "overlayBackground", '');
        $overlayOpacity = mosGetParam($_POST, "overlayOpacity", '');
        $minWidth = mosGetParam($_POST, "minWidth", '');
        $minHeight = mosGetParam($_POST, "minHeight", '');
        $maxWidth = mosGetParam($_POST, "maxWidth", '');
        $slideTimer = mosGetParam($_POST, "slideTimer", '');
        $autoSlide = mosGetParam($_POST, "autoSlide", '');
        $engine = mosGetParam($_POST, "galleryScript", '');

        $q = "DELETE FROM `#__boss_" . $directory . "_field_values` WHERE `fieldid` = '" . $fieldid . "' ";
        $database->setQuery($q)->query();

        $q = "INSERT INTO #__boss_" . $directory . "_field_values
            (fieldid, fieldtitle, fieldvalue, ordering, sys)
            VALUES
            ($fieldid,'nb_images', '$nb_images',  1,0),
            ($fieldid,'max_image_size', '$max_image_size', 2,0),
            ($fieldid,'max_size_t', '$max_size_t', 3,0),
            ($fieldid,'max_size', '$max_size', 4,0),
            ($fieldid,'tag', '$tag', 5,0),
            ($fieldid,'tag_fontsize', '$tag_fontsize', 6,0),
            ($fieldid,'tag_position', '$tag_position', 7,0),
            ($fieldid,'tag_color', '$tag_color', 8,0),
            ($fieldid,'image_display', '$image_display', 9,0),
            ($fieldid,'cat_max_width', '$cat_max_width', 10,0),
            ($fieldid,'cat_max_height', '$cat_max_height', 11,0),
            ($fieldid,'cat_max_width_t', '$cat_max_width_t', 12,0),
            ($fieldid,'cat_max_height_t', '$cat_max_height_t', 13,0),
            ($fieldid,'galleryTitle', '$galleryTitle', 14,0),           
            ($fieldid,'overlayBackground', '$overlayBackground', 16,0),
            ($fieldid,'overlayOpacity', '$overlayOpacity', 17,0),
            ($fieldid,'minWidth', '$minWidth', 18,0),
            ($fieldid,'minHeight', '$minHeight', 19,0),
            ($fieldid,'maxWidth', '$maxWidth', 20,0),
            ($fieldid,'slideTimer', '$slideTimer', 21,0),
            ($fieldid,'autoSlide', '$autoSlide', 22,0),            
            ($fieldid,'galleryScript', '$engine', 23,0)
            ";
        $database->setQuery($q)->query();
        //если плагин не создает собственных таблиц а пользется таблицами босса то возвращаем false
        //иначе true
        return false;
    }

    //расположение иконки плагина начиная со слеша от корня сайта
    function getFieldIcon()
    {
        return "/components/com_boss/plugins/fields/" . __CLASS__ . "/images/image_1.png";
    }

    //действия при установке плагина
    function install($directory)
    {
        return;
    }

    //действия при удалении плагина
    function uninstall($directory)
    {
        return;
    }

    //действия при поиске
    function search($directory, $fieldName)
    {
        $search = '';
        return $search;
    }

    //скрипты и стили в голову, которые не кешируются
    function addInHead($field, $field_values, $directory)
    {
        $conf = self::fvalues($field_values);
        $engine = !empty($conf['galleryScript']) ? $conf['galleryScript'] : 'mbGallery';
        $path = _LPATH_SITE . '/components/com_boss/plugins/fields/BossImageGalleryPlugin/engines/' . $engine;
        $params = array();

        //загружаем файл инциализации скрипта галереи
        self::getEngine($engine);
        $params = galleryAddInHead($path);
        //$params['css']['galleryImg0'] = _LPATH_SITE . '/components/com_boss/plugins/fields/BossImageGalleryPlugin/css/style.admin.css';

        $task = mosGetParam($_REQUEST, 'task', '');

        if ($task == 'show_all' or $task == 'show_content' or $task == 'show_category' or $task == '') { // добавляем параметры, при просмотре категории или контента или главной страницы
            $conf_fields = self::fvalues($field_values);

            $engine = (!empty($conf_fields['galleryScript'])) ? $conf_fields['galleryScript'] : 'mbGallery';
            $galleryTitle = htmlspecialchars(stripslashes(cutLongWord($conf_fields['galleryTitle'])), ENT_QUOTES);

            $overlayBackground = (!empty($conf_fields['overlayBackground'])) ? $conf_fields['overlayBackground'] : '#666';
            $overlayOpacity = (!empty($conf_fields['overlayOpacity'])) ? (int)$conf_fields['overlayOpacity'] : '.3';

            $minWidth = (!empty($conf_fields['minWidth'])) ? (int)$conf_fields['minWidth'] : '';
            $minHeight = (!empty($conf_fields['minHeight'])) ? (int)$conf_fields['minHeight'] : '';
            $maxWidth = (!empty($conf_fields['maxWidth'])) ? (int)$conf_fields['maxWidth'] : '';
            $maxHeight = (!empty($conf_fields['maxHeight'])) ? (int)$conf_fields['maxHeight'] : '';

            $slideTimer = (!empty($conf_fields['slideTimer'])) ? (int)$conf_fields['slideTimer'] : '6000';
            $autoSlide = (!empty($conf_fields['autoSlide'])) ? $conf_fields['autoSlide'] : false;

            $html = "<script>";
//            $html .= "$(function(){";
            $html .= "var galleryOptions = {";
            $html .= "galleryTitle: '" . $galleryTitle . "',";
            $html .= "overlayBackground: '" . $overlayBackground . "',";
            $html .= "overlayOpacity: '" . $overlayOpacity . "',";
            $html .= "minWidth: '" . $minWidth . "',";
            $html .= "minHeight: '" . $minHeight . "',";
            $html .= "maxWidth: '" . $maxWidth . "',";
            $html .= "maxHeight: '" . $maxHeight . "',";
            $html .= "slideTimer: '" . $slideTimer . "',";
            $html .= "autoSlide: '" . $autoSlide . "',";
            $html .= "}";
//            $html .= "});";
            $html .= "</script>";

            $params['custom_script']['imageGallery'] = $html;
        }
        return $params;
    }

    private function fvalues($field_values)
    {
        $fieldvalue = array();
        foreach ($field_values as $field_value) {
            $fieldvalue[$field_value->fieldtitle] = $field_value->fieldvalue;
        }
        return $fieldvalue;
    }

    //отображение ссылки на скачивание
    private function displayFileLink($directory, $filename)
    {
        $return = '';
        if ($filename) {
            $return .= "<img class='thumb' src=\"" . _LPATH_SITE . "/images/boss/" . $directory . "/contents/gallery/thumb/" . $filename . "\" align=\"middle\" border=\"0\" />&nbsp;";
        }

        return $return;
    }

    /**
     * Создание каталога
     *
     * @param string Существующий абсолютный путь до корня
     * @param string Путь к созданию от основного пути
     *
     * @return boolean True if successful
     */
    private static function makePath($base, $path = '')
    {
        // Преобразование Windows путей
        $path = str_replace('\\', '/', $path);
        $path = str_replace('//', '/', $path);

        // Присоединение к одному слешу
        $path = ltrim($path, '/');
        $base = rtrim($base, '/') . '/';

        // проверка на существоание папки
        if (file_exists($base . $path)) {
            return true;
        }

        // Устанавливаем mode для папки
        $origmask = null;
        if (LCore::getCfg('dirperms') == '') {
            $mode = 0777;
        } else {
            $origmask = @umask(0);
            $mode = octdec(LCore::getCfg('dirperms'));

        }

        $parts = explode('/', $path);
        $n = count($parts);
        $ret = true;
        if ($n < 1) {
            if (substr($base, -1, 1) == '/') {
                $base = substr($base, 0, -1);
            }
            $ret = @mkdir($base, $mode);
        } else {
            $path = $base;
            for ($i = 0; $i < $n; $i++) {
                // don't add if part is empty
                if ($parts[$i]) {
                    $path .= $parts[$i] . '/';
                }
                if (!file_exists($path)) {
                    if (!@mkdir(substr($path, 0, -1), $mode)) {
                        $ret = false;
                        break;
                    }
                }
            }
        }
        if (isset($origmask)) {
            @umask($origmask);
        }
        return $ret;
    }
}
