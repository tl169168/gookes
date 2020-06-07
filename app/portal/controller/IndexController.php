<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\portal\controller;

use app\mall\model\MallItemModel;
use app\mall\model\MallModelModel;
use app\portal\model\PortalPostModel;
use cmf\controller\HomeBaseController;
use Doctrine\Tests\Common\Cache\RedisCacheTest;
use think\Cookie;
use think\Db;
use think\Session;


class IndexController extends HomeBaseController
{
    function _initialize()
    {
        $dateStr = date('Ymd');
        Cookie::set('ystime',$dateStr);
        $yetime=Cookie::get('ystime');

        if($dateStr>$yetime){

            $db=Db::table('auto_mess_lo')->where('id',1)->update(['login1'=>0]);
        }
        $en=url('en/index/indexs',[]);

        $index=url('portal/index/index',[]);

        $about=url('portal/index/about',[]);
        $product=url('portal/index/product',[]);
        $wedo=url('portal/index/wedo',[]);
        $messages=url('portal/index/messages',[]);
        $chain=url('portal/index/chain',[]);
        $school=url('portal/index/school',[]);
        $zxly=url('portal/index/zxly',[]);

        $news=url('portal/index/news',[]);
        $faq=url('portal/index/faq',[]);
        $contact=url('portal/index/contact',[]);
        $this->assign('en', $en);
        $this->assign('zxly', $zxly);
        $this->assign('school', $school);
        $this->assign('chain', $chain);
        $this->assign('messages', $messages);
        $this->assign('about', $about);
        $this->assign('index', $index);
        $this->assign('wedo', $wedo);
        $this->assign('product', $product);
        $this->assign('news', $news);
        $this->assign('faq', $faq);
        $this->assign('contact', $contact);
        $this->_xss_check();
        $this->assign('waitSecond', 3);
        $time = time();
        $this->assign('js_debut', APP_DEBUG ? "?v=$time" : "");
        if (APP_DEBUG) {

        }
        //前后台菜单
        $bt = Db::name('nav_menu')->where('status', 1)->where('nav_id', 1)->where('parent_id', 0)->order('list_order')->select()->toArray();;

        for ($i = 0; $i < count($bt); $i++) {
            $bt[$i]['data'] = Db::name('nav_menu')->where('nav_id', 1)->where('parent_id', $bt[$i]['id'])->order('list_order')->select()->toArray();
        }
//        dump($bt);
        $btt = Db::name('nav_menu')->where('status', 1)->where('nav_id', 2)->where('parent_id', 0)->order('list_order')->select()->toArray();;
        for ($i = 0; $i < count($btt); $i++) {
            $btt[$i]['data'] = Db::name('nav_menu')->where('nav_id', 2)->where('parent_id', $btt[$i]['id'])->order('list_order')->select();

        }
//        dump($bt);

        $this->assign('btt', $btt);
        $this->assign('bt', $bt);
        $this->assign('site_info', cmf_get_option('site_info'));
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        //友情链接
        $yqlj=Db::table('auto_link')
            ->order('list_order')
            ->where('status',1)
            ->select();
        $this->assign('yqlj',$yqlj);
    }

    private function _xss_check()
    {
        $temp = strtoupper(urldecode(urldecode($_SERVER['REQUEST_URI'])));
        if (strpos($temp, '<') !== false || strpos($temp, '"') !== false || strpos($temp, 'CONTENT-TRANSFER-ENCODING') !== false) {
            die('您当前的访问请求当中含有非法字符,已经被系统拒绝');
        }
        return true;
    }

    //首页
    public function index()
    {
        //前台菜单
        $se = $this->request->param('se');
        Session::set('se', $se);
        $this->assign('se', $se);
//        echo $se;
        $kewordse = Db::name('portal_category')
            ->where('name', '首页幻灯片')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '首页幻灯片')->select();
        $hdp11 = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '首页图标')->select();
        $hdp1 = Db::table('auto_portal_category')
            ->where('parent_id',222)
            ->order('list_order  desc')
            ->select();

        $this->assign('hdp1', $hdp11);
        $this->assign('hdp', $hdp);

        //公司简介
