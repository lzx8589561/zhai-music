<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/18
 * Time: 14:28
 */

namespace app\admin\controller;


use app\common\controller\BackendBaseController;
use ilt\Random;
use think\facade\Session;

/**
 * 用户管理控制器
 * Class User
 * @package app\admin\controller
 */
class User extends BackendBaseController
{
    protected $noNeedLogin = ['login','register'];

    // 初始化
    protected function initialize()
    {
        parent::initialize();
        $this->model = model("User");
    }


    /**
     * 登陆跳转以及验证
     * @return mixed
     */
    public function login()
    {
        // 判断是否为ajax
        if ($this->request->isPost()) {
            $username = $this->request->post('username');
            $password = md5($this->request->post('password'));
            $user = $this->model->where(['username' => $username, 'password' => $password])->find();
            if ($user == null) {
                $this->error('用户名或密码有误！');
            } else {
                Session::set('loginUser', $user->toArray());
                // 更新登陆信息
                $user->last_login_time = date("Y-m-d H:i:s");
                $user->save();
                $this->success('登陆成功！');
            }
        }

        // 页面跳转
        return $this->fetch();
    }

    /**
     * 用户注册
     */
    public function register()
    {
        $username = $this->request->post('username');
        $password = md5($this->request->post('password'));
        $qq = $this->request->post('qq');

        // 持久化
        $this->model->save([
            'id' => Random::uuid(),
            'username' => $username,
            'password' => $password,
            'qq' => $qq,
            "create_time" => date("Y-m-d H:i:s"),
            'create_ip' => $this->request->ip()
        ]);
        $this->success('注册成功！');
    }

    /**
     * 退出登陆
     */
    public function logout()
    {
        Session::clear();
        $this->success();
    }

}