<?php defined('_LINDEX') or die;
/**
 * Joostina BOSS Plugin
 *
 * @package   BOSS Plugin
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2013 JLotos.
 * @license   GNU GPL: http://www.gnu.org/licenses/gpl-3.0.html
 *            Joostina Lotos CMS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL. (help/copyright.php)
 * @Date      21.07.2013
 * @see       http://wiki.lotos-cms.ru/index.php/BossDatePlugin
 */

class BossDatePlugin{

	//имя типа поля в выпадающем списке в настройках поля
	var $name = 'Date';

	//тип плагина для записи в таблицы
	var $type = 'BossDatePlugin';

	//отображение поля в категории
	function getListDisplay($directory, $content, $field, $field_values, $conf){
		return $this->getDetailsDisplay($directory, $content, $field, $field_values, $conf);
	}

	//отображение поля в контенте
	function getDetailsDisplay($directory, $content, $field, $field_values, $conf){

		$fieldname = $field->name;

		$value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
		if(!empty($value)){
			//формат даты из настроек поля
			$format = (!empty($field_values[0]->fieldvalue)) ? $field_values[0]->fieldvalue : 'Y-m-d';
			//переводим дату в метку времени уникс
			$value = strtotime($value);
			//переводим метку времени в дату согласно формату, заданному в настройках поля
			$value = date($format, $value);
		}

		$return = '';
		if(!empty($field->text_before))
			$return .= '<span>' . $field->text_before . '</span>';
		if(!empty($field->tags_open))
			$return .= html_entity_decode($field->tags_open);

		$return .= $value;

		if(!empty($field->tags_close))
			$return .= html_entity_decode($field->tags_close);
		if(!empty($field->text_after))
			$return .= '<span>' . $field->text_after . '</span>';

		return $return;
	}

	//функция вставки фрагмента ява-скрипта в скрипт
	//сохранения формы при редактировании контента с фронта.
	function addInWriteScript($field){

	}

	//отображение поля в админке в редактировании контента
	function getFormDisplay($directory, $content, $field, $field_values, $nameform = 'adminForm', $mode = "write"){
		$fieldname = $field->name;
		$value = (isset ($content->$fieldname)) ? $content->$fieldname : '';
		$strtitle = htmlentities(jdGetLangDefinition($field->title), ENT_QUOTES, 'utf-8');
		$return = '';
		$mainframe = MainFrame::getInstance();
		$mainframe->addJS(_LPATH_SITE . '/includes/js/joomla.javascript.js');
		LHtml::loadCalendar();
		if(($mode == "write") && ($field->required == 1)){
			$class = "class='boss_required' mosReq='1' mosLabel='" . $strtitle . "'";
			$return .= "<input $class type='text' name='" . $field->name . "' id='" . $field->name . "' size='25' maxlength='19' value='" . $value . "' />";
			$return .= "<span class='button'><input name='reset' type='reset' class='button' onclick=\"return showCalendar('" . $field->name . "');\" value='...' /></span>";
		} else if($mode == "search"){
			$class = "class='boss'";
			$return .= "<input $class type='text' name='" . $field->name . "_from' id='" . $field->name . "_from' size='25' maxlength='19' value='" . $value . "' readonly='true' />";
			$return .= "<span class='button'><input name='reset' type='reset' class='button' onclick=\"return showCalendar('" . $field->name . "_from');\" value='...' /></span>";

			$return .= "<input $class type='text' name='" . $field->name . "_to' id='" . $field->name . "_to' size='25' maxlength='19' value='" . $value . "' />";
			$return .= "<span class='button'><input name='reset' type='reset' class='button' onclick=\"return showCalendar('" . $field->name . "_to');\" value='...' readonly='true' /></span>";
		} else{
			$class = "class='boss'";
			$return .= "<input $class type='text' name='" . $field->name . "' id='" . $field->name . "' size='25' maxlength='19' value='" . $value . "' />";
			$return .= "<span class='button'><input name='reset' type='reset' class='button' onclick=\"return showCalendar('" . $field->name . "');\" value='...' /></span>";
		}
		return $return;
	}

	function onFormSave($directory, $contentid, $field, $isUpdateMode){
		$return = mosGetParam($_POST, $field->name, "");
		return $return;
	}

	function onDelete($directory, $content){
		return;
	}

	//отображение поля в админке в настройках поля
	function getEditFieldOptions($row, $directory, $fieldimages, $fieldvalues){
		$value = (!empty($fieldvalues['date_format']->fieldvalue)) ? $fieldvalues['date_format']->fieldvalue : 'Y-m-d';
		$return = "
            <div id='divDateOptions'>
                <table class='adminform'>
                    <tr>
                        <td>" . BOSS_DATE_FORMAT . "</td>
                        <td>
                            <input
                                type='text'
                                name='date_format'
                                mosReq=1
                                mosLabel='date_format'
                                class='inputbox'
                                value='" . $value . "'
                            />
                        </td>
                        <td>" . BOSS_DATE_FORMAT_DESC . "</td>
                    </tr>
                </table>
            </div>";
		return $return;
	}

	//действия при сохранении настроек поля
	function saveFieldOptions($directory, $field){
		$database = database::getInstance();
		$fieldid = $field->fieldid;
		$date_format = mosGetParam($_POST, "date_format", '');

		$database->setQuery("DELETE FROM `#__boss_" . $directory . "_field_values` WHERE `fieldid` = '" . $fieldid . "' ");
		$database->query();
		$database->setQuery("INSERT INTO #__boss_" . $directory . "_field_values
    		                    (fieldid, fieldtitle, fieldvalue, ordering, sys)
    		                    VALUES
    		                    ($fieldid,'date_format',  '$date_format',   1,0)
    		                    ");
		$database->query();
		//если плагин не создает собственных таблиц а пользется таблицами босса то возвращаем false
		//иначе true
		return false;
	}

	//расположение иконки плагина начиная со слеша от корня сайта
	function getFieldIcon(){
		return "/components/com_boss/plugins/fields/" . __CLASS__ . "/images/date.png";
	}

	//действия при установке плагина
	function install($directory){
		return;
	}

	//действия при удалении плагина
	function uninstall($directory){
		return;
	}

	//действия при поиске
	function search($directory, $fieldName){
		$search = '';
		$from = mosGetParam($_REQUEST, $fieldName . '_from', "");
		$to = mosGetParam($_REQUEST, $fieldName . '_to', "");
		if($from != "")
			$search .= " AND a.$fieldName >= '$from'";
		if($to != "")
			$search .= " AND a.$fieldName <= '$to'";
		return $search;
	}
}
