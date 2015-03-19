<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * XMap - Компонент создания карт сайта
 *
 * @package   XMap
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
LCore::requireFilesCom('xmap', true);

// Подключаем интерфейс
require_once(_LPATH_ADM_COM . '/com_xmap/admin.xmap.intf.php');

$my = LCore::getUser();

// Проверка доступа к компоненту
if (!($acl->acl_check('administration', 'config', 'users', $my->usertype)) || $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_gdfeedback')) {
    mosRedirect('index2.php', _NOT_AUTH);
}

$task = LSef::getTask();

$XMAP_CLASS = new XMapAdminClass();

// Загружаем скрипт всплывающих подсказок
LHtml::loadToolTip();

switch ($task) {

    // Создание файла карты сайта
    case 'sitemap':
        $XMAP_CLASS->createSitemap();
        break;

    // Запись настроек компонента
    case 'savecfg':
        $XMAP_CLASS->saveConfig();
        break;

    // Настройки компонента
    case 'configuration':
        $XMAP_CLASS->viewConfig();
        break;

    // запись настроек отображения карты сайта
    case 'savelink':
        $XMAP_CLASS->saveLink();
        break;

    // Страница настроек отображения карты сайта
    case 'configlink':
        $XMAP_CLASS->configLink();
        break;

    // CPanel
    default:
        $XMAP_CLASS->defaultPage();
        break;
}

