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

// load html output class
$mainframe = MainFrame::getInstance();
require_once($mainframe->getPath('toolbar_html'));

$task = LSef::getTask();
switch($task){
	case 'configuration':
		TOOLBAR_xmap::_CONFIG();
		break;

	case 'configlink':
		TOOLBAR_xmap::_CONFIGLINK();
		break;

	case 'savelink':
	default:
		TOOLBAR_xmap::_DEFAULT();
		break;
}