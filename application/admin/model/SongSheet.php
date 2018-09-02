<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/30
 * Time: 15:18
 */

namespace app\admin\model;


use think\Model;

/**
 * 歌单模型
 * Class SongSheet
 * @package app\admin\model
 */
class SongSheet extends Model
{
    // 一对多关联
    public function songs(){
        return $this->hasMany('Song','song_sheet_id');
    }

    // 查询歌单关联的播放器
    public function songSheetPlayers($songSheetId){
        return model('PlayerSongSheet')->where('song_sheet_id',$songSheetId)->select();
    }
}