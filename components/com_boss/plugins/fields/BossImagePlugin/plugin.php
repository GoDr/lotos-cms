<?php
/**
 * @package   Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 *            Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 *            Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */
defined('_LINDEX') or die('STOP in file ' . __FILE__);

class BossImagePlugin
{

    //имя типа поля в выпадающем списке в настройках поля
    var $name = 'Image';

    //тип плагина для записи в таблицы
    var $type = 'BossImagePlugin';

    //отображение поля в категории
    function getListDisplay($directory, $content, $field, $field_values, $conf)
    {
        $return = '';
        $ok = 0;
        $i = 1;
        $field_values = $this->fvalues($field_values);
        while (!$ok) {
            if ($i <= $field_values['nb_images']) {
                $ext_name = chr(ord('a') + $i - 1);
                if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg")) {
                    $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg";
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png")) {
                    $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png";
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif")) {
                    $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif";
                } else {
                    $pic = null;
                }
                if (!is_null($pic)) {
                    $return .= "<img src='" . $pic . "' alt='" . htmlspecialchars(stripslashes(cutLongWord($content->name)), ENT_QUOTES) . "' data-plugin-core='" . $field_values['image_plagins_cat'] . "' />";
                    $ok = 1;
                }
            } else {
                if ((BOSS_NOPIC != "") && (file_exists(_LPATH_TPL_COM . "/com_boss/" . $conf->template . "/images/" . BOSS_NOPIC))) {
                    $return .= "<img src='" . _LPATH_TPL_COM_S . "/com_boss/" . $conf->template . "/images/" . BOSS_NOPIC . "' alt='nopic' data-plugin-core='" . $field_values['image_plagins_cat'] . "' />";
                } else {
                    $return .= "<img src='" . _LPATH_TPL_COM_S . "/com_boss/" . $conf->template . "/images/nopic.gif' alt='nopic' data-plugin-core='" . $field_values['image_plagins_cat'] . "' />";
                }
                $ok = 1;
            }
            $i++;
        }

        if($conf->use_content_plugin == 1){
            $_PLUGINS = mosPluginHandler::getInstance();
            $_PLUGINS->loadBotGroup('content');
            $params = new mosParameters('');
            $row = new stdClass();
            $row->text = $return;
            $row->catid = $content->catid;
            $row->id = $content->id;
            $_PLUGINS->trigger('onPrepareContent', array(&$row, &$params, 0), true);
            $return = $row->text;
        }

