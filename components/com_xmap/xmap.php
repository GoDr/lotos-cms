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

// Подключаем интерфейс
require_once(_LPATH_ADM_COM . '/com_xmap/admin.xmap.intf.php');

// Подключаем XMapAdminClass
require_once(_LPATH_ADM_COM . '/com_xmap/admin.xmap.class.php');

// Подключаем файлы компонента
LCore::requireFilesCom('xmap');




// вывод карты сайта
$XMAP = new XMapClass();

// проверяем включено ли принудительное кеширование
if ($XMAP->getConfig('cache')) {
    $config = Jconfig::getInstance();
    // своё время кеширования
    $def_cachetime = ($XMAP->getConfig('cache')) ? $XMAP->getConfig('cache') : $config->config_cachetime;
    $group = 'com_xmap';
    $handler = 'callback';
    $storage = $config->config_cache_handler;
    $options = array('defaultgroup' => $group, 'cachebase' => $config->config_cachepath . '/', 'lifetime' => $def_cachetime, 'language' => $config->config_lang, 'storage' => $storage);
    $cache = LibCache::getInstance($handler, $options, null);
    $cache->setCaching(1);
} else {
    $cache = mosCache::getCache('com_xmap');
}
$cache->call(array($XMAP, "getMap"));

