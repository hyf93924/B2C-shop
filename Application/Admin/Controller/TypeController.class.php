<?php
//商品类型控制器
namespace Admin\Controller;
use Think\Controller;
class TypeController extends BaseController {

	//显示类型
	public function index(){
		$typeModel = M('goods_type');
		$count   = $typeModel->count();// 查询满足要求的总记录数
		$Page    = new \Think\Page($count,3);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->setConfig('prev',"上一页");
		$Page->setConfig('next',"下一页");
		$show    = $Page->show();// 分页显示输出
		$types = $typeModel->order('type_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('types',$types);
		$this->assign('page',$show);
		$this->display();
	}

	//添加类型
	public function add(){
		if (IS_POST) {
			//类型信息入库
			$data['type_name'] = I('type_name');
			$typeModel = D('goods_type');
			if ($typeModel->create($data)){
				if ($typeModel->add()) {
					$this->success('添加商品类型成功',U('index'),1);
				} else {
					$this->error('添加商品类型失败');
				}
			} else {
				$this->error($typeModel->getError());
			}
			return;
		}
		//载入添加类型界面
		$this->display();
	}

	//编辑类型
	public function edit(){
		$type_id = I('id',0,'int');
		if (IS_POST) {
			//更新类型
			$data['type_name'] = I('type_name');
			$data['type_id'] = I('type_id');
			$typeModel = D('goods_type');
			if ($typeModel->create($data)){
				if ($typeModel->save()) {
					$this->success('修改商品类型成功',U('index'),1);
				} else {
					$this->error('修改商品类型失败');
				}
			} else {
				$this->error($typeModel->getError());
			}
			return;
		}
		//载入编辑界面
		$type = M('goods_type')->find($type_id);
		$this->assign('type',$type);
		$this->display();
	}

	//删除类型
	public function delete(){
		$type_id = I('id',0,'int');

		if (M('goods_type')->delete($type_id)) {
			$this->success('删除商品类型成功',U('index'),1);
		} else {
			$this->error('删除商品类型失败');
		}
	}


}