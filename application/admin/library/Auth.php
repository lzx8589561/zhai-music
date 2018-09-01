<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/31
 * Time: 10:41
 */

namespace app\admin\library;


use think\facade\Request;

class Auth
{

    /**
     * @var object 对象实例
     */
    protected static $instance;

    /**
     * 初始化
     * @access public
     * @param array $options 参数
     * @return Auth
     */
    public static function instance($options = [])
    {
        if (is_null(self::$instance))
        {
            self::$instance = new static($options);
        }

        return self::$instance;
    }

    public function match($arr = [])
    {
        $request = Request::instance();
        $arr = is_array($arr) ? $arr : explode(',', $arr);
        if (!$arr) {
            return FALSE;
        }

        $arr = array_map('strtolower', $arr);
        // 是否存在
        if (in_array(strtolower($request->action()), $arr) || in_array('*', $arr)) {
            return TRUE;
        }

        // 没找到匹配
        return FALSE;
    }

    /**
     * 检查当前是否登陆
     * @return bool 是否登陆
     */
    public function isLogin(){
        $loginUser = Request::session('loginUser');
        return $loginUser != null;
    }

}