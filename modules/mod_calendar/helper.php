<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package      Calendar
 * @version      1.0
 * @author       Gold Dragon & Lotos CMS <support@lotos-cms.ru>
 * @link         http://lotos-cms.ru
 * @date         20.03.2014
 * @copyright    Авторские права (C) 2000-2014 Gold Dragon.
 * @license      The MIT License (MIT)
 *               Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description  Calendar - модуль календарь статей
 * @see          http://wiki.lotos-cms.ru/index.php/Calendar
 */
class mod_calendar_Helper
{
    /**
     * Функция выводи модуль
     *
     * @param int $moduleid : Идентификатор модуля
     *
     * @return bool : false - ошибка, true - ок
     *
     * @modification 20.03.2014 Gold Dragon
     */
    public function getHTML($moduleid = 0)
    {
        // Сегодня
        $today = date('d.m.Y');

        // Подключаем скрипт
        LHtml::addJS(_LPATH_SITE . '/modules/mod_calendar/js/easyTooltip.js', '', true);
        LHtml::addJS(_LPATH_SITE . '/modules/mod_calendar/js/nav.js', '', true);
        echo '
        <div id="clndr_' . $moduleid . '" data-path-site="' . _LPATH_SITE . '"></div>
        <script>
            $(function(){
                calendar_nav("' . $moduleid . '", "' . $today . '");
            });
        </script>
        ';
        return true;
    }
}













