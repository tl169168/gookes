<?php
// +----------------------------------------------------------------------
// | YzdlmCompressImage [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019 yzdlm All rights reserved.
// +----------------------------------------------------------------------
// | Author: yzdlm <1741509527@qq.com>
// +----------------------------------------------------------------------
namespace plugins\yzdlm_compress_image;
use cmf\lib\Plugin;
use app\portal\model\PortalPostModel;
class YzdlmCompressImagePlugin extends Plugin
{

    public $info = [
        'name'        => 'YzdlmCompressImage',
        'title'       => '压缩图片插件',
        'description' => '压缩图片插件',
        'status'      => 1,
        'author'      => '悟空',
        'version'     => '1.0',
        'demo_url'    => '',
        'author_url'  => ''
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


    public function userAdminAssetIndexView(){
        echo $this->fetch('index');
    }
	
	public function PortalAdminAfterSaveArticle($hookParam)
    {
        if(empty($hookParam['article']['more']['thumbnail'])){
            $data=[];
            $data['id']=$hookParam['article']['id'];
            $content = $hookParam['article']['post_content'];
            $pattern1 = "/ src=&quot;(.*)&quot;/iU";
            preg_match_all($pattern1,$content,$matchContent1);
            if(!empty($matchContent1[1][0])){
                $rurl = $matchContent1[1][0];
                $data['more']['thumbnail']=$rurl;
                $post=new PortalPostModel();
                $post->allowField(true)->isUpdate(true)->data($data, true)->save();
            }
        }
    }
}