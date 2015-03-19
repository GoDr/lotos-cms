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
if(!($acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all') | $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'com_poll'))){
	mosRedirect('index2.php', _NOT_AUTH);
}

require_once ($mainframe->getPath('admin_html'));
require_once ($mainframe->getPath('class'));

$cid = josGetArrayInts('cid');
$task = LSef::getTask();
switch($task){
	case 'new':
		editPoll(0);
		break;

	case 'edit':
		editPoll(intval($cid[0]));
		break;

	case 'editA':
		editPoll($id);
		break;

	case 'save':
		savePoll();
		break;

	case 'remove':
		removePoll($cid);
		break;

	case 'publish':
		publishPolls($cid, 1);
		break;

	case 'unpublish':
		publishPolls($cid, 0);
		break;

	case 'cancel':
		cancelPoll();
		break;

	default:
		showPolls();
		break;
}

function showPolls(){
	$mainframe = MainFrame::getInstance();
    $_db = LCore::getDB();

	$limit = intval($mainframe->getUserStateFromRequest("viewlistlimit", 'limit', LCore::getCfg('list_limit')));
	$limitstart = intval($mainframe->getUserStateFromRequest("viewcom_polllimitstart", 'limitstart', 0));

	$sql = "SELECT COUNT(*) FROM `#__polls`";
	$total = $_db->selectCell($sql);

	MainFrame::addLib('pagenavigation');
	$pageNav = new mosPageNav($total, $limitstart, $limit);

	$sql = "SELECT m.*, u.name AS editor, COUNT(d.id) AS numoptions
            FROM `#__polls` AS m
            LEFT JOIN `#__users` AS u ON u.id = m.checked_out
            LEFT JOIN `#__poll_data` AS d ON d.pollid = m.id AND d.text != ?
            GROUP BY m.id
            LIMIT ?, ?";
	$rows = $_db->select($sql, '', $pageNav->limitstart, $pageNav->limit);

	HTML_poll::showPolls($rows, $pageNav);
}

function editPoll($uid = 0){
    $my = LCore::getUser();
	$database = database::getInstance();

	$row = new mosPoll($database);
	// load the row from the db table
	$row->load((int)$uid);

	// fail if checked out not by 'me'
	if($row->isCheckedOut($my->id)){
		mosRedirect('index2.php?option=com_poll', $row->title . ' - ' . _POLL_IS_BEING_EDITED_BY_ADMIN);
	}

	$options = array();

	if($uid){
		$row->checkout($my->id);
		$query = "SELECT id, text FROM `#__poll_data` WHERE pollid = " . (int)$uid . " ORDER BY id";
		$database->setQuery($query);
		$options = $database->loadObjectList();
	} else{
		$row->lag = 3600 * 24;
		$row->published = 1;
	}

	// get selected pages
	if($uid){
		$sql = "SELECT *
				FROM #__poll_menu
				WHERE pollid = " . (int)$row->id;
		$database->setQuery($sql);
		$lookup = $database->loadObjectList();
	} else{
		$lookup = array();
	}

	// build the html select list
	$lists['select'] = LAdminMenu::MenuLinks($lookup, 1, 1);

	// build the html select list for published
	$lists['published'] = LAdminMenu::Published($row);

	HTML_poll::editPoll($row, $options, $lists);
}

function savePoll(){
	$database = database::getInstance();
	josSpoofCheck();
	// save the poll parent information
	$row = new mosPoll($database);
	if(!$row->bind($_POST)){
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	$isNew = ($row->id == 0);

	if(!$row->check()){
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}

	if(!$row->store()){
		echo "<script> alert('" . $row->getError() . "'); window.history.go(-1); </script>\n";
		exit();
	}
	$row->checkin();
	// save the poll options
	$options = mosGetParam($_POST, 'polloption', array());

	foreach($options as $i => $text){
		$text = addslashes($text);
		if($isNew){
			$query = "INSERT INTO #__poll_data ( pollid, text ) VALUES ( " . (int)$row->id . ", " . $database->Quote($text) . " )";
			$database->setQuery($query);
			$database->query();
		} else{
			$query = "UPDATE #__poll_data SET text = " . $database->Quote($text) . " WHERE id = " . (int)$i . " AND pollid = " . (int)$row->id;
			$database->setQuery($query);
			$database->query();
		}
	}

	// update the menu visibility

	$query = "DELETE FROM #__poll_menu WHERE pollid = " . (int)$row->id;
	$database->setQuery($query);
	$database->query();

	$menus = isset($_POST['selections']) ? $_POST['selections'] : array();
	if(!count($menus) or in_array('0-0-0-', $menus)){
		$sql = "INSERT INTO `#__poll_menu`  (`id`, `pollid`, `option`, `directory`, `category`, `task`)
		 		VALUES (NULL, '" . $row->id . "', '0',  '0',  '0',  '');";
	} elseif(in_array('-0-0-', $menus)){
		$sql = "INSERT INTO `#__poll_menu` (`id`, `pollid`, `option`, `directory`, `category`, `task`)
		 		VALUES (NULL, '" . $row->id . "', '',  '0',  '0',  '');";
	} else{
		$sql = '';
		foreach($menus as $menu){
			if($menu != '-999'){
				$tmp = preg_match("#^([a-z0-9_-]*)-(\d+)-(\d+)-([a-z0-9_-]*)$#i", $menu, $arr_sel);
				if($tmp){
					$sel['option'] = isset($arr_sel[1]) ? $arr_sel[1] : '';
					$sel['directory'] = isset($arr_sel[2]) ? $arr_sel[2] : 0;
					$sel['category'] = isset($arr_sel[3]) ? $arr_sel[3] : 0;
					$sel['task'] = isset($arr_sel[4]) ? $arr_sel[4] : '';
					$sql .= "INSERT INTO #__poll_menu (`id`, `pollid`, `option`, `directory`, `category`, `task`)
		 					VALUES (
		 						NULL,
		 						'" . $row->id . "',
		 						'" . $sel['option'] . "',
		 						'" . $sel['directory'] . "',
		 						'" . $sel['category'] . "',
		 						'" . $sel['task'] . "'
		 					);";
				}
			}
		}
	}
	$database->setQuery($sql);
	$database->multiQuery();

	mosRedirect('index2.php?option=com_poll');
}

function removePoll($cid){
	$database = database::getInstance();
	josSpoofCheck();
	$msg = '';
	for($i = 0, $n = count($cid); $i < $n; $i++){
		$poll = new mosPoll($database);
		if(!$poll->delete($cid[$i])){
			$msg .= $poll->getError();
		}
	}
	mosRedirect('index2.php?option=com_poll&mosmsg=' . $msg);
}

/**
 * Publishes or Unpublishes one or more records
 * @param array An array of unique category id numbers
 * @param integer 0 if unpublishing, 1 if publishing
 * @param string The current url option
 */
function publishPolls($cid = null, $publish = 1){
    $my = LCore::getUser();
	$database = database::getInstance();
	josSpoofCheck();
	if(!is_array($cid) || count($cid) < 1){
		$action = $publish ? 'publish' : 'unpublish';
		echo "<script> alert('" . _CHOOSE_OBJECT_FOR . " $action'); window.history.go(-1);</script>\n";
		exit;
	}

	mosArrayToInts($cid);
	$cids = 'id=' . implode(' OR id=', $cid);

	$query = "UPDATE #__polls SET published = " . intval($publish) . " WHERE ( $cids )" . " AND ( checked_out = 0 OR ( checked_out = " . (int)$my->id . " ) )";
	$database->setQuery($query);
	if(!$database->query()){
		echo "<script> alert('" . $database->getErrorMsg() . "'); window.history.go(-1); </script>\n";
		exit();
	}

	if(count($cid) == 1){
		$row = new mosPoll($database);
		$row->checkin($cid[0]);
	}
	mosRedirect('index2.php?option=com_poll');
}

function cancelPoll(){
	$database = database::getInstance();
	josSpoofCheck();
	$row = new mosPoll($database);
	$row->bind($_POST);
	$row->checkin();
	mosRedirect('index2.php?option=com_poll');
}