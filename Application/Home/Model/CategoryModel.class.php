<?php
//商品分类模型
namespace Home\Model;
use Think\Model;
class CategoryModel extends Model {
	//定义一个方法，构造嵌套结构的多维数组
	public function childList($arr,$pid = 0) {
		$res = array();
		foreach ($arr as $v) {
			if ($v['parent_id'] == $pid) {
				//找到节点，接着继续找当前节点的所有子孙节点
				$v['child'] = $this->childList($arr,$v['cat_id']);
				$res[] = $v;
			}
		}
		return $res;
	}
	//定义一个方法，获取所有分类，并构造多维数组
	public function frontCats(){
		$cats = $this->select();
		return $this->childList($cats);
	}

	//给定分类id，获取其所有祖先分类信息----迭代
	public function getParents($cat_id) {
		$res = array();
		while($cat_id) {
			$cat = $this->where("cat_id=$cat_id")->find();
			$res[] = array(
				'cat_id' => $cat['cat_id'],
				'cat_name' => $cat['cat_name']
			);
			//改变条件
			$cat_id = $cat['parent_id'];
		}
		return array_reverse($res);
	}
}