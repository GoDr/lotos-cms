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

$params_aray = array( //-------------------------------Основные настройки
	'moduleclass_sfx'    => $params->get('moduleclass_sfx'), //Суффикс класса модуля
	'all_user'           => $params->get('all_user'), //Показывать количество зарегистрированных пользователей
	'online_user_count'  => $params->get('online_user_count'), //Пользователи online
	'online_users'       => $params->get('online_users'), //Кто online
	'user_avatar'        => $params->get('user_avatar'), //аватары пользователей
	'module_orientation' => $params->get('module_orientation'), //Ориентация модуля
);

$output = '';

display_module($params_aray, $database);

unset($params_aray);

function display_module($params_aray, $database){
	echo '<div class="mod_who_online">';

	if($params_aray['all_user']){
		$all_user = '<span>' . _REGISTERED_USERS_COUNT . ':</span> ' . all_user($database);
	} else{
		$all_user = '';
	}

	if($params_aray['online_user_count'] !== '2'){
		$count_online = '<span>' . _ONLINE . ':</span> ' . online_users($params_aray, $database);
	} else{
		$count_online = '';
	}

	if($params_aray['online_users']){
		$online_users = who_online($params_aray, $database);
	} else{
		$online_users = '';
	}

	if($params_aray['module_orientation'] == '0'){ //горизонтальный вывод
		echo '<div class="mod_who_online_info">';
		echo $all_user;
		echo '&nbsp;&nbsp;';
		echo $count_online;
		echo '</div>';
		echo $online_users;

	} else{ //вертикальный вывод
		echo $all_user;
		echo '<br />';
		echo $count_online;
		echo '<hr />';
		echo $online_users;
	}
	echo '</div>';

}

function online_users($params_aray, $database){

	$output = '';
	$query = "SELECT guest, usertype FROM #__session";
	$database->setQuery($query);
	$sessions = $database->loadObjectList();

	// calculate number of guests and members
	$user_array = 0;
	$guest_array = 0;
	foreach($sessions as $session){
		// if guest increase guest count by 1
		if($session->guest == 1 && !$session->usertype){
			$guest_array++;
		}
		// if member increase member count by 1
		if($session->guest == 0){
			$user_array++;
		}
	}
	if($params_aray['online_user_count'] == '0'){
		$itogo = $guest_array + $user_array;
		return $itogo;
	}
	// check if any guest or member is on the site
	if($guest_array != 0 or $user_array != 0){
		$output .= '<ul>';
		// guest count handling
		$output .= '<li>' . sprintf(_GUESTS_COUNT, $guest_array) . '</li>';

		$output .= '<li>' . sprintf(_MEMBERS_COUNT, $user_array) . '</li>';
		$output .= '</ul>';
	}

	return $output;
}


function who_online($params_aray, $database){

	$output = '';
	$query = "SELECT a.username, a.userid, b.name, b.id,b.avatar FROM #__session AS a, #__users AS b WHERE a.guest = 0 AND a.userid=b.id";
	$database->setQuery($query);
	$rows = $database->loadObjectList();

	if(count($rows)){

		if($params_aray['module_orientation'] == '1'){
			$dop_class = "gorizontal";
		} else{
			$dop_class = "vertical";
		}

		// output
		$output .= '<ul class="users_online ' . $dop_class . '">';

		foreach($rows as $row){
			if($params_aray['online_users'] == '1'){
				$user_name = $row->username;
			} else{
				$user_name = $row->name;
			}
			$user_link = 'index.php?option=com_users&amp;task=profile&amp;user=' . $row->userid;
			$user_seflink = '<a href="' . LSef::getUrlToSef($user_link) . '">' . $user_name . '</a>';
			$avatar = '<img class="user_avatar_img" src="' . _LPATH_SITE . '/' . mosUser::get_avatar($row) . '" alt="' . $user_name . '"/>';
			$avatar_link = '<a href="' . LSef::getUrlToSef($user_link) . '">' . $avatar . '</a>';
			if($params_aray['user_avatar'] == '1'){
				$user_item = $avatar_link . $user_seflink;
			} else
				if($params_aray['user_avatar'] == '2'){
					$user_item = $avatar_link;
				} else{
					$user_item = $user_seflink;
				}

			$output .= '<li>';
			$output .= $user_item;
			$output .= '</li>';
		}
		$output .= '</ul>';
	}
	return $output;
}

// получение числа всех незаблокированных пользователей
function all_user($database){
	$q = "SELECT COUNT(id) FROM #__users WHERE block = '0' ";
	$database->setQuery($q);
	$row = $database->loadResult();
	return $row;
}