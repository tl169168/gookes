<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
// | UploadViewUaf Plugin Author: Foxjun<jun777@qq.com>
// +----------------------------------------------------------------------
//
namespace plugins\upload_view_uaf\controller; 
use cmf\controller\PluginBaseController;
use think\Db;

class IndexController extends PluginBaseController
{

    function index()
    {   
        $where = array();

        $config = $this->getPlugin()->getConfig();

        //获取文件类型
        $filetype = request()->param('filetype');
        
        if($config['viewtype']==2){

            //获取上传设置
            $sconfig = cmf_get_upload_setting();
            //根据当前文件类型获取对应扩展名
            $type = $sconfig['file_types'][$filetype]['extensions'];


            $where['suffix'] = array('in',$type);

        }

    	$where['user_id'] = cmf_get_current_admin_id();
        $result = Db::name('asset')
            ->where($where)
            ->where('file_md5','NEQ','')
            ->order('create_time', 'DESC')
            ->paginate(12);
        $this->assign('assets', $result->items());
        $this->assign('page', $result->render());



        

        $this->assign('config',$config);
        $this->assign('filetype',$filetype);

        return $this->fetch("/index");
    }

}
