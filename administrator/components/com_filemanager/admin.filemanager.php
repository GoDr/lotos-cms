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

// Подключаем файлы компонента
LCore::requireFilesCom('filemanager', true);

$my = LCore::getUser();

// Проверка доступа к компоненту
if (!($acl->acl_check('administration', 'config', 'users', $my->usertype)) || $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_gdfeedback')) {
    mosRedirect('index2.php', _NOT_AUTH);
}

define('_COM_FM_PATH_S', _LPATH_SITE . '/administrator/components/com_filemanager/elfinder');

LHtml::addJS(_LPATH_SITE . '/includes/js/jquery/jquery.js');
LHtml::addJS(_LPATH_SITE . '/includes/js/jquery/ui.js');

// подключаем ядро elfinder
LHtml::addJS(_COM_FM_PATH_S . '/js/elFinder.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/elFinder.version.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/jquery.elfinder.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/elFinder.resources.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/elFinder.options.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/elFinder.history.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/elFinder.command.js');

// elfinder ui
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/overlay.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/workzone.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/navbar.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/dialog.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/tree.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/cwd.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/toolbar.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/button.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/uploadButton.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/viewbutton.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/searchbutton.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/sortbutton.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/panel.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/contextmenu.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/path.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/stat.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/ui/places.js');

// elfinder commands
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/back.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/forward.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/reload.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/up.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/home.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/copy.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/cut.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/paste.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/open.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/rm.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/info.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/duplicate.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/rename.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/help.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/getfile.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/mkdir.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/mkfile.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/upload.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/download.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/edit.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/quicklook.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/quicklook.plugins.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/extract.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/archive.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/search.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/view.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/resize.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/sort.js');
LHtml::addJS(_COM_FM_PATH_S . '/js/commands/netmount.js');

// подключаем языковой файл
$lang = LCore::getCfg('lang');
switch ($lang) {
    case 'еnglish':
        LHtml::addJS(_COM_FM_PATH_S . '/js/i18n/elfinder.en.js');
        break;
    default:
        LHtml::addJS(_COM_FM_PATH_S . '/js/i18n/elfinder.ru.js');
}

// Подключаем стили
LHtml::addCSS(_LPATH_SITE . '/includes/js/jquery/ui/ui.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/commands.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/common.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/contextmenu.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/cwd.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/dialog.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/fonts.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/navbar.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/places.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/quicklook.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/statusbar.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/theme.css');
LHtml::addCSS(_COM_FM_PATH_S . '/css/toolbar.css');

$path = (empty($mosConfig_dir_edit)) ? $mosConfig_absolute_path : $mosConfig_absolute_path . '/' . $mosConfig_dir_edit;
$url = (empty($mosConfig_dir_edit)) ? $mosConfig_live_site : $mosConfig_live_site . '/' . $mosConfig_dir_edit;

FileManagerAdminHtml::run();