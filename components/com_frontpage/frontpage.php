<?php
/**
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

// Подключаем файлы компонента
LCore::requireFilesCom('frontpage');

$fronpage = new FrontPage();

$frontpageConf = (object)null;
$configObject = new frontpageConfig();

$database = LCore::getDB();

$sql = "SELECT `value` FROM `#__config` WHERE `name` = ? AND `group` = ? AND `subgroup` = ?";
$frontpageConf->directory = $configObject->_parseValue($database->selectCell($sql, 'directory', 'com_frontpage', 'default'));

$sql = "SELECT `value` FROM `#__config` WHERE `name` = ? AND `group` = ? AND `subgroup` = ?";
$frontpageConf->task = $configObject->_parseValue($database->selectCell($sql, 'page', 'com_frontpage', 'default'));

$frontpageConf->page_name = $fronpage->getParam('page_name', '');
$frontpageConf->no_site_name = $fronpage->getParam('no_site_name', '');
$frontpageConf->meta_description = $fronpage->getParam('meta_description', '');
$frontpageConf->meta_keywords = $fronpage->getParam('meta_keywords', '');

// code handling has been shifted into content.php
require_once (_LPATH_ROOT . '/components/com_boss/boss.php');