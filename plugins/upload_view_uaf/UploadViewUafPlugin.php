<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\upload_view_uaf;
use cmf\lib\Plugin;
use \think\Request;
use cmf\lib\Upload;
use think\View;
use think\Db;

class UploadViewUafPlugin extends Plugin
{

    public $info = [
        'name'        => 'UploadViewUaf',
        'title'       => '上传视图文件管理--本地文件版',
        'description' => '本地上传视图中增加用户本地现有资源文件的选择',
        'status'      => 1,
        'author'      => '悟空',
        'version'     => '1.1.1',
        'demo_url'    => '',
        'author_url'  => ''
    ];

    public $hasAdmin = 0;//插件是否有后台管理界面

    // 插件安装
    public function install()
    {
        return true;//安装成功返回true，失败false
    }

    // 插件卸载
    public function uninstall()
    {
        return true;//卸载成功返回true，失败false
    }

    //实现的footer_start钩子方法
    public function fetchUploadView($param)
    {
        
        $filetype = request()->param('filetype');

        $url = cmf_plugin_url('UploadViewUaf://Index/index',['filetype'=>$filetype]);
        $this->assign('ifurl',$url);

        echo <<<EOT
            <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
            <script>
            $(function(){
               $(".nav-tabs").append('<li class=""><a href="#files" data-toggle="tab" onclick="showlocahfiles();">本地文件</a></li>'); 

               $(".tab-content").append('<div class="tab-pane" id="files"><div class="bk3"></div><input type="text" id="fileinfo" name="info[filename]" class="input form-control" value="" style="display: none;"></div>');});

              
               function showlocahfiles(){
                if($("#filesiframe").length<= 0)
                   {
                    $("#files").append('<iframe src="$url" style="width: 100%; min-height: 320px;" frameborder="0" scrolling="no" id="filesiframe" class=""></iframe>');
                    }
               }
               
            </script>
EOT;
        
    }

   


}