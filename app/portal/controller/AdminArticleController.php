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

use cmf\controller\AdminBaseController;
use app\portal\model\PortalPostModel;
use app\portal\service\PostService;
use app\portal\model\PortalCategoryModel;
use think\Db;
use app\admin\model\ThemeModel;
use think\Session;

class AdminArticleController extends AdminBaseController
{
    /**
     * 文章列表
     * @adminMenu(
     *     'name'   => '文章管理',
     *     'parent' => 'portal/AdminIndex/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章列表',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $param = $this->request->param();

        $categoryId = $this->request->param('category', 0, 'intval');

        $postService = new PostService();
        $data        = $postService->adminArticleList($param);

        $data->appends($param);
//        dump($data);
        $portalCategoryModel = new PortalCategoryModel();
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId);

        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');
        $this->assign('articles', $data->items());
        $this->assign('category_tree', $categoryTree);
        $this->assign('category', $categoryId);
        $this->assign('page', $data->render());


        return $this->fetch();
    }

    //招聘管理
    public function zhaopin1(){
        $param = $this->request->param();

        $categoryId = $this->request->param('category', 0, 'intval');

        $postService = new PostService();
        $data        =  Db::table('auto_portal_category_post b,auto_portal_post c')
            ->where('b.post_id=c.id')
            ->where('c.delete_time', 0)
            ->order('c.create_time desc')
            ->where('b.category_id',235)
            ->paginate(20);

//        $data->appends($param);
//        dump($data);
        $portalCategoryModel = new PortalCategoryModel();
        $categoryTree        = $portalCategoryModel->adminCategoryTree($categoryId);

        $this->assign('start_time', isset($param['start_time']) ? $param['start_time'] : '');
        $this->assign('end_time', isset($param['end_time']) ? $param['end_time'] : '');
        $this->assign('keyword', isset($param['keyword']) ? $param['keyword'] : '');
        $this->assign('articles', $data);
        $this->assign('category_tree', $categoryTree);
        $this->assign('category', $categoryId);
        $this->assign('page', $data->render());
        return $this->fetch();
    }

    /**
     * 添加文章
     * @adminMenu(
     *     'name'   => '添加文章',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章',
     *     'param'  => ''
     * )
     */
    public function add()
    {
        $content = hook_one('portal_admin_article_add_view');

        if (!empty($content)) {
            return $content;
        }

        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        return $this->fetch();
    }

