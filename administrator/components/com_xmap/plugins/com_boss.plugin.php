<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * XMap - Компонент создания карт сайта
 *
 * @package     XMap/Plugins
 * @version     1.0
 * @author      Gold Dragon <illusive@bk.ru>
 * @link        http://gd.lotos-cms.ru
 * @copyright   2000-2014 Gold Dragon
 * @date        01.07.2014
 * @see         http://wiki.lotos-cms.ru/index.php/XMap
 * @license     MIT License: /copyright/MIT_License.lic
 *              Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description Плагин для компонента COM_BOSS
 */

// Регистрация класса
XMapAdminClass::registryPlugin('boss', 'XMapPluginBoss');

class XMapPluginBoss implements XMapIntf
{
    /** Версия плагина */
    const VERSION = 'XMapPluginBoss ver.1.0 23.12.2014';

    /** @var string : уникальный идентификатор плагина */
    private $id_plugin = '';

    /** @var string : параметры настроек ссылок */
    private $_params = '';

    function __construct()
    {
        $_db = LCore::getDB();

        $this->id_plugin = 'comboss';

        // Получаем настройки плагина
        $sql = "SELECT `params` FROM `#__xmap_ext` WHERE `plugin` = ?;";
        $this->_params = $_db->selectCell($sql, 'comboss');

    }

    /**
     * Создание файла карты сайта sitemap.xml
     *
     * @param int $config_sef : настройка преобразование в SEF
     *
     * @return string : записи строк в формате http://www.sitemaps.org/schemas/sitemap/0.9
     *                <url> (обязательный) : родительский тег для каждой записи URL-адреса. Остальные теги являются дочерними для этого тега
     *                <loc> (обязательный) : URL-адрес страницы
     *                <lastmod> (необязательно) : дата последнего изменения файла в формате W3C Datetime, допускается ГГГГ-ММ-ДД
     *                <changefreq> (необязательно) : always, hourly, daily, weekly, monthly, yearly, never
     *                <priority> (необязательно) : Приоритетность URL относительно других URL на сайте (от 0.0 до 1.0). По умолчанию — 0.5
     *
     * @modification 13.01.2015 Gold Dragon
     */
    public function createSitemap($config_sef)
    {
        $result = array();
        $_db = LCore::getDB();
        $params = preg_split("#\n#", $this->_params);

        for ($i = 0; $i < sizeof($params); $i++) {
            // если ссылка на каталог
            if (preg_match('#d([\d]*)=#', $params[$i], $matches)) {

                $result[] = '<url>';
                if (LCore::getCfg('sef') AND $config_sef) {
                    $result[] = '<loc>' . LSef::getUrlToSef('index.php?option=com_boss&amp;task=show_all&amp;directory=' . $matches[1], true) . '</loc>';
                } else {
                    $result[] = '<loc>' . _LPATH_SITE . '/index.php?option=com_boss&amp;task=show_all&amp;directory=' . $matches[1] . '</loc>';
                }
                $result[] = '<lastmod>' . date("c") . '</lastmod>';
                $result[] = '<changefreq>daily</changefreq>';
                $result[] = '<priority>0.8</priority>';
                $result[] = '</url>';

            }// Если это категория
            elseif (preg_match('#d([\d]*)c([\d]*)=#', $params[$i], $matches)) {

                $result[] = '<url>';
                if (LCore::getCfg('sef') AND $config_sef) {
                    $result[] = '<loc>' . LSef::getUrlToSef('index.php?option=com_boss&amp;task=show_category&amp;catid=' . $matches[2] . '&amp;directory=' . $matches[1], true) . '</loc>';
                } else {
                    $result[] = '<loc>' . _LPATH_SITE . '/index.php?option=com_boss&amp;task=show_category&amp;catid=' . $matches[2] . '&amp;directory=' . $matches[1] . '</loc>';
                }
                $result[] = '<lastmod>' . date("Y-m-d") . '</lastmod>';
                $result[] = '<changefreq>weekly</changefreq>';
                $result[] = '<priority>0.7</priority>';
                $result[] = '</url>';

            }// если это материалы
            elseif (preg_match('#d([\d]*)c([\d]*)a=#', $params[$i], $matches)) {
                $i++;
                // количество материалов в категории
                if (preg_match('#d[\d]*c[\d]*n=(.*)#', $params[$i], $matches2)) {
                    if (empty($matches2[1])) {
                        $sql = 'SELECT con.id, con.date_created
                                FROM `#__boss_' . $matches[1] . '_contents` AS con
                                LEFT JOIN `#__boss_' . $matches[1] . '_content_category_href` AS ccat ON con.id = ccat.content_id
                                WHERE ccat.category_id = ?';
                        $rows = $_db->select($sql, $matches[2]);
                    } else {
                        $sql = 'SELECT con.id, con.date_created
                                FROM `#__boss_' . $matches[1] . '_contents` AS con
                                LEFT JOIN `#__boss_' . $matches[1] . '_content_category_href` AS ccat ON con.id = ccat.content_id
                                WHERE ccat.category_id = ?
                                LIMIT ?';
                        $rows = $_db->select($sql, $matches[2], $matches2[1]);
                    }
                    if (sizeof($rows)) {
                        foreach ($rows as $value) {
                            $result[] = '<url>';
                            if (LCore::getCfg('sef') AND $config_sef) {
                                $result[] = '<loc>' . LSef::getUrlToSef('index.php?option=com_boss&amp;task=show_content&amp;catid=' . $matches[2] . '&amp;directory=' . $matches[1] . '&amp;contentid=' . $value['id'], true) . '</loc>';
                            } else {
                                $result[] = '<loc>' . _LPATH_SITE . '/index.php?option=com_boss&amp;task=show_content&amp;catid=' . $matches[2] . '&amp;directory=' . $matches[1] . '&amp;contentid=' . $value['id'] . '</loc>';
                            }
                            $result[] = '<lastmod>' . LibDateTime::formatDate($value['date_created'], "Y-m-d") . '</lastmod>';
                            $result[] = '<changefreq>yearly</changefreq>';
                            $result[] = '<priority>0.5</priority>';
                            $result[] = '</url>';
                        }
                    }
                } else {
                    $i--;
                }
            }
        }
        return implode('', $result);
    }

