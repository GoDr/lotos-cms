<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * XMap - Компонент создания карт сайта
 *
 * @package   XMap
 * @version   1.0
 * @author    Gold Dragon <illusive@bk.ru>
 * @link      http://gd.lotos-cms.ru
 * @copyright 2000-2015 Gold Dragon
 * @date      01.07.2014
 * @see       http://wiki.lotos-cms.ru/index.php/XMap
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */
class XmapAdminHtml
{

    /**
     * Форма настройки компонента
     *
     * @param array $config: настройки компонента
     *
     * @modification 13.01.2015 Gold Dragon
     */
    public static function formConfig($config){
        LHtml::addCSS(_LPATH_SITE . '/administrator/components/com_xmap/css/xmap.css');
        $_lang = LLang::getLang('com.xmap');
        ?>
        <div id="xmap_box">
            <form action="index2.php?option=com_xmap" method="post" name="adminForm">
                <table class="adminheading">
                    <tr>
                        <th class="categories"><?php echo $_lang['XMAP_NAME']; ?> : <?php echo $_lang['XMAP_BASIC_SETTINGS']; ?></th>
                    </tr>
                </table>
                <table class="adminlist">
                    <tr>
                        <th><?php echo $_lang['XMAP_CFG_NAME']; ?></th>
                        <th><?php echo $_lang['XMAP_CFG_VALUE']; ?></th>
                    </tr>
                    <tr>
                        <td>
                            <label for="cfg_cache"><?php echo $_lang['XMAP_CFG_NAME_1']; ?></label>
                        </td>
                        <td>
                            <?php echo LHtml::yesnoSelectList('cfg_cache', 'id="cfg_cache"', $config['cache']); ?>
                            <?php LHtml::toolTip('', $_lang['XMAP_CFG_NAME_1_DESC']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cfg_cachetime"><?php echo $_lang['XMAP_CFG_NAME_2']; ?></label>
                        </td>
                        <td>
                            <?php echo LHtml::inputType('cfg_cachetime', 'number', $config['cachetime'], 'id="cfg_cachetime" min="0" size="4"'); ?>
                            <?php LHtml::toolTip('', $_lang['XMAP_CFG_NAME_2_DESC']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cfg_template"><?php echo $_lang['XMAP_CFG_NAME_3']; ?></label>
                        </td>
                        <td>
                            <?php echo LHtml::yesnoSelectList('cfg_template', 'id="cfg_template"', $config['template'], $_lang['XMAP_CFG_YES_1'], $_lang['XMAP_CFG_NO_1']); ?>
                            <?php LHtml::toolTip('', $_lang['XMAP_CFG_NAME_3_DESC']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cfg_templatename"><?php echo $_lang['XMAP_CFG_NAME_4']; ?></label>
                        </td>
                        <td>
                            <?php echo LHtml::inputType('cfg_templatename', '', $config['templatename'], 'id="cfg_templatename"'); ?>
                            <?php LHtml::toolTip('', $_lang['XMAP_CFG_NAME_4_DESC']); ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="cfg_sef"><?php echo $_lang['XMAP_CFG_NAME_5']; ?></label>
                        </td>
                        <td>
                            <?php echo LHtml::yesnoSelectList('cfg_sef', 'id="cfg_sef"', $config['sef']); ?>
                            <?php LHtml::toolTip('', $_lang['XMAP_CFG_NAME_5_DESC']); ?>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="task" value="saveconfig"/>
            </form>
        </div>
    <?php
    }

    /**
     * Вывод главной страницы
     *
     * @param array $plugin_ver : версия плагина
     * @param array $link_num   : количество ссылок
     */
    public static function defaultPage($plugin_ver, $link_num, $sitemap, $_lang)
    {
        LHtml::addCSS(_LPATH_SITE . '/administrator/components/com_xmap/css/xmap.css');
        ?>
        <table class="adminheading" border="0">
            <tr>
                <th class="cpanel"><?php echo $_lang['XMAP_NAME']; ?></th>
            </tr>
        </table>
        <table>
            <tr>
                <td width="50%" valign="top">
                    <div class="cpicons">
                        <?php

                        $link = 'index2.php?option=com_xmap&task=configuration';
                        self::quickIconButton($link, 'configuration_b.png', $_lang['XMAP_CONFIG']);

                        $link = 'index2.php?option=com_xmap&task=configlink';
                        self::quickIconButton($link, 'reference_b.png', $_lang['XMAP_LINK']);

                        $link = 'index2.php?option=com_xmap&task=sitemap';
                        self::quickIconButton($link, 'map_b.png', $_lang['XMAP_MAP']);

                        ?>
                    </div>
                    <div style="clear:both;">&nbsp;</div>
                </td>
                <td width="50%" valign="top">
                    <table class="adminlist">
                        <tr>
                            <th align="center"><?php echo $_lang['XMAP_PLUGIN_INFO']; ?></th>
                            <th align="center"><?php echo $_lang['XMAP_LINK_INFO']; ?></th>
                        </tr>
                        <?php for ($i = 0; $i < sizeof($plugin_ver); $i++) { ?>
                            <tr>
                                <td align="center"><?php echo $plugin_ver[$i]; ?></td>
                                <td align="center"><?php echo $link_num[$i]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <table class="adminlist">
                        <tr>
                            <th colspan="2" align="center"><?php echo $_lang['XMAP_SITEMAP_MES_0']; ?></th>
                        </tr>
                        <?php echo implode('', $sitemap)?>
                    </table>
                </td>
            </tr>
        </table>
    <?php
    }

    /**
     * Вывод страницы настройки карты
     *
     * @param string $content : содержимое
     *
     * @modification 22.12.2014 Gold Dragon
     */
    public static function formEdit($content)
    {
        LHtml::addCSS(_LPATH_SITE . '/administrator/components/com_xmap/css/xmap.css');
        $_lang = LLang::getLang('com.xmap');
        ?>
        <div id="xmap_box">
            <form action="index2.php?option=com_xmap" method="post" name="adminForm">
                <table class="adminheading">
                    <tr>
                        <th class="categories"><?php echo $_lang['XMAP_NAME']; ?> : <?php echo $_lang['XMAP_LINK']; ?></th>
                    </tr>
                </table>
                <?php echo $content ?>
                <input type="hidden" name="task" value="savelink"/>
            </form>
        </div>
    <?php
    }

    /**
     * Прорисовка кнопок управления
     *
     * @param $link  - ссылка
     * @param $image - иконка
     * @param $text  - подпись
     */
    public static function quickIconButton($link, $image, $text)
    {
        ?>
        <span>
	        <a href="<?php echo $link; ?>" title="<?php echo $text; ?>">
                <?php
                echo LAdminMenu::imageCheckAdmin($image, '/administrator/components/com_xmap/images/', null, null, $text);
                echo $text;
                ?>
            </a>
        </span>
    <?php
    }
}

