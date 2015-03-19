<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Core
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LAutoloader
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

spl_autoload_register(array('LAutoloader', 'Connect'));

class LAutoloader
{
    /**
     * Инициализация класса
     *
     * @param string $class_name - Имя файла класса
     *
     * @throws Exception
     *
     * @modification 24.01.2014 Gold Dragon
     */
    public static function Connect($class_name = '')
    {
        if (!class_exists($class_name)) {
            if (strpos($class_name, 'Lib') === 0) { // Библиотеки : LibНазваниеКласса
                $filename = strtolower(substr($class_name, 3));
                $path = _LPATH_LIBRARIES . '/' . $filename . '/' . $filename . '.php';
            }elseif(preg_match('#^L[A-Z]+#', $class_name)){
                $filename = strtolower(substr($class_name, 1));
                $path = _LPATH_ROOT . '/core/' . $filename . '.php';
            }else{
                $path = null;
            }
            if(!is_null($path)){
                self::includeClass($class_name, $path);
            }
        }
//        _p($class_name . ' - '. class_exists($class_name));
    }

    /**
     * Подключение файла с классом
     *
     * @param $class_name
     * @param $path
     *
     * @throws Exception
     *
     * @modification 24.01.2014 Gold Dragon
     */
    private static function includeClass($class_name, $path){
        if(file_exists($path)){
            require_once($path);
            if(!class_exists($class_name)){
                throw new Exception(sprintf("The uploaded file <b>%s</b> class <b>%s</b> not found", $path, $class_name));
            }
        }else{
            throw new Exception(sprintf("Not found the file <b>%s</b> with the class <b>%s</b>", $path, $class_name));
        }
    }
}

