    /**
     * Вывод настроек ссылок
     *
     * @modification 22.12.2014 Gold Dragon
     */
    public function configLink()
    {
        $_db = LCore::getDB();

        // Получаем каталоги
        $sql = "SELECT `id`,  `name` FROM `#__boss_config`;";
        $directories = $_db->select($sql);

        // Получаем категории
        echo '<ul>';
        foreach ($directories as $value1) {
            $sql = "SELECT  `id`,  `parent`,  `name` FROM `#__boss_" . $value1['id'] . "_categories` WHERE `published` = ?;";
            $rows = $_db->select($sql, 1);
            $return = array();

            $this->itemEdit($value1['name'], 'd' . $value1['id']);
            foreach ($rows as $value2) {
                $return[$value2['parent']][] = $value2;
            }
            $this->outTree(0, 0, $return, $value1['id']);
        }
        echo '</ul>';
    }

    /**
     * Запись настроек ссылок
     *
     * @modification 23.12.2014 Gold Dragon
     */
    public function saveLink()
    {
        $_lang = LLang::getLang('com.xmap');
        $_db = LCore::getDB();
        $params = array();
        foreach ($_REQUEST as $key => $value) {
            $b = preg_match('#^comboss(.*)#', $key, $tmp);
            if ($b) {
                if (!is_numeric($value)) {
                    $value = 1;
                }
                $params[] = $tmp[1] . '=' . $value;
            }
        }
        $params = implode("\n", $params);
        $sql = "REPLACE INTO `#__xmap_ext` (`plugin`, `params`) VALUES (?, ?);";
        $_db->insert($sql, 'comboss', $params);
        mosRedirect('index2.php?option=com_xmap', $_lang['XMAP_CFG_SAVE_OK']);
    }

