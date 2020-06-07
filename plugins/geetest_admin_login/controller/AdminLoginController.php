<?php
// +----------------------------------------------------------------------
// | 后台登录主控制器类
// +----------------------------------------------------------------------
// | Author: xiwang6428 <xiwang6428@sina.com>
// +----------------------------------------------------------------------
namespace plugins\geetest_admin_login\controller;

use think\Db;
use cmf\controller\PluginBaseController;

class AdminLoginController extends PluginBaseController
{
	/**
	 * 登录验证
	 */
	public function doLogin()
	{
		$loginAllowed = session("__LOGIN_BY_CMF_ADMIN_PW__");
		if (empty($loginAllowed)) {
			$this->error(lang('outlaw_login'), cmf_get_root() . '/');
		}
		//极验验证
		require_once dirname(dirname(__FILE__)) . '/lib/class.geetestlib.php';
		$config = $this->getPlugin()->getConfig();
		$GtSdk = new \GeetestLib($config['captcha_id'], $config['private_key']);
		$data = [
			"user_id" => session('user_id'),
			"client_type" => "web",
			"ip_address" => get_client_ip()
		];
		$geetest_challenge = $this->request->param('geetest_challenge');
		$geetest_validate = $this->request->param('geetest_validate');
		$geetest_seccode = $this->request->param('geetest_seccode');
		if (session('gtserver') == 1) {   //服务器正常
			$result = $GtSdk->success_validate($geetest_challenge, 
				$geetest_validate, $geetest_seccode, $data);
			if (!$result) {
				 $this->error(lang('CAPTCHA_NOT_RIGHT'));
			}
		}else{  //服务器宕机,走failback模式
			$result = $GtSdk->fail_validate($geetest_challenge, 
				$geetest_validate, $geetest_seccode);
			if (!$result){
				$this->error(lang('CAPTCHA_NOT_RIGHT'));
			}
		}
		//用户名、密码验证
		$name = $this->request->param("username");
		if (empty($name)) {
			$this->error(lang('USERNAME_OR_EMAIL_EMPTY'));
		}
		$pass = $this->request->param("password");
		if (empty($pass)) {
			$this->error(lang('PASSWORD_REQUIRED'));
		}
		if (strpos($name, "@") > 0) {//邮箱登陆
			$where['user_email'] = $name;
		} else {
			$where['user_login'] = $name;
		}
	
		$result = Db::name('user')->where($where)->find();
	
		if (!empty($result) && $result['user_type'] == 1) {
			if (cmf_compare_password($pass, $result['user_pass'])) {
				$groups = Db::name('RoleUser')
				->alias("a")
				->join('__ROLE__ b', 'a.role_id =b.id')
				->where(["user_id" => $result["id"], "status" => 1])
				->value("role_id");
				if ($result["id"] != 1 && (empty($groups) || empty($result['user_status']))) {
					$this->error(lang('USE_DISABLED'));
				}
				//登入成功页面跳转
				session('ADMIN_ID', $result["id"]);
				session('name', $result["user_login"]);
				$result['last_login_ip']   = get_client_ip(0, true);
				$result['last_login_time'] = time();
				$token                     = cmf_generate_user_token($result["id"], 'web');
				if (!empty($token)) {
					session('token', $token);
				}
				Db::name('user')->update($result);
				cookie("admin_username", $name, 3600 * 24 * 30);
				session("__LOGIN_BY_CMF_ADMIN_PW__", null);
				$this->success(lang('LOGIN_SUCCESS'), url("admin/Index/index"));
			} else {
				$this->error(lang('USERNAME_OR_PASSWORD_NOT_RIGHT'));
			}
		} else {
			$this->error(lang('USERNAME_OR_PASSWORD_NOT_RIGHT'));
		}
	}
	
	/**
	 * 验证码初始化
	 */
	public function startCaptcha(){
		//error_reporting(0);
		require_once dirname(dirname(__FILE__)) . '/lib/class.geetestlib.php';
		$config = $this->getPlugin()->getConfig();
		$GtSdk = new \GeetestLib($config['captcha_id'], $config['private_key']);
		$data = [
			"user_id" => mt_rand(100000,999999),
			"client_type" => "web", 
			"ip_address" => get_client_ip()
		];
		
		$status = $GtSdk->pre_process($data, 1);
		session('gtserver', $status);
		session('user_id', $data['user_id']);
		return $GtSdk->get_response_str();
	}
}