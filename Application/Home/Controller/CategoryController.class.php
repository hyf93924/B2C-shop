<?php
namespace Home\Controller;
use Think\Controller;
class CategoryController extends BaseController {

	public function index(){
		$cat_id = I('id',0,'int');
		//面包屑导航
		//获取当前分类下(包括后代所有)所有的商品
		$parentCats = D('category')->getParents($cat_id);
		$this->assign('parentCats',$parentCats);
		// dump($parentCats);
		$this->display();
	}
}