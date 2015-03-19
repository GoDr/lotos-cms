<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);
/**
 * @package     Lotos CMS INSTALLATION
 * @version     1.0
 * @author      Lotos CMS <support@lotos-cms.ru>
 * @link        http://lotos-cms.ru
 * @copyright   Авторские права (C) 2014 Lotos CMS.
 * @date        01.01.2014
 * @see         http://wiki.lotos-cms.ru/index.php/Installation
 * @license     MIT License: /copyright/MIT_License.lic
 *              Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description Первая страница установки
 */

function getContent()
{
    $info['left'] = getLeft(0, 0, 0, 0, 0);
    $info['title'] = 'Проверка системы';

    $info['button'] = getButton(0, 'Проверить снова');
    $info['button'] .= getButton(1, 'Далее');

    $check['bra_name'] = getUserBrowser('browser');
    $check['bra_ver'] = getUserBrowser('version');;
    $check['bra_name'] = ($check['bra_name'] == 'Internet Explorer' and intval($check['bra_ver']) < 10) ? '<span style="color:#ff0000">' . $check['bra_name'] . '</span>' : $check['bra_name'];

    // проверка настроек PHP
    $check['php_rec'] = (version_compare(PHP_VERSION, '5.3.1')) ? '<span style="color:green">' . phpversion() . '</span>' : '<span style="color:#ff0000">' . phpversion() . '</span>';
    $check['zlib'] = (extension_loaded('zlib')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['xml'] = (extension_loaded('xml')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['SimpleXML'] = (extension_loaded('SimpleXML')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['mysqli'] = (class_exists('mysqli')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['mysqli_result'] = (class_exists('mysqli_result')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    if (@file_exists(_LPATH_ROOT . '/configuration.php') and @is_writable(_LPATH_ROOT . '/configuration.php')) {
        $check['configuration'] = '<b><span style="color:green">Доступен для записи</span></b>';
    } else {
        if (is_writable('..')) {
            $check['configuration'] = '<b><span style="color:green">Доступен для записи</span></b>';
        } else {
            $check['configuration']
                = '<b><span style="color:#ff0000">Недоступен для записи</span></b><br /><span class="small">Вы можете продолжать установку, значения файла конфигурации будут показаны в конце. ОБЯЗАТЕЛЬНО СОХРАНИТЕ ЕГО: скопируйте/вставьте содержимое в созданный вами файл configuration.php и загрузите на сервер!</span>';
        }
    }
    $check['mbstring'] = (extension_loaded('mbstring')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['curl'] = (extension_loaded('curl')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['json'] = (extension_loaded('json')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['iconv'] = (extension_loaded('iconv')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';
    $check['pcre'] = (extension_loaded('pcre')) ? '<span style="color:green">Доступно</span>' : '<span style="color:#ff0000">Недоступна</span>';

    $sp = ini_get('session.save_path');
    $check['sp1'] = (is_writable($sp)) ? '<span style="color:green">Доступен для записи</span>' : '<span style="color:#ff0000">Недоступен для записи</span>';
    $check['sp2'] = $sp ? $sp : '<span style="color:#ff0000">Не установлен</span>';

    $check['display_errors'] = (ini_get('display_errors') == 0) ? '<span style="color:green">OFF</span>' : '<span style="color:red">ON</span>';
    $check['file_uploads'] = (ini_get('file_uploads') == 1) ? '<span style="color:green">ON</span>' : '<span style="color:red">OFF</span>';
    $check['output_buffering'] = (ini_get('output_buffering') == 0) ? '<span style="color:green">OFF</span>' : '<span style="color:red">ON</span>';
    $check['session_auto_start'] = (ini_get('session.auto_start') == 0) ? '<span style="color:green">OFF</span>' : '<span style="color:red">ON</span>';


    $info['content']
        = '
        <script>
        <!--
            $(function(){
                $("#js").html(\'<span style="color:green">Включен</span>\');
            });
        // -->
        </script>
        <h1>Проверка браузера:</h1>
        <div class="install-text">Если ваш браузер будет выделен <b><span style="color:#ff0000">красным цветом</span></b>, то у вас могут возникнуть проблемы при администрировании сайта.
            Если у вас выключена поддержка <b>JavaScript</b>, вы не сможете удачно завершить установку Lotos CMS.
        </div>
        <table class="content">
            <tr>
                <td>Имя браузера</td>
                <td><b>' . $check['bra_name'] . '</b></td>
            </tr>
            <tr>
                <td>Версия браузера:</td>
                <td><b>' . $check['bra_ver'] . '</b></td>
            </tr>
            <tr>
                <td>Поддержка JavaScript</td>
                <td><b id="js"><span style="color:red">Выключен</span></b>
                </td>
            </tr>
        </table>

        <h1>Проверка настроек сервера:</h1>
        <div class="install-text">Если на сервере имеются настройки, способные привести к ошибкам во время установки или работы Lotos CMS, то на этой странице они будут отмечены <b><span style="color:#ff0000">красным цветом</span></b>.
            Для полноценной и беспроблемной работы системы рекомендуем исправить все необходимые настройки.
        </div>
        <table class="content">
            <tr>
                <td>Версия PHP &#062;= 5.3.1</td>
                <td><b>' . $check['php_rec'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка zlib-сжатия</td>
                <td><b>' . $check['zlib'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка XML</td>
                <td><b>' . $check['xml'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка XML</td>
                <td><b>' . $check['SimpleXML'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка MySQLi</td>
                <td><b>' . $check['mysqli'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка MySQLi_Result</td>
                <td><b>' . $check['mysqli_result'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка MB-функций</td>
                <td><b>' . $check['mbstring'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка cURL</td>
                <td><b>' . $check['curl'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка JSON</td>
                <td><b>' . $check['json'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка iconv</td>
                <td><b>' . $check['iconv'] . '</b></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp; - поддержка PCRE</td>
                <td><b>' . $check['pcre'] . '</b></td>
            </tr>
            <tr>
                <td>Файл <b>configuration.php</b></td>
                <td>' . $check['configuration'] . '</td>
            </tr>
            <tr>
                <td>Каталог для записи сессий</td>
                <td><b>' . $check['sp1'] . '</b></td>
            </tr>
            <tr>
                <td>Каталог для записи сессий</td>
                <td><b>' . $check['sp2'] . '</b></td>
            </tr>
        </table>
        <h1>Рекомендуемые параметры PHP:</h1>
        <div class="install-text">Эти параметры PHP рекомендуются для полной совместимости с Lotos CMS.<br/>Однако, Lotos CMS будет работать, если эти параметры не в полной мере соответствуют рекомендуемым.</div>
        <table class="content">
            <tr>
                <th>Директива</th>
                <th>Рекомендовано</th>
                <th>Установлено</th>
                <th>Место изменения</th>
                <th>Примечание</th>
            </tr>
            <tr>
                <td>Display Errors</td>
                <td>OFF</td>
                <td><b>' . $check['display_errors'] . '</b></td>
                <td>PHP_INI_ALL</td>
                <td></td>
            </tr>
            <tr>
                <td>File Uploads</td>
                <td>ON</td>
                <td><b>' . $check['file_uploads'] . '</b></td>
                <td>PHP_INI_SYSTEM</td>
                <td>PHP_INI_ALL в PHP &#060;= 4.2.3. Доступна с версии PHP 4.0</td>
            </tr>
            <tr>
                <td>Output Buffering</td>
                <td>OFF</td>
                <td><b>' . $check['output_buffering'] . '</b></td>
                <td>PHP_INI_PERDIR</td>
                <td></td>
            </tr>
            <tr>
                <td>Session auto start</td>
                <td>OFF</td>
                <td><b>' . $check['session_auto_start'] . '</b></td>
                <td>PHP_INI_ALL</td>
                <td></td>
            </tr>
        </table>

        <h1>Права доступа к файлам и каталогам:</h1>
        <div class="install-text">Для нормальной работы Lotos CMS необходимо, чтобы на определенные файлы и каталоги были установлены права записи. Если вы видите <b><span style="color:#ff0000">Недоступен для записи</span></b> для некоторых файлов и каталогов, то необходимо установить на них права доступа, позволяющие перезаписывать их.</div>
        ';

    $dirs = array('administrator/components',
                  'administrator/modules',
                  'cache',
                  'components',
                  'images',
                  'images/backups',
                  'images/show',
                  'images/stories',
                  'languages',
                  'plugins/content',
                  'plugins/editors',
                  'plugins/editors-xtd',
                  'plugins/search',
                  'plugins/system',
                  'media',
                  'modules',
                  'settings',
                  'templates/admin',
                  'templates/components',
                  'templates/front',
                  'templates/modules',
                  'templates/system'
    );
    $dirs_result = '';
    foreach ($dirs as $dir) {
        if (is_writable(_LPATH_ROOT . '/' . $dir)) {
            // каталоги в которые запись разрешена
            $dirs_result .= '<tr><td class="item">' . $dir . '/</td><td style="text-align:right"><b><span style="color:green">Доступен для записи</span></b></tr>';
        } else {
            // каталоги в которые запись запрещена
            $dirs_result .= '<tr><td class="item">' . $dir . '/</td><td style="text-align:right"><b><span style="color:#ff0000">Недоступен для записи</span></b></tr>';
        }
    }
    $info['content'] .= '<table class="content">' . $dirs_result . '</table>';

    return $info;
}

