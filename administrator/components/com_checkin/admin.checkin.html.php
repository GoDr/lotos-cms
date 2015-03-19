<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * Checkin - Компонент разблокировки объектов
 *
 * @package   Checkin
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2014 Gold Dragon
 * @date      01.07.2014
 * @see       http://wiki.lotos-cms.ru/index.php/XMap
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

class HTML_checkin{

	public static function showlist($itemlist){
		?>
	<table class="adminheading">
		<tr>
			<th class="checkin"><?php echo _BLOCKED_OBJECTS?></th>
		</tr>
	</table>
	<table class="adminlist">
		<tr>
			<th><?php echo _TABLE_IN_DB ?></th>
			<th><?php echo _CAPTION ?></th>
			<th><?php echo _OBJECT ?></th>
			<th><?php echo _WHO_BLOCK?></th>
			<th><?php echo _BLOCK_TIME?></th>
			<th><?php echo _ACTION?></th>
		</tr>
		<?php
		$k = 0;
        foreach($itemlist as $value){
            echo '<tr class="row' . $k .'">';
            echo '<td align="center">' . $value["table"] . '</td>';
            echo '<td align="center">' . $value["title"] . '</td>';
            echo '<td align="center">' . $value["id"] . '</td>';
            echo '<td align="center">' . $value["name"] . '</td>';
            echo '<td align="center">' . $value["cotime"] . '</td>';
            echo '<td align="center"><a href="'. _LPATH_SITE . '/administrator/index2.php?option=com_checkin&amp;task=checkin&amp;table=' . $value["table"] . '&amp;id='. $value["id"] . '">'._CHECKIN_OJECT.'</a></td>';
            echo '</tr>';
			$k = 1 - $k;
        }
		?>
	</table>
	<?php
	}
}