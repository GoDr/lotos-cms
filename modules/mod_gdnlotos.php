<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package     GDNLotos
 * @copyright   Авторские права (C) 2000-2013 Gold Dragon.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @description Модуль позволяет выводить основные материалы по определённым критериям для Joostina 1.4.3.x
 * @see         http://wiki.lotos-cms.ru/index.php/GDNLotos
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);
// подключаем вспомогательный класс
$module->get_helper($mainframe);

// выводим модуль
$module->helper->getHTML($params, $module->id);









