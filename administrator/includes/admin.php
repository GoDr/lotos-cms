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

/**
 * вывод подключения js и css
 */
function adminHead($mainframe){

	$custom = $mainframe->getHeadData('custom');
	if(!empty($custom)){
		$head = array();
		foreach($custom as $html){
			$head[] = $html;
		}
		echo implode("\n", $head) . "\n";
	}
	;

	$js = $mainframe->getHeadData('js');
	if(!empty($js)){
		$head = array();
		foreach($js as $html){
			$head[] = $html;
		}
		echo implode("\n", $head) . "\n";
	}
	;

	$css = $mainframe->getHeadData('css');
	if(!empty($css)){
		$head = array();
		foreach($css as $html){
			$head[] = $html;
		}
		echo implode("\n", $head) . "\n";
	}
	;
	// отправим пользователю шапку - пусть браузер работает пока будет формироваться дальнейший код страницы
	flush();
}


/**
 * @param string THe template position
 */
function mosCountAdminModules($position = 'left'){
	$database = database::getInstance();

	$query = "SELECT COUNT( m.id )"
		. "\n FROM #__modules AS m"
		. "\n WHERE m.published = 1"
		. "\n AND m.position = " . $database->Quote($position)
		. "\n AND m.client_id = 1";
	$database->setQuery($query);

	return $database->loadResult();
}

/**
 * Loads admin modules via module position
 * @param string The position
 * @param int 0 = no style, 1 = tabbed
 */
function mosLoadAdminModules($position = 'left', $style = 0){
	$acl = &gacl::getInstance();
    $my = LCore::getUser();

	static $all_modules;
	if(!isset($all_modules)){
		$database = database::getInstance();

		$query = "SELECT id, title, module, position, content, showtitle, params FROM #__modules AS m WHERE m.published = 1 AND m.client_id = 1 ORDER BY m.ordering";
		$database->setQuery($query);
		$_all_modules = $database->loadObjectList();


		$all_modules = array();
		foreach($_all_modules as $__all_modules){
			$all_modules[$__all_modules->position][] = $__all_modules;
		}
		unset($_all_modules, $__all_modules);
	}

	$modules = isset($all_modules[$position]) ? $all_modules[$position] : array();

	switch($style){
		case 1:
			// Tabs
			$tabs = new LTabs(1, 1);
			$tabs->startPane('modules-' . $position);
			foreach($modules as $module){
				$params = new mosParameters($module->params);
				$editAllComponents = $acl->acl_check('administration', 'edit', 'users', $my->usertype, 'components', 'all');
				// special handling for components module
				if($module->module != 'mod_components' || ($module->module == 'mod_components' && $editAllComponents)){
					$tabs->startTab($module->title, 'module' . $module->id);
					if($module->module == ''){
						mosLoadCustomModule($module, $params);
					} else{
						mosLoadAdminModule(substr($module->module, 4), $params);
					}
					$tabs->endTab();
				}
			}
			$tabs->endPane();
			break;

		case 2:
			// Div'd
			foreach($modules as $module){
				$params = new mosParameters($module->params);
				echo '<div>';
				if($module->module == ''){
					mosLoadCustomModule($module, $params);
				} else{
					mosLoadAdminModule(substr($module->module, 4), $params);
				}
				echo '</div>';
			}
			break;

		case 0:
		default:
			foreach($modules as $module){
				$params = new mosParameters($module->params);
				if($module->module == ''){
					mosLoadCustomModule($module, $params);
				} else{
					mosLoadAdminModule(substr($module->module, 4), $params);
				}
			}
			break;
	}
}

/**
 * Loads an admin module
 */
function mosLoadAdminModule($name, $params = null){

	$mainframe = MainFrame::getInstance();

	$name = str_replace('/', '', $name);
	$name = str_replace('\\', '', $name);
	$path = _LPATH_ADMINISTRATOR . "/modules/mod_$name.php";
	if(file_exists($path)){
		if($mainframe->getLangFile('mod_' . $name)){
			include($mainframe->getLangFile('mod_' . $name));
		}
		require $path;
	}
}

