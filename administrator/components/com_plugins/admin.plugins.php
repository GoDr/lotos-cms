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
// ensure user has access to this function
if(!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'plugins', 'all') | $acl->acl_check('administration', 'install', 'users', $my->usertype, 'plugins', 'all'))){
	mosRedirect('index2.php', _NOT_AUTH);
}

require_once ($mainframe->getPath('admin_html'));

$client = strval(mosGetParam($_REQUEST, 'client', ''));

$cid = josGetArrayInts('cid');
$task = LSef::getTask();

switch($task){

	case 'new':
	case 'edit':
		editPlugin(intval($cid[0]), $client);
		break;

	case 'editA':
		editPlugin($id, $client);
		break;

	case 'save':
	case 'apply':
		savePlugin($client, $task);
		break;

	case 'remove':
		removePlugin($cid, $client);
		break;

	case 'cancel':
		cancelPlugin($client);
		break;

	case 'publish':
	case 'unpublish':
		publishPlugin($cid, ($task == 'publish'), $client);
		break;

	case 'orderup':
	case 'orderdown':
		orderPlugin(intval($cid[0]), ($task == 'orderup' ? -1 : 1), $client);
		break;

	case 'accesspublic':
	case 'accessregistered':
	case 'accessspecial':
		accessMenu(intval($cid[0]), $task, $client);
		break;

	case 'saveorder':
		saveOrder($cid);
		break;

	default:
		viewPlugins($client);
		break;
}

/**
 * Compiles a list of installed or defined modules
 */
function viewPlugins($client){
	$mainframe = MainFrame::getInstance();
	$database = database::getInstance();
    $_db = LCore::getDB();

	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', LCore::getCfg('list_limit')));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewcom_pluginslimitstart", 'limitstart', 0));
	$filter_type = $mainframe->getUserStateFromRequest("filter_typecom_plugins{$client}", 'filter_type', 1);
	$search = $mainframe->getUserStateFromRequest("searchcom_plugins{$client}", 'search', '');

    $where = array();
    $sql_date = array();

	if($client == 'admin'){
		$where[] = "m.client_id = ?";
        $sql_date[] = 1;
		$client_id = 1;
	} else{
		$where[] = "m.client_id = ?";
        $sql_date[] = 0;
		$client_id = 0;
	}

	// used by filter
	if($filter_type != 1){
		$where[] = "m.folder = ?";
        $sql_date[] = $filter_type;
	}
	if($search){
		$where[] = "LOWER( m.name ) LIKE ?";
        $sql_date[] = '%' . JString::trim(Jstring::strtolower($search)) . '%';
	}

    $where = (count($where)) ? " WHERE " . implode(' AND ', $where) : '';

    // get the total number of records
	$sql = 'SELECT COUNT(*) FROM `#__plugins` AS m ' . $where;
	$total = $_db->selectCell($sql, $sql_date);

	MainFrame::addLib('pagenavigation');
	$pageNav = new mosPageNav($total, $limitstart, $limit);

    $sql = "SELECT m.*, u.name AS editor, g.name AS groupname
		    FROM `#__plugins` AS m
		    LEFT JOIN `#__users` AS u ON u.id = m.checked_out
		    LEFT JOIN `#__groups` AS g ON g.id = m.access
            " . $where . "
            GROUP BY m.id
		    ORDER BY m.folder ASC, m.ordering ASC, m.name ASC
		    LIMIT ?, ?";
    $rows = $_db->select($sql, $sql_date, $pageNav->limitstart, $pageNav->limit);

	// get list of Positions for dropdown filter
    $sql = "SELECT folder AS value, folder AS text
		    FROM `#__plugins`
		    WHERE client_id = ?
		    GROUP BY folder
		    ORDER BY folder";
	$types[] = LHtml::makeOptionL(1, _SEL_TYPE);
	$types = array_merge($types, $_db->select($sql, (int)$client_id));
	$lists['type'] = LHtml::selectListL($types, 'filter_type', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_type);

	HTML_modules::showPlugins($rows, $client, $pageNav, $lists, $search);
}

/**
 * Saves the module after an edit form submit
 */
