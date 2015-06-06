<?php
namespace Admin\Controller;
use Think\Controller;
class BrandController extends BaseController {
	//显示品牌
	public function index(){
		// $brands = M('brand')->order('sort_order asc')->select();
		$brandModel = M('brand');
		$count   = $brandModel->count();// 查询满足要求的总记录数
		$Page    = new \Think\Page($count,2);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		$Page->setConfig('prev',"上一页");
		$Page->setConfig('next',"下一页");
		$show    = $Page->show();// 分页显示输出
		$brands = $brandModel->order('sort_order asc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('brands',$brands);
		$this->assign('page',$show);
		$this->display();
	}

	//添加品牌
	public function add(){
		if (IS_POST) {
			// 品牌入库
			//收集表单数据
			$data['brand_name'] = I('brand_name');
			$data['url'] = I('url');
			$data['brand_desc'] = I('brand_desc');
			$data['sort_order'] = I('sort_order');
			$data['is_show'] = I('is_show');
			
			//处理上传图片
			if ($_FILES['logo']['tmp_name'] != '') {
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize  = 3145728 ;// 设置附件上传大小
				$upload->exts     = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath = "./"; //注意，一定要设置这个
				$upload->savePath  =  './Public/Uploads/'; // 设置附件上传目录
				$info  =  $upload->uploadOne($_FILES['logo']);
				if ($info) {
					// 上传成功
					$data['logo'] = $info['savepath'].$info['savename'];
				}
			}
			//调用模型完成入库
			$brandModel = D('brand');
			if ($brandModel->create($data)) {
				//通过验证，创建数据成功
				if ($brandModel->add()){ 
					$this->success('添加品牌成功',U('index'),1);
				} else {
					//失败
					$this->success('添加品牌失败');
				}
			} else {
				$this->error($brandModel->getError());
			}
			return;
		}
		// 载入添加界面
		$this->display();
	}

	//编辑
	public function edit(){
		$id = I('id',0,'int');
		if (IS_POST) {
			$data['brand_name'] = I('brand_name');
			$data['url'] = I('url');
			$data['brand_desc'] = I('brand_desc');
			$data['sort_order'] = I('sort_order');
			$data['is_show'] = I('is_show');
			$data['brand_id'] = I('brand_id');

			//判断是否有图片上传,如果上传，则删除原来图片
			if ($_FILES['logo']['tmp_name'] != '') {	
				$upload = new \Think\Upload();// 实例化上传类
				$upload->maxSize   =   3145728 ;// 设置附件上传大小    
				$upload->exts      =   array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
				$upload->rootPath = './';    
				$upload->savePath  =   './Public/Uploads/'; // 设置附件上传目录
				$info  =  $upload->uploadOne($_FILES['logo']);
				if($info) {
					//删除原来的图片
					$brand = M('brand')->find($data['brand_id']);
					unlink($brand['logo']);
					// 上传成功 获取上传文件信息         
					$data['logo'] = $info['savepath'].$info['savename'];     
				}

			}
			$brandModel = D('brand');
			if ($brandModel->create($data)) {
				if ($brandModel->save()) {
					$this->success('编辑品牌成功',U('Brand/index'),1);
				} else {
					$this->error('编辑品牌失败');
				} 
			}else {
				$this->error($brandModel->getError());
			}	 

			return;

		}
		
		$brand = M('brand')->find($id);
		$this->assign('brand',$brand);
		$this->display();
	}
	//移除
	public function delete(){
		$id = I('id',0,'int');
		$brand = M('brand')->find($id);
		if (M('brand')->delete($id)) {
			//删除的同时删除图片
			unlink($brand['logo']);
			$this->success('删除成功');
		} else {
			$this->error('删除失败');
		}
	}
}