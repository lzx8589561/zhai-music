/** index.js By Beginner Emain:zheng_jinfan@126.com HomePage:http://www.zhengjinfan.cn */
layui.config({
	base: 'js/'
}).use(['element', 'layer','form'], function() {
	var element = layui.element(),
		$ = layui.jquery,
		form = layui.form();
		layer = layui.layer;
	//iframe自适应
	$(window).on('resize', function() {
		var $content = $('.admin-nav-card .layui-tab-content');
		$content.height($(this).height() - 147);
		$content.find('iframe').each(function() {
			$(this).height($content.height());
		});
	}).resize();

	$('.layui-nav-item').find('a').removeClass('layui-this');
	var wl = String(window.location);
	$('.layui-nav-child dd').each(function(){
		//alert();
		if(wl.indexOf($(this).find('a').attr('href'))>0){
			$(this).find('a').parent().addClass('layui-this');
		}

	});
	$('#addPlayer').click(function () {
		//默认prompt
		layer.prompt({title: '请输入播放器名称'},function (val, index) {
			$.post("/admin/player/add", {name:val}, function (data) {
				if (data.code === 1) {
					layer.alert(data.msg);
					location.replace(location.href);
				} else {
					layer.alert('添加失败！');
				}
				layer.close(index);
			});
		});
	});
	$('#addSongSheet').click(function () {
		//默认prompt
		layer.prompt({title: '请输入歌单名称'},function (val, index) {
			$.post("/admin/songSheet/add", {name:val}, function (data) {
                if (data.code === 1) {
					layer.alert(data.msg);
					location.replace(location.href);
				} else {
                    layer.alert('添加失败！');
				}
				layer.close(index);
			});
		});
	});

    form.on('submit(updPwd)', function (data) {
        var beforePwd = $("#beforePwd").val();
        var newPwd = $("#newPwd").val();
        var chkPwd = $("#chkPwd").val();

        if(newPwd !=chkPwd ){
        	layer.msg("两次密码输入不一致！");
        	return false;
		}
        if(newPwd.length < 6){
			layer.msg("密码长度不能小于6位！");
			return false;
		}

        $.post("/admin/user/resetPwd", {beforePwd:beforePwd,newPwd:newPwd}, function (data) {
            layer.alert(data.msg ? data.msg : '操作失败！');
        });
        return false;
    });

	$('.admin-side-toggle').on('click', function() {
		var sideWidth = $('#admin-side').width();
		if(sideWidth === 200) {
			$('#admin-body').animate({
				left: '0'
			}); //admin-footer
			$('#admin-footer').animate({
				left: '0'
			});
			$('#admin-side').animate({
				width: '0'
			});
		} else {
			$('#admin-body').animate({
				left: '200px'
			});
			$('#admin-footer').animate({
				left: '200px'
			});
			$('#admin-side').animate({
				width: '200px'
			});
		}
	});

	//锁屏
	$(document).on('keydown', function() {
		var e = window.event;
		if(e.keyCode === 76 && e.altKey) {
			//alert("你按下了alt+l");
			lock($, layer);
		}
	});
	$('#lock').on('click', function() {
		lock($, layer);
	});

	//手机设备的简单适配
	var treeMobile = $('.site-tree-mobile'),
		shadeMobile = $('.site-mobile-shade');
	treeMobile.on('click', function() {
		$('body').addClass('site-mobile');
	});
	shadeMobile.on('click', function() {
		$('body').removeClass('site-mobile');
	});


});
function delPlayer(id) {
    layui.config({
        base: 'js/'
    }).use(['layer'], function() {
        var $ = layui.jquery,
            layer = layui.layer;
        layer.confirm('确定要删除播放器？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post("/admin/player/del", {id:id}, function (data) {
                if (data.code === 1) {
                    layer.alert(data.msg);
                    location.replace('/admin/');
                } else {
                    layer.alert('删除失败！');
                }
                layer.close(index);
            });
        }, function(){
        });
    });
}
function delSongSheet(id) {
    layui.config({
        base: 'js/'
    }).use(['layer'], function() {
        var $ = layui.jquery,
            layer = layui.layer;
        layer.confirm('确定要删除歌单？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.post("/admin/songSheet/del", {id:id}, function (data) {
                if (data.code === 1) {
                    layer.alert(data.msg);
                    location.replace('/admin/');
                } else {
                    layer.alert('删除失败！');
                }
                layer.close(index);
            });
        }, function(){
        });
    });
}

function lock($, layer) {
	//自定页
	layer.open({
		title: false,
		type: 1,
		closeBtn: 0,
		anim: 6,
		content: $('#lock-temp').html(),
		shade: [0.9, '#393D49'],
		success: function(layero, lockIndex) {

			//给显示用户名赋值
			layero.find('div#lockUserName').text('admin');
			layero.find('input[name=lockPwd]').on('focus', function() {
					var $this = $(this);
					if($this.val() === '输入密码解锁..') {
						$this.val('').attr('type', 'password');
					}
				})
				.on('blur', function() {
					var $this = $(this);
					if($this.val() === '' || $this.length === 0) {
						$this.attr('type', 'text').val('输入密码解锁..');
					}
				});
			//在此处可以写一个请求到服务端删除相关身份认证，因为考虑到如果浏览器被强制刷新的时候，身份验证还存在的情况			
			//do something...
			//e.g. 
			/*
			 $.post(url,params,callback,'json');
			 */
			//绑定解锁按钮的点击事件
			layero.find('button#unlock').on('click', function() {
				var $lockBox = $('div#lock-box');

				var userName = $lockBox.find('div#lockUserName').text();
				var pwd = $lockBox.find('input[name=lockPwd]').val();
				if(pwd === '输入密码解锁..' || pwd.length === 0) {
					layer.msg('请输入密码..', {
						icon: 2,
						time: 1000
					});
					return;
				}
				unlock(userName, pwd);
			});
			/**
			 * 解锁操作方法
			 * @param {String} 用户名
			 * @param {String} 密码
			 */
			var unlock = function(un, pwd) {
				//这里可以使用ajax方法解锁
				/*$.post('api/xx',{username:un,password:pwd}},function(data){
				 	//验证成功
					if(data.success){
						//关闭锁屏层
						layer.close(lockIndex);
					}else{
						layer.msg('密码输入错误..',{icon:2,time:1000});
					}					
				},'json');
				*/

				//演示：默认输入密码都算成功
				//关闭锁屏层
				layer.close(lockIndex);
			};
		}
	});
};
