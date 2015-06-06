<?php
namespace Home\Controller;
use Think\Controller;
class GoodsController extends BaseController{

	//获取商品详情
	public function index(){
		$goods_id = I('id',0,'int');
		$condition['goods_id'] = $goods_id;
		//获取商品基本信息
		$goods = M('goods')->find($goods_id);
		//获取商品的扩展信息
		$attrs = M('goods_attr')->where($condition)->select();
		$this->assign('attrs',$attrs);
		$this->assign('goods',$goods);
		$this->display();
	}
}