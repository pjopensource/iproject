<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class mod_meal_checkout extends inc_mod_admin {
	/* 按模块查询菜单信息并返回数组列表
	 * module : 指定查询模块
	 */
	function get_pagelist() {
		$arr_where = array("money>=shop_checkout_money");
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'key' => fun_get::get("s_key"),
		);
		if( $arr_search_key['key'] != '' ) $arr_where_s[] = "(shop_name like '%" . $arr_search_key['key'] . "%' or shop_linkname like '%" . $arr_search_key['key'] . "%' or shop_tel like '%" . $arr_search_key['key'] . "%')";
		//合并查询数组
		$arr_where = array_merge($arr_where , $arr_where_s);
		if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
		$arr_return = $this->sql_list($str_where , (int)fun_get::get('page'));

		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;

		return $arr_return;
	}

	/* 实现按具体条件查询数据表，并返回分页信息
	 * str_where : sql 查询条件 , lng_page : 当前页
	 */
	function sql_list($str_where = "" , $lng_page = 1) {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		$arr_type = tab_meal_shop::get_perms("type");
		//取字段信息
		$arr_cfg_fields = tab_sys_user_config::get_fields("meal.checkout.no" , $this->app_dir , "meal");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("meal.checkout.no"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$lng_pagesize = $arr_config_info["pagesize"];
		$arr_return["sort"] = $arr_config_info["sort"];
		$arr_state = tab_meal_shop::get_perms("state");
		$date = date("Y-m-") . cls_config::get("checkout_day" , "meal" , "01");
		if( strtotime($date) > TIME ) $date = date("Y-m-d" , strtotime("-1 month" , strtotime($date)));
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo("(select sum(order_total) money,order_shop_id from " . cls_config::DB_PRE . "meal_order where order_state>0 and order_checkout_id=0 and order_day<'" . $date . "' group by order_shop_id) a left join " . cls_config::DB_PRE . "meal_shop b on a.order_shop_id=b.shop_id" , $str_where , $lng_page , $lng_pagesize);
		$obj_result = $obj_db->select("SELECT " . $arr_cfg_fields["sel"] . " FROM (select sum(order_total) money,order_shop_id from " . cls_config::DB_PRE . "meal_order where order_state>0 and order_checkout_id=0 and order_day<'" . $date . "' group by order_shop_id) a left join " . cls_config::DB_PRE . "meal_shop b on a.order_shop_id=b.shop_id" . $str_where . $sort . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}
	/* 结算列表
	 * 
	 */
	function get_list() {
		$arr_where = array();
		$arr_where_s = array();
		$str_where = '';
		$lng_issearch = 0;
		//取查询参数
		$arr_search_key = array(
			'key' => fun_get::get("s_key"),
			'addtime1' => fun_get::get("s_addtime1"),
			'addtime2' => fun_get::get("s_addtime2"),
			'date' => fun_get::get("s_date"),
		);
		if( $arr_search_key['key'] != '' ) $arr_where_s[] = "(shop_name like '%" . $arr_search_key['key'] . "%' or shop_linkname like '%" . $arr_search_key['key'] . "%' or shop_tel like '%" . $arr_search_key['key'] . "%')";
		if( fun_is::isdate( $arr_search_key['addtime1'] ) ) $arr_where_s[] = "checkout_addtime >= '" . strtotime( $arr_search_key['addtime1'] ) . "'"; 
		if( fun_is::isdate( $arr_search_key['addtime2'] ) ) $arr_where_s[] = "checkout_addtime <= '" . fun_get::endtime( $arr_search_key['addtime2'] ) . "'"; 
		if( fun_is::isdate( $arr_search_key['date'] ) ) $arr_where_s[] = "checkout_date='" . $arr_search_key['date'] . "'"; 

		//合并查询数组
		$arr_where = array_merge($arr_where , $arr_where_s);
		if(count($arr_where)>0) $str_where = " where " . implode(" and " , $arr_where);
		$arr_return = $this->sql_checkout_list($str_where , (int)fun_get::get('page'));

		if( count($arr_where_s) > 0 ) $lng_issearch = 1;
		$arr_return['issearch'] = $lng_issearch;

		return $arr_return;
	}
	/* 结算列表实现按具体条件查询数据表，并返回分页信息
	 * str_where : sql 查询条件 , lng_page : 当前页
	 */
	function sql_checkout_list($str_where = "" , $lng_page = 1) {
		$arr_return = array("list" => array());
		$obj_db = cls_obj::db();
		$arr_type = tab_meal_shop::get_perms("type");
		//取字段信息
		$arr_cfg_fields = tab_sys_user_config::get_fields("meal.checkout" , $this->app_dir , "meal");
		$arr_return['tabtd'] = $arr_cfg_fields["tabtd"];
		$arr_return['tabtit'] = $arr_cfg_fields["tabtit"];
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("meal.checkout"  , $this->app_dir);
		$sort = $arr_config_info["sortby"];
		$lng_pagesize = $arr_config_info["pagesize"];
		$arr_return["sort"] = $arr_config_info["sort"];
		//取分页信息
		$arr_uid = array();//用户id
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE . "meal_checkout a left join " . cls_config::DB_PRE . "meal_shop b on a.checkout_shop_id=b.shop_id" , $str_where , $lng_page , $lng_pagesize);
		$obj_result = $obj_db->select("SELECT " . $arr_cfg_fields["sel"] . " FROM " . cls_config::DB_PRE . "meal_checkout a left join " . cls_config::DB_PRE . "meal_shop b on a.checkout_shop_id=b.shop_id" . $str_where . $sort . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			if(isset($obj_rs["checkout_addtime"])) $obj_rs["checkout_addtime"] = date("Y-m-d H:i" , $obj_rs["checkout_addtime"]);
			if(isset($obj_rs["checkout_user_id"])) $arr_uid[] = $obj_rs["checkout_user_id"];
			$arr_return["list"][] = $obj_rs;
		}
		if(count($arr_uid)>0) {
			$arr_uid = array_unique($arr_uid);
			$str_ids = implode("," , $arr_uid);
			$obj_result = $obj_db->select("select user_id , user_name , user_realname , user_netname from " . cls_config::DB_PRE . "sys_user where user_id in(" . $str_ids. ")");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$name = $obj_rs["user_realname"];
				if(empty($name)) $name = $obj_rs["user_netname"];
				if(empty($name)) $name = $obj_rs["user_name"];
				$arr_uname["id_" . $obj_rs["user_id"]] = $name;
			}
			for($i = count($arr_return['list'])-1 ; $i >= 0 ; $i--) {
				if(isset($arr_uname["id_" . $arr_return["list"][$i]["checkout_user_id"]])) {
					$arr_return["list"][$i]["checkout_user_id"] = $arr_uname["id_" . $arr_return["list"][$i]["checkout_user_id"]];
				}
			}
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}
	//获取结算日期列表
	function get_date_list() {
		$arr_return = array();
		$obj_db = cls_obj::db();
		$obj_result = $obj_db->select("select checkout_date from " . cls_config::DB_PRE . "meal_checkout group by checkout_date order by checkout_date desc");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr_return[] = $obj_rs["checkout_date"];
		}
		return $arr_return;
	}
	//结算处理
	function on_checkout() {
		$arr_return = array();
		$shop_id = (int)fun_get::get("shop_id");
		$money = (int)fun_get::get("money");
		if(empty($shop_id) || empty($money) ) {
			$arr_return["code"] = 500;
			$arr_return["msg"] = "结算参数传递有误";
			return $arr_return;
		}
		$date = date("Y-m-") . cls_config::get("checkout_day" , "meal" , "01");
		if( strtotime($date) > TIME ) $date = date("Y-m-d" , strtotime("-1 month" , strtotime($date)));
		$obj_db = cls_obj::db_w();
		$obj_rs = $obj_db->get_one("select 1 from " . cls_config::DB_PRE . "meal_order where order_shop_id='" . $shop_id . "' and order_state=0 and order_time<'" . $date . "'");
		if(!empty($obj_rs)) {
			$arr_return["code"] = 500;
			$arr_return["msg"] = $date . " 之前还有未处理的订单，请处理后再来结算";
			return $arr_return;
		}
		//取结款金额
		$obj_order = $obj_db->get_one("select sum(order_total) money from " . cls_config::DB_PRE . "meal_order where order_state>0 and order_checkout_id=0 and order_day<'" . $date . "' and order_shop_id='" . $shop_id . "'");
		if(empty($obj_order) || empty($obj_order["money"]) ) {
			$arr_return["code"] = 500;
			$arr_return["msg"] = "未查询到可结算金额";
			return $arr_return;
		}
		if( $money != $obj_order["money"] ) {
			$arr_return["code"] = 500;
			$arr_return["msg"] = "结算金额有变动，请刷新页面，重新点击结算";
			return $arr_return;
		}
		//开始事务
		$obj_db->begin("checkout");
		$arr_fields = array("checkout_shop_id" => $shop_id , "checkout_money" => $money , "checkout_date" => $date , "checkout_user_id" => cls_obj::get("cls_user")->uid);
		$arr = tab_meal_checkout::on_save($arr_fields);
		if($arr["code"] == 0 && $arr["id"]>0) {
			$arr_1 = $obj_db->on_update(cls_config::DB_PRE . "meal_order" , array("order_checkout_id" => $arr["id"]) , "order_state>0 and order_checkout_id=0 and order_day<'" . $date . "' and order_shop_id='" . $shop_id . "'");
			if($arr_1["code"] == 0) {
				$obj_db->commit("checkout");
			} else {
				$obj_db->rollback("checkout");
			}
		} else {
			$obj_db->rollback("checkout");
			return $arr;
		}
		return array("code" => 0 , "msg"=>"结算成功");
	}
}