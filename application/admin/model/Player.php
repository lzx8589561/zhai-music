<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/30
 * Time: 16:25
 */

namespace app\admin\model;


use think\Db;
use think\Model;

/**
 * 播放器模型
 * Class Player
 * @package app\admin\model
 */
class Player extends Model
{

    /**
     * 获取播放器歌单
     * @param $playerId
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function songSheets($playerId){
        return Db::table('player_song_sheet')
            ->alias('pss')
            ->join('song_sheet ss', 'ss.id=pss.song_sheet_id')
            ->field('ss.*')->where('pss.player_id', $playerId)
            ->order('pss.taxis asc')
            ->select();
    }
}