    //招聘管理
    public function zhaopinadd(){
        $content = hook_one('portal_admin_article_add_view');

        if (!empty($content)) {
            return $content;
        }
        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        return $this->fetch();
    }
    /**
     * 添加文章提交
     * @adminMenu(
     *     'name'   => '添加文章提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '添加文章提交',
     *     'param'  => ''
     * )
     */
    public function uploadMultiImage(){
        $id=$this->request->param('id');

        if (!empty($content)) {
            return $content;
        }
        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $content = hook_one('portal_admin_article_add_view');

        if (!empty($content)) {
            return $content;
        }

        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
        $this->assign('article_theme_files', $articleThemeFiles);

        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $this->assign('id',$id);
        return $this->fetch();
    }
    public function uploadMultiImages(){
        $files = request()->file('image');
        if(empty($files)){
            return $this->error('请选择图片');

        }
        $datae['post_title']=$this->request->param('title');
        $datae['post_titlem']=$this->request->param('post_titleen');
        $varen=explode(",",$datae['post_titlem']);
        $var=explode(",",$datae['post_title']);
        if (count($var)!=count($files)||count($varen)!=count($files)){
            $this->error('图片和标题数量不对！');
            return;
        }
//        dump($files); return;
        $id=$this->request->param('idces');
        /* if (empty($id)){
           $this->error("分类不能为空");
         }*/
        Session::set('idces',$id);
//        dump($id);
//        return $id;
//        dump($files);
        $data['parent_id']=0;
        $data['post_type']=1;
        $data['post_format']=1;
        $data['user_id']=1;
        $data['post_status']=1;
        $data['comment_status']=1;
        $data['is_top']=0;
        $data['recommended']=0;
        $data['post_hits']=0;
        $data['post_favorites']=0;
        $data['post_like']=0;
        $data['comment_count']=0;
        $data['create_time']=time();
        $data['update_time']=time();
        $data['published_time']=time();
        $data['post_keywords']=$this->request->param('post_keywords');
        $data['post_keywordsm']=$this->request->param('post_keywordsen');
        /*  $data['post_description']=$this->request->param('post_description');
          $data['post_descriptionm']=$this->request->param('post_descriptionen');*/
        $data['post_source']=$this->request->param('post_source');
        $data['post_sourcem']=$this->request->param('post_sourceen');
        $data['post_excerpt']=$this->request->param('post_excerpt');
        $data['post_excerptm']=$this->request->param('post_excerpten');
        $data['post_content']=$this->request->param('post_content');
        $data['post_contentm']=$this->request->param('post_contenten');
        $data['post_source']=0;
        $data['delete_time']=0;

        $datae['post_usid']=Session::get('ADMIN_ID');

        /*  dump($var);
          dump($varen);
          dump($files);*/

        foreach($files as  $key => $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'upload' . DS . 'user');

            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
//                $imgname=$info->getSaveName();
                $getSaveName=str_replace("\\","/",$info->getSaveName());//把反斜杠(\)替换成斜杠(/) 因为在windows下上传路是反斜杠径20180914\a6587063b2abbda94058120dbf1c7dad.png
                $getSaveNames=str_replace("\\","\/",$info->getSaveName());//把反斜杠(\)替换成斜杠(/) 因为在windows下上传路是反斜杠径20180914\a6587063b2abbda94058120dbf1c7dad.png
//                return $imgname;  {"video":"","thumbnail":"user\/20190627\/86a36d351bab0fdb0348c1a5e68db0a2.jpg"}
                //                  {"video":"","thumbnail":"user\/20190702\/edd2e101355eca0a638075dd4cb5c69c.jpg"}
                $data['thumbnail']='user/'.$getSaveName;
//                dump(json($data['thumbnail']));
//                return ;  {"video":"","thumbnail":"user\/20190627\/7c716e8cbbc0b88517335910db180ee3.jpg"}

                $data['more']= '{"video":"","thumbnail":'.'"user\/'.$getSaveNames.'"}';

                if (empty($datae['post_titlem'])){
                    $var=explode(",",$datae['post_title']);
                    $data['post_title']=$var[$key];
                }else{
                    $varen=explode(",",$datae['post_titlem']);
                    $var=explode(",",$datae['post_title']);
                    $data['post_title']=$var[$key];
                    $data['post_titlem']=$varen[$key];
                }



                $str=Db::table('auto_portal_post')->insertGetId($data);
//                dump($str);
//                return 1;
//                $idces=Session::get('idces');
                $me['post_id']=$str;
                $me['category_id']=$id;
                $me['list_order']=10000;
                $me['status']=1;
                $sto=Db::table('auto_portal_category_post')->insert($me);
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
//                echo $info->getFilename();

            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }

        }
        if ($sto==true){
            $conid=Db::table('auto_portal_category')->where('id',$id)->field('name')->find();
            $url = url('index',['name'=>$conid['name'],'id'=>$id]);
            $this->success('新增成功', $url,3);
        }else{
            echo $file->getError();
        }
//        dump($file);
    }
    public function addPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            //状态只能设置默认值。未发布、未置顶、未推荐
            $data['post']['post_status'] = 0;
            $data['post']['is_top']      = 0;
            $data['post']['recommended'] = 0;

            $post = $data['post'];

            $result = $this->validate($post, 'AdminArticle');
            if ($result !== true) {
                $this->error($result);
            }

            $portalPostModel = new PortalPostModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $data['post']['more']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                $data['post']['more']['files'] = [];
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['files'], ["url" => $fileUrl, "name" => $data['file_names'][$key]]);
                }
            }


            $portalPostModel->adminAddArticle($data['post'], $data['post']['categories']);

            $data['post']['id'] = $portalPostModel->id;
            $hookParam          = [
                'is_add'  => true,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);

