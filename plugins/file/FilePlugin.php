<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\File;//Demo插件英文名，改成你的插件英文就行了
use cmf\lib\Plugin;

/**
 * Demo
 */
class FilePlugin extends Plugin{//Demo插件英文名，改成你的插件英文就行了



    public $info = [
        'name'        => 'File',//Demo插件英文名，改成你的插件英文就行了
        'title'       => '文件管理',
        'description' => '文件管理,使用插件时，把不要把url模式设置为普通',
        'status'      => 1,
        'author'      => '悟空',
        'version'     => '1.1',
        'demo_url'    => 'http://demo.thinkcmf.com',
        'author_url'  => 'http://www.thinkcmf.com'
    ];
        

        public $hasAdmin = 1;//插件是否有后台管理界面

        public function install(){//安装方法必须实现
         
            return true;//安装成功返回true，失败false
        }

        public function uninstall(){//卸载方法必须实现
        
            return true;//卸载成功返回true，失败false
        }
        

}