//        dump($cp);
        $portalPostModel = new PortalPostModel();
        $post = $portalPostModel->where('id', 233)->find();
        $post['url']=url('portal/index/about',['id'=>85,'cid'=>269]);
        //联系我们
        //留言
        $liyyan=Db::name('comment')->where('status',1)->where('delete_time',0)->select();
        $this->assign('liyyan', $liyyan);
        //合作客户
        $gsxwh = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id',20)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.post_status', 1)
            ->select()->toArray();
        $this->assign('gsxwh', $gsxwh);
        //
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.create_time')
            ->paginate(8);
        $scp = json_decode(json_encode($gsxw),true);

//         dump($scp);exit;
        for ($c=0;$c<count($scp['data']);$c++){
//           dump($news['data'][$c]['title']);
            $scp['data'][$c]['url'] =url('portal/index/workshop',['id'=>$scp['data'][$c]['id']]);
        }
        $this->assign('scp', $scp['data']);
//公司新闻
        $gsxw4 = Db::table('auto_portal_category')
            ->where('parent_id',1)
             ->order('list_order')
            ->where('delete_time', 0)
            ->select()->toArray();

        for ($y=0;$y<count($gsxw4);$y++){
            $gsxw4[$y]['url']=url('portal/index/wedo',['id'=>$gsxw4[$y]['id']]);
        }
//        dump($gsxw4); return ;

         $jdcgl=url('portal/index/jdcg',['eid'=>87]);
//        dump($gsxw4); return ;
        $this->assign('jdcgl', $jdcgl);
        $this->assign('gsxw4', $gsxw4);
        //首页视频展示
        $gsxwr = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id',271)
             ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();

        for ($y=0;$y<count($gsxwr);$y++){
            $gsxwr[$y]['url']=url('portal/index/newsmore',['id'=>$gsxwr[$y]['post_id']]);
        }

        //来吧，展示
        $cjwt=Db::name('portal_category')->where('parent_id',1)->select()->toArray();
        for ($i=0;$i<count($cjwt);$i++){
            $cjwt[$i]['data'] = Db::table('auto_portal_category_post b,auto_portal_post c')
                ->where('b.post_id=c.id')
                ->order('b.list_order,c.create_time desc')
                ->where('b.category_id',$cjwt[$i]['id'])
                ->where('c.delete_time', 0)
                ->where('c.post_status', 1)
                ->select()->toArray();
            for ($y=0;$y<count($cjwt[$i]['data']);$y++){
                $cjwt[$i]['data'][$y]['url']=url('portal/index/allchain',['id'=>$cjwt[$i]['data'][$y]['post_id']]);
            }
        }

        $this->assign('cjwt', $cjwt);
//        dump($cjwt);

        $eid=$this->request->param('eid');
        $this->assign('eid', $eid);
        $this->assign('gsxwr', $gsxwr);
        $this->assign('post', $post);

        return $this->fetch(':index');
    }
    public function chain2()
    {
        //前台菜单
        $se = $this->request->param('se');
        Session::set('se', $se);
        $this->assign('se', $se);
//        echo $se;
        $kewordse = Db::name('portal_category')
            ->where('name', '首页幻灯片')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '首页幻灯片')->select();
        $hdp11 = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '合作客户')->select();
        $hdp1 = Db::table('auto_portal_category')
            ->where('parent_id',222)
            ->order('list_order  desc')
            ->select();

        $this->assign('hdp1', $hdp11);
        $this->assign('hdp', $hdp);

        //公司简介
//        dump($cp);
        $portalPostModel = new PortalPostModel();
        $post = $portalPostModel->where('id', 233)->find();
        $post['url']=url('portal/index/about',['id'=>85,'cid'=>269]);
        //联系我们

        //
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem')
            ->paginate(8);
        $scp = json_decode(json_encode($gsxw),true);

