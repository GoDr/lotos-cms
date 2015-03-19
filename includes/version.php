<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS INCLUDES
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LVersion
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

/**
 * Информация о версии
 * @package Joostina
 */
class LVersion
{
    /** @var string Название CMS */
    private $CMS = 'Lotos CMS';

    /** @var string Номер основной версии */
    private $CMS_VER = '0';

    /** @var string Номер подверсии */
    private $DEV_LEVEL = '7';

    /** @var string Номер сборки (ревизии) */
    private $BUILD = '56';

    /** @var string Кодовое имя */
    private $CODENAME = 'Nelumbo Nucifera';

    /** @var string Дата релиза */
    private $RELDATE = '03.02.2015';

    /** @var string Текст авторских прав */
    private $COPYRIGHT = 'Авторские права &copy; 2013-2015, Lotos CMS. Все права защищены.';

    /** @var string центр поддержки */
    private $SUPPORT_CENTER = 'http://lotos-cms.ru';

    /** @var string Ссылка на центр поддержки */
    private $LINK = '<a href="http://lotos-cms.ru">Lotos CMS</a> - свободное программное обеспечение (The MIT License)';

    /**
     * Получение полного формата версии
     *
     * @return string
     * @modification : 01.01.2014
     */
    public static function getLongVersion()
    {
        $_class = __CLASS__;
        $_version = new $_class;
        return $_version->CMS . ' ' . $_version->CMS_VER . '.' . $_version->DEV_LEVEL . '.' . $_version->BUILD . ' [' . $_version->CODENAME . ']';
    }

    /**
     * Получение значения свойства класса
     *
     * @param string $name : имя свойства
     *
     * @return mixed
     * @modification : 01.01.2014
     */
    public static function get($name)
    {
        $_class = __CLASS__;
        $_version = new $_class;
        if(property_exists(__CLASS__, $name)) {
            return $_version->$name;
        } else {
            return null;
        }
    }
}