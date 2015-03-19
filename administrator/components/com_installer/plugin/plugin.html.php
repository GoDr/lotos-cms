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
 * @package Joostina
 * @subpackage Installer
 */
class HTML_plugin{

	/**
	 * Displays the installed non-core Joostina
	 * @param array An array of plugin object
	 * @param strong The URL option
	 */
	public static function showInstalledPlugins(&$rows, $option){
		?>
	<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
			<tr>
				<th class="install"><?php echo _INSTALLED_PLUGINS ?></th>
			</tr>
			<tr><?php HTML_installer::cPanel(); ?></tr>
			<tr>
				<td>
					<div class="jwarning"><?php echo _INSTALLED_COMPONENTS2 ?></div>
				</td>
			</tr>
		</table>
		<?php if(count($rows)){ ?>

		<table class="adminlist" id="adminlist">
			<tr>
				<th width="20%" class="title"><?php echo _PLUGIN ?></th>
				<th width="10%" class="title"><?php echo _TYPE ?></th>
				<th width="10%" align="left"><?php echo _AUTHOR ?></th>
				<th width="5%" align="center"><?php echo _VERSION ?></th>
				<th width="10%" align="center"><?php echo _DATE ?></th>
				<th width="15%" align="left">E-mail</th>
				<th width="15%" align="left"><?php echo _COMPONENT_AUTHOR_URL ?></th>
			</tr>
			<?php
			$rc = 0;
			$n = count($rows);
			for($i = 0; $i < $n; $i++){
				$row = &$rows[$i];
				?>
				<tr class="row<?php echo $rc ?>">
					<td align="left">
						<input type="radio" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $row->id; ?>" onclick="isChecked(this.checked);">
						<span class="bold"><?php echo $row->name; ?></span>
					</td>
					<td align="left"><?php echo $row->folder; ?></td>
					<td><?php echo @$row->author != '' ? $row->author : "&nbsp;"; ?></td>
					<td align="center"><?php echo @$row->version != '' ? $row->version : "&nbsp;"; ?></td>
					<td align="center"><?php echo @$row->creationdate != '' ? $row->creationdate : "&nbsp;"; ?></td>
					<td><?php echo @$row->authorEmail != '' ? $row->authorEmail : "&nbsp;"; ?></td>
					<td><?php echo @$row->authorUrl != "" ? "<a href=\"" . (substr($row->authorUrl, 0, 7) == 'http://' ? $row->authorUrl : 'http://' . $row->authorUrl) . "\" target=\"_blank\">$row->authorUrl</a>" : "&nbsp;"; ?></td>
				</tr>
				<?php
				$rc = 1 - $rc;
			}
			?>
		</table>
		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="boxchecked" value="0"/>
		<input type="hidden" name="option" value="com_installer"/>
		<input type="hidden" name="element" value="plugin"/>
		<input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1"/>
		<?php
	} else{
		echo _OTHER_PLUGINS;
	}
		?>
	</form>
	<?php
	}

}