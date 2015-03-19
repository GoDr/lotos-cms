<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS DEFINITIONS
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

if(!defined('_LPATH_ROOT')){
    define('_LPATH_ROOT', dirname(dirname((__FILE__))));
}

define('DS', DIRECTORY_SEPARATOR);

// абсолютный путь до библиотек
define('_LPATH_LIBRARIES', _LPATH_ROOT . '/libraries');

// абсолютный путь до sef-файлов
define('_LPATH_SEF', _LPATH_ROOT . '/settings/sef');

// абсолютный путь до lnk-файлов
define('_LPATH_LNK', _LPATH_ROOT . '/settings/linking');

// абсолютный путь до каталога панели управления
define('_LPATH_ADMINISTRATOR', _LPATH_ROOT . '/administrator');

// абсолютный путь до каталога компонентов панели управления
define('_LPATH_ADM_COM', _LPATH_ROOT . '/administrator/components');

// абсолютный путь до каталога компонентов фронта
define('_LPATH_COM', _LPATH_ROOT . '/components');

// абсолютный путь до каталога с языковыми файлами
define('_LPATH_LANG', _LPATH_ROOT . '/languages');

// абсолютный путь до шаблонов панели управления
define('_LPATH_TPL_ADMI', _LPATH_ROOT . '/templates/admin');

// абсолютный путь до шаблонов компонентов
define('_LPATH_TPL_COM', _LPATH_ROOT . '/templates/components');

// абсолютный путь до шаблонов сайта
define('_LPATH_TPL_FRONT', _LPATH_ROOT . '/templates/front');

// абсолютный путь до шаблонов модулей
define('_LPATH_TPL_MOD', _LPATH_ROOT . '/templates/modules');

// абсолютный путь до системных шаблонов
define('_LPATH_TPL_SYS', _LPATH_ROOT . '/templates/system');

// Порт сайта
define('_LPATH_SITE_PORT', $_SERVER['SERVER_PORT']);

// Адрес сайта
define('_LPATH_SITE', "http://" . $_SERVER['SERVER_NAME'] . ((_LPATH_SITE_PORT == '80') ? '' : ':' . _LPATH_SITE_PORT));

// URL-путь до шаблонов панели управления
define('_LPATH_TPL_ADMI_S', _LPATH_SITE . '/templates/admin');

// URL-путь до шаблонов компонентов
define('_LPATH_TPL_COM_S', _LPATH_SITE . '/templates/components');

// URL-путь до шаблонов сайта
define('_LPATH_TPL_FRONT_S', _LPATH_SITE . '/templates/front');

// URL-путь до шаблонов модулей
define('_LPATH_TPL_MOD_S', _LPATH_SITE . '/templates/modules');

// URL-путь до системных шаблонов
define('_LPATH_TPL_SYS_S', _LPATH_SITE . '/templates/system');

// URL-путь до картинки "нет изображения"
define('_LPATH_IMG_NODEF', _LPATH_SITE . '/images/noimage.jpg');

// функции отладки
function _x($var, $i = false)
{
    echo '<pre style="border:2px solid #ff0000;color:#ff0000;padding:5px;background-color:#ffffff;">';
    var_export($var);
    echo "</pre>";
    if ($i) {
        die();
    }
}

function _v($var, $i = false)
{
    echo '<pre style="border:2px solid #ff0000;color:#ff0000;padding:5px;background-color:#ffffff;">';
    var_dump($var);
    echo "</pre>";
    if ($i) {
        die();
    }
}

function _p($var, $i = false)
{
    echo '<pre style="border:2px solid #ff0000;color:#ff0000;padding:5px;background-color:#ffffff;">';
    print_r($var);
    echo "</pre>";
    if ($i) {
        die();
    }
}

function _a($var = null, $i = false)
{
    echo '<span style="border:1px solid #ff0000;color:#ff0000;padding:1px;background-color:#ffffff;">';
    if (is_null($var)) {
        echo '+++++++++';
    } else {
        echo $var;
    }
    echo '</span>';
    if ($i) {
        die();
    }

}

function _m(){
    static $var = 0;
    echo '<pre style="border:2px solid #ff0000;color:#ff0000;padding:5px;background-color:#ffffff;">';
    print_r($var);
    echo "</pre>";
    $var++;
}

function _hr(){
    echo '<hr>';
}


























