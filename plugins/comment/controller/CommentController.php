<?php
// +----------------------------------------------------------------------
// | Author: heizai <876555425@qq.com>
// +----------------------------------------------------------------------
namespace plugins\comment\controller;

use app\portal\model\PortalPostModel;
use app\user\model\CommentModel;
use cmf\controller\PluginBaseController;
use plugins\comment\model\PluginCommentModel;
use think\Db;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\Session;

class CommentController extends PluginBaseController
{
    // 提交
    public function add()
    {

        $user_id = cmf_get_current_user_id();
        $data = $this->request->post();
        $result = $this->validate($data, "Post");
        if ($result !== true) {
            $this->error($result);
        }

        $session_name = $data["table_name"] . '_' . trim($data["object_id"]) . '_'
            . trim($user_id) . '_' . trim($data["to_user_id"]);
        $config = $this->getPlugin()->getConfig();
        if ($config['anonymous'] == 0 && $user_id == 0) {
            return $this->error("登陆后才可以评论！");
        }
        if ($config['captcha'] == 1 && !isset($data["verify"])) {
            return $this->error("验证码不能为空");
        }

        if ($config['time'] > 0 && $config['time'] * 60 + session($session_name) > time()) {

            return $this->error("不能频繁对同一内容评论！");
        }

        if ($config['captcha'] == 1 && !cmf_captcha_check($data['verify'])) {
            $this->error("验证码错误！");
        }

        if (!isset($data["content"])) {
            return $this->error("评论内容不能为空!");
        }
        $content = trim(cmf_replace_content_file_url(htmlspecialchars_decode($data["content"])));
        if (mb_strlen(strip_tags($content)) < 10) {
            return $this->error("评论内容太短！");
        }

        $comment = array(
            "parent_id" => $data["parent_id"],
            "to_user_id" => $data["to_user_id"],
            "object_id" => $data["object_id"],
            "create_time" => time(),
            "type" => 0,
            "table_name" => $data["table_name"],
            "url" => base64_decode($data["url"]),
            "content" => $data["content"]
        );

        if ($user_id > 0 && $data["to_user_id"] == $user_id) {
            return $this->error("不能评论自己的内容！");
        }


        if ($user_id > 0) {
            $user = cmf_get_current_user();
            $comment['full_name'] = $user['user_nickname'];
            $comment['email'] = $user['user_email'];
            $comment['user_id'] = $user_id;
            $comment['type'] = 1;
        }

        $comment['status'] = $config['verify'];


        $model = new PluginCommentModel();
        $res = $model->insert($comment);
        if ($res > 0) {
            if ($config['time'] > 0) {
                session($session_name, time());
            }

            //comment_count 评论数量
            $where = ["object_id" => $data['object_id'], 'delete_time' => 0];
            $count = $model->where($where)->count("id");

            $PORTAL = new PortalPostModel();
            $PORTAL->save(['comment_count' => $count], ['id' => $data["object_id"]]);

            return $this->success("评论成功！" . ($config['verify'] == 0 ? "\n待管理员审核通过后才会显示。" : ""));
        } else {
            return $this->error("评论失败！");
        }
    }

    // 赞
    public function dolike()
    {
        $id = $this->request->param("id", 0, "intval");
        $model = new PluginCommentModel();
        $where['id'] = $id;
        $session_name = 'comment_dolike_' . $id;
        $config = $this->getPlugin()->getConfig();
        if ($config['time'] > 0 && $config['time'] * 60 + session($session_name) > time()) {
            return $this->error("不能频繁对同一内容点赞！");
        }
        try {
            $comment = $model->find($where)->toArray();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        if ($comment) {
            $data['like_count'] = $comment['like_count'] + 1;
            $res = $model->save($data, $where);
        }
        if ($res) {
            session($session_name, time());
            $this->success("操作成功！");
        } else {
            $this->error("操作失败！");
        }

    }

    // 踩
    public function dounlike()
    {
        $id = $this->request->param("id", 0, "intval");
        $model = new PluginCommentModel();
        $where['id'] = $id;

        $session_name = 'comment_dounlike_' . $id;
        $config = $this->getPlugin()->getConfig();
        if ($config['time'] > 0 && $config['time'] * 60 + session($session_name) > time()) {
            return $this->error("不能频繁踩一内容！");
        }

        try {
            $comment = $model->find($where)->toArray();
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
        if ($comment) {
            $data['dislike_count'] = $comment['dislike_count'] + 1;
            $res = $model->save($data, $where);
        }
        if ($res) {
            session($session_name, time());
            $this->success("操作成功！");
        } else {
            $this->error("操作失败！");
        }

    }

}
