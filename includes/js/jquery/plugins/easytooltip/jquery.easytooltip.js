/*
 * 	Easy Tooltip 1.0 - jQuery plugin
 *	written by Alen Grakalic	
 *	http://cssglobe.com/post/4380/easy-tooltip--jquery-plugin
 *
 *	Copyright (c) 2009 Alen Grakalic (http://cssglobe.com)
 *	Dual licensed under the MIT (MIT-LICENSE.txt)
 *	and GPL (GPL-LICENSE.txt) licenses.
 *
 *	Built for jQuery library
 *	http://jquery.com
 *---------------------------------------------------------------------------------------------
 *	Example 1:
 *
 *	[js]
 *	$(document).ready(function(){
 * 		$("a").easyTooltip();
 *	});
 *
 * 	[html]
 *	<a href="http://lotos-cms.ru" title="Lotos CMS">ссылка</a>
 *---------------------------------------------------------------------------------------------
 *	Example 2:
 *
 *	[js]
 *	$(document).ready(function(){
 *		$("a#link").easyTooltip({
 *			tooltipId: "easyTooltip2",
 *			content: '<h4>Заголовок</h4><p>Текст текст, <strong>выделенный</strong> текст.</p>'
 *		});
 *	});
 *
 * 	[html]
 *	<a id="link" href="http://lotos-cms.ru" title="Lotos CMS">ссылка</a>
 *---------------------------------------------------------------------------------------------
 *	Example 3:
 *
 *	[js]
 *	$(document).ready(function(){
 *		$("a#link").easyTooltip({
 *			tooltipId: "easyTooltip3",
 *			useElement: "item"
 * 		});
 *	});
 *
 *	[css]
 * 	#item{display:none;}
 *
 * 	[html]
 * 	<a id="link" href="http://lotos-cms.ru" title="Lotos CMS">ссылка</a>
 *	<div id="item">
 *		<h3>Item title</h3>
 *		<img src="http://lotos-cms.ru/images/files/loading.gif" />
 *		<p>Текст текст текст текст текст текст текст текст.</p>
 *		<ul>
 *			<li>Строка 1</li>
 *			<li>Строка 2</li>
 *			<li>Строка 3</li>
 *		</ul>
 *	</div>
 *
 */
;(function ($) {
    $.fn.easyTooltip = function (options) {

        // default configuration properties
        var defaults = {
            xOffset: 20,
            yOffset: 15,
            tooltipId: "easyTooltip",
            clickRemove: false,
            content: "",
            useElement: ""
        };

        var options = $.extend(defaults, options);
        var content;

        this.each(function () {
            var title = $(this).attr("title");
            $(this).hover(function (e) {
                    content = (options.content != "") ? options.content : title;
                    content = (options.useElement != "") ? $("#" + options.useElement).html() : content;
                    $(this).attr("title", "");
                    if (content != "" && content != undefined) {
                        $("body").append("<div id='" + options.tooltipId + "'>" + content + "</div>");
                        $("#" + options.tooltipId)
                            .css("position", "absolute")
                            .css("top", (e.pageY - options.yOffset) + "px")
                            .css("left", (e.pageX + options.xOffset) + "px")
                            .css("display", "none")
                            .fadeIn("fast")
                    }
                },
                function () {
                    $("#" + options.tooltipId).remove();
                    $(this).attr("title", title);
                });
            $(this).mousemove(function (e) {
                $("#" + options.tooltipId)
                    .css("top", (e.pageY - options.yOffset) + "px")
                    .css("left", (e.pageX + options.xOffset) + "px")
            });
            if (options.clickRemove) {
                $(this).mousedown(function (e) {
                    $("#" + options.tooltipId).remove();
                    $(this).attr("title", title);
                });
            }
        });

    };

})(jQuery);
