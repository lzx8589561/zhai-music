<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/9/1
 * Time: 10:43
 */

namespace app\admin\controller;


use app\common\controller\BackendBaseController;
use ilt\MusicApi;
use ilt\Random;
use think\Db;
use think\facade\Cache;
use think\facade\Session;

/**
 * 歌单管理控制器
 * Class SongSheet
 * @package app\admin\controller
 */
class SongSheet extends BackendBaseController
{

    protected function initialize()
    {
        parent::initialize();
        $this->model = model('SongSheet');

    }

    /**
     * 歌单详情
     * @param $id string 歌单id
     * @return mixed
     */
    public function index($id)
    {

        // 获取实例
        $this->getSide();
        $entity = $this->model->get($id);
        $this->assign('entity', $entity);

        // 获取歌单关联歌曲
        $songs = $entity->songs()->order('taxis asc')->select();
        $this->assign('songs', $songs);

        return $this->fetch();
    }

    /**
     * 编辑歌单
     */
    public function edit()
    {
        $songSheetId = $this->request->post('id');

        // 清除缓存
        $players = $this->model->songSheetPlayers($songSheetId);
        if(count($players) > 0){
            foreach ($players as $value){
                // 删除api缓存
                Cache::rm('info'.$value->player_id);
            }
        }

        $this->model->save([
            'type' => $this->request->post('type'),
            'sheet_id' => $this->request->post('sheet_id', ''),
            'name' => $this->request->post('name'),
            'author' => $this->request->post('author'),
            'create_time' => date("Y-m-d H:i:s"),
            'user_id' => Session::get('loginUser')['id']
        ], ['id' => $songSheetId]);

        $this->success('编辑歌单成功！');
    }

    /**
     * 添加歌单
     */
    public function add()
    {
        $this->model->save([
            'id' => Random::uuid(),
            'type' => 'sdtj',
            'name' => $this->request->post('name'),
            'author' => Session::get('loginUser')['username'],
            'create_time' => date("Y-m-d H:i:s"),
            'user_id' => Session::get('loginUser')['id']
        ]);

        $this->success('添加歌单成功！');
    }

    /**
     * 根据id搜索歌曲
     * @param $type string 来源
     * @param $songId string 歌曲类型
     */
    public function selSong($type, $songId)
    {
        $musicApi = new MusicApi();
        $song = [];
        switch ($type) {
            case 'wy':
                $song = $musicApi->mc_get_song_by_id($songId, 'netease');
                break;
            case  'kg':
                $song = $musicApi->mc_get_song_by_id($songId, 'kugou');
                break;
            case 'qq':
                $song = $musicApi->mc_get_song_by_id($songId, 'qq');
                break;
        }

        if ($song != '' && count($song) > 0) {
            $song = $song[0];
            $this->success('获取成功！', '', $song);
        } else {
            $this->error();
        }
    }

    /**
     * 搜索歌曲
     * @param $type string 歌曲类型
     * @param $songName string 歌曲名称
     * @param int $page int 页码
     */
    public function searchSong($type, $songName,$page = 1){
        $musicApi = new MusicApi();
        $songs = [];
        switch ($type) {
            case 'wy':
                $songs = $musicApi->mc_get_song_by_name($songName,'netease',$page);
                break;
            case  'kg':
                $songs = $musicApi->mc_get_song_by_name($songName,'kugou',$page);
                break;
            case 'qq':
                $songs = $musicApi->mc_get_song_by_name($songName,'qq',$page);
                break;
        }
        $this->success('获取成功！','',$songs);
    }

    /**
     * 保存歌单歌曲
     * @param $jsonData string json数据
     * @param $songSheetId string 歌单id
     * @throws \Exception
     */
    public function editSongSheetSong($jsonData, $songSheetId)
    {
        // 清除缓存
        $players = $this->model->songSheetPlayers($songSheetId);
        if(count($players) > 0){
            foreach ($players as $value){
                // 删除api缓存
                Cache::rm('info'.$value->player_id);
            }
        }

        // 删除播放器之前的歌曲
        $songModel = model('Song');
        $songModel->where('song_sheet_id', $songSheetId)->delete();

        // 重新保存歌曲列表
        $array = json_decode($jsonData, true);
        foreach ($array as $key => $value) {
            $value['song_sheet_id'] = $songSheetId;
            $value['id'] = Random::uuid();
            $array[$key] = $value;
        }
        $songModel->saveAll($array, false);

        $this->success('保存成功！');
    }

    /**
     * 删除歌单
     * @param $id string 歌单id
     * @throws \Exception
     */
    public function del($id){
        // 删除歌单关联项
        model('PlayerSongSheet')->where('song_sheet_id',$id)->delete();
        // 删除歌单
        $this->model->get($id)->delete();
        $this->success('删除成功！');
    }
}