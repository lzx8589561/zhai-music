<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/9/2
 * Time: 8:47
 */

namespace app\api\controller;


use app\admin\model\SongSheet;
use ilt\ImageUtils;
use think\Controller;
use think\facade\Cache;

/**
 * api获取控制器
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{

    /**
     * 获取播放器基础信息以及歌单
     * @param $id string 播放器歌单
     * @return string script
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function info($id){
        // 读取缓存
        $cache = Cache::get('info'.$id);
        if($cache){
            return response($cache);
        }
        $result = '';
        $playerModel = model('admin/Player');
        // 获取播放器详情
        $player = $playerModel->get($id);
        $result .= "var playerName = '".$player->name."',";
        $result .= "autoPlayer = ".$player->auto_player.",";
        $result .= "randomPlayer = ".$player->random_player.",";
        $result .= "defaultVolume = ".$player->default_volume.",";
        $result .= "showLrc = ".$player->show_lrc.",";
        $result .= "greeting = '".$player->greeting."',";
        $result .= "showGreeting = ".$player->show_greeting.",";
        $result .= "defaultAlbum = ".$player->default_album.",";
        $result .= "siteName = '".$player->site_name."',";
        $result .= "background = ".$player->background.",";
        $result .= "playerWidth = ".$player->player_width.",";
        $result .= "coverWidth = ".$player->cover_width.",";
        $result .= "showNotes = ".$player->show_notes.",";
        $result .= "autoPopupPlayer = ".$player->auto_popup_player.";";
        // 获取播放器歌单
        $playerSongSheets = $playerModel->songSheets($id);
        // 获取歌单歌曲
        $songSheetList = [];
        foreach ($playerSongSheets as $key => $item){
            $songSheetModel = new SongSheet($item);
            $songs = $songSheetModel->songs()->order('taxis asc')->select();
            $songIds = [];$songNames = [];$songTypes = [];$albumNames = [];
            $artistNames = [];$albumCovers = [];$locations = [];
            foreach ($songs->toArray() as $key2 => $item2){
                $songIds[$key2] = $item2['song_id'];
                $songNames[$key2] = $item2['name'];
                $songTypes[$key2] = $item2['type'];
                $albumNames[$key2] = $item2['album_name'];
                $artistNames[$key2] = $item2['artist_name'];
                $albumCovers[$key2] = $item2['album_cover'];
                $locations[$key2] = $item2['location'];
            }
            $songSheetList[$key] = [
                'songSheetName' => $item['name'],
                'author' => $item['author'],
                'songIds' => $songIds,
                'songNames' => $songNames,
                'songTypes' => $songTypes,
                'albumNames' => $albumNames,
                'artistNames' => $artistNames,
                'albumCovers' => $albumCovers
            ];
        }

        $result .= "var songSheetList = ".json_encode($songSheetList);
        // 设置缓存
        Cache::set('info'.$id,$result);
        return response($result);
    }


    /**
     * 获取图片主色及字体颜色
     * @param $url string 图片链接
     * @return string script
     */
    public function mainColor($url){
        $cache = Cache::get('mainColor'.$url);
        if($cache){
            return response($cache);
        }
        $result = "var mainColor =";
        if($url != null && $url != ''){
            list($r,$g,$b) = ImageUtils::mainColor($url);
            $result .= "'".$r.",".$g.",".$b."'";
            $grayLevel = $r * 0.299 + $g * 0.587 + $b * 0.114;
            if ($grayLevel >= 150) {
                $result .= ";font_color='0,0,0';";
            }else{
                $result .= ";font_color='255,255,255';";
            }
        }else{
            $result .= "'0,0,0';font_color='255,255,255';";
        }

        // 设置缓存
        Cache::set('mainColor'.$url,$result);
        return response($result);
    }

    /**
     * 获取歌曲
     * @param $type string 歌曲类型
     * @param $songId string 歌曲id
     * @return \think\response\Redirect
     */
    public function musicUrl($type,$songId){
        // 读取缓存
        $cache = Cache::get('music'.$type.$songId);
        if(!$cache){
            $songs = model('admin/Song')->findMusicInfo($type,$songId);
            if ($songs != '' && count($songs) > 0) {
                $song = $songs[0];
                // 设置缓存
                Cache::set('music'.$type.$songId,$song['url'],60*20);
                return redirect($song['url']);
            } else {
                $this->error();
            }
        }else{
            return redirect($cache);
        }

    }

    /**
     * 获取歌词
     * @param $type string 歌曲类型
     * @param $songId string 歌曲id
     * @return \think\Response
     */
    public function musicLyric($type,$songId){
        // 读取缓存
        $cache = Cache::get('musicLyric'.$type.$songId);
        $script = "var lrcstr =''";
        if(!$cache){
            $songs = model('admin/Song')->findMusicInfo($type,$songId);
            if ($songs != '' && count($songs) > 0) {
                $song = $songs[0];
                $lryic = str_replace("\r\n",'',$song['lrc']);
                $lryic = str_replace("\n",'',$lryic);
                $lryic = str_replace("'","\'",$lryic);
                $script = "var lrcstr ='".$lryic."'";
            }
            // 设置缓存
            Cache::set('musicLyric'.$type.$songId,$script);
        }
        return response($cache ? $cache : $script);
    }
}