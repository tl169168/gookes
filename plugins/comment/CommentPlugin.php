<?php

namespace plugins\comment;
//Demo插件英文名，改成你的插件英文就行了
use cmf\lib\Plugin;
use plugins\comment\model\PluginCommentModel;

class CommentPlugin extends Plugin
{

    public $info = [
        'name' => 'Comment',
        'title' => '系统评论插件',
        'description' => '系统评论插件,利用系统自带的评论表，基本功能都有',
        'status' => 1,
        'author' => ' MTJO',
        'version' => '1.0.1',
        'demo_url' => 'http://mtjo.net'
    ];
    public $hasAdmin = 1;//插件是否有后台管理界面

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的comment钩子方法
    public function comment($param)
    {
        $config = $this->getConfig();
        $this->assign($config);
        $model = new PluginCommentModel();

        $where = ['object_id' => $param['object_id'],'status'=>1, 'delete_time' => 0];

        $datas = $model->with("touser,parent")
            ->order('id desc')->where($where)->paginate();
        $this->assign("datas", $datas);
        $this->assign('page', $datas->render());

        $this->assign($param);

        return $this->fetch('widget');
    }
}