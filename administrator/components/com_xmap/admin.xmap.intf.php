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

interface XMapIntf {
    /** Создание файла карты сайта sitemap.xml
     *
     * @param int $config_sef : настройка преобразование в SEF
     *
     * @return mixed
     */
    public function createSitemap($config_sef);

    /** Вывод настроек */
    public function configLink();

    /** Запись настроек */
    public function saveLink();

    /** Возвращает данные о версии плагина */
    public function getVersion();

    /** Возвращает количество ссылок */
    public function getLinkNum();

    /** Выводит ссылки */
    public function getLinks();
}