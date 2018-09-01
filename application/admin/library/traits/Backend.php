<?php
/**
 * Created by PhpStorm.
 * User: Zing
 * Date: 2018/8/31
 * Time: 14:01
 */

namespace app\admin\library\traits;


trait Backend
{

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                } catch (\think\Exception $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error('Parameter %s can not be empty', '');
        }
        return $this->view->fetch();
    }
}