    /**
     * Вывод поля ссылки с настройками
     *
     * @param $name : название поля
     * @param $id   : Идентификатор поля : comboss + d + N1 + c + N2 + a + N3
     *              [comboss] - обозначает плагин
     *              [d] - обозначает каталог
     *              [N1] - идентификатор каталога
     *              [c] - обозначает категорию
     *              [N2] - идентификатор категории
     *
     * @modification 22.12.2014 Gold Dragon
     */
    private function itemEdit($name, $id)
    {
        $_lang = LLang::getLang('com.xmap');
        $checked1 = $this->checkParams($id);
        $checked2 = $this->checkParams($id . 'a');
        $checked3 = intval($this->getParams($id . 'n'));

        ?>
        <li>
            <label>
                <input <?php echo $checked1; ?> type="checkbox" id="<?php echo $this->id_plugin . $id ?>" name="<?php echo $this->id_plugin . $id ?>">
                <?php echo $name ?>
            </label>
            <?php if (preg_match('#c#', $id)) { ?>
                <label class="selectblock">
                    <?php echo $_lang['XMAP_VIEW_ARTICLE'] ?>
                    <input <?php echo $checked2; ?> type="checkbox" id="<?php echo $this->id_plugin . $id ?>a"
                                                    name="<?php echo $this->id_plugin . $id ?>a">
                    <?php LHtml::toolTip('', $_lang['XMAP_VIEW_ARTICLE_DESC']); ?>
                </label>
                <label class="selectblock">
                    <?php echo $_lang['XMAP_VIEW_ARTICLE_NUM'] ?>
                    <input value="<?php echo $checked3; ?>" type="number" min="0" id="<?php echo $this->id_plugin . $id ?>n"
                           name="<?php echo $this->id_plugin . $id ?>n">
                    <?php LHtml::toolTip('', $_lang['XMAP_VIEW_ARTICLE_NUM_DESC']); ?>
                </label>
            <?php } ?>
        </li>
    <?php
    }

    /**
     * Проверка наличия параметра
     *
     * @param $id : имя параметра
     *
     * @return string: атрибут выделения
     */
    private function checkParams($id)
    {
        $result = (preg_match('#^' . $id . '=#m', $this->_params, $tmp)) ? ' checked' : '';
        return $result;
    }

    /**
     * Получение значения параметра
     *
     * @param $id : имя параметра
     *
     * @return mixed : значение параметра
     */
    private function getParams($id)
    {
        preg_match('#^' . $id . '=(.*?)$#m', $this->_params, $result);
        return $result[1];
    }

    /**
     * Вывод дерева
     *
     * @param Integer $parent - id-родителя
     * @param Integer $level  - уровень вложености
     *
     * @modification 22.12.2014 Gold Dragon
     */
    private function outTree($parent, $level, $rows, $id_dir)
    {
        if (isset($rows[$parent])) {
            echo '<ul>';
            foreach ($rows[$parent] as $value) {
                $this->itemEdit($value['name'], 'd' . $id_dir . 'c' . $value['id']);
                $level++;
                $this->outTree($value['id'], $level, $rows, $id_dir);
                $level--;
            }
            echo '</ul>';
        }
    }

