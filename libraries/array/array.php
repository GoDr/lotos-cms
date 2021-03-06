<?php defined('_LINDEX') or die;
/**
 * Joostina Lotos CMS 1.4.3
 *
 * @package   LIBRARIES
 * @version   1.4.4
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2013 Gold Dragon
 * @license   GNU GPL: http://www.gnu.org/licenses/gpl-3.0.html
 *            Joostina Lotos CMS - свободное программное обеспечение распространяемое по условиям лицензии GNU/GPL. (help/copyright.php)
 * @Date      11.07.2013
 * @see       http://wiki.lotos-cms.ru/index.php/LibArray
 */

/**
 * Class LibArray - Класс для работы с массвами
 */
class LibArray{

    /**
     * Преобразование одномерного массива в объект
     *
     * @param array $array - массив с данными
     *
     * @return stdClass - объект с данными
     */
    public static function ArrayToObject($array = array()){
        $object = new stdClass();
        if(is_array($array) and sizeof($array)){
            foreach($array as $key => $value){
                $object->$key = $value;
            }
        }
        return $object;
    }


}
