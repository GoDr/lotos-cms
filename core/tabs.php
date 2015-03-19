<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS CORE
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LTabs
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

/**
 * Создание табов
 */
class LTabs
{

    /**
     * @var int Use cookies
     */
    var $useCookies = 0;

    /**
     * Constructor
     * Includes files needed for displaying tabs and sets cookie options
     *
     * @param int useCookies, if set to 1 cookie will hold last used tab between page refreshes
     */
    function __construct($useCookies, $xhtml = 0)
    {
        $mainframe = MainFrame::getInstance();
        $config = $mainframe->get('config');

        // активация gzip сжатия css и js файлов
        if ($config->config_gz_js_css) {
            $css_f = 'joostina.tabs.css.php';
            $js_f = 'joostina.tabs.js.php';
        } else {
            $css_f = 'tabpane.css';
            $js_f = 'tabpane.js';
        }
        $css_dir = ($mainframe->isAdmin() == 1) ? '/templates/admin/' . TEMPLATE . '/css' : '/templates/front/' . TEMPLATE . '/css';

        if (!is_file(_LPATH_ROOT . $css_dir . '/' . $css_f)) {
            $css_dir = _LPATH_SITE . '/includes/js/tabs';
        } else {
            $css_dir = _LPATH_SITE . $css_dir;
        }

        $css = '<link rel="stylesheet" type="text/css" media="all" href="' . $css_dir . '/' . $css_f . '" id="luna-tab-style-sheet" />';
        $js = '<script src="' . _LPATH_SITE . '/includes/js/tabs/' . $js_f . '"></script>';
        /* запрет повторного включения css и js файлов в документ */
        if (!defined('_MTABS_LOADED')) {
            define('_MTABS_LOADED', 1);

            if ($xhtml) {
                $mainframe->addCustomHeadTag($css);
                $mainframe->addCustomHeadTag($js);
            } else {
                echo $css . "\n\t";
                echo $js . "\n\t";
            }
            $this->useCookies = $useCookies;
        }
    }

    /**
     * creates a tab pane and creates JS obj
     *
     * @param string The Tab Pane Name
     */
    function startPane($id)
    {
        echo '<div class="tab-page" id="' . $id . '">';
        echo '<script>var tabPane1 = new WebFXTabPane( document.getElementById( "' . $id . '" ), ' . $this->useCookies . ' )</script>';
    }

    /**
     * Ends Tab Pane
     */
    function endPane()
    {
        echo '</div>';
    }

    /*
	 * Creates a tab with title text and starts that tabs page
	 * @param tabText - This is what is displayed on the tab
	 * @param paneid - This is the parent pane to build this tab on
	 */

    function startTab($tabText, $paneid)
    {
        echo '<div class="tab-page" id="' . $paneid . '">';
        echo '<h2 class="tab">' . $tabText . '</h2>';
        echo '<script>tabPane1.addTabPage( document.getElementById( "' . $paneid . '" ) );</script>';
    }

    /*
	 * Ends a tab page
	 */

    function endTab()
    {
        echo '</div>';
    }

}