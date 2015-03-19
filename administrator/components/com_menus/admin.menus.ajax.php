<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Menu
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LMenus
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

$task = mosGetParam($_GET, 'task', 'publish');
$id = intval(mosGetParam($_GET, 'id', '0'));

switch($task){
	case "publish":
		echo x_publish($id);
		return;

	case "access":
		echo x_access($id);
		return;

	case "get_category_content":
		echo getCategoryContent();
		break;

	default:
		echo 'error-task';
		return;
}


function x_access($id){
	$access = LCore::getParam($_GET, 'chaccess', 'accessregistered', '');
    $my = LCore::getUser();
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
		default:
			$access = 0;
			break;
	}
    $_db = LCore::getDB();
    $_db->update('UPDATE `#__menu` SET `access`= ? WHERE  `id`= ? AND (`published` = ? OR `published` = ?);', $access, $id, 0, $my->id);

	if(!$access){
		$color_access = 'style="color: green;"';
		$task_access = 'accessregistered';
		$text_href = _USER_GROUP_ALL;
	} elseif($access == 1){
		$color_access = 'style="color: red;"';
		$task_access = 'accessspecial';
		$text_href = _USER_GROUP_REGISTERED;
	} else{
		$color_access = 'style="color: black;"';
		$task_access = 'accesspublic';
		$text_href = _USER_GROUP_SPECIAL;
	}

	return '<a href="#" onclick="ch_access(' . $id . ',\'' . $task_access . '\',\'com_menus\')" ' . $color_access . '>' . $text_href . '</a>';
}

function x_publish($id = null){
    $_db = LCore::getDB();
    $state = $_db->selectCell("SELECT `published` FROM `#__menu` WHERE `id` = ? ", $id);
	if(!empty($id)){
        $my = LCore::getUser();

        if($state == '1'){
            $ret_img = 'publish_x.png';
            $state = '0';
        } else{
            $ret_img = 'publish_g.png';
            $state = '1';
        }

        $sql = "UPDATE `#__menu`
                SET published = ?
                WHERE id = ?
                    AND ( checked_out = ? OR checked_out = ? )";
        $_db->update($sql, $state, $id, 0, $my->id);
    }else{
        if($state == '0'){
            $ret_img = 'publish_x.png';
        } else{
            $ret_img = 'publish_g.png';
        }
    }
    return $ret_img;
}

function getCategoryContent(){
	$database = database::getInstance();
	$catid = mosGetParam($_REQUEST, 'catid', 0);
	$directory = mosGetParam($_REQUEST, 'directory', 0);

	if($catid == 0 || $directory == 0)
		return null;
	$q = "SELECT content.id, content.name ";
	$q .= "FROM #__boss_" . $directory . "_contents as content, ";
	$q .= "#__boss_" . $directory . "_content_category_href as cch ";
	$q .= "WHERE cch.category_id = $catid ";
	$q .= "AND cch.content_id = content.id ";
	$q .= "ORDER BY content.name";

	$contents = $database->setQuery($q)->loadObjectList();
	$options = '';
	foreach($contents as $content){
		$options .= '<option value="' . $content->id . '">' . $content->name . '</option> ';
	}
	return $options;
}

