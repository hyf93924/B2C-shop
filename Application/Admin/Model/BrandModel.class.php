<?php
namespace Admin\Model;
use Think\Model;
//品牌模型
class BrandModel extends Model  {
	//自动验证规则
	protected $_validate = array(
		array('brand_name','require','品牌名称不能为空'),
		array('brand_name','','该品牌已经存在',0,'unique',1),
	);
}