<?php
/**
 * @package Joostina BOSS
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina BOSS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Joostina BOSS основан на разработках Jdirectory от Thomas Papin
 */
defined('_LINDEX') or die('STOP in file ' . __FILE__);

class jcomment{
	public function install($directory){
	}

	public function uninstall($directory){
	}

	public function displayReviews($content, $directory, $conf, $reviews){
	}

	public function displayAddReview($directory, $content, $conf){
		$mainframe = MainFrame::getInstance();
		$my = LCore::getUser();
		if($conf->allow_comments == 0){
			return;
		} else if($my->id == 0 && $conf->allow_unregisered_comment == 0){
			$link = LSef::getUrlToSef("index.php?option=com_boss&amp;task=login&amp;directory=$directory");
			echo sprintf(BOSS_REVIEW_LOGIN_REQUIRED, $link);
		} else{
			$comments = _LPATH_ROOT . '/components/com_jcomments/jcomments.php';
			if(file_exists($comments)){
				require_once($comments);
				$JComments = new JComments();
				$contentId = $directory * 100000 + $content->id;
				echo $JComments->showComments($contentId, 'com_boss', $content->name);
			}
		}
	}

	public function displayNumReviews($content, $reviews, $conf){
		if($conf->allow_comments == 0)
			echo "";
		else{
			if(isset($content->num_reviews))
				$nb = $content->num_reviews;
			else
				$nb = count($reviews);

			echo sprintf(BOSS_NUM_REVIEWS, $nb);
		}
	}

	//функция для вставки таблиц и полей рейтинга в запрос категории
	public function queryStringList($directory, $conf){
		$query = array();
		if($conf->allow_comments == 1){
			$query['tables'] = " LEFT JOIN #__jcomments as rev ON (" . $directory . "*100000+a.id) = rev.object_id AND rev.object_group = 'com_boss' \n";
			$query['fields'] = " count(DISTINCT rev.id) as num_reviews, \n";
			$query['wheres'] = '';
		} else{
			$query['tables'] = '';
			$query['fields'] = '';
			$query['wheres'] = '';
		}
		return $query;
	}

	//функция для вставки таблиц и полей рейтинга в запрос контента
	public function queryStringContent($directory, $conf, $id){
		$database = database::getInstance();
		$reviews = array();

		if($conf->allow_comments == 1){
			$contentId = $directory * 100000 + $id;
			$database->setQuery("SELECT id FROM #__jcomments " .
				"WHERE published = 1 AND object_id = " . $contentId .
				" AND object_group = 'com_boss_" . $directory . "'");
			$reviews = $database->loadObjectList();
			if($database->getErrorNum()){
				echo $database->stderr();
				return false;
			}
		}
		return $reviews;
	}
}