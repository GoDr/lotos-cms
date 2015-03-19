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

if(!defined('_JOS_EDITOR_INCLUDED')){
	$mainframe = MainFrame::getInstance();
	$my = LCore::getUser();

	// проверка сессии на параметр отключения редактора, если такой имеется - то вместо выбранного или прописанного по умолчанию редактора используется параметр 'none' - отсутствующий визуальный редактор
	if(intval(mosGetParam($_SESSION, 'user_editor_off', 0))){
		$editor = 'none';
	} else{ // получение параметров редактора из настоек пользователя
		$params = new mosParameters($my->params);
		$editor = $params->get('editor', '');
		if(!$editor){
			$editor = LCore::getCfg('editor');
		}
	}

	$_PLUGINS->loadBot('editors', $editor, 1);

	/**
	 * Инициализация редактора
	 * При вызове функции происходит загрузка плагинов группы редакторов и выводятся данные их настройки
	 */
	function initEditor(){
		$_PLUGINS = mosPluginHandler::getInstance();
		$mainframe = MainFrame::getInstance();
		if($mainframe->get('loadEditor')){
			$results = $_PLUGINS->trigger('onInitEditor');
			foreach($results as $result){
				if(trim($result)){
					echo $result;
				}
			}
		}
	}

	/**
	 * Получение содержимого редактора
	 * Проверяется функция соответствующая триггеру onGetEditorContents
	 */
	function getEditorContents($editorArea, $hiddenField){
		$_PLUGINS = mosPluginHandler::getInstance();
		$mainframe = MainFrame::getInstance();
		$mainframe->set('loadEditor', true);
		$results = $_PLUGINS->trigger('onGetEditorContents', array($editorArea, $hiddenField));
		foreach($results as $result){
			if(trim($result)){
				echo $result;
			}
		}
	}

	// just present a textarea
	function editorArea($name, $content, $hiddenField, $width, $height, $col, $row, $params = null){
		$_PLUGINS = mosPluginHandler::getInstance();
		$mainframe = MainFrame::getInstance();
		// загрузка ботов раздела editor-xtd, константа _JOS_EDITORXTD_INCLUDED сигнализирует что плагины загружены
		if(!defined('_JOS_EDITORXTD_INCLUDED')){
			define('_JOS_EDITORXTD_INCLUDED', 1);
			$_PLUGINS->loadBotGroup('editors-xtd');
		}
		$mainframe->set('loadEditor', true);
		$results = $_PLUGINS->trigger('onEditorArea', array($name, $content, $hiddenField, $width, $height, $col, $row, $params));
		foreach($results as $result){
			if(trim($result)){
				echo $result;
			}
		}
	}

	function editorBox($name, $content, $hiddenField, $width, $height, $col, $row){
		$_PLUGINS = mosPluginHandler::getInstance();
		$mainframe = MainFrame::getInstance();
		// загрузка ботов раздела editor-xtd, константа _JOS_EDITORXTD_INCLUDED сигнализирует что плагины загружены
		if(!defined('_JOS_EDITORXTD_INCLUDED')){
			define('_JOS_EDITORXTD_INCLUDED', 1);
			$_PLUGINS->loadBotGroup('editors-xtd');
		}
		$mainframe->set('loadEditor', true);
		$results = $_PLUGINS->trigger('onEditorArea', array($name, $content, $hiddenField, $width, $height, $col, $row));
		foreach($results as $result){
			if(trim($result)){
				echo $result;
			}
		}
	}

	// установка константы - флага, что редактор подключен
	define('_JOS_EDITOR_INCLUDED', 1);
}