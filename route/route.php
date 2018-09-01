<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'admin/hello');

// 前端首页
Route::get('/','index/index/index');

// 后台首页
Route::get('/admin/','admin/index/index');

// 播放器详情
Route::get('/admin/player/:id','admin/player/index');

// id搜索歌曲
Route::post('/admin/songSheet/selSong','admin/SongSheet/selSong');
// 保存歌单歌曲
Route::post('/admin/songSheet/editSongSheetSong','admin/SongSheet/editSongSheetSong');
// 添加歌单
Route::post('/admin/songSheet/add','admin/SongSheet/add');
// 编辑歌单
Route::post('/admin/songSheet/edit','admin/SongSheet/edit');
// 歌单首页
Route::get('/admin/songSheet/:id','admin/SongSheet/index');

return [

];
