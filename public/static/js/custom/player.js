/**
 * Created by LiuZhenXing on 2017/2/6.
 */
layui.config({
    base: 'js/'
}).use(['element', 'layer', 'form'], function () {
    var element = layui.element(),
        $ = layui.jquery,
        form = layui.form();
    layer = layui.layer;
    form.on('submit(*)', function (data) {
        $.post("/players/editSave", data.field, function (data) {
            if (data.success) {
                layer.alert(data.success);
            } else {
                layer.alert(data.error);
            }
        });
        return false;
    });

    var tul = document.getElementById("handle-1");
    Sortable.create(tul, {
    	handle: '.drag-handle',
    	animation: 150
    });

    $('.addSongSheet').click(function () {
        htmlStr = '<li><span class="drag-handle">☰</span> ' + $(this).attr("ss-name") + '<div style="float:right"><a href="#"><i class="layui-icon">&#xe642;</i></a> <a onclick="delPlayerSongSheet(this)" href="#"><i class="layui-icon">&#x1006;</i></a></div><input type="hidden" name="ids" value="' + $(this).attr("ss-id") + '"></li>';
        $('#handle-1').append(htmlStr);
    });

    $('#savePlayerSongSheet').click(function () {
        $.post("/players/editPlayerSongSheet", $('#playerSongSheetForm').serialize(), function (data) {
            if (data.success) {
                layer.alert(data.success);
            } else {
                layer.alert(data.error);
            }
        });
    });
});

function delPlayerSongSheet(entity){
    layui.use('jquery', function(){
        $ = layui.jquery;
        $(entity).parent().parent().remove();
    });
}