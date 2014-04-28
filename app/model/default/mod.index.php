<?php
/* KLKKDJ订餐之多店版
 * 版本号：3.3
 * 官网：http://www.klkkdj.com
 * 2013-05-06
 */
class mod_index extends inc_mod_default {

	/* 获取店铺列表 , 按经营模式返回
	 * 
	 */
	function get_shop_list($page = 1){
		$sortby = (int)fun_get::get("sortby");
		$sortval = (int)fun_get::get("sortval");
		$addprice = (int) fun_get::get("addprice");
		if(empty($sortby)) $sortby = (int)cls_config::get("index_sortby" , "view");
		if(empty($sortval)) $sortval = (int)cls_config::get("index_sortval" , "view");
		$cache_time = (int)cls_config::get("area_shoplist","cache");
		$cache_key = "area_" . $this->area_id . "_shoplist_" . $page."_".$sortby."_".$sortval . "_" . $addprice;
		$arr_return = cls_cache::get($cache_key,"area_shop",$cache_time);
		if($arr_return === null) {
			$lng_pagesize = (int)cls_config::get("index_pagesize","view");
			if(empty($lng_pagesize)) $lng_pagesize = 20;
			$obj_db = cls_obj::db();
			$arr_return = array("list" => array() , "shopid" => array() ,"sortby" => $sortby , "sortval" => $sortval );
			$s_key = fun_get::get("s_key");
			$arr_shop = $this->get_area_shop($this->area_id);
			$ids = (empty($arr_shop['id'])) ? "0" : implode("," , $arr_shop['id']);
			$arr_where = array("(shop_state>0 or shop_state=-1) and shop_isdel=0 and shop_id in(" . $ids . ")");
			//if(!empty($s_key)) $arr_where[] = $s_key;
			if($addprice == 1) $arr_where[] = "shop_addprice=0";
			$where = " where " . implode(" and " , $arr_where);
			//取收藏
			$arr_collect_id = array();
			$obj_result = $obj_db->select("select collect_for_id from " . cls_config::DB_PRE . "meal_collect where collect_user_id='" . cls_obj::get('cls_user')->uid . "' and collect_type=1");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$arr_collect_id[] = $obj_rs['collect_for_id'];
			}
			$sort = "";
			//取排序
			if($sortby == 2) {
				$sort = " order by shop_sold";
			} else if($sortby == 3) {
				$sort = " order by shop_hits";
			} else {
				$sort = " order by shop_id";
				$sortby = 1;
			}
			$sort .= ($sortval==1) ? " asc" : " desc";
			$arr_shopid = $arr_tj = array();
			$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."meal_shop" , $where , $page , $lng_pagesize);
			$obj_result = $obj_db->select("select shop_name,shop_id,shop_state,shop_mode,shop_hits,shop_dispatch_price,shop_support_score,shop_pic_small,shop_tj,shop_extend,shop_addprice from " . cls_config::DB_PRE . "meal_shop" . $where . $sort . $arr_return['pageinfo']['limit']);
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$obj_rs["shop_dispatch_price"] = (float)$obj_rs['shop_dispatch_price'];
				$obj_rs["extend"] = (!empty($obj_rs['shop_extend'])) ? unserialize($obj_rs["shop_extend"]) : array();
				$obj_rs['shop_pic_small'] = fun_get::html_url($obj_rs['shop_pic_small']);
				if(in_array($obj_rs['shop_id'] , $arr_collect_id)) {
					$obj_rs['iscollect'] = true;
				} else {
					$obj_rs['iscollect'] = false;
				}
				$arr_return['shopid'][] = $obj_rs['shop_id'];
				if(isset($arr_shop['list']['id_' . $obj_rs['shop_id']])) {
					if(!empty($arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_price'])) $obj_rs['shop_dispatch_price'] = $arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_price'];
					if(!empty($arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_time'])) $obj_rs['extend']['arrivedelay'] = $arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_time'];
				}
				$arr_return["list"][] = $obj_rs;
			}
			cls_cache::set($arr_return,$cache_key,"area_shop");
		}
		return $arr_return;
	}
	//取收藏与推荐的店铺
	function get_shop_collect_tj() {
		//收藏的店铺
		$arr_return = array("collect" => array() , "tj" => array() , 'shopid' => array() );
		$obj_db = cls_obj::db();
		$arr_collect_id = array();
		$obj_result = $obj_db->select("select collect_for_id from " . cls_config::DB_PRE . "meal_collect where collect_user_id='" . cls_obj::get('cls_user')->uid . "' and collect_type=1");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr_collect_id[] = $obj_rs['collect_for_id'];
		}
		$ids = implode("," , $arr_collect_id);
		if(!empty($ids)) {
			$where = " where (shop_state>0 or shop_state=-1) and shop_isdel=0 and shop_id in(" . $ids . ")";
			$obj_result = $obj_db->select("select shop_name,shop_id,shop_state,shop_mode,shop_hits,shop_dispatch_price,shop_support_score,shop_pic_small,shop_tj,shop_extend,shop_addprice from " . cls_config::DB_PRE . "meal_shop" . $where);
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$obj_rs["shop_dispatch_price"] = (float)$obj_rs['shop_dispatch_price'];
				$obj_rs["extend"] = (!empty($obj_rs['shop_extend'])) ? unserialize($obj_rs["shop_extend"]) : array();
				$obj_rs['shop_pic_small'] = fun_get::html_url($obj_rs['shop_pic_small']);
				$obj_rs['iscollect'] = true;
				if(isset($arr_shop['list']['id_' . $obj_rs['shop_id']])) {
					if(!empty($arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_price'])) $obj_rs['shop_dispatch_price'] = $arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_price'];
					if(!empty($arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_time'])) $obj_rs['extend']['arrivedelay'] = $arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_time'];
				}
				$arr_return['collect'][] = $obj_rs;
				$arr_return['shopid'][] = $obj_rs['shop_id'];
			}
		}
		//推荐
		$arr_shop = $this->get_area_shop($this->area_id);
		$arr_shopid = $arr_shop['id'];
		$ids = (empty($arr_shopid)) ? "0" : implode("," , $arr_shopid);
		$where = " where (shop_state>0 or shop_state=-1) and shop_isdel=0 and shop_id in(" . $ids . ")";
		$obj_result = $obj_db->select("select shop_name,shop_id,shop_state,shop_mode,shop_hits,shop_dispatch_price,shop_support_score,shop_pic_small,shop_tj,shop_extend,shop_addprice from " . cls_config::DB_PRE . "meal_shop" . $where . " and shop_tj>0");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$obj_rs["shop_dispatch_price"] = (float)$obj_rs['shop_dispatch_price'];
			$obj_rs["extend"] = (!empty($obj_rs['shop_extend'])) ? unserialize($obj_rs["shop_extend"]) : array();
			$obj_rs['shop_pic_small'] = fun_get::html_url($obj_rs['shop_pic_small']);
			if(in_array($obj_rs['shop_id'] , $arr_collect_id)) {
				$obj_rs['iscollect'] = true;
			} else {
				$obj_rs['iscollect'] = false;
			}
			if(isset($arr_shop['list']['id_' . $obj_rs['shop_id']])) {
				if(!empty($arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_price'])) $obj_rs['shop_dispatch_price'] = $arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_price'];
				if(!empty($arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_time'])) $obj_rs['extend']['arrivedelay'] = $arr_shop['list']['id_' . $obj_rs['shop_id']]['dispatch_time'];
			}
			$arr_return['shopid'][] = $obj_rs['shop_id'];
			$arr_return["tj"][] = $obj_rs;
		}
		return $arr_return;
	}
	//取店铺的活动信息
	function get_shop_act_info($arr_shopid , $is_index = false) {
		$arr_return = array();
		if(!empty($arr_shopid)) {
			$ids = (is_array($arr_shopid)) ? implode("," , $arr_shopid) : $arr_shopid;
			$obj_db = cls_obj::db();
			$arr_act = array();
			$obj_result = $obj_db->select("select act_name,act_shop_id,act_method from " . cls_config::DB_PRE . "meal_act where act_shop_id in(" . $ids . ") and act_state>0 and act_isdel=0 and act_starttime<='" . date("Y-m-d H:i") . "' and act_endtime>='" . date("Y-m-d H:i") . "' order by act_method");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$arr_return['act_' . $obj_rs['act_shop_id']][] = $obj_rs;
				$type = $obj_rs['act_method'];
				if($type==4) {
					$type = 3;
				} else if($type==6) {
					$type = 5;
				}
				if(isset($arr_act['id_' . $obj_rs['act_shop_id']]['id_'.$type])) {
					$arr_act['id_' . $obj_rs['act_shop_id']]['id_'.$type]['title'] .= "&#10;" . $obj_rs['act_name'];
				} else {
					$arr_act['id_' . $obj_rs['act_shop_id']]['id_'.$type] = array('id'=>$type , 'title' => $obj_rs['act_name']);
				}
			}
		}
		if($is_index) {
			$arr_return['list'] = $arr_return;
			$arr_return['method'] = $arr_act;
		}
		return $arr_return;
	}
	//取指定店铺的活动信息
	function get_shop_cart_act($shop_id) {
		$arr_return = array();
		$date = date("Y-m-d H:i:s");
		$i = 1;
		$arr_where = $arr_num_where = $arr_time_num_where = array();
		$obj_result = cls_obj::db()->select("select act_id,act_name,act_where,act_method,act_where_val,act_method_val from " . cls_config::DB_PRE . "meal_act where act_isdel=0 and act_state>0 and act_shop_id='" . $shop_id . "' and act_starttime<='" . $date . "' and act_endtime>='" . $date . "'");
		while($obj_rs = cls_obj::db()->fetch_array($obj_result)) {
			$obj_rs["index"] = $i;
			$i++;
			if($obj_rs['act_where']==1) {//大于指定金额
				$obj_rs['where_val'] = (float)$obj_rs['act_where_val'];
				$arr_return[] = $obj_rs;
			} else if($obj_rs['act_where']==2) {//指定时间
				$arr = explode("," , $obj_rs['act_where_val']);
				$time1 = (int)$arr[0];
				$time2 = (count($arr)>1) ? (int)$arr[1] : 0;
				$time = (int)date("Hi");
				if($time>=$time1 && $time<$time2) {
					$x = (int)substr($time,0,-2);
					$x1 = (int)substr($time2,0,-2);
					if($x == $x1) {
						$x = (int)substr($time,-2);
						$x1 = (int)substr($time2,-2);
						$obj_rs['time'] = ($x1-$x)*60*1000;
					} else {
						$obj_rs['time'] = ($x1-$x)*60*60*1000;
					}
					$arr_return[] = $obj_rs;
				}
			} else if($obj_rs['act_where']==3) {//达到指定数量
				$obj_rs['where_val'] = (int)$obj_rs['act_where_val'];
				$arr_return[] = $obj_rs;
			} else if($obj_rs['act_where']==4) {//指定时间，达到指定数量
				$arr = explode("," , $obj_rs['act_where_val']);
				$time1 = (int)$arr[0];
				$time2 = (count($arr)>1) ? (int)$arr[1] : 0;
				$obj_rs['where_val'] = $lng_num = (count($arr)>2) ? (int)$arr[2] : 0;
				$time = (int)date("Hi");
				if($time>=$time1 && $time<$time2) {
					$x = (int)substr($time,0,-2);
					$x1 = (int)substr($time2,0,-2);
					if($x == $x1) {
						$x = (int)substr($time,-2);
						$x1 = (int)substr($time2,-2);
						$obj_rs['time'] = ($x1-$x)*60*1000;
					} else {
						$obj_rs['time'] = ($x1-$x)*60*60*1000;
					}
					$arr_return[] = $obj_rs;
				}
			}
		}
		return $arr_return;
	}
	//取最近光顾过的店铺信息
	function get_shop_history($uid) {
		if(empty($uid)) return array('list'=>array() , 'shopid'=>array() );
		$obj_db = cls_obj::db();
		$arr_return = array('list'=>array() , 'shopid'=>array() );
		$arr_shopid = $arr_shop = $arr_collect_id = array();
		$obj_result = $obj_db->select("select collect_for_id from " . cls_config::DB_PRE . "meal_collect where collect_user_id='" . $uid . "' and collect_type=1");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr_collect_id[] = $obj_rs['collect_for_id'];
		}
		$obj_result = $obj_db->select("select order_shop_id,max(order_addtime) as addtime from " . cls_config::DB_PRE . "meal_order where order_user_id='" . $uid . "' group by order_shop_id order by addtime");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr_shopid[] = $obj_rs['order_shop_id'];
		}
		$ids = implode(',',$arr_shopid);
		$obj_result = $obj_db->select("select shop_name,shop_id,shop_state,shop_mode,shop_hits,shop_dispatch_price,shop_support_score,shop_pic_small,shop_tj,shop_extend from " . cls_config::DB_PRE . "meal_shop where shop_id in(" . $ids . ")");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$obj_rs["shop_dispatch_price"] = (float)$obj_rs['shop_dispatch_price'];
			$obj_rs["extend"] = (!empty($obj_rs['shop_extend'])) ? unserialize($obj_rs["shop_extend"]) : array();
			$obj_rs['shop_pic_small'] = fun_get::html_url($obj_rs['shop_pic_small']);
			if(in_array($obj_rs['shop_id'] , $arr_collect_id)) {
				$obj_rs['iscollect'] = true;
			} else {
				$obj_rs['iscollect'] = false;
			}
			$arr_shop['id_' . $obj_rs['shop_id']] = $obj_rs;
			$arr_return['shopid'][] = $obj_rs['shop_id'];
		}
		foreach($arr_shopid as $item) {
			if(isset($arr_shop['id_' . $item])) $arr_return['list'][] = $arr_shop['id_' . $item];
		}
		return $arr_return;
	}
	/* 获取指店铺信息
	 *
	 */
	function get_shopinfo($shop_id, $arr_add_fields = array()) {
		$arr = explode("," , 'shop_name,shop_id,shop_user_id,shop_extend,shop_linkname,shop_linktel,shop_tel,shop_address,shop_pic,shop_desc,shop_oneminleast,shop_dispatch_price,shop_mode,shop_support_score,shop_state,shop_addprice');
		$arr = array_merge($arr , $arr_add_fields);
		$arr = array_unique($arr);
		$fields = implode("," , $arr);
		$obj_shop = cls_obj::db()->get_one("select " . $fields . " from " . cls_config::DB_PRE . "meal_shop where shop_id='" . $shop_id . "'");
		if(!empty($obj_shop['shop_extend']))  $obj_shop["extend"] = unserialize($obj_shop["shop_extend"]);
		//评论数
		$obj_rs = cls_obj::db()->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_order_comment where comment_shop_id='" . $shop_id . "'");
		$obj_shop['commentnum'] = (empty($obj_rs)) ? 0 : $obj_rs['num'];
		//菜品数
		$obj_rs = cls_obj::db()->get_one("select count(1) as 'num' from " . cls_config::DB_PRE . "meal_menu where menu_shop_id='" . $shop_id . "' and menu_state>0 and menu_isdel=0");
		$obj_shop['menunum'] = (empty($obj_rs)) ? 0 : $obj_rs['num'];
		//是否收藏
		$obj_rs = cls_obj::db()->get_one("select collect_id from " . cls_config::DB_PRE . "meal_collect where collect_for_id='" . $shop_id . "' and collect_user_id='" . cls_obj::get("cls_user")->uid . "' and collect_type=1");
		$obj_shop['iscollect'] = (empty($obj_rs)) ? 0 : 1;
		$obj_shop['shop_pic'] = fun_get::html_url($obj_shop['shop_pic']);
		return $obj_shop;
	}
	/* 获取店铺菜品列表，按分类分组
	 * 
	 */
	function get_menu_list($shop_id , $index_group = 'price' , $sort = ''){
		if(empty($index_group)) $index_group = 'price';
		$obj_db = cls_obj::db();
		$arr_menu = $arr_cart = $arr_cart_id = array();
		$arr = $this->get_cart_info();
		if(isset($arr["shop_" . $shop_id])) {
			$arr_cart = $arr["shop_" . $shop_id]['cart'];
			$arr_cart_id = $arr["shop_" . $shop_id]['ids'];
		}
		$arr_return = array("list"=>array(),"tj"=>array(),'price'=>array() , "group" => array());
		$arr_group_id = $arr_price = $arr_price_list = array();
		$arr_group_id = array();
		$where = $this->get_menu_today_where($shop_id);
		//当前星期值
		$weekday = date("w");
		$day = (int)date("d");
		if(!empty($sort)) {
			$sortby = $sort;
		} else {
			$sortby = "b.group_sort,a.menu_sort";
		}
		$obj_result = $obj_db->select("select menu_id,menu_type,menu_intro,menu_group_id,menu_title,menu_price,menu_comment_num,menu_sold,menu_tj,menu_pic,menu_pic_small,menu_num,menu_attribute,menu_mode,menu_holiday,menu_weekday,menu_mode,menu_weekday,group_name,menu_date,menu_sold_time,menu_sold_today from " . cls_config::DB_PRE . "meal_menu a left join " . cls_config::DB_PRE . "meal_menu_group b on a.menu_group_id=b.group_id " . $where ." order by " . $sortby);
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			//如果是按星期上，不在范围内就排除
			if($obj_rs["menu_mode"]==1 && !stristr("," . $obj_rs["menu_weekday"] . "," , ",".$weekday.",")) continue;
			//如果是按日期上，不在范围内就排除
			if($obj_rs["menu_mode"]==3 && !stristr("," . $obj_rs["menu_date"] . "," , ",".$day."," )) continue;
			if(empty($obj_rs["menu_pic_small"])) $obj_rs["menu_pic_small"] = $obj_rs["menu_pic"];
			if(empty($obj_rs["menu_pic"])) $obj_rs["menu_pic"] = $obj_rs["menu_pic_small"];
			$obj_rs["menu_pic_small"] = fun_get::html_url($obj_rs["menu_pic_small"]);
			$obj_rs["menu_pic"] = fun_get::html_url($obj_rs["menu_pic"]);
			$arr = explode("." , $obj_rs["menu_price"]);
			$obj_rs["price_int"] = $arr[0];
			$obj_rs["price_float"] = $arr[1];
			//当前数量
			if(empty($obj_rs['menu_num'])) {
				$obj_rs['num'] = 9999;
			} else {
				$obj_rs['num'] = ($obj_rs['menu_sold_time']> strtotime(date("Y-m-d")) ) ? $obj_rs['menu_num']-$obj_rs['menu_sold_today'] : $obj_rs['menu_num'];
			}
			if(empty($sort)) {
				if($index_group == 'group') {
					$arr_return["list"]["group_" . $obj_rs["menu_group_id"]]["list"][] = $obj_rs;
				} else {
					$arr_price_list[$obj_rs["menu_price"]][] = $obj_rs;
				}
				//初始购物车时用到
				if(in_array($obj_rs["menu_id"] , $arr_cart_id)) $arr_menu["id_". $obj_rs["menu_id"]] = $obj_rs;
				if(!in_array( $obj_rs["menu_group_id"] , $arr_group_id) ) {
					if($index_group == 'group') {
						$arr_return["list"]["group_" . $obj_rs["menu_group_id"]]["id"] = $obj_rs["menu_group_id"];
						$arr_return["list"]["group_" . $obj_rs["menu_group_id"]]["name"] = $obj_rs["group_name"];
					}
					$arr_group_id[] = $obj_rs["menu_group_id"];
					$arr_return['group'][] = array("id"=>$obj_rs['menu_group_id'] , "name" => $obj_rs['group_name']);
				}
				if(!empty($obj_rs['menu_tj'])) {
					$arr_return['tj'][] = $obj_rs;
				}
			} else {
				$arr_return["list"][] = $obj_rs;
			}
		}
		if($index_group == 'price' && empty($sort)) {
			krsort($arr_price_list);
			foreach($arr_price_list as $item => $key) {
				$arr_return["list"]["group_" . $item]["id"] = $item;
				$arr_return["list"]["group_" . $item]["name"] = "￥" . $item;
				$arr_return["list"]["group_" . $item]["list"] = $key;
				if(!in_array( $item , $arr_price) ) {
					$arr_return['price'][] = array("id"=>$item,"name"=>"￥" . $item);
					$arr_price[] = $item;
				}
			}
		}
		$arr_return['cart_menu'] = $arr_menu;
		$arr_return['cart'] = $arr_cart;
		return $arr_return;
	}
	/* 获取购物车列表
	 *
	 */
	function get_cart_list() {
		$arr_cart = $this->get_cart_info();
		$shopids = implode(",", $arr_cart['shop_ids']);
		$arr = $arr_cart["menu_ids"];
		$menuids = array();
		foreach($arr as $item) {
			if(empty($item) || !is_numeric($item) ) continue;
			$menuids[] = $item;
		}
		$menuids = implode(",",$menuids);
		$shop_id = fun_get::get("shop_id");
		if(!empty($shop_id)) {
			(in_array($shop_id , $arr_cart['shop_ids']))? $shopids = $shop_id : $shopids = '0';
		}
		$arr_shop = $arr_menu = array();
		$obj_db = cls_obj::db();
		//取菜品信息
		if(!empty($menuids)) {
			$obj_result = $obj_db->select("select * from " . cls_config::DB_PRE . "meal_menu where menu_id in(".$menuids.")");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$obj_rs["menu_price"] = (float)$obj_rs["menu_price"];
				$arr_menu["id_" . $obj_rs["menu_id"]] = $obj_rs;
			}
		}
		$arr_dispatch = $arr_arrive = array();
		$lng_delay = 0;
		$dispatch_isnull = 0;//支持的送货地区是否为空，只要有一家店为空，则为空
		$ticket = 1;
		//取店铺信息
		if(!empty($shopids)) {
			$ii = 1;
			$obj_result = $obj_db->select("select * from " . cls_config::DB_PRE . "meal_shop where shop_id in(".$shopids.")");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				$obj_rs["index"] = $ii;
				$arr_shop["id_" . $obj_rs["shop_id"]]['info'] = $obj_rs;
				if(!empty($obj_rs["shop_extend"])) {
					$arr = unserialize($obj_rs["shop_extend"]);
					if(isset($arr["arr_arrivetime"])) {
						(count($arr_arrive)<1) ? $arr_arrive = $arr["arr_arrivetime"] : $arr_arrive = array_intersect( $arr_arrive , $arr["arr_arrivetime"]);
					}
					if(isset($arr["arrivedelay"]) && $arr["arrivedelay"] > $lng_delay ) $lng_delay = $arr["arrivedelay"];
				}
				//如果存在多店，只要一家不提供发票，则不提供发票
				if(empty($obj_rs["shop_ticket"])) $ticket = 0;
				$arr_1 = array();
				$jj = 1;
				$num = 0;
				$arr_shop["id_" . $obj_rs["shop_id"]]['price'] = 0;
				foreach($arr_cart['shop_'.$obj_rs["shop_id"]]['cart'] as $item) {
					$arr['index'] = $jj;
					$arr['ids'] = $item;
					$arr['price'] = 0;
					foreach($item as $menu) {
						$arr['price'] += (isset($arr_menu["id_" . $menu])) ? $arr_menu["id_" . $menu]['menu_price'] : 0;
					}
					$num++;
					$x = implode("_" , $item);
					if(isset($arr_1[$x])) {
						$arr_1[$x]['price'] = $arr_1[$x]['price']/$arr_1[$x]['num'];
						$arr_1[$x]['num']++;
						$arr_1[$x]['price'] = $arr_1[$x]['price']*$arr_1[$x]['num'];
					} else {
						$arr['num'] = 1;
						$arr_1[$x] = $arr;
						$jj++;
					}
					$arr_shop["id_" . $obj_rs["shop_id"]]['price'] += $arr['price'];
				}
				$arr_shop["id_" . $obj_rs["shop_id"]]['cart'] = $arr_1;
				$arr_shop["id_" . $obj_rs["shop_id"]]['num'] = $num;
				$ii++;
			}
		}
		//发票
		$arr_ticket = array("0"=>"暂不提供");
		if($ticket) {
			$arr_ticket = cls_config::get("ticket_list","meal");
		}
		$arr_arrive = $this->get_arrive_time($arr_arrive , $lng_delay);
		$arr_return =  array("cart"=>$arr_cart , "shop"=>$arr_shop , "menu"=>$arr_menu , "shopids"=>$shopids , "arrivetime" => $arr_arrive , "ticket" => $arr_ticket);
		return $arr_return;
	}
	/* 购物车信息
	 *
	 */
	function get_cart_info() {
		//取当购物车信息
		$cart_ids = fun_get::get("cart_ids");
		if(empty($cart_ids)) $cart_ids = cls_obj::get("cls_session")->get_cookie("cart_ids");
		$arr_return = $this->format_cart($cart_ids);
		return $arr_return;
	}

	//取送餐时间
	function get_arrive_time($arr , $delay) {
		$date_i = (int)date('i');
		$lng_time = date("Hi")+$delay;
		if($date_i+$delay > 60) {
			$lng_time += 40;
		}
		$lng_time_now = date("Hi");
		$arr_new = array();
		foreach($arr as $item=>$key) {
			if($item < $lng_time) continue;
			$arr_new[$item] = $key;
		}
		return $arr_new;
	}
	//根据指定id 获取文章内容
	function get_article($id , $fid = 0) {
		$obj_db = cls_obj::db();
		if(!empty($id)) {
			$where = " where article_id='" . $id . "'";
		} else {
			$where = " where article_folder_id='" . $fid . "' order by article_id desc";
		}
		$obj_rs = $obj_db->get_one("select * from " . cls_config::DB_PRE ."article" . $where);
		if(!empty($obj_rs)) {
			$obj_rs["article_content"] = fun_get::filter($obj_rs["article_content"] , true);
		}
		return $obj_rs;
	}
	//获取地区html列表
	function get_area($shopid) {
		$arr_return = array("default" => array() , "depth" => 1);
		$obj_db = cls_obj::db();
		$obj_result = $obj_db->select("select area_id,area_name,area_pid,dispatch_area_id,area_depth,area_pids,area_val,dispatch_price,dispatch_time from " . cls_config::DB_PRE . "meal_dispatch a left join " . cls_config::DB_PRE . "sys_area b on " . $obj_db->concat("," , 'b.area_pids' , ',') . " like " . $obj_db->concat(",%" , "a.dispatch_area_id" , "%,") . " where a.dispatch_shop_id='" . $shopid . "'");
		$depth = 0;
		$this_pids = array();
		$arr_str = array();
		$pid = 0;
		$depth_min = 100;
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			if(empty($obj_rs["area_name"])) $obj_rs["area_name"] = $obj_rs["area_val"];
			if(empty($obj_rs["area_val"])) $obj_rs["area_val"] = $obj_rs["area_name"];
			if($obj_rs['area_val'] == $obj_rs['area_name']) unset($obj_rs['area_val']);

			$arr_list["id_" . $obj_rs["area_pid"]][] = $obj_rs["area_id"];
			$arr_area["id_" . $obj_rs["area_id"]] = $obj_rs;
			if($obj_rs['area_depth'] > $depth) $depth = $obj_rs['area_depth'];
			if($obj_rs['area_depth'] < $depth_min) {
				$depth_min = $obj_rs['area_depth'];
				$pid = $obj_rs['area_pid'];
			}
			$arr = explode(',',$obj_rs['area_pids']);
			if($obj_rs['dispatch_area_id'] == $obj_rs['area_id'] ) {
				if(!empty($arr)) unset($arr[count($arr)-1]);
				$ids = implode("," , $arr);
				if(!in_array($ids , $arr_str)) $arr_str[$ids] = $arr;
			}
			if($obj_rs['area_id'] == $this->area_id) {
				$this_pids = explode("," , $obj_rs['area_pids']);
			}
		}
		$arr_pids = array();
		$maxkey = 0;
		for($ii = 0; $ii<10 ; $ii++) {
			$arr = array();
			foreach($arr_str as $item => $key) {
				if(isset($key[$ii]) && !in_array($key[$ii] , $arr)) $arr[] = $key[$ii];
				if(count($key)>$maxkey) $maxkey = count($key);
			}
			if(count($arr)>1 || (isset($key[$ii]) && $pid == $key[$ii]) || $pid == 0) {
				foreach($arr_str as $item => $key) {
					for($i = $ii ; $i < count($key) ; $i++) {
						$arr_pids[] = $key[$i];
					}
				}
				$arr_pids = array_unique($arr_pids);
				break;
			} else if($maxkey == $ii+1 ) {
				$arr_pids = $arr;
				break;
			} else if(empty($arr)) {
				break;
			}
			if(isset($this_pids[$ii])) unset($this_pids[$ii]);
		}
		$ids = implode("," , $arr_pids);
		if(!empty($ids)) {
			$area_depth = 0;
			$obj_result = $obj_db->select("select area_id,area_name,area_pid,area_depth,area_pids,area_val from " . cls_config::DB_PRE . "sys_area where area_id in(" . $ids . ")");
			while($obj_rs = $obj_db->fetch_array($obj_result)) {
				if(empty($obj_rs["area_name"])) $obj_rs["area_name"] = $obj_rs["area_val"];
				if(empty($obj_rs["area_val"])) $obj_rs["area_val"] = $obj_rs["area_name"];
				if($obj_rs['area_val'] == $obj_rs['area_name']) unset($obj_rs['area_val']);
				$obj_rs['dispatch_price'] = 0;
				$obj_rs['dispatch_time'] = 0;
				$arr_list["id_" . $obj_rs["area_pid"]][] = $obj_rs["area_id"];
				$arr_area["id_" . $obj_rs["area_id"]] = $obj_rs;
				//if($obj_rs["area_pid"] == $pid) $arr_return["default"][] = $obj_rs;
				if($obj_rs['area_depth']<$area_depth) {
					$pid = $obj_rs['area_pid'];
					$area_depth = $obj_rs['area_depth'];
				}
			}
		}
		if(isset($arr_list['id_'.$pid])) {
			foreach($arr_list['id_' . $pid] as $item) {
				if(isset($arr_area["id_" . $item])) $arr_return["default"][] = $arr_area["id_" . $item];
			}
		}
		if(count($arr_return["default"])>0) {
			$arr_return["depth"] = $depth - $arr_return["default"][0]["area_depth"] + 1;
		}
		$arr_return["pids"] = implode(",", $this_pids);
		$arr_return["area"] = $arr_area;
		$arr_return["list"] = $arr_list;
		return $arr_return;
	}
	//取首页活动信息
	function get_activitie($shop_id) {
		$arr = array();
		$obj_db = cls_obj::db();
		$obj_result = $obj_db->select("select channel_name,channel_id,article_title,article_id from " . cls_config::DB_PRE . "article_channel a left join " . cls_config::DB_PRE . "article b on a.channel_id=b.article_channel_id where channel_key='activitie' and article_state>0 and article_about_id='" . $shop_id . "' and article_isdel=0 order by b.article_id limit 0,10");
		while($obj_rs = $obj_db->fetch_array($obj_result)) {
			$arr[] = $obj_rs;
		}
		return $arr;
	}
	function get_article_list($channel_id , $about_id = 0) {
		$arr_return = array("list" => array() , "pagebtns" => "");
		$obj_db = cls_obj::db();
		$lng_page = (int)fun_get::get("page");
		//取排序字段
		$arr_config_info = tab_sys_user_config::get_info("com.msg"  , $this->app_dir);
		$lng_pagesize = $arr_config_info["pagesize"];
		$str_where = " where article_isdel=0 and article_state>0 and article_channel_id='" . $channel_id . "'";
		$arr = cls_config::get_data("article_channel" , "id_" . $channel_id );
		if(!empty($about_id) || !empty($arr) && !empty($arr['channel_user_type'])) $str_where .= " and article_about_id='" . $about_id . "'";
		//取分页信息
		$arr_return["list"] = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."article" , $str_where , $lng_page , $lng_pagesize);
		$obj_result = $obj_db->select("SELECT * FROM ".cls_config::DB_PRE."article" . $str_where . " order by article_id desc" . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$arr_return["list"][] = $obj_rs;
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}
	function get_comment_list($id) {
		$lng_pagesize = 10;
		$arr_return = array("list" => array() , "pagebtns" => "");
		$obj_db = cls_obj::db();
		$lng_page = (int)fun_get::get("page");
		$str_where = " where comment_menu_id='" . $id . "'";
		//取分页信息
		$arr_val = array("好吃" => 1 , "一般" => 0 , "难吃" => -1);
		$arr_return["list"] = array();
		$arr_uid = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."meal_menu_comment" , $str_where , $lng_page , $lng_pagesize);
		$obj_result = $obj_db->select("SELECT comment_addtime,comment_val,comment_user_id FROM ".cls_config::DB_PRE."meal_menu_comment" . $str_where . " order by comment_id desc" . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$obj_rs['val'] = array_search($obj_rs['comment_val'] , $arr_val);
			$obj_rs['addtime'] = date("Y-m-d H:i" , $obj_rs['comment_addtime']);
			$obj_rs['user_name'] = '';
			$arr_uid[] = $obj_rs['comment_user_id'];
			$arr_return["list"][] = $obj_rs;
		}
		if(count($arr_uid)>0) {
			$user_info = cls_obj::get("cls_user")->get_user($arr_uid);
			$count = count($arr_return["list"]);
			for($i = 0 ; $i < $count ; $i++) {
				$arr_return["list"][$i]['user_name'] = array_search($arr_return["list"][$i]['comment_user_id'] , $user_info);
			}
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}

	function get_comment_shop_list($id) {
		$lng_pagesize = 10;
		$arr_return = array("list" => array() , "pagebtns" => "");
		$obj_db = cls_obj::db();
		$lng_page = (int)fun_get::get("page");
		$str_where = " where comment_shop_id='" . $id . "'";
		//取分页信息
		$arr_val = array("好吃" => 1 , "一般" => 0 , "难吃" => -1);
		$arr_return["list"] = array();
		$arr_uid = array();
		$arr_return["pageinfo"] = $obj_db->get_pageinfo(cls_config::DB_PRE."meal_order_comment" , $str_where , $lng_page , $lng_pagesize);
		$obj_result = $obj_db->select("SELECT comment_addtime,comment_val,comment_user_id,comment_beta FROM ".cls_config::DB_PRE."meal_order_comment" . $str_where . " order by comment_id desc" . $arr_return['pageinfo']['limit']);
		while( $obj_rs = $obj_db->fetch_array($obj_result) ) {
			$obj_rs['val'] = array_search($obj_rs['comment_val'] , $arr_val);
			$obj_rs['addtime'] = date("Y-m-d H:i" , $obj_rs['comment_addtime']);
			$obj_rs['user_name'] = '';
			$arr_uid[] = $obj_rs['comment_user_id'];
			$arr_return["list"][] = $obj_rs;
		}
		if(count($arr_uid)>0) {
			$user_info = cls_obj::get("cls_user")->get_user($arr_uid);
			$count = count($arr_return["list"]);
			for($i = 0 ; $i < $count ; $i++) {
				$arr_return["list"][$i]['user_name'] = array_search($arr_return["list"][$i]['comment_user_id'] , $user_info);
			}
		}
		$arr_return['pagebtns']   = $this->get_pagebtns($arr_return['pageinfo']);
		return $arr_return;
	}

	function get_slide_ads($id) {
		$obj_db = cls_obj::db();
		$obj_rs = $obj_db->get_one("select ads_html from " . cls_config::DB_PRE . "other_ads where ads_shop_id='" . $id . "' and ads_starttime<='" . TIME . "' and ads_endtime>='" . TIME . "' and ads_state>0");
		if(!empty($obj_rs)) {
			return fun_get::filter($obj_rs['ads_html'],true);
		} else {
			return '';
		}
	}

}