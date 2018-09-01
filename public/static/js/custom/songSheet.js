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
    form.on('submit(addSong)', function (data) {
        songType = $("#songType").val();
        songId = $("#songId").val();
        position = $(":radio[name='position']:checked").val();
        if (songType == "") {
            layer.msg("请选择歌曲类型！");
            return false;
        }
        if (songId.trim() == "") {
            layer.msg("请填写歌曲id");
            return false;
        }

        $.post("/songSheets/selSong", {songId: songId, type: songType}, function (data) {
            if (data.code === 200) {
                layer.confirm('歌曲名称：' + data.song.name + '<br/>歌手：' + data.song.artistName + ' <br/> 确认添加到歌单吗？', {
                    icon: 3,
                    title: '确认'
                }, function (index) {
                    var liHtml = '<li song_id="' + data.song.songId +
                        '" name="' + data.song.name + '" type="' + songType +
                        '" album_name="' + data.song.albumName +
                        '" artist_name="' + data.song.artistName +
                        '" album_cover="' + data.song.albumCover +
                        '" location="' + data.song.location +
                        '" lyric="' + data.song.lyric + '"><span class="drag-handle">☰</span>' +
                        ' ' + data.song.name +
                        '<div style="float:right"><a onclick="delPlayerSongSheet(this)" href="javascript:"><i class="layui-icon">ဆ</i></a></div></li>';
                    position == 'top' ? $("#handle-1").prepend(liHtml) : $("#handle-1").append(liHtml);
                    layer.msg("已添加到歌单");
                    layer.close(index);
                });
            } else {
                layer.alert("未找到歌曲请检查输入是否有误！");
            }
        });


        return false;
    });
    form.on('submit(save)', function (data) {
        var songSheetName = $(":text[name='name']").val();
        var songSheetAuthor = $(":text[name='author']").val();
        var id = $(":hidden[name='id']").val();
        var type = $("#type").val();
        var sheetId = $(":text[name='sheetId']").val();
        var data = {
            id: id,
            name: songSheetName,
            author: songSheetAuthor
        };
        if (type !== "sdtj") {
            data["type"] = type;
            data["sheetId"] = sheetId;
        } else {
            data["type"] = type;
            data["sheetid"] = "sdtj";
        }

        $.post("/songSheets/editSave", data, function (data) {
            if (data.success) {
                layer.msg(data.success);
                if (type !== "sdtj") {
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                }
            } else {
                layer.alert(data.error);
            }
        });
        return false;
    });

    form.on('select(type)', function (data) {
        if (data.value == "wygd") {
            $(".sdtj_option").hide()
            $("#sheetIdDiv").show();

        } else if (data.value == "sdtj") {
            $(".sdtj_option").show();
            $("#sheetIdDiv").hide();
        }
    });

    $('.addSongSheet').click(function () {
        htmlStr = '<li><span class="drag-handle">☰</span> ' + $(this).attr("ss-name") + '<div style="float:right"><a onclick="delPlayerSongSheet(this)" href="#"><i class="layui-icon">&#x1006;</i></a></div><input type="hidden" name="ids" value="' + $(this).attr("ss-id") + '"></li>';
        $('#handle-1').append(htmlStr);
    });

    $('#saveSongSheetSongTaxis').click(function () {

        var data = $("#handle-1 li");
        var jsonData = new Array();
        for (var x = 0; x < data.length; x++) {
            var row = $(data[x]);
            jsonData[x] = {
                songId: row.attr("song_id"),
                name: row.attr("name"),
                type: row.attr("type"),
                albumName: row.attr("album_name"),
                artistName: row.attr("artist_name"),
                albumCover: row.attr("album_cover"),
                lyric: row.attr("lyric"),
                taxis: x
            };
        }
        jsonData = JSON.stringify(jsonData);

        $.post("/songSheets/editSongSheetSong", {
            jsonData: jsonData,
            songSheetId: $("#songSheetId").val()
        }, function (data) {
            if (data.success) {
                layer.alert(data.success);
            } else {
                layer.alert(data.error);
            }
        });
    });

    var tul = document.getElementById("handle-1");
    Sortable.create(tul, {
        handle: '.drag-handle',
        animation: 150
    });

    $("#sou").keydown(function (e) {
        var curKey = e.which;
        if (curKey == 13) {
            $.post("/songSheets/searchAddSong", {songName: $(this).val()}, function (data) {
                if (data.success) {
                    var htmlArray = [];
                    $("#songTbody").html("");
                    $.each(data.songList, function (i, item) {
                        htmlArray.push('<tr>')
                        htmlArray.push('<td>' + item.name + '</td>');
                        htmlArray.push('<td>' + item.artistName + '</td>');
                        htmlArray.push('<td>' + item.albumName + '</td>');
                        htmlArray.push('<td><a class="addSong" href="javascript:" song_id="' + item.songId + '" name="' + item.name + '" type="' + item.type + '" album_name="' + item.albumName + '" artist_name="' + item.artistName + '" album_cover="' + item.albumCover + '" lyric="' + item.lyric + '"><i class="layui-icon">&#xe608;</i></a></td>');
                        htmlArray.push('</tr>')
                    });
                    $("#songTbody").append(htmlArray.join(""));

                    //重新绑定click事件
                    $(".addSong").click(function () {
                        position2 = $(":radio[name='position2']:checked").val();
                        var zhis = $(this);
                        $.post("/song/findMusicImg", {songId: zhis.attr("song_id")}, function (data) {
                            if(data.success){
                                var albumCover = data.albumCover;
                                liHtml2 = '<li song_id="' + zhis.attr("song_id") + '" name="' + zhis.attr("name") + '" type="' + zhis.attr("type") + '" album_name="' + zhis.attr("album_name") + '" artist_name="' + zhis.attr("artist_name") + '" album_cover="' + albumCover + '" lyric="' + zhis.attr("lyric") + '"><span class="drag-handle">☰</span> ' + zhis.attr("name") + '<div style="float:right"><a onclick="delPlayerSongSheet(this)" href="javascript:"><i class="layui-icon">ဆ</i></a></div></li>';
                                position2 == 'top' ? $("#handle-1").prepend(liHtml2) : $("#handle-1").append(liHtml2);
                            }
                        });

                    });
                }
            });
            return false;
        }
    });
});

function delPlayerSongSheet(entity) {
    layui.use('jquery', function () {
        $ = layui.jquery;
        $(entity).parent().parent().remove();
    });
}