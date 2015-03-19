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
 * @description Вторая страница установки
 */

function getContent()
{
    $info['left'] = getLeft(1, 0, 0, 0, 0);
    $info['title'] = 'Лицензионное соглашение';

    $info['button'] = getButton(0, 'Проверка системы');
    $info['button'] .= getButton(2, 'Далее');
    $info['content']
        = '
<div class="licen">

<h1 style="font-size: 150%; font-weight: 700">The MIT License (MIT)</h1>
<h3>English version</h3>

<div style="text-align: justify">
<p>Copyright &copy; 2013-2014 Lotos CMS.
<p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
<p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
<p>THE SOFTWARE IS PROVIDED «AS IS», WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</div>

<h3>Русская версия</h3>

<div style="text-align: justify">
<p>Copyright &copy; 2013-2014 Lotos CMS.
<p>Данная лицензия разрешает лицам, получившим копию данного программного обеспечения и сопутствующей документации (в дальнейшем именуемыми «Программное Обеспечение»), безвозмездно использовать Программное Обеспечение без ограничений, включая неограниченное право на использование, копирование, изменение, добавление, публикацию, распространение, сублицензирование и/или продажу копий Программного Обеспечения, также как и лицам, которым предоставляется данное Программное Обеспечение, при соблюдении следующих условий:
<p>Указанное выше уведомление об авторском праве и данные условия должны быть включены во все копии или значимые части данного Программного Обеспечения.
<p>ДАННОЕ ПРОГРАММНОЕ ОБЕСПЕЧЕНИЕ ПРЕДОСТАВЛЯЕТСЯ «КАК ЕСТЬ», БЕЗ КАКИХ-ЛИБО ГАРАНТИЙ, ЯВНО ВЫРАЖЕННЫХ ИЛИ ПОДРАЗУМЕВАЕМЫХ, ВКЛЮЧАЯ, НО НЕ ОГРАНИЧИВАЯСЬ ГАРАНТИЯМИ ТОВАРНОЙ ПРИГОДНОСТИ, СООТВЕТСТВИЯ ПО ЕГО КОНКРЕТНОМУ НАЗНАЧЕНИЮ И ОТСУТСТВИЯ НАРУШЕНИЙ ПРАВ. НИ В КАКОМ СЛУЧАЕ АВТОРЫ ИЛИ ПРАВООБЛАДАТЕЛИ НЕ НЕСУТ ОТВЕТСТВЕННОСТИ ПО ИСКАМ О ВОЗМЕЩЕНИИ УЩЕРБА, УБЫТКОВ ИЛИ ДРУГИХ ТРЕБОВАНИЙ ПО ДЕЙСТВУЮЩИМ КОНТРАКТАМ, ДЕЛИКТАМ ИЛИ ИНОМУ, ВОЗНИКШИМ ИЗ, ИМЕЮЩИМ ПРИЧИНОЙ ИЛИ СВЯЗАННЫМ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ ИЛИ ИСПОЛЬЗОВАНИЕМ ПРОГРАММНОГО ОБЕСПЕЧЕНИЯ ИЛИ ИНЫМИ ДЕЙСТВИЯМИ С ПРОГРАММНЫМ ОБЕСПЕЧЕНИЕМ.
</div>

</div>
    ';
    return $info;
}
