<?php
use think\Route;

Route::rule([
    'indexs'=>'en/index/index1',

    'index/:eid'=>'portal/index/index',
    'index'=>'portal/index/index',
    'about/:eid/:id'=>'portal/index/about',
    'about/:eid'=>'portal/index/about',
    'about'=>'portal/index/about',

    'about1/:eid'=>'portal/index/about',
    'productmore/:eid/:id/:cid'=>'portal/index/productmore',
    'workshop/:id'=>'portal/index/workshop',
    'productmore/:eid'=>'portal/index/productmore',
    'product/:eid/:id'=>'portal/index/product',
    'product/:eid'=>'portal/index/product',

    'product'=>'portal/index/product',

    'news/:eid/:id'=>'portal/index/news',
    'news/:eid'=>'portal/index/news',

    'news/:name'=>'portal/index/news',
    'news'=>'portal/index/news',

    'faq/:eid'=>'portal/index/faq',
    'message'=>'portal/index/message',
    'support/:eid/:id'=>'portal/index/support',
    'support/:eid'=>'portal/index/support',
    'wedo/:id'=>'portal/index/wedo',
    'wedo'=>'portal/index/wedo',
    'chain'=>'portal/index/chain',
    'chain2'=>'portal/index/chain2',
    'school'=>'portal/index/school',
    'school/:k'=>'portal/index/school',
    'chain3/:id'=>'portal/index/chain3',
    'school/:id'=>'portal/index/school',

    'solution/:eid/:id'=>'portal/index/solution',
    'solution/:eid'=>'portal/index/solution',

    'solution'=>'portal/index/solution',
    'down/:eid/:id'=>'portal/index/down',
    'down/:eid'=>'portal/index/down',
    'yyfa/:eid/:id'=>'portal/index/yyfa',
    'yyfa/:eid'=>'portal/index/yyfa',


    'yyfa'=>'portal/index/yyfa',

    'yyfamore/:eid/:id/:cid'=>'portal/index/yyfamore',
    'yyfamore'=>'portal/index/yyfamore',

    'solutionshow/:eid/:id'=>'portal/index/solutionshow',
    'solutionshow/:eid'=>'portal/index/solutionshow',

    'partners/:id'=>'portal/index/partners',
    'feedback/:eid'=>'portal/index/feedback',
    'partners'=>'portal/index/partners',
    'technology/:id'=>'portal/index/technology',
    'technology'=>'portal/index/technology',


    'contact/eid:/:id'=>'portal/index/contact',
    'contact/:eid'=>'portal/index/contact',

    'contact'=>'portal/index/contact',
    'technologymore/:id'=>'portal/index/technologymore',
    'technologymore'=>'portal/index/technologymore',


    'messages'=>'portal/index/messages',

    'product/:id'=>'portal/index/product',
    'newsmore/:id'=>'portal/index/newsmore',
    'allchain/:id'=>'portal/index/allchain',
    'jdcgl1/:eid'=>'portal/index/jdcg',
    'jdcg/:eid/:id'=>'portal/index/jdcg',
    'career/:eid'=>'portal/index/career',
    'join/'=>'portal/index/join',
    'zxly'=>'portal/index/zxly',

],'','get|post');