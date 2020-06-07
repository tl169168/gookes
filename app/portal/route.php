<?php
use think\Route;

Route::rule([
    'index'=>'portal/index/index',
    'about/:id'=>'portal/index/about',
    'product/:id'=>'portal/index/product',
    'news/:id'=>'portal/index/news',
    'faq/:id'=>'portal/index/faq',
    'contact/:id'=>'portal/index/contact',
    'feedback/:id'=>'portal/index/feedback',
    'productmore/:id/:cid'=>'portal/index/productmore',
    'newsmore/:id/:cid'=>'portal/index/newsmore',

],'','get|post');