<?php
use think\Route;



Route::rule([
    'cn'=>'portal/index/indexs',

    'indexs'=>'en/index/index',
    'abouts/:eid'=>'en/index/about',
    'products/:id'=>'en/index/product',
    'newss/:eid'=>'en/index/news',
    'faqs/:eid'=>'en/index/faq',
    'messagess/:eid'=>'en/index/messages',
    'contacts/:eid'=>'en/index/contact',
    'feedbacks/:eid'=>'en/index/feedback',
    'productmores/:eid/:id'=>'en/index/productmore',
    'productmores/:eid'=>'en/index/productmore',
    'newsmores/:id'=>'en/index/newsmore',

    'about1s/:eid'=>'en/index/about',

    'contacts/:eid'=>'en/index/contact',


    'jdcgs/:eid'=>'en/index/jdcg',
    'jdcgs/:eid/:id'=>'en/index/jdcg',
    'zhaopin1s/:eid'=>'en/index/zhaopin',
    'zhaopins/:eid/:id'=>'en/index/zhaopin',
    'zxlys'=>'en/index/zxly',

],'','get|post');