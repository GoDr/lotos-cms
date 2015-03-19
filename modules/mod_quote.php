<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);
/**
 * @package      Quote
 * @version      1.0
 * @author       Gold Dragon & Lotos CMS <support@lotos-cms.ru>
 * @link         http://lotos-cms.ru
 * @date         24.10.2013
 * @copyright    Авторские права (C) 2000-2013 Gold Dragon.
 * @license      The MIT License (MIT)
 *               Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description  Quote - модуль выводит случайные цитаты
 * @see          http://wiki.lotos-cms.ru/index.php/Quote
 */


ModQuote::getHTML($params);

class ModQuote
{
    private static $error = array();

    public static function getHTML($params)
    {
        $quotes = array();

        $_lang = LLang::getLang('mod.quote');
        $_moduleclass_sfx = trim($params->get('moduleclass_sfx', ''));
        $_path = trim($params->get('path', ''),'/');
        $_pref = trim($params->get('pref', ''));
        $_error_view = intval($params->get('error_view', 1));

        $quote_file = _LPATH_ROOT . '/' . $_path . '/' . $_pref .'*.ini';
        $quote_files = glob($quote_file);
        if(empty($quote_files) and $_error_view){
            self::addError($_lang['MOD_QT_ERR_NOT_FILES']);
        }else{
            foreach($quote_files as $file){
                $_tmp = parse_ini_file($file, INI_SCANNER_RAW);
                if(isset($_tmp['quote'])){
                    $quotes = array_merge($quotes, $_tmp['quote']);
                }
            }
            if(empty($quotes)){
                self::addError($_lang['MOD_QT_ERR_NOT_QUOTE']);
            }else{
                $quote_num = sizeof($quotes) - 1;
                $quote_mes = str_replace("\n", "<br />", $quotes[mt_rand(0, $quote_num)]);

                echo '<blockquote class="message'.$_moduleclass_sfx.'">' . $quote_mes . '</blockquote>';
            }
        }

        if(!empty(self::$error)){
            echo '<div class="error'.$_moduleclass_sfx.'">'.implode('<br>', self::$error).'</div>';
        }

    }

    private static function addError($str = ''){
        if(!empty($str)){
            self::$error[] = $str;
        }
    }

}