//         dump($scp);exit;
        for ($c=0;$c<count($scp['data']);$c++){
//           dump($news['data'][$c]['title']);
            $scp['data'][$c]['url'] =url('portal/index/productmore',['id'=>$scp['data'][$c]['id'],'cid'=>$scp['data'][$c]['uid']]);
        }
        $this->assign('scp', $scp['data']);
//公司新闻
        $gsxw4 = Db::table('auto_portal_category')
            ->where('parent_id',7)
            ->where('delete_time',0)
            ->select()->toArray();
        for ($y=0;$y<count($gsxw4);$y++){

            $gsxw4[$y]['url']=url('portal/index/chain3',['id'=>$gsxw4[$y]['id']]);
            $gsxw4[$y]['ids']=$gsxw4[$y]['id']-7;
        }
//        dump($gsxw4); return ;

        $jdcgl=url('portal/index/jdcg',['eid'=>87]);
//        dump($gsxw4); return ;
        $this->assign('jdcgl', $jdcgl);
        $this->assign('gsxw4', $gsxw4);
        //首页视频展示
        $gsxwr = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id',271)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();

        for ($y=0;$y<count($gsxwr);$y++){
            $gsxwr[$y]['url']=url('portal/index/newsmore',['id'=>$gsxwr[$y]['post_id']]);
        }
        //首页相册
        $gsxwwo = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id',270)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.recommended', 1)
            ->where('c.post_status', 1)
            ->select()->toArray();
        for ($m=0;$m<count($gsxwwo);$m++){
            $gsxwwo[$m]['url']=url('portal/index/about',['id'=>$gsxwwo[$m]['category_id'],'eid'=>$gsxwwo[$m]['parent_id']]);
        }
        $this->assign('gsxwwo', $gsxwwo);
        //常见问题
        $cjwt = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id',264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 0)
            ->where('c.post_status', 1)
            ->select();
        $this->assign('cjwt', $cjwt);
//        dump($cjwt);
        $cjwt1 = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('a.id',264)
            ->order('b.list_order,c.create_time desc')
            ->where('c.delete_time', 0)
            ->where('c.is_top', 1)
            ->where('c.post_status', 1)
            ->find();
        $this->assign('cjwt1', $cjwt1);

        $cpfenlei = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.parent_id',196)->select();
//        dump($cptj);

        $this->assign('cpfenlei', $cpfenlei);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $eid=$this->request->param('eid');
        $this->assign('eid', $eid);
        $this->assign('gsxwr', $gsxwr);
        $this->assign('post', $post);

        return $this->fetch(':chain2');
    }
    public function about()
    {
        $id=$this->request->param('id');
        $eid=$this->request->param('eid');
        if (empty($id)){
            $kewordse = Db::name('portal_category')
                ->where('name', '关于我们')->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }else{
            $kewordse = Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
                ->where('b.post_id=c.id')->where('a.id', 1)->find();
        }

        $se=Session::get('se');
        $this->assign('se',$se);
        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
            $feban=Db::table('auto_portal_category')->where('id',$id)->find();
            
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', $feban['name'])->find();
        if (empty($hdp)){
            $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '关于我们')->find();
        }
        //幻灯片
//        dump($hdp);
        //友情链接

        $this->assign('hdp', $hdp);
        //分类

        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id',14)->select()->toArray();
            for($i=0;$i<count($fenlei);$i++){
                $fenlei[$i]['url']=url('portal/index/about',['id'=>$fenlei[$i]['id'],'eid'=>$eid]);
            }


        //        dump($fenlei);
        $fenlei1 = Db::table('auto_portal_category')->where('id', $id)->find();
