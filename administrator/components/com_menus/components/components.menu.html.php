<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Menu
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      01.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/LMenus
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

class components_menu_html
{


    public static function edit($menu, $components, $lists, $params)
    {
        LHtml::loadOverlib();
        if ($menu->id) {
            $title = '[ ' . $lists['componentname'] . ' ]';
        } else {
            $title = '';
        }
        ?>
        <div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
        <script>
            function submitbutton(pressbutton) {
                var form = document.adminForm;
                if (pressbutton == 'cancel') {
                    submitform(pressbutton);
                    return;
                }

                var comp_links = new Array;
                <?php
                foreach($components as $row){
                    ?>
                comp_links[ <?php echo $row->value; ?> ] = 'index.php?<?php echo addslashes($row->link); ?>';
                <?php
            }
            ?>
                if (form.id.value == 0) {
                    var comp_id = getSelectedValue('adminForm', 'componentid');
                    form.link.value = comp_links[comp_id];
                } else {
                    form.link.value = comp_links[form.componentid.value];
                }

                if (trim(form.name.value) == "") {
                    alert("<?php echo _OBJECT_MUST_HAVE_NAME?>");
                } else if (form.componentid.value == "") {
                    alert("<?php echo _CHOOSE_COMPONENT?>");
                } else {
                    submitform(pressbutton);
                }
            }
        </script>

        <form action="index2.php" method="post" name="adminForm">
            <table class="adminheading">
                <tr>
                    <th class="menus">
                        <?php echo $menu->id ? _EDITING . ' -' : _CREATION . ' -'; ?> <?php echo _MENU_ITEM_COMPONENT ?>
                        <small>
                            <small><?php echo $title; ?></small>
                        </small>
                    </th>
                </tr>
            </table>
            <table width="100%">
                <tr valign="top">
                    <td width="60%">
                        <table class="adminform">
                            <tr>
                                <th colspan="2"><?php echo _DETAILS ?></th>
                            </tr>
                            <tr>
                                <td width="10%" align="right"><?php echo _NAME ?>:</td>
                                <td width="80%">
                                    <input class="inputbox" type="text" name="name" size="50" maxlength="100" value="<?php echo htmlspecialchars($menu->name, ENT_QUOTES); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td width="10%" align="right" valign="top"><?php echo _LINK_TITLE ?>:</td>
                                <td width="80%">
                                    <input class="inputbox" type="text" name="params[title]" size="50" maxlength="100" value="<?php echo htmlspecialchars($params->get('title', ''), ENT_QUOTES); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="right"><?php echo _COMPONENT ?>:</td>
                                <td><?php echo $lists['componentid']; ?></td>
                            </tr>
                            <tr>
                                <td width="10%" align="right">URL:</td>
                                <td width="80%"><?php echo ampReplace($lists['link']); ?></td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo _PARENT_MENU_ITEM ?>:</td>
                                <td><?php echo $lists['parent']; ?>
                                </td>
                            </tr>

                            <tr>
                                <td valign="top" align="right"><?php echo _ORDER_DROPDOWN ?>:</td>
                                <td><?php echo $lists['ordering']; ?></td>
                            </tr>
                            <tr>
                                <td valign="top" align="right"><?php echo _ACCESS ?>:</td>
                                <td><?php echo $lists['access']; ?></td>
                            </tr>
                            <tr>
                                <td valign="top" align="right"><?php echo _PUBLISHED ?>:</td>
                                <td><?php echo $lists['published']; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    <td width="40%">
                        <table class="adminform">
                            <tr>
                                <th><?php echo _PARAMETERS ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    if ($menu->id) {
                                        echo $params->render();
                                    } else {
                                        ?>
                                        <strong><?php echo _MENU_PARAMS_AFTER_SAVE ?></strong>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <input type="hidden" name="option" value="com_menus"/>
            <input type="hidden" name="id" value="<?php echo $menu->id; ?>"/>
            <input type="hidden" name="link" value=""/>
            <input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>"/>
            <input type="hidden" name="type" value="<?php echo $menu->type; ?>"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="hidemainmenu" value="0"/>
            <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1"/>
        </form>
    <?php
    }
}
