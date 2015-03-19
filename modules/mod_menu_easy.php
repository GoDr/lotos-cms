<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Menu Easy
 * @copyright Авторские права (C) 2000-2013 Gold Dragon.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @dascription Menu Easy -  модуль простого одноуровневого меню для Joostina 1.4.3+
 * @see http://wiki.lotos-cms.ru/index.php/MenuEasy
 */

$mainframe = MainFrame::getInstance();

// подключаем вспомогательный класс
$module->get_helper($mainframe);

// выводим модуль
$module->helper->getHTML($params, $module);