//        dump($fenlei);
        $this->assign('fenlei1', $fenlei1);
        $this->assign('fenlei', $fenlei);
        $page=$this->request->param('page',1);


        if (empty($id)&&$eid==85){
            $post=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                 ->order('b.list_order,c.create_time desc')
                ->where('a.id',269)
                ->find();
//            dump($post);
        }else if ($id==269) {
            $post=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                ->order('b.list_order,c.create_time desc')
                ->where('a.id',$id)
                ->find();
//            dump($post);

        }else{

            $post=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')
                ->where('c.delete_time', 0)
                ->limit($page)
                ->order('b.list_order,c.create_time desc')
                ->where('a.id',$id)
                ->paginate(8);

            $this->assign('page', $post->render());
            $news = json_decode(json_encode($post),true);

//             dump($post);exit;
            for ($c=0;$c<count($news['data']);$c++){
//           dump($news['data'][$c]['title']);
                $news['data'][$c]['url'] =url('portal/index/newsmore',['id'=>$news['data'][$c]['id']]);
            }
//            dump($news);
            $this->assign('news', $news['data']);

        }
//            dump($post);




        $this->assign('bid', $id);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $this->assign('post', $post);

        $this->assign('eid', $eid);
        $this->assign('id', $id);

            if ($id==269||empty($id)&&$eid=85){

                return $this->fetch(':about');

            } else if ($id == 270|| $id==281||$id == 272) {
                return $this->fetch(':about');
            }else{
                return $this->fetch(':about');
            }




    }
    public function school()
    {



        $cid = $this->request->param('id');
        $eid = $this->request->param('eid');
        $k = $this->request->param('k');

        if (!empty($k)){
            $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                ->where('b.title','like','%'.$k.'%')
                ->order('b.list_order')
                ->where('a.id=b.category_id')
                ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                ->select();


            $news = json_decode(json_encode($gsxw),true);

//             dump($post);exit;
            for ($c=0;$c<count($news);$c++){
//           dump($news['data'][$c]['title']);
                $news[$c]['url'] =url('portal/index/workshop',['id'=>$news[$c]['id']]);
            }
            $this->assign('news', $news);

        }
        $nameid= Db::name('mall_category')
            ->field('name')
            ->where('id',$cid)
            ->find();
//        dump($nameid);
        $kewordse = Db::name('portal_category')
            ->where('name', '果壳学院')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();

        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        //友情链接
        $yqlj=Db::table('auto_link')
            ->order('list_order')
            ->where('status',1)
            ->select();
        $this->assign('yqlj',$yqlj);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '果壳学院')->find();
//        dump($hdp);
        $this->assign('hdp', $hdp);
        $die = $this->request->param('die');

            $fenlei = Db::table('auto_mall_category')->where('delete_time', 0)->order('list_order')->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('portal/index/productmore',['id'=>$fenlei[$i]['id'],'eid'=>$eid]);
        }
//       dump($fenlei);
        $this->assign('fenlei', $fenlei);
//        dump($fenlei);
        $id = $this->request->param('id');
        $fenlei1 = Db::table('auto_mall_category')->where('delete_time',0)->select()->toArray();
        for ($i=0;$i<count($fenlei1);$i++){
            $fenlei1[$i]['data']= Db::table('auto_mall_item')->where('category_id',$fenlei1[$i]['id'])->where('delete_time',0)->select()->toArray();
            for ($y=0;$y<count($fenlei1[$i]['data']);$y++){
                $fenlei1[$i]['data'][$y]['url']=url('portal/index/workshop',['id'=>$fenlei1[$i]['data'][$y]['id']]);
            }
        }


        $this->assign('fenlei1', $fenlei1);

        $cp1 = Db::table('auto_mall_category')->where('delete_time', 0)->where('id', $cid)->find();
        $this->assign('cp1', $cp1);
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        $keyword = $this->request->param('keyword');
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $name = $this->request->param('name');
        $topces=Db::table('auto_mall_category')
            ->where('id',$id)
            ->find();
        $this->assign('topces', $topces);
        $page=$this->request->param('page',1);

