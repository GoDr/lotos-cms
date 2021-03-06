<?php
/**
 * @package   Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */
defined('_LINDEX') or die('STOP in file ' . __FILE__);

class BossTextAreaEditorPlugin
{

    //имя типа поля в выпадающем списке в настройках поля
    var $name = 'Editor Text Area';

    //тип плагина для записи в таблицы
    var $type = 'BossTextAreaEditorPlugin';

    //отображение поля в категории
    function getListDisplay($directory, $content, $field, $field_values, $conf)
    {
        return $this->getDetailsDisplay($directory, $content, $field, $field_values, $conf);
    }

    //отображение поля в контенте
    function getDetailsDisplay($directory, $content, $field, $field_values, $conf)
    {
        $fieldname = $field->name;
        $return = '';
        if (isset ($content->$fieldname)) {
            if (!empty($field->text_before)) {
                $return .= '<span>' . $field->text_before . '</span>';
            }
            if (!empty($field->tags_open)) {
                $return .= html_entity_decode($field->tags_open);
            }

            if ($conf->use_content_plugin == 1) {
                $_PLUGINS = mosPluginHandler::getInstance();
                $_PLUGINS->loadBotGroup('content');
                $params = new mosParameters('');
                $row = new stdClass();
                $row->text = $content->$fieldname;
                $row->id = $content->id;
                $row->access = 0;
                $row->catid = $content->catid;
                $row->title = $content->name;
                $row->readmore = '';
                $_PLUGINS->trigger('onPrepareContent', array(&$row, &$params, 0), true);
                $content->$fieldname = $row->text;
            }

            $return .= $content->$fieldname;

            if (!empty($field->tags_close)) {
                $return .= html_entity_decode($field->tags_close);
            }
            if (!empty($field->text_after)) {
                $return .= '<span>' . $field->text_after . '</span>';
            }
        }
        return $return;
    }

    //функция вставки фрагмента ява-скрипта в скрипт
    //сохранения формы при редактировании контента с фронта.
    function addInWriteScript($field)
    {
        $_PLUGINS = mosPluginHandler::getInstance();
        $return = '';
        $arrayEditors = $_PLUGINS->trigger('onGetEditorContents', array('editor_' . $field->name, $field->name));
        foreach ($arrayEditors as $editor) {
            $return .= $editor;
        }

        if ($field->required == 1) {

            // проверяем какой редактор подключен
            if ($_PLUGINS->_bots[0]->element == 'ckeditor') {
                $return .= 'var editor_' . $field->name . ' = CKEDITOR.instances["' . $field->name . '"].getData()';
            } else {
                $return .= 'var editor_' . $field->name . ' = document.getElementById("' . $field->name . '").value;';
            }
            $return
                .= "
                 if (editor_" . $field->name . " == '') {
                    errorMSG += '" . $field->title . " " . html_entity_decode(addslashes(BOSS_REGWARN_ERROR), ENT_QUOTES) . "';
                    iserror = 1;
                }
                ";
        }
        return $return;
    }

    //отображение поля в админке в редактировании контента
    function getFormDisplay($directory, $content, $field, $field_values, $nameform = 'adminForm', $mode = "write")
    {
        $mainframe = MainFrame::getInstance();;
        $fieldname = $field->name;
        $return = "";
        if ($mode == "search") {
            $strtitle = htmlentities(jdGetLangDefinition($field->title), ENT_QUOTES, 'utf-8');
            $value = mosGetParam($_REQUEST, $field->name, '');
            $return .= "<input class='boss' id='" . $field->name . "' type='text' name='" . $field->name . "' mosLabel='" . $strtitle . "' size='$field->size' maxlength='$field->maxlength' value='" . htmlspecialchars(
                    $value, ENT_QUOTES
                ) . "' />\n";
        } else {
            $value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
            $mainframe = MainFrame::getInstance();
            $mainframe->set('allow_wysiwyg', 1);
            $mainframe->set('loadEditor', true);
            initEditor();

            ob_start();
            editorArea('editor_' . $field->name, htmlspecialchars($value, ENT_QUOTES, 'utf-8'), $field->name, '100%', 250, $field->cols, $field->rows);
            $return .= ob_get_contents();
            ob_end_clean();
        }

        return $return;
    }

    function onFormSave($directory, $contentid, $field, $isUpdateMode)
    {
        $return = mosGetParam($_POST, $field->name, "", _MOS_ALLOWHTML);
        return $return;
    }

    function onDelete($directory, $content)
    {
        return;
    }

    //отображение поля в админке в настройках поля
    function getEditFieldOptions($row, $directory, $fieldimages, $fieldvalues)
    {
        $return
            = '
            <div id="divTextLength">
            <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
                <tr>
                    <td width="20%">' . BOSS_FIELD_MAX_LENGTH . '</td> ';

        if (!isset($row->maxlength) || ($row->maxlength == "")) {
            $row->maxlength = 20;
        }
        $return
            .= '
                    <td width="20%"><input type="text" name="maxlength" mosLabel="Max Length" class="inputbox"
                                           value="' . $row->maxlength . '"/></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>

        <div id=divColsRows>
            <table cellpadding="4" cellspacing="1" border="0" width="100%" class="adminform">
                <tr>
                    <td width="20%">' . BOSS_FIELD_COLS . '</td>
                    <td width="20%"><input type="text" name="cols" mosLabel="Cols" class="inputbox"
                                           value="' . $row->cols . '"/></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="20%">' . BOSS_FIELD_ROWS . '</td>
                    <td width="20%"><input type="text" name="rows" mosLabel="Rows" class="inputbox"
                                           value="' . $row->rows . '"/></td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
            ';
        return $return;
    }

    //действия при сохранении настроек поля
    function saveFieldOptions($directory, $field)
    {
        //если плагин не создает собственных таблиц а пользется таблицами босса то возвращаем false
        //иначе true
        return false;
    }

    //расположение иконки плагина начиная со слеша от корня сайта
    function getFieldIcon()
    {
        return "/components/com_boss/plugins/fields/" . __CLASS__ . "/images/application_edit.png";
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
        $value = mosGetParam($_REQUEST, $fieldName, "");
        if ($value != "") {
            $search .= " AND a.$fieldName LIKE '%$value%'";
        }
        return $search;
    }
}
