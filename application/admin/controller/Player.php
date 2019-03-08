<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/30
 * Time: 17:12
 */

namespace app\admin\controller;


use app\common\controller\BackendBaseController;
use ilt\Random;
use think\Db;
use think\facade\Cache;
use think\facade\Session;

/**
 * 播放器管理控制器
 * Class Player
 * @package app\admin\controller
 */
class Player extends BackendBaseController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = model('Player');
    }

    /**
     * 添加播放器
     */
    public function add()
    {
        $this->model->save([
            'id'=>Random::uuid(),
            'user_id'=> Session::get('loginUser')['id'],
            'auto_player'=> 1,
            'random_player'=> 1,
            'name'=> $this->request->post('name'),
            'site_name'=> 'IT技术宅',
            'greeting'=> '欢迎光临寒舍！',
            'show_greeting'=> 1,
            'background'=> 1,
            'default_volume'=> 75,
            'default_album'=> 0,
            'show_lrc'=> 1]);
        $this->success('添加播放器成功！');
    }

    /**
     * 删除播放器
     * @param $id string 播放器id
     * @throws \Exception
     */
    public function del($id)
    {
        $this->checkPlayerRole($id);
        // 删除歌单关联项
        model('PlayerSongSheet')->where('player_id',$id)->delete();
        // 删除播放器
        $this->model->get($id)->delete();
        $this->success('删除成功！');
    }

    /**
     * 获取播放器
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index($id)
    {
        $this->checkPlayerRole($id);
        $this->getSide();

        $entity = $this->model->get($id);
        $this->assign('entity', $entity);

        // 获取播放器歌单
        $list = $this->model->songSheets($id);
        $this->assign('selectedSongSheetList', $list);

        // 获取用户的所有歌单
        $userSongSheet = Db::table('song_sheet')
            ->where('user_id', $entity->user_id)
            ->select();
        $this->assign('userSongSheet', $userSongSheet);

        return $this->fetch();
    }

    /**
     * 用户编辑歌单
     * @param $playerId string|int 播放器id
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function editPlayerSongSheet($playerId){
        $this->checkPlayerRole($playerId);
        // 删除api缓存
        Cache::rm('info'.$playerId);
        $ids = $this->request->post('ids/a');

        // 删除之前的关联
        Db::table('player_song_sheet')->where('player_id',$playerId)->delete();

        if($ids != null){
            $joins = [];
            for($i = 0;$i < count($ids);$i++){
                $joins[$i] = ['player_id' => $playerId,'song_sheet_id' => $ids[$i],'taxis'=>$i];
            }

            //插入编辑之后的数据
            Db::table('player_song_sheet')->insertAll($joins);
        }

        $this->success('保存成功！');
    }

    /**
     * 更新播放器
     */
    public function edit()
    {
       $this->checkPlayerRole($this->request->post('id'));
        // 删除api缓存
        Cache::rm('info'.$this->request->post('id'));

        $this->model->save([
            'auto_player'=> $this->request->post('auto_player',0),
            'random_player'=> $this->request->post('random_player',0),
            'name'=> $this->request->post('name',''),
            'site_name'=> $this->request->post('site_name',''),
            'greeting'=> $this->request->post('greeting',''),
            'show_greeting'=> $this->request->post('show_greeting',0),
            'background'=> $this->request->post('background',0),
            'default_volume'=> $this->request->post('default_volume',100),
            'default_album'=> $this->request->post('default_album',0),
            'show_lrc'=> $this->request->post('show_lrc',0)],
            ['id'=> $this->request->post('id')]);
        $this->success('编辑成功！');
    }

}