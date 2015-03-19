<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS INSTALLATION
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Installation
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

/**
 * Получение версии Lotos CMS
 *
 * @return string
 */
function getVersion()
{
    require_once (_LPATH_ROOT . '/includes/version.php');
    return LVersion::getLongVersion();
}

/**
 * Получение заголовка страницы
 *
 * @param $page  - номер страницы
 * @param $title - название страницы
 *
 * @return string
 */
function getTitle($page, $title)
{
    $result = '<div>';
    $result .= '<div id="title_img"><img src="img/titlepage' . $page . '.png" alt="'.$title.'" /></div>';
    $result .= '<div id="title_txt"><h1>' . $title . '</h1></div>';
    $result .= '</div>';
    return $result;
}

/**
 * Левая часть страницы
 *
 * @param $lefl0 - 0:нет, 1:да
 * @param $lefl1
 * @param $lefl2
 * @param $lefl3
 * @param $lefl4
 *
 * @return string
 */
function getLeft($lefl0, $lefl1, $lefl2, $lefl3, $lefl4)
{
    $result = '<h3 class="left">Процесс установки:</h3>';
    $result .= '<ul class="left">';
    $result .= '<li class="left_' . $lefl0 . '">Проверка системы</li>';
    $result .= '<li class="left_' . $lefl1 . '">Лицензионное соглашение</li>';
    $result .= '<li class="left_' . $lefl2 . '">Настройка базы данных</li>';
    $result .= '<li class="left_' . $lefl3 . '">Настройка сайта</li>';
    $result .= '<li class="left_' . $lefl4 . '">Завершение установки</li>';
    $result .= '</ul>';

    $result .= '<h3 class="left">Помощь:</h3>';
    $result .= '<div>';
    $result .= '<a href="http://lotos-cms.ru/" target="_blank">&bull;&nbsp;&nbsp;Официльный сайт</a>';
    $result .= '<a href="http://forum.lotos-cms.ru/" target="_blank">&bull;&nbsp;&nbsp;Форум поддержки</a>';
    $result .= '<a href="http://wiki.lotos-cms.ru/" target="_blank">&bull;&nbsp;&nbsp;Техническая документация</a>';
    $result .= '</div>';

    return $result;
}

/**
 * Кнопка (навигация)
 * @param int    $page - номер страницы
 * @param string $text - назване кнопки
 * @param string $form - индентификатор для привязки к форме
 *
 * @return string
 */
function getButton($page, $text, $form = '')
{
    if($form != ''){
        $form = ' onclick="document.getElementById(\''. $form . '\').submit(); return false;"';
    }
    $result = '<a class="a_but" href="index.php?page=' . $page . '"'.$form.'>' . $text . '</a>';
    return $result;
}

/**
 * @static Функция определения браузера пользователя
 * @param string $param
 *             'browser' - получить название браузера
 *             'version' - получить версию браузера
 *             'both' - получить название и версию браузера (по умолчанию)
 * @param string $separator - разделитель браузера и версии
 * @return string
 */
function getUserBrowser($param = 'both', $separator = ' '){
    $agent = $_SERVER['HTTP_USER_AGENT'];
    preg_match("/(MSIE|Opera|Firefox|Chrome|Version|Opera Mini|Netscape|Konqueror|SeaMonkey|Camino|Minefield|Iceweasel|K-Meleon|Maxthon)(?:\/| )([0-9.]+)/", $agent, $browser_info);
    list(, $browser, $version) = $browser_info;
    if(preg_match("/Opera ([0-9.]+)/i", $agent, $opera)){
        $browser = 'Opera';
        $version = $opera[1];
    }
    if($browser == 'MSIE'){
        preg_match("/(Maxthon|Avant Browser|MyIE2)/i", $agent, $ie);
        if($ie){
            $browser = $ie[1] . ' based on Internet Explorer';
        }else{
            $browser = 'Internet Explorer';
        }

    }
    if($browser == 'Firefox'){
        preg_match("/(Flock|Navigator|Epiphany)\/([0-9.]+)/", $agent, $ff);
        if($ff){
            $browser = $ff[1];
            $version = $ff[2];
        }
    }
    if($browser == 'Opera' && $version == '9.80'){
        $browser = 'Opera';
        $version =  substr($agent, -5);
    }
    if($browser == 'Version'){
        $browser = 'Safari';
    }
    if(!$browser && strpos($agent, 'Gecko')){
        $browser = 'Browser based on Gecko';
    }
    switch($param){
        case "browser":
            $result = $browser;
            break;
        case "version":
            $result = $version;
            break;
        default:
            $result = $browser . strip_tags($separator) . $version;
    }
    return $result;
}

/**
 * Генерация случайного пароля
 *
 * @param int $length - длина
 *
 * @return string
 */
function makePassword($sign_na = 3, $sign_nb = 3, $sign_nc = 3){
    $result = array();
    $sign_aa = 'abcdefghijklmnopqrstuvwxyz';
    $sign_ab = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $sign_ac = '0123456789';

    // Получаем строчные буквы
    for($i=0; $i < $sign_na; $i++){
        $result[] = $sign_aa[mt_rand(0, strlen($sign_aa)-1)];
    }
    // Получаем строчные буквы
    for($i=0; $i < $sign_nb; $i++){
        $result[] = $sign_ab[mt_rand(0, strlen($sign_ab)-1)];
    }
    // Получаем строчные буквы
    for($i=0; $i < $sign_nc; $i++){
        $result[] = $sign_ac[mt_rand(0, strlen($sign_ac)-1)];
    }
    shuffle($result);
    return implode($result);
}
