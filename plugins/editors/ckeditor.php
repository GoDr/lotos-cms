<?php defined('_LINDEX') or die(__FILE__);

$_PLUGINS->registerFunction('onInitEditor', 'botCKEditorInit');
$_PLUGINS->registerFunction('onGetEditorContents', 'botCKEditorGetContents');
$_PLUGINS->registerFunction('onEditorArea', 'botCKEditorArea');

function botCKEditorInit()
{
    $mainframe = MainFrame::getInstance();
    $mainframe->addJS(_LPATH_SITE . '/plugins/editors/ckeditor/ckeditor.js');
}

function botCKEditorGetContents($editorArea, $hiddenField)
{
}

function botCKEditorArea($name, $content, $hiddenField, $width, $height, $col, $row)
{
    $_PLUGINS = mosPluginHandler::getInstance();
    $results  = $_PLUGINS->trigger('onCustomEditorButton');
    $buttons  = array();
    foreach ($results as $result) {
        if ($result[0]) {
            $buttons[] = '<img src="' . _LPATH_SITE . '/plugins/editors-xtd/' . $result[0] . '" onclick="insertAtCursor( \'' . $hiddenField . '\', \'' . $result[1] . '\' )" data-tooltip="'.$result[1].'" alt="' . $result[1] . '"/>';
        }
    }
    $buttons = implode("", $buttons);

    ?>
    <textarea id="<?php echo $hiddenField; ?>" name="<?php echo $hiddenField; ?>" cols="<?php echo $col; ?>" rows="<?php echo $row; ?>" style="width:<?php echo $width; ?>px; height:<?php echo $height; ?>px;" class="mceEditor"><?php echo $content; ?></textarea>
    <div style="color: #666;">Клавиша &laquo;ENTER&raquo; &rArr; новый абзац (&lt;P>) | Клавиша &laquo;Shift&raquo;+&laquo;ENTER&raquo; &rArr; новая строка (&lt;BR>)</div>
    <div style="margin: 4px 0;"><?php echo $buttons; ?></div>
    <script>
    CKEDITOR.replace('<?php echo $hiddenField;?>', {

            filebrowserBrowseUrl: ['<?php echo _LPATH_SITE; ?>/plugins/editors/ckeditor/kcfinder/browse.php?type=files'],
            filebrowserImageBrowseUrl: ['<?php echo _LPATH_SITE; ?>/plugins/editors/ckeditor/kcfinder/browse.php?type=images'],
            filebrowserFlashBrowseUrl: ['<?php echo _LPATH_SITE; ?>/plugins/editors/ckeditor/kcfinder/browse.php?type=flash'],
            filebrowserUploadUrl: ['<?php echo _LPATH_SITE; ?>/plugins/editors/ckeditor/kcfinder/upload.php?type=files'],
            filebrowserFlashUploadUrl: ['<?php echo _LPATH_SITE; ?>/plugins/editors/ckeditor/kcfinder/upload.php?type=flash'],

            enterMode: [CKEDITOR.ENTER_P],
            shiftEnterMode: [CKEDITOR.ENTER_BR],

            language: ['ru'],

            toolbar: [
                ['Source', '-', 'NewPage', 'Preview', '-', 'Undo', 'Redo', 'Find', '-', 'Replace', 'SelectAll'],
                ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],
                ['TextColor', 'BGColor'],
                ['NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'Outdent', 'Indent'],
                '/',
                ['Format', 'Font', 'FontSize'],
                ['Blockquote', 'CreateDiv'],
                ['Link', 'Unlink', 'Anchor'],
                ['Image', 'Flash', 'MediaEmbed', 'qrc', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe'],
                ['Maximize', 'ShowBlocks'],
                '/'
            ]
        });
    function insertAtCursor(myField, myValue) {
        CKEDITOR.instances[myField].insertText(myValue);
    }
    </script>
<?php
}