    /**
     * Возвращает версию плагина
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }

    /**
     * Возвращает количество активных
     *
     * @return string
     */
    public function getLinkNum()
    {
        $_db = LCore::getDB();
        // количество ссылок на каталоги
        preg_match_all('#d[\d]*=#', $this->_params, $matches);
        $result = sizeof($matches[0]);

        // Количество ссылок на категории
        preg_match_all('#d[\d]*c[\d]*=#', $this->_params, $matches);
        $result = $result + sizeof($matches[0]);

        // Количество ссылок на материалы
        preg_match_all('#(d[\d]*c[\d]*)a=(.*)#', $this->_params, $matches);
        foreach ($matches[1] as $value) {
            // получаем каталог и категорию
            preg_match('#d([\d]*)c([\d]*)#', $value, $tmp1);
            $directory = $tmp1[1];
            $category = $tmp1[2];

            // получаем количестdо материалов для отображения
            preg_match('#' . $value . 'n=(.*)#', $this->_params, $matches2);

            // запрашиваем количество материалов в базе
            if (empty($matches2[1])) { // если все материалы
                $sql = 'SELECT COUNT(*)
                        FROM `#__boss_' . $directory . '_content_category_href`
                        WHERE `category_id` = ?';
                $num = $_db->selectCell($sql, $category);
            } else {
                $sql = 'SELECT `id`
                        FROM `#__boss_' . $directory . '_content_category_href`
                        WHERE `category_id` = ?
                        LIMIT ?';
                $num = sizeof($_db->select($sql, $category, $matches2[1]));
            }
            $result = $result + $num;
        }
        return $result;
    }

    /**
     * Выводит ссылки
     */
    public function getLinks()
    {
        $result = array();
        $_db = LCore::getDB();
        $params = preg_split("#\n#", $this->_params);

        for ($i = 0; $i < sizeof($params); $i++) {
            // если ссылка на каталог
            if (preg_match('#d([\d]*)=#', $params[$i], $matches)) {
                $directory_name = $_db->selectCell('SELECT  `name` FROM `#__boss_config` WHERE `id` = ? ;', $matches[1]);
                $result[] = '<h2><a href="' . LSef::getUrlToSef('index.php?option=com_boss&amp;task=show_all&amp;directory=' . $matches[1]) . '">' . $directory_name . '</a></h2>';
            }// Если это категория
            elseif (preg_match('#d([\d]*)c([\d]*)=#', $params[$i], $matches)) {
                $category_name = $_db->selectCell('SELECT  `name` FROM `#__boss_' . $matches[1] . '_categories` WHERE `published` = ? AND `id` = ?;', 1, $matches[2]);
                $result[] = '<h3><a href="' . LSef::getUrlToSef('index.php?option=com_boss&amp;task=show_category&amp;catid=' . $matches[2] . '&amp;directory=' . $matches[1]) . '">' . $category_name . '</a></h3>';
            }// если это материалы
            elseif (preg_match('#d([\d]*)c([\d]*)a=#', $params[$i], $matches)) {
                $content_name = 'Название статьи / материала';
                $i++;
                // количество материалов в категории
                if (preg_match('#d[\d]*c[\d]*n=(.*)#', $params[$i], $matches2)) {
                    if (empty($matches2[1])) {
                        $sql = 'SELECT con.id, con.name
                                FROM `#__boss_' . $matches[1] . '_contents` AS con
                                LEFT JOIN `#__boss_' . $matches[1] . '_content_category_href` AS ccat ON con.id = ccat.content_id
                                WHERE ccat.category_id = ?';
                        $rows = $_db->select($sql, $matches[2]);
                    } else {
                        $sql = 'SELECT con.id, con.name
                                FROM `#__boss_' . $matches[1] . '_contents` AS con
                                LEFT JOIN `#__boss_' . $matches[1] . '_content_category_href` AS ccat ON con.id = ccat.content_id
                                WHERE ccat.category_id = ?
                                LIMIT ?';
                        $rows = $_db->select($sql, $matches[2], $matches2[1]);
                    }
                    if (sizeof($rows)) {
                        $result[] = '<ul>';
                        foreach ($rows as $value) {
                            $result[] = '<li><h4><a href="' . LSef::getUrlToSef('index.php?option=com_boss&amp;task=show_content&amp;catid=' . $matches[2] . '&amp;directory=' . $matches[1] . '&amp;contentid=' . $value['id']) . '">' . $value['name'] . '</a></h4></li>';
                        }
                        $result[] = '</ul>';
                    }
                } else {
                    $i--;
                }
            }
        }
        echo implode('', $result);
    }
}















