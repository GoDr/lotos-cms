function calendar_nav(id, val){
    var path_site = $("#clndr_" + id).data('path-site');
    $("#clndr_" + id).html('<div style="text-align: center"><img src="'+ path_site + '/modules/mod_calendar/images/loading.gif" /></div>').animate({opacity:1}, 600);
    $.get(path_site + '/ajax.index.php?option=com_boss&act=calendar&id=' + id + '&val=' + val, function(data) {
        $("#clndr_" + id).html(data);
    });
    return false;
}
