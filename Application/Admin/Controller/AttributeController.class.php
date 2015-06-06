<?php
namespace Admin\Controller;
use Think\Controller;
class AttributeController extends BaseController {
		
	//显示属性
	public function index(){
		$type_id = I('id',0,'int');
		$condition['type_id'] = $type_id;
		$count   = M('attribute')->where($condition)->count();// 查询满足要求的总记录数
		$Page    = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->setConfig('prev',"上一页");
		$Page->setConfig('next',"下一页");
		$show    = $Page->show();// 分页显示输出
		$attrs = D('attribute')->where($condition)->limit($Page->firstRow.','.$Page->listRows)->relation(true)->select();
		$this->assign('page',$show);
		$this->assign('attrs',$attrs);
		//获取所有的商品类型，并分配到模板
		$types = M('goods_type')->select();
		$this->assign('types',$types);
		$this->assign('type_id',$type_id);
		$this->display();
	}	

	//添加属性
	public function add(){
		if (IS_POST) {
			//入库
			$data['attr_name'] = I('attr_name');
			$data['type_id'] = I('type_id');
			$data['attr_type'] = I('attr_type');
			$data['attr_input_type'] = I('attr_input_type');
			$data['attr_value'] = I('attr_value');
			$attrModel = D('attribute');
			if ($attrModel->create($data)) {
				//通过验证,则插入
				if ($attrModel->add()) {
					$this->success('添加属性成功',U('index'),1);
				} else {
					$this->error('添加属性失败');
				}
			} else {
				//没有通过验证，则提示错误信息
				$this->error($attrModel->getError());
			}
			return;
		} 
		//载入添加界面 
		//获取所有的商品类型
		$types = M('goods_type')->select();
		$this->assign('types',$types);
		$this->display();
	}
	//编辑属性
	public function edit(){
		if (IS_POST) {
			//入库
			return;
		} 
		//载入编辑界面
		$this->display();
	}

	//删除属性
	public function delete(){

	}

	//获取指定类型下属性表单
	public function getAttrs(){
		$type_id = I('type_id');
		// $attrs = $type_id;
		$attrs = D('attribute')->getAttrForm($type_id);
		$this->ajaxReturn($attrs,'eval');
	}
}