//        dump($news);
        $xurl=url('portal/index/productmore',false,true);
        $se=Session::get('se');
        $this->assign('xurl',$xurl);
        $this->assign('eid',$eid);
        $this->assign('se',$se);
        $this->assign('name', $name);
        $this->assign('die', $die);
        $this->assign('id', $cid);

            if (!empty($k)){
                return $this->fetch(':schools');
            }else{
                return $this->fetch(':school');
            }

    }
    public function product1()
    {

        $cid = $this->request->param('id');
        $nameid= Db::name('mall_category')
            ->field('name')
            ->where('id',$cid)
            ->find();
//        dump($nameid);
        $kewordse = Db::name('portal_category')
            ->where('name', '产品中心')->find();
        $kewords = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.id', 1)->find();

        $this->assign('kewords', $kewords);
        $this->assign('kewordse', $kewordse);
        //幻灯片
        //友情链接
        $yqlj=Db::table('auto_link')
            ->order('list_order')
            ->where('status',1)
            ->select();
        $this->assign('yqlj',$yqlj);
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '产品中心')->find();
//        dump($hdp);
        $this->assign('hdp', $hdp);
        $die = $this->request->param('die');
            $uid=Db::table('auto_mall_category')->where('id', $cid)->find();
        $fenlei = Db::table('auto_mall_category')->where('delete_time', 0)->where('parent_id',$uid['parent_id'])->order('list_order')->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('en/index/product',['id'=>$fenlei[$i]['id']]);
        }
//       dump($fenlei);
        $this->assign('fenlei', $fenlei);
//        dump($fenlei);
        $id = $this->request->param('id');
        $fenlei1 = Db::table('auto_mall_category')->where('id', $id)->find();


        $this->assign('fenlei1', $fenlei1);

        $cp1 = Db::table('auto_mall_category')->where('delete_time', 0)->where('id', $cid)->find();
        $this->assign('cp1', $cp1);
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        $keyword = $this->request->param('keyword');
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.id','in',[114,115])
            ->paginate(9);
//        dump($fenlei);
        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $name = $this->request->param('name');
        $topces=Db::table('auto_mall_category')
            ->where('id',$id)
            ->find();
        $this->assign('topces', $topces);
        $page=$this->request->param('page',1);
        if (!empty($name)){
            $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                ->where('b.title','like','%'.$name.'%')
                ->order('b.list_order')
                ->where('a.id=b.category_id')
                ->limit($page)
                ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                ->paginate(9);
        }else{
            if ($cid=='en') {
                $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                    ->order('b.list_order')
                    ->where('a.id=b.category_id')
                    ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                    ->limit($page)
                    ->paginate(9);

            } else{
                $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
                    ->where('b.category_id',$cid)
                    ->order('b.list_order')
                    ->where('a.id=b.category_id')
                    ->limit($page)
                    ->field('b.title,b.thumbnail,b.id,a.id as uid,b.subtitle,b.subtitlem,b.titlem')
                    ->paginate(9);
            }
        }
        $this->assign('page', $gsxw->render());

        $news = json_decode(json_encode($gsxw),true);

        // print_r($nn);exit;
        for ($c=0;$c<count($news['data']);$c++){
//           dump($news['data'][$c]['title']);
            $news['data'][$c]['url'] =url('portal/index/productmore',['id'=>$news['data'][$c]['id'],'cid'=>$news['data'][$c]['uid']]);
        }
//        echo count($news);
//        dump($news['data']); return;
//        dump($gsxw);
        //产品详情页面
        $xurl=url('portal/index/productmore',false,true);
        $se=Session::get('se');
        $this->assign('xurl',$xurl);
        $this->assign('se',$se);
        $this->assign('name', $name);
        $this->assign('die', $die);
        $this->assign('id', $cid);

        $this->assign('gsxw', $news['data']);
        return $this->fetch(':product1');
    }
    public function workshop(){
        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);

        $kewordse=Db::name('portal_category')
            ->where('name', '新闻中心133')->find();
        $se=Session::get('se');

        $this->assign('se',$se);
        $id = $this->request->param('id');
        $cid = $this->request->param('cid');
        $kewords=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('c.id',$id)
            ->find();