        return $return;
    }

    //отображение поля в контенте
    function getDetailsDisplay($directory, $content, $field, $field_values, $conf)
    {
        $fieldname = $field->name;
        $return = '';
        $image_found = 0;
        $field_values = $this->fvalues($field_values);
        $nb = $field_values['nb_images'];

        if (!empty($field->text_before)) {
            $return .= '<span>' . $field->text_before . '</span>';
        }
        if (!empty($field->tags_open)) {
            $return .= html_entity_decode($field->tags_open);
        }

        //начало вывода изображения
        if (($nb != 0) && ($field_values['image_display'] == 'gallery')) {
            echo '<ul id="gallery">';
        }
        for ($i = 1; $i < $nb + 1; $i++) {
            $ext_name = chr(ord('a') + $i - 1);
            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg")) {
                $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg";
                $piclink = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".jpg";
            } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png")) {
                $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png";
                $piclink = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".png";
            } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif")) {
                $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif";
                $piclink = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".gif";
            } else {
                $pic = null;
                $piclink = '';
            }

            if (!is_null($pic)) {
                switch ($field_values['image_display']) {
                    case 'gallery':
                        $return .= "<li><img src='" . $piclink . "' alt='" . htmlspecialchars(stripslashes($content->name), ENT_QUOTES) . "' data-plugin-core='" . $field_values['image_plagins_cnt'] . "' /></li>";
                        break;
                    case 'popup':
                        $return .= "<a href=\"javascript:popup('" . $piclink . "');\"><img src='" . $pic . "' alt='" . htmlspecialchars(stripslashes($content->name), ENT_QUOTES) . "' data-plugin-core='" . $field_values['image_plagins_cnt'] . "' /></a>";
                        break;
                    case 'fancybox':
                        $return .= "<a href='" . $piclink . "'  class='fancybox' rel='fancyboxImg'><img src='" . $pic . "' alt='" . htmlspecialchars(stripslashes($content->name), ENT_QUOTES) . "' data-plugin-core='" . $field_values['image_plagins_cnt'] . "' /></a>";
                        break;
                    case 'default':
                        $return .= "<a href='" . $piclink . "' target='_blank'><img src='" . $pic . "' alt='" . htmlspecialchars(stripslashes($content->name), ENT_QUOTES) . "' data-plugin-core='" . $field_values['image_plagins_cnt'] . "' /></a>";
                        break;
                    default:
                        $return .= "<img src='" . $pic . "' alt='" . htmlspecialchars(stripslashes($content->name), ENT_QUOTES) . "' data-plugin-core='" . $field_values['image_plagins_cnt'] . "' />";
                        break;
                }
                $image_found = 1;
            }
        }
        if (($image_found == 0) && ($nb > 0)) {

            if ((BOSS_NOPIC != "") && (file_exists(_LPATH_TPL_COM . "/com_boss/$conf->template/images/" . BOSS_NOPIC))) {
                $return .= '<img src="' . _LPATH_TPL_COM_S . '/com_boss/' . $conf->template . '/images/' . BOSS_NOPIC . '" alt="nopic" data-plugin-core="' . $field_values['image_plagins_cnt'] . '" />';
            } else {
                $return .= '<img src="' . _LPATH_TPL_COM_S . '/com_boss/' . $conf->template . '/images/nopic.gif" alt="nopic" data-plugin-core="' . $field_values['image_plagins_cnt'] . '" />';
            }
        }

        if (($nb != 0) && ($field_values['image_display'] == 'gallery')) {
            $return .= '</ul>';
        }
        //конец вывода изображения

        if (!empty($field->tags_close)) {
            $return .= html_entity_decode($field->tags_close);
        }
        if (!empty($field->text_after)) {
            $return .= '<span>' . $field->text_after . '</span>';
        }

        if($conf->use_content_plugin == 1){
            $_PLUGINS = mosPluginHandler::getInstance();
            $_PLUGINS->loadBotGroup('content');
            $params = new mosParameters('');
            $row = new stdClass();
            $row->text = $return;
            $row->catid = $content->catid;
            $row->id = $content->id;
            $_PLUGINS->trigger('onPrepareContent', array(&$row, &$params, 0), true);
            $return = $row->text;
        }

        return $return;
    }

    //функция вставки фрагмента ява-скрипта в скрипт
    //сохранения формы при редактировании контента с фронта.
    function addInWriteScript($field)
    {

    }

    //отображение поля в админке в редактировании контента
    function getFormDisplay($directory, $content, $field, $field_values, $nameform = 'adminForm', $mode = "write")
    {
        $database = database::getInstance();
        $row = $database->setQuery("SELECT `fieldtitle`, `fieldvalue` FROM #__boss_" . $directory . "_field_values WHERE fieldid = '$field->fieldid'")->loadObjectList('fieldtitle');

        $return = '';

        if ($mode != "search") {
            $return .= '<table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">';
            for ($i = 1; $i < $row['nb_images']->fieldvalue + 1; $i++) {
                $ext_name = chr(ord('a') + $i - 1);
                if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg")) {
                    $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg";
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png")) {
                    $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png";
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif")) {
                    $pic = _LPATH_SITE . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif";
                } else {
                    $pic = null;
                }

                $return
                    .= '
                        <tr>
                            <td>' . BOSS_FORM_CONTENT_PICTURE . " " . $i . '</td>
                            <td>
                                <input type="file" name="content_picture' . $i . '"/>
                                <br/>';

                if (!is_null($pic)) {
                    $return .= '<td><img src="' . $pic . '"/></td>';
                    $return .= '<td><input type="checkbox" name="cb_image' . $i . '" value="delete">' . BOSS_CONTENT_DELETE_IMAGE . '</td>';
                } else {
                    $return .= '<td></td> <td> </td>';
                }
                $return
                    .= '
                            </td>
                        </tr>';
            }
            $return .= '</table>';
        }
        return $return;
    }

    function onFormSave($directory, $contentid, $field, $isUpdateMode)
    {

        $mainframe = MainFrame::getInstance();
        $isAdmin = $mainframe->isAdmin();
        $database = database::getInstance();
        $conf = $database->setQuery("SELECT `fieldtitle`, `fieldvalue` FROM #__boss_" . $directory . "_field_values WHERE fieldid = '$field->fieldid'")->loadObjectList('fieldtitle');

        $nbImages = $conf['nb_images']->fieldvalue;

        for ($i = 1; $i < $nbImages + 1; $i++) {
            $ext_name = chr(ord('a') + $i - 1);
            $cb_image = mosGetParam($_POST, "cb_image$i", "");
            // image1 delete
            if ($cb_image == "delete") {

                // удаляем все возможные картинки
                if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . "_t.jpg")) {
                    unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . "_t.jpg");
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . "_t.png")) {
                    unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . "_t.png");
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . "_t.gif")) {
                    unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . "_t.gif");
                }

                if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . ".jpg")) {
                    unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . ".jpg");
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . ".png")) {
                    unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . ".png");
                } elseif (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . ".gif")) {
                    unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $contentid . $ext_name . ".gif");
                }
            }

            if (isset($_FILES["content_picture$i"])) {
                if ($_FILES["content_picture$i"]['size'] > $conf['max_image_size']->fieldvalue) {
                    if ($isAdmin) {
                        $url = _LPATH_SITE . "/administrator/index2.php?option=com_boss&act=contents&task=edit&&directory=$directory&tid[]=$contentid";
                    } else {
                        $url = _LPATH_SITE . "/index.php?option=com_boss&amp;act=contents&amp;catid=&amp;directory=$directory";
                    }
                    mosRedirect($url, BOSS_IMAGETOOBIG);
                }
            }

            // image1 upload
            if (isset($_FILES["content_picture$i"]) and !$_FILES["content_picture$i"]['error']) {
                if ($_FILES['content_picture' . $i]['type'] == 'image/gif') {
                    $ext = 'gif';
                } elseif ($_FILES['content_picture' . $i]['type'] == 'image/png') {
                    $ext = 'png';
                } else {
                    $ext = 'jpg';
                }

                createImageAndThumb(
                    $_FILES['content_picture' . $i]['tmp_name'],
                    $_FILES['content_picture' . $i]['name'],
                    _LPATH_ROOT . "/images/boss/$directory/contents/",
                    $contentid . $ext_name . "." . $ext,
                    $contentid . $ext_name . "_t." . $ext,
                    $conf['max_width']->fieldvalue,
                    $conf['max_height']->fieldvalue,
                    $conf['max_width_t']->fieldvalue,
                    $conf['max_height_t']->fieldvalue,
                    $conf['tag']->fieldvalue
                );
            }
        }
        return 1;
    }

    function onDelete($directory, $content)
    {

        $i = 0;
        $isfile = true;
        while ($isfile) {
            $tmp =
            $ext_name = chr(ord('a') + $i);

            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg")) {
                unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.jpg");
            }
            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png")) {
                unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.png");
            }
            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif")) {
                unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . "_t.gif");
            }

            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".jpg")) {
                unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".jpg");
            }else{
                $isfile = false;
            }
            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".png")) {
                unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".png");
            }else{
                $isfile = false;
            }
            if (is_readable(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".gif")) {
                unlink(_LPATH_ROOT . "/images/boss/$directory/contents/" . $content->id . $ext_name . ".gif");
            }else{
                $isfile = false;
            }

            $i++;
        }
        return;
    }

    //отображение поля в админке в настройках поля
    function getEditFieldOptions($row, $directory, $fieldimages, $fieldvalues)
    {
        $return
            = '<div id="divImageOptions">
            <table cellpadding="4" cellspacing="0" border="0" width="100%" class="adminlist">
                <tr>
                    <td>' . BOSS_NB_IMAGES . '</td>
                    <td><input type="text" name="nb_images" id="nb_images" value="' . @$fieldvalues['nb_images']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_NB_IMAGES_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_MAX_IMAGE_SIZE . '</td>
                    <td><input type="text" name="max_image_size" id="max_image_size" value="' . @$fieldvalues['max_image_size']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_MAX_IMAGE_SIZE_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_MAX_IMAGE_WIDTH . '</td>
                    <td><input type="text" name="max_width" id="max_width" value="' . @$fieldvalues['max_width']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_MAX_IMAGE_WIDTH_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_MAX_IMAGE_HEIGHT . '</td>
                    <td><input type="text" name="max_height" id="max_height" value="' . @$fieldvalues['max_height']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_MAX_IMAGE_HEIGHT_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_MAX_THUMBNAIL_WIDTH . '</td>
                    <td><input type="text" name="max_width_t" id="max_width_t" value="' . @$fieldvalues['max_width_t']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_MAX_THUMBNAIL_WIDTH_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_MAX_THUMBNAIL_HEIGHT . '</td>
                    <td><input type="text" name="max_height_t" id="max_height_t" value="' . @$fieldvalues['max_height_t']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_MAX_THUMBNAIL_HEIGHT_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_IMAGE_TAG . '</td>
                    <td><input type="text" name="tag" id="tag" value="' . @$fieldvalues['tag']->fieldvalue . '"/></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_IMAGE_TAG_LONG) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_IMAGE_DISPLAY . '</td>
                    <td>
                        <select id="image_display" name="image_display" style="width: 140px">
                            <option value="none"';
        $return .= (@$fieldvalues['image_display']->fieldvalue == 'none') ? 'selected="selected"' : '';
        $return .= '>' . BOSS_IMAGE_DISPLAY_NONE . '</option>
                            <option value="default"';
        $return .= (@$fieldvalues['image_display']->fieldvalue == 'default') ? 'selected="selected"' : '';
        $return .= '>' . BOSS_IMAGE_DISPLAY_DEFAULT . '</option>
                            <option value="fancybox"';
        $return .= (@$fieldvalues['image_display']->fieldvalue == 'fancybox') ? 'selected="selected"' : '';


        $return .= '>' . BOSS_IMAGE_DISPLAY_FANCY . '</option>
                            <option value="popup"';
        $return .= (@$fieldvalues['image_display']->fieldvalue == 'popup') ? 'selected="selected"' : '';
        $return .= '>' . BOSS_IMAGE_DISPLAY_POPUP . '</option>
                            <option value="gallery"';
        $return .= (@$fieldvalues['image_display']->fieldvalue == 'gallery') ? 'selected="selected"' : '';
        $return .= '>' . BOSS_IMAGE_DISPLAY_GALLERY . '</option>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>' . BOSS_IMAGE_PLAGINS_CAT . '</td>
                    <td>
                    <select id="image_plagins_cat" name="image_plagins_cat">
                            <option value="0"';
        $return .= (@$fieldvalues['image_plagins_cat']->fieldvalue == 0) ? 'selected="selected"' : '';
        $return .= '>' . BOSS_NO . '</option>
                            <option value="1"';
        $return .= (@$fieldvalues['image_plagins_cat']->fieldvalue) ? 'selected="selected"' : '';
        $return .= '>' . BOSS_YES . '</option>

                        </select></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_IMAGE_PLAGINS_DESC) . '</td>
                </tr>
                <tr>
                    <td>' . BOSS_IMAGE_PLAGINS_CNT . '</td>
                    <td>
                    <select id="image_plagins_cnt" name="image_plagins_cnt">
                            <option value="0"';
        $return .= (@$fieldvalues['image_plagins_cnt']->fieldvalue==0) ? 'selected="selected"' : '';
        $return .= '>' . BOSS_NO . '</option>
                            <option value="1"';
        $return .= (@$fieldvalues['image_plagins_cnt']->fieldvalue) ? 'selected="selected"' : '';
        $return .= '>' . BOSS_YES . '</option>

                        </select></td>
                    <td>' . boss_helpers::bossToolTip(BOSS_IMAGE_PLAGINS_DESC) . '</td>
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

        $nb_images = LCore::getParam($_POST, "nb_images", 0, 'i');
        $max_image_size = LCore::getParam($_POST, "max_image_size", 0, 'i');
        $max_width = LCore::getParam($_POST, "max_width", 0, 'i');
        $max_height = LCore::getParam($_POST, "max_height", 0, 'i');
        $max_width_t = LCore::getParam($_POST, "max_width_t", 0, 'i');
        $max_height_t = LCore::getParam($_POST, "max_height_t", 0, 'i');
        $tag = LCore::getParam($_POST, "tag", '', 'sn');
        $image_display = LCore::getParam($_POST, "image_display", '', 'sn');
        $cat_max_width = LCore::getParam($_POST, "cat_max_width", 0, 'i');
        $cat_max_height = LCore::getParam($_POST, "cat_max_height", 0, 'i');
        $cat_max_width_t = LCore::getParam($_POST, "cat_max_width_t", 0, 'i');
        $cat_max_height_t = LCore::getParam($_POST, "cat_max_height_t", 0, 'i');

        $image_plagins_cat = LCore::getParam($_POST, "image_plagins_cat", 0, 'i');
        $image_plagins_cnt = LCore::getParam($_POST, "image_plagins_cnt", 0, 'i');

        $q = "DELETE FROM `#__boss_" . $directory . "_field_values` WHERE `fieldid` = '" . $fieldid . "' ";
        $database->setQuery($q)->query();

        $q = "INSERT INTO #__boss_" . $directory . "_field_values
            (fieldid, fieldtitle, fieldvalue, ordering, sys)
            VALUES
            ($fieldid,'nb_images', '$nb_images',  1,0),
            ($fieldid,'max_image_size', '$max_image_size', 2,0),
            ($fieldid,'max_width', '$max_width', 3,0),
            ($fieldid,'max_height', '$max_height', 4,0),
            ($fieldid,'max_width_t', '$max_width_t', 5,0),
            ($fieldid,'max_height_t', '$max_height_t', 6,0),
            ($fieldid,'tag', '$tag', 7,0),
            ($fieldid,'image_display', '$image_display', 8,0),
            ($fieldid,'cat_max_width', '$cat_max_width', 9,0),
            ($fieldid,'cat_max_height', '$cat_max_height', 10,0),
            ($fieldid,'cat_max_width_t', '$cat_max_width_t', 11,0),
            ($fieldid,'cat_max_height_t', '$cat_max_height_t', 12,0),
            ($fieldid,'image_plagins_cat', '$image_plagins_cat', 13,0),
            ($fieldid,'image_plagins_cnt', '$image_plagins_cnt', 14,0)
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
    function addInHead($field, $field_values)
    {
        static $called;

        $field_values = $this->fvalues($field_values);
        $image_display = $field_values['image_display'];
        $params = array();

        if($called[$image_display] !== null) return $params;
        $called[$image_display]= 1;

        switch ($image_display) {
            case 'gallery':
                $params['custom_head_tag']
                    = '
                                    <style media="screen,projection" type="text/css">

                                    /* begin gallery styling */
                                    #jgal { list-style: none; width: 270px; position: relative; padding-top: 205px;}
                                    #jgal li { opacity: .5; float: left; display: block; width: 64px; height: 48px; background-position: 33% 33%; cursor: pointer; border: 3px solid #fff; outline: 1px solid #ddd; margin-right: 14px; margin-bottom: 14px; }
                                    #jgal li img { position: absolute; top: 0px; left: 0px; display: none; height:190px; }
                                    #jgal li.active img { display: block; }
                                    #jgal li.active, #jgal li:hover { outline-color: #bbb; opacity: .99 /* safari bug */ }

                                    /* styling without javascript */
                                    #gallery { list-style: none; display: block; }
                                    #gallery li { float: left; margin: 0 10px 10px 0; }

                                        </style>
                                        <!--[if lt IE 8]>
                                        <style media="screen,projection" type="text/css">
                                            #jgal li { filter: alpha(opacity=50); }
                                            #jgal li.active, #jgal li:hover { filter: alpha(opacity=100); }
                                        </style>
                                            <![endif]-->
                                        <script>document.write("<style type=\'text/css\'> #gallery { display: none; } </style>");</script>
                                        <!--[if lt IE 6]><style media="screen,projection" type="text/css">#gallery { display: block; }</style>
                                            <![endif]-->
                                            <script>
                                            var gal = {
                                                init : function() {
                                                    if (!document.getElementById || !document.createElement || !document.appendChild) return false;
                                                    if (document.getElementById(\'gallery\')) document.getElementById(\'gallery\').id = \'jgal\';
                                                    var li = document.getElementById(\'jgal\').getElementsByTagName(\'li\');
                                                    li[0].className = \'active\';
                                                    for (i=0; i<li.length; i++) {
                                                        li[i].style.backgroundImage = \'url(\' + li[i].getElementsByTagName(\'img\')[0].src + \')\';
                                                        li[i].style.backgroundRepeat = \'no-repeat\';
                                                        li[i].title = li[i].getElementsByTagName(\'img\')[0].alt;
                                                        gal.addEvent(li[i],\'click\',function() {
                                                            var im = document.getElementById(\'jgal\').getElementsByTagName(\'li\');
                                                            for (j=0; j<im.length; j++) {
                                                                im[j].className = \'\';
                                                            }
                                                            this.className = \'active\';
                                                        });
                                                    }
                                                },
                                                addEvent : function(obj, type, fn) {
                                                    if (obj.addEventListener) {
                                                        obj.addEventListener(type, fn, false);
                                                    }
                                                    else if (obj.attachEvent) {
                                                        obj["e"+type+fn] = fn;
                                                        obj[type+fn] = function() { obj["e"+type+fn]( window.event ); }
                                                        obj.attachEvent("on"+type, obj[type+fn]);
                                                    }
                                                }
                                            }

                                            gal.addEvent(window,\'load\', function() {
                                                gal.init();
                                            });
                                            </script>';
                break;

            case 'popup':
                $params['custom_head_tag']
                    = '
                        <script>
                        function popup(img) {
                        titre="Popup Image";
                        w=open("","image","width=400,height=400,toolbar=no,scrollbars=no,resizable=no");
                        w.document.write(\'<html><head><title>"+titre+"</title></head>\');
                        w.document.write(\'<script>function checksize() { if	(document.images[0].complete) {	window.resizeTo(document.images[0].width+30,document.images[0].height+120); window.focus();} else { setTimeout("checksize()",250) }}<\/script>\');
                        w.document.write(\'<body onload="checksize()" leftMargin=0 topMargin=0 marginwidth=0 marginheight=0>\');
                        w.document.write(\'<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%"><tr>\');
                        w.document.write(\'<td valign="middle" align="center"><img src="\'+img+\'" border=0 alt="Mon image">\');
                        w.document.write(\'</td></tr></table>\');
                        w.document.write(\'</body></html>\');
                        w.document.close();
                        }
                        </script>';
                break;
            case 'fancybox':
                $params['js']['img1'] = _LPATH_SITE . '/includes/js/jquery/plugins/fancybox/jquery.fancybox.js';
                $params['js']['img2'] = _LPATH_SITE . '/includes/js/jquery/plugins/jquery.easing.js';
                $params['css']['img1'] = _LPATH_SITE . '/includes/js/jquery/plugins/fancybox/jquery.fancybox.css';
                $params['custom_script']['image']
                    = '
                      <script>
                          jQuery(document).ready(function() {
                            jQuery("a.fancybox").fancybox();
                          });
                      </script>';
                break;
            default:
                break;
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
}
