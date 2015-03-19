<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * XMap - Компонент создания карт сайта
 *
 * @package   XMap
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2014 Gold Dragon
 * @date      01.07.2014
 * @see       http://wiki.lotos-cms.ru/index.php/XMap
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */
class TOOLBAR_xmap
{
    public static function _CONFIG()
    {
        LibMenuBar::startTable();
        LibMenuBar::save('savecfg');
        LibMenuBar::cancel();
        LibMenuBar::endTable();
    }

    public static function _CONFIGLINK()
    {
        LibMenuBar::startTable();
        LibMenuBar::save('savelink');
        LibMenuBar::cancel();
        LibMenuBar::endTable();
    }

    public static function _DEFAULT()
    {
        LibMenuBar::startTable();
        LibMenuBar::endTable();
    }
}