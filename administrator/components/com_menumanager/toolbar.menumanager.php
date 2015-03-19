<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Component
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      14.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Com_menumanager
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

$mainframe = MainFrame::getInstance();
require_once ($mainframe->getPath('toolbar_html'));

$task = LSef::getTask();

switch($task){
	case 'new':
	case 'edit':
		TOOLBAR_menumanager::_NEWMENU();
		break;

	case 'copy':
		TOOLBAR_menumanager::_COPY();
		break;

	case 'delete':
		TOOLBAR_menumanager::_DELETE();
		break;

	default:
		TOOLBAR_menumanager::_DEFAULT();
		break;
}