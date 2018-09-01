<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/30
 * Time: 16:33
 */

namespace app\index\controller;


use app\common\controller\FrontendBaseController;

/**
 * 前端控制器首页
 * Class Index
 * @package app\index\controller
 */
class Index extends FrontendBaseController
{

    public function index(){
        $this->assign('title','宅音乐播放器');
        return $this->fetch();
    }
}