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
class XMapClass extends XMapAdminClass
{
    /**
     * Вывод карты сайта
     */
    public function getMap()
    {
        $_lang = LLang::getLang('com.xmap');

        ob_start();
        foreach (parent::$plugins as $name) {
            $plugin = new $name;
            $plugin->getLinks();
        }
        $result = ob_get_contents();
        ob_end_clean();

        // Загружаем шаблон
        if($this->getConfig('template') and is_file(_LPATH_TPL_FRONT . '/html/components/com_xmap/'. $this->getConfig('templatename'))){
            $html = file_get_contents(_LPATH_TPL_FRONT . '/html/components/com_xmap/'. $this->getConfig('templatename'));
        }else{
            $html = file_get_contents(_LPATH_TPL_COM . '/com_xmap/'. $this->getConfig('templatename'));
        }

        // Вставляем данные в шаблон
        $html = str_replace('[[XMAP_NAME]]', $_lang['XMAP_NAME'], $html);
        $html = str_replace('[[XMAP_REFERENCE]]', $result, $html);

        // Выводим результат
        echo $html;
    }

}

