//            dump($kewords);
        $this->assign('kewords',$kewords);
        $this->assign('kewordse',$kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '产品中心')->find();
//        dump($hdp);
        $this->assign('hdp', $hdp);
        $die = $this->request->param('cid');

        $uid=Db::table('auto_mall_category')->where('id', $cid)->find();
        $fenlei = Db::table('auto_mall_category')->where('delete_time', 0)->where('parent_id',$uid['parent_id'])->order('list_order')->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('portal/index/product',['id'=>$fenlei[$i]['id']]);
        }
//       dump($fenlei);
        $this->assign('fenlei', $fenlei);
//        dump($fenlei);
        $id = $this->request->param('id');
        $fenlei1 = Db::table('auto_mall_category')->where('id', $cid)->find();


        $this->assign('fenlei1', $fenlei1);

        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->where('b.id',$id)
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->find();
//        dump($gsxw);
        $gsxwt=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->order('b.list_order,c.create_time desc')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.parent_id',$id)->where('c.id','<',$id)
            ->find();
        $gsxwn=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->order('b.list_order,c.create_time desc')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('a.parent_id',$id)->where('c.id','>',$id)
            ->find();
        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
             ->order('b.list_order,c.create_time desc')
            ->where('a.parent_id',203)
            ->paginate(6);
        $gsxw = MallItemModel::get($id);
        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_comment')  ->order('create_time')->where('status',1)->where('object_id',$id)->select();
//        dump($lxwm);
        $this->assign('lxwm', $lxwm);
        $this->assign('gsxwt', $gsxwt);
        $this->assign('gsxwn', $gsxwn);
        $this->assign('id', $die);
        $this->assign('gsxw', $gsxw);

        return $this->fetch(':workshop');
    }
    public function wedo()
    {
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        $this->assign('bid', $id);

        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);
        if (empty($id)){
            $kewordse=Db::name('portal_category')
                ->where('name', '工业体系')->find();
            $kewords=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')  ->where('a.id', 1)->find();
        }else{
            $kewordse=Db::name('portal_category')
                ->where('id', $id)->find();
            $kewords=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
                ->where('b.post_id=c.id')  ->where('a.id', 1)->find();
        }
            $newsmore=url('',[]);
        $se=Session::get('se');
        $this->assign('newsmore',$newsmore);
        $this->assign('se',$se);
        $this->assign('kewords',$kewords);
        $this->assign('kewordse',$kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '工业体系')->find();
        $this->assign('hdp', $hdp);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id',260)->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('portal/index/news',['id'=>$fenlei[$i]['id']]);
        }
        $this->assign('fenlei', $fenlei);
        $page=$this->request->param('page',1);
        if (empty($id)){
            $fenlei1 = Db::table('auto_portal_category')->where('delete_time', 0)->where('id',2)->find();
        }else{
            $fenlei1 = Db::table('auto_portal_category')->where('delete_time', 0)->where('id',$id)->find();
        }

            if (empty($id)){
                $gsxw=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                    ->where('a.id=b.category_id')
                    ->where('b.post_id=c.id')
                    ->where('c.post_status',1)
                    ->order('c.is_top desc,c.recommended')
                    ->where('c.delete_time',0)
                    ->where('a.id',2)
                    ->field('c.thumbnails,a.id as cid,c.post_title,c.post_titlem,c.post_content,b.post_id,c.thumbnail,c.create_time,c.post_excerpt,c.post_excerptm')
                    ->limit($page)
                    ->paginate(6);

            }else{
                $gsxw=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
                    ->where('a.id=b.category_id')
                    ->where('b.post_id=c.id')
                    ->where('c.post_status',1)
                    ->order('c.is_top desc,c.recommended')
                    ->where('c.delete_time',0)
                    ->where('a.id',$id)
                    ->field('c.thumbnails,a.id as cid,c.post_title,c.post_titlem,c.post_content,b.post_id,c.thumbnail,c.create_time,c.post_excerpt,c.post_excerptm')
                    ->limit($page)
                    ->paginate(6);
            }


        $this->assign('page', $gsxw->render());

        $news = json_decode(json_encode($gsxw),true);

        // print_r($nn);exit;
