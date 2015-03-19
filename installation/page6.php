<?php defined('_LINDEX') or die('STOP in file ' . __FILE__);
/**
 * @package     Lotos CMS INSTALLATION
 * @version     1.0
 * @author      Lotos CMS <support@lotos-cms.ru>
 * @link        http://lotos-cms.ru
 * @copyright   Авторские права (C) 2014 Lotos CMS.
 * @date        01.01.2014
 * @see         http://wiki.lotos-cms.ru/index.php/Installation
 * @license     MIT License: /copyright/MIT_License.lic
 *              Для получения дополнительной информации об авторских правах, смотрите в каталоге copyright
 * @description Седьмая страница установки
 */

function getContent()
{
    $siteUrl = (isset($_POST['siteUrl'])) ? trim($_POST['siteUrl']) : _LPATH_SITE;
    $siteport = (isset($_POST['siteport'])) ? trim($_POST['siteport']) : _LPATH_SITE_PORT;
	$siteUrl = (intval($siteport) == 80) ? $siteUrl : $siteUrl . ':' . $siteport;
    $adminLogin = (isset($_POST['adminLogin'])) ? trim($_POST['adminLogin']) : 'admin';
    $adminPassword = (isset($_POST['adminPassword'])) ? trim($_POST['adminPassword']) : '';

    $DBSample = (isset($_POST['DBSample'])) ? trim($_POST['DBSample']) : 1;
    $DBSample = ($DBSample === 0 or $DBSample === '0') ? 0 : 1;

    $info['left'] = getLeft(1, 1, 1, 1, 1);
    $info['title'] = 'Завершение установки';

    $info['button'] = '';
    if (!$DBSample and is_dir('../images/boss')) {
        $info['button'] .= '<a id="simpl" class="a_but" href="javascript:void(0)">Удалить ДЕМО-данные</a>';
    }
    $info['button'] .= '<a id="inst" class="a_but" href="javascript:void(0)">Удалить INSTALLATION</a>';
    $info['button'] .= '<a class="a_but" href="' . $siteUrl . '">Перейти на сайт</a>';
    $info['button'] .= '<a class="a_but" href="' . $siteUrl . '/administrator">Перейти в Панель управления</a>';
    $info['content']
        = '
    <h1>Поздравляем Вас!</h1><h1>Вы успешно установили Lotos CMS!</h1>
    <p>Сайт доступен по адресу: <a href="' . $siteUrl . '" target="_blank">' . $siteUrl . '</a></p>
    <p>Панель управления доступна по адресу: <a href="' . $siteUrl . '/administrator" target="_blank">' . $siteUrl . '/administrator</a></p>
    <p>Данные для входа в <b>Панель управления:</b>
    <ul>
        <li>Логин: <b>' . $adminLogin . '</b></li>
        <li>Пароль: <b>' . $adminPassword . '</b></li>
    </ul>
    </p>
    <br>
    <div class="install-text">В ЦЕЛЯХ БЕЗОПАСНОСТИ ВЫ ДОЛЖНЫ УДАЛИТЬ КАТАЛОГ <b>INSTALLATION</b>!.</div>
<script>
    $(function () {
        $("#inst").click(function () {
            $.get("' . $siteUrl . '/installation/install.ajax.php?task=rminstalldir", function (data) {
                $("#inst").remove();
                $("#simpl").remove();
            });
        });
        $("#simpl").click(function () {
            $.get("' . $siteUrl . '/installation/install.ajax.php?task=rminstallsimpl", function (data) {
                $("#simpl").remove();
            });
        });
    });
</script>
    ';

    return $info;
}




































