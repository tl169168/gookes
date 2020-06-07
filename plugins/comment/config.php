<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

return array(
    'anonymous' => array(
        'title' => '未登陆评论:',
        'type' => 'select',
        'options' => array(
            '1' => '允许',
            '0' => '不允许',
        ),
        'value' => '1',
    ),

    'verify' => array(
        'title' => '评论需要审核:',
        'type' => 'select',
        'options' => array(
            '0' => '需要',
            '1' => '不需要',
        ),
        'value' => '1',
    ),

    'captcha' => array(
        'title' => '验证码:',
        'type' => 'select',
        'options' => array(
            '1' => '开启',
            '0' => '关闭',
        ),
        'value' => '1',
    ),

    'time' => array(
        'title' => '重复评论间隔(分钟):',
        'type' => 'number',
        'value' => '0',
    )


);
