<?php
namespace plugins\file\controller; //Demo插件英文名，改成你的插件英文就行了

use cmf\controller\PluginAdminBaseController;
use think\Request;
use think\Db;

class AdminIndexController extends PluginAdminBaseController{

    protected function _initialize()
    {
        parent::_initialize();
        $adminId = cmf_get_current_admin_id();//获取后台管理员id，可判断是否登录
        if (!empty($adminId)) {
            $this->assign("admin_id", $adminId);
        } else {
            $url='http://'.$_SERVER['HTTP_HOST'];
            echo "<script language='javascript' type='text/javascript'>";
            echo "window.location.href='$url'";
            echo "</script>";
        }
    }
    /**
     * 文件管理
     * @adminMenu(
     *     'name'   => '文件管理',
     *     'parent' => 'admin/Plugin/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '文件管理,使用插件时，把不要把url模式设置为普通',
     *     'param'  => ''
     * )
     */
	public function index(){

        //获取图标数组
        $ext_path ='plugins/file/view/Img/ext/';
        $ext_file = glob($ext_path . '*.*');
        foreach ($ext_file as $k => $v) {
            $key=current(explode('.',basename($v)));
            $ext[$key]='/'.$v;
        }

        //获取目录
        $root='public/themes/simpleboot3';
        $this->assign('root',$root);
        //获取当前目录及上级目录
       //$path_root=str_replace('~','/',$root);
        $path_root=$root;

        $dir=input('get.dir');

        if(isset($dir)){

            //$path_now=$path_root.'/'.str_replace(array('~','#'),array('/','.'),$dir);
            $path_now=$path_root.'/'.$dir;

            //$back=substr($dir,0,strrpos($dir,'~'));//上级目录
            $back=substr($dir,0,strrpos($dir,'/'));
        }else{
            $path_now=$path_root;
            $back='';

        }

        $path_now=str_replace('//', '/', $path_now);
        $this->assign('back',$back);


        //获取文件列表
        $list = glob($path_now . '/*');

        if (!empty($list)) ksort($list);

        $config = $this->getPlugin()->getConfig();
        $allow_edit=explode(",",$config['file_ext']);

        //过滤文件列表

        $file_list=array();
        foreach ($list as $k => $v) {
            $v=str_replace($path_root.'/','',$v);
            //获取拓展名
            $this_ext = pathinfo($v, PATHINFO_EXTENSION);
            //没有拓展名,判断为文件夹
            if(empty($this_ext)) $this_ext = 'dir';
            //判断是否允许编辑
            if(in_array($this_ext, $allow_edit)) $file_list[$k]['is_edit']=1;
            else $file_list[$k]['is_edit']=0;

            $file_list[$k]['ext']=$this_ext;
            if(isset($ext[$this_ext])){
                $file_list[$k]['ext_img']=$ext[$this_ext] ? $ext[$this_ext] : $ext['hlp'];

            }else{
                $file_list[$k]['ext_img']=$ext['hlp'];

            }


            //$file_list[$k]['dir']=str_replace(array('/','.'),array('~','#'),$v);
            $file_list[$k]['dir']=$v;

            $file_list[$k]['name']=basename($v);

        }

        $this->assign('path_root',$path_root);
        $this->assign('path_now',$path_now);
        $this->assign('list', $file_list);
        return $this->fetch('/admin_index/index');
    }

	 public function add(){
        if(Request::instance()->isPost()){

			$post=Request::instance()->param();;
	        //判断文件类型,为空追加默认扩展名
	        $ext = pathinfo($post['title'], PATHINFO_EXTENSION);
	        if(empty($ext)) $post['title'].='.html';

	        $file=$post['path'].$post['title'];
	        $content=$post['content'];
	        //判断同名文件
	        if(is_file($file)) $this->error('同名文件存在');
	        //添加文件
			$content=html_entity_decode($content);
	        if(file_put_contents($file, $content)){
	            $this->success('文件添加成功',cmf_plugin_url('File://AdminIndex/index')."?dir=".$post['dir']);
	        }else{
	            $this->error('文件添加失败');
	        }
		}else{
            $root='public/themes/simpleboot3';
            $dir=input('get.dir');
            if(empty($dir)) $path=$root.'/';
	        else $path=$root.'/'.$dir.'/';
			$this->assign('path',$path);
            return $this->fetch();

		}

    }

	 public function edit(){
        if(Request::instance()->isPost()){
            $post=Request::instance()->param();;
	        $file=$post['file'];
	        $content=$post['content'];


	        //判断文件类型,为空追加默认扩展名
	        $ext = pathinfo($post['title_new'], PATHINFO_EXTENSION);
	        if(empty($ext)) $post['title_new'].='.html';

	        //判断是否需要重命名
	        if($post['title_new'] != $post['title']){
	            $dirname=dirname($post['file']);
	            rename($dirname.'/'.$post['title'], $dirname.'/'.$post['title_new']);
	            $file=$dirname.'/'.$post['title_new'];
	        }
	        //修改文件
	        //$back=substr($post['dir'],0,strrpos($post['dir'],'~'));
	        $back=substr($post['dir'],0,strrpos($post['dir'],'/'));
			$content=html_entity_decode($content);
	        if(file_put_contents($file, $content)){
	            $this->success('文件修改成功',cmf_plugin_url('File://AdminIndex/index')."?dir=".$back);
	        }else{
	            $this->error('文件修改失败');
	        }
		}else{
            $dir=input('get.dir');
            $root='public/themes/simpleboot3';
	        $info['file']=$root.'/'.$dir;

	        $info['title']=basename($info['file']);
	        $info['content']=file_get_contents($info['file']);
	        $this->assign('info',$info);
            return $this->fetch();
		}

    }


    public function del(){
        $dir=input('get.dir');
        $root='public/themes/simpleboot3';
        $file=$root.'/'.$dir;

        if(is_file($file)){
            if(unlink($file)){
                $this->success('删除'.basename($file).'文件成功');
            }else{
                $this->error('删除'.basename($file).'文件失败');
            }
        }

    }

	

}
