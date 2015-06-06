<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
	//构造方法
	public function __construct(){
		parent::__construct(); //一定要调用父类的构造方法
		$this->checkLogin();
	}
	//验证是否登录
	public function checkLogin(){
		//通过session来验证
		if (!$_SESSION['admin']) {
			//没有登录
			$this->error('请先登录吧',U('Login/login'));
		}
	}
}