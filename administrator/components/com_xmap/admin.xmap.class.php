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
class XMapAdminClass
{
    /** @var array настройки компонента */
    protected $_config = array();

    /** @var array зарегистрированные плагины */
    protected static $plugins = array();

    /**
     * Создание файла карты сайта sitemap.xml
     */
    public function createSitemap()
    {
        $_lang = LLang::getLang('com.xmap');

        $result = array();

        $result[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $result[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach (self::$plugins as $name) {
            $plugin = new $name;
            $result[] = $plugin->createSitemap($this->_config['sef']);
        }
        $result[] = '</urlset> ';
        $sitemap = implode('', $result);

        file_put_contents(_LPATH_ROOT . '/sitemap.xml', $sitemap);
        mosRedirect('index2.php?option=com_xmap', $_lang['XMAP_SAVE_SITEMAP_OK']);
    }

    /**
     * Регистрация плагинов
     *
     * @modyfication : 13.10.2014
     */
    public function __construct()
    {
        $_db = LCore::getDB();

        // подключаем плагины
        foreach (glob(_LPATH_ADM_COM . "/com_xmap/plugins/*.plugin.php") as $value) {
            require_once($value);
        }

        // Получение настройки из базы
        $rows = $_db->select("SELECT `name`, `value` FROM `#__xmap`;");
        $_config = array();
        foreach ($rows as $value) {
            $_config[$value['name']] = $value['value'];
        }

        // Принудительное включение кеширования
        $this->_config['cache'] = (isset($_config['cache'])) ? $_config['cache'] : 1;

        // Время жизни кеша
        $this->_config['cachetime'] = (isset($_config['cachetime'])) ? $_config['cachetime'] : 0;

        // Откуда брать шаблон
        $this->_config['template'] = (isset($_config['template'])) ? $_config['template'] : 0;

        // Название шаблона
        $this->_config['templatename'] = (isset($_config['templatename'])) ? $_config['templatename'] : 'default.tpl';

        // Преобразовывать ссылки в SEF
        $this->_config['sef'] = (isset($_config['sef'])) ? $_config['sef'] : '1';
    }

    /**
     * Запись настроек компонента
     *
     * @modification 13.01.2015 Gold dragon
     */
    public function saveConfig()
    {
        $_lang = LLang::getLang('com.xmap');
        $_db = LCore::getDB();
        $sql_value = array();
        foreach ($_REQUEST as $key => $value) {
            $b = preg_match('#^cfg_(.*)#', $key, $tmp);
            if ($b) {
                $sql_value[] = $tmp[1];
                $sql_value[] = $value;
            }
        }
        $inquiry = array_fill(0, sizeof($sql_value) / 2, '(?, ?)');

        $sql = 'REPLACE INTO `#__xmap` (`name`, `value`) VALUES ' . implode(",", $inquiry) . ';';
        $_db->insert($sql, $sql_value);
        mosRedirect('index2.php?option=com_xmap', $_lang['XMAP_CFG_SAVE_OK']);
    }

    /**
     * Отображение страницы настроек компонента
     *
     * @modyfication : 13.01.2015
     */
    public function viewConfig()
    {
        XmapAdminHtml::formConfig($this->_config);
    }

    /**
     * Отображение страницы по умолчанию
     *
     * @modyfication : 23.12.2014
     */
    public function defaultPage()
    {
        $_lang = LLang::getLang('com.xmap');
        $plugin_ver = array();
        $link_num = array();
        $sitemap = array();
        foreach (self::$plugins as $name) {
            $plugin = new $name;
            $plugin_ver[] = $plugin->getVersion();
            $link_num[] = $plugin->getLinkNum();
        }

        // Проверка файла Sitemap.xml
        if (is_file(_LPATH_ROOT . '/sitemap.xml')) {

            // Проверяем размер файла
            if (filesize(_LPATH_ROOT . '/sitemap.xml') > 10485760) {
                ob_start();
                LHtml::toolTip('', $_lang['XMAP_SITEMAP_MES_3']);
                $toolTip = ob_get_contents();
                ob_end_clean();
                $sitemap[] = '<tr><td>' . $toolTip . " " . $_lang['XMAP_SITEMAP_MES_2'] . '</td><td class="red">' . filesize(_LPATH_ROOT . '/sitemap.xml') . '</td></tr>';
            } else {
                $sitemap[] = '<tr><td>' . $_lang['XMAP_SITEMAP_MES_2'] . '</td><td class="green">' . filesize(_LPATH_ROOT . '/sitemap.xml') . '</td></tr>';
            }

            // Проверяем количество ссылок
            $file = file_get_contents(_LPATH_ROOT . '/sitemap.xml');
            preg_match_all('#<loc>.*?</loc>#is', $file, $tmp);
            if (sizeof($tmp[0]) > 50000) {
                ob_start();
                LHtml::toolTip('', $_lang['XMAP_SITEMAP_MES_5']);
                $toolTip = ob_get_contents();
                ob_end_clean();
                $sitemap[] = '<tr><td>' . $toolTip . " " . $_lang['XMAP_SITEMAP_MES_4'] . '</td><td class="red">' . sizeof($tmp[0]) . '</td></tr>';
            } else {
                $sitemap[] = '<tr><td>' . $_lang['XMAP_SITEMAP_MES_4'] . '</td><td class="green">' . sizeof($tmp[0]) . '</td></tr>';
            }

            $sitemap[] = '<tr><td>' . $_lang['XMAP_SITEMAP_MES_6'] . '</td><td>' . date ("d.m.Y H:i:s", filemtime(_LPATH_ROOT . '/sitemap.xml')) . '</td></tr>';

        } else {
            $sitemap[] = '<tr><td colspan="2">' . $_lang['XMAP_SITEMAP_MES_1'] . '</td></tr>';
        }

        XmapAdminHtml::defaultPage($plugin_ver, $link_num, $sitemap, $_lang);
    }

    /**
     * Запись настроек ссылок
     *
     * @modification 23.12.2014 Gold Dragon
     */
    public function saveLink()
    {
        foreach (self::$plugins as $name) {
            $plugin = new $name;
            $plugin->saveLink();
        }
    }

    /**
     * Вывод настроек ссылок
     *
     * @modification 22.12.2014 Gold Dragon
     */
    public function configLink()
    {
        ob_start();
        foreach (self::$plugins as $name) {
            $plugin = new $name;
            $plugin->configLink();
        }
        $result = ob_get_contents();
        ob_end_clean();
        XmapAdminHtml::formEdit($result);
    }

    /**
     * Регистрация плагинов
     *
     * @param $name  :имя  плагина
     * @param $class : имя класса плагина
     *
     * @modification 22.12.2014 Gold Dragon
     */
    public static function registryPlugin($name, $class)
    {
        if (!in_array($name, self::$plugins)) {
            self::$plugins[$name] = $class;
        }
    }

    /**
     * Возвращает значение настройки компонента
     *
     * @param $param : имя параметра
     *
     * @return null|string : значение параметра
     */
    public function getConfig($param)
    {
        if (array_key_exists($param, $this->_config)) {
            return $this->_config[$param];
        } else {
            return null;
        }
    }

}