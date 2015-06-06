<?php
//商品分类模型
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model{
	//自动验证
	protected $_validate = array(
		array('cat_name','require','商品分类名称不能为空'),
	);

	//定义一个方法，获取树状的分类信息
	public function catTree(){
		$cats = $this->select();
		return $this->tree($cats);
	}

	//定义一个方法，对给定的数组，递归形成树
	public function tree($arr,$pid = 0,$level = 0) {
		static $tree = array();
		foreach ($arr as $v) {
			if ($v['parent_id'] == $pid) {
				//说明找到，保存
				$v['level'] = $level; //保存当前分类的所在层级
				$tree[] = $v;
				//继续找
				$this->tree($arr,$v['cat_id'],$level + 1);
			}
		}
		return $tree;
	}

	//给定一个分类，找其后代分类的cat_id，包括它自己
	public function getSubIds($cat_id){
		$cats = $this->select();
		$list = $this->tree($cats,$cat_id);
		$res = array();
		foreach ($list as $v) {
			$res[] = $v['cat_id'];
		}
		//把cat_id追加到数组
		$res[] = $cat_id;
		return $res;
	}
}