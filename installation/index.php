<?php define('_LINDEX', 1);
/**
 * @package   Lotos CMS INSTALLATION
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2014 Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Installation
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// корень файлов
define('_LPATH_ROOT', dirname(dirname(__FILE__)));

require_once(_LPATH_ROOT . '/core/defines.php');

$page = (isset($_GET['page'])) ? intval($_GET['page']) : 0;

// Проверка на существование файла конфигурации
if (file_exists(_LPATH_ROOT . '/configuration.php') and filesize(_LPATH_ROOT . '/configuration.php') > 10 and $page != 6) {
    header("Location: " . _LPATH_ROOT . "/index.php");
    exit();
}

// подключаем дополнительные функции
require_once(_LPATH_ROOT . '/installation/function.php');

require_once(_LPATH_ROOT . '/includes/version.php');

switch ($page) {
    case 6:
        require_once(_LPATH_ROOT . '/installation/page6.php');
        $info = getContent();
        break;
    case 5:
        require_once(_LPATH_ROOT . '/installation/page5.php');
        $info = getContent();
        break;
    case 4:
        require_once(_LPATH_ROOT . '/installation/page4.php');
        $info = getContent();
        break;
    case 3:
        require_once(_LPATH_ROOT . '/installation/page3.php');
        $info = getContent();
        break;
    case 2:
        require_once(_LPATH_ROOT . '/installation/page2.php');
        $info = getContent();
        break;
    case 1:
        require_once(_LPATH_ROOT . '/installation/page1.php');
        $info = getContent();
        break;
    default:
        require_once(_LPATH_ROOT . '/installation/page0.php');
        $page = 0;
        $info = getContent();
}

$info['content_title'] = getTitle($page, $info['title']);

$info['version'] = LVersion::get('CMS') . ' '
    . LVersion::get('CMS_VER') . '.'
    . LVersion::get('DEV_LEVEL') . '.'
    . LVersion::get('BUILD') . '<br>'
    . ' [' . LVersion::get('CODENAME') . ']';


/******************************************************************
 *                  Шаблон
 ******************************************************************/
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lotos CMS. <?php echo $info['title']; ?></title>
    <meta charset="utf8"/>
    <meta name="generator" content="Lotos CMS"/>
    <link rel="shortcut icon" href="favicon.ico"/>
    <link rel="stylesheet" href="install.css" type="text/css"/>
    <script src="../includes/js/jquery/jquery.js"></script>
    <style>
        #qwe1{
            width: 100px;
            box-sizing: border-box;
            border: 10px solid #000;
        }
        #qwe2{
            width: 100px;
            box-sizing: content-box;
            border: 10px solid #000;
        }
       #qwe3{
            width: 100px;
            background-color: #36AA3D;
        }
    </style>
</head>
<body>
<div id="tpl_body">
    <div id="tpl_left">
        <div id="tpl_left_1"></div>
        <div id="tpl_left_2"></div>
        <div id="tpl_left_3">
            <div><?php echo $info['left']; ?></div>
        </div>
    </div>
    <div id="tpl_right">
        <div id="tpl_right_1"></div>
        <div id="tpl_right_2">
            <div>
                <div class="div_but"><?php echo $info['button']; ?></div>
                <?php echo $info['content_title']; ?>
                <?php echo $info['content']; ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div id="tpl_down">
        <div id="tpl_down_1">
            <div><?php echo $info['version']; ?></div>
        </div>
        <div id="tpl_down_2">
            <div>
                <?php echo LVersion::get('LINK') ; ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
</body>
</html>
