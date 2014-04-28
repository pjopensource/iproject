<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class inc_mod_meal extends inc_mod_admin {
	function __construct($arr_v) {
		parent::__construct($arr_v);
		$this->admin_shop = $this->get_admin_shop();
	}
	//获取当前管理的店铺信息
	function get_admin_shop() {
		$arr_return = array("id"=>0 , "name" => "默认" , "mode" => 0);
		//是否指定店铺
		$shop_id = (int)fun_get::get("url_shop_id");
		if(empty($shop_id)) {
			$shop_id = intval(cls_session::get_cookie("admin_shop_id"));
		} else {
			cls_session::set_cookie("admin_shop_id" , $shop_id);//将当前选择保有到cookie
		}
		if(!empty($shop_id) && $shop_id>0) {
			$obj_rs = cls_obj::db()->get_one("select shop_id,shop_name,shop_mode from " . cls_config::DB_PRE . "meal_shop where shop_id='" . $shop_id . "'");
			if(!empty($obj_rs)) {
				$arr_return["id"] = $obj_rs["shop_id"];
				$arr_return["name"] = $obj_rs["shop_name"];
				$arr_return["mode"] = $obj_rs["shop_mode"];
			} else {
				$arr_return["id"] = -1;
				$arr_return["name"] = "";
			}
		} else if($shop_id == -999) {//所有
				$arr_return["id"] = -999;
				$arr_return["name"] = "所有";
		}

		return $arr_return;
	}
}