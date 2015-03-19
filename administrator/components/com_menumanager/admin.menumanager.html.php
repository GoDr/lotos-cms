<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);

/**
 * @package   Lotos CMS Component
 * @version   1.0
 * @author    Lotos CMS <support@lotos-cms.ru>
 * @link      http://lotos-cms.ru
 * @copyright Авторские права (C) 2013-2014, Lotos CMS
 * @date      14.01.2014
 * @see       http://wiki.lotos-cms.ru/index.php/Com_menumanager
 * @license   MIT License: /copyright/MIT_License.lic
 *            Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 */

/**
 * HTML class for all menumanager component output
 *
 * @package    Joostina
 * @subpackage Menus
 */
class HTML_menumanager
{
    /**
     * Выводит список меню
     *
     * @modification 27.12.2013 Gold Dragon
     */
    public static function show($menus)
    {
        $_lang = LLang::getLang('com.menumanager');
        ?>
        <form action="index2.php?option=com_menumanager" method="post" name="adminForm">
            <table class="adminheading">
                <tr>
                    <th class="menus"><?php echo $_lang['MENU_MANAGER']; ?></th>
                </tr>
            </table>
            <table class="adminlist">
                <tr>
                    <th>ID</th>
                    <th>&nbsp;</th>
                    <th><?php echo $_lang['MENU_TYPE']; ?></th>
                    <th><?php echo $_lang['MENU_TITLE']; ?></th>
                    <th><?php echo $_lang['MENU_MOD_TOTAL']; ?></th>
                    <th><?php echo $_lang['MENU_MOD_ACTIV']; ?></th>
                    <th><?php echo $_lang['MENU_LINK_PUBL']; ?></th>
                    <th><?php echo $_lang['MENU_LINK_UNPUBL']; ?></th>
                    <th><?php echo $_lang['MENU_LINK_TRASH']; ?></th>
                </tr>
                <?php
                $k = 0;
                for ($i = 0; $i < sizeof($menus); $i++) {

                }
                foreach ($menus as $value) {
                    $link_a = 'index2.php?option=com_menumanager&amp;task=edit&amp;id=' . $value['id'];
                    $link_b = 'index2.php?option=com_menus&menutype=' . $value['id'];
                    ?>
                    <tr class="<?php echo "row" . $k; ?>">
                        <td><?php echo $value['id']; ?></td>
                        <td><input type="radio" id="cb<?php echo $i; ?>" name="cid[]" value="<?php echo $value['id']; ?>" onclick="isChecked(this.checked);"/></td>
                        <td><a href="<?php echo $link_a; ?>"><?php echo $value['type']; ?></a></td>
                        <td><a href="<?php echo $link_b; ?>"><?php echo $value['title']; ?></a></td>
                        <td><?php echo $value['modules_total']; ?></td>
                        <td><?php echo $value['modules_activ']; ?></td>
                        <td><?php echo $value['link_publ']; ?></td>
                        <td><?php echo $value['link_unpub']; ?></td>
                        <td><?php echo $value['link_trash']; ?></td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
            </table>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="hidemainmenu" value="0"/>
            <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1"/>
            <input type="hidden" name="option" value="com_menumanager"/>
        </form>
    <?php
    }

    /**
     * Вывод формы создания/редактирования типов меню
     *
     * @param int    $menu_id    : идентификатор типа меню
     * @param string $menu_type  : тип меню
     * @param string $menu_title : описание меню
     * @param array  $error      : ошибки
     *
     * @modification 27.12.2013 Gold Dragon
     */
    public static function edit($menu_id = 0, $menu_type = '', $menu_title = '', $error = array())
    {
        $_lang = LLang::getLang('com.menumanager');
        LHtml::loadOverlib();
        if (sizeof($error)) {
            $error = '<div class="error_block">' . implode('<br />', $error) . '</div>';
        } else {
            $error = '';
        }

        ?>
        <script>
            function submitbutton(pressbutton) {
                var form = document.adminForm;

                if (pressbutton == 'save') {
                    if (form.menutype.value == '') {
                        alert('<?php echo $_lang['PLEASE_ENTER_MENU_NAME']; ?>');
                        form.menutype.focus();
                        return;
                    }
                    var r = new RegExp("[\']", "i");
                    if (r.exec(form.menutype.value)) {
                        alert('<?php echo $_lang['NO_QUOTES_IN_NAME']; ?>');
                        form.menutype.focus();
                        return;
                    }
                    if (form.title.value == '') {
                        alert('<?php echo $_lang['PLEASE_ENTER_MENU_DESC_NAME']; ?>');
                        form.title.focus();
                        return;
                    }
                    submitform('save');
                } else {
                    submitform(pressbutton);
                }
            }
        </script>
        <?php echo $error; ?>
        <form action="index2.php?option=com_menumanager" method="post" name="adminForm">
            <table class="adminheading">
                <tr>
                    <th class="menus"><?php echo $_lang['MENU_INFO']; ?></th>
                </tr>
            </table>

            <table class="adminform">
                <tr>
                    <td width="150px" align="right">
                        <strong><?php echo $_lang['MENU_NAME']; ?>:</strong>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="menutype" size="30" maxlength="25" value="<?php echo $menu_type; ?>"/>
                        <?php echo mosToolTip($_lang['NO_QUOTES_IN_NAME']); ?>
                    </td>
                </tr>
                <tr>
                    <td width="150px" align="right">
                        <strong><?php echo $_lang['MENU_DESC']; ?>:</strong>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="menutitle" size="30" value="<?php echo $menu_title; ?>"/>
                        <?php echo mosToolTip($_lang['NEMU_DESC_TIP']); ?>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="id" value="<?php echo $menu_id; ?>"/>
            <input type="hidden" name="task" value="save"/>
        </form>
    <?php
    }

    /**
     * Сопирование меню
     *
     * @param int    $menu_id   : идентификатор меню
     * @param string $menu_type : название меню
     * @param array  $items     : ссылки
     *
     * @modification 17.01.2014 Gold Dragon
     */
    public static function copy($menu_id, $menu_type, $items)
    {
        $_lang = LLang::getLang('com.menumanager');
        ?>
        <script>
            function submitbutton(pressbutton) {
                if (pressbutton == 'copymenu') {
                    if (document.adminForm.menu_name.value == '') {
                        alert('<?php echo $_lang['PLEASE_ENTER_MENU_COPY_NAME']; ?>');
                        return;
                    } else {
                        submitform('copymenu');
                    }
                } else {
                    submitform(pressbutton);
                }
            }
        </script>
        <form action="index2.php?option=com_menumanager" method="post" name="adminForm">
            <table class="adminheading">
                <tr>
                    <th><?php echo $_lang['MENU_COPYING']; ?></th>
                </tr>
            </table>
            <br/>
            <table class="adminform">
                <tr>
                    <td width="3%"></td>
                    <td align="left" valign="top" width="20%">
                        <strong><?php echo $_lang['NEW_MENU_NAME']; ?>:</strong>
                        <br/>
                        <input class="inputbox" type="text" name="menu_name" size="30" value=""/>
                    </td>
                    <td align="left" valign="top" width="60%">
                        <?php echo $_lang['MENU_TO_COPY']; ?>: <strong><?php echo $menu_type; ?></strong>
                        <br/><br/>
                        <strong>
                            <?php echo _MENU_ITEMS_TO_COPY ?>:
                        </strong>
                        <br/>
                        <ol>
                            <?php
                            foreach ($items as $item) {
                                ?>
                                <li>
                                    <?php echo $item['name']; ?>
                                    <br/>
                                    <span style="color:#999"><?php echo $item['link']; ?></span>
                                    <input type="hidden" name="mids[]" value="<?php echo $item['id']; ?>"/>
                                </li>
                            <?php
                            }
                            ?>
                        </ol>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="task" value="copymenu"/>
            <input type="hidden" name="menuid" value="<?php echo $menu_id; ?>"/>
            <input type="hidden" name="<?php echo josSpoofValue(); ?>" value="1"/>
        </form>
    <?php
    }
}