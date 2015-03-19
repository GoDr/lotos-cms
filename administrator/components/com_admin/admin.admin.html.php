<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * Lotos CMS - Компонент ядра
 *
 * @package    Lotos CMS
 * @subpackage Admin
 * @version    1.0
 * @author     Gold Dragon <illusive@bk.ru>
 * @link       http://gd.lotos-cms.ru
 * @copyright  2000-2014 Gold Dragon
 * @date       01.07.2014
 * @see        http://wiki.lotos-cms.ru/index.php/XMap
 * @license    MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */
class HTML_admin_misc
{
    /**
     * Control panel
     */
    public static function controlPanel()
    {
        $path = _LPATH_TPL_ADMI . '/' . TEMPLATE . '/html/cpanel.php';
        if (file_exists($path)) {
            require $path;
        } else {
            echo '<br />';
            mosLoadAdminModules('cpanel', 1);
        }
    }

    public static function get_php_setting($val, $colour = 0, $yn = 1)
    {
        $r = (ini_get($val) == '1' ? 1 : 0);

        if ($colour) {
            if ($yn) {
                $r = $r ? '<span style="color: green;">ON</span>' : '<span style="color: red;">OFF</span>';
            } else {
                $r = $r ? '<span style="color: red;">ON</span>' : '<span style="color: green;">OFF</span>';
            }

            return $r;
        } else {
            return $r ? 'ON' : 'OFF';
        }
    }

