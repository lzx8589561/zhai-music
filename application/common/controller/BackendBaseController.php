<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/30
 * Time: 10:05
 */

namespace app\common\controller;


use app\admin\library\Auth;
use app\admin\library\traits\Backend;
use think\Controller;
use think\facade\Session;

class BackendBaseController extends Controller
{
    protected $model = null;

    /**
     * 无需登录的方法
     * @var array
     */
    protected $noNeedLogin = [];

    // 引入代码块
    use Backend;

    protected function initialize()
    {
        $usr = $this->request->url();
        // 检查是否需要登陆
        if(!Auth::instance()->match($this->noNeedLogin)){
            // 检查是否登陆
            if(!Auth::instance()->isLogin()){
//                $url = Session::get('referer');
//                $url = $url ? $url : $this->request->url();
                $this->error('Please login first', '/admin/user/login');
            }
        }
    }

    /**
     * 获取侧边所需数据
     */
    protected function getSide(){
        // 获取用户拥有的播放器
        $playerModel = model('Player');
        $userPlayers = $playerModel->where('user_id',Session::get('loginUser')['id'])->select();

        // 获取用户所拥有的歌单
        $songSheetModel = model('SongSheet');
        $userSongSheets = $songSheetModel->where('user_id',Session::get('loginUser')['id'])->select();

        // 设置到session域中
        $this->assign('userPlayers',$userPlayers);
        $this->assign('userSongSheets',$userSongSheets);
    }

}