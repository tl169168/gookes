<?php 
// +----------------------------------------------------------------------
// | YzdlmCompressImage [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2019 yzdlm All rights reserved.
// +----------------------------------------------------------------------
// | Author: yzdlm <1741509527@qq.com>
// +----------------------------------------------------------------------
return [
    'key'          => [// 在后台插件配置表单中的键名 ,会是config[text]
        'title' => '压缩图片api秘钥', // 表单的label标题
        'type'  => 'text',// 表单的类型：text,password,textarea,checkbox,radio,select等
        'value' => '',// 表单的默认值
        'tip'   => '获取密钥地址https://tinypng.com/developers' //表单的帮助提示
    ],
];
