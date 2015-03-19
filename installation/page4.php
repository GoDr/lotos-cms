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
 * @description пятая страница установки
 */

function getContent()
{
    $DBhostname = (isset($_POST['DBhostname'])) ? trim($_POST['DBhostname']) : '';
    $DBuserName = (isset($_POST['DBuserName'])) ? trim($_POST['DBuserName']) : '';
    $DBpassword = (isset($_POST['DBpassword'])) ? trim($_POST['DBpassword']) : '';
    $DBname = (isset($_POST['DBname'])) ? trim($_POST['DBname']) : '';
    $DBPrefix = (isset($_POST['DBPrefix'])) ? trim($_POST['DBPrefix']) : 'jos_';

    $sitename = (isset($_POST['sitename'])) ? trim($_POST['sitename']) : '';
    $siteUrl = (isset($_POST['siteUrl'])) ? trim($_POST['siteUrl']) : preg_replace('#\:[\d]*[\s]*$#i', '',_LPATH_SITE);
    $siteport = (isset($_POST['siteport'])) ? trim($_POST['siteport']) : trim(_LPATH_SITE_PORT, ':');
    $absolutePath = (isset($_POST['absolutePath'])) ? trim($_POST['absolutePath']) : _LPATH_ROOT;
    $absolutePath = str_replace('\\', '/', $absolutePath);

    $adminLogin = (isset($_POST['adminLogin'])) ? trim($_POST['adminLogin']) : 'admin';
    $adminPassword = (isset($_POST['adminPassword'])) ? trim($_POST['adminPassword']) : makePassword();
    $adminEmail = (isset($_POST['adminEmail'])) ? trim($_POST['adminEmail']) : '';

    $DBSample = (isset($_POST['DBSample'])) ? trim($_POST['DBSample']) : 1;
    $DBSample = ($DBSample === 0 or $DBSample === '0') ? 0 : 1;

    $info['left'] = getLeft(1, 1, 1, 0, 0);
    $info['title'] = 'Настройка Сайта';

    $info['button'] = getButton(5, 'Далее', 'form');
    $info['content']
        = '
    <form action="index.php?page=5" method="post" name="form" id="form">
	    <input type="hidden" name="DBhostname" value="' . $DBhostname . '" />
	    <input type="hidden" name="DBuserName" value="' . $DBuserName . '"/>
	    <input type="hidden" name="DBpassword" value="' . $DBpassword . '"/>
	    <input type="hidden" name="DBname" value="' . $DBname . '"/>
	    <input type="hidden" name="DBPrefix" value="' . $DBPrefix . '"/>
        <input type="hidden" name="DBSample" value="' . $DBSample . '" />
    <table class="content" width="100%">
        <tr>
			<td>Название сайта</td>
			<td><input class="inputbox" type="text" name="sitename" size="40" value="'.$sitename.'"/></td>
			<td>Например: Мой новый сайт!</td>
		</tr>
        <tr>
            <td>URL сайта</td>
			<td><input class="inputbox" type="text" name="siteUrl" value="' . $siteUrl . '" size="40"/></td>
			<td><img src="img/info.png" alt="Внимание" style="float: left; padding-right: 5px" />Это значение как правило не требует вмешательства пользователя</td>
		</tr>
        <tr>
			<td>Абсолютный путь</td>
			<td><input class="inputbox" type="text" name="absolutePath" value="' . $absolutePath . '" size="40"/></td>
			<td><img src="img/info.png" alt="Внимание" style="float: left; padding-right: 5px" />Это значение как правило не требует вмешательства пользователя</td>
		</tr>
        <tr>
			<td>Порт HTTP</td>
			<td><input class="inputbox" type="text" name="siteport" value="' . $siteport . '" size="4"/></td>
			<td><img src="img/info.png" alt="Внимание" style="float: left; padding-right: 5px" />Если при подключении Вам необходимо указывать нестандартный порт HTTP, то укажите только цифры. Если Вам это неизвестно, то оставьте поле пустым</td>
		</tr>
		<tr>
			<td>Ваш логин</td>
			<td><input class="inputbox" type="text" name="adminLogin" value="' . $adminLogin . '" size="40"/></td>
			<td>Используется как логин для авторизации главного Администратора сайта. Длина логина должна быть больше 2 и не больше 30 символов</td>
		</tr>
		<tr>
			<td>Пароль Администратора</td>
			<td><input class="inputbox" type="text" name="adminPassword" value="' . $adminPassword . '" size="40"/></td>
			<td>Пароль должен содержать минимум 8 символов и должен содержать минимум два символа отличных от цифр</td>
		</tr>
		<tr>
		    <td>Ваш E-mail</td>
			<td><input class="inputbox" type="text" name="adminEmail" value="' . $adminEmail . '" size="40"/></td>
			<td>Используется как адрес главного Администратора сайта</td>
		</tr>
	</table>
    </form>
    ';
    return $info;
}




































