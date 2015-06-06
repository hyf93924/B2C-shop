<?php
//商品分类控制器
namespace Admin\Controller;
use Think\Controller;
class CategoryController extends BaseController {

	//显示分类
	public function index(){
		//获取所有的分类
		$cats = D('category')->catTree();
		$this->assign('cats',$cats);
		$this->display();
	}

	//添加分类
	public function add(){
		if (IS_POST) {
			//分类信息入库
			$data['cat_name'] = I('cat_name');
			$data['parent_id'] = I('parent_id',0,'int');
			$data['cat_desc'] = I('cat_desc');
			$data['unit'] = I('unit');
			$data['is_show'] = I('is_show');
			$data['sort_order'] = I('sort_order');
			$categoryModel = D('category');
			if ($categoryModel->create($data)) {
				//验证通过
				if ($categoryModel->add()) {
					//插入成功
					$this->success('添加分类成功',U('index'),1);
				} else {
					//插入失败
					$this->error('添加分类失败');
				}
			} else {
				//验证失败
				$this->error($categoryModel->getError());
			}

			return;
		}
		//载入添加分类界面
		//获取所有的分类
		$cats = D('category')->catTree();
		$this->assign('cats',$cats);
		$this->display();
	}

	//编辑分类
	public function edit(){
		$cat_id = I('id',0,'int');
		if (IS_POST) {
			//更新分类
			$data['cat_name'] = I('cat_name');
			$data['parent_id'] = I('parent_id',0,'int');
			$data['cat_desc'] = I('cat_desc');
			$data['unit'] = I('unit');
			$data['is_show'] = I('is_show');
			$data['sort_order'] = I('sort_order');
			$data['cat_id'] = I('cat_id');
			$categoryModel = D('category');

			$ids = $categoryModel->getSubIds($data['cat_id']);
			if (in_array($data['parent_id'], $ids)){
				$this->error('不能把当前分类及其子分类作为上级分类');
			}
			if ($categoryModel->create($data)) {
				//验证通过
				if ($categoryModel->save()) {
					//插入成功
					$this->success('修改分类成功',U('index'),1);
				} else {
					//插入失败
					$this->error('修改分类失败');
				}
			} else {
				//验证失败
				$this->error($categoryModel->getError());
			}
			
			return;
		}
		//载入编辑界面
		//获取当前分类信息
		$cat = M('category')->find($cat_id);
		//获取所有的分类信息
		$cats = D('category')->catTree();
		$this->assign('cat',$cat);
		$this->assign('cats',$cats);
		$this->display();
	}

	//删除分类
	public function delete(){
		$cat_id = I('id',0,'int');
		//如果不是叶子分类，则不允许删除
		$categoryModel = D('category');
		$ids = $categoryModel->getSubIds($cat_id);
		if (count($ids) > 1) {
			$this->error('该分类不是叶子分类，请先删除它的子分类');
		}
		if (M('category')->delete($cat_id)) {
			$this->success('删除成功',U('index'),1);
		} else {
			$this->error('删除失败');
		}
	}
}
