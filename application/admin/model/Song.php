<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/30
 * Time: 15:18
 */

namespace app\admin\model;


use ilt\MusicApi;
use think\Model;

/**
 * 歌曲模型
 * Class Song
 * @package app\admin\model
 */
class Song extends Model
{

    /**
     * 获取歌曲详情
     * @param $type
     * @param $songId
     * @return array|string
     */
    public function findMusicInfo($type,$songId){
        $musicApi = new MusicApi();
        $songs = [];
        switch ($type) {
            case 'wy':
                $songs = $musicApi->mc_get_song_by_id($songId, 'netease');
                break;
            case  'kg':
                $songs = $musicApi->mc_get_song_by_id($songId, 'kugou');
                break;
            case 'qq':
                $songs = $musicApi->mc_get_song_by_id($songId, 'qq');
                break;
        }
        return $songs;
    }
}