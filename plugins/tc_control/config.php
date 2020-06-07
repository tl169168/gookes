<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
return [
    'isbgcolor'    => [
        'title'   => '网站背景色',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'bgcolor'     => [
        'title' => '网站背景色颜色',
        'type'  => 'color',
        'value' => '#103633',
        'tip'   => '全站强制添加背景颜色'
    ],
    'rightclick'    => [
        'title'   => '禁止右键',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0',
        'tip'   => '全站强制禁止右键'
    ],
    'saveimg'    => [
        'title'   => '禁止另存图片',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0',
        'tip'   => '全站强制禁止右键'
    ],
    'saveisimg'    => [
        'title'   => '禁止第三方识别图片',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'noselect'    => [
        'title'   => '禁止复制',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'nocopy'    => [
        'title'   => '禁止工具复制',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'nof5'    => [
        'title'   => '禁用F5键',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'nof12'    => [
        'title'   => '禁用F12键',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'noiframe'    => [
        'title'   => '禁止被框架引用',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'isgray'    => [
        'title'   => '全站变灰',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'newopen'    => [
        'title'   => '强制链接新窗口打开',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'closeweb'    => [
        'title'   => '临时关闭网站',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0',
        'tip'   => '不影响网站内容和搜索引擎收录'
    ],
    'closewebtext'     => [
        'title' => '关站说明',
        'type'  => 'text',
        'value' => '网站正在维护，请稍后再访问……'
    ],
    'notice'    => [
        'title'   => '启用网站定时通知',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
    'noticest'     => [
        'title' => '开启时间',
        'type'  => 'datetime',
        'value' => '2017-05-20'
    ],
    'noticeed'     => [
        'title' => '关闭时间',
        'type'  => 'datetime',
        'value' => '2017-05-20'
    ],
    'noticetext' => [
        'title' => '网站定时通知内容',
        'type'  => 'textarea',
        'value' => '欢迎光临本站！'
    ],
    'closemsg' => [
        'title' => '网页关闭提示信息',
        'type'  => 'textarea',
        'value' => ''
    ],
    'tipshow'    => [
        'title'   => '触发禁用项提示',
        'type'    => 'radio',
        'options' => [
            '1' => '开启',
            '0' => '关闭'
        ],
        'value'   => '0'
    ],
];
					