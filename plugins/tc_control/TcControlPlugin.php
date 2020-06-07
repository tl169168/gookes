<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\tc_control;
use cmf\lib\Plugin;

class TcControlPlugin extends Plugin
{

    public $info = [
        'name'        => 'TcControl',
        'title'       => '网站小神器',
        'description' => '网站小神器',
        'status'      => 1,
        'author'      => '悟空',
        'version'     => '1.0',
        'demo_url'    => 'http://www.songzhenjiang.cn',
        'author_url'  => 'http://www.songzhenjiang.cn'
    ];

    public $hasAdmin = 0;

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function footerStart($param)
    {
        $config = $this->getConfig();
        $this->assign($config);
        $this->assign($config);
        echo $this->fetch('widget');
    }

}