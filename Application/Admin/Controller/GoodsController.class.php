<?php
namespace Admin\Controller;
use Think\Controller;
class GoodsController extends BaseController {
		
	//显示商品
	public function index(){
		$this->display();
	}	

	//添加商品
	public function add(){
		if (IS_POST) {
			//入库
			$data['goods_sn'] = I('goods_sn');
			$data['goods_name'] = I('goods_name');
			$data['brand_id'] = I('brand_id');
			$data['cat_id'] = I('cat_id');
			$data['type_id'] = I('type_id');
			$data['shop_price'] = I('shop_price');
			$data['market_price'] = I('market_price');
			$data['promote_start_time'] = I('promote_start_time',0,'strtotime');
			$data['promote_end_time'] = I('promote_end_time',0,'strotime');
			$data['goods_desc'] = I('goods_desc');
			$data['goods_number'] = I('goods_number');
			$data['is_best'] = I('is_best');
			$data['is_new'] = I('is_new');
			$data['is_hot'] = I('is_hot');
			$data['is_onsale'] = I('is_onsale');
			$data['add_time'] = time();

			//图片上传，参考品牌的图片上传
			//处理上传图片
			if ($_FILES['goods_img']['tmp_name'] != '') {
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize  = 3145728 ;// 设置附件上传大小
				$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath = "./"; //注意，一定要设置这个
				$upload->savePath  =  './Public/Uploads/'; // 设置附件上传目录
				$info  =  $upload->uploadOne($_FILES['goods_img']);
				if ($info) {
					// 上传成功
					$data['goods_img'] = $info['savepath'].$info['savename'];
				}
			}

			$goodsModel = D('goods');
			if ($goodsModel->create($data)) {
				if ($goods_id = $goodsModel->add()) {
					//添加商品成功的同时，需要获取属性，并入库
					$attr_ids = I('attr_id_list');
					$attr_values = I('attr_value_list');
					$attr_prices = I('attr_price_list');
					$attrs = array();
					if (!empty($attr_ids)) {
						foreach ($attr_ids as $k => $v) {
							$attrs[] = array(
								'goods_id' => $goods_id,
								'attr_id' => $v,
								'attr_value' => $attr_values[$k],
								'attr_price' => $attr_prices[$k],
							);
						}
					}
					M('goods_attr')->addAll($attrs);

					$this->success('添加商品成功',U('index'),1);
				} else {
					$this->error('添加商品失败');
				}
			} else {
				$this->error($goodsModel->getError());
			}

			return;
		} 
		//载入添加页面
		$cats = D('category')->catTree();
		$brands = M('brand')->select();
		$types = M('goods_type')->select();
		$this->assign('cats',$cats);
		$this->assign('brands',$brands);
		$this->assign('types',$types);
		$this->display();
	}
	//编辑商品
	public function edit(){
		if (IS_POST) {
			//入库
			return;
		} 
		//载入编辑页面
		$this->display();
	}

	//删除商品
	public function delete(){

	}
}