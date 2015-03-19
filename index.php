<?php define('_LINDEX', 1);
/**
 * Joostina Lotos CMS 1.4
 *
 * @package   INDEX
 * @version   1.4.1
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2012 Gold Dragon
 * @license   GNU GPL: http://www.gnu.org/licenses/gpl-3.0.html
 *            Joostina Lotos CMS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL. (help/copyright.php)
 * @Date      25.06.2012
 */

//$mainframe = MainFrame::getInstance();
//$mainframe->getCfg('live_site');

// рассчет памяти
define('_MEM_USAGE_START', memory_get_usage());

// Установка флага родительского файла


// корень файлов
define('_LPATH_ROOT', __DIR__);

// подключение основных глобальных переменных
require_once _LPATH_ROOT . '/core/defines.php';

// проверка конфигурационного файла, если не обнаружен, то загружается страница установки
if (!file_exists(_LPATH_ROOT . '/configuration.php')) {
    header('Location: ../installation/index.php');
    exit();
}

// подключение файла конфигурации
require_once (_LPATH_ROOT . '/configuration.php');

// считаем время за которое сгенерирована страница
$mosConfig_time_generate ? $sysstart = microtime(true) : null;

try {
    // подключение главного файла - ядра системы
    require_once (_LPATH_ROOT . '/core/core.php');

    // подключение главного файла - ядра системы
    // TODO GoDr: заменить со временем на core.php
    require_once (_LPATH_ROOT . '/includes/joostina.php');

    // подключение SEF
    LSef::getInstance(LCore::getCfg('sef'), LCore::getCfg('com_frontpage_clear'));

    //Проверка подпапки установки, удалена при работе с SVN
    if (file_exists('installation/index.php')) {
        define('_INSTALL_CHECK', 1);
        include (_LPATH_TPL_SYS . '/offline.php');
        exit();
    }

    $_PLUGINS = mosPluginHandler::getInstance();

    // проверяем, разрешено ли использование системных плагинов
    if (LCore::getCfg('mmb_system_off') == 0) {
        $_PLUGINS->loadBotGroup('system');
        // триггер событий onStart
        $_PLUGINS->trigger('onStart');
    }

    require_once (_LPATH_ROOT . '/includes/frontend.php');

    // mainframe - основная рабочая среда API, осуществляет взаимодействие с 'ядром'
    $mainframe = MainFrame::getInstance();
    $option = $mainframe->option;

    // отображение страницы выключенного сайта
    if (LCore::getCfg('offline') == 1) {
        require (_LPATH_TPL_SYS . '/offline.php');
    }

    // отключение ведения сессий на фронте
    (LCore::getCfg('no_session_front') == 0) ? $mainframe->initSession() : null;


    // триггер событий onAfterStart
    (LCore::getCfg('mmb_system_off') == 0) ? $_PLUGINS->trigger('onAfterStart') : null;

    // путь уменьшения воздействия на шаблоны
    $option = ($option == 'search') ? 'com_search' : $option;

    // загрузка файла русского языка по умолчанию
    $mainframe->set('lang', LCore::getCfg('lang'));
    include_once($mainframe->getLangFile());

    // контроль входа и выхода в фронт-энд
    $return = strval(mosGetParam($_REQUEST, 'return', null));
    $message = intval(mosGetParam($_POST, 'message', 0));

    $my = LCore::getUser();

    $gid = intval($my->gid);

if ($option == 'login') {
    $mainframe->login();
    // Всплывающее сообщение JS
    if ($message) {
        ?>
        <script>
            <!--//
            alert("<?php echo addslashes(_LOGIN_SUCCESS); ?>");
            //-->
        </script>
    <?php
    }

    if ($return && !(strpos($return, 'com_registration') || strpos($return, 'com_login'))) {
        // checks for the presence of a return url
        // and ensures that this url is not the registration or login pages
        // Если sessioncookie существует, редирект на заданную страницу. Otherwise, take an extra round for a cookiecheck
        if (isset($_COOKIE[MainFrame::sessionCookieName()])) {
            mosRedirect($return);
        } else {
            mosRedirect(_LPATH_SITE . '/index.php?option=cookiecheck&return=' . urlencode($return));
        }
    } else {
        // If a sessioncookie exists, redirect to the start page. Otherwise, take an extra round for a cookiecheck
        if (isset($_COOKIE[MainFrame::sessionCookieName()])) {
            mosRedirect(_LPATH_SITE . '/index.php');
        } else {
            mosRedirect(_LPATH_SITE . '/index.php?option=cookiecheck&return=' . urlencode(_LPATH_SITE . '/index.php'));
        }
    }
} elseif ($option == 'logout') {
    $mainframe->logout();

    // Всплывающее сообщение JS
if ($message) {
    ?>
    <script>
        <!--//
        alert("<?php echo addslashes(_LOGOUT_SUCCESS); ?>");
        //-->
    </script>
<?php
}

    if ($return && !(strpos($return, 'com_registration') || strpos($return, 'com_login'))) {
        // checks for the presence of a return url
        // and ensures that this url is not the registration or logout pages
        mosRedirect($return);
    } else {
        mosRedirect(_LPATH_SITE);
    }
} elseif ($option == 'cookiecheck') {
    // No cookie was set upon login. If it is set now, redirect to the given page. Otherwise, show error message.
    if (isset($_COOKIE[MainFrame::sessionCookieName()])) {
        mosRedirect($return);
    } else {
        mosErrorAlert(_ALERT_ENABLED);
    }
}

    // проверка и отсылка информации на центральный сервер
    $mainframe->verifInfoServer();

    // получение шаблона страницы
    $cur_template = $mainframe->getTemplate();
    define('TEMPLATE', $cur_template);

    /* * * *  - Места для хранения информации обработки компонента */
    $_MOS_OPTION = array();

    // подключение функций редактора, т.к. сессии(авторизация ) на фронте отключены - это тоже запрещаем
    if (LCore::getCfg('frontend_login') == 1) {
        LCore::connectionEditor();
    }
    // начало буферизации основного содержимого

    // Содершит подключаемые скрипты в тело BODY
    $_MOS_OPTION['jqueryplugins'] = '';

    ob_start();

    if ($path = $mainframe->getPath('front')) {
        $task = LSef::getTask();
        $ret = mosMenuCheck($option, $task, $gid, $mainframe);
        if ($ret) {
            //Подключаем язык компонента
            if ($mainframe->getLangFile($option)) {
                require_once($mainframe->getLangFile($option));
            }
            require_once ($path);
        } else {
            mosNotAuth();
        }
    } else {
        header('HTTP/1.0 404 Not Found');
        echo _NOT_EXIST;
    }
    $_MOS_OPTION['buffer'] = ob_get_contents(); // главное содержимое - стек вывода компонента - mainbody
    ob_end_clean();

    initGzip();

    header('Content-type: text/html; charset=UTF-8');

    // TODO в будущем проверить правильность кеширования
    if (!LCore::getCfg('caching')) { // не кешируется
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    } elseif ($option != 'logout' or $option != 'login') { // кешируется
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
        header('Cache-Control: max-age=3600');
    }

    (LCore::getCfg('mmb_system_off') == 0) ? $_PLUGINS->trigger('onAfterDispatch') : null;

    // отображение предупреждения о выключенном сайте, при входе админа
    if (defined('_ADMIN_OFFLINE')) {
        include (_LPATH_TPL_SYS . '/offlinebar.php');
    }
    // буферизация итогового содержимого, необходимо для шаблонов группы templates
    ob_start();
    // загрузка файла шаблона
    if (!file_exists(_LPATH_TPL_FRONT . '/' . $cur_template . '/index.php')) {
        echo _TEMPLATE_WARN . $cur_template ;
    } else {
        require_once (_LPATH_TPL_FRONT . '/' . $cur_template . '/index.php');
    }
    $_template_body = ob_get_contents(); // главное содержимое - стек вывода компонента - mainbody
    ob_end_clean();

    // активация плагинов группы mainbody
    if (LCore::getCfg('mmb_mainbody_off') == 0) {
        $_PLUGINS->loadBotGroup('mainbody');
        $_PLUGINS->trigger('onTemplate', array(&$_template_body));
    }

    unset($_PLUGINS, $mainframe, $my, $_MOS_OPTION);

    // вывод стека всего тела страницы, уже после обработки плагинами группы onTemplate
    echo $_template_body;

    // подсчет времени генерации страницы
    echo LCore::getCfg('time_generate') ? '<div id="time_gen">'. _SCRIPT_TIME_USING . round((microtime(true) - $sysstart), 5) . '</div>' : null;


    // вывод лога отладки
    if (LCore::getCfg('debug')) {
        if (defined('_MEM_USAGE_START')) {
            $mem_usage = (memory_get_usage() - _MEM_USAGE_START);
            jd_log_top('<b>' . _SCRIPT_MEMORY_USING . ':</b> ' . sprintf('%0.2f', $mem_usage / 1048576) . ' MB');
        }
        jd_get();
    }

    doGzip();

    // запускаем встроенный оптимизатор таблиц
    (LCore::getCfg('optimizetables') == 1) ? joostina_api::optimizetables() : null;
} catch (Exception $e) {
    echo '<i>Ошибка:</i> ' . $e->getMessage() . '<br>';
    echo '<i>Файл:</i> ' . $e->getFile() . '<br>';
    echo '<i>Строка:</i> ' . $e->getLine();
    _p($e->getTraceAsString());
}
