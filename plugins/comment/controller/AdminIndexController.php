<?php
// +----------------------------------------------------------------------
// | Author: heizai <876555425@qq.com>
// +----------------------------------------------------------------------
namespace plugins\comment\controller;

use app\user\model\CommentModel;
use app\user\model\UserModel;
use cmf\controller\PluginAdminBaseController;
use plugins\comment\model\PluginCommentModel;
use think\Db;

class AdminIndexController extends PluginAdminBaseController
{

    protected function _initialize()
    {
        parent::_initialize();
        $adminId = cmf_get_current_admin_id();//获取后台管理员id，可判断是否登录
        if (!empty($adminId)) {
            $this->assign("admin_id", $adminId);
        }
    }

    /**
     * 评论列表
     * @adminMenu(
     *     'name'   => '评论列表',
     *     'parent' => 'admin/Plugin/default',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '系统评论插件',
     *     'param'  => ''
     * )
     */
    public function index()
    {
        $model = new PluginCommentModel();
        $datas = $model->order('id desc')->where(['delete_time'=>0])->paginate();
        $this->assign("datas", $datas);
        $this->assign('page', $datas->render());
        return $this->fetch('/admin_index');
    }

    /**
     * 审核
     * @return string|\think\response\Json
     */
    public function verify()
    {

        $id = $this->request->param("id", 0, "intval");
        $status = $this->request->param("status", 0, "intval");

        $resString = $status==1?"取消审核":"审核";

        $status = $status==1?0:1;

        $model = new PluginCommentModel();
        $saveData = ["status" => $status];
        $res = $model->save($saveData, ["id" => $id]);

        if ($res > 0) {
            $this->success($resString."成功！");
        } else {
            $this->error($resString."失败！");
        }

    }


    /**
     * 删除用户评论
     */
    public function delete()
    {
        $id = $this->request->param("id", 0, "intval");
        $model=new PluginCommentModel(); ;
        $where['id']         = $id;
        $data['delete_time'] = time();
        $res = $model->save($data,$where);
        if ($res) {
            $this->success("删除成功！");
        } else {
            $this->error("删除失败！");
        }
    }
    public function upid(){
       $data['content']=$this->request->param('content');
       $id=$this->request->param('id');
       $huifu_time=time();
       $db=Db::name('comment')->where('id',$id)->update(['contenth'=>$data['content'],'huifu_time'=>$huifu_time]);
       if ($db){
           return "成功";
       }else{
           return  "失败";
       }
    }

}
