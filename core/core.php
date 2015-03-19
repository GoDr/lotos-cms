<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS CORE
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LCore
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

// подключаем файл для автозагрузки классов
require_once(_LPATH_ROOT . '/core/autoloader.php');

// подключаем вспомогательный класс для вывода HTML-кода
require_once(_LPATH_ROOT . '/core/html.php');


/**
 * Основной класс - Ядро
 */
class LCore
{
    /** @var object Интерфейс класса ядра */
    private static $_instance;

    /** @var object Интерфейс класса конфигурации */
    private static $_config;

    /** @var object Интерфейс класса БД */
    private static $_db;

    /** @var object Интерфейс класса mosUser */
    private static $_user;

    /** @var object Интерфейс класса Language */
    private static $_lang;

    /**
     * Конструктор
     */
    private function __construct()
    {
    }

    /**
     * Подключение редактора
     */
    public static function connectionEditor()
    {
        global $_PLUGINS;
        require_once _LPATH_ROOT . '/includes/editor.php';
    }

    /**
     * @static Подключение класса
     * @return object
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            $class_name = __CLASS__;
            self::$_instance = new $class_name;
        }
        return self::$_instance;
    }

    /**
     * Возвращает интерфейс для работы с базой данных
     *
     * @return object LibMySQLi
     */
    public static function getDB()
    {
        if (!isset(self::$_db)) {
            self::$_db = LibMySQLi::Init();
        }
        return self::$_db;
    }

    /**
     * Возвращает интерфейс для работы с пользователем
     *
     * @return object
     * @see
     */
    public static function getUser()
    {
        if (!isset(self::$_user)) {
            $_mainframe = MainFrame::getInstance();
            self::$_user = $_mainframe->getUser();
        }
        return self::$_user;
    }

    /**
     * Подключение библиотек (/libraries/...)
     *
     * @param $str - имя библиотеки, оно же имя файла $str.php
     *
     * @return bool - false - нет файла, true - файл подключен
     */
    public static function getLib($str)
    {
        $file_lib = _LPATH_LIBRARIES . '/' . $str . '/' . $str . '.php';
        if (is_file($file_lib)) {
            require_once($file_lib);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получение значения конфигурации
     *
     * @param $varname - параметр конфигурации
     *
     * @return JConfig|null|object - значение параметра
     */
    public static function getCfg($varname = null)
    {
        if (!isset(self::$_config)) {
            self::$_config = JConfig::getInstance();
        }
        if (is_null($varname)) {
            return self::$_config;
        } else {

            $varname = 'config_' . $varname;
            $varname = (isset(self::$_config->$varname)) ? self::$_config->$varname : null;
            return $varname;
        }
    }

    /**
     * Получает значение из глобальной переменной или массива
     *
     * @param array       $arr        - глобальный массив
     * @param string      $name       - параметр
     * @param string|null $def        - значение по умолчанию
     * @param string|null $is         - тип переменной
     *                                s  - строка
     *                                i  - число
     *                                n  - удалить пробельные символы в начале и конце
     *                                u  - декодирует URL-кодированную строку
     *                                sn - строка без пробельных символов в начале и конце
     *
     * @return null|string - значение из глобальной переменной
     */
    public static function getParam($arr, $name, $def = null, $is = null)
    {
        $result = null;
        if (isset($arr) and isset($arr[$name])) {
            $result = $arr[$name];
            if (!is_null($is)) {
                switch ($is) {
                    case 'sn':
                        $result = trim(strval($result));
                        break;
                    case 's':
                        $result = strval($result);
                        break;
                    case 'i':
                        $result = intval($result);
                        break;
                    case 'n':
                        $result = trim($result);
                        break;
                    case 'u':
                        $result = urldecode($result);
                        break;
                }
            }
            return $result;
        } else {
            return $def;
        }
    }

    /**
     * Подключение стандартных файлов компоненов
     *
     * @param string $name  : имя компонента без префикса "com"
     * @param bool   $admin : флаг панели управелния : false - подключаем файлы фронта, true - файлы админки
     *
     * @modification 25.12.2013 Gold Dragon
     */
    public static function requireFilesCom($name, $admin = false)
    {
        if ($admin) {
            if (file_exists(_LPATH_ADM_COM . '/com_' . $name . '/admin.' . $name . '.html.php')) {
                require_once(_LPATH_ADM_COM . '/com_' . $name . '/admin.' . $name . '.html.php');
            }
            if (file_exists(_LPATH_ADM_COM . '/com_' . $name . '/admin.' . $name . '.config.php')) {
                require_once(_LPATH_ADM_COM . '/com_' . $name . '/admin.' . $name . '.config.php');
            }
            if (file_exists(_LPATH_ADM_COM . '/com_' . $name . '/admin.' . $name . '.class.php')) {
                require_once(_LPATH_ADM_COM . '/com_' . $name . '/admin.' . $name . '.class.php');
            }
        } else {
            if (file_exists(_LPATH_COM . '/com_' . $name . '/' . $name . '.html.php')) {
                require_once(_LPATH_COM . '/com_' . $name . '/' . $name . '.html.php');
            }
            if (file_exists(_LPATH_COM . '/com_' . $name . '/' . $name . '.config.php')) {
                require_once(_LPATH_COM . '/com_' . $name . '/' . $name . '.config.php');
            }
            if (file_exists(_LPATH_COM . '/com_' . $name . '/' . $name . '.class.php')) {
                require_once(_LPATH_COM . '/com_' . $name . '/' . $name . '.class.php');
            }
        }
    }
	
    /**
     * Определение Главной страницы
     *
     * @return bool : TRUE - Главная
     *
     * @modification 11.05.2014 Gold Dragon
     */
    public static function isFrontPage(){
        $result = LSef::getTask();
        if(empty($result)){
            return true;
        }else{
            return false;
        }
    }

}

