<?php
/**
 * Created by PhpStorm.
 * User: 张林
 * Date: 2019/4/26
 * Time: 11:33
 */

namespace app\portal\controller;


use cmf\controller\PluginBaseController;
use think\Db;

class FilesController extends PluginBaseController
{

    public function imgs(){
        $list = Db::name('asset')->paginate(8);
//        halt($list->items());
        $this->assign('list', $list->items());
        $this->assign('page', $list->render());//单独提取分页出来
        return $this->fetch();
    }
}