function mosLoadCustomModule($module, $params){
	$rssurl = $params->get('rssurl', '');
	$rssitems = $params->get('rssitems', '');
	$rssdesc = $params->get('rssdesc', '');
	$moduleclass_sfx = $params->get('moduleclass_sfx', '');
	$rsscache = $params->get('rsscache', 3600);
	$cachePath = LCore::getCfg('cachepath') . '/';

	echo '<table cellpadding="0" cellspacing="0" class="moduletable' . $moduleclass_sfx . '">';

	if($module->content){
		echo '<tr><td>' . $module->content . '</td></tr>';
	}

	// feed output
	if($rssurl){
		if(!is_writable($cachePath)){
			echo '<tr><td>' . _CACHE_DIR_IS_NOT_WRITEABLE . '</td></tr>';
		} else{
			$LitePath = _LPATH_ROOT . '/includes/Cache/Lite.php';
			require_once (_LPATH_ROOT . '/includes/domit/xml_domit_rss_lite.php');
			$rssDoc = new xml_domit_rss_document_lite();
			$rssDoc->setRSSTimeout(5);
			$rssDoc->useHTTPClient(true);
			$rssDoc->useCacheLite(true, $LitePath, $cachePath, $rsscache);
			$success = $rssDoc->loadRSS($rssurl);

			if($success){
				$totalChannels = $rssDoc->getChannelCount();

				for($i = 0; $i < $totalChannels; $i++){
					$currChannel = &$rssDoc->getChannel($i);

					$feed_title = $currChannel->getTitle();
					$feed_title = LHtml::newsfeedEncoding($rssDoc, $feed_title);

					echo '<tr>';
					echo '<td><strong><a href="' . $currChannel->getLink() . '" target="_child">';
					echo $feed_title . '</a></strong></td>';
					echo '</tr>';

					if($rssdesc){
						$feed_descrip = $currChannel->getDescription();
						$feed_descrip = LHtml::newsfeedEncoding($rssDoc, $feed_descrip);

						echo '<tr>';
						echo '<td>' . $feed_descrip . '</td>';
						echo '</tr>';
					}

					$actualItems = $currChannel->getItemCount();
					$setItems = $rssitems;

					if($setItems > $actualItems){
						$totalItems = $actualItems;
					} else{
						$totalItems = $setItems;
					}

					for($j = 0; $j < $totalItems; $j++){
						$currItem = &$currChannel->getItem($j);

						$item_title = $currItem->getTitle();
						$item_title = LHtml::newsfeedEncoding($rssDoc, $item_title);

						$text = $currItem->getDescription();
						$text = LHtml::newsfeedEncoding($rssDoc, $text);

						echo '<tr>';
						echo '<td><strong><a href="' . $currItem->getLink() . '" target="_child">';
						echo $item_title . '</a></strong> - ' . $text . '</td>';
						echo '</tr>';
					}
				}
			}
		}
	}
	echo '</table>';
}

function mosShowSource($filename, $withLineNums = false){
	ini_set('highlight.html', '000000');
	ini_set('highlight.default', '#800000');
	ini_set('highlight.keyword', '#0000ff');
	ini_set('highlight.string', '#ff00ff');
	ini_set('highlight.comment', '#008000');

	if(!($source = @highlight_file($filename, true))){
		return 'Операция невозможна';
	}
	$source = explode("<br />", $source);

	$ln = 1;

	$txt = '';
	foreach($source as $line){
		$txt .= "<code>";
		if($withLineNums){
			$txt .= '<span style="color:#aaaaaa">';
			$txt .= str_replace(' ', '&nbsp;', sprintf("%4d:", $ln));
			$txt .= "</span>";
		}
		$txt .= "$line<br /><code>";
		$ln++;
	}
	return $txt;
}

// проверка на доступность смены прав
function mosIsChmodable($file){
	$perms = fileperms($file);
	if($perms !== false){
		if(@chmod($file, $perms ^ 0001)){
			@chmod($file, $perms);
			return true;
		} // if
	}
	return false;
} // mosIsChmodable

/**
 * @param string An existing base path
 * @param string A path to create from the base path
 * @param int Directory permissions
 * @return boolean True if successful
 */
