<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * JLotos SEF - Компонент для управления SEF (ЧПУ)
 *
 * @package   JLotosSEF
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2013 Gold Dragon
 * @license   GNU GPL: http://www.gnu.org/licenses/gpl-3.0.html
 * @date      01.07.2013
 * @see       http://wiki.lotos-cms.ru/index.php/JLotosSEF
 */

class ToolbarJLotosSef{

    public static function linkDupMemu(){
        $_lang = JLotosSefClass::getLang();
        LibMenuBar::startTable();
        LibMenuBar::deleteList('','removedup');
        LibMenuBar::editList('editdup');
        LibMenuBar::back($_lang['JLSEF_DES_1'], 'index2.php?option=com_jlotossef');
        LibMenuBar::endTable();
    }

    /**
     * Редактирование дубликатов
     */
    public static function editDupMemu(){
        $_lang = JLotosSefClass::getLang();
        LibMenuBar::startTable();
        LibMenuBar::save('savedup');
        LibMenuBar::cancel('listdup');
        LibMenuBar::back($_lang['JLSEF_DES_1'], 'index2.php?option=com_jlotossef');
        LibMenuBar::endTable();
    }
    /**
     * Меню по уполчанию: список форм
     */
    public static function defaultMenu(){
	}

    /**
     * Описание sef-файла
     */
    public static function descriptionMemu(){
        $_lang = JLotosSefClass::getLang();
        LibMenuBar::startTable();
        LibMenuBar::back($_lang['JLSEF_DES_1'], 'index2.php?option=com_jlotossef');
        LibMenuBar::endTable();
    }

    public static function configurationMemu(){
        $_lang = JLotosSefClass::getLang();
        LibMenuBar::startTable();
        LibMenuBar::cancel();
        LibMenuBar::save('savecfg');
        LibMenuBar::apply('applycfg');
        LibMenuBar::back($_lang['JLSEF_DES_1'], 'index2.php?option=com_jlotossef');
        LibMenuBar::endTable();
    }

    public static function linkRefMemu(){
        $_lang = JLotosSefClass::getLang();
        LibMenuBar::startTable();
        LibMenuBar::deleteList('','removeref');
        LibMenuBar::editList('editref');
        LibMenuBar::back($_lang['JLSEF_DES_1'], 'index2.php?option=com_jlotossef');
        LibMenuBar::endTable();
    }

    public static function editRefMemu(){
        $_lang = JLotosSefClass::getLang();
        LibMenuBar::startTable();
        LibMenuBar::save('saveref');
        LibMenuBar::cancel('listref');
        LibMenuBar::back($_lang['JLSEF_DES_1'], 'index2.php?option=com_jlotossef');
        LibMenuBar::endTable();
    }
}





















