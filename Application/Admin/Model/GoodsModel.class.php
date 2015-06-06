<?php
namespace Admin\Model;
use Think\Model;
//商品模型
class GoodsModel extends Model  {
	
	//自动验证规则
	protected $_validate = array(
		array('goods_name','require','商品名称不能为空'),
	);
}