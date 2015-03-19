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
 * @description Шестая страница установки
 */

function getContent()
{
    $error = array();

    $DBhostname = (isset($_POST['DBhostname'])) ? trim($_POST['DBhostname']) : '';
    $DBuserName = (isset($_POST['DBuserName'])) ? trim($_POST['DBuserName']) : '';
    $DBpassword = (isset($_POST['DBpassword'])) ? trim($_POST['DBpassword']) : '';
    $DBname = (isset($_POST['DBname'])) ? trim($_POST['DBname']) : '';
    $DBPrefix = (isset($_POST['DBPrefix'])) ? trim($_POST['DBPrefix']) : 'jos_';

    $DBSample = (isset($_POST['DBSample'])) ? trim($_POST['DBSample']) : 1;
    $DBSample = ($DBSample === 0 or $DBSample === '0') ? 0 : 1;

    $sitename = (isset($_POST['sitename'])) ? trim($_POST['sitename']) : '';
    $siteUrl = (isset($_POST['siteUrl'])) ? trim($_POST['siteUrl']) : _LPATH_SITE;
    $siteport = (isset($_POST['siteport'])) ? trim($_POST['siteport']) : _LPATH_SITE_PORT;
    $absolutePath = (isset($_POST['absolutePath'])) ? trim($_POST['absolutePath']) : '';
    $absolutePath = str_replace('\\', '/', $absolutePath);
    $adminLogin = (isset($_POST['adminLogin'])) ? trim($_POST['adminLogin']) : 'admin';
    $adminPassword = (isset($_POST['adminPassword'])) ? trim($_POST['adminPassword']) : '';
    $adminEmail = (isset($_POST['adminEmail'])) ? trim($_POST['adminEmail']) : '';

    $info['left'] = getLeft(1, 1, 1, 0, 0);
    $info['title'] = 'Состояние настроек сайта';

    $info['button'] = getButton(5, 'Далее', 'form');
    $info['content']
        = '<br><div class="install-text">Все настройки можно будет изменить в дальнейшем в ходе администрирования сайта.</div>';

    // проверка названия сайта
    if ($sitename == '') {
        $error[] = 'Не заполнено поле "Название сайта"';
    } else {
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Название сайта - <b><span style="color: #090">OK</span></b></p>';
    }

    // Проверка адреса сайта
    if ($siteUrl == '') {
        $error[] = 'Не заполнено поле "Адрес сайта"';
    } else {
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Адрес сайта - <b><span style="color: #090">OK</span></b></p>';
    }

    // Проверка абсолютного пути
    if ($absolutePath == '') {
        $error[] = 'Не заполнено поле "Абсолютный путь"';
    } else {
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Абсолютный путь - <b><span style="color: #090">OK</span></b></p>';
    }

    // Проверка логина
    if (strlen($adminLogin) < 3) {
        $error[] = 'Поле логина не заполнено или содержит меньше 3-х символов';
    } elseif (strlen($adminLogin) > 30) {
        $error[] = 'Поле логина содержит более 30 символов';
    } else {
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Логин Главного Администратора - <b><span style="color: #090">OK</span></b></p>';
    }

    // Проверка пароля
    if (strlen($adminPassword) < 8) {
        $error[] = 'Поле пароля не заполнено или содержит меньше 8 символов';
    } elseif ((strlen($adminPassword) - strlen(preg_replace("#[^0-9]#", '', $adminPassword))) < 2) {
        $error[] = 'Пароль должен содержать минимум два символа отличных от цифры';
    } else {
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Пароль Главного Администратора - <b><span style="color: #090">OK</span></b></p>';
    }

    // Проверка почты
    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Не верный адрес электронной почты';
    } else {
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Адрес электронной почты Главного Администратора - <b><span style="color: #090">OK</span></b></p>';
    }

    // создаём файл конфигурации
    if (!count($error)) {
        $config = array();

        $config[] = "<?php";
        $config[] = '$mosConfig_absolute_path = "'.$absolutePath.'";';
        $config[] = '$mosConfig_adm_menu_cache = "0";';
        $config[] = '$mosConfig_adm_session_del = "0";';
        $config[] = '$mosConfig_admin_bad_auth = "5";';
        $config[] = '$mosConfig_admin_expired = "1";';
        $config[] = '$mosConfig_admin_redirect_options = "0";';
        $config[] = '$mosConfig_admin_redirect_path = "404.html";';
        $config[] = '$mosConfig_admin_secure_code = "admin";';
        $config[] = '$mosConfig_allowUserRegistration = "1";';
        $config[] = '$mosConfig_author_name = "4";';
        $config[] = '$mosConfig_auto_activ_login = "1";';
        $config[] = '$mosConfig_auto_frontpage = "0";';
        $config[] = '$mosConfig_back_button = "1";';
        $config[] = '$mosConfig_cache_handler = "file";';
        $config[] = '$mosConfig_cache_key = "' . time() . '";';
        $config[] = '$mosConfig_cachepath = "' . $absolutePath . '/cache";';
        $config[] = '$mosConfig_cachetime = "900";';
        $config[] = '$mosConfig_caching = "0";';
        $config[] = '$mosConfig_captcha = "0";';
        $config[] = '$mosConfig_codepress = "0";';
        $config[] = '$mosConfig_com_frontpage_clear = "1";';
        $config[] = '$mosConfig_content_hits = "1";';
        $config[] = '$mosConfig_custom_print = "0";';
        $config[] = '$mosConfig_db = "' . $DBname . '";';
        $config[] = '$mosConfig_dbprefix = "' . $DBPrefix . '";';
        $config[] = '$mosConfig_debug = "0";';
        $config[] = '$mosConfig_dirperms = "";';
        $config[] = '$mosConfig_disable_access_control = "0";';
        $config[] = '$mosConfig_disable_button_help = "1";';
        $config[] = '$mosConfig_disable_checked_out = "0";';
        $config[] = '$mosConfig_disable_date_state = "0";';
        $config[] = '$mosConfig_disable_favicon = "0";';
        $config[] = '$mosConfig_editor = "ckeditor";';
        $config[] = '$mosConfig_enable_admin_secure_code = "0";';
        $config[] = '$mosConfig_enable_log_items = "0";';
        $config[] = '$mosConfig_enable_log_searches = "0";';
        $config[] = '$mosConfig_enable_stats = "0";';
        $config[] = '$mosConfig_error_message = "Сайт недоступен.<br />Пожалуйста, сообщите об этом Администратору";';
        $config[] = '$mosConfig_error_reporting = "6143";';
        $config[] = '$mosConfig_favicon = "favicon.ico";';
        $config[] = '$mosConfig_feed_timeoffset = "00:00";';
        $config[] = '$mosConfig_fileperms = "";';
        $config[] = '$mosConfig_form_date = "%d.%m.%Y";';
        $config[] = '$mosConfig_form_date_full = "%d.%m.%Y %H:%M";';
        $config[] = '$mosConfig_fromname = "' . $sitename . '";';
        $config[] = '$mosConfig_front_debug = "0";';
        $config[] = '$mosConfig_frontend_login = "1";';
        $config[] = '$mosConfig_frontend_userparams = "1";';
        $config[] = '$mosConfig_generator_off = "0";';
        $config[] = '$mosConfig_global_templates = "0";';
        $config[] = '$mosConfig_gz_js_css = "0";';
        $config[] = '$mosConfig_gzip = "0";';
        $config[] = '$mosConfig_helpurl = "http://wiki.lotos-cms.ru/";';
        $config[] = '$mosConfig_hits = "1";';
        $config[] = '$mosConfig_host = "' . $DBhostname . '";';
        $config[] = '$mosConfig_icons = "1";';
        $config[] = '$mosConfig_index_print = "0";';
        $config[] = '$mosConfig_index_tag = "0";';
        $config[] = '$mosConfig_item_navigation = "1";';
        $config[] = '$mosConfig_dir_edit = "";';
        $config[] = '$mosConfig_lang = "russian";';
        $config[] = '$mosConfig_lifetime = "900";';
        $config[] = '$mosConfig_link_titles = "0";';
        $config[] = '$mosConfig_list_limit = "30";';
        $config[] = '$mosConfig_live_site = "' . $siteUrl . '";';
        $config[] = '$mosConfig_locale = "ru_RU.utf8";';
        $config[] = '$mosConfig_mailer = "mail";';
        $config[] = '$mosConfig_mailfrom = "' . $adminEmail . '";';
        $config[] = '$mosConfig_mainbody = "1";';
        $config[] = '$mosConfig_media_dir = "images/stories";';
        $config[] = '$mosConfig_MetaAuthor = "1";';
        $config[]
            = '$mosConfig_MetaDesc = "Lotos CMS - современная система управления содержимым динамичных сайтов и мощная система управления порталами";';
        $config[] = '$mosConfig_MetaKeys = "lotos cms joostina, joostina";';
        $config[] = '$mosConfig_MetaTitle = "1";';
        $config[] = '$mosConfig_mmb_ajax_starts_off = "0";';
        $config[] = '$mosConfig_mmb_content_off = "0";';
        $config[] = '$mosConfig_mmb_mainbody_off = "1";';
        $config[] = '$mosConfig_mmb_system_off = "0";';
        $config[] = '$mosConfig_multilingual_support = "0";';
        $config[] = '$mosConfig_multipage_toc = "1";';
        $config[] = '$mosConfig_no_session_front = "0";';
        $config[] = '$mosConfig_offline = "0";';
        $config[]
            = '$mosConfig_offline_message = "Сайт временно закрыт.<br />Приносим свои извинения! Пожалуйста, зайдите позже.";';
        $config[] = '$mosConfig_offset = "0";';
        $config[] = '$mosConfig_offset_user = "4";';
        $config[] = '$mosConfig_one_template = "...";';
        $config[] = '$mosConfig_optimizetables = "0";';
        $config[] = '$mosConfig_pagetitles = "1";';
        $config[] = '$mosConfig_pagetitles_first = "1";';
        $config[] = '$mosConfig_password = "' . $DBpassword . '";';
        $config[] = '$mosConfig_readmore = "1";';
        $config[] = '$mosConfig_secret = "' . makePassword(6,5,5) . '";';
        $config[] = '$mosConfig_sef = "0";';
        $config[] = '$mosConfig_sendmail = "/usr/sbin/sendmail";';
        $config[] = '$mosConfig_session_life_admin = "1800";';
        $config[] = '$mosConfig_session_type = "0";';
        $config[] = '$mosConfig_showAuthor = "1";';
        $config[] = '$mosConfig_showCreateDate = "1";';
        $config[] = '$mosConfig_showEmail = "1";';
        $config[] = '$mosConfig_showModifyDate = "0";';
        $config[] = '$mosConfig_showPrint = "1";';
        $config[] = '$mosConfig_sitename = "' . $sitename . '";';
        $config[] = '$mosConfig_siteport = "' . trim($siteport) . '";';
        $config[] = '$mosConfig_smtpauth = "0";';
        $config[] = '$mosConfig_smtphost = "localhost";';
        $config[] = '$mosConfig_smtppass = "";';
        $config[] = '$mosConfig_smtpport = "25";';
        $config[] = '$mosConfig_smtpuser = "";';
        $config[] = '$mosConfig_syndicate_off = "0";';
        $config[] = '$mosConfig_tags = "0";';
        $config[] = '$mosConfig_time_generate = "0";';
        $config[] = '$mosConfig_tseparator = " - ";';
        $config[] = '$mosConfig_uid_news = "0";';
        $config[] = '$mosConfig_uniquemail = "1";';
        $config[] = '$mosConfig_user = "' . $DBuserName . '";';
        $config[] = '$mosConfig_useractivation = "1";';
        $config[] = '$mosConfig_vote = "1";';

        $config[] = 'setlocale (LC_TIME, $mosConfig_locale);';

        if (($fp = fopen(_LPATH_ROOT . "/configuration.php", "w"))) {
            $config = implode("\n", $config);
            fputs($fp, $config, strlen($config));
            fclose($fp);
            $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Создание файла конфигурации - <b><span style="color: #090">ОК</span></b></p>';
        } else {
            $error[] = 'Ошибка при создании файл конфигурации';
        }

        // Создание записи Администратора
        $salt = makePassword(6,5,5);
        $crypt = md5($adminPassword . $salt);
        $cryptpass = $crypt . ':' . $salt;

        $installdate = date('Y-m-d H:i:s');
        $database = new mysqli($DBhostname, $DBuserName, $DBpassword, $DBname);

        $sql = "INSERT INTO `" . $DBPrefix
            . "users` VALUES (62, 'Administrator', ?, ?, ?, 'Super Administrator', 0, 1, 25, ?, '00-00-000 00:00', '', '',0, '')";
        $stmt = $database->prepare($sql);
        $stmt->bind_param("ssss", $adminLogin, $adminEmail, $cryptpass, $installdate);
        $stmt->execute();
        $stmt->close();

        $sql = "INSERT INTO `" . $DBPrefix . "core_acl_aro` VALUES (10,'users','62',0,'Administrator',0)";
        $database->query($sql);

        $sql = "INSERT INTO `" . $DBPrefix . "core_acl_groups_aro_map` VALUES (25,'',10)";
        $database->query($sql);
        $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Создание записи Главного Администратора - <b><span style="color: #090">ОК</span></b></p>';

        // Отправка информации о сайте на центральный сервер
        $date_send_server = time() + (30 * 24 * 60 * 60);
        $info_server = "<?php\n";
        $info_server .= "\$date_send_server = '" . $date_send_server . "';\n";

        if ($fp = fopen(_LPATH_ROOT . "/jserver.php", "w")) {
            fputs($fp, $info_server, strlen($info_server));
            fclose($fp);
            $info['content'] .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;Отправка информации о сайте на центральный сервер - <b><span style="color: #090">ОК</span></b></p>';
        }
    }

    if (count($error)) {
        $info['left'] = getLeft(1, 1, 1, 0, 0);
        $info['button'] = getButton(4, 'Вернуться назад', 'form');

        array_unshift($error, '<b>Ошибка!</b>');
        $error = '<br><br><div class="install-text" style="color:red">' . implode(
            '<br>&nbsp;&nbsp;&nbsp;&nbsp;&#149;&nbsp;', $error
        ) . '</div>';

        $info['content'] .= $error . '
        <form action="index.php?page=4" method="post" name="form" id="form">
            <input type="hidden" name="DBhostname" value="' . $DBhostname . '"/>
            <input type="hidden" name="DBuserName" value="' . $DBuserName . '"/>
            <input type="hidden" name="DBpassword" value="' . $DBpassword . '"/>
            <input type="hidden" name="DBname" value="' . $DBname . '"/>
            <input type="hidden" name="DBPrefix" value="' . $DBPrefix . '"/>
            <input type="hidden" name="sitename" value="' . $sitename . '"/>
            <input type="hidden" name="siteUrl" value="' . $siteUrl . '"/>
            <input type="hidden" name="siteport" value="' . $siteport . '"/>
            <input type="hidden" name="absolutePath" value="' . $absolutePath . '"/>
            <input type="hidden" name="adminLogin" value="' . $adminLogin . '"/>
            <input type="hidden" name="adminPassword" value="' . $adminPassword . '"/>
            <input type="hidden" name="adminEmail" value="' . $adminEmail . '"/>
            <input type="hidden" name="DBSample" value="' . $DBSample . '" />
        </form>
    ';
    } else {
        $info['left'] = getLeft(1, 1, 1, 1, 0);
        $info['button'] = getButton(6, 'Далее', 'form2');
        $info['content']
            .= '
        <form action="index.php?page=6" method="post" name="form2" id="form2">
            <input type="hidden" name="siteUrl" value="' . $siteUrl . '"/>
            <input type="hidden" name="siteport" value="' . $siteport . '"/>
            <input type="hidden" name="adminLogin" value="' . $adminLogin . '"/>
            <input type="hidden" name="adminPassword" value="' . $adminPassword . '"/>
            <input type="hidden" name="DBSample" value="' . $DBSample . '" />
        </form>
    ';
    }
    return $info;
}




































