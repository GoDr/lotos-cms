<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * FileManager - Компонент файлового менеджера
 *
 * @package   FileManager
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2014 Gold Dragon
 * @date      01.07.2014
 * @see       http://wiki.lotos-cms.ru/index.php/XMap
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

class FileManagerAdminHtml{
    public static function run(){
        ?>
        <script>
            $(document).ready(function () {
                var options = {
                    url: '<?php echo _COM_FM_PATH_S; ?>/php/connector.php',
                    lang: 'ru',
                    dateFormat: 'd.m.Y H:i',
                    'requestType': 'post'
                }
                $('#comfilemanager').elfinder(options);
            });
        </script>
        <div id="comfilemanager"></div>
        <?php
    }
}
