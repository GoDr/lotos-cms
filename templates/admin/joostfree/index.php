<?php
/**
 * @JoostFREE
 * @package Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

$cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';
$option = mosGetParam($_REQUEST, 'option', '');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo LCore::getCfg('sitename'); ?> - <?php echo _JOOSTINA_CONTROL_PANEL?></title>
    <meta charset="UTF-8">
	<script>
		var _live_site = '<?php echo _LPATH_SITE;?>';
		var _option = '<?php echo $option;?>';
		var _cur_template = '<?php echo TEMPLATE;?>';
		var _js_defines = new Array();
	</script>
	<?php
	/* подключаем fullajax */
	LHtml::loadFullajax();
	LHtml::loadJquery();
    initEditor();

	$mainframe = MainFrame::getInstance();

	if(LCore::getCfg('gz_js_css')){ // работа со сжатыми css и js файлами
		$mainframe->addCSS(_LPATH_TPL_ADMI_S . '/joostfree/css/joostfree_css.php');
		$mainframe->addJS(_LPATH_SITE . '/includes/js/joostina.admin.php');
	} else{ // использовать стандартные - не сжатые файлы
		$mainframe->addCSS(_LPATH_TPL_ADMI_S . '/joostfree/css/template_css.css');
		$mainframe->addJS(_LPATH_SITE . '/includes/js/JSCookMenu.js');
		$mainframe->addJS(_LPATH_SITE . '/includes/js/ThemeOffice/theme.js');
		$mainframe->addJS(_LPATH_SITE . '/includes/js/joomla.javascript.js');
	};
	/**
	 * вывод подключения js и css
	 */
	adminHead($mainframe);


	?>
	<link rel="shortcut icon" href="<?php echo _LPATH_SITE; ?>/images/favicon.ico"/>
</head>
<body>
<div class="page">
	<div id="topper">
		<div class="logo">
			<a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>">
				<img border="0" alt="J!" src="<?php echo _LPATH_TPL_ADMI_S ?>/joostfree/images/logo_130.png"/>
			</a>
		</div>
		<div id="joo">
			<a href="index2.php" title="<?php echo _GO_TO_MAIN_ADMIN_PAGE?>">
				<?php echo LCore::getCfg('sitename');?>
			</a>
		</div>
		<div id="ajax_status"><?php echo _PLEASE_WAIT?></div>
		<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
			<tr class="menubackgr">
				<td width="85%">
					<?php mosLoadAdminModule('fullmenu'); ?>
				</td>
				<td width="5%" align="right" class="header_info">
					<?php mosLoadAdminModules('header', -2); ?>
				</td>
				<td width="35" align="center">
					<input type="image" name="jtoggle_editor" id="jtoggle_editor" title="<?php echo _TOGGLE_WYSIWYG_EDITOR?>" onclick="jtoggle_editor();" src="<?php echo $cur_file_icons_path;?>/<?php echo (intval(mosGetParam($_SESSION, 'user_editor_off', ''))) ? 'editor_off.png' : 'editor_on.png'?>" alt="<?php echo _DISABLE_WYSIWYG_EDITOR?>"/>
				</td>
				<td style="padding-left: 12px;" align="right" class="jtd_nowrap">
					<a href="<?php echo _LPATH_SITE; ?>/" target="_blank" class="preview" title="<?php echo _PREVIEW_SITE?>"><?php echo _PREVIEW_SITE?></a>
				</td>
				<td style="padding-left: 7px;" align="right" class="jtd_nowrap">
					<a href="index2.php?option=logout" class="logoff"><?PHP echo _BUTTON_LOGOUT?> <?php echo $my->username; ?></a>&nbsp;
				</td>
			</tr>
		</table>
	</div>
	<?php if($option != ''){ ?>
	<div id="top-toolbar"><?php mosLoadAdminModule('toolbar'); ?></div>
	<?php }; ?>
	<?php mosLoadAdminModule('mosmsg'); ?>
	<?php josSecurityCheck('100%');?>
	<div id="status-info" style="display: none;">&nbsp;</div>
	<div id="main_body"><?php mosMainBody_Admin(); ?></div>
</div>
<?php
// копирайты, не удалять ):-))
$jostina_ru = LVersion::getLongVersion();
?>
<div id="footer" align="center" class="smallgrey"><?php echo $jostina_ru; ?></div>
<script>
    function jf_hideLoading() {
	SRAX.get('ajax_status').style.display = 'none';
    };
if (window.addEventListener) {
	window.addEventListener('load', jf_hideLoading, false);
} else if (window.attachEvent) {
	var r = window.attachEvent("onload", jf_hideLoading);
} else {
	jf_hideLoading();
}</script>
</body>
</html>