function mosMakePath($base, $path = '', $mode = null){
	// convert windows paths
	$path = str_replace('\\', '/', $path);
	$path = str_replace('//', '/', $path);
	// ensure a clean join with a single slash
	$path = ltrim($path, '/');
	$base = rtrim($base, '/') . '/';

	// check if dir exists
	if(file_exists($base . $path)) return true;

	// set mode
	$origmask = null;
	if(isset($mode)){
		$origmask = @umask(0);
	} else{
		if(LCore::getCfg('dirperms') == ''){
			// rely on umask
			$mode = 0777;
		} else{
			$origmask = @umask(0);
			$mode = octdec(LCore::getCfg('dirperms'));
		} // if
	} // if

	$parts = explode('/', $path);
	$n = count($parts);
	$ret = true;
	if($n < 1){
		if(substr($base, -1, 1) == '/'){
			$base = substr($base, 0, -1);
		}
		$ret = @mkdir($base, $mode);
	} else{
		$path = $base;
		for($i = 0; $i < $n; $i++){
			// don't add if part is empty
			if($parts[$i]){
				$path .= $parts[$i] . '/';
			}
			if(!file_exists($path)){
				if(!@mkdir(substr($path, 0, -1), $mode)){
					$ret = false;
					break;
				}
			}
		}
	}
	if(isset($origmask)){
		@umask($origmask);
	}

	return $ret;
}

function mosMainBody_Admin(){
	echo $GLOBALS['_MOS_OPTION']['buffer'];
}

// boston, кеширование меню администратора
function js_menu_cache($data, $usertype, $state = 0){
	if(!is_writeable(LCore::getCfg('cachepath')) && LCore::getCfg('adm_menu_cache')){
		echo '<script>alert(\'' . _CACHE_DIR_IS_NOT_WRITEABLE . '\');</script>';
		return false;
	}
	$menuname = md5($usertype . LCore::getCfg('secret'));
	$file = LCore::getCfg('cachepath') . '/adm_menu_' . $menuname . '.js';
	if(!file_exists($file)){ // файла нету
		if($state == 1) return false; // файла у нас не было и получен сигнал 0 - продолжаем вызывающую функцию, а отсюда выходим
		touch($file);
		$handle = fopen($file, 'w');
		fwrite($handle, $data);
		fclose($handle);
		return true; // файла не было - но был создан заново
	} else{
		return true; // файл уже был, просто завершаем функцию
	}
}

/*
* Добавлено в версии 1.0.11
*/
function josSecurityCheck($width = '95%'){
	$wrongSettingsTexts = array();
	// проверка на запись  в каталог кеша
	if(!is_writeable(LCore::getCfg('cachepath')) && LCore::getCfg('caching')) $wrongSettingsTexts[] = _CACHE_DIR_IS_NOT_WRITEABLE2;

	if(count($wrongSettingsTexts)){
		?>
	<div style="width: <?php echo $width; ?>;" class="jwarning">
		<h3 style="color:#484848"><?php echo _PHP_SETTINGS_WARNING?>:</h3>
		<ul style="margin: 0px; padding: 0px; padding-left: 15px; list-style: none;">
			<?php
			foreach($wrongSettingsTexts as $txt){
				?>
				<li style="font-size: 12px; color: red;"><b><?php echo $txt;?></b></li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
	}
}

//boston, удаление кеша меню панели управления
function js_menu_cache_clear($echo = true){
    $my = LCore::getUser();

	if(!LCore::getCfg('adm_menu_cache')) return;

	$usertype = str_replace(' ', '_', $my->usertype);
	$menuname = md5($usertype . LCore::getCfg('secret'));
	$file = _LPATH_ROOT . '/cache/adm_menu_' . $menuname . '.js';
	if(file_exists($file)){
		if(unlink($file))
			echo $echo ? joost_info(_MENU_CACHE_CLEANED) : null;
		else
			echo $echo ? joost_info(_CLEANING_ADMIN_MENU_CACHE) : null;
	} else{
		echo $echo ? joost_info(_NO_MENU_ADMIN_CACHE) : null;
	}
}

/* вывод информационного поля*/
function joost_info($msg){
	return '<div class="message">' . $msg . '</div>';
}