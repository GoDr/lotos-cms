<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package     Boss Content
 * @copyright   Авторские права (C) 2000-2013 Gold Dragon.
 * @license     The MIT License (MIT)
 * @dascription Модуль позволяет вставлять содержимое статьи
 * @see         http://wiki.lotos-cms.ru/index.php/BossContent
 */
class mod_boss_content_Helper
{
    /** @var string : перфикс CSS модуля */
    private $_moduleclass_sfx = '';

    /** @var array : ключ => значения адресной строки */
    private $_url = array();

    /** @var int : выводить ли ошибки */
    private $_error_view = 0;

    /** @var array : ошибки */
    private $_error = array();

    function getParams($params, $module)
    {
        $_lang = LLang::getLang('mod.boss_content');

        $this->_moduleclass_sfx = trim($params->get('moduleclass_sfx', ''));

        $this->_error_view = intval($params->get('error_view', 1));

        $_url = trim($params->get('url', ''));

        // Парсим адрес
        $_url = parse_url($_url, PHP_URL_QUERY);
        parse_str($_url, $this->_url);

        // Корректрируем значения
        $this->_url['option'] = isset($this->_url['option']) ? $this->_url['option'] : null;
        $this->_url['task'] = isset($this->_url['task']) ? $this->_url['task'] : null;
        $this->_url['catid'] = isset($this->_url['catid']) ? $this->_url['catid'] : null;
        $this->_url['contentid'] = isset($this->_url['contentid']) ? $this->_url['contentid'] : null;
        $this->_url['directory'] = isset($this->_url['directory']) ? $this->_url['directory'] : null;

        // Проверяем на корректность
        if (!$this->_url['option'] or $this->_url['option'] != 'com_boss') {
            $this->_error[] = $_lang['ERROR_OPTION'];
        }
        if (!$this->_url['task'] or $this->_url['task'] != 'show_content') {
            $this->_error[] = $_lang['EXCEP_TASK'];
        }
        if (!$this->_url['catid']) {
            $this->_error[] = $_lang['EXCEP_CATID'];
        }
        if (!$this->_url['contentid']) {
            $this->_error[] = $_lang['EXCEP_CONTENTID'];
        }
        if (!$this->_url['directory']) {
            $this->_error[] = $_lang['EXCEP_DIRECTORY'];
        }
    }

    /**
     * @param $params
     * @param $moduleid
     *
     * @return bool
     *
     * @modification 26.12.2014 Gold Dragon
     */
    public function getHTML($params, $moduleid)
    {
        $this->getParams($params, $moduleid);

        // Выводим ошибки если естьи включены и завершаем обработку
        if (sizeof($this->_error)) {
            if ($this->_error_view) {
                echo implode("<br>", $this->_error);
            }
            return false;
        }

        // Выводим содержимое
        if (function_exists('show_content')) {
            // Получаем шаблон
            $_db = LCore::getDB();
            $sql = "SELECT `template` FROM `#__boss_config` WHERE `id` = ?";
            $template = $_db->selectCell($sql, $this->_url['directory']);
            $template = (isset($template)) ? $template : 'default';

            $_task = (isset($_REQUEST['task'])) ? $_REQUEST['task'] : '';
            $_REQUEST['task'] = 'show_content';

            $results = show_content($this->_url['contentid'], $this->_url['catid'], $this->_url['directory'], $template);
            boss_show_cached_result($results['params']);

            $_REQUEST['task'] = $_task;

            // Увеличение количества просмотров. Не учитывается если пользователь автор статьи
            $content_userid = $results['contentid'];
            $my = LCore::getUser();
            if ($my->id <> $content_userid) {
                $_db->update("UPDATE #__boss_" . $this->_url['directory'] . "_contents SET views = LAST_INSERT_ID(views+1) WHERE id = ?", $this->_url['contentid']);
            }
        }

        return true;
    }

}