//            Db::table('')
            $this->success('添加成功!', url('AdminArticle/edit', ['id' => $portalPostModel->id]));
        }

    }
    public function zhaopinaddPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            //状态只能设置默认值。未发布、未置顶、未推荐
            $data['post']['post_status'] = 0;
            $data['post']['is_top']      = 0;
            $data['post']['recommended'] = 0;

            $post = $data['post'];

            $result = $this->validate($post, 'AdminArticle');
            if ($result !== true) {
                $this->error($result);
            }

            $portalPostModel = new PortalPostModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $data['post']['more']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                $data['post']['more']['files'] = [];
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['files'], ["url" => $fileUrl, "name" => $data['file_names'][$key]]);
                }
            }


            $portalPostModel->adminAddArticle($data['post'], $data['post']['categories']);

            $data['post']['id'] = $portalPostModel->id;
            $hookParam          = [
                'is_add'  => true,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);

//            Db::table('')
            $this->success('添加成功!', url('AdminArticle/zhaopinedit', ['id' => $portalPostModel->id]));
        }

    }
    /**
     * 编辑文章
     * @adminMenu(
     *     'name'   => '编辑文章',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章',
     *     'param'  => ''
     * )
     */
    public function edit()
    {
        $content = hook_one('portal_admin_article_edit_view');

        if (!empty($content)) {
            return $content;
        }

        $id = $this->request->param('id', 0, 'intval');

        $portalPostModel = new PortalPostModel();
        $post            = $portalPostModel->where('id', $id)->find();
        $postCategories  = $post->categories()->alias('a')->column('a.name', 'a.id');
        $postCategoryIds = implode(',', array_keys($postCategories));
        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('post', $post);
        $this->assign('post_categories', $postCategories);
        $this->assign('post_category_ids', $postCategoryIds);

        return $this->fetch();
    }
    public function zhaopinedit(){
        $content = hook_one('portal_admin_article_edit_view');

        if (!empty($content)) {
            return $content;
        }

        $id = $this->request->param('id', 0, 'intval');

        $portalPostModel = new PortalPostModel();
        $post            = $portalPostModel->where('id', $id)->find();
        $postCategories  = $post->categories()->alias('a')->column('a.name', 'a.id');
        $postCategoryIds = implode(',', array_keys($postCategories));
        $db=Db::table('auto_mess_lo')->where('id',1)->find();
        $this->assign('db', $db['login']);
        $themeModel        = new ThemeModel();
        $articleThemeFiles = $themeModel->getActionThemeFiles('portal/Article/index');
        $this->assign('article_theme_files', $articleThemeFiles);
        $this->assign('post', $post);
        $this->assign('post_categories', $postCategories);
        $this->assign('post_category_ids', $postCategoryIds);

        return $this->fetch();
    }

    /**
     * 编辑文章提交
     * @adminMenu(
     *     'name'   => '编辑文章提交',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '编辑文章提交',
     *     'param'  => ''
     * )
     */
    public function editPost()
    {

        if ($this->request->isPost()) {
            $data = $this->request->param();

            //需要抹除发布、置顶、推荐的修改。
            unset($data['post']['post_status']);
            unset($data['post']['is_top']);
            unset($data['post']['recommended']);

            $post   = $data['post'];
            $result = $this->validate($post, 'AdminArticle');
            if ($result !== true) {
                $this->error($result);
            }

            $portalPostModel = new PortalPostModel();

            if (!empty($data['photo_names']) && !empty($data['photo_urls'])) {
                $data['post']['more']['photos'] = [];
                foreach ($data['photo_urls'] as $key => $url) {
                    $photoUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['photos'], ["url" => $photoUrl, "name" => $data['photo_names'][$key]]);
                }
            }

            if (!empty($data['file_names']) && !empty($data['file_urls'])) {
                $data['post']['more']['files'] = [];
                foreach ($data['file_urls'] as $key => $url) {
                    $fileUrl = cmf_asset_relative_url($url);
                    array_push($data['post']['more']['files'], ["url" => $fileUrl, "name" => $data['file_names'][$key]]);
                }
            }

            $portalPostModel->adminEditArticle($data['post'], $data['post']['categories']);

            $hookParam = [
                'is_add'  => false,
                'article' => $data['post']
            ];
            hook('portal_admin_after_save_article', $hookParam);

            $this->success('保存成功!');

        }
    }

    /**
     * 文章删除
     * @adminMenu(
     *     'name'   => '文章删除',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章删除',
     *     'param'  => ''
     * )
     */
    public function delete()
    {
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['id'])) {
            $id           = $this->request->param('id', 0, 'intval');
            $result       = $portalPostModel->where(['id' => $id])->find();
            $data         = [
                'object_id'   => $result['id'],
                'create_time' => time(),
                'table_name'  => 'portal_post',
                'name'        => $result['post_title'],
                'user_id'     => cmf_get_current_admin_id()
            ];
            $resultPortal = $portalPostModel
                ->where(['id' => $id])
                ->update(['delete_time' => time()]);
            if ($resultPortal) {
                Db::name('portal_category_post')->where(['post_id' => $id])->update(['status' => 0]);
                Db::name('portal_tag_post')->where(['post_id' => $id])->update(['status' => 0]);

                Db::name('recycleBin')->insert($data);
            }
            $this->success("删除成功！", '');

        }

        if (isset($param['ids'])) {
            $ids     = $this->request->param('ids/a');
            $recycle = $portalPostModel->where(['id' => ['in', $ids]])->select();
            $result  = $portalPostModel->where(['id' => ['in', $ids]])->update(['delete_time' => time()]);
            if ($result) {
                Db::name('portal_category_post')->where(['post_id' => ['in', $ids]])->update(['status' => 0]);
                Db::name('portal_tag_post')->where(['post_id' => ['in', $ids]])->update(['status' => 0]);
                foreach ($recycle as $value) {
                    $data = [
                        'object_id'   => $value['id'],
                        'create_time' => time(),
                        'table_name'  => 'portal_post',
                        'name'        => $value['post_title'],
                        'user_id'     => cmf_get_current_admin_id()
                    ];
                    Db::name('recycleBin')->insert($data);
                }
                $this->success("删除成功！", '');
            }
        }
    }

    /**
     * 文章发布
     * @adminMenu(
     *     'name'   => '文章发布',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章发布',
     *     'param'  => ''
     * )
     */
    public function publish()
    {
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['post_status' => 1, 'published_time' => time()]);

            $this->success("发布成功！", '');
        }

        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['post_status' => 0]);

            $this->success("取消发布成功！", '');
        }

    }

    /**
     * 文章置顶
     * @adminMenu(
     *     'name'   => '文章置顶',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章置顶',
     *     'param'  => ''
     * )
     */
    public function top()
    {
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['is_top' => 1]);

            $this->success("置顶成功！", '');

        }

        if (isset($_POST['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['is_top' => 0]);

            $this->success("取消置顶成功！", '');
        }
    }

    /**
     * 文章推荐
     * @adminMenu(
     *     'name'   => '文章推荐',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章推荐',
     *     'param'  => ''
     * )
     */
    public function recommend()
    {
        $param           = $this->request->param();
        $portalPostModel = new PortalPostModel();

        if (isset($param['ids']) && isset($param["yes"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['recommended' => 1]);

            $this->success("推荐成功！", '');

        }
        if (isset($param['ids']) && isset($param["no"])) {
            $ids = $this->request->param('ids/a');

            $portalPostModel->where(['id' => ['in', $ids]])->update(['recommended' => 0]);

            $this->success("取消推荐成功！", '');

        }
    }

    /**
     * 文章排序
     * @adminMenu(
     *     'name'   => '文章排序',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文章排序',
     *     'param'  => ''
     * )
     */
    public function listOrder()
    {
        parent::listOrders(Db::name('portal_category_post'));
        $this->success("排序更新成功！", '');
    }

    public function move()
    {

    }

    public function copy()
    {

    }

    public function liuyan(){
        $db=Db::table('auto_message')->order('lain asc')->paginate(12);
        $this->assign('db',$db);
        $this->assign('page', $db->render());
        return $this->fetch();
    }
    public function liuyanfk(){
        $id=$this->request->param('id');
        $db=Db::table('auto_message')->where('id',$id)->update(['lain'=>1]);
        if ($db){
            return 1;
        }else{
            return 0;
        }
    }
    public function liuyanfk1(){
        $id=$this->request->param('id');
        $db=Db::table('auto_message')->where('id',$id)->delete();
        if ($db){
            return 1;
        }else{
            return 0;
        }
    }
    public function zhaomo(){
        $db=Db::table('auto_messageer')->select();
        $this->assign('db',$db);
        return $this->fetch();
    }
    public function ts(){
        $db=Db::table('auto_message')->select();
        $this->assign('db',$db);
        return $this->fetch();
    }
}