//        dump($news); return ;
        for ($c=0;$c<count($news['data']);$c++){
//           dump($news['data'][$c]['title']);
            $news['data'][$c]['url'] =url('portal/index/allchain',['id'=>$news['data'][$c]['post_id']]);
        }

        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
             ->order('b.list_order,c.create_time desc')
            ->where('a.id','in',[114,115])
            ->select();
//        dump($fenlei);
        $this->assign('fenlei1', $fenlei1);
        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
//友情链接
        $yqlj=Db::table('auto_link')
            ->order('list_order')
            ->where('status',1)
            ->select();
        $this->assign('yqlj',$yqlj);
        $cptj = Db::table('auto_mall_category a,auto_mall_item b')
            ->field('b.id,b.thumbnail,b.title,b.titlem,a.namem,a.name')
            ->order('b.list_order')
            ->where('a.id=b.category_id')
            ->where('b.recommended',1)
            ->paginate(2);
        $this->assign('id', $id);
        $this->assign('eid', $eid);
        $this->assign('cptj', $cptj);

        $this->assign('gsxw', $news['data']);
        return $this->fetch(':wedo');
    }
    public function chain3()
    {
        //前台菜单

        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);

        $kewordse=Db::name('portal_category')
            ->where('name', '新闻中心133')->find();
        $se=Session::get('se');

        $this->assign('se',$se);
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        $kewords=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('c.id',$id)
            ->find();
//            dump($kewords);
        $this->assign('bid',$id);
        $this->assign('kewords',$kewords);
        $this->assign('kewordse',$kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '工业体系')->find();
        $this->assign('hdp', $hdp);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id',260)->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('portal/index/news',['id'=>$fenlei[$i]['id']]);
        }
        $this->assign('fenlei', $fenlei);
        $portalPostModel = new PortalPostModel();
        /*  $post            = $portalPostModel->where('id', $id)->find();*/
        $gsxw=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('c.delete_time',0)->where('a.id',$id)->order('c.id desc')
            ->find();

        $gs=Db::table('auto_portal_category_post')->where('post_id',$id)->find();

//        dump($gs);
        $gsxwt=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('b.category_id',$gs['category_id'])->order('c.id desc')->where('b.post_id','<',$id)
            ->find();
        if (!empty($gsxwt)){
            $gsxwt['url']=url('portal/index/allchain',['id'=>$gsxwt['post_id']]);
            $gsxwt['post_title']=$gsxwt['post_title'];
        }else{
            $gsxwt['url']="";
            $gsxwt['post_title']='没有了';
        }


        $gsxwn=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('b.category_id',$gs['category_id'])->order('c.id asc')->where('b.post_id','>',$id)
            ->find();
        if (!empty($gsxwn)){
            $gsxwn['url']=url('portal/index/allchain',['id'=>$gsxwn['post_id']]);
            $gsxwn['post_title']=$gsxwn['post_title'];
        }else{

            $gsxwn['url']="";
            $gsxwn['post_title']='没有了';
        }


        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.parent_id',203)
            ->paginate(6);

        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $this->assign('gsxwt', $gsxwt);
        $this->assign('gsxwn', $gsxwn);
        $this->assign('eid', $eid);
        $this->assign('id', $id);
        $this->assign('gsxw', $gsxw);

        return $this->fetch(':chain3');
    }
    public function allchain()
    {
        //前台菜单

        $erweima = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '底部二维码')->find();
        $this->assign('erweima', $erweima);

        $kewordse=Db::name('portal_category')
            ->where('name', '新闻中心133')->find();
        $se=Session::get('se');

        $this->assign('se',$se);
        $id = $this->request->param('id');
        $eid = $this->request->param('eid');
        $kewords=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('c.id',$id)
            ->find();
