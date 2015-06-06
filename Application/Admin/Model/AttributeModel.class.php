<?php
namespace Admin\Model;
use Think\Model\RelationModel;
//属性模型
class AttributeModel extends RelationModel  {
	
	//自动验证规则
	protected $_validate = array(
		array('attr_name','require','属性名称不能为空'),
	);
	//定义关联
	protected $_link = array(
		'rel1' => array(
			'mapping_type' => self::BELONGS_TO,
			'class_name' => 'goods_type',
			'foreign_key' => 'type_id',
			// 'mapping_fields' => 'type_name',
			'as_fields' => 'type_name',
		),
	);

	//获取指定类型下的所有属性，并构成表单
	public function getAttrForm($type_id){
		//获取所有的属性
		$condition['type_id'] = $type_id;
		$attrs = $this->where($condition)->select();
		//拼接字符串操作
		$res = "<table width='100%' id='attrTable'>";
		$res .= "<tbody>";
		foreach ($attrs as $attr) {
			$res .= "<tr>";
			$res .= "<td class='label'>{$attr['attr_name']}</td>";
			$res .= "<td>";
			$res .= "<input type='hidden' name='attr_id_list[]' value='".$attr['attr_id']."'>";
			switch ($attr['attr_input_type']) {
				case 0:
					# 文本框
					$res .= "<input name='attr_value_list[]' type='text' size='40'>";
					break;
				case 1:
					# 下拉列表
					$res.= "<select name='attr_value_list[]'>";
					$res .= "<option value='>请选择...</option>";
					$opts = explode(PHP_EOL, $attr['attr_value']);
					foreach ($opts as $opt) {
						$res .= "<option value='$opt'>$opt</option>";
					}
					$res .= "</select>";
					break;
				case 2 :
					#文本域
					break;
			}
			$res .= "<input type='hidden' name='attr_price_list[]' value='0'>";
			$res .= "</td>";
			$res .= "</tr>";
		}
		$res .= "</tbody>";
		$res .="</table>";

		return $res;

		/*
		<table width='100%' id='attrTable'>
		<tbody>
		<tr>
			<td class='label'>上市日期</td>
			<td>
				<input type='hidden' name='attr_id_list[]' value='172'>
					<select name='attr_value_list[]'>
						<option value='>请选择...</option>
						<option value='2008年01月'>2008年01月</option>
					</select>  
				<input type='hidden' name='attr_price_list[]' value='0'>
			</td>
		</tr>
		<tr>
			<td class='label'>存储卡格式</td>
			<td>	
				<input type='hidden' name='attr_id_list[]' value='180'>
				<input name='attr_value_list[]' type='text' value='MicroSD' size='40'>  
				<input type='hidden' name='attr_price_list[]' value='0'>
			</td>
		</tr>
		*/
	}
}