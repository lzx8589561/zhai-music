<?php
namespace app\admin\controller;

use app\common\controller\BackendBaseController;

class Index extends BackendBaseController
{
    public function index()
    {
        $this->getSide();
        return $this->fetch();
    }
}
