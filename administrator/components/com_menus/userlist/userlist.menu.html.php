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
include_once(MainFrame::getInstance()->getLangFile('com_users'));

class userlist_menu_html
{

    public static function edit($menu, $lists, $params)
    {
        $acl = & gacl::getInstance();
        LHtml::loadOverlib();

        $gtree = $acl->get_group_children_tree(null, 'USERS', false);
        $gtree[0] = LHtml::makeOption(0, 'All');

        ?>
        <div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
        <script>
            function submitbutton(pressbutton) {
                var form = document.adminForm;
                if (pressbutton == 'cancel') {
                    submitform(pressbutton);
                    return;
                }

                // do field validation
                if (trim(form.name.value) == "") {
                    alert("<?php echo _LINK_MUST_HAVE_NAME?>");
                }

                if (form.gid.value == "29") {
                    alert("<?php echo _BAD_GROUP_1?>");
                }
                else if (form.gid.value == "30") {
                    alert("<?php echo _BAD_GROUP_2?>");
                }
                else if (form.gid.value == 0) {
                    form.link.value = "index.php?option=com_users&task=userlist";
                    submitform(pressbutton);
                }
                else {
                    form.link.value = "index.php?option=com_users&task=userlist&group=" + form.gid.value;
                    submitform(pressbutton);
                }
            }
        </script>

        <form action="index2.php" method="post" name="adminForm">
            <table class="adminheading">
                <tr>
                    <th class="menus">
                        <?php
                        echo $menu->id ? _EDITING . ' - ' : _CREATION . ' - ';
                        echo _USERS_USERLIST;
                        ?>
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
                                <td width="20%" align="right"><?php echo _NAME ?>:</td>
                                <td width="80%">
                                    <input class="inputbox" type="text" name="name" size="50" maxlength="150" value="<?php echo htmlspecialchars($menu->name, ENT_QUOTES); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" valign="top">
                                    <?php echo _LINK_TITLE ?>:
                                </td>
                                <td width="80%">
                                    <input class="inputbox" type="text" name="params[title]" size="50" maxlength="100" value="<?php echo htmlspecialchars($params->get('title', ''), ENT_QUOTES); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo _GROUP ?>:</td>
                                <td width="70%">
                                    <?php echo LHtml::selectList($gtree, 'gid', 'size="1"', 'value', 'text', $params->get('group', '')); ?>
                                    <br/>
                                    <?php echo $menu->link; ?>
                                    <input class="inputbox" type="hidden" name="link" size="50" maxlength="250" value="<?php echo $menu->link; ?>"/>
                                    <input class="inputbox" type="hidden" name="params[group]" size="50" maxlength="250" value="<?php echo $params->get('group', ''); ?>"/>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="right"><?php echo _LINK_TARGET ?></td>
                                <td><?php echo $lists['target']; ?></td>
                            </tr>
                            <tr>
                                <td align="right"><?php echo _PARENT_MENU_ITEM ?>:</td>
                                <td><?php echo $lists['parent']; ?></td>
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
                        </table>
                    </td>
                    <td width="30%">
                        <table class="adminform">
                            <tr>
                                <th><?php echo _PARAMETERS ?></th>
                            </tr>
                            <tr>
                                <td><?php echo $params->render(); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <input type="hidden" name="option" value="com_menus"/>
            <input type="hidden" name="id" value="<?php echo $menu->id; ?>"/>
            <input type="hidden" name="menutype" value="<?php echo $menu->menutype; ?>"/>
            <input type="hidden" name="type" value="<?php echo $menu->type; ?>"/>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="hidemainmenu" value="0"/>
            <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1"/>
        </form>
    <?php
    }
}

