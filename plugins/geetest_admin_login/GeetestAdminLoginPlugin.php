<?php
// +----------------------------------------------------------------------
// | 极验验证插件管理控制器类
// +----------------------------------------------------------------------
// | Author: xiwang6428 <xiwang6428@sina.com>
// +----------------------------------------------------------------------
namespace plugins\geetest_admin_login;

use cmf\lib\Plugin;

class GeetestAdminLoginPlugin extends Plugin
{

    public $info = [
        'name'        => 'GeetestAdminLogin',
        'title'       => '极验验证后台登录页',
        'description' => '极验验证后台登录页',
        'status'      => 1,
        'author'      => '悟空',
        'version'     => '1.0'
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

    public function adminLogin()
    {
        return $this->fetch('widget');
    }

}