//            dump($kewords);
        $this->assign('bid',$id);
        $this->assign('kewords',$kewords);
        $this->assign('kewordse',$kewordse);
        //幻灯片
        $hdp = Db::table('auto_slide a,auto_slide_item b')->order('list_order')->where('a.id=b.slide_id')->where('a.name', '工业体系')->find();
        $this->assign('hdp', $hdp);
        $fenlei = Db::table('auto_portal_category')->where('delete_time', 0)->order('list_order')->where('parent_id',260)->select()->toArray();
        for($i=0;$i<count($fenlei);$i++){
            $fenlei[$i]['url']=url('portal/index/news',['id'=>$fenlei[$i]['id']]);
        }
        $this->assign('fenlei', $fenlei);
        $portalPostModel = new PortalPostModel();
        /*  $post            = $portalPostModel->where('id', $id)->find();*/
        $gsxw=$portalPostModel->where('id', $id)->find();

        $gs=Db::table('auto_portal_category_post')->where('post_id',$id)->find();

//        dump($gs);
        $gsxwt=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('b.category_id',$gs['category_id'])->order('c.id desc')->where('b.post_id','<',$id)
            ->find();
        if (!empty($gsxwt)){
            $gsxwt['url']=url('portal/index/allchain',['id'=>$gsxwt['post_id']]);
            $gsxwt['post_title']=$gsxwt['post_title'];
        }else{
            $gsxwt['url']="";
            $gsxwt['post_title']='没有了';
        }


        $gsxwn=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')->where('b.category_id',$gs['category_id'])->order('c.id asc')->where('b.post_id','>',$id)
            ->find();
        if (!empty($gsxwn)){
            $gsxwn['url']=url('portal/index/allchain',['id'=>$gsxwn['post_id']]);
            $gsxwn['post_title']=$gsxwn['post_title'];
        }else{

            $gsxwn['url']="";
            $gsxwn['post_title']='没有了';
        }


        $xinwen = Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')
            ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')
            ->where('c.recommended',1)
            ->where('c.delete_time', 0)
            ->order('b.list_order,c.create_time desc')
            ->where('a.parent_id',203)
            ->paginate(6);

        $this->assign('xinwen', $xinwen);
        $lxwm=Db::table('auto_portal_category a,auto_portal_category_post b,auto_portal_post c')  ->where('a.id=b.category_id')
            ->where('b.post_id=c.id')   ->order('b.list_order,c.create_time desc')->where('a.id',125)->select();
        $this->assign('lxwm', $lxwm);
        $this->assign('gsxwt', $gsxwt);
        $this->assign('gsxwn', $gsxwn);
        $this->assign('eid', $eid);
        $this->assign('id', $id);
        $this->assign('gsxw', $gsxw);

        return $this->fetch(':allchain');
    }
    public function add(){
        $data['content']=$this->request->param('content');
        if (empty($data['content'])){
            return $this->error('请填写内容');
        }
        $data['object_id']=$this->request->param('id');
        $data['url']=$this->request->param('url');
        $data['create_time']=time();
//        $data['status']=0;
        $str=Db::table('auto_comment')->insert($data);
        if ($str){
            return $this->success('评论成功');
        }else{
            return $this->error("网络错误,请稍后重试");
        }
    }
    public function pages(){
        $id=$this->request->param('id');
        $db=Db::table('auto_portal_post')->where('id',$id)->setInc('post_hits');
        if ($db){
            return $id;
        }else{
            return 0;
        }

    }


    public function pagese(){

        $db=Db::table('auto_mess_lo')->where('id',1)->setInc('login1');
        $db=Db::table('auto_mess_lo')->where('id',1)->setInc('login2');
    }
    public function pageses(){
        $db=Db::table('auto_mess_lo')->where('id',1)->upload(['login1'=>0]);
    }
    public function cppages(){
        $id=$this->request->param('id');
        $gsxw = Db::table('auto_mall_category a,auto_mall_item b')
            ->where('b.id',$id)
            ->setInc('b.view_count');
    }
}
