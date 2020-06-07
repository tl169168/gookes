<?php
// +----------------------------------------------------------------------
// | TcLinksubmit [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 Tangchao All rights reserved.
// +----------------------------------------------------------------------
// | Author: Tangchao <79300975@qq.com>
// +----------------------------------------------------------------------
namespace plugins\tc_linksubmit;
use cmf\lib\Plugin;
use think\Db;

class TcLinksubmitPlugin extends Plugin
{

    public $info = [
        'name'        => 'TcLinksubmit',
        'title'       => '百度链接自动提交',
        'description' => '百度链接自动提交',
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

    public function PortalAdminAfterSaveArticle($hookParam)
    {
        $config = $this->getConfig();
        $apl = explode(',', $hookParam['article']['categories']);
        $urls=[];
        $urls[]=cmf_url('portal/Article/index',array('id'=>$hookParam['article']['id']),'html',true);
        foreach ($apl as $vo) {
            $urls[]=cmf_url('portal/Article/index',array('id'=>$hookParam['article']['id'],'cid'=>$vo),'html',true);
        }
        $api = $config['linksubmit'];
        $ch = curl_init();
        $options =  array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
    }
}