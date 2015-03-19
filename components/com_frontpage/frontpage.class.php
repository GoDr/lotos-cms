<?php
/**
 * @package   Joostina
 * @copyright Авторские права (C) 2008-2010 Joostina team. Все права защищены.
 * @license   Лицензия http://www.gnu.org/licenses/gpl-2.0.htm GNU/GPL, или help/license.php
 * Joostina! - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL
 * Для получения информации о используемых расширениях и замечаний об авторском праве, смотрите файл help/copyright.php.
 */

// запрет прямого доступа
defined('_LINDEX') or die('STOP in file ' . __FILE__);

$mainframe = MainFrame::getInstance();
$mainframe->addLib('dbconfig');


/**
 * Class FrontPage
 */
class FrontPage
{
    /** @var array : хранит настройки главной страницы */
    private $_params = array();

    /**
     * Конструктор
     */
    public function __construct()
    {
        $_db = LCore::getDB();
        $row = $_db->selectCell("SELECT `params` FROM `#__menu` WHERE `link` LIKE ?;", '%com_frontpage%');
        $params = new mosParameters($row);
        $this->_params = $params->toArray();
    }

    /**
     * Возвращает занчение настройки
     *
     * @param string $name    : название настройки
     * @param string $default : значение по умолчанию
     *
     * @return string : значение настройки
     */
    public function getParam($name, $default = '')
    {
        if (array_key_exists($name, $this->_params)) {
            return $this->_params[$name];
        } else {
            return $default;
        }
    }
}

/**
 * @package    Joostina
 * @subpackage Content
 */
class mosFrontPage extends mosDBTable
{
    /**
     * @var int Primary key
     */
    var $content_id = null;
    /**
     * @var int
     */
    var $ordering = null;

    /**
     * @param database A database connector object
     */
    function mosFrontPage($db, $directory)
    {
        $this->mosDBTable('#__boss_' . $directory . '_contents', 'id', $db);
    }
}

/**
 * конфигурация компонента
 */
class frontpageConfig extends DBConfig
{

    public $directory = null;
    public $page = null;
    public $order = null;

    function __construct($group = 'com_frontpage', $subgroup = 'default')
    {
        parent::__construct($group, $subgroup);
    }


    function getConfig()
    {
        $confObject = null;
        $config = $this->getBatchValues();
        if (count($config) > 0) {
            foreach ($config as $conf) {
                $confName = $conf->name;
                $confObject->$confName = $conf->value;
            }
        }
        return $confObject;
    }

    function save_config()
    {

        if (!$this->bindConfig($_REQUEST)) {
            echo "<script> alert('" . $this->_error . "'); window.history.go(-1); </script>";
            exit();
        }

        if (!$this->storeConfig()) {
            echo "<script> alert('" . $this->_error . "'); window.history.go(-1); </script>";
            exit();
        }
    }
}