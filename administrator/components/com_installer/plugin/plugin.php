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

$mainframe = MainFrame::getInstance();

if(!$acl->acl_check('administration', 'install', 'users', $my->usertype, $element . 's', 'all')){
	mosRedirect('index2.php', _NOT_AUTH);
}

require_once ($mainframe->getPath('installer_html', 'plugin'));
require_once ($mainframe->getPath('installer_class', 'plugin'));
$task = LSef::getTask();
switch($task){
	case 'remove':
		removeElement($client);
		js_menu_cache_clear();
		break;

	default:
		showInstalledPlugins($option);
		js_menu_cache_clear();
		break;
}

/**
 * @param
 */
function removeElement($client){
	josSpoofCheck(null, null, 'request');
	$cid = mosGetParam($_REQUEST, 'cid', array(0));
	$option = mosGetParam($_REQUEST, 'option', 'com_installer');

	if(!is_array($cid)){
		$cid = array(0);
	}

	$installer = new mosInstallerPlugin();
	$result = false;
	if($cid[0]){
		$result = $installer->uninstall($cid[0], $option, $client);
	}

	$msg = $installer->getError();

	mosRedirect($installer->returnTo('com_installer', 'plugin', $client), $result ? _DELETE_SUCCESS . ' ' . $msg : _UNSUCCESS . ' ' . $msg);
}

function showInstalledPlugins($_option){
	$database = database::getInstance();

	$query = "SELECT id, name, folder, element, client_id FROM #__plugins WHERE iscore = 0 ORDER BY folder, name";
	$database->setQuery($query);
	$rows = $database->loadObjectList();

	// path to plugin directory
	$pluginBaseDir = mosPathName(mosPathName(_LPATH_ROOT) . "plugins");

	$id = 0;
	$n = count($rows);
	for($i = 0; $i < $n; $i++){
		$row = $rows[$i];
		// xml file for module
		$xmlfile = $pluginBaseDir . DS . $row->folder . DS . $row->element . ".xml";

		if(file_exists($xmlfile)){
			$xmlDoc = new DOMIT_Lite_Document();
			$xmlDoc->resolveErrors(true);
			if(!$xmlDoc->loadXML($xmlfile, false, true)){
				continue;
			}

			$root = $xmlDoc->documentElement;

			if($root->getTagName() != 'mosinstall'){
				continue;
			}
			if($root->getAttribute("type") != "plugin"){
				continue;
			}

			$element = $root->getElementsByPath('creationDate', 1);
			$row->creationdate = $element ? $element->getText() : '';

			$element = $root->getElementsByPath('author', 1);
			$row->author = $element ? $element->getText() : '';

			$element = $root->getElementsByPath('copyright', 1);
			$row->copyright = $element ? $element->getText() : '';

			$element = $root->getElementsByPath('authorEmail', 1);
			$row->authorEmail = $element ? $element->getText() : '';

			$element = $root->getElementsByPath('authorUrl', 1);
			$row->authorUrl = $element ? $element->getText() : '';

			$element = $root->getElementsByPath('version', 1);
			$row->version = $element ? $element->getText() : '';
		}
	}
	HTML_plugin::showInstalledPlugins($rows, $_option, $id, $xmlfile);
}