function savePlugin($client, $task){
	$database = database::getInstance();
	josSpoofCheck();
	$params = mosGetParam($_POST, 'params', '');
	if(is_array($params)){
		$txt = array();
		foreach($params as $k => $v){
			$txt[] = "$k=$v";
		}

		$_POST['params'] = mosParameters::textareaHandling($txt);
	}

	$row = new mosPlugin($database);
	if(!$row->bind($_POST)){
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	if(!$row->check()){
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	if(!$row->store()){
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	if($client == 'admin'){
		$where = "client_id='1'";
	} else{
		$where = "client_id='0'";
	}
	$row->updateOrder("folder = " . $database->Quote($row->folder) . " AND ordering > -10000 AND ordering < 10000 AND ( $where )");
	$task = LSef::getTask();
	switch($task){
		case 'apply':
			$msg = $row->name . '-- ' . _E_ITEM_SAVED;
			mosRedirect('index2.php?option=com_plugins&client=' . $client . '&task=editA&hidemainmenu=1&id=' . $row->id, $msg);

		case 'save':
		default:
			$msg = $row->name . '-- ' . _E_ITEM_SAVED;
			mosRedirect('index2.php?option=com_plugins&client=' . $client, $msg);
			break;
	}
}

/**
 * Compiles information to add or edit a module
 * @param string The current GET/POST option
 * @param integer The unique id of the record to edit
 */
function editPlugin($uid, $client){
    $my = LCore::getUser();
	$database = database::getInstance();

	$lists = array();
	$row = new mosPlugin($database);

	// load the row from the db table
	$row->load((int)$uid);

	// fail if checked out not by 'me'
	if($row->isCheckedOut($my->id)){
		mosErrorAlert($row->title . ' ' . _COM_PLUGINS_NON_EDIT);
	}

	if($client == 'admin'){
		$where = "client_id='1'";
	} else{
		$where = "client_id='0'";
	}

	// get list of groups
	if($row->access == 99 || $row->client_id == 1){
		$lists['access'] = 'Administrator<input type="hidden" name="access" value="99" />';
	} else{
		// build the html select list for the group access
		$lists['access'] = LAdminMenu::Access($row);
	}

	if($uid){
		$row->checkout($my->id);

		if($row->ordering > -10000 && $row->ordering < 10000){
			// build the html select list for ordering
			$query = "SELECT ordering AS value, name AS text"
				. "\n FROM #__plugins"
				. "\n WHERE folder = "
				. $database->Quote($row->folder)
				. "\n AND published > 0"
				. "\n AND $where"
				. "\n AND ordering > -10000"
				. "\n AND ordering < 10000"
				. "\n ORDER BY ordering";
			$order = mosGetOrderingList($query);
			$lists['ordering'] = LHtml::selectList($order, 'ordering', 'class="inputbox" size="1"', 'value', 'text', intval($row->ordering));
		} else{
			$lists['ordering'] = '<input type="hidden" name="ordering" value="' . $row->ordering . '" />' . _COM_PLUGINS_NON_REORDER;
		}
		$lists['folder'] = '<input type="hidden" name="folder" value="' . $row->folder . '" />' . $row->folder;

		// XML library
		require_once (_LPATH_ROOT . '/includes/domit/xml_domit_lite_include.php');
		// xml file for module
		$xmlfile = _LPATH_ROOT . DS . 'plugins' . DS . $row->folder . DS . $row->element . '.xml';
		$xmlDoc = new DOMIT_Lite_Document();
		$xmlDoc->resolveErrors(true);
		if($xmlDoc->loadXML($xmlfile, false, true)){
			$root = $xmlDoc->documentElement;
			if($root->getTagName() == 'mosinstall' && $root->getAttribute('type') == 'plugin'){
				$element = $root->getElementsByPath('description', 1);
				$row->description = $element ? trim($element->getText()) : '';
			}
		}
	} else{
		$row->folder = '';
		$row->ordering = 999;
		$row->published = 1;
		$row->description = '';

		$folders = mosReadDirectory(_LPATH_ROOT . DS . 'plugins' . DS);
		$folders2 = array();
		foreach($folders as $folder){
			if(is_dir(_LPATH_ROOT . DS . 'plugins' . DS . $folder) && ($folder != 'CVS')){
				$folders2[] = LHtml::makeOption($folder);
			}
		}
		$lists['folder'] = LHtml::selectList($folders2, 'folder', 'class="inputbox" size="1"', 'value', 'text', null);
		$lists['ordering'] = '<input type="hidden" name="ordering" value="' . $row->ordering . '" />' . _NEW_PLUGINS_IN_THE_END;
	}

	$lists['published'] = LHtml::yesnoRadioList('published', 'class="inputbox"', $row->published);

	$path = _LPATH_ROOT . DS . "plugins/$row->folder/$row->element.xml";
	if(!file_exists($path)){
		$path = '';
	}

	// get params definitions
	$params = new mosParameters($row->params, $path, 'plugin');
	HTML_modules::editPlugin($row, $lists, $params);
}

/**
 * Deletes one or more plugins
 * Also deletes associated entries in the #__plugins table.
 * @param array An array of unique category id numbers
 */
function removePlugin($cid, $client){
	josSpoofCheck();
	if(count($cid) < 1){
		echo "<script> alert('" . _CHOOSE_OBJ_DELETE . "'); window.history.go(-1);</script>\n";
		exit;
	}

	mosRedirect('index2.php?option=com_installer&element=plugin&client=' . $client . '&task=remove&cid[]=' . $cid[0] . '&' . josSpoofValue() . '=1');
}

/**
 * Publishes or Unpublishes one or more modules
 * @param array An array of unique category id numbers
 * @param integer 0 if unpublishing, 1 if publishing
 */
function publishPlugin($cid = null, $publish = 1, $client){
    $my = LCore::getUser();
	$database = database::getInstance();
	josSpoofCheck();
	if(count($cid) < 1){
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('" . _CHOOSE_PLUGIN_FOR . " $action'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts($cid);
	$cids = 'id=' . implode(' OR id=', $cid);

	$query = "UPDATE #__plugins SET published = " . (int)$publish . "\n WHERE ( $cids ) AND ( checked_out = 0 OR ( checked_out = " . (int)$my->id . " ) )";
	$database->setQuery($query);
	if(!$database->query()){
		echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
		exit();
	}

	if(count($cid) == 1){
		$row = new mosPlugin($database);
		$row->checkin($cid[0]);
	}

	mosRedirect('index2.php?option=com_plugins&client=' . $client);
}

/**
 * Cancels an edit operation
 */
function cancelPlugin($client){
	$database = database::getInstance();
	josSpoofCheck();
	$row = new mosPlugin($database);
	$row->bind($_POST);
	$row->checkin();

	mosRedirect('index2.php?option=com_plugins&client=' . $client);
}

/**
 * Moves the order of a record
 * @param integer The unique id of record
 * @param integer The increment to reorder by
 */
function orderPlugin($uid, $inc, $client){
	$database = database::getInstance();
	josSpoofCheck();
	// Currently Unsupported
	if($client == 'admin'){
		$where = "client_id = 1";
	} else{
		$where = "client_id = 0";
	}
	$row = new mosPlugin($database);
	$row->load((int)$uid);
	$row->move($inc, "folder=" . $database->Quote($row->folder) . " AND ordering > -10000 AND ordering < 10000 AND ($where)");

	mosRedirect('index2.php?option=com_plugins');
}

/**
 * changes the access level of a record
 * @param integer The increment to reorder by
 */
function accessMenu($uid, $access, $client){
	$database = database::getInstance();
	josSpoofCheck();
	switch($access){
		case 'accesspublic':
			$access = 0;
			break;

		case 'accessregistered':
			$access = 1;
			break;

		case 'accessspecial':
			$access = 2;
			break;
	}

	$row = new mosPlugin($database);
	$row->load((int)$uid);
	$row->access = $access;

	if(!$row->check()){
		return $row->getError();
	}
	if(!$row->store()){
		return $row->getError();
	}

	mosRedirect('index2.php?option=com_plugins');
}

function saveOrder($cid){
	$database = database::getInstance();
	josSpoofCheck();
	$total = count($cid);
	$order = josGetArrayInts('order');

	$row = new mosPlugin($database);
	$conditions = array();

	// update ordering values
	for($i = 0; $i < $total; $i++){
		$row->load((int)$cid[$i]);
		if($row->ordering != $order[$i]){
			$row->ordering = $order[$i];
			if(!$row->store()){
				echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
				exit();
			} // if
			// remember to updateOrder this group
			$condition = "folder = " . $database->Quote($row->folder) . " AND ordering > -10000 AND ordering < 10000 AND client_id = " . (int)$row->client_id;
			$found = false;
			foreach($conditions as $cond)
				if($cond[1] == $condition){
					$found = true;
					break;
				} // if
			if(!$found) $conditions[] = array($row->id, $condition);
		} // if
	} // for

	// execute updateOrder for each group
	foreach($conditions as $cond){
		$row->load($cond[0]);
		$row->updateOrder($cond[1]);
	} // foreach

	$msg = _NEW_ORDER_SAVED;
	mosRedirect('index2.php?option=com_plugins', $msg);
} // saveOrder