<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 小夏 < 449134904@qq.com>
// +----------------------------------------------------------------------
namespace plugins\comment\validate;

use think\Validate;

class PostValidate extends Validate
{
    protected $rule = [
        // 用|分开
        //'verify' => 'require',
        'content' => 'require|max:5000'
    ];
    protected $message = [
        //'verify.require'       => "验证码不能为空！",
        'content.require' => '评论内容不能为空',
        'content.max' => '评论长度不能超过5000'
    ];


}