    public static function get_server_software()
    {
        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            return $_SERVER['SERVER_SOFTWARE'];
        } else {
            if (($sf = phpversion() <= '4.2.1' ? getenv('SERVER_SOFTWARE') : $_SERVER['SERVER_SOFTWARE'])) {
                return $sf;
            } else {
                return 'n/a';
            }
        }
    }

    public static function system_info($version)
    {
        $_db = LCore::getDB();
        $info = $_db->getInfo();

        $cur_file_icons_path = _LPATH_TPL_ADMI_S . '/' . TEMPLATE . '/images/ico';

        $tabs = new LTabs(0);
        ?>
        <table class="adminheading">
            <tr>
                <th class="info"><?php echo _INFO ?></th>
            </tr>
        </table>
        <?php
        $tabs->startPane("sysinfo");
        /* Лотос ***************************************************************************************/
        $tabs->startTab(_COM_ADMIN_LOTOS, "LOTOS");
        ?>
        <table class="adminform">
            <tr>
                <td width="200"><?php echo _COM_ADMIN_CMS; ?></td>
                <td><?php echo LVersion::get('CMS'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_CODENAME; ?></td>
                <td><?php echo LVersion::get('CODENAME'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_CMS_VER; ?></td>
                <td><?php echo LVersion::get('CMS_VER'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_DEV_LEVEL; ?></td>
                <td><?php echo LVersion::get('DEV_LEVEL'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_BUILD; ?></td>
                <td><?php echo LVersion::get('BUILD'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_RELDATE; ?></td>
                <td><?php echo LVersion::get('RELDATE'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_COPYRIGHT; ?></td>
                <td><?php echo LVersion::get('COPYRIGHT'); ?></td>
            </tr>
            <tr>
                <td><?php echo _COM_ADMIN_SUPPORT_CENTER; ?></td>
                <td><?php echo LVersion::get('SUPPORT_CENTER'); ?></td>
            </tr>
        </table>
        <?php
        $tabs->endTab();
        /* Лист изменений ***************************************************************************************/
        $tabs->startTab(_COM_ADMIN_VERSION, "VERSION");
        ?>
        <table class="adminform">
            <tr>
                <td>
			<pre>
						<?php
                        include(_LPATH_ROOT . '/listchanges.log');
                        ?>
			</pre>
                </td>
            </tr>
        </table>
        <?php
        $tabs->endTab();
        /* Лицензионные соглашения ***************************************************************************************/
        $tabs->startTab(_COM_ADMIN_LICENSE, "LICENSE");
        ?>
        <table class="adminform">
            <tr>
                <td>
                    <h3>PHP</h3>
                    <pre>
						<?php
                        include(_LPATH_ROOT . '/copyright/php.license.txt');
                        ?>
			        </pre>
                    <h3>Javascript</h3>
			        <pre>
						<?php
                        include(_LPATH_ROOT . '/copyright/javascript.license.txt');
                        ?>
			        </pre>
                    <h3><?php echo _COM_ADMIN_LICENSE_A ?></h3>
                    <ul>
                        <li><a href="<?php echo _LPATH_SITE; ?>/copyright/BSD_License.lic" target="_blank">The BSD License</a></li>
                        <li><a href="<?php echo _LPATH_SITE; ?>/copyright/GNU_GPL_v2.lic" target="_blank">GNU GENERAL PUBLIC LICENSE (v.2)</a></li>
                        <li><a href="<?php echo _LPATH_SITE; ?>/copyright/GNU_GPL_v3.lic" target="_blank">GNU GENERAL PUBLIC LICENSE (v.3)</a></li>
                        <li><a href="<?php echo _LPATH_SITE; ?>/copyright/MIT_License.lic" target="_blank">The MIT License (MIT)</a></li>
                        <li><a href="<?php echo _LPATH_SITE; ?>/copyright/MPL.lic" target="_blank">Mozilla Public License</a></li>
                        <li><a href="<?php echo _LPATH_SITE; ?>/copyright/PHP_License_v3.01.lic" target="_blank">The PHP License, version 3.01</a></li>
                    </ul>
                </td>
            </tr>
        </table>
        <?php
        $tabs->endTab();
        /* Информация о системе ***************************************************************************************/
        $tabs->startTab(_ABOUT_SYSTEM, "system-page");
        ?>
        <table class="adminform">
            <tr>
                <td><strong><?php echo _LOTOS_VERSION ?>:</strong></td>
                <td><?php echo LVersion::getLongVersion(); ?></td>
            </tr>
            <tr>
                <td width="250"><strong><?php echo _SYSTEM_OS ?>:</strong></td>
                <td><?php echo php_uname(); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo _COM_ADMIN_MYSQL_C ?>:</strong></td>
                <td><?php echo $info['client_info']; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo _COM_ADMIN_MYSQL_S ?>:</strong></td>
                <td><?php echo $info['server_info']; ?></td>
            </tr>
            <tr>
                <td><strong><?php echo _PHP_VERSION ?>:</strong></td>
                <td><?php echo phpversion(); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo _APACHE_VERSION ?>:</strong></td>
                <td><?php echo HTML_admin_misc::get_server_software(); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo _PHP_APACHE_INTERFACE ?>:</strong></td>
                <td><?php echo php_sapi_name(); ?></td>
            </tr>
            <tr>
                <td><strong><?php echo _BROWSER ?>:</strong></td>
                <td><?php echo MainFrame::getUserBrowser(); ?></td>
            </tr>
            <tr>
                <td valign="top">
                    <strong><?php echo _PHP_SETTINGS ?>:</strong>
                </td>
                <td>
                    <table cellspacing="1" cellpadding="1" border="0">
                        <tr>
                            <td><?php echo _FILE_UPLOAD ?>:</td>
                            <td style="font-weight: bold;">
                                <?php echo HTML_admin_misc::get_php_setting('file_uploads', 1, 1); ?>
                            </td>
                            <td>
                                <?php $img = ((!ini_get('file_uploads')) ? 'publish_x.png' : 'tick.png'); ?>
                                <img src="<?php echo $cur_file_icons_path; ?>/<?php echo $img; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _SESSION_HANDLING ?>:</td>
                            <td style="font-weight: bold;">
                                <?php echo HTML_admin_misc::get_php_setting('session.auto_start', 1, 0); ?>
                            </td>
                            <td>
                                <?php $img = ((ini_get('session.auto_start')) ? 'publish_x.png' : 'tick.png'); ?>
                                <img src="<?php echo $cur_file_icons_path; ?>/<?php echo $img; ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _SESS_SAVE_PATH ?>:</td>
                            <td style="font-weight: bold;" colspan="2">
                                <?php echo(($sp = ini_get('session.save_path')) ? $sp : 'none'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _PHP_TAGS ?>:</td>
                            <td style="font-weight: bold;">
                                <?php echo HTML_admin_misc::get_php_setting('short_open_tag'); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _BUFFERING ?>:</td>
                            <td style="font-weight: bold;">
                                <?php echo HTML_admin_misc::get_php_setting('output_buffering'); ?>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _OPEN_BASEDIR ?>:</td>
                            <td style="font-weight: bold;" colspan="2">
                                <?php echo(($ob = ini_get('open_basedir')) ? $ob : 'none'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _ERROR_REPORTING ?>:</td>
                            <td style="font-weight: bold;" colspan="2">
                                <?php echo HTML_admin_misc::get_php_setting('display_errors'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _XML_SUPPORT ?>:</td>
                            <td style="font-weight: bold;" colspan="2">
                                <?php echo extension_loaded('xml') ? 'Yes' : 'No'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _ZLIB_SUPPORT ?>:</td>
                            <td style="font-weight: bold;" colspan="2">
                                <?php echo extension_loaded('zlib') ? 'Yes' : 'No'; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo _DISABLED_FUNCTIONS ?>:</td>
                            <td style="font-weight: bold;" colspan="2">
                                <?php echo(($df = ini_get('disable_functions')) ? $df : 'none'); ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="height: 10px;">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><strong><?php echo _CONFIGURATION_FILE ?>:</strong></td>
                <td>
                    <?php
                    $cf = file(_LPATH_ROOT . '/configuration.php');
                    foreach ($cf as $k => $v) {
                        if (preg_match('/mosConfig_host/i', $v)) {
                            $cf[$k] = '$mosConfig_host = \'xxxxxx\'';
                        } elseif (preg_match('/mosConfig_user/i', $v)) {
                            $cf[$k] = '$mosConfig_user = \'xxxxxx\'';
                        } elseif (preg_match('/mosConfig_password/i', $v)) {
                            $cf[$k] = '$mosConfig_password = \'xxxxxx\'';
                        } elseif (preg_match('/mosConfig_db /i', $v)) {
                            $cf[$k] = '$mosConfig_db = \'xxxxxx\'';
                        }
                    }
                    foreach ($cf as $k => $v) {
                        $k = htmlspecialchars($k);
                        $v = htmlspecialchars($v);
                        $cf[$k] = $v;
                    }
                    echo implode("<br />", $cf);
                    ?>
                </td>
            </tr>
        </table>
        <?php
        $tabs->endTab();
        /* PHP ***************************************************************************************/
        $tabs->startTab("PHP Info", "php-page");
        ?>
        <table class="adminform">
            <tr>
                <td>
                    <?php
                    ob_start();
                    phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES);
                    $phpinfo = ob_get_contents();
                    ob_end_clean();
                    preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output);
                    $output = preg_replace('#<table#', '<table class="adminlist" align="center"', $output[1][0]);
                    $output = preg_replace('#(\w),(\w)#', '\1, \2', $output);
                    $output = preg_replace('#border="0" cellpadding="3" width="600"#', 'border="0" cellspacing="1" cellpadding="4" width="95%"', $output);
                    $output = preg_replace('#<hr />#', '', $output);
                    echo $output;
                    ?>
                </td>
            </tr>
        </table>
        <?php
        $tabs->endTab();
        /****************************************************************************************/
        $tabs->startTab(_ACCESS_RIGHTS, 'perms');
        $sp = ini_get('session.save_path');
        ?>
        <strong><?php echo _DIRS_WITH_RIGHTS ?>:</strong>
        <table class="adminform">
                <?php
                LHtml::writableCell('administrator/components');
                LHtml::writableCell('administrator/modules');
                LHtml::writableCell('components');
                LHtml::writableCell('components');
                LHtml::writableCell('images');
                LHtml::writableCell('images/backups');
                LHtml::writableCell('images/show');
                LHtml::writableCell('images/stories');
                LHtml::writableCell('languages');
                LHtml::writableCell('plugins/content');
                LHtml::writableCell('plugins/editors');
                LHtml::writableCell('plugins/editors-xtd');
                LHtml::writableCell('plugins/search');
                LHtml::writableCell('plugins/system');
                LHtml::writableCell('media');
                LHtml::writableCell('modules');
                LHtml::writableCell('settings');
                LHtml::writableCell('templates/admin');
                LHtml::writableCell('templates/components');
                LHtml::writableCell('templates/front');
                LHtml::writableCell('templates/modules');
                LHtml::writableCell('templates/system');
                LHtml::writableCell(LCore::getCfg('cachepath'), 0, '<strong>' . _CACHE_DIR . '</strong> ');
                LHtml::writableCell($sp, 0, '<strong>' . _SESSION_DIRECTORY . '</strong> ');
                ?>
        </table>
        <?php
        $tabs->endTab();
        /* база данных ***************************************************************************************/
        $tabs->startTab(_DATABASE, 'db');
        ?>
        <table class="adminform">
            <tr>
                <th><?php echo _TABLE_NAME ?>:</th>
                <th><?php echo _DB_CHARSET ?>:</th>
                <th><?php echo _COM_ADMIN_DB_ENGINE ?>:</th>
                <th><?php echo _DB_NUM_RECORDS ?>:</th>
                <th><?php echo _DB_SIZE ?>:</th>
                <th><?php echo _DB_INDEX_LENGTH ?>:</th>
                <th><?php echo _COM_ADMIN_DB_UPDATE_TIME ?>:</th>
                <th><?php echo _COM_ADMIN_DB_DATA_FREE ?>:</th>
            </tr>
            <?php
            $db_info = HTML_admin_misc::db_info();
            $k = 0;
            foreach ($db_info as $table) {
                if ($table->Collation != 'utf8_general_ci') {
                    $table->Collation = '<span style="color:#ff0000"><b>' . $table->Collation . '</b></span>';
                }
                if ($table->Data_free) {
                    $table->Data_free = '<span style="color:#ff0000"><b>' . $table->Data_free . '</b></span>';
                }
                echo '<tr><td><b>' . $table->Name . '</b></td>
                        <td>' . $table->Collation . '</td>
                        <td>' . $table->Engine . '</td>
                        <td>' . $table->Rows . '</td>
                        <td>' . $table->Data_length . '</td>
                        <td>' . $table->Index_length . '</td>
                        <td>' . $table->Update_time . '</td>
                        <td>' . $table->Data_free . '</td>
                        </tr>';
                $k = 1 - $k;
            }
            ?>

        </table>
        <?php
        $tabs->endTab();
        $tabs->endPane();
        ?>
    <?php
    }

    // получение информации о базе данных
    public static function db_info()
    {
        $database = database::getInstance();
        $sql = 'SHOW TABLE STATUS FROM ' . LCore::getCfg('db');
        $database->setQuery($sql);
        return $database->loadObjectList();
    }

    public static function ListComponents()
    {
        $database = database::getInstance();

        $query = "SELECT params FROM #__modules WHERE module = 'mod_components'";
        $database->setQuery($query);
        $row = $database->loadResult();
        $params = new mosParameters($row);

        mosLoadAdminModule('components', $params);
    }

    /**
     * Display Help Page
     */
    public static function help()
    {
        $helpurl = strval(mosGetParam($GLOBALS, 'mosConfig_helpurl', ''));

        if ($helpurl == 'http://help.mamboserver.com') {
            $helpurl = 'http://help.joomla.org';
        }
        $fullhelpurl = $helpurl . '/index2.php?option=com_boss&amp;task=findkey&pop=1&keyref=';

        $helpsearch = strval(mosGetParam($_REQUEST, 'helpsearch', ''));
        $helpsearch = addslashes(htmlspecialchars($helpsearch));

        $page = strval(mosGetParam($_REQUEST, 'page', 'joomla.whatsnew100.html'));
        $toc = getHelpToc($helpsearch);
        if (!preg_match('/\.html$/', $page)) {
            $page .= '.xml';
        }

        echo $helpsearch;
        ?>
        <style type="text/css">
            .helpIndex {
                border: 0px;
                width: 95%;
                height: 100%;
                padding: 0px 5px 0px 10px;
                overflow: auto;
            }

            .helpFrame {
                border-left: 0px solid #222;
                border-right: none;
                border-top: none;
                border-bottom: none;
                width: 100%;
                height: 700px;
                padding: 0px 5px 0px 10px;
            }
        </style>
        <form name="adminForm">
            <table class="adminform" border="1">
                <tr>
                    <th colspan="2" class="title"><?php echo _HELP; ?></th>
                </tr>
                <tr>
                    <td colspan="2">
                        <table width="100%">
                            <tr>
                                <td>
                                    <strong><?php echo _SEARCH ?>:</strong>
                                    <input class="text_area" type="hidden" name="option" value="com_admin"/>
                                    <input type="text" name="helpsearch" value="<?php echo $helpsearch; ?>" class="inputbox"/>
                                    <input type="submit" value="<?php echo _FIND ?>" class="button"/>
                                    <input type="button" value="<?php echo _CLEAR ?>" class="button" onclick="f=document.adminForm;f.helpsearch.value='';f.submit()"/>
                                </td>
                                <td style="text-align:right">
                                    <?php
                                    if ($helpurl) {
                                        ?>
                                        <a href="<?php echo $fullhelpurl; ?>joomla.glossary" target="helpFrame"><?php echo _GLOSSARY ?></a>
                                        |
                                        <a href="<?php echo $fullhelpurl; ?>joomla.credits" target="helpFrame"><?php echo _DEVELOPERS ?></a>
                                        |
                                        <a href="<?php echo $fullhelpurl; ?>joomla.support" target="helpFrame"><?php echo _SUPPORT ?></a>
                                    <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo _LPATH_SITE; ?>/help/joomla.glossary.html" target="helpFrame"><?php echo _GLOSSARY ?></a>
                                        |
                                        <a href="<?php echo _LPATH_SITE; ?>/help/joomla.credits.html" target="helpFrame"><?php echo _DEVELOPERS ?></a>
                                        |
                                        <a href="<?php echo _LPATH_SITE; ?>/help/joomla.support.html" target="helpFrame"><?php echo _SUPPORT ?></a>
                                    <?php
                                    }
                                    ?>
                                    |
                                    <a href="http://www.gnu.org/licenses/gpl-2.0.htm" target="helpFrame"><?php echo _LICENSE ?></a>
                                    |
                                    <a href="http://help.joomla.org" target="_blank">help.joomla.org</a>
                                    |
                                    <a href="http://Joom.Ru" target="_blank">Joom.Ru</a>
                                    <br/>
                                    <a href="<?php echo _LPATH_SITE; ?>/<?php echo JADMIN_BASE ?>/index3.php?option=com_admin&task=changelog" target="helpFrame"><?php echo _CHANGELOG ?></a>
                                    |
                                    <a href="<?php echo _LPATH_SITE; ?>/<?php echo JADMIN_BASE ?>/index3.php?option=com_admin&task=sysinfo" target="helpFrame"><?php echo _ABOUT_SYSTEM ?></a>
                                    |
                                    <a href="http://joostina-cms.ru/" target="_blank"><?php echo _CHECK_VERSION ?></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr valign="top">
                    <td width="20%" valign="top">
                        <strong><?php echo _TOC_JUMPTO ?></strong>

                        <div class="helpIndex">
                            <?php
                            foreach ($toc as $k => $v) {
                                if ($helpurl) {
                                    echo '<br /><a href="' . $fullhelpurl . urlencode($k) . '" target="helpFrame">' . $v .
                                        '</a>';
                                } else {
                                    echo '<br /><a href="' . _LPATH_SITE . '/help/' . $k . '" target="helpFrame">' .
                                        $v . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </td>
                    <td valign="top">
                        <iframe name="helpFrame" src="<?php echo _LPATH_SITE . '/help/' . $page; ?>" class="helpFrame" frameborder="0"></iframe>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="task" value="help"/>
            <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1"/>
        </form>
    <?php
    }

    /**
     * Preview site
     */
    public static function preview($tp = 0)
    {
        $tp = intval($tp);
        ?>
        <style type="text/css">
            .previewFrame {
                border: none;
                width: 95%;
                height: 600px;
                padding: 0px 5px 0px 10px;
            }
        </style>
        <table class="adminform">
            <tr>
                <th width="50%" class="title"><?php echo _PREVIEW_SITE ?></th>
                <th width="50%" style="text-align:right">
                    <a href="<?php echo _LPATH_SITE . '/index.php?tp=' . $tp; ?>" target="_blank"><?php echo _IN_NEW_WINDOW ?></a>
                </th>
            </tr>
            <tr>
                <td width="100%" valign="top" colspan="2">
                    <iframe name="previewFrame" src="<?php echo _LPATH_SITE . '/index.php?tp=' . $tp; ?>" class="previewFrame"></iframe>
                </td>
            </tr>
        </table>
    <?php
    }

    /*
    * Displays contents of Changelog.php file
    */
    public static function changelog()
    {
        ?>
        <pre>
			<?php
            readfile(_LPATH_ROOT . '/changeslog');
            ?>
</pre>
    <?php
    }
}

/**
 * Compiles the help table of contents
 *
 * @param string A specific keyword on which to filter the resulting list
 */
function getHelpTOC($helpsearch)
{
    $helpurl = strval(mosGetParam($GLOBALS, 'mosConfig_helpurl', ''));
    $files = mosReadDirectory(_LPATH_ROOT . '/help/', '\.xml$|\.html$');

    require_once(_LPATH_ROOT . '/includes/domit/xml_domit_lite_include.php');

    $toc = array();
    foreach ($files as $file) {
        $buffer = file_get_contents(_LPATH_ROOT . '/help/' . $file);
        if (preg_match('#<title>(.*?)</title>#', $buffer, $m)) {
            $title = trim($m[1]);
            if ($title) {
                if ($helpurl) {
                    // strip the extension
                    $file = preg_replace('#\.xml$|\.html$#', '', $file);
                }
                if ($helpsearch) {
                    if (strpos(strip_tags($buffer), $helpsearch) !== false) {
                        $toc[$file] = $title;
                    }
                } else {
                    $toc[$file] = $title;
                }
            }
        }
    }
    asort($toc);
    return $toc;
}