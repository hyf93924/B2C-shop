<?php
namespace Admin\Model;
use Think\Model;
//后台管理员模型
class AdminModel extends Model {
	//验证用户名和密码
	public function checkUser($username,$password){
		$condition['admin_name'] = $username;
		$condition['password'] = md5($password);
		if ($admin = $this->where($condition)->find()) {
			//成功，保存session标识，并跳转到首页
			session('admin',$admin);
			return true;
		} else {
			return false;
		}
	}
}